<?php
/**
 * This file is part of the FreeDSx ASN1 package.
 *
 * (c) Chad Sikorra <Chad.Sikorra@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FreeDSx\Asn1;

use FreeDSx\Asn1\Type\AbstractTimeType;
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
use FreeDSx\Asn1\Type\SequenceOfType;
use FreeDSx\Asn1\Type\SequenceType;
use FreeDSx\Asn1\Type\SetOfType;
use FreeDSx\Asn1\Type\SetType;
use FreeDSx\Asn1\Type\TeletexStringType;
use FreeDSx\Asn1\Type\UniversalStringType;
use FreeDSx\Asn1\Type\UtcTimeType;
use FreeDSx\Asn1\Type\Utf8StringType;
use FreeDSx\Asn1\Type\VideotexStringType;
use FreeDSx\Asn1\Type\VisibleStringType;

/**
 * Used to construct various ASN1 structures.
 *
 * @author Chad Sikorra <Chad.Sikorra@gmail.com>
 */
class Asn1
{
    /**
     * @param AbstractType[] ...$types
     * @return SequenceType
     */
    public static function sequence(AbstractType ...$types) : SequenceType
    {
        return new SequenceType(...$types);
    }

    /**
     * @param AbstractType[] ...$types
     * @return SequenceOfType
     */
    public static function sequenceOf(AbstractType ...$types) : SequenceOfType
    {
        return new SequenceOfType(...$types);
    }

    /**
     * @param int $int
     * @return IntegerType
     */
    public static function integer(int $int) : IntegerType
    {
        return new IntegerType($int);
    }

    /**
     * @param bool $bool
     * @return BooleanType
     */
    public static function boolean(bool $bool) : BooleanType
    {
        return new BooleanType($bool);
    }

    /**
     * @param int $enum
     * @return EnumeratedType
     */
    public static function enumerated(int $enum) : EnumeratedType
    {
        return new EnumeratedType($enum);
    }

    /**
     * @param float $real
     * @return RealType
     */
    public static function real(float $real) : RealType
    {
        return new RealType($real);
    }

    /**
     * @return NullType
     */
    public static function null() : NullType
    {
        return new NullType();
    }

    /**
     * @param string $string
     * @return OctetStringType
     */
    public static function octetString(string $string) : OctetStringType
    {
        return new OctetStringType($string);
    }

    /**
     * @param string $bitString
     * @return BitStringType
     */
    public static function bitString(string $bitString) : BitStringType
    {
        return new BitStringType($bitString);
    }

    /**
     * @param int $integer
     * @return BitStringType
     */
    public static function bitStringFromInteger(int $integer) : BitStringType
    {
        return BitStringType::fromInteger($integer);
    }

    /**
     * @param string $binary
     * @return BitStringType
     */
    public static function bitStringFromBinary($binary) : BitStringType
    {
        return BitStringType::fromBinary($binary);
    }

    /**
     * @param string $oid
     * @return OidType
     */
    public static function oid(string $oid) : OidType
    {
        return new OidType($oid);
    }

    /**
     * @param string $oid
     * @return RelativeOidType
     */
    public static function relativeOid(string $oid) : RelativeOidType
    {
        return new RelativeOidType($oid);
    }

    /**
     * @param string $string
     * @return BmpStringType
     */
    public static function bmpString(string $string) : BmpStringType
    {
        return new BmpStringType($string);
    }

    /**
     * @param string $string
     * @return CharacterStringType
     */
    public static function charString(string $string) : CharacterStringType
    {
        return new CharacterStringType($string);
    }

    /**
     * @param \DateTime $dateTime
     * @param string $dateFormat
     * @param string $tzFormat
     * @return GeneralizedTimeType
     */
    public static function generalizedTime(?\DateTime $dateTime = null, string $dateFormat = AbstractTimeType::FORMAT_FRACTIONS, string $tzFormat = AbstractTimeType::TZ_UTC) : GeneralizedTimeType
    {
        return new GeneralizedTimeType($dateTime, $dateFormat, $tzFormat);
    }

    /**
     * @param \DateTime $dateTime
     * @param string $dateFormat
     * @param string $tzFormat
     * @return UtcTimeType
     */
    public static function utcTime(?\DateTime $dateTime = null, string $dateFormat = AbstractTimeType::FORMAT_SECONDS, string $tzFormat = AbstractTimeType::TZ_UTC) : UtcTimeType
    {
        return new UtcTimeType($dateTime, $dateFormat, $tzFormat);
    }

    /**
     * @param string $string
     * @return GeneralStringType
     */
    public static function generalString(string $string) : GeneralStringType
    {
        return new GeneralStringType($string);
    }

    /**
     * @param string $string
     * @return GraphicStringType
     */
    public static function graphicString(string $string) : GraphicStringType
    {
        return new GraphicStringType($string);
    }

    /**
     * @param string $string
     * @return IA5StringType
     */
    public static function ia5String(string $string) : IA5StringType
    {
        return new IA5StringType($string);
    }

    /**
     * @param string $string
     * @return NumericStringType
     */
    public static function numericString(string $string) : NumericStringType
    {
        return new NumericStringType($string);
    }

    /**
     * @param string $string
     * @return PrintableStringType
     */
    public static function printableString(string $string) : PrintableStringType
    {
        return new PrintableStringType($string);
    }

    /**
     * @param string $string
     * @return TeletexStringType
     */
    public static function teletexString(string $string) : TeletexStringType
    {
        return new TeletexStringType($string);
    }

    /**
     * @param string $string
     * @return UniversalStringType
     */
    public static function universalString(string $string) : UniversalStringType
    {
        return new UniversalStringType($string);
    }

    /**
     * @param string $string
     * @return Utf8StringType
     */
    public static function utf8String(string $string) : Utf8StringType
    {
        return new Utf8StringType($string);
    }

    /**
     * @param string $string
     * @return VideotexStringType
     */
    public static function videotexString(string $string) : VideotexStringType
    {
        return new VideotexStringType($string);
    }

    /**
     * @param string $string
     * @return VisibleStringType
     */
    public static function visibleString(string $string) : VisibleStringType
    {
        return new VisibleStringType($string);
    }

    /**
     * @param AbstractType[] ...$types
     * @return SetType
     */
    public static function set(AbstractType ...$types) : SetType
    {
        return new SetType(...$types);
    }

    /**
     * @param AbstractType[] ...$types
     * @return SetOfType
     */
    public static function setOf(AbstractType ...$types) : SetOfType
    {
        return new SetOfType(...$types);
    }

    /**
     * @param $tagNumber
     * @param AbstractType $type
     * @return AbstractType
     */
    public static function context(int $tagNumber, AbstractType $type)
    {
        return $type->setTagClass(AbstractType::TAG_CLASS_CONTEXT_SPECIFIC)->setTagNumber($tagNumber);
    }

    /**
     * @param $tagNumber
     * @param AbstractType $type
     * @return AbstractType
     */
    public static function application(int $tagNumber, AbstractType $type)
    {
        return $type->setTagClass(AbstractType::TAG_CLASS_APPLICATION)->setTagNumber($tagNumber);
    }

    /**
     * @param int $tagNumber
     * @param AbstractType $type
     * @return AbstractType
     */
    public static function universal(int $tagNumber, AbstractType $type)
    {
        return $type->setTagClass(AbstractType::TAG_CLASS_UNIVERSAL)->setTagNumber($tagNumber);
    }

    /**
     * @param int $tagNumber
     * @param AbstractType $type
     * @return AbstractType
     */
    public static function private(int $tagNumber, AbstractType $type)
    {
        return $type->setTagClass(AbstractType::TAG_CLASS_PRIVATE)->setTagNumber($tagNumber);
    }
}
