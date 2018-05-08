<?php
/**
 * This file is part of the FreeDSx LDAP package.
 *
 * (c) Chad Sikorra <Chad.Sikorra@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FreeDSx\Ldap\Server\Token;

/**
 * Represents a username/password token that is bound and authorized.
 *
 * @author Chad Sikorra <Chad.Sikorra@gmail.com>
 */
class BindToken implements TokenInterface
{
    /**
     * @var string
     */
    protected $username;

    /**
     * @var string
     */
    protected $password;

    /**
     * @var int
     */
    protected $version;

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
     * {@inheritdoc}
     */
    public function getUsername() : ?string
    {
        return $this->username;
    }

    /**
     * {@inheritdoc}
     */
    public function getPassword() : ?string
    {
        return $this->password;
    }

    /**
     * {@inheritdoc}
     */
    public function getVersion() : int
    {
        return $this->version;
    }
}
