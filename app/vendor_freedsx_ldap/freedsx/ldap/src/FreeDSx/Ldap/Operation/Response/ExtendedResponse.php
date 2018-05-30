<?php
/**
 * This file is part of the FreeDSx LDAP package.
 *
 * (c) Chad Sikorra <Chad.Sikorra@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FreeDSx\Ldap\Operation\Response;

use FreeDSx\Asn1\Asn1;
use FreeDSx\Asn1\Type\AbstractType;
use FreeDSx\Asn1\Type\SequenceType;
use FreeDSx\Ldap\Exception\ProtocolException;
use FreeDSx\Ldap\Operation\LdapResult;
use FreeDSx\Ldap\Protocol\LdapEncoder;
use FreeDSx\Ldap\Protocol\ProtocolElementInterface;

/**
 * RFC 4511, 4.12
 *
 * ExtendedResponse ::= [APPLICATION 24] SEQUENCE {
 *     COMPONENTS OF LDAPResult,
 *         responseName     [10] LDAPOID OPTIONAL,
 *         responseValue    [11] OCTET STRING OPTIONAL }
 *
 * @author Chad Sikorra <Chad.Sikorra@gmail.com>
 */
class ExtendedResponse extends LdapResult
{
    protected $tagNumber = 24;

    /**
     * RFC 4511, 4.4.1. Used by the server to notify the client it is terminating the LDAP session.
     */
    public const OID_NOTICE_OF_DISCONNECTION = '1.3.6.1.4.1.1466.20036';

    /**
     * @var null|string
     */
    protected $responseName;

    /**
     * @var null|string
     */
    protected $responseValue;

    /**
     * @param LdapResult $result
     * @param null|string $responseName
     * @param null|string $responseValue
     */
    public function __construct(LdapResult $result, ?string $responseName = null, ?string $responseValue = null)
    {
        $this->responseValue = $responseValue;
        $this->responseName = $responseName;
        parent::__construct($result->getResultCode(), $result->getDn(), $result->getDiagnosticMessage(), ...$result->getReferrals());
    }

    /**
     * Get the OID name of the extended response.
     *
     * @return null|string
     */
    public function getName() : ?string
    {
        return $this->responseName;
    }

    /**
     * Get the value of the extended response.
     *
     * @return null|string
     */
    public function getValue() : ?string
    {
        return $this->responseValue;
    }

    /**
     * {@inheritdoc}
     */
    public static function fromAsn1(AbstractType $type)
    {
        return new self(
            self::createLdapResult($type),
            ...self::parseExtendedResponse($type)
        );
    }

    /**
     * {@inheritdoc}
     */
    public function toAsn1(): AbstractType
    {
        /** @var SequenceType $asn1 */
        $asn1 = parent::toAsn1();

        if ($this->responseName !== null) {
            $asn1->addChild(Asn1::context(10, Asn1::octetString($this->responseName)));
        }
        if ($this->responseValue !== null) {
            $encoder = new LdapEncoder();
            $value = $this->responseValue;
            if ($value instanceof AbstractType) {
                $value = $encoder->encode($value);
            } elseif ($value instanceof ProtocolElementInterface) {
                $value = $encoder->encode($value->toAsn1());
            }
            $asn1->addChild(Asn1::context(11, Asn1::octetString($value)));
        }

        return $asn1;
    }

    /**
     * @param AbstractType $type
     * @return array
     */
    protected static function parseExtendedResponse(AbstractType $type)
    {
        $info = [0 => null, 1 => null];

        /** @var \FreeDSx\Asn1\Type\SequenceType $type */
        foreach ($type->getChildren() as $child) {
            if ($child->getTagNumber() === 10) {
                $info[0] = $child->getValue();
            } elseif ($child->getTagNumber() === 11) {
                $info[1] = $child->getValue();
            }
        }

        return $info;
    }

    /**
     * @param AbstractType $type
     * @return LdapResult
     */
    protected static function createLdapResult(AbstractType $type)
    {
        [$resultCode, $dn, $diagnosticMessage, $referrals] = self::parseResultData($type);

        return new LdapResult($resultCode, $dn, $diagnosticMessage, ...$referrals);
    }

    /**
     * @param AbstractType $type
     * @return AbstractType|null
     * @throws ProtocolException
     */
    protected static function decodeEncodedValue(AbstractType $type)
    {
        if (!$type instanceof SequenceType) {
            throw new ProtocolException('The received control is malformed. Unable to get the encoded value.');
        }
        [1 => $value] = self::parseExtendedResponse($type);

        return $value === null ? null : (new LdapEncoder())->decode($value);
    }
}
