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

App::uses('PaginatorComponent', 'Controller/Component');


class AppPaginatorComponent extends PaginatorComponent {

    public function validateSort(Model $object, array $options, array $whitelist = []) {
        $options = parent::validateSort($object, $options, $whitelist);

        $unescaped = $options['order'];
        unset($options['order']);
        foreach($unescaped as $column => $value){
            //CakePHP's order validation is a joke. This as well but, what sould we do...
            $matches = null;
            if(preg_match('/^([a-zA-Z\.\_]+)$/', $column, $matches) === 1){
                if(!isset($options['order'])){
                    $options['order'] = [];
                }
                $options['order'][$column] = $value;
            }
            //We have a problem!
            //Some like Host.id;drop database--
            //Drop this
            continue;
        }

        return $options;
    }

}
