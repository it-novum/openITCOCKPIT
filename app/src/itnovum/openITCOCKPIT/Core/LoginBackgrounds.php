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


class LoginBackgrounds {

    /**
     * @var array
     */
    private $images = [
        'default' => [
            [
                'iamge'  => 'spacex-71870.jpg',
                'credit' => 'Photo by SpaceX on Unsplash'
            ],
            [
                'iamge'  => 'nasa-53884.jpg',
                'credit' => 'Photo by NASA on Unsplash'
            ]
        ],
        'winter'  => [
            [
                'iamge'  => 'todd-diemer-67t2GJcD5PI-unsplash.jpg',
                'credit' => 'Photo by Todd Diemer on Unsplash'
            ],
            [
                'iamge'  => 'nasa-53884.jpg',
                'credit' => 'Photo by NASA on Unsplash'
            ]
        ],
    ];

    /**
     * @return array
     */
    public function getAllImages() {
        return $this->images;
    }

    public function getImages() {
        $season = $this->getSeason();

        if(!isset($this->images[$season])){
            return $this->images['default'];
        }

        return $this->images[$season];
    }

    /**
     * @return string
     */
    public function getSeason() {
        $season = 'winter';

        $month = date('n');
        if ($month >= 3) {
            $season = 'spring'; //March (3)
        }

        if ($month >= 6) {
            $season = 'summer'; //June (6)
        }

        if ($month >= 9) {
            $season = 'fall'; //September (9)
        }

        if ($month >= 12) {
            $season = 'winter'; //December (12)
        }

        $today = date('d.m');

        // Easter sunday
        $easter_sunday = easter_date(date('Y'));

        //Good Friday
        if ($today === strtotime('last friday', $easter_sunday)) {
            $season = 'easter';
        }

        //Easter Saturday
        if ($today === strtotime('last saturday', $easter_sunday)) {
            $season = 'easter';
        }

        //Easter Monday
        if ($today === strtotime('next monday', $easter_sunday)) {
            $season = 'easter';
        }


        //Valentine's Day - 14. Feb
        if ($today === '14.02') {
            $season = 'valentines_day';
        }

        //German Unity Day - 3. Oct
        if ($today === '03.10') {
            $season = 'germany';
        }

        // Sysadmin day - Last friday of July(7)
        if ($today === date('d.m', strtotime('last friday of july'))) {
            $season = 'sysadmin_day';
        }


        // Halloween - 31. Oct
        if ($today === '31.10') {
            $season = 'halloween';
        }

        // Christmas 24.12 - 26.12
        if ($today === '24.12' || $today === '25.12' || $today === '26.12') {
            $season = 'christmas';
        }

        return $season;

    }
}