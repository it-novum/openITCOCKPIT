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

use FreeDSx\Ldap\Exception\InvalidArgumentException;

/**
 * Represents a Relative Distinguished Name.
 *
 * @author Chad Sikorra <Chad.Sikorra@gmail.com>
 */
class Rdn
{
    use EscapeTrait;

    public const ESCAPE_MAP = [
        '\\' => '\\5c',
        '"' => '\\22',
        '+' => '\\2b',
        ',' => '\\2c',
        ';' => '\\3b',
        '<' => '\\3c',
        '>' => '\\3e',
    ];

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $value;

    /**
     * @var Rdn[]
     */
    protected $additional = [];

    /**
     * @param string $name
     * @param string $value
     */
    public function __construct(string $name, string $value)
    {
        $this->name = $name;
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function getName() : string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getValue() : string
    {
        return $this->value;
    }

    /**
     * @return bool
     */
    public function isMultivalued() : bool
    {
        return !empty($this->additional);
    }

    /**
     * @return string
     */
    public function toString() : string
    {
        $rdn = $this->name.'='.$this->value;

        foreach ($this->additional as $rdn) {
            $rdn .= '+'.$rdn->getName().'='.$rdn->getValue();
        }

        return $rdn;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->toString();
    }

    /**
     * @param string $rdn
     * @return Rdn
     */
    public static function create(string $rdn) : Rdn
    {
        $pieces = preg_split('/(?<!\\\\)\+/', $rdn);

        // @todo Simplify this logic somehow?
        $obj = null;
        foreach ($pieces as $piece) {
            $parts = explode('=', $piece, 2);
            if (count($parts) !== 2) {
                throw new InvalidArgumentException(sprintf('The RDN "%s" is invalid.', $piece));
            }
            if ($obj === null) {
                $obj = new self($parts[0], $parts[1]);
            } else {
                /** @var Rdn $obj */
                $obj->additional[] = new self($parts[0], $parts[1]);
            }
        }

        if ($obj === null) {
            throw new InvalidArgumentException(sprintf("The RDN '%s' is not valid.", $rdn));
        }

        return $obj;
    }

    /**
     * Escape an RDN value.
     *
     * @param string $value
     * @return string
     */
    public static function escape(string $value) : string
    {
        if (self::shouldNotEscape($value)) {
            return $value;
        }
        $value = str_replace(array_keys(self::ESCAPE_MAP), array_values(self::ESCAPE_MAP), $value);

        if ($value[0] === '#' || $value[0] === ' ') {
            $value = ($value[0] === '#' ? '\23' : '\20').substr($value, 1);
        }
        if ($value[-1] === ' ') {
            $value = substr_replace($value, '\20',-1, 1);
        }

        return self::escapeNonPrintable($value);
    }
}
