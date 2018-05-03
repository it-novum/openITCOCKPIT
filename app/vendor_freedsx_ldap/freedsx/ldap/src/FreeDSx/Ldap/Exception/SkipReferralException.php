<?php
/**
 * This file is part of the FreeDSx LDAP package.
 *
 * (c) Chad Sikorra <Chad.Sikorra@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FreeDSx\Ldap\Exception;

/**
 * Thrown in the referral chaser to indicate that a referral should be skipped.
 *
 * @author Chad Sikorra <Chad.Sikorra@gmail.com>
 */
class SkipReferralException extends \Exception
{
}
