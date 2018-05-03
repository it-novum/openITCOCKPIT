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

use FreeDSx\Asn1\Asn1;
use FreeDSx\Ldap\Protocol\LdapEncoder;
use FreeDSx\Asn1\Type\AbstractType;
use FreeDSx\Asn1\Type\BooleanType;
use FreeDSx\Asn1\Type\IncompleteType;
use FreeDSx\Asn1\Type\OctetStringType;
use FreeDSx\Asn1\Type\SequenceType;
use FreeDSx\Ldap\Entry\Attribute;
use FreeDSx\Ldap\Exception\ProtocolException;

/**
 * Represents an extensible matching rule filter. RFC 4511, 4.5.1.7.7
 *
 * MatchingRuleAssertion ::= SEQUENCE {
 *     matchingRule    [1] MatchingRuleId OPTIONAL,
 *     type            [2] AttributeDescription OPTIONAL,
 *     matchValue      [3] AssertionValue,
 *     dnAttributes    [4] BOOLEAN DEFAULT FALSE }
 *
 * @author Chad Sikorra <Chad.Sikorra@gmail.com>
 */
class MatchingRuleFilter implements FilterInterface
{
    protected const CHOICE_TAG = 9;

    /**
     * @var null|string
     */
    protected $matchingRule;

    /**
     * @var null|string
     */
    protected $attribute;

    /**
     * @var string
     */
    protected $value;

    /**
     * @var bool
     */
    protected $useDnAttributes;

    /**
     * @param null|string $matchingRule
     * @param null|string $attribute
     * @param string $value
     * @param bool $useDnAttributes
     */
    public function __construct(?string $matchingRule, ?string $attribute, string $value, bool $useDnAttributes = false)
    {
        $this->matchingRule = $matchingRule;
        $this->attribute = $attribute;
        $this->value = $value;
        $this->useDnAttributes = $useDnAttributes;
    }

    /**
     * @return null|string
     */
    public function getAttribute() : ?string
    {
        return $this->attribute;
    }

    /**
     * @param null|string $attribute
     * @return $this
     */
    public function setAttribute(?string $attribute)
    {
        $this->attribute = $attribute;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getMatchingRule() : ?string
    {
        return $this->matchingRule;
    }

    /**
     * @param null|string $matchingRule
     * @return $this
     */
    public function setMatchingRule(?string $matchingRule)
    {
        $this->matchingRule = $matchingRule;

        return $this;
    }

    /**
     * @return string
     */
    public function getValue() : string
    {
        return $this->value;
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setValue(string $value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * @return bool
     */
    public function getUseDnAttributes() : bool
    {
        return $this->useDnAttributes;
    }

    /**
     * @param bool $useDnAttributes
     * @return $this
     */
    public function setUseDnAttributes(bool $useDnAttributes)
    {
        $this->useDnAttributes = $useDnAttributes;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function toAsn1() : AbstractType
    {
        /** @var \FreeDSx\Asn1\Type\SequenceType $matchingRule */
        $matchingRule = Asn1::context(self::CHOICE_TAG, Asn1::sequence());

        if ($this->matchingRule !== null) {
            $matchingRule->addChild(Asn1::context(1, Asn1::octetString($this->matchingRule)));
        }
        if ($this->attribute !== null) {
            $matchingRule->addChild(Asn1::context(2, Asn1::octetString($this->attribute)));
        }
        $matchingRule->addChild(Asn1::context(3, Asn1::octetString($this->value)));
        $matchingRule->addChild(Asn1::context(4, Asn1::boolean($this->useDnAttributes)));

        return $matchingRule;
    }

    /**
     * {@inheritdoc}
     */
    public function toString(): string
    {
        $filter = '';
        if ($this->attribute) {
            $filter = $this->attribute;
        }
        if ($this->matchingRule) {
            $filter .= ':'.$this->matchingRule;
        }
        if ($this->useDnAttributes) {
            $filter .= ':dn';
        }

        return self::PAREN_LEFT.$filter.self::FILTER_EXT.Attribute::escape($this->value).self::PAREN_RIGHT;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->toString();
    }

    /**
     * {@inheritdoc}
     */
    public static function fromAsn1(AbstractType $type)
    {
        $type = $type instanceof IncompleteType ? (new LdapEncoder())->complete($type, AbstractType::TAG_TYPE_SEQUENCE) : $type;
        if (!($type instanceof SequenceType && (count($type) >= 1 && count($type) <= 4))) {
            throw new ProtocolException('The matching rule filter is malformed');
        }
        $matchingRule = null;
        $matchingType = null;
        $matchValue = null;
        $useDnAttr = null;

        foreach ($type->getChildren() as $child) {
            if ($child->getTagClass() !== AbstractType::TAG_CLASS_CONTEXT_SPECIFIC) {
                continue;
            }
            if ($child->getTagNumber() === 1) {
                $matchingRule = $child;
            } elseif ($child->getTagNumber() === 2) {
                $matchingType = $child;
            } elseif ($child->getTagNumber() === 3) {
                $matchValue = $child;
            } elseif ($child->getTagNumber() === 4) {
                $useDnAttr = $child;
            }
        }
        if (!$matchValue instanceof OctetStringType) {
            throw new ProtocolException('The matching rule filter is malformed.');
        }
        if ($matchingRule && !$matchingRule instanceof OctetStringType) {
            throw new ProtocolException('The matching rule filter is malformed.');
        }
        if ($matchingType && !$matchingType instanceof OctetStringType) {
            throw new ProtocolException('The matching rule filter is malformed.');
        }
        if ($useDnAttr && !$useDnAttr instanceof BooleanType) {
            throw new ProtocolException('The matching rule filter is malformed.');
        }
        $matchingRule = $matchingRule ? $matchingRule->getValue() : null;
        $matchingType = $matchingType ? $matchingType->getValue() : null;
        $useDnAttr = $useDnAttr ? $useDnAttr->getValue() : false;

        return new self($matchingRule, $matchingType, $matchValue->getValue(), $useDnAttr);
    }
}
