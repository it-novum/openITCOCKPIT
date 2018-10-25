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

namespace itnovum\openITCOCKPIT\InitialDatabase;

class Importer implements ImporterInterface {
    /**
     * @var \Model
     */
    protected $Model;

    public function __construct(\Model $Model) {
        $this->Model = $Model;
    }

    /**
     * @return bool
     */
    public function isTableEmpty() {
        $this->unbindAllAssociations();
        $result = $this->Model->find('all', [
            'recursive' => -1
        ]);

        return empty($result);

    }

    /**
     * @return array
     */
    public function getData() {
        return [];
    }

    public function unbindAllAssociations() {
        foreach (['hasOne', 'hasMany', 'belongsTo', 'hasAndBelongsToMany'] as $association) {
            if (!empty($this->Model->{$association})) {
                foreach ($this->Model->{$association} as $accociatedModel) {
                    $this->Model->unbindModel([$association => [$accociatedModel['className']]]);
                }
            }
        }
    }

}
