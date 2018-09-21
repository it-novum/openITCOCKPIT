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

namespace itnovum\openITCOCKPIT\Graphite;


use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Psr7\Request;

class GraphiteLoader {

    /**
     * @var GraphiteConfig
     */
    private $GraphiteConfig;

    /**
     * @var bool
     */
    private $hideNullValues = true;

    /**
     * @var string
     * Start time as string
     */
    private $from = '-12hours';

    /**
     * GraphiteLoader constructor.
     * @param GraphiteConfig $GraphiteConfig
     */
    public function __construct(GraphiteConfig $GraphiteConfig) {
        $this->GraphiteConfig = $GraphiteConfig;
    }

    /**
     * @return bool
     */
    public function isNullValueHidden() {
        return $this->hideNullValues;
    }

    /**
     * @param bool $hideNullValues
     */
    public function setHideNullValues($hideNullValues) {
        $this->hideNullValues = (bool)$hideNullValues;
    }


    /**
     * @param array $proxySettings
     * @param array $queryOptions
     * @return Client
     */
    private function getHttpClient($proxySettings = [], $queryOptions) {
        $options = [
            'verify' => $this->GraphiteConfig->isIgnoreSslCertificate()
        ];
        if ($this->GraphiteConfig->isUseProxy() && !(empty($proxySettings['ipaddress']) & empty($proxySettings['port']))) {
            $options['proxy'] = [
                'http'  => sprintf('%s:%s', $proxySettings['ipaddress'], $proxySettings['port']),
                'https' => sprintf('%s:%s', $proxySettings['ipaddress'], $proxySettings['port'])
            ];
        } else {
            $options['proxy'] = [
                'http'  => false,
                'https' => false
            ];
        }

        $options['query'] = $queryOptions;

        return new Client($options);
    }

    /**
     * @param GraphiteMetric $GraphiteMetric
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getSeriesRaw(GraphiteMetric $GraphiteMetric) {
        $options = $this->getBaseRequestOptions();
        $options['target'] = sprintf(
            '%s.%s',
            $this->GraphiteConfig->getGraphitePrefix(),
            $GraphiteMetric->getMetricPath()
        );
        return $this->normalizeData($this->sendRequest($options));
    }

    /**
     * @param GraphiteMetric $GraphiteMetric
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getSeriesAvg(GraphiteMetric $GraphiteMetric) {
        $options = $this->getBaseRequestOptions();
        $options['target'] = sprintf(
            'averageSeries(%s.%s)',
            $this->GraphiteConfig->getGraphitePrefix(),
            $GraphiteMetric->getMetricPath()
        );
        return $this->normalizeData($this->sendRequest($options));
    }

    /**
     * @param GraphiteMetric $GraphiteMetric
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getSeriesMin(GraphiteMetric $GraphiteMetric) {
        $options = $this->getBaseRequestOptions();
        $options['target'] = sprintf(
            'minSeries(%s.%s)',
            $this->GraphiteConfig->getGraphitePrefix(),
            $GraphiteMetric->getMetricPath()
        );
        return $this->normalizeData($this->sendRequest($options));
    }

    /**
     * @param GraphiteMetric $GraphiteMetric
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getSeriesMax(GraphiteMetric $GraphiteMetric) {
        $options = $this->getBaseRequestOptions();
        $options['target'] = sprintf(
            'maxSeries(%s.%s)',
            $this->GraphiteConfig->getGraphitePrefix(),
            $GraphiteMetric->getMetricPath()
        );
        return $this->normalizeData($this->sendRequest($options));
    }

    /**
     * @return array
     */
    private function getBaseRequestOptions() {
        $options = [
            'format' => 'json',
            'from'   => $this->from
        ];

        if ($this->hideNullValues) {
            $options['noNullPoints'] = 'true';
        }

        return $options;
    }

    /**
     * @param array $options
     * @return string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    private function sendRequest($options = []) {
        $client = $this->getHttpClient([], $options);
        $request = new Request('GET', $this->GraphiteConfig->getBaseUrl() . '/render');
        try {
            $response = $client->send($request);
        } catch (\Exception $e) {
            if ($e instanceof ClientException) {
                $response = $e->getResponse();
                $responseBody = $response->getBody()->getContents();
                return $responseBody;
            }
            return $e->getMessage();
        }

        if ($response->getStatusCode() == 200) {
            return \json_decode($response->getBody()->getContents(), true);
        }
    }

    /**
     * @param $data
     * @return array
     */
    private function normalizeData($data) {
        $normalizedData = [];

        foreach ($data as $metric) {
            foreach ($metric['datapoints'] as $datapoint) {
                if ($this->hideNullValues && $datapoint[0] === null) {
                    continue;
                }
                $normalizedData[$datapoint[1]] = $datapoint[0];
            }
        }
        return $normalizedData;
    }


}
