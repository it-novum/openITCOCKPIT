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

use itnovum\openITCOCKPIT\Core\ValueObjects\LastDeletedId;

class Command extends AppModel
{
    public $hasMany = [
        'Commandargument' => [
            'className'  => 'Commandargument',
            'dependent'  => true,
            'foreignKey' => 'command_id',
        ],
    ];

    public $validate = [
        'name' => [
            'notBlank' => [
                'rule'     => 'notBlank',
                'message'  => 'This field cannot be left blank.',
                'required' => true,
            ],
            'isUnique' => [
                'rule'    => 'isUnique',
                // The migration scripts needs to be adapted if this message is changed.
                // Otherwise the migration script won't work properly anymore!
                'message' => 'This command name has already been taken.',
            ],
        ],
        'uuid' => [
            'notBlank' => [
                'rule'     => 'notBlank',
                'message'  => 'This field cannot be left blank.',
                'required' => true,
                'on'       => 'create',
            ],
        ],
    ];

    /**
     * @var LastDeletedId|null
     */
    private $LastDeletedId = null;

    public function __construct($id = false, $table = null, $ds = null)
    {
        parent::__construct($id, $table, $ds);
        App::uses('UUID', 'Lib');
    }

    /**
     * Return all CHECK_COMMAND Objects
     *
     * @param string $type    for tha CakePHP find function (all, list, first, ...)
     * @param array  $options Options for find()
     *
     * @return array $ find result
     * @author Daniel Ziegler <daniel.ziegler@it-novum.com>
     * @since  3.0
     */
    public function checkCommands($type = 'all', $options = [])
    {
        $_options = [
            'conditions' => [
                'command_type' => CHECK_COMMAND,
            ],
        ];

        return $this->find($type, Hash::merge($_options, $options));
    }

    /**
     * Return all HOSTCHECK_COMMAND Objects
     *
     * @param string $type    for tha CakePHP find function (all, list, first, ...)
     * @param array  $options Options for find()
     *
     * @return array $ find result
     * @author Daniel Ziegler <daniel.ziegler@it-novum.com>
     * @since  3.0
     */
    public function hostCommands($type = 'all', $options = [])
    {
        $_options = [
            'conditions' => [
                'command_type' => HOSTCHECK_COMMAND,
            ],
        ];

        return $this->find($type, Hash::merge($_options, $options));
    }

    /**
     * Return all SERVICECHECK_COMMAND Objects
     *
     * @param string $type    for tha CakePHP find function (all, list, first, ...)
     * @param array  $options Options for find()
     *
     * @return array $ find result
     * @author Maximilian Pappert <maximilian.pappert@it-novum.com>
     * @since  3.0
     */
    public function serviceCommands($type = 'all', $options = [])
    {
        $_options = [
            'conditions' => [
                'command_type' => CHECK_COMMAND,
            ],
        ];

        return $this->find($type, Hash::merge($_options, $options));
    }

    /**
     * Return all NOTIFICATION_COMMAND Objects
     *
     * @param string $type    for tha CakePHP find function (all, list, first, ...)
     * @param array  $options Options for find()
     *
     * @return array $ find result
     * @author Daniel Ziegler <daniel.ziegler@it-novum.com>
     * @since  3.0
     */
    public function notificationCommands($type = 'all', $options = [])
    {
        $_options = [
            'conditions' => [
                'command_type' => NOTIFICATION_COMMAND,
            ],
        ];

        return $this->find($type, Hash::merge($_options, $options));
    }

    /**
     * Return all EVENTHANDLER_COMMAND Objects
     *
     * @param string $type    for tha CakePHP find function (all, list, first, ...)
     * @param array  $options Options for find()
     *
     * @return array $ find result
     */
    public function eventhandlerCommands($type = 'all', $options = [])
    {
        $_options = [
            'conditions' => [
                'command_type' => EVENTHANDLER_COMMAND,
            ],
        ];

        return $this->find($type, Hash::merge($_options, $options));
    }

    /**
     * @param bool $created
     * @param array $options
     * @return bool|void
     */
    public function afterSave($created, $options = [])
    {
        parent::afterSave($created, $options);
        if ($created) {
            $this->lastInsertedData = array_map(function ($element) {
                if (isset($element['Commandargument'])) {
                    return $element['Commandargument'];
                }

                return $element;
            }, $this->lastInsertedData);
        }


        if ($this->DbBackend->isCrateDb() && isset($this->data['Command']['id'])) {
            if(isset($this->data['Command']['command_type']) && $this->data['Command']['command_type'] == NOTIFICATION_COMMAND) {
                //Save data also to CrateDB
                $CrateCommand = new \itnovum\openITCOCKPIT\Crate\CrateCommand($this->data['Command']['id']);
                $command = $this->find('first', $CrateCommand->getFindQuery());
                $CrateCommand->setDataFromFindResult($command);

                $CrateCommandModel = ClassRegistry::init('CrateModule.CrateCommand');
                $CrateCommandModel->save($CrateCommand->getDataForSave());
            }
        }


    }

    public function getConsoleWelcome($systemname)
    {
        return "This is a terminal connected to your ".$systemname." ".
            "Server, this is very powerful to test and debug plugins.\n".
            "User: \033[31mnagios\033[0m\nPWD: \033[35m/opt/openitc/nagios/libexec/\033[0m\n\n";
    }

    public function beforeDelete($cascade = true){
        $this->LastDeletedId = new LastDeletedId($this->id);
        return parent::beforeDelete($cascade);
    }

    public function afterDelete(){
        if($this->LastDeletedId !== null) {
            if ($this->DbBackend->isCrateDb() && $this->LastDeletedId->hasId()) {
                $CrateCommandModel = ClassRegistry::init('CrateModule.CrateCommand');
                $CrateCommandModel->delete($this->LastDeletedId->getId());
                $this->LastDeletedId = null;
            }
        }

        parent::afterDelete();
    }
}
