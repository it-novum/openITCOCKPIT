<?php
/**
 * This file is part of the FreeDSx LDAP package.
 *
 * (c) Chad Sikorra <Chad.Sikorra@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FreeDSx\Ldap\Protocol\Factory;

use FreeDSx\Asn1\Type\AbstractType;
use FreeDSx\Asn1\Type\OctetStringType;
use FreeDSx\Asn1\Type\SequenceType;
use FreeDSx\Ldap\Control\Control;
use FreeDSx\Ldap\Control\PagingControl;
use FreeDSx\Ldap\Control\Sorting\SortingResponseControl;
use FreeDSx\Ldap\Control\Vlv\VlvResponseControl;
use FreeDSx\Ldap\Exception\InvalidArgumentException;
use FreeDSx\Ldap\Exception\ProtocolException;
use FreeDSx\Ldap\Protocol\ProtocolElementInterface;

/**
 * Used to instantiate the controls in the LDAP message envelope.
 *
 * @author Chad Sikorra <Chad.Sikorra@gmail.com>
 */
class ControlFactory
{
    /**
     * @var string[]
     */
    protected static $map = [
        Control::OID_PAGING => PagingControl::class,
        Control::OID_SORTING_RESPONSE => SortingResponseControl::class,
        Control::OID_VLV_RESPONSE => VlvResponseControl::class,
    ];

    /**
     * Retrieve the control class for a given OID and ASN1.
     *
     * @param AbstractType $asn1
     * @return Control
     * @throws ProtocolException
     */
    public static function get(AbstractType $asn1) : Control
    {
        if (!($asn1 instanceof SequenceType && $asn1->getChild(0) && $asn1->getChild(0) instanceof OctetStringType)) {
            throw new ProtocolException('The control either is not a sequence or has no OID value attached.');
        }
        $oid = $asn1->getChild(0)->getValue();

        return call_user_func((self::$map[$oid] ?? Control::class).'::fromAsn1', $asn1);
    }

    /**
     * Check whether a specific control OID is mapped to a class.
     *
     * @param string $oid
     * @return bool
     */
    public static function has(string $oid) : bool
    {
        return isset(self::$map[$oid]);
    }

    /**
     * Set a specific class for an operation. It must implement ProtocolElementInterface.
     *
     * @param string $oid
     * @param $className
     */
    public static function set(string $oid, $className) : void
    {
        if (!class_exists($className)) {
            throw new InvalidArgumentException(sprintf(
                'The class for control %s does not exist: %s',
                $oid,
                $className
            ));
        }
        if (!is_subclass_of($className, ProtocolElementInterface::class)) {
            throw new InvalidArgumentException(sprintf(
                'The class must implement ProtocolElementInterface, but it does not: %s',
                $className
            ));
        }
        self::$map[$oid] = $className;
    }
}
