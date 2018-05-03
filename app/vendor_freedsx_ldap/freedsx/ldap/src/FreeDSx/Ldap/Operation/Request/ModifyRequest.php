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
use FreeDSx\Asn1\Type\EnumeratedType;
use FreeDSx\Asn1\Type\OctetStringType;
use FreeDSx\Asn1\Type\SequenceType;
use FreeDSx\Asn1\Type\SetType;
use FreeDSx\Ldap\Entry\Attribute;
use FreeDSx\Ldap\Entry\Change;
use FreeDSx\Ldap\Entry\Dn;
use FreeDSx\Ldap\Exception\ProtocolException;

/**
 * A Modify Request. RFC 4511, 4.6
 *
 * ModifyRequest ::= [APPLICATION 6] SEQUENCE {
 *     object          LDAPDN,
 *     changes         SEQUENCE OF change SEQUENCE {
 *         operation       ENUMERATED {
 *             add     (0),
 *             delete  (1),
 *             replace (2),
 *             ...  },
 *         modification    PartialAttribute } }
 *
 * @author Chad Sikorra <Chad.Sikorra@gmail.com>
 */
class ModifyRequest implements RequestInterface, DnRequestInterface
{
    protected const APP_TAG = 6;

    /**
     * @var Change[]
     */
    protected $changes;

    /**
     * @var Dn
     */
    protected $dn;

    /**
     * @param string $dn
     * @param Change[] ...$changes
     */
    public function __construct($dn, Change ...$changes)
    {
        $this->setDn($dn);
        $this->changes = $changes;
    }

    /**
     * @return Change[]
     */
    public function getChanges() : array
    {
        return $this->changes;
    }

    /**
     * @param Change[] ...$changes
     * @return $this
     */
    public function setChanges(Change ...$changes)
    {
        $this->changes = $changes;

        return $this;
    }

    /**
     * @param string|Dn $dn
     * @return $this
     */
    public function setDn($dn)
    {
        $this->dn = $dn instanceof $dn ? $dn : new Dn($dn);

        return $this;
    }

    /**
     * @return Dn
     */
    public function getDn() : Dn
    {
        return $this->dn;
    }

    /**
     * {@inheritdoc}
     */
    public static function fromAsn1(AbstractType $type)
    {
        if (!($type instanceof SequenceType && count($type) === 2)) {
            throw new ProtocolException('The modify request is malformed');
        }

        $dn = $type->getChild(0);
        $changes = $type->getChild(1);
        if (!($dn instanceof OctetStringType && $changes instanceof SequenceType)) {
            throw new ProtocolException('The modify request is malformed');
        }

        $changeList = [];
        foreach ($changes as $change) {
            $changeList[] = self::parseChange($change);
        }

        return new self($dn->getValue(), ...$changeList);
    }

    /**
     * {@inheritdoc}
     */
    public function toAsn1(): AbstractType
    {
        $changes = Asn1::sequenceOf();

        foreach ($this->changes as $change) {
            $changeSeq = Asn1::sequence(Asn1::enumerated($change->getType()));

            $changeSeq->addChild(Asn1::sequence(
                Asn1::octetString($change->getAttribute()->getName()),
                Asn1::setOf(...array_map(function ($value) {
                    return Asn1::octetString($value);
                }, $change->getAttribute()->getValues()))
            ));

            $changes->addChild($changeSeq);
        }

        return Asn1::application(self::APP_TAG, Asn1::sequence(
            Asn1::octetString($this->dn->toString()),
            $changes
        ));
    }

    /**
     * @param AbstractType $type
     * @return Change
     * @throws ProtocolException
     */
    protected static function parseChange(AbstractType $type) : Change
    {
        if (!($type instanceof SequenceType && count($type->getChildren()) === 2)) {
            throw new ProtocolException('The change for the modify request is malformed.');
        }

        $operation = $type->getChild(0);
        $modification = $type->getChild(1);
        if (!($operation instanceof EnumeratedType && $modification instanceof SequenceType)) {
            throw new ProtocolException('The change for the modify request is malformed.');
        }

        return new Change($operation->getValue(), self::parsePartialAttribute($modification));
    }

    /**
     * @param SequenceType $type
     * @return Attribute
     * @throws ProtocolException
     */
    protected static function parsePartialAttribute(SequenceType $type) : Attribute
    {
        if (count($type->getChildren()) !== 2) {
            throw new ProtocolException('The partial attribute for the modify request is malformed.');
        }

        $attrType = $type->getChild(0);
        $attrVals = $type->getChild(1);
        if (!($attrType instanceof OctetStringType && $attrVals instanceof SetType)) {
            throw new ProtocolException('The partial attribute for the modify request is malformed.');
        }

        $values = [];
        foreach ($attrVals->getChildren() as $attrVal) {
            if (!$attrVal instanceof OctetStringType) {
                throw new ProtocolException('The partial attribute for the modify request is malformed.');
            }
            $values[] = $attrVal->getValue();
        }

        return new Attribute($attrType->getValue(), ...$values);
    }
}
