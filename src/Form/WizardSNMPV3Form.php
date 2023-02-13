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


namespace App\Form;

use Cake\Form\Form;
use Cake\Form\Schema;
use Cake\Validation\Validator;

/**
 * Class WizardSNMPForm
 * @package App\Form
 */
class WizardSNMPV3Form extends Form {
    protected function _buildSchema(Schema $schema): Schema {
        return $schema
            ->addField('securityName', ['type' => 'string'])
            ->addField('authPassword', ['type' => 'string'])
            ->addField('privacyProtocol', ['type' => 'string'])
            ->addField('authProtocol', ['type' => 'string'])
            ->addField('privacyPassword', ['type' => 'string'])
            ->addField('services', ['type' => 'array']);
    }

    public function validationDefault(Validator $validator): Validator {
        $validator
            ->requirePresence('securityName')
            ->notEmptyString('securityName');
        $validator
            ->requirePresence('authPassword')
            ->notEmptyString('authPassword');
        $validator
            ->requirePresence('privacyProtocol')
            ->notEmptyString('privacyProtocol');
        $validator
            ->requirePresence('authProtocol')
            ->notEmptyString('authProtocol');
        $validator
            ->requirePresence('privacyPassword')
            ->notEmptyString('privacyPassword');

        $validator
            ->notEmptyArray('services', __('Please select at least one service'));
        return $validator;
    }
}
