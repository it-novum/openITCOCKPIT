<?php
/**
 * This file is part of the FreeDSx LDAP package.
 *
 * (c) Chad Sikorra <Chad.Sikorra@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FreeDSx\Ldap;

use FreeDSx\Ldap\Control\Control;
use FreeDSx\Ldap\Control\ControlBag;
use FreeDSx\Ldap\Control\Sorting\SortingControl;
use FreeDSx\Ldap\Control\Sorting\SortKey;
use FreeDSx\Ldap\Entry\Entries;
use FreeDSx\Ldap\Entry\Entry;
use FreeDSx\Ldap\Exception\OperationException;
use FreeDSx\Ldap\Operation\Request\ExtendedRequest;
use FreeDSx\Ldap\Operation\Request\RequestInterface;
use FreeDSx\Ldap\Operation\Request\SearchRequest;
use FreeDSx\Ldap\Operation\ResultCode;
use FreeDSx\Ldap\Protocol\ClientProtocolHandler;
use FreeDSx\Ldap\Protocol\LdapMessageResponse;
use FreeDSx\Ldap\Search\Paging;
use FreeDSx\Ldap\Search\Vlv;

/**
 * The LDAP client.
 *
 * @author Chad Sikorra <Chad.Sikorra@gmail.com>
 */
class LdapClient
{
    public const REFERRAL_IGNORE = 'ignore';

    public const REFERRAL_FOLLOW = 'follow';

    public const REFERRAL_THROW = 'throw';

    /**
     * @var array
     */
    protected $options = [
        'version' => 3,
        'servers' => [],
        'port' => 389,
        'base_dn' => null,
        'page_size' => 1000,
        'use_ssl' => false,
        'use_tls' => false,
        'ssl_validate_cert' => true,
        'ssl_allow_self_signed' => null,
        'ssl_ca_cert' => null,
        'ssl_peer_name' => null,
        'timeout_connect' => 3,
        'timeout_read' => 10,
        'referral' => 'throw',
        'referral_chaser' => null,
        'referral_limit' => 10,
        'logger' => null,
    ];

    /**
     * @var ClientProtocolHandler
     */
    protected $handler;

    /**
     * @param array $options
     */
    public function __construct(array $options)
    {
        $this->options = array_merge($this->options, $options);
        $this->handler = new ClientProtocolHandler($this->options);
    }

    /**
     * Bind to LDAP with a username and password.
     *
     * @param string $username
     * @param string $password
     * @return LdapMessageResponse
     * @throws \FreeDSx\Ldap\Exception\BindException
     */
    public function bind(string $username, string $password) : LdapMessageResponse
    {
        return $this->handler->send(Operations::bind($username, $password)->setVersion($this->options['version']));
    }

    /**
     * Check whether or not an entry matches a certain attribute and value.
     *
     * @param string|\FreeDSx\Ldap\Entry\Dn $dn
     * @param string $attributeName
     * @param string $value
     * @param Control[] ...$controls
     * @return bool
     */
    public function compare($dn, string $attributeName, string $value, Control ...$controls) : bool
    {
        /** @var \FreeDSx\Ldap\Operation\Response\CompareResponse $response */
        $response = $this->send(Operations::compare($dn, $attributeName, $value), ...$controls)->getResponse();

        return $response->getResultCode() === ResultCode::COMPARE_TRUE;
    }

    /**
     * Create a new entry.
     *
     * @param Entry $entry
     * @param Control[] ...$controls
     * @return LdapMessageResponse
     */
    public function create(Entry $entry, Control ...$controls) : LdapMessageResponse
    {
        $response = $this->send(Operations::add($entry), ...$controls);
        $entry->changes()->reset();

        return $response;
    }

    /**
     * Read an entry.
     *
     * @param string $entry
     * @param array $attributes
     * @param Control[] ...$controls
     * @return Entry|null
     */
    public function read(string $entry, $attributes = [], Control ...$controls) : ?Entry
    {
        try {
            return $this->search(Operations::read($entry, ...$attributes), ...$controls)->first();
        } catch (OperationException $e) {
            if ($e->getCode() === ResultCode::NO_SUCH_OBJECT) {
                return null;
            }
            throw $e;
        }
    }

    /**
     * Delete an entry.
     *
     * @param string $entry
     * @param Control[] ...$controls
     * @return LdapMessageResponse
     */
    public function delete(string $entry, Control ...$controls) : LdapMessageResponse
    {
        return $this->send(Operations::delete($entry), ...$controls);
    }

    /**
     * Update an existing entry.
     *
     * @param Entry $entry
     * @param Control[] ...$controls
     * @return LdapMessageResponse
     */
    public function update(Entry $entry, Control ...$controls) : LdapMessageResponse
    {
        $response = $this->send(Operations::modify($entry->getDn(), ...$entry->changes()), ...$controls);
        $entry->changes()->reset();

        return $response;
    }

    /**
     * Send a search response and return the entries.
     *
     * @param SearchRequest $request
     * @param Control[] ...$controls
     * @return \FreeDSx\Ldap\Entry\Entries
     */
    public function search(SearchRequest $request, Control ...$controls) : Entries
    {
        /** @var \FreeDSx\Ldap\Operation\Response\SearchResponse $response */
        $response = $this->send($request, ...$controls)->getResponse();

        return $response->getEntries();
    }

    /**
     * A helper for performing a paging based search.
     *
     * @param SearchRequest $search
     * @param int $size
     * @return Paging
     */
    public function paging(SearchRequest $search, ?int $size = null) : Paging
    {
        return new Paging($this, $search, $size ?? $this->options['page_size']);
    }

    /**
     * A helper for performing a VLV (Virtual List View) based search.
     *
     * @param SearchRequest $search
     * @param SortingControl|string|SortKey $sort
     * @param int $afterCount
     * @return Vlv
     */
    public function vlv(SearchRequest $search, $sort, int $afterCount) : Vlv
    {
        return new Vlv($this, $search, $sort, $afterCount);
    }

    /**
     * Send a request operation to LDAP.
     *
     * @param RequestInterface $request
     * @param Control[] ...$controls
     * @return LdapMessageResponse|null
     */
    public function send(RequestInterface $request, Control ...$controls) : ?LdapMessageResponse
    {
        return $this->handler->send($request, ...$controls);
    }

    /**
     * Issue a startTLS to encrypt the LDAP connection.
     *
     * @return $this
     */
    public function startTls()
    {
        $this->handler->send(Operations::extended(ExtendedRequest::OID_START_TLS));

        return $this;
    }

    /**
     * Unbind and close the LDAP TCP connection.
     *
     * @return $this
     */
    public function unbind()
    {
        $this->handler->send(Operations::unbind());

        return $this;
    }

    /**
     * Perform a whoami request and get the returned value.
     *
     * @return string
     */
    public function whoami() : ?string
    {
        /** @var \FreeDSx\Ldap\Operation\Response\ExtendedResponse $response */
        $response = $this->send(Operations::whoami())->getResponse();

        return $response->getValue();
    }

    /**
     * Access to add/set/remove/reset the controls to be used for each request. If you want request specific controls in
     * addition to these, then pass them as a parameter to the send() method.
     *
     * @return ControlBag
     */
    public function controls() : ControlBag
    {
        return $this->handler->controls();
    }

    /**
     * Get the options currently set.
     *
     * @return array
     */
    public function getOptions() : array
    {
        return $this->options;
    }

    /**
     * Merge a set of options.
     *
     * @param array $options
     * @return $this
     */
    public function setOptions(array $options)
    {
        $this->options = array_merge($this->options, $options);

        return $this;
    }

    /**
     * @param ClientProtocolHandler $handler
     * @return $this
     */
    public function setProtocolHandler(ClientProtocolHandler $handler)
    {
        $this->handler = $handler;

        return $this;
    }

    /**
     * Try to clean-up if needed.
     */
    public function __destruct()
    {
        if ($this->handler && $this->handler->getSocket() !== null && $this->handler->getSocket()->isConnected()) {
            $this->unbind();
        }
    }
}
