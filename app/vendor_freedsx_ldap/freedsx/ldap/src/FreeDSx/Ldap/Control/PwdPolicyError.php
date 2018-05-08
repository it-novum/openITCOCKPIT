<?php
/**
 * This file is part of the FreeDSx LDAP package.
 *
 * (c) Chad Sikorra <Chad.Sikorra@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FreeDSx\Ldap\Control;

/**
 * Possible Password Policy Error values. draft-behera-ldap-password-policy-10, Section 6.2.
 *
 * @see https://tools.ietf.org/html/draft-behera-ldap-password-policy-10
 * @author Chad Sikorra <Chad.Sikorra@gmail.com>
 */
class PwdPolicyError
{
    public const PASSWORD_EXPIRED = 0;

    public const ACCOUNT_LOCKED = 1;

    public const CHANGE_AFTER_RESET = 2;

    public const PASSWORD_MOD_NOT_ALLOWED = 3;

    public const MUST_SUPPLY_OLD_PASSWORD = 4;

    public const INSUFFICIENT_PASSWORD_QUALITY = 5;

    public const PASSWORD_TOO_SHORT = 6;

    public const PASSWORD_TOO_YOUNG = 7;

    public const PASSWORD_IN_HISTORY = 8;
}
