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
 * Represents a set of controls.
 *
 * @author Chad Sikorra <Chad.Sikorra@gmail.com>
 */
class ControlBag implements \IteratorAggregate, \Countable
{
    /**
     * @var Control[]
     */
    protected $controls;

    /**
     * ControlBag constructor.
     * @param Control[] ...$controls
     */
    public function __construct(Control ...$controls)
    {
        $this->controls = $controls;
    }

    /**
     * Check if a specific control exists by either the OID string or the Control object (strict check).
     *
     * @param string|Control $control
     * @return bool
     */
    public function has($control) : bool
    {
        if ($control instanceof Control) {
            return array_search($control, $this->controls, true) !== false;
        }

        foreach ($this->controls as $ctrl) {
            if ($ctrl->getTypeOid() === (string) $control) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get a control object by the string OID type. If none is found it will return null. Can check first with has.
     *
     * @param string $oid
     * @return null|Control
     */
    public function get(string $oid) : ?Control
    {
        foreach ($this->controls as $control) {
            if ($oid === $control->getTypeOid()) {
                return $control;
            }
        }

        return null;
    }

    /**
     * Add more controls.
     *
     * @param Control[] ...$controls
     * @return $this
     */
    public function add(Control ...$controls)
    {
        foreach ($controls as $control) {
            $this->controls[] = $control;
        }

        return $this;
    }

    /**
     * Set the controls.
     *
     * @param Control[] ...$controls
     * @return $this
     */
    public function set(Control ...$controls)
    {
        $this->controls = $controls;

        return $this;
    }

    /**
     * Remove controls by OID or Control object (strict check).
     *
     * @param Control[]|string[] ...$controls
     * @return $this
     */
    public function remove(...$controls)
    {
        foreach ($controls as $control) {
            if ($control instanceof Control) {
                if (($i = array_search($control, $this->controls, true)) !== false) {
                    unset($this->controls[$i]);
                }
            } else {
                foreach ($this->controls as $i => $ctrl) {
                    if ($ctrl->getTypeOid() === (string) $control) {
                        unset($this->controls[$i]);
                    }
                }
            }
        }

        return $this;
    }

    /**
     * Remove all of the controls.
     *
     * @return $this
     */
    public function reset()
    {
        $this->controls = [];

        return $this;
    }

    /**
     * Get the array of Control objects.
     *
     * @return Control[]
     */
    public function toArray() : array
    {
        return $this->controls;
    }

    /**
     * @return int
     */
    public function count()
    {
        return count($this->controls);
    }

    /**
     * @return \ArrayIterator
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->controls);
    }
}
