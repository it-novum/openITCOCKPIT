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
 * Represents a generic authentication token.
 *
 * @author Chad Sikorra <Chad.Sikorra@gmail.com>
 */
interface TokenInterface
{
    /**
     * @return null|string
     */
    public function getUsername() : ?string;

    /**
     * @return null|string
     */
    public function getPassword() : ?string;

    /**
     * @return int
     */
    public function getVersion() : int;
}
