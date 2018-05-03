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

use FreeDSx\Ldap\Control\Control;
use FreeDSx\Ldap\Control\ControlBag;
use FreeDSx\Ldap\Entry\Entries;
use FreeDSx\Ldap\Exception\BindException;
use FreeDSx\Ldap\Exception\ConnectionException;
use FreeDSx\Ldap\Exception\OperationException;
use FreeDSx\Ldap\Exception\ReferralException;
use FreeDSx\Ldap\Exception\RuntimeException;
use FreeDSx\Ldap\Exception\SkipReferralException;
use FreeDSx\Ldap\Exception\UnsolicitedNotificationException;
use FreeDSx\Ldap\LdapClient;
use FreeDSx\Ldap\LdapUrl;
use FreeDSx\Ldap\Operation\Request\DnRequestInterface;
use FreeDSx\Ldap\ReferralChaserInterface;
use FreeDSx\Ldap\Operation\LdapResult;
use FreeDSx\Ldap\Operation\Request\BindRequest;
use FreeDSx\Ldap\Operation\Request\ExtendedRequest;
use FreeDSx\Ldap\Operation\Request\RequestInterface;
use FreeDSx\Ldap\Operation\Request\SearchRequest;
use FreeDSx\Ldap\Operation\Request\UnbindRequest;
use FreeDSx\Ldap\Operation\Response\ExtendedResponse;
use FreeDSx\Ldap\Operation\Response\SearchResponse;
use FreeDSx\Ldap\Operation\Response\SearchResultDone;
use FreeDSx\Ldap\Operation\Response\SearchResultEntry;
use FreeDSx\Ldap\Operation\ResultCode;
use FreeDSx\Ldap\Protocol\Factory\ExtendedResponseFactory;
use FreeDSx\Ldap\Search\Filters;
use FreeDSx\Ldap\Tcp\ClientMessageQueue;
use FreeDSx\Ldap\Tcp\Socket;
use FreeDSx\Ldap\Tcp\SocketPool;

/**
 * Handles client specific protocol communication details.
 *
 * @author Chad Sikorra <Chad.Sikorra@gmail.com>
 */
class ClientProtocolHandler
{
    /**
     * RFC 4511, A.1. These are considered result codes that do not indicate an error condition.
     */
    protected const NON_ERROR_CODES = [
        ResultCode::SUCCESS,
        ResultCode::COMPARE_FALSE,
        ResultCode::COMPARE_TRUE,
        ResultCode::REFERRAL,
        ResultCode::SASL_BIND_IN_PROGRESS,
    ];

    /**
     * @var SocketPool
     */
    protected $pool;

    /**
     * @var Socket
     */
    protected $tcp;

    /**
     * @var ClientMessageQueue
     */
    protected $queue;

    /**
     * @var LdapEncoder
     */
    protected $encoder;

    /**
     * @var array
     */
    protected $options;

    /**
     * @var ControlBag
     */
    protected $controls;

    /**
     * @var int
     */
    protected $messageId = 0;

    /**
     * @var BindRequest
     */
    protected $bind;

    /**
     * @param array $options
     * @param ClientMessageQueue|null $queue
     * @param SocketPool|null $pool
     */
    public function __construct(array $options, ClientMessageQueue $queue = null, SocketPool $pool = null)
    {
        $this->options = $options;
        $this->encoder = new LdapEncoder();
        $this->pool = $pool ?: new SocketPool($options);
        $this->queue = $queue;
        $this->controls = new ControlBag();
    }

    /**
     * @return ControlBag
     */
    public function controls() : ControlBag
    {
        return $this->controls;
    }

    /**
     * @return null|Socket
     */
    public function getSocket() : ?Socket
    {
        return $this->tcp;
    }

    /**
     * @param RequestInterface $request
     * @param Control[] $controls
     * @return LdapMessageResponse|null
     * @throws ConnectionException
     * @throws UnsolicitedNotificationException
     */
    public function send(RequestInterface $request, Control ...$controls) : ?LdapMessageResponse
    {
        $messageTo = new LdapMessageRequest(
            ++$this->messageId,
            $request,
            ...array_merge($this->controls->toArray(), $controls)
        );

        try {
            $messageFrom = $this->handleRequest($messageTo);
        } catch (UnsolicitedNotificationException $exception) {
            if ($exception->getOid() === ExtendedResponse::OID_NOTICE_OF_DISCONNECTION) {
                $this->closeTcp();
                throw new ConnectionException(
                    sprintf('The remote server has disconnected the session. %s', $exception->getMessage()),
                    $exception->getCode()
                );
            }

            throw $exception;
        }

        if ($messageFrom && $messageFrom->getResponse()->getResultCode() === ResultCode::REFERRAL) {
            $result = $messageFrom->getResponse();
            switch ($this->options['referral']) {
                case 'throw':
                    throw new ReferralException($result->getDiagnosticMessage(), ...$result->getReferrals());
                    break;
                case 'follow':
                    return $this->handleReferral($messageTo, $messageFrom);
                    break;
                default:
                    throw new RuntimeException(sprintf(
                        'The referral option "%s" is invalid.',
                        $this->options['referral']
                    ));
            }
        }
        if ($request instanceof BindRequest) {
            $this->bind = $request;
        }
        if ($messageFrom) {
            $this->handleResponse($messageTo, $messageFrom);
        }

        return $messageFrom;
    }

    /**
     * @param LdapMessageRequest $messageTo
     * @param LdapMessageResponse $messageFrom
     * @throws BindException
     * @throws OperationException
     */
    protected function handleResponse(LdapMessageRequest $messageTo, LdapMessageResponse $messageFrom) : void
    {
        if ($messageFrom->getResponse() instanceof ExtendedResponse) {
            $this->handleExtendedResponse($messageTo, $messageFrom);
        }
        $result = $messageFrom->getResponse();

        # No action to take if there was no result, we received something that isn't an LDAP Result, or on success.
        if ($result === null || !$result instanceof LdapResult || $result->getResultCode() === ResultCode::SUCCESS) {
            return;
        }

        # The success code above should satisfy the majority of cases. This checks if the result code is really a non
        # error condition defined in RFC 4511, A.1
        if (in_array($result->getResultCode(), self::NON_ERROR_CODES)) {
            return;
        }

        if ($messageTo->getRequest() instanceof BindRequest) {
            $this->bind = null;
            throw new BindException(
                sprintf('Unable to bind to LDAP. %s', $result->getDiagnosticMessage()),
                $result->getResultCode()
            );
        }

        throw new OperationException($result->getDiagnosticMessage(), $result->getResultCode());
    }

    /**
     * @param LdapMessageRequest $messageTo
     * @return null|LdapMessageResponse
     */
    protected function handleRequest(LdapMessageRequest $messageTo) : ?LdapMessageResponse
    {
        $request = $messageTo->getRequest();
        if ($request instanceof SearchRequest && $request->getBaseDn() === null) {
            $request->setBaseDn($this->options['base_dn'] ?? null);
        }
        $this->tcp()->write($this->encoder->encode($messageTo->toAsn1()));

        $messageFrom = null;
        if ($request instanceof UnbindRequest) {
            # An unbind is like a 'quit' statement. It expects no PDU in return.
            $this->closeTcp();
        } elseif ($request instanceof ExtendedRequest && $request->getName() === ExtendedRequest::OID_START_TLS) {
            $this->handleStartTls($messageTo);
        } elseif ($request instanceof SearchRequest) {
            $messageFrom = $this->handleSearchResponse($messageTo);
        } else {
            $messageFrom = $this->queue()->getMessage($messageTo->getMessageId());
        }

        return $messageFrom;
    }

    /**
     * @param LdapMessageRequest $messageTo
     * @param LdapMessageResponse $messageFrom
     * @return LdapMessageResponse|null
     * @throws ReferralException
     */
    protected function handleReferral(LdapMessageRequest $messageTo, LdapMessageResponse $messageFrom)
    {
        $referralChaser = $this->options['referral_chaser'];
        if (!($referralChaser === null || $referralChaser instanceof ReferralChaserInterface)) {
            throw new RuntimeException(sprintf('The referral_chaser must implement "%s" or be null.', ReferralChaserInterface::class));
        }

        # Initialize a referral context to track the referrals we have already visited as well as count.
        if (!isset($this->options['_referral_context'])) {
            $this->options['_referral_context'] = new ReferralContext($this->bind);
        }

        foreach ($messageFrom->getResponse()->getReferrals() as $referral) {
            # We must skip referrals we have already visited to avoid a referral loop
            if ($this->options['_referral_context']->hasReferral($referral)) {
                continue;
            }

            $this->options['_referral_context']->addReferral($referral);
            if ($this->options['_referral_context']->count() > $this->options['referral_limit']) {
                throw new OperationException(sprintf(
                    'The referral limit of %s has been reached.',
                    $this->options['referral_limit']
                ));
            }

            $bind = $this->options['_referral_context']->getBindRequest();
            try {
                if ($referralChaser) {
                    $bind = $referralChaser->chase($messageTo, $referral, $bind);
                }
            } catch (SkipReferralException $e) {
                continue;
            }
            $options = $this->options;
            $options['servers'] = $referral->getHost() !== null ? [$referral->getHost()] : [];
            $options['port'] = $referral->getPort() ?? 389;
            $options['use_ssl'] = $referral->getUseSsl();

            # Each referral could potentially modify different aspects of the request, depending on the URL. Clone it
            # here, merge the options, then use that request to send to LDAP. This makes sure we don't accidentally mix
            # options from different referrals.
            $request = clone $messageTo->getRequest();
            $this->mergeReferralOptions($request, $referral);

            try {
                $client = $referralChaser !== null ? $referralChaser->client($options) : new LdapClient($options);

                # If we have a referral on a bind request, then do not bind initially.
                #
                # It's not clear that this should even be allowed, though RFC 4511 makes no indication that referrals
                # should not be followed on a bind request. The problem is that while we bind on a different server,
                # this client continues on with a different bind state, which seems confusing / problematic.
                if ($bind && !$messageTo->getRequest() instanceof BindRequest) {
                    $client->send($bind);
                }

                $response = $client->send($messageTo->getRequest(), ...$messageTo->controls());
                unset($this->options['_referral_context']);

                return $response;
            # Skip referrals that fail due to connection issues and not other issues
            } catch (ConnectionException $e) {
                continue;
            # If the referral encountered other referrals but exhausted them, continue to the next one.
            } catch (OperationException $e) {
                if ($e->getCode() === ResultCode::REFERRAL) {
                    continue;
                }
                # Other operation errors should bubble up, so throw it
                unset($this->options['_referral_context']);
                throw  $e;
            } catch (\Throwable $e) {
                unset($this->options['_referral_context']);
                throw $e;
            }
        }

        # If we have exhausted all referrals consider it an operation exception.
        unset($this->options['_referral_context']);
        throw new OperationException(
            $messageFrom->getResponse()->getDiagnosticMessage(),
            ResultCode::REFERRAL
        );
    }

    /**
     * @param RequestInterface $request
     * @param LdapUrl $referral
     */
    protected function mergeReferralOptions(RequestInterface $request, LdapUrl $referral) : void
    {
        if ($referral->getDn() !== null && $request instanceof SearchRequest) {
            $request->setBaseDn($referral->getDn());
        } elseif ($referral->getDn() !== null && $request instanceof DnRequestInterface) {
            $request->setDn($referral->getDn());
        }

        if ($referral->getScope() !== null && $request instanceof SearchRequest) {
            if ($referral->getScope() === LdapUrl::SCOPE_SUB) {
                $request->setScope(SearchRequest::SCOPE_WHOLE_SUBTREE);
            } elseif ($referral->getScope() === LdapUrl::SCOPE_BASE) {
                $request->setScope(SearchRequest::SCOPE_SINGLE_LEVEL);
            } else {
                $request->setScope(SearchRequest::SCOPE_BASE_OBJECT);
            }
        }

        if ($referral->getFilter() !== null && $request instanceof SearchRequest) {
            $request->setFilter(Filters::raw($referral->getFilter()));
        }
    }

    /**
     * @param LdapMessageRequest $messageTo
     * @param LdapMessageResponse $messageFrom
     */
    protected function handleExtendedResponse(LdapMessageRequest $messageTo, LdapMessageResponse $messageFrom) : void
    {
        if (!$messageTo->getRequest() instanceof ExtendedRequest) {
            return;
        }

        /** @var ExtendedRequest $request */
        $request = $messageTo->getRequest();
        if (!ExtendedResponseFactory::has($request->getName())) {
            return;
        }

        //@todo Should not have to do this. But the extended response name OID from the request is needed to complete.
        $response = ExtendedResponseFactory::get($messageFrom->getResponse()->toAsn1(), $request->getName());
        $prop = (new \ReflectionClass(LdapMessageResponse::class))->getProperty('response');
        $prop->setAccessible(true);
        $prop->setValue($messageFrom, $response);
    }

    /**
     * @param LdapMessageRequest $messageTo
     * @throws ConnectionException
     */
    protected function handleStartTls(LdapMessageRequest $messageTo) : void
    {
        $messageFrom = $this->queue()->getMessage($messageTo->getMessageId());

        if ($messageFrom->getResponse()->getResultCode() !== ResultCode::SUCCESS) {
            throw new ConnectionException(sprintf(
                'Unable to start TLS: %s',
                $messageFrom->getResponse()->getDiagnosticMessage()
            ));
        }

        $this->tcp()->encrypt(true);
    }

    /**
     * @param LdapMessage $messageTo
     * @return LdapMessageResponse
     */
    protected function handleSearchResponse(LdapMessage $messageTo) : LdapMessageResponse
    {
        $entries = [];
        $done = null;

        while ($done === null) {
            /** @var LdapMessageResponse $message */
            foreach ($this->queue()->getMessages($messageTo->getMessageId()) as $message) {
                $response = $message->getResponse();
                if ($response instanceof SearchResultEntry) {
                    $entries[] = $response->getEntry();
                } elseif ($response instanceof SearchResultDone) {
                    $done = $message;
                }
            }
        }

        /** @var LdapMessageResponse $done */
        return new LdapMessageResponse(
            $done->getMessageId(),
            new SearchResponse($done->getResponse(), new Entries(...$entries)),
            ...$done->controls()->toArray()
        );
    }

    /**
     * Closes the TCP connection and resets the message ID back to 0.
     */
    protected function closeTcp() : void
    {
        $this->tcp->close();
        $this->messageId = 0;
        $this->tcp = null;
    }

    /**
     * @return Socket
     */
    protected function tcp() : Socket
    {
        if ($this->tcp === null) {
            $this->tcp = $this->pool->connect();
        }

        return $this->tcp;
    }

    /**
     * @return ClientMessageQueue
     */
    protected function queue() : ClientMessageQueue
    {
        if ($this->queue === null) {
            $this->queue = new ClientMessageQueue($this->tcp());
        }

        return $this->queue;
    }
}
