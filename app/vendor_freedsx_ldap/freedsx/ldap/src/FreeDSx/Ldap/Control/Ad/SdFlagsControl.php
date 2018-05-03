<?php
/**
 * This file is part of the FreeDSx LDAP package.
 *
 * (c) Chad Sikorra <Chad.Sikorra@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FreeDSx\Ldap\Control\Ad;

use FreeDSx\Asn1\Asn1;
use FreeDSx\Asn1\Type\AbstractType;
use FreeDSx\Ldap\Control\Control;

/**
 * Represents a SD Flags Control for Active Directory.
 *
 * SDFlagsRequestValue ::= SEQUENCE {
 *     Flags    INTEGER
 * }
 *
 * @see https://msdn.microsoft.com/en-us/library/cc223323.aspx
 * @author Chad Sikorra <Chad.Sikorra@gmail.com>
 */
class SdFlagsControl extends Control
{
    /**
     * Owner identifier of the object.
     */
    public const OWNER_SECURITY_INFORMATION = 1;

    /**
     * Primary group identifier.
     */
    public const GROUP_SECURITY_INFORMATION = 2;

    /**
     * Discretionary access control list (DACL) of the object.
     */
    public const DACL_SECURITY_INFORMATION = 4;

    /**
     * System access control list (SACL) of the object.
     */
    public const SACL_SECURITY_INFORMATION = 8;

    /**
     * @var int
     */
    protected $flags;

    /**
     * @param int $flags
     */
    public function __construct(int $flags)
    {
        $this->flags = $flags;
        parent::__construct(self::OID_SD_FLAGS);
    }

    /**
     * @return int
     */
    public function getFlags() : int
    {
        return $this->flags;
    }

    /**
     * @param int $flags
     * @return $this
     */
    public function setFlags(int $flags)
    {
        $this->flags = $flags;

        return $this;
    }

    public function toAsn1(): AbstractType
    {
        $this->controlValue = Asn1::sequence(Asn1::integer($this->flags));

        return parent::toAsn1();
    }
}
