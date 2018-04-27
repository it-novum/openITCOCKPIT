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
use FreeDSx\Asn1\Type\IntegerType;
use FreeDSx\Asn1\Type\OctetStringType;
use FreeDSx\Asn1\Type\SequenceType;
use FreeDSx\Ldap\Exception\ProtocolException;

/**
 * Represents a base bind request. RFC 4511, 4.2
 *
 * BindRequest ::= [APPLICATION 0] SEQUENCE {
 *     version                 INTEGER (1 ..  127),
 *     name                    LDAPDN,
 *     authentication          AuthenticationChoice }
 *
 * @author Chad Sikorra <Chad.Sikorra@gmail.com>
 */
abstract class BindRequest implements RequestInterface
{
    protected const APP_TAG = 0;

    /**
     * @var int
     */
    protected $version = 3;

    /**
     * @var string
     */
    protected $username;

    /**
     * @param int $version
     * @return $this
     */
    public function setVersion(int $version)
    {
        $this->version = $version;

        return $this;
    }

    /**
     * @return int
     */
    public function getVersion() : int
    {
        return $this->version;
    }

    /**
     * @param string $username
     * @return $this
     */
    public function setUsername(string $username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @return string
     */
    public function getUsername() : string
    {
        return $this->username;
    }

    /**
     * {@inheritdoc}
     */
    public function toAsn1(): AbstractType
    {
        $this->validate();

        return Asn1::application(self::APP_TAG, Asn1::sequence(
            Asn1::integer($this->version),
            Asn1::octetString($this->username),
            $this->getAsn1AuthChoice()
        ));
    }

    /**
     * {@inheritdoc}
     */
    public static function fromAsn1(AbstractType $type)
    {
        if (!($type instanceof SequenceType && count($type) === 3)) {
            throw new ProtocolException('The bind request in malformed');
        }
        $version = $type->getChild(0);
        $name = $type->getChild(1);
        $auth = $type->getChild(2);

        if (!($version instanceof IntegerType && $name instanceof OctetStringType)) {
            throw new ProtocolException('The bind request in malformed');
        }
        $version = $version->getValue();
        $name = $name->getValue();

        if ($auth->getTagNumber() !== 0) {
            throw new ProtocolException(sprintf(
                'Only a simple bind is currently supported, but got: %s',
                $auth->getTagNumber()
            ));
        }
        $auth = $auth->getValue();

        if (empty($auth) && $auth !== '0') {
            return new AnonBindRequest($name, $version);
        } else {
            return new SimpleBindRequest($name, $auth, $version);
        }
    }

    /**
     * Get the ASN1 AuthenticationChoice for the bind request.
     *
     * @return AbstractType
     */
    abstract protected function getAsn1AuthChoice() : AbstractType;

    /**
     * This is called as the request is transformed to ASN1 to be encoded. If the request parameters are not valid
     * then the method should throw an exception.
     */
    abstract protected function validate() : void;
}
