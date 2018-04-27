<?php
/**
 * This file is part of the FreeDSx LDAP package.
 *
 * (c) Chad Sikorra <Chad.Sikorra@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FreeDSx\Ldap\Control\Vlv;

/**
 * Some common VLV methods/properties.
 *
 * @author Chad Sikorra <Chad.Sikorra@gmail.com>
 */
trait VlvTrait
{
    /**
     * @var int|null
     */
    protected $count;

    /**
     * @var null|string
     */
    protected $contextId;

    /**
     * @var int|null
     */
    protected $offset;

    /**
     * @return null|string
     */
    public function getContextId() : ?string
    {
        return $this->contextId;
    }

    /**
     * @return int
     */
    public function getOffset() : ?int
    {
        return $this->offset;
    }

    /**
     * @return int
     */
    public function getCount() : ?int
    {
        return $this->count;
    }
}
