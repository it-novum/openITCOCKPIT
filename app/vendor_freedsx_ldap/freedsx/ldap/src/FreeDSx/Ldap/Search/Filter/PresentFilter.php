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
use FreeDSx\Ldap\Exception\ProtocolException;
use FreeDSx\Ldap\Protocol\LdapEncoder;

/**
 * Checks for the presence of an attribute (ie. whether or not it contains a value). RFC 4511, 4.5.1.7.5
 *
 * @author Chad Sikorra <Chad.Sikorra@gmail.com>
 */
class PresentFilter implements FilterInterface
{
    use FilterAttributeTrait;

    protected const APP_TAG = 7;

    /**
     * @param string $attribute
     */
    public function __construct(string $attribute)
    {
        $this->attribute = $attribute;
    }

    /**
     * {@inheritdoc}
     */
    public function toAsn1() : AbstractType
    {
        return Asn1::context(self::APP_TAG, Asn1::octetString($this->attribute));
    }

    /**
     * {@inheritdoc}
     */
    public function toString() : string
    {
        return self::PAREN_LEFT.$this->attribute.self::FILTER_EQUAL.'*'.self::PAREN_RIGHT;
    }

    /**
     * {@inheritdoc}
     */
    public static function fromAsn1(AbstractType $type)
    {
        $type = $type instanceof IncompleteType ? (new LdapEncoder())->complete($type, AbstractType::TAG_TYPE_OCTET_STRING) : $type;
        if (!($type instanceof OctetStringType)) {
            throw new ProtocolException('The present filter is malformed');
        }

        return new self($type->getValue());
    }
}
