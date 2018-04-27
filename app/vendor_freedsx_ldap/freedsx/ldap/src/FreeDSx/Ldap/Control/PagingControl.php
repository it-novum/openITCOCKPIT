<?php
/**
 * This file is part of the FreeDSx LDAP package.
 *
 * (c) Chad Sikorra <Chad.Sikorra@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FreeDSx\Ldap\Control;

use FreeDSx\Asn1\Asn1;
use FreeDSx\Asn1\Type\AbstractType;
use FreeDSx\Asn1\Type\IntegerType;
use FreeDSx\Asn1\Type\OctetStringType;
use FreeDSx\Asn1\Type\SequenceType;
use FreeDSx\Ldap\Exception\ProtocolException;

/**
 * Represents a paged results control request value. RFC 2696.
 *
 * realSearchControlValue ::= SEQUENCE {
 *     size            INTEGER (0..maxInt),
 *                          -- requested page size from client
 *                          -- result set size estimate from server
 *     cookie          OCTET STRING }
 *
 * @author Chad Sikorra <Chad.Sikorra@gmail.com>
 */
class PagingControl extends Control
{
    /**
     * @var string
     */
    protected $cookie;

    /**
     * @var int
     */
    protected $size;

    /**
     * @param int $size
     * @param string $cookie
     */
    public function __construct(int $size, string $cookie = '')
    {
        $this->size = $size;
        $this->cookie = $cookie;
        parent::__construct(self::OID_PAGING);
    }

    /**
     * @return string
     */
    public function getCookie() : string
    {
        return $this->cookie;
    }

    /**
     * @return int
     */
    public function getSize() : int
    {
        return $this->size;
    }

    /**
     * @param string $cookie
     * @return $this
     */
    public function setCookie(string $cookie)
    {
        $this->cookie = $cookie;

        return $this;
    }

    /**
     * @param int $size
     * @return $this
     */
    public function setSize(int $size)
    {
        $this->size = $size;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public static function fromAsn1(AbstractType $type)
    {
        $paging = self::decodeEncodedValue($type);
        self::validate($paging);

        /** @var SequenceType $paging */
        $control = new self($paging->getChild(0)->getValue(), $paging->getChild(1)->getValue());

        return self::mergeControlData($control, $type);
    }

    /**
     * {@inheritdoc}
     */
    public function toAsn1() : AbstractType
    {
        $this->controlValue = Asn1::sequence(
            Asn1::integer($this->size),
            Asn1::octetString($this->cookie)
        );

        return parent::toAsn1();
    }

    /**
     * @param AbstractType $type
     * @throws ProtocolException
     */
    protected static function validate(AbstractType $type)
    {
        if (!($type instanceof SequenceType && count($type) === 2)) {
            throw new ProtocolException('A paged control value must be a sequence type with 2 children.');
        }
        if (!$type->getChild(0) instanceof IntegerType) {
            throw new ProtocolException('A paged control value sequence 0 must be an integer type.');
        }
        if (!$type->getChild(1) instanceof OctetStringType) {
            throw new ProtocolException('A paged control value sequence 1 must be an octet string type.');
        }
    }
}
