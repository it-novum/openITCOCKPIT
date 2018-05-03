<?php
/**
 * This file is part of the FreeDSx ASN1 package.
 *
 * (c) Chad Sikorra <Chad.Sikorra@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FreeDSx\Asn1\Encoder;

use FreeDSx\Asn1\Exception\EncoderException;
use FreeDSx\Asn1\Type\AbstractTimeType;
use FreeDSx\Asn1\Type\AbstractType;
use FreeDSx\Asn1\Type\SetOfType;

/**
 * Common restrictions on CER and DER encoding.
 *
 * @author Chad Sikorra <Chad.Sikorra@gmail.com>
 */
trait CerDerTrait
{
    /**
     * @param string $bytes
     * @param int $length
     * @param int $unused
     * @return string
     * @throws EncoderException
     */
    protected function binaryToBitString($bytes, int $length, int $unused) : string
    {
        if ($unused && $length && ord($bytes[-1]) !== 0 && ((8 - $length) << ord($bytes[-1])) !== 0) {
            throw new EncoderException(sprintf(
                'The last %s unused bits of the bit string must be 0, but they are not.',
                $unused
            ));
        }

        return parent::binaryToBitString($bytes, $length, $unused);
    }

    /**
     * @param string $bytes
     * @return bool
     * @throws EncoderException
     */
    protected function decodeBoolean($bytes): bool
    {
        $value = ord($bytes[0]);

        if ($value === 0) {
            return false;
        } elseif ($value === 255) {
            return true;
        } else {
            throw new EncoderException(sprintf('The encoded boolean must be 0 or 255, received "%s".', $value));
        }
    }

    /**
     * {@inheritdoc}
     * @throws EncoderException
     */
    protected function encodeTime(AbstractTimeType $type, string $format)
    {
        $this->validateTimeType($type);

        return parent::encodeTime($type, $format);
    }

    /**
     * {@inheritdoc}
     * @throws EncoderException
     */
    protected function validateDateFormat(array $matches, array $matchMap)
    {
        if (isset($matchMap['fractions']) && isset($matches[$matchMap['fractions']]) && $matches[$matchMap['fractions']] !== '') {
            if ($matches[$matchMap['fractions']][-1] === '0') {
                throw new EncoderException('Trailing zeros must be omitted from Generalized Time types, but it is not.');
            }
        }
    }

    /**
     * @param AbstractTimeType $type
     * @throws EncoderException
     */
    protected function validateTimeType(AbstractTimeType $type)
    {
        if ($type->getTimeZoneFormat() !== AbstractTimeType::TZ_UTC) {
            throw new EncoderException(sprintf(
                'Time must end in a Z, but it does not. It is set to "%s".',
                $type->getTimeZoneFormat()
            ));
        }
        $dtFormat = $type->getDateTimeFormat();
        if (!($dtFormat === AbstractTimeType::FORMAT_SECONDS || $dtFormat === AbstractTimeType::FORMAT_FRACTIONS)) {
            throw new EncoderException(sprintf(
                'Time must be specified to the seconds, but it is specified to "%s".',
                $dtFormat
            ));
        }
    }

    /**
     * X.690 Section 11.6
     *
     * The encodings of the component values of a set-of value shall appear in ascending order, the encodings being
     * compared as octet strings with the shorter components being padded at their trailing end with 0-octets.
     *
     *   NOTE – The padding octets are for comparison purposes only and do not appear in the encodings.
     *
     * ---------
     *
     * It's very hard to find examples, but it's not clear to me from the wording if I have this correct. The example I
     * did find in "ASN.1 Complete" (John Larmouth) contains seemingly several encoding errors:
     *
     *    - Length is not encoded correctly for the SET OF element.
     *    - The integer 10 is encoded incorrectly.
     *    - The sort is in descending order of the encoded value (in opposition to X.690 11.6), though in ascending
     *      order of the literal integer values.
     *
     * So I'm hesitant to trust that. Perhaps there's an example elsewhere to be used? Tests around this are hard to
     * come by in ASN.1 libraries for some reason.
     *
     * @todo Is this assumed ordering correct? Confirmation needed. This could probably be simplified too.
     * @param SetOfType $setOf
     * @return string
     */
    protected function encodeSetOf(SetOfType $setOf)
    {
        if (count($setOf) === 0) {
            return '';
        }
        $children = [];

        # Encode each child and record the length, we need it later
        foreach ($setOf as $type) {
            $child = ['original' => $this->encode($type)];
            $child['length'] = strlen($child['original']);
            $children[] = $child;
        }

        # Sort the encoded types by length first to determine the padding needed.
        usort($children, function ($a, $b) {
            /* @var AbstractType $a
             * @var AbstractType $b */
            return $a['length'] < $b['length'] ? -1 : 1;
        });

        # Get the last child (ie. the longest), and put the array back to normal.
        $child = end($children);
        $padding = $child ['length'];
        reset($children);

        # Sort by padding the items and comparing them.
        usort($children, function($a, $b) use ($padding) {
            return strcmp(
                str_pad($a['original'], $padding, "\x00"),
                str_pad($b['original'], $padding, "\x00")
            );
        });

        # Reconstruct the byte string from the order obtained.
        $bytes = '';
        foreach ($children as $child) {
            $bytes .= $child['original'];
        }

        return $bytes;
    }
}
