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
 * RFC 3062. A Modify Password Response.
 *
 * PasswdModifyResponseValue ::= SEQUENCE {
 *     genPasswd       [0]     OCTET STRING OPTIONAL }
 *
 * @author Chad Sikorra <Chad.Sikorra@gmail.com>
 */
class PasswordModifyResponse extends ExtendedResponse
{
    /**
     * @var null|string
     */
    protected $generatedPassword;

    /**
     * @param LdapResult $result
     * @param null|string $generatedPassword
     */
    public function __construct(LdapResult $result, ?string $generatedPassword = null)
    {
        $this->generatedPassword = $generatedPassword;
        parent::__construct($result);
    }

    /**
     * @return null|string
     */
    public function getGeneratedPassword() : ?string
    {
        return $this->generatedPassword;
    }

    /**
     * {@inheritdoc}
     */
    public function toAsn1(): AbstractType
    {
        if ($this->generatedPassword !== null) {
            $this->responseValue = Asn1::sequence(Asn1::context(0, Asn1::octetString($this->generatedPassword)));
        }

        return parent::toAsn1();
    }

    /**
     * {@inheritdoc}
     */
    public static function fromAsn1(AbstractType $type)
    {
        $result = self::createLdapResult($type);
        $generatedPassword = null;

        $pwdResponse = self::decodeEncodedValue($type);
        if ($pwdResponse && $pwdResponse instanceof SequenceType) {
            foreach ($pwdResponse->getChildren() as $child) {
                if ($child->getTagNumber() === 0) {
                    $generatedPassword = $child->getValue();
                }
            }
        }

        return new self($result, $generatedPassword);
    }
}
