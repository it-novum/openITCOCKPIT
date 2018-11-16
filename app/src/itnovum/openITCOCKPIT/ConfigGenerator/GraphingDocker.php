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

    protected $outfile = '/usr/share/openitcockpit/docker/container/graphing/docker-compose.yml';

    /**
     * @var string
     */
    protected $commentChar = '#';

    protected $defaults = [
        'string' => [
            'carbon_path'           => '/var/lib/graphite/whisper',
            'carbon_storage_schema' => '60s:365'
        ],
        'int'    => [
            'number_of_carbon_cache_instances' => 2,
            'number_of_carbon_c_relay_workers' => 4
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

        $configDir = dirname($this->outfile);
        if (!is_dir($configDir)) {
            return false;
        }

        $FileHeader = new FileHeader();
        $configToExport['STATIC_FILE_HEADER'] = $FileHeader->getHeader($this->commentChar);
        $success = true;

        /*
         * Write:
         * - carbon-c-relay.conf
         * carbon.conf
         * docker-compose.yml
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
        if (!file_put_contents($this->outfile, $twig->render($this->getTemplateName(), $configToExport))) {
            $success = false;
        }

        //carbon-c-relay.conf
        if (!file_put_contents('/etc/openitcockpit/carbon/carbon-c-relay.conf', $twig->render('carbon-c-relay.conf', $configToExport))) {
            $success = false;
        }

        //carbon.conf
        if (!file_put_contents('/etc/openitcockpit/carbon/carbon.conf', $twig->render('carbon.conf', $configToExport))) {
            $success = false;
        }

        //storage-schemas.conf
        if (!file_put_contents('/etc/openitcockpit/carbon/storage-schemas.conf', $twig->render('storage-schemas.conf', $configToExport))) {
            $success = false;
        }

        //storage-aggregation.conf
        if (!file_put_contents('/etc/openitcockpit/carbon/storage-aggregation.conf', $twig->render('storage-aggregation.conf', $configToExport))) {
            $success = false;
        }

        //local_settings.py (graphite web)
        if (!file_put_contents('/etc/openitcockpit/carbon/local_settings.py', $twig->render('local_settings.py', $configToExport))) {
            $success = false;
        }

        //wsgi.py (graphite web)
        if (!file_put_contents('/etc/openitcockpit/carbon/wsgi.py', $twig->render('wsgi.py', $configToExport))) {
            $success = false;
        }

        //grafana.ini
        if (!file_put_contents('/etc/openitcockpit/grafana/grafana.ini', $twig->render('grafana.ini', $configToExport))) {
            $success = false;
        }


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