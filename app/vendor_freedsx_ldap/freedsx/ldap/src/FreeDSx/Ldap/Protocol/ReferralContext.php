<?php
/**
 * This file is part of the FreeDSx LDAP package.
 *
 * (c) Chad Sikorra <Chad.Sikorra@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FreeDSx\Ldap\Protocol;

use FreeDSx\Ldap\LdapUrl;
use FreeDSx\Ldap\Operation\Request\BindRequest;

/**
 * Keeps track of referrals while they are being chased.
 *
 * @author Chad Sikorra <Chad.Sikorra@gmail.com>
 */
class ReferralContext
{
    /**
     * @var LdapUrl[]
     */
    protected $referrals = [];

    /**
     * @var BindRequest|null
     */
    protected $bindRequest;

    /**
     * @param BindRequest|null $bindRequest
     * @param LdapUrl[] ...$referrals
     */
    public function __construct(?BindRequest $bindRequest, LdapUrl ...$referrals)
    {
        $this->referrals = $referrals;
        $this->bindRequest = $bindRequest;
    }

    /**
     * @return LdapUrl[]
     */
    public function getReferrals() : array
    {
        return $this->referrals;
    }

    /**
     * @param LdapUrl ...$referral
     * @return $this
     */
    public function addReferral(LdapUrl $referral)
    {
        $this->referrals[] = $referral;

        return $this;
    }

    /**
     * @param LdapUrl $url
     * @return bool
     */
    public function hasReferral(LdapUrl $url) : bool
    {
        foreach ($this->referrals as $referral) {
            if (strtolower($referral->toString()) === strtolower($url->toString())) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return BindRequest|null
     */
    public function getBindRequest() : ?BindRequest
    {
        return $this->bindRequest;
    }

    /**
     * @return int
     */
    public function count()
    {
        return count($this->referrals);
    }
}
