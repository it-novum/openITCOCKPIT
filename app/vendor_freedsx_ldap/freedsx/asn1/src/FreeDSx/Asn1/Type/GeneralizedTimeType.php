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
 * Represents a Generalized Time type.
 *
 * @author Chad Sikorra <Chad.Sikorra@gmail.com>
 */
class GeneralizedTimeType extends AbstractTimeType
{
    /**
     * GeneralizedTime defined as:
     *
     * a)  A string representing the calendar date, as specified in ISO 8601, with a four-digit representation of the
     *     year, a two-digit representation of the month and a two-digit representation of the day, without use of
     *     separators, followed by a string representing the time of day, as specified in ISO 8601, without separators
     *     other than decimal comma or decimal period (as provided for in ISO 8601), and with no terminating Z (as
     *     provided for in ISO 8601); or
     *
     * b)  the characters in a) above followed by an upper-case letter Z; or
     *
     * c)  the characters in a) above followed by a string representing a local time differential, as specified in
     *     ISO 8601, without separators
     */
    public const TIME_REGEX = '~^
        (\d\d\d\d)              # 1  - Year
        (\d\d)                  # 2  - Month
        (\d\d)                  # 3  - Day
        (\d\d)                  # 4  - Hour
        ((\d\d)                 # 6  - Minutes, capture group before since all others are optional  
            ((\d\d)             # 8  - Seconds, capture group as this can be optional
                ((\.\d{1,}))?   # 10 - Fractions of seconds, also optional. 
            )?                  # ---- End of seconds capture group. 
        )?                      # ---- End of minutes capture group.
        (Z|[\+\-]\d\d\d\d)?     # 11 - Timezone modifier (optional). It can either be a Z (UTC) or a time differential.
    $~x';

    public const REGEX_MAP = [
        'hours' => 4,
        'minutes' => 6,
        'seconds' => 8,
        'fractions' => 10,
        'timezone' => 11,
    ];

    protected $tagNumber = self::TAG_TYPE_GENERALIZED_TIME;

    /**
     * Valid datetime formats.
     */
    protected $validDateFormats = [
        self::FORMAT_HOURS,
        self::FORMAT_MINUTES,
        self::FORMAT_SECONDS,
        self::FORMAT_FRACTIONS,
    ];

    /**
     * Valid timezone formats
     */
    protected $validTzFormats = [
        self::TZ_UTC,
        self::TZ_DIFF,
        self::TZ_LOCAL,
    ];

    /**
     * {@inheritdoc}
     */
    public function __construct(?\DateTime $dateTime = null, string $dateFormat = self::FORMAT_FRACTIONS, string $tzFormat = self::TZ_UTC)
    {
        parent::__construct($dateTime, $dateFormat, $tzFormat);
    }
}
