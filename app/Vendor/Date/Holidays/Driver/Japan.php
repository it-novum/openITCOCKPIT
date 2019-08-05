<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
/**
 * Driver for holidays in Japanese
 *
 * PHP Version 5
 *
 * Copyright (c) 1997-2008 The PHP Group
 *
 * This source file is subject to version 3.0 of the PHP license,
 * that is bundled with this package in the file LICENSE, and is
 * available at through the world-wide-web at
 * http://www.php.net/license/3_01.txt.
 * If you did not receive a copy of the PHP license and are unable to
 * obtain it through the world-wide-web, please send a note to
 * license@php.net so we can mail you a copy immediately.
 *
 * @category Date
 * @package  Date_Holidays
 * @author   Hideyuki Shimooka <shimooka@doyouphp.jp>
 * @license  http://www.php.net/license/3_01.txt PHP License 3.0.1
 * @version  CVS: $Id$
 * @link     http://pear.php.net/package/Date_Holidays
 * @see      http://www.h3.dion.ne.jp/~sakatsu/holiday_topic.htm
 */

/**
 * Extends Date_Holidays_Driver
 */
require_once __DIR__ . '/../Driver.php';

/**
 * the gradient parameter of the approximate expression
 * to calculate equinox day
 *
 * @access  public
 */
define('DATE_HOLIDAYS_EQUINOX_GRADIENT', 0.242194);

/**
 * the initial parameter of the approximate expression
 * to calculate vernal equinox day from 1948 to 1979
 *
 * @access  public
 */
define('DATE_HOLIDAYS_VERNAL_EQUINOX_PARAM_1979', 20.8357);

/**
 * the initial parameter of the approximate expression
 * to calculate vernal equinox day from 1980 to 2099
 *
 * @access  public
 */
define('DATE_HOLIDAYS_VERNAL_EQUINOX_PARAM_2099', 20.8431);

/**
 * the initial parameter of the approximate expression
 * to calculate vernal equinox day from 2100 to 2150
 *
 * @access  public
 */
define('DATE_HOLIDAYS_VERNAL_EQUINOX_PARAM_2150', 21.8510);

/**
 * the initial parameter of the approximate expression
 * to calculate autumnal equinox day from 1948 to 1979
 *
 * @access  public
 */
define('DATE_HOLIDAYS_AUTUMNAL_EQUINOX_PARAM_1979', 23.2588);

/**
 * the initial parameter of the approximate expression
 * to calculate autumnal equinox day from 1980 to 2099
 *
 * @access  public
 */
define('DATE_HOLIDAYS_AUTUMNAL_EQUINOX_PARAM_2099', 23.2488);

/**
 * the initial parameter of the approximate expression
 * to calculate autumnal equinox day from 2100 to 2150
 *
 * @access  public
 */
define('DATE_HOLIDAYS_AUTUMNAL_EQUINOX_PARAM_2150', 24.2488);

/**
 * class that calculates Japanese holidays
 *
 * @category   Date
 * @package    Date_Holidays
 * @subpackage Driver
 * @author     Hideyuki Shimooka <shimooka@doyouphp.jp>
 * @license    http://www.php.net/license/3_01.txt PHP License 3.0.1
 * @version    CVS: $Id$
 * @link       http://pear.php.net/package/Date_Holidays
 * @see        http://www.h3.dion.ne.jp/~sakatsu/holiday_topic.htm
 */
class Date_Holidays_Driver_Japan extends Date_Holidays_Driver
{
    /**
     * this driver's name
     *
     * @access   protected
     * @var      string
     */
    var $_driverName = 'Japan';

    /**
     * a translation file name
     *
     * @access  private
     */
    var $_translationFile = null;

    /**
     * a translation locale
     *
     * @access  private
     */
    var $_translationLocale = null;

    /**
     * Constructor
     *
     * Use the Date_Holidays::factory() method to construct an object of a
     * certain driver
     *
     * @access   protected
     */
    public function __construct()
    {
    }

    /**
     * Build the internal arrays that contain data about the calculated holidays
     *
     * @access   protected
     * @return   boolean true on success, otherwise a PEAR_ErrorStack object
     * @throws   object PEAR_ErrorStack
     */
    function _buildHolidays()
    {
        parent::_buildHolidays();

        $this->_clearHolidays();

        $this->_buildNewYearsDay();
        $this->_buildComingofAgeDay();
        $this->_buildNationalFoundationDay();
        $this->_buildVernalEquinoxDay();
        $this->_buildShowaDay();
        $this->_buildConstitutionMemorialDay();
        $this->_buildGreeneryDay();
        $this->_buildChildrensDay();
        $this->_buildMarineDay();
        $this->_buildMountainDay();
        $this->_buildRespectfortheAgedDay();
        $this->_buildAutumnalEquinoxDay();
        $this->_buildHealthandSportsDay();
        $this->_buildNationalCultureDay();
        $this->_buildLaborThanksgivingDay();
        $this->_buildEmperorsBirthday();

        $this->_buildOtherMemorialDays();

        $this->_buildSubstituteHolidays();

        return true;
    }

    /**
     * Method that returns an array containing the ISO3166 codes that may possibly
     * identify a driver.
     *
     * @static
     * @access public
     * @return array possible ISO3166 codes
     */
    function getISO3166Codes()
    {
        return array('jp', 'jpn');
    }

    /**
     * build day of New Year's Day
     *
     * @access   private
     * @return   void
     */
    function _buildNewYearsDay()
    {
        if ($this->_year >= 1949) {
            $this->_addHoliday('newYearsDay',
                               $this->_year . '-01-01',
                               'New Year\'s Day');
        }
    }

    /**
     * build day of Coming of Age Day
     *
     * @access   private
     * @return   void
     */
    function _buildComingofAgeDay()
    {
        $date = null;
        if ($this->_year >= 2000) {
            $date = $this->_calcNthMondayInMonth(1, 2);
        } else if ($this->_year >= 1949) {
            $date = $this->_year . '-01-15';
        }
        if (!is_null($date)) {
            $this->_addHoliday('comingOfAgeDay',
                               $date,
                               'Coming of Age Day');
        }
    }

    /**
     * build day of National Foundation Day
     *
     * @access   private
     * @return   void
     */
    function _buildNationalFoundationDay()
    {
        if ($this->_year >= 1949) {
            $this->_addHoliday('nationalFoundationDay',
                               $this->_year . '-02-11',
                               'National Foundation Day');
        }
    }

    /**
     * build day of Vernal Equinox Day
     *
     * use approximate expression to calculate equinox day internally.
     *
     * @access   private
     * @return   void
     * @see      http://www.h3.dion.ne.jp/~sakatsu/holiday_topic.htm (in Japanese)
     */
    function _buildVernalEquinoxDay()
    {
        $day = null;
        if ($this->_year >= 1948 && $this->_year <= 1979) {
            $day = floor(DATE_HOLIDAYS_VERNAL_EQUINOX_PARAM_1979 +
                         DATE_HOLIDAYS_EQUINOX_GRADIENT *
                         ($this->_year - 1980) -
                         floor(($this->_year - 1980) / 4));
        } else if ($this->_year <= 2099) {
            $day = floor(DATE_HOLIDAYS_VERNAL_EQUINOX_PARAM_2099 +
                         DATE_HOLIDAYS_EQUINOX_GRADIENT *
                         ($this->_year - 1980) -
                         floor(($this->_year - 1980) / 4));
        } else if ($this->_year <= 2150) {
            $day = floor(DATE_HOLIDAYS_VERNAL_EQUINOX_PARAM_2150 +
                         DATE_HOLIDAYS_EQUINOX_GRADIENT *
                         ($this->_year - 1980) -
                         floor(($this->_year - 1980) / 4));
        }
        if (!is_null($day)) {
            $this->_addHoliday('vernalEquinoxDay',
                               sprintf('%04d-%02d-%02d', $this->_year, 3, $day),
                               'Vernal Equinox Day');
        }
    }

    /**
     * build day of Showa Day
     *
     * @access   private
     * @return   void
     */
    function _buildShowaDay()
    {
        $internalName = null;
        $title = null;
        if ($this->_year >= 2007) {
            $internalName = 'showaDay';
            $title = 'Showa Day';
        } else if ($this->_year >= 1989) {
            $internalName = 'greeneryDay';
            $title = 'Greenery Day';
        } else if ($this->_year >= 1949) {
            $internalName = 'showaEmperorsBirthday';
            $title = 'Showa Emperor\'s Birthday';
        }
        if (!is_null($internalName)) {
            $this->_addHoliday($internalName,
                               $this->_year . '-04-29',
                               $title);
        }
    }

    /**
     * build day of Constitution Memorial Day
     *
     * @access   private
     * @return   void
     */
    function _buildConstitutionMemorialDay()
    {
        if ($this->_year >= 1949) {
            $this->_addHoliday('constitutionMemorialDay',
                               $this->_year . '-05-03',
                               'Constitution Memorial Day');
        }
    }

    /**
     * build day of Greenery Day
     *
     * @access   private
     * @return   void
     */
    function _buildGreeneryDay()
    {
        $internalName = null;
        $title = null;
        if ($this->_year >= 2007) {
            $internalName = 'greeneryDay';
            $title = 'Greenery Day';
        } else if ($this->_year >= 1986) {
            $date = new Date($this->_year . '-05-04');
            if ($date->getDayOfWeek() != 0) {
                $internalName = 'nationalHoliday';
                $title = 'National Holiday';
            }
        }
        if (!is_null($internalName)) {
            $this->_addHoliday($internalName,
                               $this->_year . '-05-04',
                               $title);
        }
    }

    /**
     * build day of Children's Day
     *
     * @access   private
     * @return   void
     */
    function _buildChildrensDay()
    {
        if ($this->_year >= 1949) {
            $this->_addHoliday('childrensDay',
                               $this->_year . '-05-05',
                               'Children\'s Day');
        }
    }

    /**
     * build day of Marine Day
     *
     * @access   private
     * @return   void
     */
    function _buildMarineDay()
    {
        $date = null;
        if ($this->_year >= 2003) {
            $date = $this->_calcNthMondayInMonth(7, 3);
        } else if ($this->_year >= 1996) {
            $date = $this->_year . '-07-20';
        }
        if (!is_null($date)) {
            $this->_addHoliday('marineDay',
                               $date,
                               'Marine Day');
        }
    }

    /**
     * build day of Mountain Day
     *
     * @access   private
     * @return   void
     */
    function _buildMountainDay()
    {
        $date = null;
        if ($this->_year >= 2016) {
            $date = $this->_year . '-08-11';
        }
        if (!is_null($date)) {
            $this->_addHoliday('mountainDay',
                               $date,
                               'Mountain Day');
        }
    }

    /**
     * build day of Respect for the Aged Day
     *
     * @access   private
     * @return   void
     */
    function _buildRespectfortheAgedDay()
    {
        $date = null;
        if ($this->_year >= 2003) {
            $date = $this->_calcNthMondayInMonth(9, 3);
        } else if ($this->_year >= 1966) {
            $date = $this->_year . '-09-15';
        }
        if (!is_null($date)) {
            $this->_addHoliday('respectfortheAgedDay',
                               $date,
                               'Respect for the Aged Day');
        }
    }

    /**
     * build day of Health and Sports Day
     *
     * @access   private
     * @return   void
     */
    function _buildHealthandSportsDay()
    {
        $date = null;
        if ($this->_year >= 2000) {
            $date = $this->_calcNthMondayInMonth(10, 2);
        } else if ($this->_year >= 1966) {
            $date = $this->_year . '-10-10';
        }
        if (!is_null($date)) {
            $this->_addHoliday('healthandSportsDay',
                               $date,
                               'Health and Sports Day');
        }
    }

    /**
     * build day of Autumnal Equinox Day
     *
     * use approximate expression to calculate equinox day internally.
     *
     * @access   private
     * @return   void
     * @see      http://www.h3.dion.ne.jp/~sakatsu/holiday_topic.htm (in Japanese)
     */
    function _buildAutumnalEquinoxDay()
    {
        $day = null;
        if ($this->_year >= 1948 && $this->_year <= 1979) {
            $day = floor(DATE_HOLIDAYS_AUTUMNAL_EQUINOX_PARAM_1979 +
                         DATE_HOLIDAYS_EQUINOX_GRADIENT *
                         ($this->_year - 1980) -
                         floor(($this->_year - 1980) / 4));
        } else if ($this->_year <= 2099) {
            $day = floor(DATE_HOLIDAYS_AUTUMNAL_EQUINOX_PARAM_2099 +
                         DATE_HOLIDAYS_EQUINOX_GRADIENT *
                         ($this->_year - 1980) -
                         floor(($this->_year - 1980) / 4));
        } else if ($this->_year <= 2150) {
            $day = floor(DATE_HOLIDAYS_AUTUMNAL_EQUINOX_PARAM_2150 +
                         DATE_HOLIDAYS_EQUINOX_GRADIENT *
                         ($this->_year - 1980) -
                         floor(($this->_year - 1980) / 4));
        }
        if (!is_null($day)) {
            $this->_addHoliday('autumnalEquinoxDay',
                               sprintf('%04d-%02d-%02d', $this->_year, 9, $day),
                               'Autumnal Equinox Day');

            if ($this->_year >= 2003 &&
                $this->getHolidayDate('autumnalEquinoxDay')->getDayOfWeek() == 3) {
                $this->_addHoliday('nationalHolidayBeforeAutumnalEquinoxDay',
                    $this->getHolidayDate('autumnalEquinoxDay')->getPrevDay(),
                    'National Holiday before Autumnal Equinox Day');
            }
        }
    }

    /**
     * build day of National Culture Day
     *
     * @access   private
     * @return   void
     */
    function _buildNationalCultureDay()
    {
        if ($this->_year >= 1948) {
            $this->_addHoliday('nationalCultureDay',
                               $this->_year . '-11-03',
                               'National Culture Day');
        }
    }

    /**
     * build day of Labor Thanksgiving Day
     *
     * @access   private
     * @return   void
     */
    function _buildLaborThanksgivingDay()
    {
        if ($this->_year >= 1948) {
            $this->_addHoliday('laborThanksgivingDay',
                               $this->_year . '-11-23',
                               'Labor Thanksgiving Day');
        }
    }

    /**
     * build day of Emperor's Birthday
     *
     * @access   private
     * @return   void
     */
    function _buildEmperorsBirthday()
    {
        if ($this->_year >= 1989) {
            $this->_addHoliday('emperorsBirthday',
                               $this->_year . '-12-23',
                               'Emperor\'s Birthday');
        }
    }

    /**
     * build day of Emperor's Birthday
     *
     * @access   private
     * @return   void
     */
    function _buildOtherMemorialDays()
    {
        if ($this->_year == 1959) {
            $this->_addHoliday('theRiteofWeddingofHIHCrownPrinceAkihito',
                               $this->_year . '-04-10',
                               'The Rite of Wedding of HIH Crown Prince Akihito');
        }
        if ($this->_year == 1989) {
            $this->_addHoliday('theFuneralCeremonyofEmperorShowa.',
                               $this->_year . '-02-24',
                               'The Funeral Ceremony of Emperor Showa.');
        }
        if ($this->_year == 1990) {
            $this->_addHoliday('theCeremonyoftheEnthronementof'
                             . 'HisMajestytheEmperor(attheSeiden)',
                               $this->_year . '-11-12',
                               'The Ceremony of the Enthronement of ' .
                               'His Majesty the Emperor (at the Seiden)');
        }
        if ($this->_year == 1993) {
            $this->_addHoliday('theRiteofWeddingofHIHCrownPrinceNaruhito',
                               $this->_year . '-06-09',
                               'The Rite of Wedding of HIH Crown Prince Naruhito');
        }
    }

    /**
     * build day of substitute holiday
     *
     * @access   private
     * @return   void
     */
    function _buildSubstituteHolidays()
    {
        // calculate 'current' substitute holidays
        foreach ($this->_dates as $internalName => $date) {
            if ($date->getDayOfWeek() == 0) {
                if ($this->_year >= 2007) {
                    while (in_array($date, $this->_dates)) {
                        $date = $date->getNextDay();
                    }
                } else if ($date->getDate() >= '1973-04-12') {
                    $date = $date->getNextDay();
                    if (in_array($date, $this->_dates)) {
                        continue;
                    }
                } else {
                    continue;
                }
                if (!is_null($date)) {
                    $name = 'substituteHolidayFor' . $internalName;
                    $this->_addHoliday($name,
                                       $date,
                                       'Substitute Holiday for ' .
                                       $this->_titles['C'][$internalName]);
                }
            }
        }

        // reset translated titles if set.
        // because substitute Holidays change each year.
        if (!is_null($this->_translationFile)) {
            $ext = substr($this->_translationFile, -3);
            if ($ext === 'xml') {
                $this->addTranslationFile($this->_translationFile,
                                          $this->_translationLocale);
            } else if ($ext === 'ser') {
                $this->addCompiledTranslationFile($this->_translationFile,
                                                  $this->_translationLocale);
            }
        }
    }


    /**
     * Add a language-file's content
     *
     * The language-file's content will be parsed and translations,
     * properties, etc. for holidays will be made available with the specified
     * locale.
     *
     * @param string $file   filename of the language file
     * @param string $locale locale-code of the translation
     *
     * @access   public
     * @return   boolean true on success, otherwise a PEAR_ErrorStack object
     * @throws   object PEAR_Errorstack
     */
    function addTranslationFile($file, $locale)
    {
        $result = parent::addTranslationFile($file, $locale);
        if (PEAR::isError($result)) {
            return $result;
        }
        $this->_translationFile   = $file;
        $this->_translationLocale = $locale;
        return $result;
    }

    /**
     * Add a compiled language-file's content
     *
     * The language-file's content will be unserialized and translations,
     * properties, etc. for holidays will be made available with the
     * specified locale.
     *
     * @param string $file   filename of the compiled language file
     * @param string $locale locale-code of the translation
     *
     * @access   public
     * @return   boolean true on success, otherwise a PEAR_ErrorStack object
     * @throws   object PEAR_Errorstack
     */
    function addCompiledTranslationFile($file, $locale)
    {
        $result = parent::addCompiledTranslationFile($file, $locale);
        if (PEAR::isError($result)) {
            return $result;
        }
        $this->_translationFile   = $file;
        $this->_translationLocale = $locale;
        return $result;
    }

    /**
     * clear all holidays
     *
     * @access   private
     * @return   void
     */
    function _clearHolidays()
    {
        $this->_holidays      = array();
        $this->_internalNames = array();
        $this->_dates         = array();
        $this->_titles        = array();
    }
}
