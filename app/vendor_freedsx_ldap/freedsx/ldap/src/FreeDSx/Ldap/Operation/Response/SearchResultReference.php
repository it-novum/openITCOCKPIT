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
use FreeDSx\Ldap\Exception\ProtocolException;
use FreeDSx\Ldap\Exception\UrlParseException;
use FreeDSx\Ldap\LdapUrl;

/**
 * A search result reference. RFC 4511, 4.5.3.
 *
 * SearchResultReference ::= [APPLICATION 19] SEQUENCE
 *     SIZE (1..MAX) OF uri URI
 *
 * @author Chad Sikorra <Chad.Sikorra@gmail.com>
 */
class SearchResultReference implements ResponseInterface
{
    protected const TAG_NUMBER = 19;

    /**
     * @var LdapUrl[]
     */
    protected $referrals = [];

    /**
     * @param LdapUrl[] ...$referrals
     */
    public function __construct(LdapUrl ...$referrals)
    {
        $this->referrals = $referrals;
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
    public static function fromAsn1(AbstractType $type)
    {
        $referrals = [];

        /** @var \FreeDSx\Asn1\Type\SequenceType $type */
        foreach ($type->getChildren() as $referral) {
            try {
                $referrals[] =  LdapUrl::parse($referral->getValue());
            } catch (UrlParseException $e) {
                throw new ProtocolException($e->getMessage());
            }

        }

        return new self(...$referrals);
    }

    /**
     * {@inheritdoc}
     */
    public function toAsn1(): AbstractType
    {
        return Asn1::application(self::TAG_NUMBER, Asn1::sequence(...array_map(function ($ref) {
            /** @var LdapUrl $ref */
            return Asn1::octetString($ref->toString());
        }, $this->referrals)));
    }
}
