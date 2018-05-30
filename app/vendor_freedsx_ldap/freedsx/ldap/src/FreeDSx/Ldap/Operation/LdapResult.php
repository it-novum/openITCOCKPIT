<?php
/**
 * This file is part of the FreeDSx LDAP package.
 *
 * (c) Chad Sikorra <Chad.Sikorra@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FreeDSx\Ldap\Operation;

use FreeDSx\Asn1\Asn1;
use FreeDSx\Asn1\Type\AbstractType;
use FreeDSx\Asn1\Type\SequenceType;
use FreeDSx\Ldap\Protocol\LdapEncoder;
use FreeDSx\Ldap\Entry\Dn;
use FreeDSx\Ldap\Exception\ProtocolException;
use FreeDSx\Ldap\Exception\UrlParseException;
use FreeDSx\Ldap\LdapUrl;
use FreeDSx\Ldap\Operation\Response\ResponseInterface;

/**
 * Represents the result of an operation request. RFC 4511, 4.1.9
 *
 * LDAPResult ::= SEQUENCE {
 *     resultCode         ENUMERATED {
 *         success                      (0),
 *         operationsError              (1),
 *         protocolError                (2),
 *         timeLimitExceeded            (3),
 *         sizeLimitExceeded            (4),
 *         compareFalse                 (5),
 *         compareTrue                  (6),
 *         authMethodNotSupported       (7),
 *         strongerAuthRequired         (8),
 *         -- 9 reserved --
 *         referral                     (10),
 *         adminLimitExceeded           (11),
 *         unavailableCriticalExtension (12),
 *         confidentialityRequired      (13),
 *         saslBindInProgress           (14),
 *         noSuchAttribute              (16),
 *         undefinedAttributeType       (17),
 *         inappropriateMatching        (18),
 *         constraintViolation          (19),
 *         attributeOrValueExists       (20),
 *         invalidAttributeSyntax       (21),
 *         -- 22-31 unused --
 *         noSuchObject                 (32),
 *         aliasProblem                 (33),
 *         invalidDNSyntax              (34),
 *         -- 35 reserved for undefined isLeaf --
 *         aliasDereferencingProblem    (36),
 *         -- 37-47 unused --
 *         inappropriateAuthentication  (48),
 *         invalidCredentials           (49),
 *         insufficientAccessRights     (50),
 *         busy                         (51),
 *         unavailable                  (52),
 *         unwillingToPerform           (53),
 *         loopDetect                   (54),
 *         -- 55-63 unused --
 *         namingViolation              (64),
 *         objectClassViolation         (65),
 *         notAllowedOnNonLeaf          (66),
 *         notAllowedOnRDN              (67),
 *         entryAlreadyExists           (68),
 *         objectClassModsProhibited    (69),
 *         -- 70 reserved for CLDAP --
 *         affectsMultipleDSAs          (71),
 *         -- 72-79 unused --
 *         other                        (80),
 *         ...  },
 *     matchedDN          LDAPDN,
 *     diagnosticMessage  LDAPString,
 *     referral           [3] Referral OPTIONAL }
 *
 * @author Chad Sikorra <Chad.Sikorra@gmail.com>
 */
class LdapResult implements ResponseInterface
{
    /**
     * @var int
     */
    protected $tagNumber;

    /**
     * @var int
     */
    protected $resultCode;

    /**
     * @var Dn
     */
    protected $dn;

    /**
     * @var string
     */
    protected $diagnosticMessage;

    /**
     * @var LdapUrl[]
     */
    protected $referrals = [];

    /**
     * @param int $resultCode
     * @param string $dn
     * @param string $diagnosticMessage
     * @param LdapUrl[] $referrals
     */
    public function __construct(int $resultCode, string $dn = '', string $diagnosticMessage = '', LdapUrl ...$referrals)
    {
        $this->resultCode = $resultCode;
        $this->dn = new Dn($dn);
        $this->diagnosticMessage = $diagnosticMessage;
        $this->referrals = $referrals;
    }

    /**
     * @return string
     */
    public function getDiagnosticMessage() : string
    {
        return $this->diagnosticMessage;
    }

    /**
     * @return Dn
     */
    public function getDn() : Dn
    {
        return $this->dn;
    }

    /**
     * @return LdapUrl[]
     */
    public function getReferrals() : array
    {
        return $this->referrals;
    }

    /**
     * {@inheritdoc}
     */
    public function getResultCode() : int
    {
        return $this->resultCode;
    }

    /**
     * {@inheritdoc}
     */
    public function toAsn1() : AbstractType
    {
        $result = Asn1::sequence(
            Asn1::enumerated($this->resultCode),
            Asn1::octetString($this->dn),
            Asn1::octetString($this->diagnosticMessage)
        );
        if (!empty($this->referrals)) {
            $result->addChild(Asn1::context(3, Asn1::sequence(...array_map(function ($v) {
                return Asn1::octetString($v);
            }, $this->referrals))));
        }
        if ($this->tagNumber === null) {
            throw new ProtocolException(sprintf('You must define the tag number property on %s', get_parent_class()));
        }

        return Asn1::application($this->tagNumber, $result);
    }

    /**
     * {@inheritdoc}
     */
    public static function fromAsn1(AbstractType $type)
    {
        [$resultCode, $dn, $diagnosticMessage, $referrals] = self::parseResultData($type);

        return new static($resultCode, $dn, $diagnosticMessage, ...$referrals);
    }

    /**
     * @param AbstractType $type
     * @return array
     * @throws ProtocolException
     */
    protected static function parseResultData(AbstractType $type)
    {
        if (!$type instanceof SequenceType) {
            throw new ProtocolException('The LDAP result is malformed.');
        }
        $referrals = [];

        /** @var \FreeDSx\Asn1\Type\SequenceType $type*/
        foreach ($type->getChildren() as $child) {
            if ($child->getTagClass() === AbstractType::TAG_CLASS_CONTEXT_SPECIFIC && $child->getTagNumber() === 3) {
                /** @var \FreeDSx\Asn1\Type\SequenceType $child */
                $child = (new LdapEncoder())->complete($child, AbstractType::TAG_TYPE_SEQUENCE);
                foreach ($child->getChildren() as $ldapUrl) {
                    try {
                        $referrals[] = LdapUrl::parse($ldapUrl->getValue());
                    } catch (UrlParseException $e) {
                        throw new ProtocolException($e->getMessage());
                    }
                }
            }
        }

        return [
            $type->getChild(0)->getValue(),
            $type->getChild(1)->getValue(),
            $type->getChild(2)->getValue(),
            $referrals
        ];
    }
}
