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
 * Represents an ASN.1 Real type.
 *
 * @author Chad Sikorra <Chad.Sikorra@gmail.com>
 */
class RealType extends AbstractType
{
    protected $tagNumber = self::TAG_TYPE_REAL;

    /**
     * @param float $value
     */
    public function __construct(float $value)
    {
        parent::__construct($value);
    }

    /**
     * @param float $value
     * @return RealType
     */
    public function setValue(float $value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * @return float
     */
    public function getValue() : float
    {
        return $this->value;
    }
}
