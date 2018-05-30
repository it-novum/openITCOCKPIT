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
use FreeDSx\Ldap\Exception\BindException;

/**
 * Represents a simple bind request consisting of a username (dn, etc) and a password.
 *
 * @author Chad Sikorra <Chad.Sikorra@gmail.com>
 */
class SimpleBindRequest extends BindRequest
{
    /**
     * @var string
     */
    protected $password;

    /**
     * @param string $username
     * @param string $password
     * @param int $version
     */
    public function __construct(string $username, string $password, int $version = 3)
    {
        $this->username = $username;
        $this->password = $password;
        $this->version = $version;
    }

    /**
     * @return string
     */
    public function getPassword() : string
    {
        return $this->password;
    }

    /**
     * @param string $password
     * @return $this
     */
    public function setPassword(string $password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    protected function getAsn1AuthChoice(): AbstractType
    {
        return Asn1::context(0, Asn1::octetString($this->password));
    }

    /**
     * {@inheritdoc}
     */
    protected function validate(): void
    {
        if ($this->isEmpty($this->username) || $this->isEmpty($this->password)) {
            throw new BindException('A simple bind must have a non-empty username and password.');
        }
    }

    /**
     * @param $value
     * @return bool
     */
    protected function isEmpty($value)
    {
        return empty($value) && $value !== '0';
    }
}
