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
use FreeDSx\Ldap\Entry\Dn;
use FreeDSx\Ldap\Exception\ProtocolException;

/**
 * RFC 4511, 4.8
 *
 * DelRequest ::= [APPLICATION 10] LDAPDN
 *
 * @author Chad Sikorra <Chad.Sikorra@gmail.com>
 */
class DeleteRequest implements RequestInterface, DnRequestInterface
{
    protected const APP_TAG = 10;

    /**
     * @var Dn
     */
    protected $dn;

    /**
     * @param string $dn
     */
    public function __construct($dn)
    {
        $this->setDn($dn);
    }

    /**
     * @param string $dn
     * @return $this
     */
    public function setDn($dn)
    {
        $this->dn = $dn instanceof Dn ? $dn : new Dn($dn);

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
    public function toAsn1(): AbstractType
    {
        return Asn1::application(self::APP_TAG, Asn1::octetString($this->dn->toString()));
    }

    /**
     * {@inheritdoc}
     */
    public static function fromAsn1(AbstractType $type)
    {
        self::validate($type);

        return new self($type->getValue());
    }

    /**
     * @param AbstractType $type
     * @throws ProtocolException
     */
    protected static function validate(AbstractType $type) : void
    {
        if (!$type instanceof OctetStringType || $type->getTagClass() !== AbstractType::TAG_CLASS_APPLICATION) {
            throw new ProtocolException('The delete request must be an octet string with an application tag class.');
        }
        if ($type->getTagNumber() !== self::APP_TAG) {
            throw new ProtocolException(sprintf(
                'The delete request must have an app tag of %s, received: %s',
                self::APP_TAG,
                $type->getTagNumber()
            ));
        }
    }
}
