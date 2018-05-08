<?php
/**
 * This file is part of the FreeDSx LDAP package.
 *
 * (c) Chad Sikorra <Chad.Sikorra@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FreeDSx\Ldap\Control\Sorting;

/**
 * Represents a server side sorting request SortKey.
 *
 * @author Chad Sikorra <Chad.Sikorra@gmail.com>
 */
class SortKey
{
    /**
     * @var string
     */
    protected $attribute;

    /**
     * @var null|string
     */
    protected $orderingRule;

    /**
     * @var bool
     */
    protected $useReverseOrder;

    /**
     * @param string $attribute
     * @param bool $useReverseOrder
     * @param null|string $orderingRule
     */
    public function __construct(string $attribute, bool $useReverseOrder = false, ?string $orderingRule = null)
    {
        $this->attribute = $attribute;
        $this->orderingRule = $orderingRule;
        $this->useReverseOrder = $useReverseOrder;
    }

    /**
     * @return string
     */
    public function getAttribute() : string
    {
        return $this->attribute;
    }

    /**
     * @param string $attribute
     * @return $this
     */
    public function setAttribute(string $attribute)
    {
        $this->attribute = $attribute;

        return $this;
    }

    /**
     * @return string
     */
    public function getOrderingRule() : ?string
    {
        return $this->orderingRule;
    }

    /**
     * @param string $orderingRule
     * @return $this
     */
    public function setOrderingRule(?string $orderingRule)
    {
        $this->orderingRule = $orderingRule;

        return $this;
    }

    /**
     * @return bool
     */
    public function getUseReverseOrder() : bool
    {
        return $this->useReverseOrder;
    }

    /**
     * @param bool $useReverseOrder
     * @return $this
     */
    public function setUseReverseOrder(bool $useReverseOrder)
    {
        $this->useReverseOrder = $useReverseOrder;

        return $this;
    }

    /**
     * Create an ascending sort key.
     *
     * @param string $attribute
     * @param null|string $matchRule
     * @return SortKey
     */
    public static function ascending(string $attribute, ?string $matchRule = null)
    {
        return new self($attribute, false, $matchRule);
    }

    /**
     * Create a descending sort key.
     *
     * @param string $attribute
     * @param null|string $matchRule
     * @return SortKey
     */
    public static function descending(string $attribute, ?string $matchRule = null)
    {
        return new self($attribute, true, $matchRule);
    }
}
