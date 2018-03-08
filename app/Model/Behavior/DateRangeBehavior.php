<?php
App::uses('ModelBehavior', 'Model');

class DateRangeBehavior extends ModelBehavior
{

    function createDateRanges(&$Model, $date_start, $date_end, $time_ranges = [])
    {
        $date_start_timestamp = strtotime($date_start);
        $date_end_timestamp = strtotime($date_end);

        $time_slices = [];

        $first_monday_in_week = strtotime(date('d.m.Y H:i:s', $date_start_timestamp).' -'.(date('N', $date_start_timestamp) - 1).' days');
        //evaluation period in weeks
        $week_count = ceil(($date_end_timestamp - $first_monday_in_week) / (3600 * 24 * 7));
        $default_week_created = false;

        $time_ranges = Hash::combine($time_ranges, '{n}.id', '{n}', '{n}.day');//group by day
        for ($week = 0; $week <= $week_count; $week++) {
            $current_day_timestamp = strtotime(date('m.d.Y 00:00', $first_monday_in_week).'+  '.$week.' week');
            if (!$default_week_created) {
                for ($day = 0; $day < 7; $day++) {
                    //start is always 00:00
                    $current_day_timestamp = strtotime('+ '.$day.' day', $first_monday_in_week);
                    $day_of_week = date('N', $current_day_timestamp);
                    if (array_key_exists($day_of_week, $time_ranges)) {
                        foreach ($time_ranges[$day_of_week] as $day_in_time_range => $time_range) {
                            if ($time_range['end'] == '24:00') {
                                $time_range['end'] = '23:59:59';
                            }
                            $start = strtotime(date('d.m.Y ', $current_day_timestamp).' '.$time_range['start']);
                            $duration_in_seconds = (strtotime($time_range['end']) - strtotime($time_range['start']));
                            $time_slices_default[] = [
                                'start' => strtotime(date('d.m.Y ', $current_day_timestamp).' '.$time_range['start']),
                                'end'   => strtotime(date('d.m.Y ', $current_day_timestamp).' '.$time_range['start'].' +'.$duration_in_seconds.' seconds'),
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
                    'start' => strtotime(date('d.m.Y H:i:s', $time_slice['start']).' + '.$week.' week'),
                    'end'   => strtotime(date('d.m.Y H:i:s', $time_slice['end']).' + '.$week.' week'),
                ];
            }
        }

        return $this->removeUselessTimeslices(date('Ymd', $date_start_timestamp), date('Ymd', $date_end_timestamp), $time_slices);
    }

    function removeUselessTimeslices($date_start, $date_end, $time_slices)
    {
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

    public function mergeTimeOverlapping(&$Model, $timeslice_array)
    {
        $next_key = 0;
        for ($i = 0; $i <= sizeof($timeslice_array); $i++) {
            $next_key++;
            if (isset($timeslice_array[$next_key]) && isset($timeslice_array[$i])) {
                if ($this->dateIsBetween($timeslice_array[$i]['start_time'], $timeslice_array[$i]['end_time'], $timeslice_array[$next_key]['start_time'])
                    && $this->dateIsBetween($timeslice_array[$i]['start_time'], $timeslice_array[$i]['end_time'], $timeslice_array[$next_key]['end_time'])
                ) {
                    unset($timeslice_array[$next_key]);
                    $i--;
                } elseif ($this->dateIsBetween($timeslice_array[$i]['start_time'], $timeslice_array[$i]['end_time'], $timeslice_array[$next_key]['start_time'])
                    && !$this->dateIsBetween($timeslice_array[$i]['start_time'], $timeslice_array[$i]['end_time'], $timeslice_array[$next_key]['end_time'])
                ) {
                    $timeslice_array[$i] = ['start_time' => $timeslice_array[$i]['start_time'],
                                            'end_time'   => $timeslice_array[$next_key]['end_time']];
                    unset($timeslice_array[$next_key]);
                    $i--;
                } else {
                    $i = $next_key;
                }

            }
        }

        return $timeslice_array;
    }

    public function dateIsBetween($start_date, $end_date, $current_date)
    {
        return (($current_date >= $start_date) && ($current_date <= $end_date));
    }

    public function setDowntimesInTimeslices(&$Model, $time_slices, $downtimes)
    {
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
}
