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
use FreeDSx\Asn1\Type\AbstractType;
use FreeDSx\Asn1\Type\IncompleteType;
use FreeDSx\Asn1\Type\OctetStringType;
use FreeDSx\Asn1\Type\SequenceType;
use FreeDSx\Ldap\Entry\Attribute;
use FreeDSx\Ldap\Exception\ProtocolException;
use FreeDSx\Ldap\Exception\RuntimeException;
use FreeDSx\Ldap\Protocol\LdapEncoder;

/**
 * Represents a substring filter. RFC 4511, 4.5.1.7.2.
 *
 * SubstringFilter ::= SEQUENCE {
 *     type           AttributeDescription,
 *     substrings     SEQUENCE SIZE (1..MAX) OF substring CHOICE {
 *         initial [0] AssertionValue,  -- can occur at most once
 *         any     [1] AssertionValue,
 *         final   [2] AssertionValue } -- can occur at most once
 *     }
 *
 * @author Chad Sikorra <Chad.Sikorra@gmail.com>
 */
class SubstringFilter implements FilterInterface
{
    use FilterAttributeTrait;

    protected const CHOICE_TAG = 4;

    /**
     * @var null|string
     */
    protected $startsWith;

    /**
     * @var null|string
     */
    protected $endsWith;

    /**
     * @var string[]
     */
    protected $contains = [];

    /**
     * @param string $attribute
     * @param null|string $startsWith
     * @param null|string $endsWith
     * @param string[] ...$contains
     */
    public function __construct(string $attribute, ?string $startsWith = null, ?string $endsWith = null, string ...$contains)
    {
        $this->attribute = $attribute;
        $this->startsWith = $startsWith;
        $this->endsWith = $endsWith;
        $this->contains = $contains;
    }

    /**
     * Get the value that it should start with.
     *
     * @return null|string
     */
    public function getStartsWith() : ?string
    {
        return $this->startsWith;
    }

    /**
     * Set the value it should start with.
     *
     * @param null|string $value
     * @return $this
     */
    public function setStartsWith(?string $value)
    {
        $this->startsWith = $value;

        return $this;
    }

    /**
     * Get the value it should end with.
     *
     * @return null|string
     */
    public function getEndsWith() : ?string
    {
        return $this->endsWith;
    }

    /**
     * Set the value it should end with.
     *
     * @param null|string $value
     * @return $this
     */
    public function setEndsWith(?string $value)
    {
        $this->endsWith = $value;

        return $this;
    }

    /**
     * Get the values it should contain.
     *
     * @return string[]
     */
    public function getContains() : array
    {
        return $this->contains;
    }

    /**
     * Set the values it should contain.
     *
     * @param string[] ...$values
     * @return $this
     */
    public function setContains(string ...$values)
    {
        $this->contains = $values;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function toAsn1() : AbstractType
    {
        if ($this->startsWith === null && $this->endsWith === null && empty($this->contains)) {
            throw new RuntimeException('You must provide a contains, starts with, or ends with value to the substring filter.');
        }
        $substrings = Asn1::sequenceOf();

        if ($this->startsWith !== null) {
            $substrings->addChild(Asn1::context(0, Asn1::octetString($this->startsWith)));
        }

        foreach ($this->contains as $contain) {
            $substrings->addChild(Asn1::context(1, Asn1::octetString($contain)));
        }

        if ($this->endsWith !== null) {
            $substrings->addChild(Asn1::context(2, Asn1::octetString($this->endsWith)));
        }

        return Asn1::context(self::CHOICE_TAG, Asn1::sequence(
           Asn1::octetString($this->attribute),
           $substrings
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function toString(): string
    {
        $filter = self::PAREN_LEFT.$this->attribute.self::FILTER_EQUAL;

        $value = '';
        if (!empty($this->contains)) {
            $value = array_map(function($value) {
                return Attribute::escape($value);
            }, $this->contains);
            $value = '*'.implode('*', $value).'*';
        }
        if ($this->startsWith !== null) {
            $startsWith = Attribute::escape($this->startsWith);
            $value = ($value === '' ? $startsWith.'*' : $startsWith).$value;
        }
        if ($this->endsWith !== null) {
            $endsWith = Attribute::escape($this->endsWith);
            $value = $value.($value === '' ? '*'.$endsWith : $endsWith);
        }

        return $filter.$value.self::PAREN_RIGHT;
    }

    /**
     * {@inheritdoc}
     */
    public static function fromAsn1(AbstractType $type)
    {
        $encoder = new LdapEncoder();
        $type = $type instanceof IncompleteType ? $encoder->complete($type, AbstractType::TAG_TYPE_SEQUENCE) : $type;
        if (!($type instanceof SequenceType && count($type->getChildren()) === 2)) {
            throw new ProtocolException('The substring type is malformed');
        }

        $attrType = $type->getChild(0);
        $substrings = $type->getChild(1);
        if (!($attrType instanceof OctetStringType && $substrings instanceof SequenceType && count($substrings) > 0)) {
            throw new ProtocolException('The substring filter is malformed.');
        }
        [$startsWith, $endsWith, $contains] = self::parseSubstrings($substrings);

        return new self($attrType->getValue(), $startsWith, $endsWith, ...$contains);
    }

    /**
     * @param $substrings
     * @return array
     * @throws ProtocolException
     */
    protected static function parseSubstrings(SequenceType $substrings) : array
    {
        $startsWith = null;
        $endsWith = null;
        $contains = [];

        /** @var AbstractType $substring */
        foreach ($substrings as $substring) {
            if ($substring->getTagClass() !== AbstractType::TAG_CLASS_CONTEXT_SPECIFIC) {
                throw new ProtocolException('The substring filter is malformed.');
            }
            # Starts With and Ends With can occur only once each. Contains can occur multiple times.
            if ($substring->getTagNumber() === 0) {
                if ($startsWith) {
                    throw new ProtocolException('The substring filter is malformed.');
                } else {
                    $startsWith = $substring;
                }
            } elseif ($substring->getTagNumber() === 1) {
                $contains[] = $substring->getValue();
            } elseif ($substring->getTagNumber() === 2) {
                if ($endsWith) {
                    throw new ProtocolException('The substring filter is malformed.');
                } else {
                    $endsWith = $substring;
                }
            } else {
                throw new ProtocolException('The substring filter is malformed.');
            }
        }

        return [$startsWith ? $startsWith->getValue() : null, $endsWith ? $endsWith->getValue() : null, $contains];
    }
}
