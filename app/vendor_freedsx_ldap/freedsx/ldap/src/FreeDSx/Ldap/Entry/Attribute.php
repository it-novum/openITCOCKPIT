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
 * Represents an entry attribute and any values.
 *
 * @author Chad Sikorra <Chad.Sikorra@gmail.com>
 */
class Attribute implements \IteratorAggregate, \Countable
{
    use EscapeTrait;

    protected const ESCAPE_MAP = [
        '\\' => '\5c',
        '*' => '\2a',
        '(' => '\28',
        ')' => '\29',
        "\x00" => '\00',
    ];

    /**
     * @var string
     */
    protected $attribute;

    /**
     * @var null|string
     */
    protected $lcAttribute;

    /**
     * @var string[]
     */
    protected $values = [];

    /**
     * @param string $attribute
     * @param string[] ...$values
     */
    public function __construct(string $attribute, ...$values)
    {
        $this->attribute = $attribute;
        $this->values = $values;
    }

    /**
     * @param string[] ...$values
     * @return $this
     */
    public function add(string ...$values)
    {
        foreach ($values as $value) {
            $this->values[] = $value;
        }

        return $this;
    }

    /**
     * @param string $value
     * @return bool
     */
    public function has(string $value) : bool
    {
        return array_search($value, $this->values) !== false;
    }

    /**
     * @param string[] ...$values
     * @return $this
     */
    public function remove(string ...$values)
    {
        foreach ($values as $value) {
            if (($i = array_search($value, $this->values)) !== false) {
                unset($this->values[$i]);
            }
        }

        return $this;
    }

    /**
     * Resets the values to any empty array.
     *
     * @return $this
     */
    public function reset()
    {
        $this->values = [];

        return $this;
    }

    /**
     * Set the values for the attribute.
     *
     * @param string[] ...$values
     * @return $this
     */
    public function set(string ...$values)
    {
        $this->values = $values;

        return $this;
    }

    /**
     * @return string
     */
    public function getName() : string
    {
        return $this->attribute;
    }

    /**
     * @return array
     */
    public function getValues() : array
    {
        return $this->values;
    }

    /**
     * @return \ArrayIterator
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->values);
    }

    /**
     * @return int
     */
    public function count()
    {
        return count($this->values);
    }

    /**
     * @param Attribute $attribute
     * @return bool
     */
    public function equals(Attribute $attribute) : bool
    {
        if ($this->lcAttribute === null) {
            $this->lcAttribute = strtolower($this->attribute);
        }
        if ($attribute->lcAttribute === null) {
            $attribute->lcAttribute = strtolower($attribute->attribute);
        }

        return $this->lcAttribute === $attribute->lcAttribute;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return implode(', ', $this->values);
    }

    /**
     * Escape an attribute value for a filter.
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

        return self::escapeNonPrintable($value);
    }
}
