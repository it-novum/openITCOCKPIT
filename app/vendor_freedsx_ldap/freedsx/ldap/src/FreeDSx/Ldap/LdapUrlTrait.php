<?php
/**
 * Created by PhpStorm.
 * User: Chad
 * Date: 1/1/2018
 * Time: 9:28 PM
 */

namespace FreeDSx\Ldap;

/**
 * Some common methods for LDAP URLs and URL Extensions.
 *
 * @author Chad Sikorra <Chad.Sikorra@gmail.com>
 */
trait LdapUrlTrait
{
    /**
     * @var array
     */
    protected static $escapeMap = [
        '%' => '%25',
        '?' => '%3f',
        ' ' => '%20',
        '<' => '%3c',
        '>' => '%3e',
        '"' => '%22',
        '#' => '%23',
        '{' => '%7b',
        '}' => '%7d',
        '|' => '%7c',
        '\\' => '%5c',
        '^' => '%5e',
        '~' => '%7e',
        '[' => '%5b',
        ']' => '%5d',
        '`' => '%60',
    ];

    /**
     * Percent-encode certain values in the URL.
     *
     * @param string $value
     * @return string
     */
    protected static function encode(?string $value) : string
    {
        return str_replace(
            array_keys(self::$escapeMap),
            array_values(self::$escapeMap),
            $value
        );
    }

    /**
     * Percent-decode values from the URL.
     *
     * @param string $value
     * @return string
     */
    protected static function decode(string $value) : string
    {
        return str_ireplace(
            array_values(self::$escapeMap),
            array_keys(self::$escapeMap),
            $value
        );
    }

}