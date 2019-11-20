<?php
App::uses('ModelBehavior', 'Model');

/**
 * Class DateRangeBehavior
 * @deprecated
 */
class DateRangeBehavior extends ModelBehavior {

    /**
     * @param $Model
     * @param $date_start string d.m.Y H:i:s
     * @param $date_end string d.m.Y H:i:s
     * @param array $time_ranges
     * @return array
     * @deprecated
     */
    public function createDateRanges(&$Model, $date_start, $date_end, $time_ranges = []) {
        $date_start_timestamp = strtotime($date_start);
        $date_end_timestamp = strtotime($date_end);
        $time_slices_default = [];

        $time_slices = [];

        $first_monday_in_week = strtotime(date('d.m.Y H:i:s', $date_start_timestamp) . ' -' . (date('N', $date_start_timestamp) - 1) . ' days');
        //evaluation period in weeks
        $week_count = ceil(($date_end_timestamp - $first_monday_in_week) / (3600 * 24 * 7));
        $default_week_created = false;

        $time_ranges = Hash::combine($time_ranges, '{n}.id', '{n}', '{n}.day');//group by day
        for ($week = 0; $week <= $week_count; $week++) {
            $current_day_timestamp = strtotime(date('m.d.Y 00:00', $first_monday_in_week) . '+  ' . $week . ' week');
            if (!$default_week_created) {
                for ($day = 0; $day < 7; $day++) {
                    //start is always 00:00
                    $current_day_timestamp = strtotime('+ ' . $day . ' day', $first_monday_in_week);
                    $day_of_week = date('N', $current_day_timestamp);
                    if (array_key_exists($day_of_week, $time_ranges)) {
                        foreach ($time_ranges[$day_of_week] as $day_in_time_range => $time_range) {
                            if ($time_range['end'] == '24:00') {
                                $time_range['end'] = '23:59:59';
                            }
                            $start = strtotime(date('d.m.Y ', $current_day_timestamp) . ' ' . $time_range['start']);
                            $duration_in_seconds = (strtotime($time_range['end']) - strtotime($time_range['start']));
                            $time_slices_default[] = [
                                'start' => strtotime(date('d.m.Y ', $current_day_timestamp) . ' ' . $time_range['start']),
                                'end'   => strtotime(date('d.m.Y ', $current_day_timestamp) . ' ' . $time_range['start'] . ' +' . $duration_in_seconds . ' seconds'),
                            ];
                        }
                    }
                }
                $default_week_created = true;
                $time_slices = $time_slices_default;
                continue;
            }
            foreach ($time_slices_default as $time_slice) {
                $time_slices[] = [
                    'start' => strtotime(date('d.m.Y H:i:s', $time_slice['start']) . ' + ' . $week . ' week'),
                    'end'   => strtotime(date('d.m.Y H:i:s', $time_slice['end']) . ' + ' . $week . ' week'),
                ];
            }
        }

        return $this->removeUselessTimeslices(date('Ymd', $date_start_timestamp), date('Ymd', $date_end_timestamp), $time_slices);
    }

    /**
     * @param $date_start string Ymd
     * @param $date_end string Ymd
     * @param $time_slices array
     * @return array
     * @deprecated
     */
    private function removeUselessTimeslices($date_start, $date_end, $time_slices) {
        $time_slices_new = [];
        foreach ($time_slices as $time_slice) {
            $current_time_slice_start = date('Ymd', $time_slice['start']);
            if ($current_time_slice_start < $date_start) {
                continue;
            }
            if ($current_time_slice_start > $date_end) {
                return $time_slices_new;
            }
            $time_slices_new[] = $time_slice;
        }

        return $time_slices_new;
    }

    /**
     * @param $Model
     * @param $timeslice_array
     * @return mixed
     * @deprecated
     */
    public function mergeTimeOverlapping(&$Model, $timeslice_array) {
        $next_key = 0;
        for ($i = 0; $i <= sizeof($timeslice_array); $i++) {
            $next_key++;
            if (isset($timeslice_array[$next_key]) && isset($timeslice_array[$i])) {
                if ($this->dateIsBetween($timeslice_array[$i]['start_time'], $timeslice_array[$i]['end_time'], $timeslice_array[$next_key]['start_time'])
                    && $this->dateIsBetween($timeslice_array[$i]['start_time'], $timeslice_array[$i]['end_time'], $timeslice_array[$next_key]['end_time'])
                ) {
                    unset($timeslice_array[$next_key]);
                    $i--;
                } else if ($this->dateIsBetween($timeslice_array[$i]['start_time'], $timeslice_array[$i]['end_time'], $timeslice_array[$next_key]['start_time'])
                    && !$this->dateIsBetween($timeslice_array[$i]['start_time'], $timeslice_array[$i]['end_time'], $timeslice_array[$next_key]['end_time'])
                ) {
                    $timeslice_array[$i] = [
                        'start_time' => $timeslice_array[$i]['start_time'],
                        'end_time'   => $timeslice_array[$next_key]['end_time']
                    ];
                    unset($timeslice_array[$next_key]);
                    $i--;
                }

            }
        }
        return $timeslice_array;
    }

    /**
     * @param $start_date
     * @param $end_date
     * @param $current_date
     * @return bool
     * @deprecated
     */
    private function dateIsBetween($start_date, $end_date, $current_date) {
        return (($current_date >= $start_date) && ($current_date <= $end_date));
    }

    /**
     * @param $Model
     * @param $time_slices
     * @param $downtimes
     * @return array
     * @deprecated
     */
    public function setDowntimesInTimeslices(&$Model, $time_slices, $downtimes) {
        $time_slices_new = [];
        $show_outages_in_dowtime = true;
        if (!empty($downtimes)) {
            foreach ($downtimes as $downtime) {
                $timeperiodTimeslices = $time_slices;
                foreach (array_keys($timeperiodTimeslices) as $i) {
                    if ($downtime['start_time'] > $time_slices[$i]['end'] || $downtime['end_time'] < $time_slices[$i]['start']) {
                        // when downtime does not intersect with time slice, next time slice
                        continue;
                    }
                    if ($downtime['start_time'] > $time_slices[$i]['start'] && $downtime['end_time'] < $time_slices[$i]['end']) {
                        $time_slices[] = ['start' => $downtime['end_time'], 'end' => $time_slices[$i]['end'], 'is_downtime' => false];
                        $time_slices[$i]['end'] = $downtime['start_time'];
                        if ($show_outages_in_dowtime) {
                            $time_slices[] = ['start' => $downtime['start_time'], 'end' => $downtime['end_time'], 'is_downtime' => true];
                        }
                        continue;
                    }
                    if ($downtime['start_time'] <= $time_slices[$i]['start'] && $downtime['end_time'] >= $time_slices[$i]['end']) {
                        // if downtime spans the time slice completely, delete time slice
                        if ($show_outages_in_dowtime) {
                            $time_slices[] = ['start' => $time_slices[$i]['start'], 'end' => $time_slices[$i]['end'], 'is_downtime' => true];
                        }
                        unset($time_slices[$i]);
                        continue;
                    }
                    if ($downtime['start_time'] <= $time_slices[$i]['start'] && $downtime['end_time'] > $time_slices[$i]['start'] && $downtime['end_time'] < $time_slices[$i]['end']) {
                        // if only the end of the downtime, move the start of the time slice
                        $tmp_start = $time_slices[$i]['start'];
                        $time_slices[$i]['start'] = $downtime['end_time'];
                        if ($show_outages_in_dowtime) {
                            $time_slices[] = ['start' => $tmp_start, 'end' => $downtime['end_time'], 'is_downtime' => true];
                        }
                        continue;
                    }
                    if ($downtime['end_time'] >= $time_slices[$i]['end'] && $downtime['start_time'] > $time_slices[$i]['start'] && $downtime['start_time'] < $time_slices[$i]['end']) {
                        // if only the start of the downtime, move the end of the time slice
                        $tmp_end = $time_slices[$i]['end'];
                        $time_slices[$i]['end'] = $downtime['start_time'];
                        if ($show_outages_in_dowtime) {
                            $time_slices[] = ['start' => $downtime['start_time'], 'end' => $tmp_end, 'is_downtime' => true];
                        }
                        continue;
                    }
                }
            }
        } else {
            $time_slices_new[] = $time_slices;
        }
        $time_slices = Hash::sort($time_slices, '{n}.start', 'ASC');

        return $time_slices;
    }

    /**
     * @param $lastUpdateDate
     * @param $interval
     * @return array date time slices
     * @throws Exception
     * @deprecated
     */
    public function createDateSlicesByIntervalAndLastUpdateDate(&$Model, $lastUpdateDate, $interval) {
        if(date('H:i:s', $lastUpdateDate) === '23:59:59'){
            $lastUpdateDate+=1;// plus on second for moving timestamp into new day
        }
        $now = time();
        $dateTimeSlices = [];
        $initialEntryType = 'update';
        $LastUpdateDate = new DateTime(date('Y-m-d H:i:s', $lastUpdateDate));
        $h = $LastUpdateDate->format('H');
        $m = $LastUpdateDate->format('i');
        $s = $LastUpdateDate->format('s');

        switch ($interval) {
            case 'DAY':
                if ($h == 0 && $m == 0 && $s == 0) {
                    $initialEntryType = 'new'; //midnight -> new day detected
                }
                if (date('zY', $lastUpdateDate) !== date('zY', $now)) {
                    $dateTimeSlices[] = [
                        'start'     => $lastUpdateDate,
                        'end'       => strtotime(date('d.m.Y', $lastUpdateDate) . ' 23:59:59'),
                        'entryType' => $initialEntryType
                    ];
                    $begin = new DateTime(date('d.m.Y', $lastUpdateDate));
                    $end = new DateTime(date('d.m.Y', $now));
                    $end = $end->modify('+1 day');

                    $dateInterval = new DateInterval('P1D');
                    $dateRange = new DatePeriod($begin, $dateInterval, $end);
                    foreach ($dateRange as $date) {
                        $dateAsTimestamp = $date->getTimestamp();
                        if (date('z', $dateAsTimestamp) < date('z', $now)) {
                            $dateTimeSlices[] = [
                                'start'     => strtotime(date('d.m.Y', $dateAsTimestamp) . ' 00:00:00'),
                                'end'       => strtotime(date('d.m.Y', $dateAsTimestamp) . ' 23:59:59'),
                                'entryType' => 'new'
                            ];
                        } else {
                            $dateTimeSlices[] = [
                                'start'     => strtotime(date('d.m.Y', $dateAsTimestamp) . ' 00:00:00'),
                                'end'       => $now,
                                'entryType' => 'new'
                            ];
                        }
                    }
                } else {
                    $dateTimeSlices[] = [
                        'start'     => $lastUpdateDate,
                        'end'       => $now,
                        'entryType' => $initialEntryType
                    ];
                }
                return $dateTimeSlices;
                break;
            case 'WEEK':
                $w = date('W', $lastUpdateDate); // ISO-8601 week number of year, weeks starting on Monday(=1)
                if ($h == 0 && $m == 0 && $s == 0 && $w == 1) {
                    $initialEntryType = 'new'; //midnight and monday -> new day detected
                }

                if (date('WY', $lastUpdateDate) !== date('WY', $now)) {
                    $begin = new DateTime(date('d.m.Y H:i:s', $lastUpdateDate));
                    $sunday = $begin->modify('sunday this week');
                    $dateTimeSlices[] = [
                        'start'     => $lastUpdateDate,
                        'end'       => strtotime($sunday->format('d.m.Y') . ' 23:59:59'),
                        'entryType' => $initialEntryType
                    ];
                    $nextWeekMonday = $begin->modify('monday next week 00:00:00');
                    $end = new DateTime(date('Y-m-d', $now));

                    $interval = new DateInterval('P1W');
                    $daterange = new DatePeriod($nextWeekMonday, $interval, $end);
                    foreach ($daterange as $date) {
                        $dateAsTimestamp = $date->getTimestamp();
                        if (date('W', $now) === date('W', $dateAsTimestamp)) {
                            $dateTimeSlices[] = [
                                'start'     => strtotime(date('d.m.Y', $dateAsTimestamp) . ' 00:00:00'),
                                'end'       => $now,
                                'entryType' => 'new'
                            ];
                        } else {
                            $dateTimeSlices[] = [
                                'start'     => strtotime(date('d.m.Y', $dateAsTimestamp) . ' 00:00:00'),
                                'end'       => strtotime(date('d.m.Y', $dateAsTimestamp) . ' sunday this week 23:59:59'),
                                'entryType' => 'new'
                            ];
                        }
                    }
                } else {
                    $dateTimeSlices[] = [
                        'start'     => $lastUpdateDate,
                        'end'       => $now,
                        'entryType' => $initialEntryType
                    ];
                }
                return $dateTimeSlices;
                break;
            case 'MONTH':
                $j = date('j',$lastUpdateDate); //Day of the month without leading zeros
                if ($h == 0 && $m == 0 && $s == 0 && $j == 1) {
                    $initialEntryType = 'new'; //midnight and first day of month -> new day detected
                }

                if (date('mY', $lastUpdateDate) !== date('mY', $now)) {
                    $begin = new DateTime(date('d.m.Y H:i:s', $lastUpdateDate));
                    $lastDayOfThisMonth = $begin->modify('last day of this month');
                    $dateTimeSlices[] = [
                        'start'     => $lastUpdateDate,
                        'end'       => strtotime($lastDayOfThisMonth->format('d.m.Y') . ' 23:59:59'),
                        'entryType' => $initialEntryType
                    ];
                    $nextMonthFirstDay = $begin->modify('first day of next month 00:00:00');
                    $end = new DateTime(date('Y-m-d', $now));

                    $interval = new DateInterval('P1M');
                    $daterange = new DatePeriod($nextMonthFirstDay, $interval, $end);
                    foreach ($daterange as $date) {
                        $dateAsTimestamp = $date->getTimestamp();
                        if (date('m', $now) === date('m', $dateAsTimestamp)) {
                            $dateTimeSlices[] = [
                                'start'     => strtotime(date('d.m.Y', $dateAsTimestamp) . ' 00:00:00'),
                                'end'       => $now,
                                'entryType' => 'new'
                            ];
                        } else {
                            $dateTimeSlices[] = [
                                'start'     => strtotime(date('d.m.Y', $dateAsTimestamp) . ' 00:00:00'),
                                'end'       => strtotime(date('d.m.Y', $dateAsTimestamp) . ' last day of this month 23:59:59'),
                                'entryType' => 'new'
                            ];
                        }
                    }
                } else {
                    $dateTimeSlices[] = [
                        'start'     => $lastUpdateDate,
                        'end'       => $now,
                        'entryType' => $initialEntryType
                    ];
                }
                return $dateTimeSlices;
                break;
            case 'QUARTER':
                $numberFromCurrentQuarter = $this->getNumberFromQuarter($now);
                $firstDayOfThisQuarter = $this->firstDayOfQuarter($LastUpdateDate, $numberFromCurrentQuarter);

                if ($firstDayOfThisQuarter->getTimestamp() === $lastUpdateDate) {
                    $initialEntryType = 'new';
                }

                if (date('Y', $lastUpdateDate) . $this->getNumberFromQuarter($lastUpdateDate) !== date('Y', $now) . $numberFromCurrentQuarter) {
                    $begin = new DateTime(date('d.m.Y H:i:s', $lastUpdateDate));
                    $lastUpdateQuarter = $this->getNumberFromQuarter($lastUpdateDate);
                    $lastDayOfThisQuarter = $this->lastDayOfQuarter($begin, $lastUpdateQuarter);
                    $dateTimeSlices[] = [
                        'start'     => $lastUpdateDate,
                        'end'       => strtotime($lastDayOfThisQuarter->format('d.m.Y') . ' 23:59:59'),
                        'entryType' => $initialEntryType
                    ];

                    $nextQuarterFirstDay = $begin->modify('tomorrow 00:00:00');
                    $end = new DateTime(date('Y-m-d', $now));
                    $interval = new DateInterval('P3M');
                    $daterange = new DatePeriod($nextQuarterFirstDay, $interval, $end);
                    foreach ($daterange as $date) {
                        $dateAsTimestamp = $date->getTimestamp();
                        $numberFromDateQuarter = $this->getNumberFromQuarter($dateAsTimestamp);
                        if (date('Y', $now) . $numberFromCurrentQuarter === date('Y', $dateAsTimestamp) . $this->getNumberFromQuarter($dateAsTimestamp)) {
                            $dateTimeSlices[] = [
                                'start'     => strtotime(date('d.m.Y', $dateAsTimestamp) . ' 00:00:00'),
                                'end'       => $now,
                                'entryType' => 'new'
                            ];
                        } else {
                            $end = $date;
                            $lastDayOfDateQuarter = $this->lastDayOfQuarter($end, $numberFromDateQuarter);
                            $dateTimeSlices[] = [
                                'start'     => strtotime(date('d.m.Y', $dateAsTimestamp) . ' 00:00:00'),
                                'end'       => strtotime(date('d.m.Y', $lastDayOfDateQuarter->getTimestamp()) . ' 23:59:59'),
                                'entryType' => 'new'
                            ];
                        }
                    }

                } else {
                    $dateTimeSlices[] = [
                        'start'     => $lastUpdateDate,
                        'end'       => $now,
                        'entryType' => $initialEntryType
                    ];
                }
                break;
            case 'YEAR':
                $j = date('j',$lastUpdateDate); //Day of the month without leading zeros
                $n = date('n',$lastUpdateDate); //Numeric representation of a month, without leading zeros
                if ($h == 0 && $m == 0 && $s == 0 && $j == 1 && $n == 1) {
                    $initialEntryType = 'new'; //midnight and first day of the year -> new day detected
                }
                if (date('Y', $lastUpdateDate) !== date('Y', $now)) {
                    $begin = new DateTime(date('d.m.Y H:i:s', $lastUpdateDate));
                    $lastDayOfThisYear = $begin->modify('last day of december this year');
                    $dateTimeSlices[] = [
                        'start'     => $lastUpdateDate,
                        'end'       => strtotime($lastDayOfThisYear->format('d.m.Y') . ' 23:59:59'),
                        'entryType' => $initialEntryType
                    ];
                    $nextMonthFirstDay = $begin->modify('first day of january next year 00:00:00');
                    $end = new DateTime(date('Y-m-d', $now));

                    $interval = new DateInterval('P1Y');
                    $daterange = new DatePeriod($nextMonthFirstDay, $interval, $end);
                    foreach ($daterange as $date) {
                        $dateAsTimestamp = $date->getTimestamp();
                        if (date('Y', $now) === date('Y', $dateAsTimestamp)) {
                            $dateTimeSlices[] = [
                                'start'     => strtotime(date('d.m.Y', $dateAsTimestamp) . ' 00:00:00'),
                                'end'       => $now,
                                'entryType' => 'new'
                            ];
                        } else {
                            $dateTimeSlices[] = [
                                'start'     => strtotime(date('d.m.Y', $dateAsTimestamp) . ' 00:00:00'),
                                'end'       => strtotime(date('d.m.Y', $dateAsTimestamp) . ' last day of december this year 23:59:59'),
                                'entryType' => 'new'
                            ];
                        }
                    }
                } else {
                    $dateTimeSlices[] = [
                        'start'     => $lastUpdateDate,
                        'end'       => $now,
                        'entryType' => $initialEntryType
                    ];
                }
                return $dateTimeSlices;
                break;

        }
        return $dateTimeSlices;
    }

    /**
     * @param $numberOfQuarter
     * @return Datetime $date
     * @deprecated
     */
    private function firstDayOfQuarter(Datetime $date, $numberOfQuarter) {
        switch ($numberOfQuarter) {
            case 1:
                $date->modify('first day of january 00:00:00');
                break;
            case 2:
                $date->modify('first day of april 00:00:00');
                break;
            case 3:
                $date->modify('first day of july 00:00:00');
                break;
            case 4:
                $date->modify('first day of october 00:00:00');
                break;
        }
        return $date;
    }

    /**
     * @param $numberOfQuarter
     * @return Datetime $date
     * @deprecated
     */
    private function lastDayOfQuarter(Datetime $date, $numberOfQuarter) {
        switch ($numberOfQuarter) {
            case 1:
                $date->modify('last day of march');
                break;
            case 2:
                $date->modify('last day of june');
                break;
            case 3:
                $date->modify('last day of september');
                break;
            case 4:
                $date->modify('last day of december');
                break;
        }
        return $date;
    }

    /**
     * @param $date
     * @return float
     * @deprecated
     */
    private function getNumberFromQuarter($date) {
        $currentMonth = date('m', $date);
        return ceil($currentMonth / 3);
    }
}
