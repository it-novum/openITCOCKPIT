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

class Cronjob extends AppModel
{
    public $hasOne = ['Cronschedule'];

    var $validate = [
        'task'     => [
            'notBlank' => [
                'rule'     => 'notBlank',
                'message'  => 'This field cannot be left blank.',
                'required' => true,
            ],
        ],
        'plugin'   => [
            'notBlank' => [
                'rule'     => 'notBlank',
                'message'  => 'This field cannot be left blank.',
                'required' => true,
            ],
        ],
        'interval' => [
            'notBlank' => [
                'rule'     => 'notBlank',
                'message'  => 'This field cannot be left blank.',
                'required' => true,
            ],
            'numeric'  => [
                'rule'    => 'numeric',
                'message' => 'This field needs to be numeric.',
            ],
            'notZero'  => [
                'rule'     => ['comparison', '>', 0],
                'message'  => 'Please enter a number > 0.',
                'required' => true,
            ],
        ],
    ];

    public function fetchPlugins()
    {
        $plugins = [];
        $plugins['Core'] = 'Core';
        $modulePlugins = array_filter(CakePlugin::loaded(), function ($value) {
            return strpos($value, 'Module') !== false;
        });

        foreach ($modulePlugins as $pluginName) {
            $plugins[$pluginName] = $pluginName;
        }

        return $plugins;
    }

    public function fetchTasks($pluginName)
    {
        $return = [];
        if ($pluginName == 'Core') {
            if (is_dir(APP.'Console/Command/Task/')) {
                $result = scandir(APP.'Console/Command/Task/');
                if (!empty($result) && is_array($result)) {
                    foreach ($result as $file) {
                        if ($file != '.' && $file != '..' && $file != 'empty') {
                            $taskName = str_replace('Task.php', '', $file);
                            $return[$taskName] = $taskName;
                        }
                    }
                }
            }
        } else {
            if (is_dir(APP.'Plugin/'.$pluginName.'/Console/Command/Task/')) {
                $result = scandir(APP.'Plugin/'.$pluginName.'/Console/Command/Task/');
                if (!empty($result) && is_array($result)) {
                    foreach ($result as $file) {
                        if ($file != '.' && $file != '..' && $file != 'empty') {
                            $taskName = str_replace('Task.php', '', $file);
                            $return[$taskName] = $taskName;
                        }
                    }
                }
            }
        }

        return $return;
    }

    public function checkForCronjob($task, $pluginName = 'Core')
    {
        $result = $this->find('first', [
            'conditions' => [
                'plugin' => $pluginName,
                'task'   => $task,
            ],
        ]);

        return !empty($result);
    }

    public function add($task, $pluginName = 'Core', $interval)
    {
        $this->create();

        return $this->save(
            [
                'Cronjob' => [
                    'task'     => $task,
                    'plugin'   => $pluginName,
                    'interval' => $interval,
                    'enabled'  => 1
                ],
            ]
        );
    }
}