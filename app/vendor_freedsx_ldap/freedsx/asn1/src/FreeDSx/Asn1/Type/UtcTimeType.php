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
 * Represents a UTC Time type.
 *
 * @author Chad Sikorra <Chad.Sikorra@gmail.com>
 */
class UtcTimeType extends AbstractTimeType
{
    /**
     * Only a 2 day year (was Y2K not a thing back then?), seconds are optional, Z or time differential.
     */
    public const TIME_REGEX = '~^
        (\d\d)                # 1 - Year
        (\d\d)                # 2 - Month
        (\d\d)                # 3 - Day
        (\d\d)                # 4 - Hour
        (\d\d)                # 5 - Minutes
        (\d\d)?               # 6 - Seconds, which are optional
        (Z|[\+\-]\d\d\d\d)    # 7 - Timezone modifier (not optional). It can either be a Z (UTC) or a time differential.
    $~x';

    public const REGEX_MAP = [
        'hours' => 4,
        'minutes' => 5,
        'seconds' => 6,
        'timezone' => 7,
    ];

    protected $tagNumber = self::TAG_TYPE_UTC_TIME;

    /**
     * Valid datetime formats.
     */
    protected $validDateFormats = [
        self::FORMAT_SECONDS,
        self::FORMAT_MINUTES,
    ];

    /**
     * Valid timezone formats
     */
    protected $validTzFormats = [
        self::TZ_UTC,
        self::TZ_DIFF,
    ];

    /**
     * {@inheritdoc}
     */
    public function __construct(?\DateTime $dateTime = null, string $dateFormat = self::FORMAT_SECONDS, string $tzFormat = self::TZ_UTC)
    {
        parent::__construct($dateTime, $dateFormat, $tzFormat);
    }
}
