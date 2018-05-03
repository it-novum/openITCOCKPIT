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
use FreeDSx\Ldap\Exception\OperationException;
use FreeDSx\Ldap\Operation\Request\AddRequest;
use FreeDSx\Ldap\Operation\Request\CompareRequest;
use FreeDSx\Ldap\Operation\Request\DeleteRequest;
use FreeDSx\Ldap\Operation\Request\ExtendedRequest;
use FreeDSx\Ldap\Operation\Request\ModifyDnRequest;
use FreeDSx\Ldap\Operation\Request\ModifyRequest;
use FreeDSx\Ldap\Operation\Request\SearchRequest;
use FreeDSx\Ldap\Server\RequestContext;

/**
 * This allows the LDAP server to run, but rejects everything. You can extend and selectively override specific request
 * types to support what you want.
 *
 * @author Chad Sikorra <Chad.Sikorra@gmail.com>
 */
class GenericRequestHandler implements RequestHandlerInterface
{
    /**
     * {@inheritdoc}
     */
    public function add(RequestContext $context, AddRequest $add)
    {
        throw new OperationException('The add operation is not supported.');
    }

    /**
     * {@inheritdoc}
     */
    public function bind(string $username, string $password): bool
    {
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function compare(RequestContext $context, CompareRequest $compare) : bool
    {
        throw new OperationException('The compare operation is not supported.');
    }

    /**
     * {@inheritdoc}
     */
    public function delete(RequestContext $context, DeleteRequest $delete)
    {
        throw new OperationException('The delete operation is not supported.');
    }

    /**
     * {@inheritdoc}
     */
    public function extended(RequestContext $context, ExtendedRequest $extended)
    {
        throw new OperationException(sprintf('The extended operation %s is not supported.', $extended->getName()));
    }

    /**
     * {@inheritdoc}
     */
    public function modify(RequestContext $context, ModifyRequest $modify)
    {
        throw new OperationException('The modify operation is not supported.');
    }

    /**
     * {@inheritdoc}
     */
    public function modifyDn(RequestContext $context, ModifyDnRequest $modifyDn)
    {
        throw new OperationException('The modify dn operation is not supported.');
    }

    /**
     * {@inheritdoc}
     */
    public function search(RequestContext $context, SearchRequest $search): Entries
    {
        throw new OperationException('The search operation is not supported.');
    }
}
