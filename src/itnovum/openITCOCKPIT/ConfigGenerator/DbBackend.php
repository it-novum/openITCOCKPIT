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


use Cake\Core\Configure;

class DbBackend extends ConfigGenerator implements ConfigInterface {

    protected $templateDir = 'config';

    protected $template = 'dbbackend.php.tpl';

    /**
     * @var string
     */
    protected $linkedOutfile = '/etc/openitcockpit/app/Config/dbbackend.php';

    /**
     * @var string
     */
    protected $realOutfile = '/var/lib/openitcockpit/etc/generated/app/Config/dbbackend.php';

    /**
     * @var string
     */
    protected $commentChar = '//';

    protected $defaults = [
        'string' => [
            'dbbackend' => 'Nagios'
            /* @todo change me to Statusengine3 */
        ]
    ];

    protected $dbKey = 'DbBackend';

    /**
     * @param array $data
     * @return array|bool|true
     */
    public function customValidationRules($data) {
        $error = [];
        $fakeModelName = 'Configfile';
        if (isset($data['string']) && is_array($data['string'])) {
            foreach ($data['string'] as $field => $value) {
                if ($field === 'dbbackend') {
                    if (!in_array($value, ['Nagios', 'Crate', 'Statusengine3'], true)) {
                        $error[$fakeModelName][$field][] = __('Value out of range (Nagios, Crate, Statusengine3)');
                    }
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
        return 'db-backend-cfg';
    }

    /**
     * @param string $key
     * @return string
     */
    public function getHelpText($key) {
        $help = [
            'dbbackend' => __('Database Backend used by openITCOCKPIT. Be careful with this option!'),
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
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function writeToFile($dbRecords) {
        $config = $this->mergeDbResultWithDefaultConfiguration($dbRecords);
        $configToExport = [];
        foreach ($config as $type => $fields) {
            foreach ($fields as $key => $value) {
                $configToExport[$key] = $value;
            }
        }

        return $this->saveConfigFile($configToExport);
    }

    /**
     * @param array $dbRecords
     * @return bool|array
     */
    public function migrate($dbRecords) {
        return $this->mergeDbResultWithDefaultConfiguration($dbRecords);

        /*
        //No migration for DbBackend
        if (!file_exists($this->linkedOutfile)) {
            return false;
        }
        $config = $this->mergeDbResultWithDefaultConfiguration($dbRecords);

        Configure::load('dbbackend');
        $configFromFile = Configure::read('dbbackend');

        if ($config['string']['dbbackend'] != $configFromFile) {
            $config['string']['dbbackend'] = $configFromFile;
        }

        return $config;
        */
    }

}
