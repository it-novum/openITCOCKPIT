<?php
/**
 * This file is part of the FreeDSx LDAP package.
 *
 * (c) Chad Sikorra <Chad.Sikorra@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FreeDSx\Ldap\Control\Vlv;

use FreeDSx\Asn1\Asn1;
use FreeDSx\Asn1\Type\AbstractType;
use FreeDSx\Ldap\Control\Control;
use FreeDSx\Ldap\Exception\RuntimeException;
use FreeDSx\Ldap\Search\Filter\GreaterThanOrEqualFilter;

/**
 * Represents a VLV Request. draft-ietf-ldapext-ldapv3-vlv-09
 *
 * VirtualListViewRequest ::= SEQUENCE {
 *     beforeCount    INTEGER (0..maxInt),
 *     afterCount     INTEGER (0..maxInt),
 *     target       CHOICE {
 *         byOffset        [0] SEQUENCE {
 *             offset          INTEGER (1 .. maxInt),
 *             contentCount    INTEGER (0 .. maxInt) },
 *         greaterThanOrEqual [1] AssertionValue },
 *     contextID     OCTET STRING OPTIONAL }
 *
 * @author Chad Sikorra <Chad.Sikorra@gmail.com>
 */
class VlvControl extends Control
{
    use VlvTrait;

    /**
     * @var int
     */
    protected $after;

    /**
     * @var int
     */
    protected $before;

    /**
     * @var null|GreaterThanOrEqualFilter
     */
    protected $filter;

    /**
     * @param int $before
     * @param int $after
     * @param int $offset
     * @param int $count
     * @param GreaterThanOrEqualFilter|null $filter
     * @param null|string $contextId
     */
    public function __construct(int $before, int $after, ?int $offset = null, ?int $count = null, GreaterThanOrEqualFilter $filter = null, ?string $contextId = null)
    {
        $this->before = $before;
        $this->after = $after;
        $this->offset = $offset;
        $this->count = $count;
        $this->filter = $filter;
        $this->contextId = $contextId;
        parent::__construct(self::OID_VLV);
    }

    /**
     * @return int
     */
    public function getAfter() : int
    {
        return $this->after;
    }

    /**
     * @param int $after
     * @return $this
     */
    public function setAfter(int $after)
    {
        $this->after = $after;

        return $this;
    }

    /**
     * @return int
     */
    public function getBefore() : int
    {
        return $this->before;
    }

    /**
     * @param int $before
     * @return $this
     */
    public function setBefore(int $before)
    {
        $this->before = $before;

        return $this;
    }

    /**
     * @param int $count
     * @return $this
     */
    public function setCount(?int $count)
    {
        $this->count = $count;

        return $this;
    }

    /**
     * @param string|null $contextId
     * @return $this
     */
    public function setContextId($contextId)
    {
        $this->contextId = $contextId;

        return $this;
    }

    /**
     * @param int $offset
     * @return $this
     */
    public function setOffset(?int $offset)
    {
        $this->offset = $offset;

        return $this;
    }

    /**
     * @return null|GreaterThanOrEqualFilter
     */
    public function getFilter() : ?GreaterThanOrEqualFilter
    {
        return $this->filter;
    }

    /**
     * @param GreaterThanOrEqualFilter|null $filter
     * @return $this
     */
    public function setFilter(GreaterThanOrEqualFilter $filter = null)
    {
        $this->filter = $filter;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function toAsn1(): AbstractType
    {
        $this->controlValue = Asn1::sequence(
            Asn1::integer($this->before),
            Asn1::integer($this->after)
        );
        if ($this->filter === null && ($this->count === null || $this->offset === null)) {
            throw new RuntimeException('You must specify a filter or offset and count for a VLV request.');
        }
        if ($this->filter) {
            $this->controlValue->addChild(Asn1::context(1, $this->filter->toAsn1()));
        } else {
            $this->controlValue->addChild(Asn1::context(0, Asn1::sequence(
                Asn1::integer($this->offset),
                Asn1::integer($this->count)
            )));
        }

        return parent::toAsn1();
    }

    /**
     * {@inheritdoc}
     */
    public static function fromAsn1(AbstractType $type)
    {
        // TODO: Implement fromAsn1() method.
    }
}
