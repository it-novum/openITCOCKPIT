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

namespace itnovum\openITCOCKPIT\ConfigGenerator;


class GraphingDocker extends ConfigGenerator implements ConfigInterface {

    protected $templateDir = 'graphing';

    protected $template = 'docker-compose.yml';

    protected $linkedOutfile = '/usr/share/openitcockpit/docker/container/graphing/docker-compose.yml';

    protected $realOutfile = '/usr/share/openitcockpit/docker/container/graphing/docker-compose.yml';

    /**
     * @var string
     */
    protected $commentChar = '#';

    protected $defaults = [
        'string' => [
            'carbon_path'           => '/var/lib/graphite/whisper',
            'carbon_storage_schema' => '60s:365d'
        ],
        'int'    => [
            'number_of_carbon_cache_instances' => 2,
            'number_of_carbon_c_relay_workers' => 4,
            'local_graphite_http_port'         => 8888,
            'local_graphite_plaintext_port'    => 2003,
        ]
    ];

    protected $dbKey = 'GraphingDocker';

    /**
     * @param array $data
     * @return array|bool|true
     */
    public function customValidationRules($data) {
        return true;
    }

    /**
     * @return string
     */
    public function getAngularDirective() {
        return 'graphing-docker-cfg';
    }

    /**
     * @param string $key
     * @return string
     */
    public function getHelpText($key) {
        $help = [
            'carbon_path'                      => __('Path where Carbon will store whisper files.'),
            'carbon_storage_schema'            => __('Carbon storage schema'),
            'number_of_carbon_cache_instances' => __('Number of carbon cache instances for multi core CPU scaling'),
            'number_of_carbon_c_relay_workers' => __('Number of Carbon-C-Relay worker threads. (Carbon-Cache load balancer)'),
            'local_graphite_http_port'         => __('Local HTTP port used by Graphite-Web'),
            'local_graphite_plaintext_port'    => __('Local plaintext port to send metrics to Carbon-C-Relay.')
        ];

        if (isset($help[$key])) {
            return $help[$key];
        }

        return '';
    }

    /**
     * Save the configuration as text file on disk
     *
     * @param array $dbRecords
     * @return bool|int
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function writeToFile($dbRecords) {
        //This method needs to write multiple configuration files!
        $config = $this->mergeDbResultWithDefaultConfiguration($dbRecords);
        $configToExport = [];
        foreach ($config as $type => $fields) {
            foreach ($fields as $key => $value) {
                $configToExport[$key] = $value;
            }
        }

        if (!is_dir('/etc/openitcockpit/carbon') || !is_dir('/etc/openitcockpit/grafana')) {
            return false;
        }

        $configDir = dirname($this->linkedOutfile);
        if (!is_dir($configDir)) {
            return false;
        }

        $FileHeader = new FileHeader();
        $configToExport['STATIC_FILE_HEADER'] = $FileHeader->getHeader($this->commentChar);
        $success = true;

        /*
         * Write:
         * - carbon-c-relay.conf
         * - carbon.conf
         * - storage-schemas.conf
         * - storage-aggregation.conf
         * - local_settings.py
         * - wsgi.py
         * - grafana.ini
         * - docker-compose.yml
         */
        $loader = new \Twig_Loader_Filesystem([
            $this->getTemplatePath(),
            $this->getTemplatePath() . DS . 'carbon-c-relay',
            $this->getTemplatePath() . DS . 'carbon-cache',
            $this->getTemplatePath() . DS . 'grafana',
            $this->getTemplatePath() . DS . 'graphite-web'
        ]);
        $twig = new \Twig_Environment($loader, ['debug' => true]);
        $configToExport['CarbonCaches'] = [];
        for ($i = 1; $i <= $configToExport['number_of_carbon_cache_instances']; $i++) {
            $instanceName = $i;
            if ($i < 10) {
                $instanceName = sprintf('0%s', $i);
            }
            $hostname = sprintf('carbon-cache%s', $instanceName);

            $configToExport['CarbonCaches'][] = [
                'instanceName' => $instanceName,
                'hostname'     => $hostname,
                'port'         => 2003
            ];
        }
        //docker-compose.yml
        if (!file_put_contents($this->linkedOutfile, $twig->render($this->getTemplateName(), $configToExport))) {
            $success = false;
        }

        //carbon-c-relay.conf
        $ConfigSymlink = new ConfigSymlink('/var/lib/openitcockpit/etc/generated/carbon/carbon-c-relay.conf', '/etc/openitcockpit/carbon/carbon-c-relay.conf');
        if (!file_put_contents('/var/lib/openitcockpit/etc/generated/carbon/carbon-c-relay.conf', $twig->render('carbon-c-relay.conf', $configToExport))) {
            $success = false;
        }
        $ConfigSymlink->link();

        //carbon.conf
        $ConfigSymlink = new ConfigSymlink('/var/lib/openitcockpit/etc/generated/carbon/carbon.conf', '/etc/openitcockpit/carbon/carbon.conf');
        if (!file_put_contents('/var/lib/openitcockpit/etc/generated/carbon/carbon.conf', $twig->render('carbon.conf', $configToExport))) {
            $success = false;
        }
        $ConfigSymlink->link();

        //storage-schemas.conf
        $ConfigSymlink = new ConfigSymlink('/var/lib/openitcockpit/etc/generated/carbon/storage-schemas.conf', '/etc/openitcockpit/carbon/storage-schemas.conf');
        if (!file_put_contents('/var/lib/openitcockpit/etc/generated/carbon/storage-schemas.conf', $twig->render('storage-schemas.conf', $configToExport))) {
            $success = false;
        }
        $ConfigSymlink->link();

        //storage-aggregation.conf
        $ConfigSymlink = new ConfigSymlink('/var/lib/openitcockpit/etc/generated/carbon/storage-aggregation.conf', '/etc/openitcockpit/carbon/storage-aggregation.conf');
        if (!file_put_contents('/var/lib/openitcockpit/etc/generated/carbon/storage-aggregation.conf', $twig->render('storage-aggregation.conf', $configToExport))) {
            $success = false;
        }
        $ConfigSymlink->link();

        //local_settings.py (graphite web)
        $ConfigSymlink = new ConfigSymlink('/var/lib/openitcockpit/etc/generated/carbon/local_settings.py', '/etc/openitcockpit/carbon/local_settings.py');
        if (!file_put_contents('/var/lib/openitcockpit/etc/generated/carbon/local_settings.py', $twig->render('local_settings.py', $configToExport))) {
            $success = false;
        }
        $ConfigSymlink->link();

        //wsgi.py (graphite web)
        $ConfigSymlink = new ConfigSymlink('/var/lib/openitcockpit/etc/generated/carbon/wsgi.py', '/etc/openitcockpit/carbon/wsgi.py');
        if (!file_put_contents('/var/lib/openitcockpit/etc/generated/carbon/wsgi.py', $twig->render('wsgi.py', $configToExport))) {
            $success = false;
        }
        $ConfigSymlink->link();

        //grafana.ini
        $ConfigSymlink = new ConfigSymlink('/var/lib/openitcockpit/etc/generated/grafana/grafana.ini', '/etc/openitcockpit/grafana/grafana.ini');
        if (!file_put_contents('/var/lib/openitcockpit/etc/generated/grafana/grafana.ini', $twig->render('grafana.ini', $configToExport))) {
            $success = false;
        }
        $ConfigSymlink->link();


        return $success;
    }

    /**
     * @param array $dbRecords
     * @return bool|array
     */
    public function migrate($dbRecords) {
        //No migration for this configs
        return $this->mergeDbResultWithDefaultConfiguration($dbRecords);
    }

}