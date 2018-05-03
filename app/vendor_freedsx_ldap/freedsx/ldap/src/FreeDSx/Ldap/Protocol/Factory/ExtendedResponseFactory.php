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
use FreeDSx\Ldap\Exception\InvalidArgumentException;
use FreeDSx\Ldap\Operation\Request\ExtendedRequest;
use FreeDSx\Ldap\Operation\Response\ExtendedResponse;
use FreeDSx\Ldap\Operation\Response\PasswordModifyResponse;

/**
 * Used to instantiate specific extended response OIDs.
 *
 * @author Chad Sikorra <Chad.Sikorra@gmail.com>
 */
class ExtendedResponseFactory
{
    /**
     * @var string[]
     */
    protected static $map = [
        ExtendedRequest::OID_PWD_MODIFY => PasswordModifyResponse::class,
    ];

    /**
     * Retrieve the Request Response/Request class given a protocol number and the ASN1.
     *
     * @param AbstractType $asn1
     * @param string $oid
     * @return null|ExtendedResponse
     */
    public static function get(AbstractType $asn1, string $oid) : ?ExtendedResponse
    {
        if (!self::has($oid)) {
            return null;
        }

        return call_user_func(self::$map[$oid].'::fromAsn1', $asn1);
    }

    /**
     * Check whether a specific control OID is mapped to a class.
     *
     * @param string $oid
     * @return bool
     */
    public static function has(string $oid)
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
                'The class for the extended response %s does not exist: %s',
                $oid,
                $className
            ));
        }
        if (!is_subclass_of($className, ExtendedResponse::class)) {
            throw new InvalidArgumentException(sprintf(
                'The class must extend the ExtendedResponse, but it does not: %s',
                $className
            ));
        }
        self::$map[$oid] = $className;
    }
}
