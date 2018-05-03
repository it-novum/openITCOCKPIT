<?php
/**
 * This file is part of the FreeDSx ASN1 package.
 *
 * (c) Chad Sikorra <Chad.Sikorra@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FreeDSx\Asn1\Factory;

use FreeDSx\Asn1\Exception\EncoderException;
use FreeDSx\Asn1\Exception\InvalidArgumentException;
use FreeDSx\Asn1\Type\AbstractType;
use FreeDSx\Asn1\Type\BitStringType;
use FreeDSx\Asn1\Type\BmpStringType;
use FreeDSx\Asn1\Type\BooleanType;
use FreeDSx\Asn1\Type\CharacterStringType;
use FreeDSx\Asn1\Type\EnumeratedType;
use FreeDSx\Asn1\Type\GeneralizedTimeType;
use FreeDSx\Asn1\Type\GeneralStringType;
use FreeDSx\Asn1\Type\GraphicStringType;
use FreeDSx\Asn1\Type\IA5StringType;
use FreeDSx\Asn1\Type\IntegerType;
use FreeDSx\Asn1\Type\NullType;
use FreeDSx\Asn1\Type\NumericStringType;
use FreeDSx\Asn1\Type\OctetStringType;
use FreeDSx\Asn1\Type\OidType;
use FreeDSx\Asn1\Type\PrintableStringType;
use FreeDSx\Asn1\Type\RealType;
use FreeDSx\Asn1\Type\RelativeOidType;
use FreeDSx\Asn1\Type\SequenceType;
use FreeDSx\Asn1\Type\SetType;
use FreeDSx\Asn1\Type\TeletexStringType;
use FreeDSx\Asn1\Type\UniversalStringType;
use FreeDSx\Asn1\Type\UtcTimeType;
use FreeDSx\Asn1\Type\Utf8StringType;
use FreeDSx\Asn1\Type\VideotexStringType;
use FreeDSx\Asn1\Type\VisibleStringType;

/**
 * Constructs tag types to their objects using the universal tag number and the value for the object.
 *
 * @author Chad Sikorra <Chad.Sikorra@gmail.com>
 */
class TypeFactory
{
    /**
     * @var array
     */
    protected static $map = [
        AbstractType::TAG_TYPE_BOOLEAN => BooleanType::class,
        AbstractType::TAG_TYPE_INTEGER => IntegerType::class,
        AbstractType::TAG_TYPE_BIT_STRING => BitStringType::class,
        AbstractType::TAG_TYPE_OCTET_STRING => OctetStringType::class,
        AbstractType::TAG_TYPE_NULL => NullType::class,
        AbstractType::TAG_TYPE_OID => OidType::class,
        AbstractType::TAG_TYPE_RELATIVE_OID => RelativeOidType::class,
        // @todo AbstractType::TAG_TYPE_EXTERNAL
        AbstractType::TAG_TYPE_REAL => RealType::class,
        AbstractType::TAG_TYPE_ENUMERATED => EnumeratedType::class,
        // @todo AbstractType::TAG_TYPE_EMBEDDED_PDV
        AbstractType::TAG_TYPE_UTF8_STRING => Utf8StringType::class,
        AbstractType::TAG_TYPE_SEQUENCE => SequenceType::class,
        AbstractType::TAG_TYPE_SET => SetType::class,
        AbstractType::TAG_TYPE_NUMERIC_STRING => NumericStringType::class,
        AbstractType::TAG_TYPE_PRINTABLE_STRING => PrintableStringType::class,
        AbstractType::TAG_TYPE_TELETEX_STRING => TeletexStringType::class,
        AbstractType::TAG_TYPE_VIDEOTEX_STRING => VideotexStringType::class,
        AbstractType::TAG_TYPE_IA5_STRING => IA5StringType::class,
        AbstractType::TAG_TYPE_UTC_TIME => UtcTimeType::class,
        AbstractType::TAG_TYPE_GENERALIZED_TIME => GeneralizedTimeType::class,
        AbstractType::TAG_TYPE_GRAPHIC_STRING => GraphicStringType::class,
        AbstractType::TAG_TYPE_VISIBLE_STRING => VisibleStringType::class,
        AbstractType::TAG_TYPE_GENERAL_STRING => GeneralStringType::class,
        AbstractType::TAG_TYPE_UNIVERSAL_STRING => UniversalStringType::class,
        AbstractType::TAG_TYPE_CHARACTER_STRING => CharacterStringType::class,
        AbstractType::TAG_TYPE_BMP_STRING => BmpStringType::class,
    ];

    /**
     * Given a tag number, value, and whether it should be constructed, construct the type object.
     *
     * @param mixed $value
     * @param int|null $tagType
     * @param bool $isConstructed
     * @return AbstractType
     * @throws EncoderException
     */
    public static function create(int $tagType, $value, bool $isConstructed = false) : AbstractType
    {
        if (!isset(self::$map[$tagType])) {
            throw new EncoderException(sprintf('Tag type %s has no class mapping.', $tagType));
        }

        if ($isConstructed) {
            $value = is_array($value) ? $value : [$value];

            /** @var AbstractType $type */
            $type = (new self::$map[$tagType]);
            $type->setChildren(...$value);

            return $type;
        } elseif ($tagType === AbstractType::TAG_TYPE_NULL) {
            return new NullType();
        }elseif ($tagType === AbstractType::TAG_TYPE_GENERALIZED_TIME || $tagType === AbstractType::TAG_TYPE_UTC_TIME) {
            return new self::$map[$tagType](...$value);
        } else {
            return new self::$map[$tagType]($value);
        }
    }

    /**
     * Set a type to map to a specific class.
     *
     * @param int $type
     * @param string $class
     */
    public static function setType(int $type, string $class)
    {
        if (!class_exists($class)) {
            throw new InvalidArgumentException(sprintf(
                'The class does not exist: %s',
                $class
            ));
        } elseif (!is_subclass_of($class, AbstractType::class)) {
            throw new InvalidArgumentException(sprintf(
                'The class must extend AbstractType, but it does not: %s',
                $class
            ));
        }
        self::$map[$type] = $class;
    }

    /**
     * Check if a type is mapped to a specific class.
     *
     * @param int $type
     * @return bool
     */
    public static function hasType(int $type) : bool
    {
        return isset(self::$map[$type]);
    }

    /**
     * Get the class mapped to a specific type. Will return null if no such mapping exists.
     *
     * @param int $type
     * @return null|string
     */
    public static function getType(int $type) : ?string
    {
        return self::hasType($type) ? self::$map[$type] : null;
    }
}
