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
    private  $images = [
        'default' => [
            [
                'iamge' => 'spacex-71870.jpg',
                'credit' => 'Photo by SpaceX on Unsplash'
            ],
            [
                'iamge' => 'nasa-53884.jpg',
                'credit' => 'Photo by NASA on Unsplash'
            ]
        ],
        'winter' => [
            [
                'iamge' => 'todd-diemer-67t2GJcD5PI-unsplash.jpg',
                'credit' => 'Photo by Todd Diemer on Unsplash'
            ],
            [
                'iamge' => 'nasa-53884.jpg',
                'credit' => 'Photo by NASA on Unsplash'
            ]
        ],
    ];

    /**
     * @return array
     */
    public function getAllImages(){
        return $this->images;
    }

    public function getImages(){
        $season = 'spring'; //ab März
        $season = 'summer'; //ab Juni
        $season = 'fall'; //ab September
        $season = 'winter'; //ab Dezember

        // Ostern

        //Valentinstag

        //Tag der deutshen einheit

        // Sysadmin day

        // Halloween 31. Oct

        // Christmas 24.12 - 26.12

    }
}