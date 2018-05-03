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
use FreeDSx\Ldap\Exception\ProtocolException;
use FreeDSx\Ldap\Protocol\ProtocolElementInterface;

/**
 * Resolves protocol operation tags and ASN1 to specific classes.
 *
 * @author Chad Sikorra <Chad.Sikorra@gmail.com>
 */
class OperationFactory
{
    /**
     * @var string[]
     */
    protected static $map = [
        0 => 'FreeDSx\Ldap\Operation\Request\BindRequest',
        1 => 'FreeDSx\Ldap\Operation\Response\BindResponse',
        2 => 'FreeDSx\Ldap\Operation\Request\UnbindRequest',
        3 => 'FreeDSx\Ldap\Operation\Request\SearchRequest',
        4 => 'FreeDSx\Ldap\Operation\Response\SearchResultEntry',
        5 => 'FreeDSx\Ldap\Operation\Response\SearchResultDone',
        6 => 'FreeDSx\Ldap\Operation\Request\ModifyRequest',
        7 => 'FreeDSx\Ldap\Operation\Response\ModifyResponse',
        8 => 'FreeDSx\Ldap\Operation\Request\AddRequest',
        9 => 'FreeDSx\Ldap\Operation\Response\AddResponse',
        10 => 'FreeDSx\Ldap\Operation\Request\DeleteRequest',
        11 => 'FreeDSx\Ldap\Operation\Response\DeleteResponse',
        12 => 'FreeDSx\Ldap\Operation\Request\ModifyDnRequest',
        13 => 'FreeDSx\Ldap\Operation\Response\ModifyDnResponse',
        14 => 'FreeDSx\Ldap\Operation\Request\CompareRequest',
        15 => 'FreeDSx\Ldap\Operation\Response\CompareResponse',
        19 => 'FreeDSx\Ldap\Operation\Response\SearchResultReference',
        23 => 'FreeDSx\Ldap\Operation\Request\ExtendedRequest',
        24 => 'FreeDSx\Ldap\Operation\Response\ExtendedResponse',
        25 => 'FreeDSx\Ldap\Operation\Response\IntermediateResponse',
    ];

    /**
     * Retrieve the Request Response/Request class given a protocol number and the ASN1.
     *
     * @param AbstractType $asn1
     * @return ProtocolElementInterface
     * @throws ProtocolException
     */
    public static function get(AbstractType $asn1)
    {
        if (!isset(self::$map[$asn1->getTagNumber()])) {
            throw new ProtocolException(sprintf(
                'There is no class mapped for protocol operation %s.',
                $asn1->getTagNumber()
            ));
        }

        return call_user_func(self::$map[$asn1->getTagNumber()].'::fromAsn1', $asn1);
    }

    /**
     * Check whether a specific operation is mapped to a class.
     *
     * @param int $operation
     * @return bool
     */
    public static function has(int $operation) : bool
    {
        return isset(self::$map[$operation]);
    }

    /**
     * Set a specific class for an operation. It must implement ProtocolElementInterface.
     *
     * @param int $operation
     * @param $className
     */
    public static function set(int $operation, $className) : void
    {
        if (!class_exists($className)) {
            throw new InvalidArgumentException(sprintf(
               'The class for operation %s does not exist: %s',
               $operation,
               $className
            ));
        }
        if (!is_subclass_of($className, ProtocolElementInterface::class)) {
            throw new InvalidArgumentException(sprintf(
               'The class must implement ProtocolElementInterface, but it does not: %s',
               $className
            ));
        }
        self::$map[$operation] = $className;
    }
}
