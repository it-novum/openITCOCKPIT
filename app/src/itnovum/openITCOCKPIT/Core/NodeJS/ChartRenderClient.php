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
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;
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

    /**
     * @var int
     */
    private $startTimestamp = 0;

    /**
     * @var int
     */
    private $endTimestamp = 0;

    public function __construct() {
        $this->Client = new Client([
            'base_uri' => $this->address,
            'proxy'    => [
                'http'  => false,
                'https' => false
            ]
        ]);
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
     * @param int $timestamp
     */
    public function setGraphEndTimestamp($timestamp) {
        $this->endTimestamp = $timestamp;
    }

    /**
     * @return int
     */
    public function getGraphStartAsISO() {
        if ($this->startTimestamp === 0) {
            $this->startTimestamp = time();
        }
        return date('c', $this->startTimestamp);
    }

    /**
     * @return int
     */
    public function getGraphEndAsISO() {
        if ($this->endTimestamp === 0) {
            $this->endTimestamp = time();
        }
        return date('c', $this->endTimestamp);
    }

    /**
     * @param array $data
     * @return string binary png image
     */
    public function getAreaChartAsPngStream($data) {
        try {
            $response = $this->Client->post('/AreaChart', [
                RequestOptions::JSON => [
                    'data'     => $this->timestampToDate($data),
                    'settings' => [
                        'width'       => $this->width,
                        'height'      => $this->height,
                        'title'       => $this->title,
                        'graph_start' => $this->getGraphStartAsISO(),
                        'graph_end'   => $this->getGraphEndAsISO()
                    ]
                ]
            ]);
            return $response->getBody()->getContents();
        } catch (ClientException $e) {
            $response = $e->getResponse();
            $ErrorImage = new ErrorImage($this->width, $this->height);
            $ErrorImage->setHeadline(sprintf(
                'Error: %s %s',
                $response->getStatusCode(),
                $response->getReasonPhrase()
            ));
            debug(strip_tags($response->getBody()->getContents()));
            $ErrorImage->setErrorText($response->getBody()->getContents());

            return $ErrorImage->getImageAsPngStream();
        } catch (ConnectException $e) {
            $ErrorImage = new ErrorImage($this->width, $this->height);
            $ErrorImage->setHeadline(sprintf(
                'Error: Could not connect'
            ));
            $ErrorImage->setErrorText($e->getMessage());
            return $ErrorImage->getImageAsPngStream();
        } catch (\Exception $e) {
            $ErrorImage = new ErrorImage($this->width, $this->height);
            $ErrorImage->setHeadline(sprintf(
                'Unknown error.'
            ));
            $ErrorImage->setErrorText($e->getMessage());
            return $ErrorImage->getImageAsPngStream();
        }
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
