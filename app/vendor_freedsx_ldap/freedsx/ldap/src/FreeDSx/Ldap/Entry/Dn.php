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

use FreeDSx\Ldap\Exception\UnexpectedValueException;

/**
 * Represents a Distinguished Name.
 *
 * @author Chad Sikorra <Chad.Sikorra@gmail.com>
 */
class Dn implements \IteratorAggregate, \Countable
{
    /**
     * @var string
     */
    protected $dn;

    /**
     * @var null|Rdn[]
     */
    protected $pieces;

    /**
     * @param string $dn
     */
    public function __construct(string $dn)
    {
        $this->dn = $dn;
    }

    /**
     * @return Rdn
     */
    public function getRdn() : Rdn
    {
        if ($this->pieces === null) {
            $this->parse();
        }

        return $this->pieces[0];
    }

    /**
     * @return null|Dn
     */
    public function getParent() : ?Dn
    {
        if ($this->pieces === null) {
            $this->parse();
        }
        if (count($this->pieces) < 2) {
            return null;
        }

        return new Dn(implode(',', array_slice($this->pieces, 1)));
    }

    /**
     * @return \ArrayIterator
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->toArray());
    }

    /**
     * @return string
     */
    public function toString() : string
    {
        return $this->dn;
    }

    /**
     * @return int
     */
    public function count()
    {
        if ($this->pieces === null) {
            $this->parse();
        }

        return count($this->pieces);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->dn;
    }

    /**
     * @return Rdn[]
     */
    public function toArray() : array
    {
        if ($this->pieces !== null) {
            return $this->pieces;
        }
        $this->parse();

        return $this->pieces;
    }

    /**
     * @todo This needs proper handling. But the regex would probably be rather crazy.
     */
    protected function parse() : void
    {
        $pieces = preg_split('/(?<!\\\\),/', $this->dn);
        $pieces = $pieces === false ? [] : $pieces;

        if (empty($pieces)) {
            throw new UnexpectedValueException(sprintf('The DN value "%s" is not valid.', $this->dn));
        }

        foreach ($pieces as $i => $piece) {
            $pieces[$i] = Rdn::create(ltrim($piece));
        }

        $this->pieces = $pieces;
    }
}
