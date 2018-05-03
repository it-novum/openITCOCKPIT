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
 * Represents an ASN1 integer type.
 *
 * @author Chad Sikorra <Chad.Sikorra@gmail.com>
 */
class IntegerType extends AbstractType
{
    protected $tagNumber = self::TAG_TYPE_INTEGER;

    public function __construct(int $integer)
    {
        parent::__construct($integer);
    }

    /**
     * @param int $value
     * @return $this
     */
    public function setValue(int $value)
    {
        $this->value = $value;

        return $this;
    }
}
