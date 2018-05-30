<?php
/**
 * This file is part of the FreeDSx LDAP package.
 *
 * (c) Chad Sikorra <Chad.Sikorra@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FreeDSx\Ldap;

use FreeDSx\Ldap\Exception\UrlParseException;

/**
 * Represents a LDAP URL extension component. RFC 4516, Section 2.
 *
 * @author Chad Sikorra <Chad.Sikorra@gmail.com>
 */
class LdapUrlExtension
{
    use LdapUrlTrait;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var null|string
     */
    protected $value;

    /**
     * @var bool
     */
    protected $isCritical = false;

    /**
     * @param string $name
     * @param null|string $value
     * @param bool $isCritical
     */
    public function __construct(string $name, ?string $value = null, bool $isCritical = false)
    {
        $this->name = $name;
        $this->value = $value;
        $this->isCritical = $isCritical;
    }

    /**
     * @return string
     */
    public function getName() : string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setName(string $name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getValue() : ?string
    {
        return $this->value;
    }

    /**
     * @param null|string $value
     * @return $this
     */
    public function setValue(?string $value)
    {
        $this->value = $value;

        return $this;
    }

    public function getIsCritical() : bool
    {
        return $this->isCritical;
    }

    /**
     * @param bool $isCritical
     * @return $this
     */
    public function setIsCritical(bool $isCritical)
    {
        $this->isCritical = $isCritical;

        return $this;
    }

    /**
     * @return string
     */
    public function toString() : string
    {
        $ext = ($this->isCritical ? '!' : '').str_replace(',', '%2c', self::encode($this->name));

        if ($this->value !== null) {
            $ext .= '='.str_replace(',', '%2c', self::encode($this->value));
        }

        return $ext;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->toString();
    }

    /**
     * @param string $extension
     * @return LdapUrlExtension
     * @throws UrlParseException
     */
    public static function parse(string $extension) : LdapUrlExtension
    {
        if (!preg_match('/!?\w+(=.*)?/', $extension)) {
            throw new UrlParseException(sprintf('The LDAP URL extension is malformed: %s', $extension));
        }
        $pieces = explode('=', $extension, 2);

        $isCritical = !empty($pieces[0]) && $pieces[0][0] === '!';
        if ($isCritical) {
            $pieces[0] = substr($pieces[0], 1);
        }

        $name = str_ireplace('%2c', ',', self::decode($pieces[0]));
        $value = isset($pieces[1]) ? str_ireplace('%2c', ',', self::decode($pieces[1])) : null;

        return new self($name, $value, $isCritical);
    }
}
