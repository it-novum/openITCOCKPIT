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

use FreeDSx\Ldap\Protocol\ProtocolElementInterface;

/**
 * Used to represent filters for search requests.
 *
 * @author Chad Sikorra <Chad.Sikorra@gmail.com>
 */
interface FilterInterface extends ProtocolElementInterface
{
    public const PAREN_LEFT = '(';

    public const PAREN_RIGHT = ')';

    public const OPERATOR_AND = '&';

    public const OPERATOR_OR = '|';

    public const OPERATOR_NOT = '!';

    public const FILTER_EQUAL = '=';

    public const FILTER_APPROX = '~=';

    public const FILTER_GTE = '>=';

    public const FILTER_LTE = '<=';

    public const FILTER_EXT = ':=';

    public const OPERATORS = [
        self::OPERATOR_NOT,
        self::OPERATOR_OR,
        self::OPERATOR_AND,
    ];

    public const FILTERS = [
        self::FILTER_EQUAL,
        self::FILTER_APPROX,
        self::FILTER_GTE,
        self::FILTER_LTE,
        self::FILTER_EXT,
    ];

    /**
     * Get the string representation of the filter.
     *
     * @return string
     */
    public function toString() : string;
}
