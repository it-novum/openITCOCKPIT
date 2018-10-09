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

namespace itnovum\openITCOCKPIT\Core\NodeJS;


use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\RequestOptions;

class ChartRenderClient {

    /**
     * @var Client
     */
    private $Client;

    /**
     * @var string
     */
    private $address = 'http://127.0.0.1:8084/';

    /**
     * @var string
     * Chart title
     * empty title '' will be hidden in chart
     */
    private $title = '';

    /**
     * @var int
     */
    private $width = 800;

    /**
     * @var int
     */
    private $height = 350;

    private $startTimestamp = 0;

    public function __construct() {
        $this->Client = new Client(['base_uri' => $this->address]);
    }

    /**
     * @param string $title
     */
    public function setTitle($title) {
        $this->title = $title;
    }

    /**
     * @param int $width
     */
    public function setWidth($width) {
        $this->width = $width;
    }

    /**
     * @param int $height
     */
    public function setHeight($height) {
        $this->height = $height;
    }

    /**
     * @param int $timestamp
     */
    public function setGraphStartTimestamp($timestamp) {
        $this->startTimestamp = $timestamp;
    }


    /**
     * @return int
     */
    public function getGraphStartAsISO() {
         if($this->startTimestamp === 0){
             $this->startTimestamp = time();
         }
         return date('c', $this->startTimestamp);
    }

    /**
     * @param array $data
     * @return string binary png image
     */
    public function getAreaChartAsPngStream($data) {
        $response = $this->Client->post('/AreaChart', [
            RequestOptions::JSON => [
                'data'     => $this->timestampToDate($data),
                'settings' => [
                    'width'  => $this->width,
                    'height' => $this->height,
                    'title'  => $this->title,
                    'graph_start' => $this->getGraphStartAsISO()
                ]
            ]
        ]);
        return $response->getBody()->getContents();
    }

    /**
     * @param $data
     * @return array
     */
    private function timestampToDate($data) {
        $result = [];
        foreach ($data as $index => $gauge) {
            $result[$index] = [
                'datasource' => $gauge['datasource'],
                'data'       => []
            ];
            foreach ($gauge['data'] as $timestamp => $value) {
                //ISO date for metric values
                $result[$index]['data'][date('c', $timestamp)] = $value;
            }
        }
        return $result;
    }

}
