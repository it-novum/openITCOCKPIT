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


use itnovum\openITCOCKPIT\Core\Views\PieChart;
use Symfony\Component\Filesystem\Filesystem;


class PieChartHelper extends AppHelper {

    /**
     * @param $chart_data
     * @return string
     * @throws Exception
     */
    public function createPieChart($chart_data) {

        $PieChart = new PieChart();
        $PieChart->createPieChart($chart_data);


        if(!is_dir(WWW_ROOT . 'img' . DS . 'charts')){
            $fs = new Filesystem();
            $fs->mkdir(WWW_ROOT . 'img' . DS . 'charts');
        }

        $filepath = WWW_ROOT . 'img' . DS . 'charts';
        $filename = uniqid() . '.png';

        $image = $PieChart->getImage();
        imagepng($image, $filepath . DS . $filename, 0);
        imagedestroy($image);

        return $filename;
    }

    /**
     * @param $chart_data
     * @return string
     * @throws Exception
     */
    public function createHalfPieChart($chart_data) {

        $filepath = WWW_ROOT . 'img' . DS . 'charts';

        if(!is_dir(WWW_ROOT . 'img' . DS . 'charts')){
            $fs = new Filesystem();
            $fs->mkdir(WWW_ROOT . 'img' . DS . 'charts');
        }

        $filename = uniqid() . '.png';

        $PieChart = new PieChart();
        $PieChart->createHalfPieChart($chart_data);

        $image = $PieChart->getImage();
        imagepng($image, $filepath . DS . $filename, 0);
        imagedestroy($image);

        return $filename;
    }

}