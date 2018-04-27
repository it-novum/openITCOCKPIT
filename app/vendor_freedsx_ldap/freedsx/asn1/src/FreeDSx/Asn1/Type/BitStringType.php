<?php
/**
 * This file is part of the FreeDSx ASN1 package.
 *
 * (c) Chad Sikorra <Chad.Sikorra@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FreeDSx\Asn1\Type;

/**
 * Represents a bit string type.
 *
 * @author Chad Sikorra <Chad.Sikorra@gmail.com>
 */
class BitStringType extends AbstractType
{
    protected $tagNumber = self::TAG_TYPE_BIT_STRING;

    /**
     * @param string $value
     */
    public function __construct(string $value)
    {
        parent::__construct($value);
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Get the integer representation of the bit string.
     *
     * @return int
     */
    public function toInteger() : int
    {
        return hexdec(bin2hex(rtrim($this->toBinary(), "\x00")));
    }

    /**
     * Get the packed binary representation.
     *
     * @return string
     */
    public function toBinary()
    {
        $bytes = '';

        foreach (str_split($this->value, 8) as $piece) {
            $bytes .= chr(bindec($piece));
        }

        return $bytes;
    }

    /**
     * Construct the bit string from a binary string value.
     *
     * @param $bytes
     * @param int|null $minLength
     * @return BitStringType
     */
    public static function fromBinary($bytes, ?int $minLength = null)
    {
        $bitstring = '';

        $length = strlen($bytes);
        for ($i = 0; $i < $length; $i++) {
            $bitstring .= sprintf('%08d', decbin(ord($bytes[$i])));
        }
        if ($minLength && strlen($bitstring) < $minLength) {
            $bitstring = str_pad($bitstring, $minLength, '0');
        }

        return new self($bitstring);
    }

    /**
     * Construct the bit string from an integer.
     *
     * @param int $int
     * @param int|null $minLength
     * @return BitStringType
     */
    public static function fromInteger(int $int, ?int $minLength = null)
    {
        $pieces = str_split(decbin($int), 8);
        $num = count($pieces);

        if ($num === 1 && strlen($pieces[0]) !== 8) {
            $pieces[0] = str_pad($pieces[0], 8, '0', STR_PAD_LEFT);
        } elseif ($num > 0 && strlen($pieces[$num - 1]) !== 8) {
            $pieces[$num - 1] = str_pad($pieces[$num - 1], 8, '0', STR_PAD_RIGHT);
        }

        $bitstring = implode('', $pieces);
        if ($minLength && strlen($bitstring) < $minLength) {
            $bitstring = str_pad($bitstring, $minLength, '0');
        }

        return new self($bitstring);
    }
}
