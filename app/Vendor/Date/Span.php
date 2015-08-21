<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4 foldmethod=marker: */

// {{{ Header

/**
 * Generic time span handling class for PEAR
 *
 * PHP versions 4 and 5
 *
 * LICENSE:
 *
 * Copyright (c) 1997-2005 Leandro Lucarella, Pierre-Alain Joye
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted under the terms of the BSD License.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS
 * FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
 * COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT,
 * INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING,
 * BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
 * CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
 * LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN
 * ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 *
 * @category   Date and Time
 * @package    Date
 * @author     Leandro Lucarella <llucax@php.net>
 * @author     Pierre-Alain Joye <pajoye@php.net>
 * @copyright  1997-2006 Leandro Lucarella, Pierre-Alain Joye
 * @license    http://www.opensource.org/licenses/bsd-license.php
 *             BSD License
 * @version    CVS: $Id$
 * @link       http://pear.php.net/package/Date
 * @since      File available since Release 1.4
 */

// }}}
// {{{ Includes

/**
 * Get the Date class
 */
require_once 'Date.php';

/**
 * Get the Date_Calc class
 */
require_once 'Calc.php';

// }}}
// {{{ Constants

/**
 * Non Numeric Separated Values (NNSV) Input Format
 *
 * Input format guessed from something like this:
 *
 *  <b>days</b><sep><b>hours</b><sep><b>minutes</b><sep><b>seconds</b>
 *
 * Where '<sep>' is any quantity of non numeric chars. If no values are
 * given, time span is set to zero, if one value is given, it is used for
 * hours, if two values are given it's used for hours and minutes and if
 * three values are given, it is used for hours, minutes and seconds.
 *
 * Examples:
 *
 *  - <b>""</b>                   -> 0, 0, 0, 0 (days, hours, minutes, seconds)
 *  - <b>"12"</b>                 -> 0, 12, 0, 0
 *  - <b>"12.30"</b>              -> 0, 12, 30, 0
 *  - <b>"12:30:18"</b>           -> 0, 12, 30, 18
 *  - <b>"3-12-30-18"</b>         -> 3, 12, 30, 18
 *  - <b>"3 days, 12-30-18"</b>   -> 3, 12, 30, 18
 *  - <b>"12:30 with 18 secs"</b> -> 0, 12, 30, 18
 *
 * @see      Date_Span::setFromString()
 */
define('DATE_SPAN_INPUT_FORMAT_NNSV', 1);

// }}}
// {{{ Global Variables

/**
 * Default time format when converting to a string
 *
 * @global string
 */
$GLOBALS['_DATE_SPAN_FORMAT'] = '%C';

/**
 * Default time format when converting from a string
 *
 * @global mixed
 */
$GLOBALS['_DATE_SPAN_INPUT_FORMAT'] = DATE_SPAN_INPUT_FORMAT_NNSV;

// }}}
// {{{ Class: Date_Span

/**
 * Generic time span handling class for PEAR
 *
 * @category  Date and Time
 * @package   Date
 * @author    Leandro Lucarella <llucax@php.net>
 * @author    Pierre-Alain Joye <pajoye@php.net>
 * @copyright 1997-2006 Leandro Lucarella, Pierre-Alain Joye
 * @license   http://www.opensource.org/licenses/bsd-license.php
 *            BSD License
 * @version   Release: 1.5.0a1
 * @link      http://pear.php.net/package/Date
 * @since     Class available since Release 1.4
 */
class Date_Span
{

    // {{{ Properties

    /**
     * The no of days
     *
     * @var      int
     * @access   private
     * @since    Property available since Release 1.0
     */
    var $day;

    /**
     * The no of hours (0 to 23)
     *
     * @var      int
     * @access   private
     * @since    Property available since Release 1.0
     */
    var $hour;

    /**
     * The no of minutes (0 to 59)
     *
     * @var      int
     * @access   private
     * @since    Property available since Release 1.0
     */
    var $minute;

    /**
     * The no of seconds (0 to 59)
     *
     * @var      int
     * @access   private
     * @since    Property available since Release 1.0
     */
    var $second;


    // }}}
    // {{{ Constructor

    /**
     * Constructor
     *
     * Creates the time span object calling {@link Date_Span::set()}
     *
     * @param mixed $time   time span expression
     * @param mixed $format format string to set it from a string or the
     *                       second date set it from a date diff
     *
     * @access   public
     * @see      set()
     */
    function Date_Span($time = 0, $format = null)
    {
        $this->set($time, $format);
    }


    // }}}
    // {{{ set()

    /**
     * Set the time span to a new value in a 'smart' way
     *
     * Sets the time span depending on the argument types, calling
     * to the appropriate setFromXxx() method.
     *
     * @param mixed $time   time span expression
     * @param mixed $format format string to set it from a string or the
     *                       second date set it from a date diff
     *
     * @return   bool       true on success
     * @access   public
     * @see      Date_Span::copy(), Date_Span::setFromArray(),
     *            Date_Span::setFromString(), Date_Span::setFromSeconds(),
     *            Date_Span::setFromDateDiff()
     */
    function set($time = 0, $format = null)
    {
        if (is_a($time, 'Date_Span')) {
            return $this->copy($time);
        } elseif (is_a($time, 'Date') and is_a($format, 'Date')) {
            return $this->setFromDateDiff($time, $format);
        } elseif (is_array($time)) {
            return $this->setFromArray($time);
        } elseif (is_string($time) || is_string($format)) {
            return $this->setFromString((string) $time, $format);
        } elseif (is_int($time)) {
            return $this->setFromSeconds($time);
        } else {
            return $this->setFromSeconds(0);
        }
    }


    // }}}
    // {{{ setFromArray()

    /**
     * Set the time span from an array
     *
     * Any value can be a float (but it has no sense in seconds), for example:
     *
     *  <code>$object->setFromArray(array(23.5, 20, 0));</code>
     *
     * is interpreted as 23 hours, 0.5 * 60 + 20 = 50 minutes and 0 seconds.
     *
     * @param array $time items are counted from right to left. First
     *                     item is for seconds, second for minutes, third
     *                     for hours and fourth for days. If there are
     *                     less items than 4, zero (0) is assumed for the
     *                     absent values.
     *
     * @return   bool       true on success
     * @access   public
     * @see      Date_Span::set()
     */
    function setFromArray($time)
    {
        if (!is_array($time)) {
            return false;
        }
        $tmp1 = new Date_Span;
        if (!$tmp1->setFromSeconds(@array_pop($time))) {
            return false;
        }
        $tmp2 = new Date_Span;
        if (!$tmp2->setFromMinutes(@array_pop($time))) {
            return false;
        }
        $tmp1->add($tmp2);
        if (!$tmp2->setFromHours(@array_pop($time))) {
            return false;
        }
        $tmp1->add($tmp2);
        if (!$tmp2->setFromDays(@array_pop($time))) {
            return false;
        }
        $tmp1->add($tmp2);
        return $this->copy($tmp1);
    }


    // }}}
    // {{{ setFromString()

    /**
     * Sets the time span from a string, based on an input format
     *
     * This is some like a mix of the PHP functions
     * {@link http://www.php.net/strftime strftime()} and
     * {@link http://www.php.net/sscanf sscanf()}.
     * The error checking and validation of this function is very primitive,
     * so you should be careful when using it with unknown strings.
     * With this method you are assigning day, hour, minute and second
     * values, and the last values are used. This means that if you use
     * something like:
     *
     *   <code>$object->setFromString('10, 20', '%H, %h');</code>
     *
     * your time span would be 20 hours long.  Always remember that this
     * method sets all the values, so if you had a span object 30
     * minutes long and you call:
     *
     *   <code>$object->setFromString('20 hours', '%H hours');</code>
     *
     * the span object would be 20 hours long (and not 20 hours and 30
     * minutes).
     *
     * Input format options:
     *
     *  - <b>%C</b> - Days with time, equivalent to '<b>D, %H:%M:%S</b>'
     *  - <b>%d</b> - Total days as a float number
     *                  (2 days, 12 hours = 2.5 days)
     *  - <b>%D</b> - Days as a decimal number
     *  - <b>%e</b> - Total hours as a float number
     *                  (1 day, 2 hours, 30 minutes = 26.5 hours)
     *  - <b>%f</b> - Total minutes as a float number
     *                  (2 minutes, 30 seconds = 2.5 minutes)
     *  - <b>%g</b> - Total seconds as a decimal number
     *                  (2 minutes, 30 seconds = 90 seconds)
     *  - <b>%h</b> - Hours as decimal number
     *  - <b>%H</b> - Hours as decimal number limited to 2 digits
     *  - <b>%m</b> - Minutes as a decimal number
     *  - <b>%M</b> - Minutes as a decimal number limited to 2 digits
     *  - <b>%n</b> - Newline character (\n)
     *  - <b>%p</b> - Either 'am' or 'pm' depending on the time. If 'pm'
     *                  is detected it adds 12 hours to the resulting time
     *                  span (without any checks). This is case
     *                  insensitive.
     *  - <b>%r</b> - Time in am/pm notation, equivalent to '<b>H:%M:%S %p</b>'
     *  - <b>%R</b> - Time in 24-hour notation, equivalent to '<b>H:%M</b>'
     *  - <b>%s</b> - Seconds as a decimal number
     *  - <b>%S</b> - Seconds as a decimal number limited to 2 digits
     *  - <b>%t</b> - Tab character (\t)
     *  - <b>%T</b> - Current time equivalent, equivalent to '<b>H:%M:%S</b>'
     *  - <b>%%</b> - Literal '%'
     *
     * @param string $time   string from where to get the time span
     *                        information
     * @param string $format format string
     *
     * @return   bool       true on success
     * @access   public
     * @see      Date_Span::set(), DATE_SPAN_INPUT_FORMAT_NNSV
     */
    function setFromString($time, $format = null)
    {
        if (is_null($format)) {
            $format = $GLOBALS['_DATE_SPAN_INPUT_FORMAT'];
        }
        // If format is a string, it parses the string format.
        if (is_string($format)) {
            $str  = '';
            $vars = array();
            $pm   = 'am';
            $day  = $hour = $minute = $second = 0;
            for ($i = 0; $i < strlen($format); $i++) {
                $char = $format{$i};
                if ($char == '%') {
                    $nextchar = $format{++$i};
                    switch ($nextchar) {
                    case 'c':
                        $str .= '%d, %d:%d:%d';
                        array_push($vars,
                                   'day',
                                   'hour',
                                   'minute',
                                   'second');
                        break;
                    case 'C':
                        $str .= '%d, %2d:%2d:%2d';
                        array_push($vars,
                                   'day',
                                   'hour',
                                   'minute',
                                   'second');
                        break;
                    case 'd':
                        $str .= '%f';
                        array_push($vars, 'day');
                        break;
                    case 'D':
                        $str .= '%d';
                        array_push($vars, 'day');
                        break;
                    case 'e':
                        $str .= '%f';
                        array_push($vars, 'hour');
                        break;
                    case 'f':
                        $str .= '%f';
                        array_push($vars, 'minute');
                        break;
                    case 'g':
                        $str .= '%f';
                        array_push($vars, 'second');
                        break;
                    case 'h':
                        $str .= '%d';
                        array_push($vars, 'hour');
                        break;
                    case 'H':
                        $str .= '%2d';
                        array_push($vars, 'hour');
                        break;
                    case 'm':
                        $str .= '%d';
                        array_push($vars, 'minute');
                        break;
                    case 'M':
                        $str .= '%2d';
                        array_push($vars, 'minute');
                        break;
                    case 'n':
                        $str .= "\n";
                        break;
                    case 'p':
                        $str .= '%2s';
                        array_push($vars, 'pm');
                        break;
                    case 'r':
                        $str .= '%2d:%2d:%2d %2s';
                        array_push($vars,
                                   'hour',
                                   'minute',
                                   'second',
                                   'pm');
                        break;
                    case 'R':
                        $str .= '%2d:%2d';
                        array_push($vars, 'hour', 'minute');
                        break;
                    case 's':
                        $str .= '%d';
                        array_push($vars, 'second');
                        break;
                    case 'S':
                        $str .= '%2d';
                        array_push($vars, 'second');
                        break;
                    case 't':
                        $str .= "\t";
                        break;
                    case 'T':
                        $str .= '%2d:%2d:%2d';
                        array_push($vars, 'hour', 'minute', 'second');
                        break;
                    case '%':
                        $str .= "%";
                        break;
                    default:
                        $str .= $char . $nextchar;
                    }
                } else {
                    $str .= $char;
                }
            }
            $vals = sscanf($time, $str);
            foreach ($vals as $i => $val) {
                if (is_null($val)) {
                    return false;
                }
                $$vars[$i] = $val;
            }
            if (strcasecmp($pm, 'pm') == 0) {
                $hour += 12;
            } elseif (strcasecmp($pm, 'am') != 0) {
                return false;
            }
            $this->setFromArray(array($day, $hour, $minute, $second));
        } elseif (is_integer($format)) {
            // If format is a integer, it uses a predefined format
            // detection method.
            switch ($format) {
            case DATE_SPAN_INPUT_FORMAT_NNSV:
                $time = preg_split('/\D+/', $time);
                switch (count($time)) {
                case 0:
                    return $this->setFromArray(array(0,
                                                     0,
                                                     0,
                                                     0));
                case 1:
                    return $this->setFromArray(array(0,
                                                     $time[0],
                                                     0,
                                                     0));
                case 2:
                    return $this->setFromArray(array(0,
                                                     $time[0],
                                                     $time[1],
                                                     0));
                case 3:
                    return $this->setFromArray(array(0,
                                                     $time[0],
                                                     $time[1],
                                                     $time[2]));
                default:
                    return $this->setFromArray($time);
                }
                break;
            }
        }
        return false;
    }


    // }}}
    // {{{ setFromSeconds()

    /**
     * Set the time span from a total number of seconds
     *
     * @param int $seconds total number of seconds
     *
     * @return   bool       true on success
     * @access   public
     * @see      Date_Span::set(), Date_Span::setFromDays(),
     *            Date_Span::setFromHours(), Date_Span::setFromMinutes()
     */
    function setFromSeconds($seconds)
    {
        if ($seconds < 0) {
            return false;
        }
        $sec  = intval($seconds);
        $min  = floor($sec / 60);
        $hour = floor($min / 60);
        $day  = intval(floor($hour / 24));

        $this->second = $sec % 60;
        $this->minute = $min % 60;
        $this->hour   = $hour % 24;
        $this->day    = $day;
        return true;
    }


    // }}}
    // {{{ setFromMinutes()

    /**
     * Sets the time span from a total number of minutes
     *
     * @param float $minutes total number of minutes
     *
     * @return   bool       true on success
     * @access   public
     * @see      Date_Span::set(), Date_Span::setFromDays(),
     *            Date_Span::setFromHours(), Date_Span::setFromSeconds()
     */
    function setFromMinutes($minutes)
    {
        return $this->setFromSeconds(round($minutes * 60));
    }


    // }}}
    // {{{ setFromHours()

    /**
     * Sets the time span from a total number of hours
     *
     * @param float $hours total number of hours
     *
     * @return   bool       true on success
     * @access   public
     * @see      Date_Span::set(), Date_Span::setFromDays(),
     *            Date_Span::setFromHours(), Date_Span::setFromMinutes()
     */
    function setFromHours($hours)
    {
        return $this->setFromSeconds(round($hours * 3600));
    }


    // }}}
    // {{{ setFromDays()

    /**
     * Sets the time span from a total number of days
     *
     * @param float $days total number of days
     *
     * @return   bool       true on success
     * @access   public
     * @see      Date_Span::set(), Date_Span::setFromHours(),
     *            Date_Span::setFromMinutes(), Date_Span::setFromSeconds()
     */
    function setFromDays($days)
    {
        return $this->setFromSeconds(round($days * 86400));
    }


    // }}}
    // {{{ setFromDateDiff()

    /**
     * Sets the span from the elapsed time between two dates
     *
     * The time span is unsigned, so the date's order is not important.
     *
     * @param object $date1 first Date
     * @param object $date2 second Date
     *
     * @return   bool       true on success
     * @access   public
     * @see      Date_Span::set()
     */
    function setFromDateDiff($date1, $date2)
    {
        if (!is_a($date1, 'date') or !is_a($date2, 'date')) {
            return false;
        }

        // create a local copy of instance, in order avoid changes the object
        // reference when its object has converted to UTC due PHP5 is always
        // passed the object by reference.
        $tdate1 = new Date($date1);
        $tdate2 = new Date($date2);

        // convert to UTC
        $tdate1->toUTC();
        $tdate2->toUTC();

        if ($tdate1->after($tdate2)) {
            list($tdate1, $tdate2) = array($tdate2, $tdate1);
        }

        $days = Date_Calc::dateDiff($tdate1->getDay(),
                                    $tdate1->getMonth(),
                                    $tdate1->getYear(),
                                    $tdate2->getDay(),
                                    $tdate2->getMonth(),
                                    $tdate2->getYear());

        $hours = $tdate2->getHour() - $tdate1->getHour();
        $mins  = $tdate2->getMinute() - $tdate1->getMinute();
        $secs  = $tdate2->getSecond() - $tdate1->getSecond();

        $this->setFromSeconds($days * 86400 +
                              $hours * 3600 +
                              $mins * 60 + $secs);
        return true;
    }

    // }}}
    // {{{ copy()

    /**
     * Sets the time span from another time object
     *
     * @param object $time source time span object
     *
     * @return   bool       true on success
     * @access   public
     */
    function copy($time)
    {
        if (is_a($time, 'date_span')) {
            $this->second = $time->second;
            $this->minute = $time->minute;
            $this->hour   = $time->hour;
            $this->day    = $time->day;
            return true;
        } else {
            return false;
        }
    }


    // }}}
    // {{{ format()

    /**
     * Formats time span according to specified code (similar to
     * {@link Date::formatLikeStrftime()})
     *
     * Uses a code based on {@link http://www.php.net/strftime strftime()}.
     *
     * Formatting options:
     *
     *  - <b>%C</b> - Days with time, equivalent to '<b>%D, %H:%M:%S</b>'
     *  - <b>%d</b> - Total days as a float number
     *                  (2 days, 12 hours = 2.5 days)
     *  - <b>%D</b> - Days as a decimal number
     *  - <b>%e</b> - Total hours as a float number
     *                  (1 day, 2 hours, 30 minutes = 26.5 hours)
     *  - <b>%E</b> - Total hours as a decimal number
     *                  (1 day, 2 hours, 40 minutes = 26 hours)
     *  - <b>%f</b> - Total minutes as a float number
     *                  (2 minutes, 30 seconds = 2.5 minutes)
     *  - <b>%F</b> - Total minutes as a decimal number
     *                  (1 hour, 2 minutes, 40 seconds = 62 minutes)
     *  - <b>%g</b> - Total seconds as a decimal number
     *                  (2 minutes, 30 seconds = 90 seconds)
     *  - <b>%h</b> - Hours as decimal number (0 to 23)
     *  - <b>%H</b> - Hours as decimal number (00 to 23)
     *  - <b>%i</b> - Hours as decimal number on 12-hour clock
     *                  (1 to 12)
     *  - <b>%I</b> - Hours as decimal number on 12-hour clock
     *                  (01 to 12)
     *  - <b>%m</b> - Minutes as a decimal number (0 to 59)
     *  - <b>%M</b> - Minutes as a decimal number (00 to 59)
     *  - <b>%n</b> - Newline character (\n)
     *  - <b>%p</b> - Either 'am' or 'pm' depending on the time
     *  - <b>%P</b> - Either 'AM' or 'PM' depending on the time
     *  - <b>%r</b> - Time in am/pm notation, equivalent to '<b>%I:%M:%S %p</b>'
     *  - <b>%R</b> - Time in 24-hour notation, equivalent to '<b>%H:%M</b>'
     *  - <b>%s</b> - Seconds as a decimal number (0 to 59)
     *  - <b>%S</b> - Seconds as a decimal number (00 to 59)
     *  - <b>%t</b> - Tab character (\t)
     *  - <b>%T</b> - Current time equivalent, equivalent to '<b>%H:%M:%S</b>'
     *  - <b>%%</b> - Literal '%'
     *
     * @param string $format the format string for returned time span
     *
     * @return   string     the time span in specified format
     * @access   public
     */
    function format($format = null)
    {
        if (is_null($format)) {
            $format = $GLOBALS['_DATE_SPAN_FORMAT'];
        }
        $output = '';
        for ($i = 0; $i < strlen($format); $i++) {
            $char = $format{$i};
            if ($char == '%') {
                $nextchar = $format{++$i};
                switch ($nextchar) {
                case 'C':
                    $output .= sprintf('%d, %02d:%02d:%02d',
                                      $this->day,
                                      $this->hour,
                                      $this->minute,
                                      $this->second);
                    break;
                case 'd':
                    $output .= $this->toDays();
                    break;
                case 'D':
                    $output .= $this->day;
                    break;
                case 'e':
                    $output .= $this->toHours();
                    break;
                case 'E':
                    $output .= floor($this->toHours());
                    break;
                case 'f':
                    $output .= $this->toMinutes();
                    break;
                case 'F':
                    $output .= floor($this->toMinutes());
                    break;
                case 'g':
                    $output .= $this->toSeconds();
                    break;
                case 'h':
                    $output .= $this->hour;
                    break;
                case 'H':
                    $output .= sprintf('%02d', $this->hour);
                    break;
                case 'i':
                case 'I':
                    $hour    = $this->hour + 1 > 12 ?
                               $this->hour - 12 :
                               $this->hour;
                    $output .= $hour == 0 ?
                               12 :
                               ($nextchar == "i" ?
                                $hour :
                                sprintf('%02d', $hour));
                    break;
                case 'm':
                    $output .= $this->minute;
                    break;
                case 'M':
                    $output .= sprintf('%02d', $this->minute);
                    break;
                case 'n':
                    $output .= "\n";
                    break;
                case 'p':
                    $output .= $this->hour >= 12 ? 'pm' : 'am';
                    break;
                case 'P':
                    $output .= $this->hour >= 12 ? 'PM' : 'AM';
                    break;
                case 'r':
                    $hour    = $this->hour + 1 > 12 ?
                               $this->hour - 12 :
                               $this->hour;
                    $output .= sprintf('%02d:%02d:%02d %s',
                                       $hour == 0 ?  12 : $hour,
                                       $this->minute,
                                       $this->second,
                                       $this->hour >= 12 ? 'pm' : 'am');
                    break;
                case 'R':
                    $output .= sprintf('%02d:%02d',
                                       $this->hour,
                                       $this->minute);
                    break;
                case 's':
                    $output .= $this->second;
                    break;
                case 'S':
                    $output .= sprintf('%02d', $this->second);
                    break;
                case 't':
                    $output .= "\t";
                    break;
                case 'T':
                    $output .= sprintf('%02d:%02d:%02d',
                                       $this->hour,
                                       $this->minute,
                                       $this->second);
                    break;
                case '%':
                    $output .= "%";
                    break;
                default:
                    $output .= $char . $nextchar;
                }
            } else {
                $output .= $char;
            }
        }
        return $output;
    }


    // }}}
    // {{{ toSeconds()

    /**
     * Converts time span to seconds
     *
     * @return   int        time span as an integer number of seconds
     * @access   public
     * @see      Date_Span::toDays(), Date_Span::toHours(),
     *            Date_Span::toMinutes()
     */
    function toSeconds()
    {
        return $this->day * 86400 + $this->hour * 3600 +
            $this->minute * 60 + $this->second;
    }


    // }}}
    // {{{ toMinutes()

    /**
     * Converts time span to minutes
     *
     * @return   float      time span as a decimal number of minutes
     * @access   public
     * @see      Date_Span::toDays(), Date_Span::toHours(),
     *            Date_Span::toSeconds()
     */
    function toMinutes()
    {
        return $this->day * 1440 + $this->hour * 60 + $this->minute +
            $this->second / 60;
    }


    // }}}
    // {{{ toHours()

    /**
     * Converts time span to hours
     *
     * @return   float      time span as a decimal number of hours
     * @access   public
     * @see      Date_Span::toDays(), Date_Span::toMinutes(),
     *            Date_Span::toSeconds()
     */
    function toHours()
    {
        return $this->day * 24 + $this->hour + $this->minute / 60 +
            $this->second / 3600;
    }


    // }}}
    // {{{ toDays()

    /**
     * Converts time span to days
     *
     * @return   float      time span as a decimal number of days
     * @access   public
     * @see      Date_Span::toHours(), Date_Span::toMinutes(),
     *            Date_Span::toSeconds()
     */
    function toDays()
    {
        return $this->day + $this->hour / 24 + $this->minute / 1440 +
            $this->second / 86400;
    }


    // }}}
    // {{{ add()

    /**
     * Adds a time span
     *
     * @param object $time time span to add
     *
     * @return   void
     * @access   public
     * @see      Date_Span::subtract()
     */
    function add($time)
    {
        return $this->setFromSeconds($this->toSeconds() +
                                     $time->toSeconds());
    }


    // }}}
    // {{{ subtract()

    /**
     * Subtracts a time span
     *
     * If the time span to subtract is larger than the original, the result
     * is zero (there's no sense in negative time spans).
     *
     * @param object $time time span to subtract
     *
     * @return   void
     * @access   public
     * @see      Date_Span::add()
     */
    function subtract($time)
    {
        $sub = $this->toSeconds() - $time->toSeconds();
        if ($sub < 0) {
            $this->setFromSeconds(0);
        } else {
            $this->setFromSeconds($sub);
        }
    }


    // }}}
    // {{{ equal()

    /**
     * Tells if time span is equal to $time
     *
     * @param object $time time span to compare to
     *
     * @return   bool       true if the time spans are equal
     * @access   public
     * @see      Date_Span::greater(), Date_Span::greaterEqual()
     *            Date_Span::lower(), Date_Span::lowerEqual()
     */
    function equal($time)
    {
        return $this->toSeconds() == $time->toSeconds();
    }


    // }}}
    // {{{ greaterEqual()

    /**
     * Tells if this time span is greater or equal than $time
     *
     * @param object $time time span to compare to
     *
     * @return   bool       true if this time span is greater or equal than $time
     * @access   public
     * @see      Date_Span::greater(), Date_Span::lower(),
     *            Date_Span::lowerEqual(), Date_Span::equal()
     */
    function greaterEqual($time)
    {
        return $this->toSeconds() >= $time->toSeconds();
    }


    // }}}
    // {{{ lowerEqual()

    /**
     * Tells if this time span is lower or equal than $time
     *
     * @param object $time time span to compare to
     *
     * @return   bool       true if this time span is lower or equal than $time
     * @access   public
     * @see      Date_Span::lower(), Date_Span::greater(),
     *            Date_Span::greaterEqual(), Date_Span::equal()
     */
    function lowerEqual($time)
    {
        return $this->toSeconds() <= $time->toSeconds();
    }


    // }}}
    // {{{ greater()

    /**
     * Tells if this time span is greater than $time
     *
     * @param object $time time span to compare to
     *
     * @return   bool       true if this time span is greater than $time
     * @access   public
     * @see      Date_Span::greaterEqual(), Date_Span::lower(),
     *            Date_Span::lowerEqual(), Date_Span::equal()
     */
    function greater($time)
    {
        return $this->toSeconds() > $time->toSeconds();
    }


    // }}}
    // {{{ lower()

    /**
     * Tells if this time span is lower than $time
     *
     * @param object $time time span to compare to
     *
     * @return   bool       true if this time span is lower than $time
     * @access   public
     * @see      Date_Span::lowerEqual(), Date_Span::greater(),
     *            Date_Span::greaterEqual(), Date_Span::equal()
     */
    function lower($time)
    {
        return $this->toSeconds() < $time->toSeconds();
    }


    // }}}
    // {{{ compare()

    /**
     * Compares two time spans
     *
     * Suitable for use in sorting functions.
     *
     * @param object $time1 the first time span
     * @param object $time2 the second time span
     *
     * @return   int        0 if the time spans are equal, -1 if time1 is lower
     *                       than time2, 1 if time1 is greater than time2
     * @access   public
     * @static
     */
    function compare($time1, $time2)
    {
        if ($time1->equal($time2)) {
            return 0;
        } elseif ($time1->lower($time2)) {
            return -1;
        } else {
            return 1;
        }
    }


    // }}}
    // {{{ isEmpty()

    /**
     * Tells if the time span is empty (zero length)
     *
     * @return   bool       true if empty
     * @access   public
     */
    function isEmpty()
    {
        return !$this->day && !$this->hour && !$this->minute && !$this->second;
    }


    // }}}
    // {{{ setDefaultInputFormat()

    /**
     * Sets the default input format
     *
     * @param mixed $format new default input format
     *
     * @return   mixed      previous default input format
     * @access   public
     * @static
     * @see      Date_Span::getDefaultInputFormat(), Date_Span::setDefaultFormat()
     */
    function setDefaultInputFormat($format)
    {
        $old = $GLOBALS['_DATE_SPAN_INPUT_FORMAT'];
        $GLOBALS['_DATE_SPAN_INPUT_FORMAT'] = $format;
        return $old;
    }


    // }}}
    // {{{ getDefaultInputFormat()

    /**
     * Returns the default input format
     *
     * @return   mixed      default input format
     * @access   public
     * @static
     * @see      Date_Span::setDefaultInputFormat(), Date_Span::getDefaultFormat()
     */
    function getDefaultInputFormat()
    {
        return $GLOBALS['_DATE_SPAN_INPUT_FORMAT'];
    }


    // }}}
    // {{{ setDefaultFormat()

    /**
     * Sets the default format
     *
     * @param mixed $format new default format
     *
     * @return   mixed      previous default format
     * @access   public
     * @static
     * @see      Date_Span::getDefaultFormat(), Date_Span::setDefaultInputFormat()
     */
    function setDefaultFormat($format)
    {
        $old = $GLOBALS['_DATE_SPAN_FORMAT'];
        $GLOBALS['_DATE_SPAN_FORMAT'] = $format;
        return $old;
    }


    // }}}
    // {{{ getDefaultFormat()

    /**
     * Returns the default format
     *
     * @return   mixed      default format
     * @access   public
     * @static
     * @see      Date_Span::setDefaultFormat(), Date_Span::getDefaultInputFormat()
     */
    function getDefaultFormat()
    {
        return $GLOBALS['_DATE_SPAN_FORMAT'];
    }


    // }}}

}

// }}}

/*
 * Local variables:
 * mode: php
 * tab-width: 4
 * c-basic-offset: 4
 * c-hanging-comment-ender-p: nil
 * End:
 */
