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

use FreeDSx\Ldap\Entry\Dn;

/**
 * Used on requests that work against specific DNs. This is needed to follow referrals for these requests.
 *
 * @author Chad Sikorra <Chad.Sikorra@gmail.com>
 */
interface DnRequestInterface
{
    /**
     * @param string|Dn $dn
     * @return $this
     */
    public function setDn($dn);
}
