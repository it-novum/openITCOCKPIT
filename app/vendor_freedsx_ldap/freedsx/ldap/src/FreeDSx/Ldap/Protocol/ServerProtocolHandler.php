<?php
/**
 * This file is part of the FreeDSx LDAP package.
 *
 * (c) Chad Sikorra <Chad.Sikorra@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FreeDSx\Ldap\Protocol;

use FreeDSx\Asn1\Exception\EncoderException;
use FreeDSx\Ldap\Entry\Attribute;
use FreeDSx\Ldap\Entry\Dn;
use FreeDSx\Ldap\Entry\Entries;
use FreeDSx\Ldap\Entry\Entry;
use FreeDSx\Ldap\Exception\OperationException;
use FreeDSx\Ldap\Exception\ProtocolException;
use FreeDSx\Ldap\Exception\RuntimeException;
use FreeDSx\Ldap\Operation\LdapResult;
use FreeDSx\Ldap\Operation\Request\AddRequest;
use FreeDSx\Ldap\Operation\Request\AnonBindRequest;
use FreeDSx\Ldap\Operation\Request\BindRequest;
use FreeDSx\Ldap\Operation\Request\CompareRequest;
use FreeDSx\Ldap\Operation\Request\DeleteRequest;
use FreeDSx\Ldap\Operation\Request\ExtendedRequest;
use FreeDSx\Ldap\Operation\Request\ModifyDnRequest;
use FreeDSx\Ldap\Operation\Request\ModifyRequest;
use FreeDSx\Ldap\Operation\Request\RequestInterface;
use FreeDSx\Ldap\Operation\Request\SearchRequest;
use FreeDSx\Ldap\Operation\Request\SimpleBindRequest;
use FreeDSx\Ldap\Operation\Request\UnbindRequest;
use FreeDSx\Ldap\Operation\Response\ExtendedResponse;
use FreeDSx\Ldap\Operation\Response\ResponseInterface;
use FreeDSx\Ldap\Operation\Response\SearchResultEntry;
use FreeDSx\Ldap\Operation\ResultCode;
use FreeDSx\Ldap\Protocol\Factory\ResponseFactory;
use FreeDSx\Ldap\Search\Filter\PresentFilter;
use FreeDSx\Ldap\Server\RequestHandler\GenericRequestHandler;
use FreeDSx\Ldap\Server\RequestHandler\RequestHandlerInterface;
use FreeDSx\Ldap\Server\Token\AnonToken;
use FreeDSx\Ldap\Server\Token\BindToken;
use FreeDSx\Ldap\Server\Token\TokenInterface;
use FreeDSx\Ldap\Server\RequestContext;
use FreeDSx\Ldap\Tcp\ServerMessageQueue;
use FreeDSx\Ldap\Tcp\Socket;

/**
 * Handles server-client specific protocol interactions.
 *
 * @author Chad Sikorra <Chad.Sikorra@gmail.com>
 */
class ServerProtocolHandler
{
    /**
     * @var Socket
     */
    protected $socket;

    /**
     * @var array
     */
    protected $options = [
        'allow_anonymous' => false,
        'require_authentication' => true,
        'request_handler' => null,
        'dse_alt_server' => null,
        'dse_naming_contexts' => 'dc=FreeDSx,dc=local',
        'dse_vendor_name' => 'FreeDSx',
        'dse_vendor_version' => null,
    ];

    /**
     * @var ServerMessageQueue
     */
    protected $queue;

    /**
     * @var int[]
     */
    protected $messageIds = [];

    /**
     * @var RequestHandlerInterface
     */
    protected $handler;

    /**
     * @var null|TokenInterface
     */
    protected $token;

    /**
     * @var LdapEncoder
     */
    protected $encoder;

    /**
     * @var int
     */
    protected $bufferSize = 8192;

    /**
     * @param Socket $socket
     * @param array $options
     * @param ServerMessageQueue|null $queue
     */
    public function __construct(Socket $socket, array $options = [], ServerMessageQueue $queue = null)
    {
        $this->socket = $socket;
        $this->queue = $queue ?? new ServerMessageQueue($socket);
        $this->options = array_merge($this->options, $options);
        $this->validateAndSetRequestHandler();
        $this->encoder = new LdapEncoder();
    }

    /**
     * Listens for messages from the socket and handles the responses/actions needed.
     */
    public function handle()
    {
        try {
            $this->dispatchRequests();
        // Per RFC 4511, 4.1.1 if the PDU cannot be parsed or is otherwise malformed a disconnect should be sent with a
        // result code of protocol error.
        } catch (EncoderException|ProtocolException $e) {
            $this->sendNoticeOfDisconnect('The message encoding is malformed.');
        } catch (\Exception|\Throwable $e) {
            if ($this->socket->isConnected()) {
                $this->sendNoticeOfDisconnect();
            }
        } finally {
            $this->socket->close();
        }
    }

    /**
     * @param RequestHandlerInterface $handler
     */
    public function setRequestHandler(RequestHandlerInterface $handler)
    {
        $this->handler = $handler;
    }

    /**
     * Routes requests from the message queue based off some logic. Some basic protocol specific requests are handled
     * directly:
     *
     *  - StartTLS request logic to encrypt a TCP session.
     *  - WhoAmI requests to send back the user specified in the token, if it exists.
     *  - Unbind requests to disconnect the session.
     *  - RootDSE search requests.
     *  - Check authentication requirements.
     *  - Check anon bind requirements.
     *
     * Other requests are then dispatched to the specific request handler that has been defined.
     */
    protected function dispatchRequests() : void
    {
        /** @var LdapMessageRequest $message */
        while ($message = $this->queue->getMessage()) {
            if (!$this->validate($message)) {
                continue;
            }
            $this->messageIds[] = $message->getMessageId();

            $entries = null;
            $result = null;
            $context = $this->buildContext($message);
            $request = $message->getRequest();
            try {
                if ($request instanceof ExtendedRequest && $request->getName() === ExtendedRequest::OID_WHOAMI) {
                    $result = $this->handleWhoAmI();
                } elseif ($request instanceof ExtendedRequest && $request->getName() === ExtendedRequest::OID_START_TLS) {
                    $this->handleStartTls($message);
                # The socket is closed in the finally block in handle(). Nothing to do, so just break the loop.
                } elseif ($request instanceof UnbindRequest) {
                    break;
                } elseif ($this->isRootDseSearch($request)) {
                    $result = $this->handleRootDse($message);
                } elseif (!$this->options['allow_anonymous'] && $request instanceof AnonBindRequest) {
                    $this->sendOpError($message, 'Anonymous binds are not allowed.', ResultCode::AUTH_METHOD_UNSUPPORTED);
                } elseif ($this->options['require_authentication'] && !$this->token instanceof BindToken && !$request instanceof BindRequest) {
                    $this->sendOpError($message, 'Authentication required.', ResultCode::INSUFFICIENT_ACCESS_RIGHTS);
                } else {
                    $result = $this->sendToRequestHandler($message, $context);
                }
            } catch (OperationException $exception) {
                $this->sendOpError($message, $exception->getMessage(), $exception->getCode());
            }

            if ($result) {
                $this->sendMessage(new LdapMessageResponse($message->getMessageId(), $result));
            }
        }
    }

    /**
     * This is to send the request back in chunks of 8192 bytes (or whatever the buffer size is) to lessen the amount of
     * TCP writes we need to perform.
     *
     * @param LdapMessageRequest $message
     * @param Entries $entries
     */
    protected function sendEntries(LdapMessageRequest $message, Entries $entries) : void
    {
        $buffer = '';

        foreach ($entries as $entry) {
            $buffer .= $this->encoder->encode((new LdapMessageResponse(
                $message->getMessageId(),
                new SearchResultEntry($entry)
            ))->toAsn1());

            $bufferLen = strlen($buffer);
            if ($bufferLen >= $this->bufferSize) {
                $this->socket->write(substr($buffer, 0, $this->bufferSize));
                $buffer = $bufferLen > $this->bufferSize ? substr($buffer, $this->bufferSize) : '';
            }
        }

        if (strlen($buffer) > 0) {
            $this->socket->write($buffer);
        }
    }

    /**
     * Checks that the message ID is valid. It cannot be zero or a message ID that was already used.
     *
     * @param LdapMessageRequest $message
     * @return bool
     */
    protected function validate(LdapMessageRequest $message) : bool
    {
        if ($message->getMessageId() === 0) {
            $this->sendMessage(new LdapMessageResponse(0, new ExtendedResponse(new LdapResult(
                ResultCode::PROTOCOL_ERROR,
                '',
                'The message ID 0 cannot be used in a client request.'
            ))));

            return false;
        }
        if (in_array($message->getMessageId(), $this->messageIds, true)) {
            $this->sendExtendedError(
                sprintf('The message ID %s is not valid.', $message->getMessageId()),
                ResultCode::PROTOCOL_ERROR
            );

            return false;
        }

        return true;
    }

    /**
     * @param LdapMessageRequest $message
     * @return RequestContext
     */
    protected function buildContext(LdapMessageRequest $message) : RequestContext
    {
        $token = $this->token ?? new AnonToken(null);

        return new RequestContext($message->controls(), $token);
    }

    /**
     * @param string $message
     */
    protected function sendNoticeOfDisconnect(string $message = '') : void
    {
        $this->sendMessage(new LdapMessageResponse(0, new ExtendedResponse(
            new LdapResult(ResultCode::PROTOCOL_ERROR, '', $message),
            ExtendedResponse::OID_NOTICE_OF_DISCONNECTION
        )));
    }

    /**
     * @param string $message
     * @param int $error
     */
    protected function sendExtendedError(string $message, int $error) : void
    {
        $this->sendMessage(new LdapMessageResponse(0, new ExtendedResponse(new LdapResult($error, '', $message))));
    }

    /**
     * @param LdapMessageRequest $message
     * @param string $diagnostic
     * @param int $resultCode
     */
    protected function sendOpError(LdapMessageRequest $message, string $diagnostic, int $resultCode) : void
    {
        $result = ResponseFactory::get($message->getRequest(), $resultCode, $diagnostic);

        if ($result) {
            $this->sendMessage(new LdapMessageResponse($message->getMessageId(), $result));
        } else {
            $this->sendExtendedError($diagnostic, $resultCode);
        }
    }

    /**
     * @param LdapMessageResponse $response
     */
    protected function sendMessage(LdapMessageResponse $response) : void
    {
        $this->socket->write($this->encoder->encode($response->toAsn1()));
    }

    /**
     * @param LdapMessageRequest $message
     * @param $context
     * @return ResponseInterface
     * @throws OperationException
     */
    protected function sendToRequestHandler($message, $context): ResponseInterface
    {
        $entries = null;
        $resultCode = ResultCode::SUCCESS;
        $diagnostic = '';

        $request = $message->getRequest();
        switch ($request) {
            case $request instanceof SimpleBindRequest:
                [$resultCode, $diagnostic] = $this->handleBindRequest($request);
                break;
            case $request instanceof SearchRequest:
                $entries = $this->handler->search($context, $request);
                break;
            case $request instanceof AddRequest:
                $this->handler->add($context, $request);
                break;
            case $request instanceof CompareRequest:
                $this->handler->compare($context, $request);
                break;
            case $request instanceof DeleteRequest:
                $this->handler->delete($context, $request);
                break;
            case $request instanceof ModifyDnRequest:
                $this->handler->modifyDn($context, $request);
                break;
            case $request instanceof ModifyRequest:
                $this->handler->modify($context, $request);
                break;
            case $request instanceof ExtendedRequest:
                $this->handler->extended($context, $request);
                break;
            default:
                throw new OperationException('The request operation is not supported.', ResultCode::NO_SUCH_OPERATION);
        }
        $result = ResponseFactory::get($request, $resultCode, $diagnostic);

        if ($entries) {
            $this->sendEntries($message, $entries);
        }

        return $result;
    }

    /**
     * @param RequestInterface $request
     * @return bool
     */
    protected function isRootDseSearch(RequestInterface $request) : bool
    {
        if (!$request instanceof SearchRequest) {
            return false;
        }
        $filter = $request->getFilter();

        # @todo We need to truly match this.
        return $request->getScope() === SearchRequest::SCOPE_BASE_OBJECT
            && $request->getBaseDn()->toString() === ''
            && $filter instanceof PresentFilter
            && strtolower($filter->getAttribute()) === 'objectclass';
    }

    /**
     * Constructs and sends a very basic RootDSE back to the client.
     *
     * @param LdapMessageRequest $message
     * @return ResponseInterface
     */
    protected function handleRootDse(LdapMessageRequest $message) : ResponseInterface
    {
        $entry = [
            'namingContexts' => $this->options['dse_naming_contexts'],
            'supportedExtension' => [
                ExtendedRequest::OID_WHOAMI,
            ],
            'supportedLDAPVersion' => ['3'],
            'vendorName' => $this->options['dse_vendor_name'],
        ];
        if (isset($this->options['ssl_cert'])) {
            $entry['supportedExtension'][] = ExtendedRequest::OID_START_TLS;
        }
        if (isset($this->options['vendor_version'])) {
            $entry['vendorVersion'] = $this->options['vendor_version'];
        }
        if (isset($this->options['alt_server'])) {
            $entry['altServer'] = $this->options['alt_server'];
        }

        /** @var SearchRequest $request */
        $request = $message->getRequest();
        $entry = $this->filterEntryAttributes($request, $entry);
        $this->sendEntries($message, new Entries(Entry::create('', $entry)));

        return ResponseFactory::get($message->getRequest(), ResultCode::SUCCESS);
    }

    /**
     * Filters attributes from an entry to return only what was requested.
     *
     * @param SearchRequest $request
     * @param array $entry
     * @return array
     */
    protected function filterEntryAttributes(SearchRequest $request, array $entry)
    {
        # Only return specific attributes if requested.
        if (!empty($request->getAttributes())) {
            $onlyThese = [];
            foreach ($request->getAttributes() as $attribute) {
                foreach (array_keys($entry) as $dseAttr) {
                    if ($attribute->equals(new Attribute($dseAttr))) {
                        $onlyThese[$dseAttr] = $entry[$dseAttr];
                    }
                }
            }
            $entry = $onlyThese;
        }

        # Return attributes only if requested.
        if ($request->getAttributesOnly()) {
            foreach (array_keys($entry) as $attr) {
                $entry[$attr] = [];
            }
        }

        return $entry;
    }

    /**
     * @param SimpleBindRequest $request
     * @return array
     */
    protected function handleBindRequest(SimpleBindRequest $request)
    {
        # Per RFC 4.2, a result code of protocol error must be sent back for unsupported versions.
        if ($request->getVersion() !== 3) {
            return [ResultCode::PROTOCOL_ERROR, 'Only LDAP version 3 is supported.'];
        }

        $diagnostic = '';
        if ($this->handler->bind($request->getUsername(), $request->getPassword())) {
            $this->token = new BindToken($request->getUsername(), $request->getPassword());
            $resultCode = ResultCode::SUCCESS;
        } else {
            $resultCode = ResultCode::INVALID_CREDENTIALS;
            $diagnostic = 'Invalid credentials.';
        }

        return [$resultCode, $diagnostic];
    }

    /**
     * @param LdapMessageRequest $message
     */
    protected function handleStartTls(LdapMessageRequest $message) : void
    {
        # If we don't have a SSL cert or the OpenSSL extension is not available, then we can do nothing...
        if (!isset($this->options['ssl_cert']) || !extension_loaded('openssl')) {
            $this->sendMessage(new LdapMessageResponse($message->getMessageId(), new ExtendedResponse(
                new LdapResult(ResultCode::PROTOCOL_ERROR),
                ExtendedRequest::OID_START_TLS
            )));
            return;
        }
        # If we are already encrypted, then consider this an operations error...
        if ($this->socket->isEncrypted()) {
            $this->sendMessage(new LdapMessageResponse($message->getMessageId(), new ExtendedResponse(
                new LdapResult(ResultCode::OPERATIONS_ERROR, '', 'The current LDAP session is already encrypted.'),
                ExtendedRequest::OID_START_TLS
            )));
            return;
        }
        $this->sendMessage(new LdapMessageResponse($message->getMessageId(), new ExtendedResponse(
            new LdapResult(ResultCode::SUCCESS),
            ExtendedRequest::OID_START_TLS
        )));

        $this->socket->block(true);
        $this->socket->encrypt(true);
    }

    /**
     * @return ExtendedResponse
     */
    protected function handleWhoAmI() : ExtendedResponse
    {
        $userId = '';

        if ($this->token) {
            $userId = $this->token->getUsername();
        }
        if ($userId) {
            try {
                (new Dn($userId))->toArray();
                $userId = 'dn:'.$userId;
            } catch (\Exception $e) {
                $userId = 'u:'.$userId;
            }
        }

        return new ExtendedResponse(new LdapResult(ResultCode::SUCCESS), null, $userId);
    }

    /**
     * The request handler should be constructed from a string class name. This is to make sure that each client instance
     * has its own version of the handler to avoid conflicts and potential security issues sharing a request handler.
     */
    protected function validateAndSetRequestHandler() : void
    {
        if (!isset($this->options['request_handler'])) {
            $this->handler = new GenericRequestHandler();
            return;
        }
        if (!is_string($this->options['request_handler'])) {
            throw new RuntimeException(sprintf(
                'The request handler must be a string class name, got %s.',
                gettype($this->options['request_handler'])
            ));
        }
        if (!class_exists($this->options['request_handler'])) {
            throw new RuntimeException(sprintf(
                'The request handler class does not exist: %s',
                $this->options['request_handler']
            ));
        }
        if (!is_subclass_of($this->options['request_handler'], RequestHandlerInterface::class)) {
            throw new RuntimeException(sprintf(
                'The request handler class must implement "%s"',
                RequestHandlerInterface::class
            ));
        }
        try {
            $this->handler = new $this->options['request_handler'];
        } catch (\Exception|\Throwable $e) {
            throw new RuntimeException(sprintf(
                'Unable to instantiate the request handler: "%s"',
                $e->getMessage()
            ), $e->getCode(), $e);
        }
    }
}
