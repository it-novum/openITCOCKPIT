<?php
/**
 * This file is part of the FreeDSx ASN1 package.
 *
 * (c) Chad Sikorra <Chad.Sikorra@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FreeDSx\Asn1\Type;

/**
 * Represents a Relative OID type.
 *
 * @author Chad Sikorra <Chad.Sikorra@gmail.com>
 */
class RelativeOidType extends AbstractType
{
    protected $tagNumber = self::TAG_TYPE_RELATIVE_OID;

    /**
     * @param string $value
     */
    public function __construct(string $value)
    {
        parent::__construct($value);
    }

    /**
     * @param string $relativeOid
     * @return $this
     */
    public function setValue(string $relativeOid)
    {
        $this->value = $relativeOid;

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
     * @return string
     */
    public function __toString()
    {
        return (string) $this->value;
    }
}
