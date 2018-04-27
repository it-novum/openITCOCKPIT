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

use FreeDSx\Ldap\Entry\Attribute;
use FreeDSx\Ldap\Entry\Dn;
use FreeDSx\Ldap\Entry\Entry;
use FreeDSx\Ldap\Entry\Change;
use FreeDSx\Ldap\Entry\Rdn;
use FreeDSx\Ldap\Operation\Request\AbandonRequest;
use FreeDSx\Ldap\Operation\Request\AddRequest;
use FreeDSx\Ldap\Operation\Request\AnonBindRequest;
use FreeDSx\Ldap\Operation\Request\CancelRequest;
use FreeDSx\Ldap\Operation\Request\CompareRequest;
use FreeDSx\Ldap\Operation\Request\DeleteRequest;
use FreeDSx\Ldap\Operation\Request\ExtendedRequest;
use FreeDSx\Ldap\Operation\Request\ModifyDnRequest;
use FreeDSx\Ldap\Operation\Request\ModifyRequest;
use FreeDSx\Ldap\Operation\Request\PasswordModifyRequest;
use FreeDSx\Ldap\Operation\Request\SearchRequest;
use FreeDSx\Ldap\Operation\Request\SimpleBindRequest;
use FreeDSx\Ldap\Operation\Request\UnbindRequest;
use FreeDSx\Ldap\Protocol\LdapMessage;
use FreeDSx\Ldap\Search\Filter\FilterInterface;
use FreeDSx\Ldap\Search\Filters;

/**
 * Provides a set of factory methods to help quickly construct different operations/requests.
 *
 * @author Chad Sikorra <Chad.Sikorra@gmail.com>
 */
class Operations
{
    /**
     * A request to abandon an ongoing operation.
     *
     * @param int $id
     * @return AbandonRequest
     */
    public static function abandon(int $id)
    {
        return new AbandonRequest($id);
    }

    /**
     * Add an entry to LDAP.
     *
     * @param Entry $entry
     * @return AddRequest
     */
    public static function add(Entry $entry)
    {
        return new AddRequest($entry);
    }

    /**
     * A simple bind request with a username and password.
     *
     * @param string $username
     * @param string $password
     * @return SimpleBindRequest
     */
    public static function bind(string $username, string $password)
    {
        return new SimpleBindRequest($username, $password);
    }

    /**
     * An anonymous bind request.
     *
     * @param string $username
     * @return AnonBindRequest
     */
    public static function bindAnonymously(string $username = '')
    {
        return new AnonBindRequest($username);
    }

    /**
     * Cancel a specific operation. Pass either the message ID or the LdapMessage object.
     *
     * @param int|LdapMessage $messageId
     * @return CancelRequest
     */
    public static function cancel($messageId)
    {
        return new CancelRequest($messageId);
    }

    /**
     * A comparison operation to check if an entry has an attribute with a certain value.
     *
     * @param string|Dn $dn
     * @param string $attributeName
     * @param string $value
     * @return CompareRequest
     */
    public static function compare($dn, string $attributeName, string $value)
    {
        return new CompareRequest($dn, Filters::equal($attributeName, $value));
    }

    /**
     * Delete an entry from LDAP by its DN.
     *
     * @param string|Dn $dn
     * @return DeleteRequest
     */
    public static function delete($dn)
    {
        return new DeleteRequest($dn);
    }

    /**
     * Perform an extended operation.
     *
     * @param string $name
     * @param null|string $value
     * @return ExtendedRequest
     */
    public static function extended(string $name, ?string $value = null)
    {
        return new ExtendedRequest($name, $value);
    }

    /**
     * Perform modification(s) on an LDAP entry.
     *
     * @param string|Dn $dn
     * @param Change[] ...$changes
     * @return ModifyRequest
     */
    public static function modify(string $dn, Change ...$changes)
    {
        return new ModifyRequest($dn, ...$changes);
    }

    /**
     * Move an LDAP entry to a new parent DN location.
     *
     * @param string|Dn $dn
     * @param string|Dn $newParentDn
     * @return ModifyDnRequest
     */
    public static function move($dn, $newParentDn)
    {
        $dn = $dn instanceof Dn ? $dn : new Dn($dn);

        return new ModifyDnRequest($dn, $dn->getRdn(), true, $newParentDn);
    }

    /**
     * Creates a password modify extended operation.
     *
     * @param string $username
     * @param string $oldPassword
     * @param string $newPassword
     * @return PasswordModifyRequest
     */
    public static function passwordModify(string $username, string $oldPassword, string $newPassword)
    {
        return new PasswordModifyRequest($username, $oldPassword, $newPassword);
    }

    /**
     * Quit is an alias for unbind. This is more indicative of what an unbind actually does.
     *
     * @return UnbindRequest
     */
    public static function quit()
    {
        return self::unbind();
    }

    /**
     * Rename an LDAP entry by modifying its RDN.
     *
     * @param string|Dn $dn
     * @param string|Rdn $rdn
     * @param bool $deleteOldRdn
     * @return ModifyDnRequest
     */
    public static function rename($dn, $rdn, bool $deleteOldRdn = true)
    {
        return new ModifyDnRequest($dn, $rdn, $deleteOldRdn);
    }

    /**
     * Search LDAP with a given filter, scope, etc to retrieve a set of entries.
     *
     * @param FilterInterface $filter
     * @param array|Attribute $attributes
     * @return SearchRequest
     */
    public static function search(FilterInterface $filter, ...$attributes)
    {
        return new SearchRequest($filter, ...$attributes);
    }

    /**
     * Search for a specific base DN object to read. This sets a 'present' filter for the 'objectClass' attribute to help
     * simplify it.
     *
     * @param string|Dn $baseDn
     * @param array ...$attributes
     * @return SearchRequest
     */
    public static function read($baseDn, ...$attributes)
    {
        return (new SearchRequest(Filters::present('objectClass'), ...$attributes))->base($baseDn)->useBaseScope();
    }

    /**
     * Search a single level list from a base DN object.
     *
     * @param FilterInterface $filter
     * @param string|Dn $baseDn
     * @param array ...$attributes
     * @return SearchRequest
     */
    public static function list(FilterInterface $filter, $baseDn, ...$attributes)
    {
        return (new SearchRequest($filter, ...$attributes))->base($baseDn)->useSingleLevelScope();
    }

    /**
     * A request to unbind. This actually causes the server to terminate the client connection.
     *
     * @return UnbindRequest
     */
    public static function unbind()
    {
        return new UnbindRequest();
    }

    /**
     * A request to determine who is currently authorized against LDAP for the current session.
     *
     * @return ExtendedRequest
     */
    public static function whoami()
    {
        return new ExtendedRequest(ExtendedRequest::OID_WHOAMI);
    }
}
