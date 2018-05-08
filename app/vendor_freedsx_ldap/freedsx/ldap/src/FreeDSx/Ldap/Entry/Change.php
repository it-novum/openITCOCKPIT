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
 * Represents an entry change.
 *
 * @author Chad Sikorra <Chad.Sikorra@gmail.com>
 */
class Change
{
    /**
     * Add a value to an attribute.
     */
    public const TYPE_ADD = 0;

    /**
     * Delete a value, or values, from an attribute.
     */
    public const TYPE_DELETE = 1;

    /**
     * Replaces the current value of an attribute with a different one.
     */
    public const TYPE_REPLACE = 2;

    /**
     * @var int
     */
    protected $modType;

    /**
     * @var Attribute
     */
    protected $attribute;

    /**
     * @param int $modType
     * @param string|Attribute $attribute
     * @param array $values
     */
    public function __construct(int $modType, $attribute, ...$values)
    {
        $this->modType = $modType;
        $this->attribute = $attribute instanceof Attribute ? $attribute : new Attribute($attribute, ...$values);
    }

    /**
     * @return Attribute
     */
    public function getAttribute() : Attribute
    {
        return $this->attribute;
    }

    /**
     * @param Attribute $attribute
     * @return $this
     */
    public function setAttribute(Attribute $attribute)
    {
        $this->attribute = $attribute;

        return $this;
    }

    /**
     * @return int
     */
    public function getType() : int
    {
        return $this->modType;
    }

    /**
     * @param int $modType
     * @return $this
     */
    public function setType(int $modType)
    {
        $this->modType = $modType;

        return $this;
    }

    /**
     * Add the values contained in the attribute, creating the attribute if necessary.
     *
     * @param string|Attribute $attribute
     * @param string[] ...$values
     * @return Change
     */
    public static function add($attribute, ...$values) : Change
    {
        $attribute = $attribute instanceof Attribute ? $attribute : new Attribute($attribute, ...$values);

        return new self(self::TYPE_ADD, $attribute);
    }

    /**
     * Delete values from the attribute. If no values are listed, or if all current values of the attribute are listed,
     * the entire attribute is removed.
     *
     * @param Attribute|string $attribute
     * @param string[] ...$values
     * @return Change
     */
    public static function delete($attribute, ...$values) : Change
    {
        $attribute = $attribute instanceof Attribute ? $attribute : new Attribute($attribute, ...$values);

        return new self(self::TYPE_DELETE, $attribute);
    }

    /**
     * Replace all existing values with the new values, creating the attribute if it did not already exist.  A replace
     * with no value will delete the entire attribute if it exists, and it is ignored if the attribute does not exist.
     *
     * @param Attribute|string $attribute
     * @param string[] ...$values
     * @return Change
     */
    public static function replace($attribute, ...$values)  : Change
    {
        $attribute = $attribute instanceof Attribute ? $attribute : new Attribute($attribute, ...$values);

        return new self(self::TYPE_REPLACE, $attribute);
    }

    /**
     * Remove all values from an attribute, essentially un-setting/resetting it. This is the same type as delete when
     * going to LDAP. The real difference being that no values are attached to the change.
     *
     * @param string|Attribute $attribute
     * @return Change
     */
    public static function reset($attribute) : Change
    {
        $attribute = $attribute instanceof Attribute ? new Attribute($attribute->getName()) : new Attribute($attribute);

        return new self(self::TYPE_DELETE, $attribute);
    }
}
