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
use FreeDSx\Ldap\Entry\Dn;
use FreeDSx\Ldap\Exception\ProtocolException;
use FreeDSx\Ldap\Search\Filter\EqualityFilter;

/**
 * An attribute-value comparison request. RFC 4511, 4.10.
 *
 * CompareRequest ::= [APPLICATION 14] SEQUENCE {
 *     entry           LDAPDN,
 *     ava             AttributeValueAssertion }
 *
 * @author Chad Sikorra <Chad.Sikorra@gmail.com>
 */
class CompareRequest implements RequestInterface, DnRequestInterface
{
    protected const APP_TAG = 14;

    /**
     * @var Dn
     */
    protected $dn;

    /**
     * @var EqualityFilter
     */
    protected $filter;

    /**
     * @param string|Dn $dn
     * @param EqualityFilter $filter
     */
    public function __construct($dn, EqualityFilter $filter)
    {
        $this->setDn($dn);
        $this->filter = $filter;
    }

    /**
     * @return Dn
     */
    public function getDn() : Dn
    {
        return $this->dn;
    }

    /**
     * @param string|Dn $dn
     * @return $this
     */
    public function setDn($dn)
    {
        $this->dn = $dn instanceof Dn ? $dn : new Dn($dn);

        return $this;
    }

    /**
     * @return EqualityFilter
     */
    public function getFilter() : EqualityFilter
    {
        return $this->filter;
    }

    /**
     * @param EqualityFilter $filter
     * @return $this
     */
    public function setFilter(EqualityFilter $filter)
    {
        $this->filter = $filter;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public static function fromAsn1(AbstractType $type)
    {
        if (!($type instanceof SequenceType && count($type->getChildren()) === 2)) {
            throw new ProtocolException('The compare request is malformed');
        }
        $dn = $type->getChild(0);
        $ava = $type->getChild(1);

        if (!$dn instanceof OctetStringType) {
            throw new ProtocolException('The compare request is malformed.');
        }

        return new self($dn->getValue(), EqualityFilter::fromAsn1($ava));
    }

    /**
     * {@inheritdoc}
     */
    public function toAsn1(): AbstractType
    {
        return Asn1::application(self::APP_TAG, Asn1::sequence(
            Asn1::octetString($this->dn->toString()),
            Asn1::universal(AbstractType::TAG_TYPE_SEQUENCE, $this->filter->toAsn1())
        ));
    }
}
