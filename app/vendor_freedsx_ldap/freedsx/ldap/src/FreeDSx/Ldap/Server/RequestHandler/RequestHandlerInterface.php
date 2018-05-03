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
use FreeDSx\Ldap\Operation\Request\AddRequest;
use FreeDSx\Ldap\Operation\Request\CompareRequest;
use FreeDSx\Ldap\Operation\Request\DeleteRequest;
use FreeDSx\Ldap\Operation\Request\ExtendedRequest;
use FreeDSx\Ldap\Operation\Request\ModifyDnRequest;
use FreeDSx\Ldap\Operation\Request\ModifyRequest;
use FreeDSx\Ldap\Operation\Request\SearchRequest;
use FreeDSx\Ldap\Server\RequestContext;

/**
 * Handler methods for LDAP server requests.
 *
 * @author Chad Sikorra <Chad.Sikorra@gmail.com>
 */
interface RequestHandlerInterface
{
    /**
     * An add request.
     *
     * @param RequestContext $context
     * @param AddRequest $add
     */
    public function add(RequestContext $context, AddRequest $add);

    /**
     * A compare request. This should return true or false for whether the compare matches or not.
     *
     * @param RequestContext $context
     * @param CompareRequest $compare
     * @return bool
     */
    public function compare(RequestContext $context, CompareRequest $compare) : bool;

    /**
     * A delete request.
     *
     * @param RequestContext $context
     * @param DeleteRequest $delete
     */
    public function delete(RequestContext $context, DeleteRequest $delete);

    /**
     * An extended operation request.
     *
     * @param RequestContext $context
     * @param ExtendedRequest $extended
     */
    public function extended(RequestContext $context, ExtendedRequest $extended);

    /**
     * A request to modify an entry.
     *
     * @param RequestContext $context
     * @param ModifyRequest $modify
     */
    public function modify(RequestContext $context, ModifyRequest $modify);

    /**
     * A request to modify the DN of an entry.
     *
     * @param RequestContext $context
     * @param ModifyDnRequest $modifyDn
     */
    public function modifyDn(RequestContext $context, ModifyDnRequest $modifyDn);

    /**
     * A search request. This should return an entries collection object.
     *
     * @param RequestContext $context
     * @param SearchRequest $search
     * @return Entries
     */
    public function search(RequestContext $context, SearchRequest $search) : Entries;

    /**
     * A simple username/password bind. It should simply return true or false for whether the username and password is
     * valid. You can also throw an operations exception, which is implicitly false, and provide an additional result
     * code and diagnostics.
     *
     * @param string $username
     * @param string $password
     * @return bool
     */
    public function bind(string $username, string $password) : bool;
}
