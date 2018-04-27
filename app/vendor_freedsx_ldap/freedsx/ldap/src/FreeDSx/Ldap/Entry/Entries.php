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
 * Represents a collection of entry objects.
 *
 * @author Chad Sikorra <Chad.Sikorra@gmail.com>
 */
class Entries implements \Countable, \IteratorAggregate
{
    /**
     * @var Entry[]
     */
    protected $entries = [];

    /**
     * @param Entry[] ...$entries
     */
    public function __construct(Entry ...$entries)
    {
        $this->entries = $entries;
    }

    /**
     * Get the first entry object, if one exists.
     *
     * @return Entry|null
     */
    public function first() : ?Entry
    {
        $entry = reset($this->entries);

        return $entry === false ? null : $entry;
    }

    /**
     * Get the last entry object, if one exists.
     *
     * @return Entry|null
     */
    public function last() : ?Entry
    {
        $entry = end($this->entries);
        reset($this->entries);

        return $entry === false ? null : $entry;
    }

    /**
     * @return Entry[]
     */
    public function toArray() : array
    {
        return $this->entries;
    }

    /**
     * @return \ArrayIterator
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->entries);
    }

    /**
     * @return int
     */
    public function count()
    {
        return count($this->entries);
    }
}
