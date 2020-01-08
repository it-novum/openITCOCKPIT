<?php
// Copyright (C) <2018>  <it-novum GmbH>
//
// This file is dual licensed
//
// 1.
//  This program is free software: you can redistribute it and/or modify
//  it under the terms of the GNU General Public License as published by
//  the Free Software Foundation, version 3 of the License.
//
//  This program is distributed in the hope that it will be useful,
//  but WITHOUT ANY WARRANTY; without even the implied warranty of
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//  GNU General Public License for more details.
//
//  You should have received a copy of the GNU General Public License
//  along with this program.  If not, see <http://www.gnu.org/licenses/>.
//
// 2.
//  If you purchased an openITCOCKPIT Enterprise Edition you can use this file
//  under the terms of the openITCOCKPIT Enterprise Edition license agreement.
//  License agreement and license key will be shipped with the order
//  confirmation.

namespace itnovum\openITCOCKPIT\Core;


use Yasumi\Holiday;
use Yasumi\Yasumi;

class Holidays {

    /**
     * @param string $countryCode
     * @return array|Holiday[]
     * @throws \ReflectionException
     */
    public function getHolidays($countryCode = 'de') {
        $countryCode = strtoupper($countryCode);
        $localCode = $this->getLocalCodeByCountryCode($countryCode);

        $providers = Yasumi::getProviders();
        if (!isset($providers[$countryCode])) {
            throw new \RuntimeException(sprintf('No provider for country code "%s"', $countryCode));
        }

        $provider = Yasumi::create($providers[$countryCode], date('Y'));
        $holidays = $provider->getHolidays();

        $filteredHolidays = [];
        foreach ($holidays as $holiday) {
            /** @var $holiday Holiday */
            $holidayName = $holiday->getName(); //en_US
            if (isset($holiday->translations[$localCode])) { //exists de_DE ?
                if($holiday->translations[$localCode] !== $holidayName) { //No not translate english to english
                    $holidayName = $holiday->translations[$localCode] . ' / ' . $holidayName; //Local translation + english
                }
            }else{
                $shortLocalCode = explode('_', $localCode, 2);  //exists 'de' ? (remove _DE from de_DE) ?
                if (isset($holiday->translations[strtolower($shortLocalCode[0])])) {
                    if($holiday->translations[strtolower($shortLocalCode[0])] !== $holidayName) { //No not translate english to english
                        $holidayName = $holiday->translations[strtolower($shortLocalCode[0])] . ' / ' . $holidayName; //Local translation + english
                    }
                }
            }


            $filteredHolidays[date('Y-m-d', $holiday->getTimestamp())] = [
                'start'           => $holiday->format('Y-m-d'),
                'title'           => $holidayName,
                'default_holiday' => true,
                'className'       => 'bg-color-magenta'
            ];
        }

        ksort($filteredHolidays);
        return array_values($filteredHolidays);
    }


    /**
     * @return array countries as CountryCode => CountryName Array
     */
    public function getCountries() {
        return [
            'au' => __('Australia'),
            'at' => __('Austria'),
            'be' => __('Belgium'),
            'ba' => __('Bosnia and Herzegovina'),
            'cz' => __('Czech Republic'),
            'dk' => __('Denmark'),
            'ee' => __('Estonia'),
            'fi' => __('Finland'),
            'fr' => __('France'),
            'de' => __('Germany'),
            'gr' => __('Greece'),
            'hu' => __('Hungary'),
            'ie' => __('Ireland'),
            'it' => __('Italy'),
            'jp' => __('Japan'),
            'lv' => __('Latvia'),
            'lt' => __('Lithuania'),
            'nl' => __('Netherlands'),
            'nz' => __('New Zealand'),
            'no' => __('Norway'),
            'pl' => __('Poland'),
            'pt' => __('Portugal'),
            'ro' => __('Romania'),
            'ru' => __('Russian Federation'),
            'sk' => __('Slovakia'),
            'za' => __('South Africa'),
            'es' => __('Spain'),
            'se' => __('Sweden'),
            'ch' => __('Switzerland'),
            'ua' => __('Ukraine'),
            'gb' => __('United Kingdom'),
            'us' => __('United States')
        ];
    }

    /**
     * @param string $countryCode
     * @return string
     */
    private function getLocalCodeByCountryCode($countryCode) {
        $locals = [
            'AU' => 'en_AU',
            'AT' => 'de_AT',
            'BE' => 'de_BE',
            'BA' => 'hr_BA',
            'BR' => 'pt_BR',
            'HR' => 'hr_HR',
            'CZ' => 'cs_CZ',
            'DK' => 'da_DK',
            'EE' => 'et_EE',
            'FI' => 'fi_FI',
            'FR' => 'fr_FR',
            'DE' => 'de_DE',
            'GR' => 'el_GR',
            'HU' => 'hu_HU',
            'IE' => 'ga_IE',
            'IT' => 'it_IT',
            'JP' => 'ja_JP',
            'LV' => 'lv_LV',
            'LT' => 'lt_LT',
            'NL' => 'nl_NL',
            'NZ' => 'en_NZ',
            'NO' => 'no_NO',
            'PL' => 'pl_PL',
            'PT' => 'pt_PT',
            'RO' => 'ro_RO',
            'RU' => 'ru_RU',
            'SK' => 'sk_SK',
            'ZA' => 'zu_ZA',
            'ES' => 'es_ES',
            'SE' => 'sv_SE',
            'CH' => 'de_CH',
            'UA' => 'uk_UA',
            'GB' => 'en_GB',
            'US' => 'en_US'
        ];

        if (!isset($locals[$countryCode])) {
            return 'en_US';
        }
        return $locals[$countryCode];
    }
}
