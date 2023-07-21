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


// in src/Form/ConfigurationitemsExportForm.php
namespace App\Form;

use Cake\Form\Form;
use Cake\Form\Schema;
use Cake\Utility\Hash;
use Cake\Validation\Validator;

/**
 * Class ConfigurationitemsExportForm
 * @package App\Form
 */
class ConfigurationitemsExportForm extends Form {
    protected function _buildSchema(Schema $schema): Schema {
        return $schema
            ->addField('commands', ['type' => 'array'])
            ->addField('timeperiods', ['type' => 'array'])
            ->addField('contacts', ['type' => 'array'])
            ->addField('contactgroups', ['type' => 'array'])
            ->addField('servicetemplates', ['type' => 'array'])
            ->addField('servicetemplategroups', ['type' => 'array']);
    }

    public function validationDefault(Validator $validator): Validator {
        $validator
            ->add('commands', 'custom', [
                'rule'    => [$this, 'atLeastOneConfigurationItem'],
                'message' => __('You must select at least one configuration item for export.')
            ]);
        $validator
            ->add('timeperiods', 'custom', [
                'rule'    => [$this, 'atLeastOneConfigurationItem'],
                'message' => __('You must select at least one configuration item for export.')
            ]);
        $validator
            ->add('contacts', 'custom', [
                'rule'    => [$this, 'atLeastOneConfigurationItem'],
                'message' => __('You must select at least one configuration item for export.')
            ]);
        $validator
            ->add('contactgroups', 'custom', [
                'rule'    => [$this, 'atLeastOneConfigurationItem'],
                'message' => __('You must select at least one configuration item for export.')
            ]);
        $validator
            ->add('servicetemplates', 'custom', [
                'rule'    => [$this, 'atLeastOneConfigurationItem'],
                'message' => __('You must select at least one configuration item for export.')
            ]);
        $validator
            ->add('servicetemplategroups', 'custom', [
                'rule'    => [$this, 'atLeastOneConfigurationItem'],
                'message' => __('You must select at least one configuration item for export.')
            ]);

        return $validator;
    }

    /**
     * @param $value
     * @param $context
     * @return bool
     */
    public function atLeastOneConfigurationItem($value, $context) {
        return !empty(Hash::filter(Hash::extract($context['data'], '{s}._ids')));
    }
}
