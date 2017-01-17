<?php
// Copyright (C) <2015>  <it-novum GmbH>
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

namespace itnovum\openITCOCKPIT\ApiShell;


use itnovum\openITCOCKPIT\ApiShell\Exceptions\MissingParameterExceptions;

class OptionParser
{

    /**
     * @var string
     */
    private $model;

    /**
     * @var string
     */
    private $action;

    /**
     * @var array
     */
    private $data;

    /**
     * @var string
     */
    private $plugin;

    /**
     * @param array $parameters
     * @param array $args
     *
     * @throws MissingParameterExceptions
     */
    public function parse($parameters, $args = [])
    {
        if (!array_key_exists('model', $parameters)) {
            throw new MissingParameterExceptions('Paremter --model is missing');
        }
        $this->model = ucfirst(strtolower($parameters['model']));

        if (!array_key_exists('action', $parameters)) {
            throw new MissingParameterExceptions('Paremter --action is missing');
        }
        $this->action = strtolower($parameters['action']);

        if (!array_key_exists('data', $parameters)) {
            throw new MissingParameterExceptions('Paremter --data is missing');
        }
        $this->data = array_merge([$parameters['data']], $args);

        if (array_key_exists('plugin', $parameters)) {
            $this->plugin = ucfirst(strtolower($parameters['plugin']));
        }
    }

    /**
     * @return string
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * @return string
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @return string
     */
    public function getPlugin()
    {
        return $this->plugin;
    }

}