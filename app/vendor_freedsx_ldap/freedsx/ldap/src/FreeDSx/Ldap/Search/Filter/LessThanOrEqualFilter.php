<?php
/**
 * This file is part of the FreeDSx LDAP package.
 *
 * (c) Chad Sikorra <Chad.Sikorra@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FreeDSx\Ldap\Search\Filter;

/**
 * Represents a less-than-or-equal-to filter. RFC 4511, 4.5.1.7.4
 *
 * @author Chad Sikorra <Chad.Sikorra@gmail.com>
 */
class LessThanOrEqualFilter implements FilterInterface
{
    use AttributeValueAssertionTrait;

    protected const CHOICE_TAG = 6;

    protected const FILTER_TYPE = self::FILTER_LTE;
}
