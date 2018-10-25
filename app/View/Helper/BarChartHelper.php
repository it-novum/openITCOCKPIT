<?php
// Copyright (C) <2015>  <it-novum GmbH>
//
// This file is dual licensed
//
// 1.
//	This program is free software: you can redistribute it and/or modify
//	it under the terms of the GNU General Public License as published by
//	the Free Software Foundation, version 3 of the License.
//
//	This program is distributed in the hope that it will be useful,
//	but WITHOUT ANY WARRANTY; without even the implied warranty of
//	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//	GNU General Public License for more details.
//
//	You should have received a copy of the GNU General Public License
//	along with this program.  If not, see <http://www.gnu.org/licenses/>.
//

// 2.
//	If you purchased an openITCOCKPIT Enterprise Edition you can use this file
//	under the terms of the openITCOCKPIT Enterprise Edition license agreement.
//	License agreement and license key will be shipped with the order
//	confirmation.


class BarChartHelper extends AppHelper {
    public function __construct(View $view, $settings = []) {
        parent::__construct($view, $settings);
    }

    public function createBarChart($chartData, $vertical = false) {

        $filepath = WWW_ROOT . 'img' . DS . 'charts';
        $filename = uniqid() . '.png';
        $tmp_dir = new Folder($filepath, true);

        $this->img = imagecreatetruecolor(260, 25);
        $this->width = 240;
        $this->height = 20;
        $this->vertical = $vertical;
        $this->chartDepth3d = 5;

        $this->x = 0;
        $this->y = 5;

        $this->setImageLayout(); //set transparence, colors, fonts, ...
        $this->create($chartData);
        imagepng($this->img, $filepath . DS . $filename, 0);
        imagedestroy($this->img);

        return $filename;
    }

    public function setImageLayout() {
        imagesavealpha($this->img, true);
        $transparent = imagecolorallocatealpha($this->img, 0, 0, 0, 127);
        imagefill($this->img, 0, 0, $transparent);
    }

    public function create($chartData) {
        $alpha = 5;
        $colors_host = [
            [92, 184, 92, $alpha], [217, 83, 79, $alpha], [183, 183, 183, $alpha],
        ];
        $colors_service = [
            [92, 184, 92, $alpha], [240, 173, 78, $alpha], [217, 83, 79, $alpha], [183, 183, 183, $alpha],
        ];
        $stateTotal = array_sum($chartData);
        $colors = (sizeof($chartData) === 3) ? $colors_host : $colors_service;
        $chartDataArray = [];
        $start = $this->x;
        $end = $this->x;
        foreach ($chartData as $state => $stateCount) {
            $stateDataPer['size'] = $stateCount / $stateTotal * 100;
            if ($stateDataPer['size'] === 0) {
                continue;
            }
            $start = $end;
            $end = $start + $this->width * $stateDataPer['size'] / 100;
            $diff = $this->width - $start;
            $chartDataArray[$state] = ['color' => $colors[$state], 'start' => $start, 'end' => $end];
        }
        if (!$this->vertical) {
            $this->bar3dHorizontal($chartDataArray);
            $this->barHorizontal($chartDataArray);
        }

    }


    function barHorizontal($chartDataArray) {
        foreach ($chartDataArray as $key => $dataArr) {
            $background = imagecolorallocatealpha($this->img, $dataArr['color'][0], $dataArr['color'][1], $dataArr['color'][2], $dataArr['color'][3]);
            imagefilledrectangle($this->img, $dataArr['start'], $this->y, $dataArr['end'], $this->y + $this->height, $background);
        }
    }

    function bar3dHorizontal($chartDataArray) {

        foreach ($chartDataArray as $key => $dataArr) {
            $background = imagecolorallocatealpha($this->img, $dataArr['color'][0] * 0.75, $dataArr['color'][1] * 0.75, $dataArr['color'][2] * 0.75, $dataArr['color'][3]);
            //top 3d
            imagefilledpolygon($this->img, [
                $dataArr['start'], $this->y,
                $dataArr['start'] + $this->chartDepth3d, $this->y - $this->chartDepth3d,
                $dataArr['end'] + $this->chartDepth3d, $this->y - $this->chartDepth3d,
                $dataArr['end'], $this->y,
            ], 4, $background);
            //left 3d
            imagefilledpolygon($this->img, [
                $dataArr['end'], $this->y + $this->height,
                $dataArr['end'], $this->y,
                $dataArr['end'] + $this->chartDepth3d, $this->y - $this->chartDepth3d,
                $dataArr['end'] + $this->chartDepth3d, $this->y + $this->height - $this->chartDepth3d,
            ], 4, $background);
        }
    }
}