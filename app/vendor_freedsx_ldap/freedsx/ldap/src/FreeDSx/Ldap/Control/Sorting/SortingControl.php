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

use FreeDSx\Asn1\Asn1;
use FreeDSx\Asn1\Type\AbstractType;
use FreeDSx\Asn1\Type\IncompleteType;
use FreeDSx\Asn1\Type\OctetStringType;
use FreeDSx\Asn1\Type\SequenceType;
use FreeDSx\Ldap\Control\Control;
use FreeDSx\Ldap\Exception\ProtocolException;
use FreeDSx\Ldap\Protocol\LdapEncoder;

/**
 * A Server Side Sorting request control value. RFC 2891.
 *
 * SortKeyList ::= SEQUENCE OF SEQUENCE {
 *     attributeType   AttributeDescription,
 *     orderingRule    [0] MatchingRuleId OPTIONAL,
 *     reverseOrder    [1] BOOLEAN DEFAULT FALSE }
 *
 * @author Chad Sikorra <Chad.Sikorra@gmail.com>
 */
class SortingControl extends Control
{
    /**
     * @var SortKey[]
     */
    protected $sortKeys = [];

    /**
     * @param SortKey[] ...$sortKeys
     */
    public function __construct(SortKey ...$sortKeys)
    {
        $this->sortKeys = $sortKeys;
        parent::__construct(self::OID_SORTING);
    }

    /**
     * @param SortKey[] ...$sortKeys
     * @return $this
     */
    public function addSortKeys(SortKey ...$sortKeys)
    {
        foreach ($sortKeys as $sortKey) {
            $this->sortKeys[] = $sortKey;
        }

        return $this;
    }

    /**
     * @param SortKey[] ...$sortKeys
     * @return $this
     */
    public function setSortKeys(SortKey ...$sortKeys)
    {
        $this->sortKeys = $sortKeys;

        return $this;
    }

    /**
     * @return SortKey[]
     */
    public function getSortKeys() : array
    {
        return $this->sortKeys;
    }

    /**
     * {@inheritdoc}
     */
    public static function fromAsn1(AbstractType $type)
    {
        $response = self::decodeEncodedValue($type);
        if (!$response instanceof SequenceType) {
            throw new ProtocolException('The sorting control is malformed.');
        }

        $sortKeys = [];
        /** @var SequenceType $response */
        foreach ($response as $sequence) {
            if (!$response instanceof SequenceType) {
                throw new ProtocolException('The sort key is malformed.');
            }

            $attrName = null;
            $matchRule = null;
            $useReverseOrder = false;

            $encoder = new LdapEncoder();
            /** @var AbstractType $keyItem */
            foreach ($sequence as $keyItem) {
                if ($keyItem instanceof OctetStringType && $keyItem->getTagClass() === AbstractType::TAG_CLASS_UNIVERSAL) {
                    $attrName = $keyItem->getValue();
                } elseif ($keyItem->getTagClass() === AbstractType::TAG_CLASS_CONTEXT_SPECIFIC && $keyItem->getTagNumber() === 0) {
                    $matchRule = $keyItem->getValue();
                } elseif ($keyItem->getTagClass() === AbstractType::TAG_CLASS_CONTEXT_SPECIFIC && $keyItem->getTagNumber() === 1) {
                    /** @var IncompleteType $keyItem */
                    $useReverseOrder = $encoder->complete($keyItem, AbstractType::TAG_TYPE_BOOLEAN)->getValue();
                } else {
                    throw new ProtocolException('The sorting control contains unexpected data.');
                }
            }
            if (empty($attrName) && $attrName !== '0') {
                throw new ProtocolException('The sort key is missing an attribute description.');
            }

            $sortKeys[] = new SortKey($attrName, $useReverseOrder, $matchRule);
        }
        $control = new self(...$sortKeys);

        return self::mergeControlData($control, $type);
    }

    /**
     * {@inheritdoc}
     */
    public function toAsn1(): AbstractType
    {
        $this->controlValue = Asn1::sequenceOf();

        foreach ($this->sortKeys as $sortKey) {
            $child = Asn1::sequence(Asn1::octetString($sortKey->getAttribute()));
            if ($sortKey->getOrderingRule() !== null) {
                $child->addChild(Asn1::context(0, Asn1::octetString($sortKey->getOrderingRule())));
            }
            if ($sortKey->getUseReverseOrder()) {
                $child->addChild(Asn1::context(1, Asn1::boolean(true)));
            }
            $this->controlValue->addChild($child);
        }

        return parent::toAsn1();
    }
}
