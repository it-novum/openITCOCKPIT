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


class StatusengineConfig extends ConfigGenerator implements ConfigInterface {

    protected $templateDir = 'statusengine';

    protected $template = 'Statusengine.php.tpl';

    protected $outfile = '/etc/statusengine/Config/Statusengine.php';

    /**
     * @var string
     */
    protected $commentChar = '//';

    protected $defaults = [
        'int' => [
            'number_servicestatus_worker' => 1,
            'number_hoststatus_worker'    => 1,
            'number_hostcheck_worker'     => 1,
            'number_servicecheck_worker'  => 1,
            'number_perfdata_worker'      => 1,

            'number_of_bulk_records' => 500,
            'max_bulk_delay'         => 1,

            'graphite_port' => 2003
        ],

        'string' => [
            'graphite_address' => '127.0.0.1',
            'graphite_prefix'  => 'openitcockpit'
        ]
    ];

    protected $dbKey = 'StatusengineCfg';

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
        return 'statusengine-cfg';
    }

    /**
     * @param string $key
     * @return string
     */
    public function getHelpText($key) {
        $help = [
            'number_servicestatus_worker' => 'Number of Statusengine Worker processes which consume statusngin_servicestatus queue',
            'number_hoststatus_worker'    => 'Number of Statusengine Worker processes which consume statusngin_hoststatus queue',
            'number_hostcheck_worker'     => 'Number of Statusengine Worker processes which consume statusngin_hostchecks queue',
            'number_servicecheck_worker'  => 'Number of Statusengine Worker processes which consume statusngin_servicechecks queue',
            'number_perfdata_worker'      => 'Number of Statusengine Worker processes which consume statusngin_servicechecks queue (Statusengine 3)',
            'number_of_bulk_records'      => 'Number of SQL operations per bunch',
            'max_bulk_delay'              => 'Time in seconds Statusengine will wait to reach number_of_bulk_records',
            'graphite_port'               => 'Carbon cache port number',
            'graphite_address'            => 'Carbon cache server address',
            'graphite_prefix'             => 'Prefix added to every metric stored in carbon (Required for Grafana)'
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
        $config = $this->mergeDbResultWithDefaultConfiguration($dbRecords);
        $configToExport = [];
        foreach ($config as $type => $fields) {
            foreach ($fields as $key => $value) {
                $configToExport[$key] = $value;
            }
        }

        $configToExport['se2_number_servicestatus_worker'] = [];
        for ($i = 1; $i <= $configToExport['number_servicestatus_worker']; $i++) {
            $configToExport['se2_number_servicestatus_worker'][] =
                '\'queues\' => [\'statusngin_servicestatus\' => \'processServicestatus\']';

        }

        //One hoststatus worker is defined in the configuration by default
        //For this reason $i=2 in this case
        $configToExport['se2_number_hoststatus_worker'] = [];
        for ($i = 2; $i <= $configToExport['number_hoststatus_worker']; $i++) {
            $configToExport['se2_number_hoststatus_worker'][] =
                '\'queues\' => [\'statusngin_hoststatus\' => \'processHoststatus\']';
        }

        //One hostcheck worker is defined in the configuration by default
        //For this reason $i=2 in this case
        $configToExport['se2_number_hostcheck_worker'] = [];
        for ($i = 2; $i <= $configToExport['number_hostcheck_worker']; $i++) {
            $configToExport['se2_number_hostcheck_worker'][] =
                '\'queues\' => [\'statusngin_hostchecks\' => \'processHostchecks\']';

        }

        $configToExport['se2_number_servicecheck_worker'] = [];
        for ($i = 1; $i <= $configToExport['number_servicecheck_worker']; $i++) {
            $configToExport['se2_number_servicecheck_worker'][] =
                '\'queues\' => [\'statusngin_servicechecks\' => \'processServicechecks\']';
        }


        $success = true;

        /*
         * Write:
         * - Statusengine.php
         * - Perfdata.php
         * - Graphite.php
         */
        $loader = new \Twig_Loader_Filesystem([
            $this->getTemplatePath()
        ]);
        $twig = new \Twig_Environment($loader, ['debug' => true]);

        // /etc/statusengine/Config/Statusengine.php
        if (!file_put_contents($this->outfile, $twig->render($this->getTemplateName(), $configToExport))) {
            $success = false;
        }

        // /etc/statusengine/Config/Perfdata.php
        if (!file_put_contents('/etc/statusengine/Config/Perfdata.php', $twig->render('Perfdata.php.tpl', $configToExport))) {
            $success = false;
        }

        // /etc/statusengine/Config/Graphite.php
        if (!file_put_contents('/etc/statusengine/Config/Graphite.php', $twig->render('Graphite.php.tpl', $configToExport))) {
            $success = false;
        }

        return $success;

    }

    /**
     * @param array $dbRecords
     * @return bool|array
     */
    public function migrate($dbRecords) {
        if (!file_exists($this->outfile)) {
            return false;
        }

        $defaultConfig = $this->mergeDbResultWithDefaultConfiguration($dbRecords);

        //Load current Statusengine.php
        require_once $this->outfile;

        $counts = [
            'statusngin_hoststatus'    => 0,
            'statusngin_servicestatus' => 0,
            'statusngin_hostchecks'    => 0,
            'statusngin_servicechecks' => 0
        ];

        if (isset($config['workers'])) {
            foreach ($config['workers'] as $worker) {
                foreach ($worker['queues'] as $queue => $method) {
                    if (isset($counts[$queue])) {
                        $counts[$queue]++;
                    }
                }
            }
        }

        $defaultConfig['int']['number_hoststatus_worker'] = $counts['statusngin_hoststatus'];
        $defaultConfig['int']['number_servicestatus_worker'] = $counts['statusngin_servicestatus'];
        $defaultConfig['int']['number_hostcheck_worker'] = $counts['statusngin_hostchecks'];
        $defaultConfig['int']['number_servicecheck_worker'] = $counts['statusngin_servicechecks'];

        $defaultConfig['int']['number_of_bulk_records'] = (int)$config['bulk_query_limit'];

        //Try to load Graphite.php
        if (file_exists('/etc/statusengine/Config/Graphite.php')) {
            require_once '/etc/statusengine/Config/Graphite.php';

            if (isset($config['graphite']['host'])) {
                $defaultConfig['string']['graphite_address'] = $config['graphite']['host'];
            }

            if (isset($config['graphite']['prefix'])) {
                $defaultConfig['string']['graphite_prefix'] = $config['graphite']['prefix'];
            }

            if (isset($config['graphite']['port'])) {
                $defaultConfig['int']['graphite_port'] = (int)$config['graphite']['port'];
            }

        }

        return $defaultConfig;
    }

}