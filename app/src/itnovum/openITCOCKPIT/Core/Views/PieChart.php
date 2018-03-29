<?php
// Copyright (C) <2015>  <it-novum GmbH>
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

namespace itnovum\openITCOCKPIT\Core\Views;


use Exception;

class PieChart {

    /**
     * @var resource
     */
    private $image;

    /**
     * @var int
     */
    private $width = 240;

    /**
     * @var int
     */
    private $height = 80;

    /**
     * @var float|int
     */
    private $x;

    /**
     * @var float|int
     */
    private $y;

    /**
     * @var float|int
     */
    private $offset_3d;

    /**
     * @var float
     * 90°    -> Radians 1.58
     */
    private $DEF_CHART_START;

    /**
     * @var float
     * 360°   -> Radians 6.38
     *
     */
    private $CHART_END;


    public function __construct() {
        $this->DEF_CHART_START = deg2rad(90); //90°    Radians 1.58
        $this->CHART_END = deg2rad(360);      //360°   Radians 6.38

        $this->x = $this->width / 2;
        $this->y = 82;
        $this->offset_3d = $this->y + 13;
    }

    /**
     * @param array $chart_data [0 => 10, 1 => 5, 2 => 15]
     */
    public function createPieChart($chart_data) {
        $this->image = imagecreatetruecolor(242, 160);
        $this->setImageLayout(); //set transparence, colors, fonts, ...
        $this->create($chart_data);
    }

    /**
     * @param array $chart_data [0 => 10, 1 => 5, 2 => 15]
     */
    public function createHalfPieChart($chart_data) {
        $this->image = imagecreatetruecolor(242, 140);
        $this->height = 160;

        $this->x = $this->width / 2;
        $this->y = 120;
        $this->offset_3d = $this->y + 7;

        $this->setImageLayout(); //set transparence, colors, fonts, ...
        $this->createHalf($chart_data);
    }

    /**
     * @return resource
     * @throws Exception
     */
    public function getImage() {
        if (!is_resource($this->image)) {
            throw new \Exception('Image not created yet');
        }
        return $this->image;
    }

    private function setImageLayout() {
        imagesavealpha($this->image, true);
        $transparent = imagecolorallocatealpha($this->image, 0, 0, 0, 127);
        imagefill($this->image, 0, 0, $transparent);
    }

    private function create($chart_data) {
        $startAngle = 0.0; //Start
        $endAngle = 0.0; //End
        $angleDiff = 0.0; //If angleDiff > 0 -> Rotate to 90°
        $temp_start = 0.0;
        $set_start = false;
        $alpha = 30;
        $colors_host = [
            [92, 184, 92, $alpha], [217, 83, 79, $alpha], [183, 183, 183, $alpha],
        ];
        $colors_service = [
            [92, 184, 92, $alpha], [240, 173, 78, $alpha], [217, 83, 79, $alpha], [183, 183, 183, $alpha],
        ];
        $state_total = array_sum($chart_data);


        //debug(array_sum($chart_data));
        $colors = (sizeof($chart_data) === 3) ? $colors_host : $colors_service;
        krsort($chart_data);

        $chart_data_array = [];
        foreach ($chart_data as $state => $state_count) {
            $state_data_per['size'] = $state_count / $state_total * 100;
            if ($state_data_per['size'] === 0) {
                $chart_data_array[$state] = ['color' => $colors[$state], 'startAngle' => $startAngle, 'endAngle' => $endAngle, 'show_percent_value' => $state_data_per['size']];
                continue;
            }
            $startAngle = $endAngle;
            $endAngle = $startAngle + deg2rad(360 * $state_data_per['size'] / 100);
            if ($startAngle < $this->DEF_CHART_START && !$set_start) {
                $set_start = true;
                $angleDiff = $this->DEF_CHART_START - $startAngle;
                $startAngle += $angleDiff;
                $endAngle += $angleDiff;
                $temp_start = $startAngle;
            }
            if ($endAngle > $this->CHART_END) {
                $endAngle = ($this->CHART_END - $endAngle) * (-1);
            }
            if ($startAngle === $endAngle)
                $endAngle -= 0.00000001;


            $chart_data_array[$state] = ['color' => $colors[$state], 'startAngle' => $startAngle, 'endAngle' => $endAngle, 'show_percent_value' => $state_data_per['size']];
        }

        $this->circle3d($chart_data_array);
        $this->circle($chart_data_array);
    }

    private function createHalf($chart_data) {
        $startAngle = 0.0; //Start
        $endAngle = 0.0; //End
        $angleDiff = 0.0; //If angleDiff > 0 -> Rotate to 90°
        $temp_start = 0.0;
        $set_start = false;
        $alpha = 30;
        $colors_host = [
            [92, 184, 92, $alpha], [217, 83, 79, $alpha], [183, 183, 183, $alpha],
        ];
        $colors_service = [
            [92, 184, 92, $alpha], [240, 173, 78, $alpha], [217, 83, 79, $alpha], [183, 183, 183, $alpha],
        ];
        $state_total = array_sum($chart_data);

        $colors = (sizeof($chart_data) === 3) ? $colors_host : $colors_service;
        krsort($chart_data);

        $chart_data_array = [];
        foreach ($chart_data as $state => $state_count) {
            $state_data_per['size'] = number_format($state_count / $state_total * 100, 4);
            if ($state_data_per['size'] === 0) {
                $chart_data_array[$state] = [
                    'color'              => $colors[$state],
                    'startAngle'         => $startAngle,
                    'endAngle'           => $endAngle,
                    'show_percent_value' => $state_data_per['size'],
                ];
                continue;
            }
            $startAngle = $endAngle;
            $degree = 180 * $state_data_per['size'] / 100;
            $radian = round($degree) * M_PI / 180;
            //$endAngle = $startAngle + deg2rad(180*$state_data_per['size']/100);
            $endAngle = $startAngle + $radian;

            if ($startAngle < 0 && !$set_start) {
                $set_start = true;
                $angleDiff = 0 - $startAngle;
                $startAngle += $angleDiff;
                $endAngle += $angleDiff;
                $temp_start = $startAngle;
            }
            if ($endAngle > 180) {
                $endAngle = (180 - $endAngle) * (-1);
            }
            if ($startAngle === $endAngle)
                $endAngle -= 0.00000001;
            if ($startAngle < 0 && $set_start) {
                $startAngle = 0.0;
            }

            $chart_data_array[$state] = [
                'color'              => $colors[$state],
                'startAngle'         => $startAngle,
                'endAngle'           => $endAngle,
                'show_percent_value' => $state_data_per['size'],
            ];
        }

        $this->circle3d($chart_data_array);
        $this->circle($chart_data_array);
    }


    private function circle($chart_data_array) {
        foreach ($chart_data_array as $key => $data_arr) {
            $background = imagecolorallocate($this->image, $data_arr['color'][0], $data_arr['color'][1], $data_arr['color'][2]);
            if ($data_arr['show_percent_value'] > 0) {
                imageSmoothArc($this->image, $this->x, $this->y, $this->width, $this->height, $data_arr['color'], $data_arr['startAngle'], $data_arr['endAngle']);
            }
        }
    }

    private function circle3d($chart_data_array) {
        for ($i = $this->offset_3d; $i > $this->y; $i--) {
            foreach ($chart_data_array as $key => $data_arr) {
                if ($data_arr['show_percent_value'] > 0) {
                    imageSmoothArc($this->image, $this->x, $i, $this->width, $this->height, [$data_arr['color'][0] * 0.75, $data_arr['color'][1] * 0.75, $data_arr['color'][2] * 0.75, 70], $data_arr['startAngle'], $data_arr['endAngle']);
                }
            }
        }
    }


}