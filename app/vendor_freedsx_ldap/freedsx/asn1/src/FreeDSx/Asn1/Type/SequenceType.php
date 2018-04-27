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
 * Represents a Sequence type.
 *
 * @author Chad Sikorra <Chad.Sikorra@gmail.com>
 */
class SequenceType extends AbstractType
{
    /**
     * @var int
     */
    protected $tagNumber = self::TAG_TYPE_SEQUENCE;

    /**
     * @param AbstractType[] ...$types
     */
    public function __construct(AbstractType ...$types)
    {
        parent::__construct(null);
        $this->setIsConstructed(true);
        $this->setChildren(...$types);
    }
}
