<?php
/**
 * This file is part of the FreeDSx LDAP package.
 *
 * (c) Chad Sikorra <Chad.Sikorra@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FreeDSx\Ldap\Server\RequestHandler;

use FreeDSx\Ldap\Entry\Entries;
use FreeDSx\Ldap\Exception\BindException;
use FreeDSx\Ldap\Exception\OperationException;
use FreeDSx\Ldap\LdapClient;
use FreeDSx\Ldap\Operation\Request\AddRequest;
use FreeDSx\Ldap\Operation\Request\CompareRequest;
use FreeDSx\Ldap\Operation\Request\DeleteRequest;
use FreeDSx\Ldap\Operation\Request\ExtendedRequest;
use FreeDSx\Ldap\Operation\Request\ModifyDnRequest;
use FreeDSx\Ldap\Operation\Request\ModifyRequest;
use FreeDSx\Ldap\Operation\Request\SearchRequest;
use FreeDSx\Ldap\Operation\ResultCode;
use FreeDSx\Ldap\Server\RequestContext;

/**
 * Proxies requests to an LDAP server and returns the response. You should extend this to add your own constructor and
 * set the LDAP client options variable.
 *
 * @author Chad Sikorra <Chad.Sikorra@gmail.com>
 */
class ProxyRequestHandler implements RequestHandlerInterface
{
    /**
     * @var LdapClient
     */
    protected $ldap;

    /**
     * @var array
     */
    protected $options = [];

    /**
     * {@inheritdoc}
     */
    public function bind(string $username, string $password): bool
    {
        try {
            return (bool) $this->ldap()->bind($username, $password);
        } catch (BindException $e) {
            throw new OperationException($e->getMessage(), $e->getCode());
        }
    }

    /**
     * {@inheritdoc}
     */
    public function modify(RequestContext $context, ModifyRequest $modify)
    {
        $this->ldap()->send($modify, ...$context->controls()->toArray());
    }

    /**
     * {@inheritdoc}
     */
    public function modifyDn(RequestContext $context, ModifyDnRequest $modifyDn)
    {
        $this->ldap()->send($modifyDn, ...$context->controls()->toArray());
    }

    /**
     * {@inheritdoc}
     */
    public function delete(RequestContext $context, DeleteRequest $delete)
    {
        $this->ldap()->send($delete, ...$context->controls()->toArray());
    }

    /**
     * {@inheritdoc}
     */
    public function add(RequestContext $context, AddRequest $add)
    {
        $this->ldap()->send($add, ...$context->controls()->toArray());
    }

    /**
     * {@inheritdoc}
     */
    public function search(RequestContext $context, SearchRequest $search): Entries
    {
        return $this->ldap()->search($search, ...$context->controls()->toArray());
    }

    /**
     * {@inheritdoc}
     */
    public function compare(RequestContext $context, CompareRequest $compare) : bool
    {
        $response = $this->ldap()->send($compare, ...$context->controls()->toArray())->getResponse();

        return $response->getResultCode() === ResultCode::COMPARE_TRUE;
    }

    /**
     * {@inheritdoc}
     */
    public function extended(RequestContext $context, ExtendedRequest $extended)
    {
        $this->ldap()->send($extended, ...$context->controls()->toArray());
    }

    /**
     * @param LdapClient $client
     */
    public function setLdapClient(LdapClient $client)
    {
        $this->ldap = $client;
    }

    /**
     * @return LdapClient
     */
    protected function ldap() : LdapClient
    {
        if (!$this->ldap) {
            $this->ldap = new LdapClient($this->options);
        }

        return $this->ldap;
    }
}
