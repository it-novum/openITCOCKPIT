<?php
/**
 * This file is part of the FreeDSx LDAP package.
 *
 * (c) Chad Sikorra <Chad.Sikorra@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FreeDSx\Ldap\Protocol\Factory;

use FreeDSx\Asn1\Type\AbstractType;
use FreeDSx\Ldap\Exception\InvalidArgumentException;
use FreeDSx\Ldap\Search\Filter\AndFilter;
use FreeDSx\Ldap\Search\Filter\ApproximateFilter;
use FreeDSx\Ldap\Search\Filter\EqualityFilter;
use FreeDSx\Ldap\Search\Filter\FilterInterface;
use FreeDSx\Ldap\Search\Filter\GreaterThanOrEqualFilter;
use FreeDSx\Ldap\Search\Filter\LessThanOrEqualFilter;
use FreeDSx\Ldap\Search\Filter\MatchingRuleFilter;
use FreeDSx\Ldap\Search\Filter\NotFilter;
use FreeDSx\Ldap\Search\Filter\OrFilter;
use FreeDSx\Ldap\Search\Filter\PresentFilter;
use FreeDSx\Ldap\Search\Filter\SubstringFilter;

/**
 * Get the filter object from a specific ASN1 tag type.
 *
 * @author Chad Sikorra <Chad.Sikorra@gmail.com>
 */
class FilterFactory
{
    /**
     * @var array
     */
    protected static $map = [
        0 => AndFilter::class,
        1 => OrFilter::class,
        2 => NotFilter::class,
        3 => EqualityFilter::class,
        4 => SubstringFilter::class,
        5 => GreaterThanOrEqualFilter::class,
        6 => LessThanOrEqualFilter::class,
        7 => PresentFilter::class,
        8 => ApproximateFilter::class,
        9 => MatchingRuleFilter::class,
    ];

    /**
     * @param AbstractType $type
     * @return FilterInterface|null
     */
    public static function get(AbstractType $type) : ?FilterInterface
    {
        $filterClass = self::$map[$type->getTagNumber()] ?? null;

        return $filterClass ? call_user_func($filterClass.'::fromAsn1', $type) : null;
    }

    /**
     * @param int $filterType
     * @return bool
     */
    public static function has(int $filterType) : bool
    {
        return isset(self::$map[$filterType]);
    }

    /**
     * @param int $filterType
     * @param string $filterClass
     */
    public static function set(int $filterType, string $filterClass) : void
    {
        if (!class_exists($filterClass)) {
            throw new InvalidArgumentException(sprintf('The filter class does not exist: %s', $filterClass));
        }
        if (!is_subclass_of($filterClass, FilterInterface::class)) {
            throw new InvalidArgumentException(sprintf('The filter class must implement FilterInterface: %s', $filterClass));
        }

        self::$map[$filterType] = $filterClass;
    }
}
