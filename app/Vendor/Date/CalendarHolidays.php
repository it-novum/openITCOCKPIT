<?php

require_once "Holidays.php";

class CalendarHolidays{

	public function getHolidays($countryCode = 'de'){
		$holidays = [];
		$holidaysArray = Date_Holidays::factory(($countryCode=='at')?'Austria':'Germany', date("Y"), 'en_EN');
		foreach($holidaysArray->_holidays as $key => $value){
			if(is_array($value)){
				foreach($value as $subvalue){
					$holidays[date('Y-m-d', $key)] = __($holidaysArray->getHoliday($subvalue, 'en_EN')->_title);
				}
			}
		}
		ksort($holidays);
		return $holidays;
	}
}
