<?php

require_once "Holidays.php";

class CalendarHolidays {

    public function getHolidays($countryCode = 'de') {
        $code = strtoupper($countryCode);
        $countryList = [
            'AU' => 'Australia',
            'AT' => 'Austria',
            'BR' => 'Brazil',
            'CL' => 'Chile',
            'HR' => 'Croatia',
            'CZ' => 'Czech',
            'DK' => 'Denmark',
            'GB' => 'England',
            'FI' => 'Finland',
            'FR' => 'France',
            'DE' => 'Germany',
            'IS' => 'Iceland',
            'IE' => 'Ireland',
            'IT' => 'Italy',
            'JP' => 'Japan',
            'NL' => 'Netherlands',
            'NO' => 'Norway',
            'PE' => 'Peru',
            'PT' => 'Portugal',
            'RO' => 'Romania',
            'RU' => 'Russia',
            'SM' => 'SanMarino',
            'RS' => 'Serbia',
            'SI' => 'Slovenia',
            'ES' => 'Spain',
            'SE' => 'Sweden',
            'TR' => 'Turkey',
            'UA' => 'Ukraine',
            'US' => 'USA',
            'VE' => 'Venezuela'
        ];
        $holidays = [];
        $holidaysArray = Date_Holidays::factory($countryList[$code], date("Y"), 'en_EN');
        if (is_array($holidaysArray->_holidays)) {
            foreach ($holidaysArray->_holidays as $key => $value) {
                if (is_array($value)) {
                    foreach ($value as $subvalue) {
                        $holidays[date('Y-m-d', $key)] = [
                            'start' => date('Y-m-d', $key),
                            'title' => __($holidaysArray->getHoliday($subvalue, 'en_EN')->_title)
                        ];
                    }
                }
            }
        }
        ksort($holidays);
        return array_values($holidays);
    }
}
