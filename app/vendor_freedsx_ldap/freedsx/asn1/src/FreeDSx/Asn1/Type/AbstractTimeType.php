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

use FreeDSx\Asn1\Exception\InvalidArgumentException;

/**
 * Generalized / UTC time type.
 *
 * @author Chad Sikorra <Chad.Sikorra@gmail.com>
 */
class AbstractTimeType extends AbstractType
{
    /**
     * Format the date time to the minutes.
     */
    public const FORMAT_HOURS = 'hours';

    /**
     * Format the date time to the minutes.
     */
    public const FORMAT_MINUTES = 'minutes';

    /**
     * Format the date time to the seconds.
     */
    public const FORMAT_SECONDS = 'seconds';

    /**
     * Format the datetime to the fractional seconds if possible (empty fractionals not allowed), otherwise  to the seconds.
     */
    public const FORMAT_FRACTIONS = 'fractions';

    /**
     * Use local time (ie. no ending timezone specification)
     */
    public const TZ_LOCAL = 'local';

    /**
     * Use a UTC timezone (ie. end with a Z)
     */
    public const TZ_UTC = 'utc';

    /**
     * Use a timezone differential (ie. -0500)
     */
    public const TZ_DIFF = 'diff';

    /**
     * @var string[] Valid datetime formats.
     */
    protected $validDateFormats = [];

    /**
     * @var string[] Valid timezone formats
     */
    protected $validTzFormats = [];

    /**
     * @var string
     */
    protected $tzFormat;

    /**
     * @var string
     */
    protected $dateFormat;

    /**
     * @param \DateTime $dateTime
     * @param string $dateFormat Represents the furthest datetime element to represent in the datetime object.
     * @param string $tzFormat Represents the format of the timezone.
     */
    public function __construct(?\DateTime $dateTime = null, string $dateFormat, string $tzFormat)
    {
        $this->setDateTimeFormat($dateFormat);
        $this->setTimeZoneFormat($tzFormat);
        parent::__construct($dateTime ?? new \DateTime());
    }

    /**
     * @param \DateTime $dateTime
     * @return $this
     */
    public function setValue(\DateTime $dateTime)
    {
        $this->value = $dateTime;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getValue() : \DateTime
    {
        return $this->value;
    }

    /**
     * @return string
     */
    public function getTimeZoneFormat() : string
    {
        return $this->tzFormat;
    }

    /**
     * @param string $tzFormat
     * @return $this
     */
    public function setTimeZoneFormat(string $tzFormat)
    {
        if (!in_array($tzFormat, $this->validTzFormats)) {
            throw new InvalidArgumentException(sprintf(
                'The timezone format %s is not valid. It must be one of: %s',
                $tzFormat,
                implode(', ', $this->validTzFormats)
            ));
        }
        $this->tzFormat = $tzFormat;

        return $this;
    }

    /**
     * @return string
     */
    public function getDateTimeFormat() : string
    {
        return $this->dateFormat;
    }

    /**
     * @param string $dateFormat
     * @return $this
     */
    public function setDateTimeFormat(string $dateFormat)
    {
        if (!in_array($dateFormat, $this->validDateFormats)) {
            throw new InvalidArgumentException(sprintf(
                'The datetime format %s is not valid. It must be one of: %s',
                $dateFormat,
                implode(', ', $this->validDateFormats)
            ));
        }
        $this->dateFormat = $dateFormat;

        return $this;
    }
}
