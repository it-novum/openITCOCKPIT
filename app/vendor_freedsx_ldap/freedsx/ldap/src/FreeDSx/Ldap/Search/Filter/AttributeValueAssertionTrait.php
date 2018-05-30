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
use FreeDSx\Asn1\Type\IncompleteType;
use FreeDSx\Asn1\Type\OctetStringType;
use FreeDSx\Asn1\Type\SequenceType;
use FreeDSx\Ldap\Entry\Attribute;
use FreeDSx\Ldap\Exception\ProtocolException;

/**
 * Common methods for filters using attribute value assertions.
 *
 * @author Chad Sikorra <Chad.Sikorra@gmail.com>
 */
trait AttributeValueAssertionTrait
{
    use FilterAttributeTrait;

    /**
     * @var string
     */
    protected $value;

    /**
     * @param string $attribute
     * @param string $value
     */
    public function __construct(string $attribute, string $value)
    {
        $this->attribute = $attribute;
        $this->value = $value;
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
     * @return string
     */
    public function getValue() : string
    {
        return $this->value;
    }

    /**
     * {@inheritdoc}
     */
    public function toAsn1() : AbstractType
    {
        return Asn1::context(self::CHOICE_TAG, Asn1::sequence(
            Asn1::octetString($this->attribute),
            Asn1::octetString($this->value)
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function toString() : string
    {
        return self::PAREN_LEFT
            .$this->attribute
            .self::FILTER_TYPE
            .Attribute::escape($this->value)
            .self::PAREN_RIGHT;
    }

    /**
     * {@inheritdoc}
     */
    public static function fromAsn1(AbstractType $type)
    {
        $type = $type instanceof IncompleteType ? (new LdapEncoder())->complete($type, AbstractType::TAG_TYPE_SEQUENCE) : $type;
        if (!($type instanceof SequenceType && count($type) === 2)) {
            throw new ProtocolException('The attribute value assertion is malformed.');
        }

        $attribute = $type->getChild(0);
        $value = $type->getChild(1);
        if (!($attribute instanceof OctetStringType && $value instanceof OctetStringType)) {
            throw new ProtocolException('The attribute value assertion is malformed.');
        }

        return new self($attribute->getValue(), $value->getValue());
    }
}
