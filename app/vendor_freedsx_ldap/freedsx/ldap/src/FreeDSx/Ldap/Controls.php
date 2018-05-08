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

use FreeDSx\Ldap\Control\Ad\SdFlagsControl;
use FreeDSx\Ldap\Control\Control;
use FreeDSx\Ldap\Control\PagingControl;
use FreeDSx\Ldap\Control\Sorting\SortingControl;
use FreeDSx\Ldap\Control\Sorting\SortKey;
use FreeDSx\Ldap\Control\Vlv\VlvControl;
use FreeDSx\Ldap\Search\Filter\GreaterThanOrEqualFilter;

/**
 * Provides some simple factory methods for building controls.
 *
 * @author Chad Sikorra <Chad.Sikorra@gmail.com>
 */
class Controls
{
    /**
     * Create a generic control by OID.
     *
     * @param string $oid
     * @param bool $criticality
     * @param null $value
     * @return Control
     */
    public static function create(string $oid, $criticality = false, $value = null) : Control
    {
        return new Control($oid, $criticality, $value);
    }

    /**
     * Create a paging control with a specific size.
     *
     * @param int $size
     * @param string $cookie
     * @return PagingControl
     */
    public static function paging(int $size, $cookie = '') : PagingControl
    {
        return new PagingControl($size, $cookie);
    }

    /**
     * Create a password policy control.
     *
     * @param bool $criticality
     * @return Control
     */
    public static function pwdPolicy(bool $criticality = true) : Control
    {
        return new Control(Control::OID_PWD_POLICY, $criticality);
    }

    /**
     * Create a server side sort with a set of SortKey objects, or simple set of attribute names.
     *
     * @param SortKey[]|string ...$sortKeys
     * @return SortingControl
     */
    public static function sort(...$sortKeys) : SortingControl
    {
        $keys = [];
        foreach ($sortKeys as $sort) {
            $keys[] = $sort instanceof SortKey ? $sort : new SortKey($sort);
        }

        return new SortingControl(...$keys);
    }

    /**
     * Create a control for a subtree delete. On a delete request this will do a recursive delete from the DN and all
     * of its children.
     *
     * @param bool $criticality
     * @return Control
     */
    public static function subtreeDelete(bool $criticality = false) : Control
    {
        return new Control(Control::OID_SUBTREE_DELETE, $criticality);
    }

    /**
     * Create a VLV offset based control.
     *
     * @param int $before
     * @param int $after
     * @param int $offset
     * @param int $count
     * @param null|string $contextId
     * @return VlvControl
     */
    public static function vlv(int $before, int $after, int $offset = 1, int $count = 0, ?string $contextId = null) : VlvControl
    {
        return new VlvControl($before, $after, $offset, $count, null, $contextId);
    }

    /**
     * Create a VLV filter based control.
     *
     * @param int $before
     * @param int $after
     * @param GreaterThanOrEqualFilter $filter
     * @param null|string $contextId
     * @return VlvControl
     */
    public static function vlvFilter(int $before, int $after, GreaterThanOrEqualFilter $filter, ?string $contextId = null) : VlvControl
    {
        return new VlvControl($before, $after, null, null, $filter, $contextId);
    }

    /**
     * Create an AD SD Flags Control.
     *
     * @param int $flags
     * @return SdFlagsControl
     */
    public static function sdFlags(int $flags) : SdFlagsControl
    {
        return new SdFlagsControl($flags);
    }
}
