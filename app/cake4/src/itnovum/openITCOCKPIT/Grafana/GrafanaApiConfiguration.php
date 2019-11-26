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


namespace itnovum\openITCOCKPIT\Grafana;


class GrafanaApiConfiguration {
    /**
     * @var string
     */
    private $apiUrl;

    /**
     * @var string
     */
    private $apiKey;

    /**
     * @var string
     */
    private $graphitePrefix;

    /**
     * @var bool
     */
    private $useHttps;

    /**
     * @var bool
     */
    private $useProxy;

    /**
     * @var bool
     */
    private $ignoreSslCertificate;

    /**
     * @var array
     */
    private $includedHostgroups;

    /**
     * @var array
     */
    private $excludedHostgroups;

    /**
     * @var string
     */
    private $dashboardStyle = 'light';

    /**
     * @var null|string
     */
    private $hostUuid = null;

    /**
     * @var null|string
     */
    private $grafana_uid = null;

    /**
     * GrafanaConfiguration constructor.
     * @param $apiUrl
     * @param $apiKey
     * @param $graphitePrefix
     * @param $useHttps
     * @param $ignorSslCertificate
     * @param array $includedHostgroups
     * @param array $excludedHostgroups
     */
    public function __construct(
        $apiUrl,
        $apiKey,
        $graphitePrefix,
        $useHttps,
        $useProxy,
        $ignorSslCertificate,
        $includedHostgroups = [],
        $excludedHostgroups = [],
        $dashboardStyle = 'light'
    ) {
        $this->apiUrl = $apiUrl;
        $this->apiKey = $apiKey;
        $this->graphitePrefix = $graphitePrefix;
        $this->useHttps = (bool)$useHttps;
        $this->useProxy = (bool)$useProxy;
        $this->ignoreSslCertificate = (bool)$ignorSslCertificate;
        $this->includedHostgroups = $includedHostgroups;
        $this->excludedHostgroups = $excludedHostgroups;
        $this->dashboardStyle = $dashboardStyle;
    }

    /**
     * @param $configuration
     * @return Grafana Configuration
     */
    public static function fromArray($configuration) {
        $apiUrl = null;
        $apiKey = null;
        $graphitePrefix = null;
        $useHttps = true;
        $useProxy = true;
        $ignoreSslCertificate = false;
        $includedHostgroups = [];
        $excludedHostgroups = [];
        if (!empty($configuration['GrafanaConfiguration']['api_url'])) {
            $apiUrl = $configuration['GrafanaConfiguration']['api_url'];
        }
        if (!empty($configuration['GrafanaConfiguration']['api_key'])) {
            $apiKey = $configuration['GrafanaConfiguration']['api_key'];
        }
        if (!empty($configuration['GrafanaConfiguration']['graphite_prefix'])) {
            $graphitePrefix = $configuration['GrafanaConfiguration']['graphite_prefix'];
        }
        if (isset($configuration['GrafanaConfiguration']['use_https'])) {
            $useHttps = $configuration['GrafanaConfiguration']['use_https'];
        }
        if (isset($configuration['GrafanaConfiguration']['use_proxy'])) {
            $useProxy = $configuration['GrafanaConfiguration']['use_proxy'];
        }
        if (isset($configuration['GrafanaConfiguration']['ignore_ssl_certificate'])) {
            $ignoreSslCertificate = $configuration['GrafanaConfiguration']['ignore_ssl_certificate'];
        }
        if (!empty($configuration['GrafanaConfiguration']['dashboard_style'])) {
            $dashboardStyle = $configuration['GrafanaConfiguration']['dashboard_style'];
        }

        if (!empty($configuration['GrafanaConfigurationHostgroupMembership'])) {
            $includedHostgroups = \Hash::combine(
                $configuration['GrafanaConfigurationHostgroupMembership'],
                '{n}[excluded=0].hostgroup_id',
                '{n}[excluded=0].hostgroup_id'
            );
            $excludedHostgroups = \Hash::combine(
                $configuration['GrafanaConfigurationHostgroupMembership'],
                '{n}[excluded=1].hostgroup_id',
                '{n}[excluded=1].hostgroup_id'
            );
        }
        return new self($apiUrl, $apiKey, $graphitePrefix, $useHttps, $useProxy, $ignoreSslCertificate, $includedHostgroups, $excludedHostgroups, $dashboardStyle);
    }

    /**
     * @return string
     */
    public function getApiKey() {
        return $this->apiKey;
    }

    /**
     * @return string
     */
    public function getGraphitePrefix() {
        return $this->graphitePrefix;
    }

    /**
     * @return bool
     */
    public function isUseHttps() {
        return $this->useHttps;
    }

    /**
     * @return bool
     */
    public function isUseProxy() {
        return $this->useProxy;
    }

    /**
     * @return bool
     */
    public function isIgnoreSslCertificate() {
        return !$this->ignoreSslCertificate;
    }

    /**
     * @return array
     */
    public function getIncludedHostgroups() {
        return $this->includedHostgroups;
    }

    /**
     * @return array
     */
    public function getExcludedHostgroups() {
        return $this->excludedHostgroups;
    }

    /**
     * @return string
     */
    public function getApiUrl() {
        if ($this->isDockerGrafana()) {
            return sprintf(
                '%s127.0.0.1%s/api',
                $this->isUseHttps() ? 'https://' : 'http://',
                $this->getDockerUrl()
            );
        }

        return sprintf(
            '%s%s/api',
            $this->isUseHttps() ? 'https://' : 'http://',
            $this->apiUrl
        );
    }

    private function getUiUrl() {
        return sprintf(
            '%s%s',
            $this->isUseHttps() ? 'https://' : 'http://',
            $this->apiUrl
        );
    }

    /**
     * @param $uuid
     */
    public function setHostUuid($uuid) {
        $this->hostUuid = $uuid;
    }

    /**
     * @param string
     */
    public function setGrafanaUid($uid) {
        if($uid === ''){
            $uid = null;
        }
        $this->grafana_uid = $uid;
    }

    /**
     * @return string
     */
    public function getIframeUrl() {
        $uiUrl = $this->getUiUrl();
        if ($this->isDockerGrafana()) {
            $uiUrl = $this->getDockerUrl();
        }

        return sprintf(
            '%s/dashboard/db/%s?theme=%s&kiosk',
            $uiUrl,
            $this->hostUuid,
            $this->dashboardStyle
        );
    }

    public function getIframeUrlForDatepicker($timerange = 'now-3h', $autorefresh = '0') {
        //&kiosk=tv require Grafana 5.3+ to work
        //https://github.com/grafana/grafana/issues/13493
        //Since Grafana 5.3, users can escape the &kiosk mode by pressing esc key.
        //Also &kiosk=tv is not very helpful. So we implemented an datepicker for now.

        $autoRefreshUrlStr = '';
        if ($autorefresh !== 0 && $autorefresh !== '0') {
            $autoRefreshUrlStr = sprintf('&refresh=%s', $autorefresh);
        }

        $uiUrl = $this->getUiUrl();
        if ($this->isDockerGrafana()) {
            $uiUrl = $this->getDockerUrl();
        }

        if($this->grafana_uid !== null) {
            //Old Grfana URL <? 5.4
            return sprintf(
                '%s/d/%s/%s?theme=%s%s&from=%s&to=now&kiosk',
                $uiUrl,
                $this->grafana_uid,
                $this->hostUuid,
                $this->dashboardStyle,
                $autoRefreshUrlStr,
                $timerange
            );
        }else{
            //Old Grfana URL <? 5.4
            return sprintf(
                '%s/dashboard/db/%s?theme=%s%s&from=%s&to=now&kiosk',
                $uiUrl,
                $this->hostUuid,
                $this->dashboardStyle,
                $autoRefreshUrlStr,
                $timerange
            );
        }
    }

    public function getIframeUrlForUserDashboard($url, $timerange = 'now-3h', $autorefresh = '0') {
        //&kiosk=tv require Grafana 5.3+ to work
        //https://github.com/grafana/grafana/issues/13493
        //Since Grafana 5.3, users can escape the &kiosk mode by pressing esc key.
        //Also &kiosk=tv is not very helpful. So we implemented an datepicker for now.

        $autoRefreshUrlStr = '';
        if ($autorefresh !== 0 && $autorefresh !== '0') {
            $autoRefreshUrlStr = sprintf('&refresh=%s', $autorefresh);
        }

        $uiUrl = $this->getUiUrl();
        if ($this->isDockerGrafana()) {
            $uiUrl = $this->getDockerUrl();
        }

        return sprintf(
            '%s%s?theme=%s%s&from=%s&to=now&kiosk',
            $uiUrl,
            $url,
            $this->dashboardStyle,
            $autoRefreshUrlStr,
            $timerange
        );
    }

    public function isDockerGrafana() {
        return $this->apiUrl === 'grafana.docker';
    }

    public function getDockerUrl() {
        return '/grafana';
    }
}
