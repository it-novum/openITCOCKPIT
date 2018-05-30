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
 * Represents an ASN1 set type.
 *
 * @author Chad Sikorra <Chad.Sikorra@gmail.com>
 */
class SetType extends AbstractType
{
    use SetTrait;

    protected $tagNumber = self::TAG_TYPE_SET;

    /**
     * @param AbstractType[] ...$types
     */
    public function __construct(AbstractType ...$types)
    {
        parent::__construct(null);
        $this->setIsConstructed(true);
        $this->setChildren(...$types);
    }

    /**
     * X.680, 8.6
     *
     * Used to determine if the set is in canonical order, which is required by some encodings for a SET.
     */
    public function isCanonical() : bool
    {
        return $this->children === $this->canonicalize(...$this->children);
    }
}
