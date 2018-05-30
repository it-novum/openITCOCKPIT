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
use FreeDSx\Asn1\Exception\InvalidArgumentException;
use FreeDSx\Asn1\Exception\PartialPduException;
use FreeDSx\Asn1\Factory\TypeFactory;
use FreeDSx\Asn1\Type\AbstractStringType;
use FreeDSx\Asn1\Type\AbstractTimeType;
use FreeDSx\Asn1\Type\AbstractType;
use FreeDSx\Asn1\Type\BitStringType;
use FreeDSx\Asn1\Type\BooleanType;
use FreeDSx\Asn1\Type\EnumeratedType;
use FreeDSx\Asn1\Type\GeneralizedTimeType;
use FreeDSx\Asn1\Type\IncompleteType;
use FreeDSx\Asn1\Type\IntegerType;
use FreeDSx\Asn1\Type\NullType;
use FreeDSx\Asn1\Type\OidType;
use FreeDSx\Asn1\Type\RealType;
use FreeDSx\Asn1\Type\RelativeOidType;
use FreeDSx\Asn1\Type\SetOfType;
use FreeDSx\Asn1\Type\SetType;
use FreeDSx\Asn1\Type\UtcTimeType;

/**
 * Basic Encoding Rules (BER) encoder.
 *
 * @author Chad Sikorra <Chad.Sikorra@gmail.com>
 */
class BerEncoder implements EncoderInterface
{
    /**
     * These types must have a non-zero length.
     */
    protected const NON_ZERO_LENGTH = [
        AbstractType::TAG_TYPE_BOOLEAN,
        AbstractType::TAG_TYPE_UTC_TIME,
        AbstractType::TAG_TYPE_GENERALIZED_TIME,
        AbstractType::TAG_TYPE_INTEGER,
        AbstractType::TAG_TYPE_ENUMERATED,
        AbstractType::TAG_TYPE_OID,
        AbstractType::TAG_TYPE_RELATIVE_OID,
    ];

    /**
     * @var array
     */
    protected $tagMap = [
        AbstractType::TAG_CLASS_APPLICATION => [],
        AbstractType::TAG_CLASS_CONTEXT_SPECIFIC => [],
        AbstractType::TAG_CLASS_PRIVATE => [],
    ];

    /**
     * @var array
     */
    protected $options = [
        'bitstring_padding' => '0',
        'primitive_only' => [
            AbstractType::TAG_TYPE_BOOLEAN,
            AbstractType::TAG_TYPE_INTEGER,
            AbstractType::TAG_TYPE_ENUMERATED,
            AbstractType::TAG_TYPE_REAL,
            AbstractType::TAG_TYPE_NULL,
            AbstractType::TAG_TYPE_OID,
            AbstractType::TAG_TYPE_RELATIVE_OID,
        ],
        'constructed_only' => [
            AbstractType::TAG_TYPE_SEQUENCE,
            AbstractType::TAG_TYPE_SET,
        ],
    ];

    /**
     * @param array $options
     */
    public function __construct(array $options = [])
    {
        $this->setOptions($options);
    }

    /**
     * {@inheritdoc}
     */
    public function decode($binary, array $tagMap = []) : AbstractType
    {
        if ($binary == '') {
            throw new InvalidArgumentException('The data to decode cannot be empty.');
        } elseif (strlen($binary) === 1) {
            throw new PartialPduException('Received only 1 byte of data.');
        }
        $info = $this->decodeBytes($binary, $tagMap, true);
        $info['type']->setTrailingData($info['bytes']);

        return $info['type'];
    }

    /**
     * {@inheritdoc}
     */
    public function complete(IncompleteType $type, int $tagType, array $tagMap = []) : AbstractType
    {
        return $this->getDecodedType($tagType, $type->getIsConstructed(), $type->getValue(), $tagMap)
            ->setTagNumber($type->getTagNumber())
            ->setTagClass($type->getTagClass());
    }

    /**
     * {@inheritdoc}
     */
    public function encode(AbstractType $type) : string
    {
        $valueBytes = $this->getEncodedValue($type);
        $lengthBytes = $this->getEncodedLength(strlen($valueBytes));

        return $this->getEncodedTag($type).$lengthBytes.$valueBytes;
    }

    /**
     * Map universal types to specific tag class values when decoding.
     *
     * @param int $class
     * @param array $map
     * @return $this
     */
    public function setTagMap(int $class, array $map)
    {
        if (isset($this->tagMap[$class])) {
            $this->tagMap[$class] = $map;
        }

        return $this;
    }

    /**
     * Get the options for the encoder.
     *
     * @return array
     */
    public function getOptions() : array
    {
        return $this->options;
    }

    /**
     * Set the options for the encoder.
     *
     * @param array $options
     * @return $this
     */
    public function setOptions(array $options)
    {
        if (isset($options['bitstring_padding']) && is_string($options['bitstring_padding'])) {
            $this->options['bitstring_padding'] = $options['bitstring_padding'];
        }
        foreach (['primitive_only', 'constructed_only'] as $opt) {
            if (isset($options[$opt]) && is_array(($options[$opt]))) {
                $this->options[$opt] = array_merge($this->options[$opt], $options[$opt]);
            }
        }

        return $this;
    }

    /**
     * Given a specific tag type / map, decode and construct the type.
     *
     * @param int|null $tagType
     * @param bool $isConstructed
     * @param string $bytes
     * @param array $tagMap
     * @return AbstractType
     * @throws EncoderException
     */
    protected function getDecodedType(?int $tagType, bool $isConstructed, $bytes, array $tagMap) : AbstractType
    {
        if ($tagType === null) {
            return new IncompleteType($bytes);
        }
        $length = strlen($bytes);
        $this->validateDecodedTypeAttributes($length, $tagType, $isConstructed);

        switch ($tagType) {
            case AbstractType::TAG_TYPE_BOOLEAN:
                if ($length > 1) {
                    throw new EncoderException(sprintf(
                        'A boolean type must have only one octet, but it has %s.',
                        $length
                    ));
                }
                $value = $this->decodeBoolean($bytes);
                break;
            case AbstractType::TAG_TYPE_NULL:
                if ($length !== 0) {
                    throw new EncoderException(sprintf(
                        'A null type must not have any value octets, but it has %s.',
                        $length
                    ));
                }
                $value = null;
                break;
            case AbstractType::TAG_TYPE_INTEGER:
            case AbstractType::TAG_TYPE_ENUMERATED:
                $value = $this->decodeInteger($bytes);
                break;
            case AbstractType::TAG_TYPE_REAL:
                $value = $this->decodeReal($bytes);
                break;
            case AbstractType::TAG_TYPE_BIT_STRING:
                $value = $this->decodeBitString($bytes);
                break;
            case AbstractType::TAG_TYPE_OID:
                $value = $this->decodeOid($bytes);
                break;
            case AbstractType::TAG_TYPE_RELATIVE_OID:
                $value = $this->decodeRelativeOid($bytes);
                break;
            case AbstractType::TAG_TYPE_GENERALIZED_TIME:
                $value = $this->decodeGeneralizedTime($bytes);
                break;
            case AbstractType::TAG_TYPE_UTC_TIME:
                $value = $this->decodeUtcTime($bytes);
                break;
            case AbstractType::TAG_TYPE_OCTET_STRING:
            case AbstractType::TAG_TYPE_GENERAL_STRING:
            case AbstractType::TAG_TYPE_VISIBLE_STRING:
            case AbstractType::TAG_TYPE_BMP_STRING:
            case AbstractType::TAG_TYPE_CHARACTER_STRING:
            case AbstractType::TAG_TYPE_UNIVERSAL_STRING:
            case AbstractType::TAG_TYPE_GRAPHIC_STRING:
            case AbstractType::TAG_TYPE_VIDEOTEX_STRING:
            case AbstractType::TAG_TYPE_TELETEX_STRING:
            case AbstractType::TAG_TYPE_PRINTABLE_STRING:
            case AbstractType::TAG_TYPE_NUMERIC_STRING:
            case AbstractType::TAG_TYPE_IA5_STRING:
            case AbstractType::TAG_TYPE_UTF8_STRING:
                $value = $bytes;
                break;
            case AbstractType::TAG_TYPE_SEQUENCE:
            case AbstractType::TAG_TYPE_SET:
                $value = $this->decodeConstructedType($bytes, $tagMap);
                break;
            default:
                throw new EncoderException(sprintf('Unable to decode value to a type for tag %s.', $tagType));
        }

        return TypeFactory::create($tagType, $value, $isConstructed);
    }

    /**
     * Get the encoded value for a specific type.
     *
     * @param AbstractType $type
     * @return string
     * @throws EncoderException
     */
    protected function getEncodedValue(AbstractType $type)
    {
        $bytes = null;

        switch ($type) {
            case $type instanceof BooleanType:
                $bytes = $this->encodeBoolean($type);
                break;
            case $type instanceof IntegerType:
            case $type instanceof EnumeratedType:
                $bytes = $this->encodeInteger($type);
                break;
            case $type instanceof RealType:
                $bytes = $this->encodeReal($type);
                break;
            case $type instanceof AbstractStringType:
                $bytes = $type->getValue();
                break;
            case $type instanceof SetOfType:
                $bytes = $this->encodeSetOf($type);
                break;
            case $type instanceof SetType:
                $bytes = $this->encodeSet($type);
                break;
            case $type->getIsConstructed():
                $bytes = $this->encodeConstructedType(...$type->getChildren());
                break;
            case $type instanceof BitStringType:
                $bytes = $this->encodeBitString($type);
                break;
            case $type instanceof OidType:
                $bytes = $this->encodeOid($type);
                break;
            case $type instanceof RelativeOidType:
                $bytes = $this->encodeRelativeOid($type);
                break;
            case $type instanceof GeneralizedTimeType:
                $bytes = $this->encodeGeneralizedTime($type);
                break;
            case $type instanceof UtcTimeType:
                $bytes = $this->encodeUtcTime($type);
                break;
            case $type instanceof NullType:
                break;
            default:
                throw new EncoderException(sprintf('The type "%s" is not currently supported.', $type));
        }

        return $bytes;
    }

    /**
     * Some initial checks against data length and form (primitive / constructed).
     *
     * @param int $length
     * @param int $tagType
     * @param bool $isConstructed
     * @throws EncoderException
     */
    protected function validateDecodedTypeAttributes(int $length, int $tagType, bool $isConstructed) : void
    {
        if ($length === 0 && in_array($tagType, self::NON_ZERO_LENGTH)) {
            throw new EncoderException(sprintf('Zero length is not permitted for tag %s.', $tagType));
        }

        if ($isConstructed && in_array($tagType, $this->options['primitive_only'])) {
            throw new EncoderException(sprintf(
                'The tag type %s is marked constructed, but it can only be primitive.',
                $tagType
            ));
        }
        if (!$isConstructed && in_array($tagType, $this->options['constructed_only'])) {
            throw new EncoderException(sprintf(
                'The tag type %s is marked primitive, but it can only be constructed.',
                $tagType
            ));
        }
    }

    /**
     * @param string $binary
     * @param array $tagMap
     * @param bool $isRoot
     * @return array
     * @throws EncoderException
     * @throws PartialPduException
     */
    protected function decodeBytes($binary, array $tagMap, bool $isRoot = false) : array
    {
        $data = ['type' => null, 'bytes' => null, 'trailing' => null];
        $tagMap = $tagMap + $this->tagMap;

        $tag = $this->getDecodedTag($binary, $isRoot);
        $length = $this->getDecodedLength(substr($binary, $tag['length']));
        $tagType = $this->getTagType($tag['number'], $tag['class'], $tagMap);

        $totalLength = $tag['length'] + $length['length_length'] + $length['value_length'];
        if (strlen($binary) < $totalLength) {
            $message = sprintf(
                'The expected byte length was %s, but received %s.',
                $totalLength,
                strlen($binary)
            );
            if ($isRoot) {
                throw new PartialPduException($message);
            } else {
                throw new EncoderException($message);
            }
        }

        $data['type'] = $this->getDecodedType($tagType, $tag['constructed'], substr($binary, $tag['length'] + $length['length_length'], $length['value_length']), $tagMap);
        $data['type']->setTagClass($tag['class']);
        $data['type']->setTagNumber($tag['number']);
        $data['type']->setIsConstructed($tag['constructed']);
        $data['bytes'] = substr($binary, $totalLength);

        return $data;
    }

    /**
     * From a specific tag number and class try to determine what universal ASN1 type it should be mapped to. If there
     * is no mapping defined it will return null. In this case the binary data will be wrapped into an IncompleteType.
     *
     * @param int $tagNumber
     * @param int $tagClass
     * @param array $map
     * @return int|null
     */
    protected function getTagType(int $tagNumber, int $tagClass, array $map) : ?int
    {
        if ($tagClass === AbstractType::TAG_CLASS_UNIVERSAL) {
            return $tagNumber;
        }

        return $map[$tagClass][$tagNumber] ?? null;
    }

    /**
     * @param string $bytes
     * @return array
     * @throws EncoderException
     */
    protected function getDecodedLength($bytes) : array
    {
        $info = ['value_length' => isset($bytes[0]) ? ord($bytes[0]) : 0, 'length_length' => 1];

        if ($info['value_length'] === 128) {
            throw new EncoderException('Indefinite length encoding is not currently supported.');
        }

        # Long definite length has a special encoding.
        if ($info['value_length'] > 127) {
            $info = $this->decodeLongDefiniteLength($bytes, $info);
        }

        return $info;
    }

    /**
     * @param string $bytes
     * @param array $info
     * @return array
     * @throws EncoderException
     * @throws PartialPduException
     */
    protected function decodeLongDefiniteLength($bytes, array $info) : array
    {
        # The length of the length bytes is in the first 7 bits. So remove the MSB to get the value.
        $info['length_length'] = $info['value_length'] & ~0x80;

        # The value of 127 is marked as reserved in the spec
        if ($info['length_length'] === 127) {
            throw new EncoderException('The decoded length cannot be equal to 127 bytes.');
        }
        if ($info['length_length'] + 1 > strlen($bytes)) {
            throw new PartialPduException('Not enough data to decode the length.');
        }

        # Base 256 encoded
        $info['value_length'] = 0;
        for ($i = 1; $i < $info['length_length'] + 1; $i++) {
            $info['value_length'] = $info['value_length'] * 256 + ord($bytes[$i]);
        }

        # Add the byte that represents the length of the length
        $info['length_length']++;

        return $info;
    }

    /**
     * @param string $bytes
     * @param bool $isRoot
     * @return array
     * @throws EncoderException
     * @throws PartialPduException
     */
    protected function getDecodedTag($bytes, bool $isRoot) : array
    {
        $tag = ord($bytes[0]);
        $info = ['class' => null, 'number' => null, 'constructed' => null, 'length' => 1];

        if ($tag & AbstractType::TAG_CLASS_APPLICATION && $tag & AbstractType::TAG_CLASS_CONTEXT_SPECIFIC) {
            $info['class'] = AbstractType::TAG_CLASS_PRIVATE;
        } elseif ($tag & AbstractType::TAG_CLASS_APPLICATION) {
            $info['class'] = AbstractType::TAG_CLASS_APPLICATION;
        } elseif ($tag & AbstractType::TAG_CLASS_CONTEXT_SPECIFIC) {
            $info['class'] = AbstractType::TAG_CLASS_CONTEXT_SPECIFIC;
        } else {
            $info['class'] = AbstractType::TAG_CLASS_UNIVERSAL;
        }
        $info['constructed'] = (bool) ($tag & AbstractType::CONSTRUCTED_TYPE);
        $info['number'] = $tag & ~0xe0;

        # Less than or equal to 30 is a low tag number represented in a single byte.
        if ($info['number'] <= 30) {
            return $info;
        }

        # A high tag number is determined using VLQ (like the OID identifier encoding) of the subsequent bytes.
        try {
            $tagNumBytes = $this->getVlqBytes(substr($bytes, 1));
        # It's possible we only got part of the VLQ for the high tag, as there is no way to know it's ending length.
        } catch (EncoderException $e) {
            if ($isRoot) {
                throw new PartialPduException(
                    'Not enough data to decode the high tag number. No ending byte encountered for the VLQ bytes.'
                );
            }
            throw $e;
        }
        $info['number'] = $this->getVlqInt($tagNumBytes);
        $info['length'] = 1 + strlen($tagNumBytes);

        return $info;
    }

    /**
     * Gets the bytes representing the VLQ value.
     *
     * @param $bytes
     * @return string
     * @throws EncoderException
     */
    protected function getVlqBytes($bytes)
    {
        $vlq = '';
        $length = strlen($bytes);

        for ($i = 0; $i < $length; $i++) {
            # We have reached the last byte if the MSB is not set.
            if ((ord($bytes[$i]) & 0x80) === 0) {
                return $vlq.$bytes[$i];
            } else {
                $vlq .= $bytes[$i];
            }
        }

        throw new EncoderException('Expected an ending byte to decode a VLQ, but none was found.');
    }

    /**
     * Given the VLQ bytes, get the actual int it represents.
     *
     * @param string $bytes
     * @return int
     */
    protected function getVlqInt($bytes) : int
    {
        $value = 0;

        $length = strlen($bytes);
        for ($i = 0; $i < $length; $i++) {
            $value = ($value << 7) | (ord($bytes[$i]) & 0x7f);
        }

        return $value;
    }

    /**
     * Get the bytes that represent variable length quantity.
     *
     * @param int $int
     * @return string
     */
    protected function intToVlqBytes(int $int)
    {
        $bytes = chr(0x7f & $int);
        $int >>= 7;

        while ($int > 0) {
            $bytes = chr((0x7f & $int) | 0x80).$bytes;
            $int >>= 7;
        }

        return $bytes;
    }

    /**
     * Get the encoded tag byte(s) for a given type.
     *
     * @param AbstractType $type
     * @return string
     */
    protected function getEncodedTag(AbstractType $type)
    {
        # The first byte of a tag always contains the class (bits 8 and 7) and whether it is constructed (bit 6).
        $tag = $type->getTagClass() | ($type->getIsConstructed() ? AbstractType::CONSTRUCTED_TYPE : 0);

        # For a high tag (>=31) we flip the first 5 bits on (0x1f) to make the first byte, then the subsequent bytes is
        # the VLV encoding of the tag number.
        if ($type->getTagNumber() >= 31) {
            $bytes = chr($tag | 0x1f).$this->intToVlqBytes($type->getTagNumber());
        # For a tag less than 31, everything fits comfortably into a single byte.
        } else {
            $bytes = chr($tag | $type->getTagNumber());
        }

        return $bytes;
    }

    /**
     * @param int $num
     * @return string
     * @throws EncoderException
     */
    protected function getEncodedLength(int $num)
    {
        # Short definite length, nothing to do
        if ($num < 128) {
            return chr($num);
        } else {
            return $this->encodeLongDefiniteLength($num);
        }
    }

    /**
     * @param int $num
     * @return string
     * @throws EncoderException
     */
    protected function encodeLongDefiniteLength(int $num)
    {
        # Long definite length is base 256 encoded. This seems kinda inefficient. Found on base_convert comments.
        $num = base_convert($num, 10, 2);
        $num = str_pad($num, ceil(strlen($num) / 8) * 8, '0', STR_PAD_LEFT);

        $bytes = '';
        for ($i = strlen($num) - 8; $i >= 0; $i -= 8) {
            $bytes = chr(base_convert(substr($num, $i, 8), 2, 10)).$bytes;
        }

        $length = strlen($bytes);
        if ($length >= 127) {
            throw new EncoderException('The encoded length cannot be greater than or equal to 127 bytes');
        }

        return chr(0x80 | $length).$bytes;
    }

    /**
     * @param BooleanType $type
     * @return string
     */
    protected function encodeBoolean(BooleanType $type)
    {
        return $type->getValue() ? "\xFF" : "\x00";
    }

    /**
     * @param BitStringType $type
     * @return string
     */
    protected function encodeBitString(BitStringType $type)
    {
        $data = $type->getValue();
        $length = strlen($data);
        $unused = 0;
        if ($length % 8) {
            $unused = 8 - ($length % 8);
            $data = str_pad($data, $length + $unused, $this->options['bitstring_padding']);
        }

        $bytes = chr($unused);
        for ($i = 0; $i < strlen($data) / 8; $i++) {
            $bytes .= chr(bindec(substr($data, $i * 8, 8)));
        }

        return $bytes;
    }

    /**
     * @param RelativeOidType $type
     * @return string
     */
    protected function encodeRelativeOid(RelativeOidType $type)
    {
        $oids = explode('.', $type->getValue());

        $bytes = '';
        foreach ($oids as $oid) {
            $bytes .= $this->intToVlqBytes((int) $oid);
        }

        return $bytes;
    }

    /**
     * @param OidType $type
     * @return string
     * @throws EncoderException
     */
    protected function encodeOid(OidType $type)
    {
        $oids = explode('.', $type->getValue());
        if (count($oids) < 2) {
            throw new EncoderException(sprintf('To encode the OID it must have at least 2 components: %s', $type->getValue()));
        }

        # The first and second components of the OID are represented by one byte using the formula: (X * 40) + Y
        $bytes = chr(($oids[0] * 40) + $oids[1]);
        $length = count($oids);
        for ($i = 2; $i < $length; $i++) {
            $bytes .= $this->intToVlqBytes((int) $oids[$i]);
        }

        return $bytes;
    }

    /**
     * @param GeneralizedTimeType $type
     * @return string
     * @throws EncoderException
     */
    protected function encodeGeneralizedTime(GeneralizedTimeType $type)
    {
        return $this->encodeTime($type, 'YmdH');
    }

    /**
     * @param UtcTimeType $type
     * @return string
     * @throws EncoderException
     */
    protected function encodeUtcTime(UtcTimeType $type)
    {
        return $this->encodeTime($type, 'ymdH');
    }

    /**
     * @param AbstractTimeType $type
     * @param string $format
     * @return string
     * @throws EncoderException
     */
    protected function encodeTime(AbstractTimeType $type, string $format)
    {
        if ($type->getDateTimeFormat() === GeneralizedTimeType::FORMAT_SECONDS || $type->getDateTimeFormat() === GeneralizedTimeType::FORMAT_FRACTIONS) {
            $format .= 'is';
        } elseif ($type->getDateTimeFormat() === GeneralizedTimeType::FORMAT_MINUTES) {
            $format .= 'i';
        }

        # Is it possible to construct a datetime object in this way? Seems better to be safe with this check.
        if ($type->getValue()->format('H') === '24') {
            throw new EncoderException('Midnight must only be specified by 00, not 24.');
        }

        return $this->formatDateTime(
            clone $type->getValue(),
            $type->getDateTimeFormat(),
            $type->getTimeZoneFormat(),
            $format
        );
    }

    /**
     * @param \DateTime $dateTime
     * @param string $dateTimeFormat
     * @param string $tzFormat
     * @param string $format
     * @return string
     */
    protected function formatDateTime(\DateTime $dateTime, string $dateTimeFormat, string $tzFormat, string $format)
    {
        if ($tzFormat === GeneralizedTimeType::TZ_LOCAL) {
            $dateTime->setTimezone(new \DateTimeZone(date_default_timezone_get()));
        } elseif ($tzFormat === GeneralizedTimeType::TZ_UTC) {
            $dateTime->setTimezone(new \DateTimeZone('UTC'));
        }
        $value = $dateTime->format($format);

        # Fractions need special formatting, so we cannot directly include them in the format above.
        $ms = '';
        if ($dateTimeFormat === GeneralizedTimeType::FORMAT_FRACTIONS) {
            $ms = (string) rtrim($dateTime->format('u'), '0');
        }

        $tz = '';
        if ($tzFormat === GeneralizedTimeType::TZ_UTC) {
            $tz = 'Z';
        } elseif ($tzFormat === GeneralizedTimeType::TZ_DIFF) {
            $tz = $dateTime->format('O');
        }

        return $value.($ms !== '' ? '.'.$ms : '').$tz;
    }

    /**
     * @param AbstractType $type
     * @return string
     */
    protected function encodeInteger(AbstractType $type) : string
    {
        $int = abs($type->getValue());
        $isNegative = ($type->getValue() < 0);

        # Subtract one for Two's Complement...
        if ($isNegative) {
            $int = $int - 1;
        }
        # dechex can produce uneven hex while hex2bin requires it to be even
        $hex = dechex($int);
        $bytes = hex2bin((strlen($hex) % 2) === 0 ? $hex : '0'.$hex);

        # Two's Complement, invert the bits...
        if ($isNegative) {
            $len = strlen($bytes);
            for ($i = 0; $i < $len; $i++) {
                $bytes[$i] = ~$bytes[$i];
            }
        }

        # MSB == Most Significant Bit. The one used for the sign.
        $msbSet = (bool) (ord($bytes[0]) & 0x80);
        if (!$isNegative && $msbSet) {
            $bytes = "\x00".$bytes;
        } elseif ($isNegative && !$msbSet) {
            $bytes = "\xFF".$bytes;
        }

        return $bytes;
    }

    /**
     * @param RealType $type
     * @return string
     * @throws EncoderException
     */
    protected function encodeReal(RealType $type)
    {
        $real = $type->getValue();

        # If the value is zero, the contents are omitted
        if ($real === ((float) 0)) {
            return '';
        }
        # If this is infinity, then a single octet of 0x40 is used.
        if ($real === INF) {
            return "\x40";
        }
        # If this is negative infinity, then a single octet of 0x41 is used.
        if ($real === -INF) {
            return "\x41";
        }

        // @todo Real type encoding/decoding is rather complex. Need to implement this yet.
        throw new EncoderException('Real type encoding of this value not yet implemented.');
    }

    /**
     * @param $bytes
     * @return array
     * @throws EncoderException
     */
    protected function decodeGeneralizedTime($bytes)
    {
        return $this->decodeTime($bytes, 'YmdH', GeneralizedTimeType::TIME_REGEX, GeneralizedTimeType::REGEX_MAP);
    }

    /**
     * @param $bytes
     * @return array
     * @throws EncoderException
     */
    protected function decodeUtcTime($bytes)
    {
        return $this->decodeTime($bytes, 'ymdH', UtcTimeType::TIME_REGEX, UtcTimeType::REGEX_MAP);
    }

    /**
     * @param string $bytes
     * @param string $format
     * @param string $regex
     * @param array $matchMap
     * @return array
     * @throws EncoderException
     */
    protected function decodeTime($bytes, string $format, string $regex, array $matchMap) : array
    {
        if (!preg_match($regex, $bytes, $matches)) {
            throw new EncoderException('The datetime format is invalid and cannot be decoded.');
        }
        if ($matches[$matchMap['hours']] === '24') {
            throw new EncoderException('Midnight must only be specified by 00, but got 24.');
        }
        $tzFormat = AbstractTimeType::TZ_LOCAL;
        $dtFormat = AbstractTimeType::FORMAT_HOURS;

        # Minutes
        if (isset($matches[$matchMap['minutes']]) && $matches[$matchMap['minutes']] !== '') {
            $dtFormat = AbstractTimeType::FORMAT_MINUTES;
            $format .= 'i';
        }
        # Seconds
        if (isset($matches[$matchMap['seconds']]) && $matches[$matchMap['seconds']] !== '') {
            $dtFormat = AbstractTimeType::FORMAT_SECONDS;
            $format .= 's';
        }
        # Fractions of a second
        if (isset($matchMap['fractions']) && isset($matches[$matchMap['fractions']]) && $matches[$matchMap['fractions']] !== '') {
            $dtFormat = AbstractTimeType::FORMAT_FRACTIONS;
            $format .= '.u';
        }
        # Timezone
        if (isset($matches[$matchMap['timezone']]) && $matches[$matchMap['timezone']] !== '') {
            $tzFormat = $matches[$matchMap['timezone']] === 'Z' ? AbstractTimeType::TZ_UTC : AbstractTimeType::TZ_DIFF;
            $format .= 'T';
        }
        $this->validateDateFormat($matches, $matchMap);

        $dateTime = \DateTime::createFromFormat($format, $bytes);
        if ($dateTime === false) {
            throw new EncoderException('Unable to decode time to a DateTime object.');
        }

        return [$dateTime, $dtFormat, $tzFormat];
    }

    /**
     * Some encodings have specific restrictions. Allow them to override and validate this.
     *
     * @param array $matches
     * @param array $matchMap
     */
    protected function validateDateFormat(array $matches, array $matchMap)
    {
    }

    /**
     * @param string $bytes
     * @return string
     * @throws EncoderException
     */
    protected function decodeOid($bytes) : string
    {
        # The first 2 digits are contained within the first byte
        $byte = ord($bytes[0]);
        $first = (int) ($byte / 40);
        $second =  $byte - (40 * $first);

        $oid = $first.'.'.$second;
        $bytes = substr($bytes, 1);
        if (strlen($bytes)) {
            $oid .= '.'.$this->decodeRelativeOid($bytes);
        }

        return $oid;
    }

    /**
     * @param $bytes
     * @return string
     * @throws EncoderException
     */
    protected function decodeRelativeOid($bytes) : string
    {
        $oid = '';

        while (strlen($bytes)) {
            $vlqBytes = $this->getVlqBytes($bytes);
            $oid .= ($oid === '' ? '' : '.').$this->getVlqInt($vlqBytes);
            $bytes = substr($bytes, strlen($vlqBytes));
        }

        return $oid;
    }

    /**
     * @param $bytes
     * @return bool
     */
    protected function decodeBoolean($bytes) : bool
    {
        return ord($bytes[0]) !== 0;
    }

    /**
     * @param string $bytes
     * @return string
     * @throws EncoderException
     */
    protected function decodeBitString($bytes) : string
    {
        # The first byte represents the number of unused bits at the end.
        $unused = ord($bytes[0]);
        $bytes = substr($bytes, 1);
        $length = strlen($bytes);

        if ($unused > 7) {
            throw new EncoderException(sprintf(
                'The unused bits in a bit string must be between 0 and 7, got: %s',
                $unused
            ));
        }
        if ($unused > 0 && $length < 1) {
            throw new EncoderException(sprintf(
                'If the bit string is empty the unused bits must be set to 0. However, it is set to %s with %s octets.',
                $unused,
                $length
            ));
        }

        return $this->binaryToBitString($bytes, $length, $unused);
    }

    /**
     * @param string $bytes
     * @param int $length
     * @param int $unused
     * @return string
     */
    protected function binaryToBitString($bytes, int $length, int $unused) : string
    {
        $bitstring = '';

        for ($i = 0; $i < $length; $i++) {
            $octet = sprintf( "%08d", decbin(ord($bytes[$i])));
            if ($i === ($length - 1) && $unused) {
                $bitstring .= substr($octet, 0, ($unused * -1));
            } else {
                $bitstring .= $octet;
            }
        }

        return $bitstring;
    }

    /**
     * @param string $bytes
     * @return int number
     */
    protected function decodeInteger($bytes) : int
    {
        $isNegative = (ord($bytes[0]) & 0x80);
        $len = strlen($bytes);

        # Need to reverse Two's Complement. Invert the bits...
        if ($isNegative) {
            for ($i = 0; $i < $len; $i++) {
                $bytes[$i] = ~$bytes[$i];
            }
        }
        $int = hexdec(bin2hex($bytes));

        # Complete Two's Complement by adding 1 and turning it negative...
        if ($isNegative) {
            $int = ($int + 1) * -1;
        }

        return $int;
    }

    /**
     * @param string $bytes
     * @return float
     * @throws EncoderException
     */
    protected function decodeReal($bytes) : float
    {
        if (strlen($bytes) === 0) {
            return 0;
        }

        $ident = ord($bytes[0]);
        if ($ident === 0x40) {
            return INF;
        }
        if ($ident === 0x41) {
            return -INF;
        }

        // @todo Real type encoding/decoding is rather complex. Need to implement this yet.
        throw new EncoderException('The real type encoding encountered is not yet implemented.');
    }

    /**
     * Encoding subsets may require specific ordering on set types. Allow this to be overridden.
     *
     * @param SetType $set
     * @return string
     * @throws EncoderException
     */
    protected function encodeSet(SetType $set)
    {
        return $this->encodeConstructedType(...$set->getChildren());
    }

    /**
     * Encoding subsets may require specific ordering on set of types. Allow this to be overridden.
     *
     * @param SetOfType $set
     * @return string
     * @throws EncoderException
     */
    protected function encodeSetOf(SetOfType $set)
    {
        return $this->encodeConstructedType(...$set->getChildren());
    }

    /**
     * @param AbstractType[] $types
     * @return string
     * @throws EncoderException
     */
    protected function encodeConstructedType(AbstractType ...$types)
    {
        $bytes = '';

        foreach ($types as $type) {
            $bytes .= $this->encode($type);
        }

        return $bytes;
    }

    /**
     * @param string $bytes
     * @param array $tagMap
     * @return array
     * @throws EncoderException
     * @throws PartialPduException
     */
    protected function decodeConstructedType($bytes, array $tagMap)
    {
        $children = [];

        while ($bytes) {
            list('type' => $type, 'bytes' => $bytes) = $this->decodeBytes($bytes, $tagMap);
            $children[] = $type;
        }

        return $children;
    }
}
