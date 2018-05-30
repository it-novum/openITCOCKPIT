<?php
/**
 * This file is part of the FreeDSx LDAP package.
 *
 * (c) Chad Sikorra <Chad.Sikorra@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FreeDSx\Ldap\Entry;

/**
 * Some common methods around escaping attribute values and RDN values.
 *
 * @author Chad Sikorra <Chad.Sikorra@gmail.com>
 */
trait EscapeTrait
{
    /**
     * Escape all characters in a value.
     *
     * @param string $value
     * @return string
     */
    public static function escapeAll(string $value) : string
    {
        if (self::shouldNotEscape($value)) {
            return $value;
        }

        return '\\'.implode('\\', str_split(bin2hex($value), 2));
    }

    /**
     * Replace non-printable ASCII with escaped hex.
     *
     * @param string $value
     * @return string
     */
    protected static function escapeNonPrintable(string $value) : string
    {
        return preg_replace_callback('/([\x00-\x1F\x7F])/', function($matches) {
            return '\\'.bin2hex($matches[1]);
        }, $value);
    }

    /**
     * @param string $value
     * @return bool
     */
    protected static function shouldNotEscape(string $value)
    {
        return (preg_match('/^(\\\\[0-9A-Fa-f]{2})+$/', $value)  || $value === '');
    }
}
