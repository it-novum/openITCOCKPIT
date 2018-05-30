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
 * Represents a logical 'and' filter. RFC 4511, 4.5.1
 *
 * @author Chad Sikorra <Chad.Sikorra@gmail.com>
 */
class AndFilter implements FilterContainerInterface, \IteratorAggregate, \Countable
{
    use FilterContainerTrait;

    protected const FILTER_OPERATOR = self::OPERATOR_AND;

    protected const CHOICE_TAG = 0;
}
