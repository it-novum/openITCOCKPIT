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

/**
 * Class Documentation
 * @deprecated
 */
class Documentation extends AppModel {
    public $validate = [
        'uuid' => [
            'notBlank' => [
                'rule'     => 'notBlank',
                'message'  => 'This field cannot be left blank.',
                'required' => true,
            ],
            'isUnique' => [
                'rule'    => 'isUnique',
                // The migration scripts needs to be adapted if this message is changed.
                // Otherwise the migration script won't work properly anymore!
                'message' => 'This uuid already exists.',
            ],
        ],
    ];

    /**
     * @param null $uuid
     * @return bool
     * @deprecated
     */
    public function existsForUuid($uuid = null) {
        $result = $this->find('first', [
            'recursive'  => -1,
            'conditions' => [
                'Documentation.uuid' => $uuid
            ]
        ]);
        return !empty($result);
    }
}
