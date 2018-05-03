<?php
/**
 * This file is part of the FreeDSx LDAP package.
 *
 * (c) Chad Sikorra <Chad.Sikorra@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FreeDSx\Ldap\Operation\Response;

use FreeDSx\Asn1\Asn1;
use FreeDSx\Asn1\Type\AbstractType;
use FreeDSx\Asn1\Type\SequenceType;
use FreeDSx\Ldap\Entry\Attribute;
use FreeDSx\Ldap\Entry\Entry;

/**
 * A search result entry. RFC 4511, 4.5.2.
 *
 * SearchResultEntry ::= [APPLICATION 4] SEQUENCE {
 *     objectName      LDAPDN,
 *     attributes      PartialAttributeList }
 *
 * PartialAttributeList ::= SEQUENCE OF
 *     partialAttribute PartialAttribute
 *
 * @author Chad Sikorra <Chad.Sikorra@gmail.com>
 */
class SearchResultEntry implements ResponseInterface
{
    protected const TAG_NUMBER = 4;

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
     * {@inheritdoc}
     */
    public static function fromAsn1(AbstractType $type)
    {
        $attributes = [];

        /** @var \FreeDSx\Asn1\Type\SequenceType $type */
        foreach ($type->getChild(1) as $partialAttribute) {
            $values = [];

            /** @var \FreeDSx\Asn1\Type\SequenceType $partialAttribute */
            foreach ($partialAttribute->getChild(1) as $attrValue) {
                /** @var \FreeDSx\Asn1\Type\OctetStringType $attrValue */
                $values[] = $attrValue->getValue();
            }

            $attributes[] = new Attribute($partialAttribute->getChild(0)->getValue(), ...$values);
        }

        return new self(new Entry($type->getChild(0)->getValue(), ...$attributes));
    }

    /**
     * {@inheritdoc}
     */
    public function toAsn1(): AbstractType
    {
        /** @var SequenceType $asn1 */
        $asn1 = Asn1::application(self::TAG_NUMBER, Asn1::sequence());

        $partialAttributes = Asn1::sequenceOf();
        foreach ($this->entry->getAttributes() as $attribute) {
            $partialAttributes->addChild(Asn1::sequence(
                Asn1::octetString($attribute->getName()),
                Asn1::setOf(...array_map(function ($v) {
                    return Asn1::octetString($v);
                }, $attribute->getValues()))
            ));
        }
        $asn1->addChild(Asn1::octetString($this->entry->getDn()->toString()));
        $asn1->addChild($partialAttributes);

        return $asn1;
    }
}
