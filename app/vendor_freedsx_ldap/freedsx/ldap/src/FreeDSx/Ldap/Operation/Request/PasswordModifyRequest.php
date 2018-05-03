<?php
/**
 * This file is part of the FreeDSx LDAP package.
 *
 * (c) Chad Sikorra <Chad.Sikorra@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FreeDSx\Ldap\Operation\Request;

use FreeDSx\Asn1\Asn1;
use FreeDSx\Asn1\Type\AbstractType;
use FreeDSx\Asn1\Type\SequenceType;
use FreeDSx\Ldap\Exception\ProtocolException;

/**
 * RFC 3062. A password modify extended request.
 *
 * PasswdModifyRequestValue ::= SEQUENCE {
 *     userIdentity    [0]  OCTET STRING OPTIONAL
 *     oldPasswd       [1]  OCTET STRING OPTIONAL
 *     newPasswd       [2]  OCTET STRING OPTIONAL }
 *
 * @author Chad Sikorra <Chad.Sikorra@gmail.com>
 */
class PasswordModifyRequest extends ExtendedRequest
{
    /**
     * @var null|string
     */
    protected $userIdentity;

    /**
     * @var null|string
     */
    protected $oldPassword;

    /**
     * @var null|string
     */
    protected $newPassword;

    /**
     * @param null|string $userIdentity
     * @param null|string $oldPassword
     * @param null|string $newPassword
     */
    public function __construct(?string $userIdentity = null, ?string $oldPassword = null, ?string $newPassword = null)
    {
        $this->userIdentity = $userIdentity;
        $this->oldPassword = $oldPassword;
        $this->newPassword = $newPassword;
        parent::__construct(self::OID_PWD_MODIFY);
    }

    /**
     * @return null|string
     */
    public function getUsername() : ?string
    {
        return $this->userIdentity;
    }

    /**
     * @param null|string $username
     * @return $this
     */
    public function setUsername(?string $username)
    {
        $this->userIdentity = $username;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getNewPassword() : ?string
    {
        return $this->newPassword;
    }

    /**
     * @param null|string $newPassword
     * @return $this
     */
    public function setNewPassword(?string $newPassword)
    {
        $this->newPassword = $newPassword;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getOldPassword() : ?string
    {
        return $this->oldPassword;
    }

    /**
     * @param null|string $oldPassword
     * @return $this
     */
    public function setOldPassword(?string $oldPassword)
    {
        $this->oldPassword = $oldPassword;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function toAsn1(): AbstractType
    {
        $this->requestValue = Asn1::sequence();

        if ($this->userIdentity !== null) {
            $this->requestValue->addChild(Asn1::context(0, Asn1::octetString($this->userIdentity)));
        }
        if ($this->oldPassword !== null) {
            $this->requestValue->addChild(Asn1::context(1, Asn1::octetString($this->oldPassword)));
        }
        if ($this->newPassword !== null) {
            $this->requestValue->addChild(Asn1::context(2, Asn1::octetString($this->newPassword)));
        }

        return parent::toAsn1();
    }

    /**
     * {@inheritdoc}
     */
    public static function fromAsn1(AbstractType $type)
    {
        $request = self::decodeEncodedValue($type);
        if (!$request) {
            return new self();
        }
        if (!($request instanceof SequenceType)) {
            throw new ProtocolException('The password modify request is malformed.');
        }

        $userIdentity = null;
        $oldPasswd = null;
        $newPasswd = null;
        foreach ($request as $value) {
            /** @var AbstractType $value */
            if ($value->getTagClass() !== AbstractType::TAG_CLASS_CONTEXT_SPECIFIC) {
                throw new ProtocolException('The password modify request is malformed');
            }
            if ($value->getTagNumber() === 0) {
                $userIdentity = $value;
            } elseif ($value->getTagNumber() === 1) {
                $oldPasswd = $value;
            } elseif ($value->getTagNumber() === 2) {
                $newPasswd = $value;
            }
        }

        return new self(
            $userIdentity !== null ? $userIdentity->getValue() : null,
            $oldPasswd !== null ? $oldPasswd->getValue() : null,
            $newPasswd !== null ? $newPasswd->getValue() : null
        );
    }
}
