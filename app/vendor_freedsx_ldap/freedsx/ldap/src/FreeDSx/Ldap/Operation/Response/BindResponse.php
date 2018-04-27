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
use FreeDSx\Ldap\Operation\LdapResult;

/**
 * RFC 4511 Section 4.2.2
 *
 * BindResponse ::= [APPLICATION 1] SEQUENCE {
 *     COMPONENTS OF LDAPResult,
 *     serverSaslCreds    [7] OCTET STRING OPTIONAL }
 *
 * @author Chad Sikorra <Chad.Sikorra@gmail.com>
 */
class BindResponse extends LdapResult
{
    protected $tagNumber = 1;

    /**
     * @var null|string
     */
    protected $saslCreds;

    /**
     * @param LdapResult $result
     * @param null|string $saslCreds
     */
    public function __construct(LdapResult $result, ?string $saslCreds = null)
    {
        $this->saslCreds = $saslCreds;
        parent::__construct($result->getResultCode(), $result->getDn(), $result->getDiagnosticMessage(), ...$result->getReferrals());
    }

    /**
     * @return null|string
     */
    public function getSaslCredentials() : ?string
    {
        return $this->saslCreds;
    }

    /**
     * {@inheritdoc}
     */
    public function toAsn1(): AbstractType
    {
        /** @var SequenceType $response */
        $response = parent::toAsn1();

        if ($this->saslCreds !== null) {
            $response->addChild(Asn1::context(7, Asn1::octetString($this->saslCreds)));
        }

        return $response;
    }

    /**
     * {@inheritdoc}
     */
    public static function fromAsn1(AbstractType $type)
    {
        [$resultCode, $dn, $diag, $ref] = self::parseResultData($type);
        $saslCreds = null;

        /** @var SequenceType $type */
        foreach ($type->getChildren() as $child) {
            if ($child->getTagNumber() === 7 && $child->getTagClass() === AbstractType::TAG_CLASS_CONTEXT_SPECIFIC) {
                $saslCreds = $child->getValue();
                break;
            }
        }

        return new self(new LdapResult($resultCode, $dn, $diag, ...$ref), $saslCreds);
    }
}
