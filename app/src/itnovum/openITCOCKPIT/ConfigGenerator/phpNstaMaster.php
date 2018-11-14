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


class phpNstaMaster extends ConfigGenerator implements ConfigInterface {

    protected $templateDir = 'phpNSTA';

    protected $template = 'config.php.tpl';

    protected $outfile = '/etc/phpnsta/config.php';

    /**
     * @var string
     */
    protected $commentChar = '//';

    protected $defaults = [
        'string' => [
            'use_spooldir'          => '3',
            'logrotate_date_format' => 'd_m_Y_H_i',
            'date_format'           => 'd.m.Y H:i:s',
            'ssh_username'          => 'nagios',
            'private_path'          => '/var/lib/nagios/.ssh/id_rsa',
            'public_path'           => '/var/lib/nagios/.ssh/id_rsa.pub',
            'port_range'            => '55000-55500',
            'supervisor_username'   => 'phpNSTA',
            'supervisor_password'   => 'phpNSTAsSecretPassword',
            'tsync_every'           => 'hour',
            'loglevel'              => '12',
        ],
        'int'    => [
            'cleanup_fileage' => 15,
            'max_checks'      => 200,
            'max_threads'     => 20,
            'ssh_port'        => 22,
        ],
        'bool'   => [
            'use_ssh_tunnel'   => 1,
            'synchronize_time' => 1
        ]
    ];

    protected $dbKey = 'phpNSTAMaster';

    /**
     * @param array $data
     * @return array|bool|true
     */
    public function customValidationRules($data) {
        $error = [];
        $fakeModelName = 'Configfile';
        if (isset($data['string']) && is_array($data['string'])) {
            foreach ($data['string'] as $field => $value) {

                switch ($field) {
                    case 'use_spooldir':
                        if ($value < 1 || $value > 3) {
                            $error[$fakeModelName][$field][] = __('Value out of range (1-3)');
                        }
                        break;

                    case 'loglevel':
                        if ($value < -1 || $value > 12) {
                            $error[$fakeModelName][$field][] = __('Value out of range (1-3)');
                        }
                        break;
                }
            }
        }

        if (!empty($error)) {
            return $error;
        }

        return true;
    }

    /**
     * @return string
     */
    public function getAngularDirective() {
        return 'phpnsta-master-cfg';
    }

    /**
     * @param string $key
     * @return string
     */
    public function getHelpText($key) {
        $help = [
            'logrotate_date_format' => __('Date format for filename of rotaed logfiles. Germany: d_m_Y_H_i, USA: m_d_Y_H_i'),
            'date_format'           => __('Date format of the timestamp in the logfile. Germany: d.m.Y H:i:s, USA: m.d.Y H:i:s'),
            'cleanup_fileage'       => __('Determines in days, how long old log files should be keeped.'),
            'use_spooldir'          => __('Determines how to submit check result data to Naemon/Nagios.'),
            'max_checks'            => __('Bulk size of submitted passive check results.'),
            'max_threads'           => __('Maximum number of worker processes. Recommendation: One process per satellite.'),
            'use_ssh_tunnel'        => __('Use an SSH tunnel for each satellite for secure connection.'),
            'ssh_username'          => __('Username used to established SSH connection.'),
            'private_path'          => __('Private key file used to established SSH connection.'),
            'public_path'           => __('Public key file used to established SSH connection.'),
            'ssh_port'              => __('SSH Port of remote hosts'),
            'port_range'            => __('The local ports where each satellite system gets bind to'),
            'supervisor_username'   => __('Username used for supervisor XML-RPC API.'),
            'supervisor_password'   => __('Password of supervisor XML-RPC API'),
            'synchronize_time'      => __('Synchronize the system clock to each connected satellite system'),
            'tsync_every'           => __('System time synchronization interval')
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
                switch ($type) {
                    case 'bool':
                        $configToExport[$key] = $this->asBoolString($value);
                        break;

                    default:
                        $configToExport[$key] = $value;
                        break;

                }
            }
        }

        return $this->saveConfigFile($configToExport);
    }

}