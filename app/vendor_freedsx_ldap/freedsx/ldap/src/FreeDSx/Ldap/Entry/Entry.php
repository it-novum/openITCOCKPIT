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
 * Represents an Entry in LDAP.
 *
 * @author Chad Sikorra <Chad.Sikorra@gmail.com>
 */
class Entry implements \IteratorAggregate, \Countable
{
    /**
     * @var Attribute[]
     */
    protected $attributes;

    /**
     * @var Dn
     */
    protected $dn;

    /**
     * @var Changes
     */
    protected $changes;

    /**
     * @param string|Dn $dn
     * @param Attribute[] ...$attributes
     */
    public function __construct($dn, Attribute ...$attributes)
    {
        $this->dn = $dn instanceof Dn ? $dn : new Dn($dn);
        $this->attributes = $attributes;
        $this->changes = new Changes();
    }

    /**
     * Add an attribute and its values.
     *
     * @param string $attribute
     * @param array ...$values
     * @return $this
     */
    public function add($attribute, ...$values)
    {
        $attribute = $attribute instanceof Attribute ? $attribute : new Attribute($attribute, ...$values);

        $exists = false;
        foreach ($this->attributes as $i => $attr) {
            if ($attr->equals($attribute)) {
                $exists = true;
                $this->attributes[$i]->add(...$attribute->getValues());
                break;
            }
        }
        if (!$exists) {
            $this->attributes[] = $attribute;
        }
        $this->changes->add(Change::add(clone $attribute));

        return $this;
    }

    /**
     * Remove an attribute's value(s).
     *
     * @param string|Attribute $attribute
     * @param array ...$values
     * @return $this
     */
    public function remove($attribute, ...$values)
    {
        $attribute = $attribute instanceof Attribute ? $attribute : new Attribute($attribute, ...$values);

        if (!empty($attribute->getValues())) {
            foreach ($this->attributes as $i => $attr) {
                if ($attr->equals($attribute)) {
                    $this->attributes[$i]->remove(...$attribute->getValues());
                    break;
                }
            }
            $this->changes->add(Change::delete(clone $attribute));
        }

        return $this;
    }

    /**
     * Reset an attribute, which removes any values it may have.
     *
     * @param string[]|Attribute[] ...$attributes
     * @return $this
     */
    public function reset(...$attributes)
    {
        foreach ($attributes as $attribute) {
            $attribute = $attribute instanceof Attribute ? $attribute : new Attribute($attribute);
            foreach ($this->attributes as $i => $attr) {
                if ($attr->equals($attribute)) {
                    unset($this->attributes[$i]);
                    break;
                }
            }
            $this->changes()->add(Change::reset(clone $attribute));
        }

        return $this;
    }

    /**
     * Set an attribute on the entry, replacing any value(s) that may exist on it.
     *
     * @param string|Attribute $attribute
     * @param array ...$values
     * @return $this
     */
    public function set($attribute, ...$values)
    {
        $attribute = $attribute instanceof Attribute ? $attribute : new Attribute($attribute, ...$values);

        $exists = false;
        foreach ($this->attributes as $i => $attr) {
            if ($attr->equals($attribute)) {
                $exists = true;
                $this->attributes[$i] = $attribute;
                break;
            }
        }
        if (!$exists) {
            $this->attributes[] = $attribute;
        }
        $this->changes->add(Change::replace(clone $attribute));

        return $this;
    }

    /**
     * Get a specific attribute by name (or Attribute object).
     *
     * @param string|Attribute $attribute
     * @return null|Attribute
     */
    public function get($attribute) : ?Attribute
    {
        $attribute = $attribute instanceof Attribute ? $attribute : new Attribute($attribute);

        foreach ($this->attributes as $attr) {
            if ($attribute->equals($attr)) {
                return $attr;
            }
        }

        return null;
    }

    /**
     * Check if a specific attribute exists on the entry.
     *
     * @param string|Attribute $attribute
     * @return bool
     */
    public function has($attribute) : bool
    {
        $attribute = $attribute instanceof Attribute ? $attribute : new Attribute($attribute);

        foreach ($this->attributes as $attr) {
            if ($attr->equals($attribute)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return Attribute[]
     */
    public function getAttributes() : array
    {
        return $this->attributes;
    }

    /**
     * @return Dn
     */
    public function getDn() : Dn
    {
        return $this->dn;
    }

    /**
     * Get the changes accumulated for this entry.
     *
     * @return Changes
     */
    public function changes() : Changes
    {
        return $this->changes;
    }

    /**
     * Get the entry representation as an associative array.
     *
     * @return array
     */
    public function toArray() : array
    {
        $attributes = [];

        foreach ($this->attributes as $attribute) {
            $attributes[$attribute->getName()] = $attribute->getValues();
        }

        return $attributes;
    }

    /**
     * @return \ArrayIterator
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->attributes);
    }

    /**
     * @return int
     */
    public function count()
    {
        return count($this->attributes);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->dn->toString();
    }

    /**
     * @param $name
     * @return Attribute|null
     */
    public function __get($name)
    {
        return $this->get($name);
    }

    /**
     * @param $name
     * @param $value
     */
    public function __set($name, $value)
    {
        $this->set($name, ...(is_array($value) ? $value : [$value]));
    }

    /**
     * @param $name
     * @return bool
     */
    public function __isset($name)
    {
        return $this->has($name);
    }

    /**
     * @param $name
     */
    public function __unset($name)
    {
        $this->reset($name);
    }

    /**
     * Construct an entry from an associative array.
     *
     * @param string $dn
     * @param array $attributes
     * @return Entry
     */
    public static function create(string $dn, array $attributes = []) : Entry
    {
        /** @var Attribute[] $entryAttr */
        $entryAttr = [];

        foreach ($attributes as $attribute => $value) {
            $entryAttr[] = new Attribute($attribute, ...(is_array($value) ? $value : [$value]));
        }

        return new self($dn, ...$entryAttr);
    }
}
