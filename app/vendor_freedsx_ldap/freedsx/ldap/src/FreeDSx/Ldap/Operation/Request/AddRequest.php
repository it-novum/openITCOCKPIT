<?php
/**
 * This file is part of the FreeDSx LDAP package.
 *
 * (c) Chad Sikorra <Chad.Sikorra@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FreeDSx\Ldap\Operation\Request;

use FreeDSx\Asn1\Asn1;
use FreeDSx\Asn1\Type\AbstractType;
use FreeDSx\Asn1\Type\OctetStringType;
use FreeDSx\Asn1\Type\SequenceType;
use FreeDSx\Asn1\Type\SetType;
use FreeDSx\Ldap\Entry\Attribute;
use FreeDSx\Ldap\Entry\Dn;
use FreeDSx\Ldap\Entry\Entry;
use FreeDSx\Ldap\Exception\ProtocolException;

/**
 * A request to add an entry to LDAP. RFC 4511, 4.7.
 *
 * AddRequest ::= [APPLICATION 8] SEQUENCE {
 *     entry           LDAPDN,
 *     attributes      AttributeList }
 *
 * AttributeList ::= SEQUENCE OF attribute Attribute
 *
 * PartialAttribute ::= SEQUENCE {
 *     type       AttributeDescription,
 *     vals       SET OF value AttributeValue }
 *
 * Attribute ::= PartialAttribute(WITH COMPONENTS {
 *     ...,
 *     vals (SIZE(1..MAX))})
 *
 * AttributeDescription ::= LDAPString
 *
 * AttributeValue ::= OCTET STRING
 *
 * @author Chad Sikorra <Chad.Sikorra@gmail.com>
 */
class AddRequest implements RequestInterface
{
    protected const APP_TAG = 8;

    /**
     * @var Entry
     */
    protected $entry;

    /**
     * @param Entry $entry
     */
    public function __construct(Entry $entry)
    {
        $this->entry = $entry;
    }

    /**
     * @return Entry
     */
    public function getEntry() : Entry
    {
        return $this->entry;
    }

    /**
     * @param Entry $entry
     * @return $this
     */
    public function setEntry(Entry $entry)
    {
        $this->entry = $entry;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public static function fromAsn1(AbstractType $type)
    {
        if (!($type instanceof SequenceType && count($type) === 2)) {
            throw new ProtocolException('The add request is malformed: %s');
        }

        $dn = $type->getChild(0);
        $attrList = $type->getChild(1);
        if (!($dn instanceof OctetStringType && $attrList instanceof SequenceType)) {
            throw new ProtocolException('The add request is malformed.');
        }
        $dn = new Dn($dn->getValue());

        $attributes = [];
        foreach ($attrList as $attrListing) {
            if (!($attrListing instanceof SequenceType && count($attrListing->getChildren()) == 2)) {
                throw new ProtocolException(sprintf(
                    'Expected a sequence type, but received: %s',
                    get_class($attrListing)
                ));
            }

            $attrType = $attrListing->getChild(0);
            $vals = $attrListing->getChild(1);
            if (!($attrType instanceof OctetStringType && $vals instanceof SetType)) {
                throw new ProtocolException('The add request is malformed.');
            }

            $attrValues = [];
            foreach ($vals as $val) {
                if (!$val instanceof OctetStringType) {
                    throw new ProtocolException('The add request is malformed.');
                }
                $attrValues[] = $val->getValue();
            }

            $attributes[] = new Attribute($attrType->getValue(), ...$attrValues);
        }

        return new self(new Entry($dn, ...$attributes));
    }

    /**
     * {@inheritdoc}
     */
    public function toAsn1(): AbstractType
    {
        $attributeList = Asn1::sequenceOf();

        /** @var Attribute $attribute */
        foreach ($this->entry as $attribute) {
            $attr = Asn1::sequence(Asn1::octetString($attribute->getName()));

            $attrValues = Asn1::setOf(...array_map(function ($value) {
                return Asn1::octetString($value);
            }, $attribute->getValues()));

            $attributeList->addChild($attr->addChild($attrValues));
        }

        return Asn1::application(self::APP_TAG, Asn1::sequence(
            Asn1::octetString($this->entry->getDn()->toString()),
            $attributeList
        ));
    }
}
