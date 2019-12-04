<?php
App::uses('ClassRegistry', 'Utility');
App::uses('CakeLogInterface', 'Log');

/**
 * Class DatabaseLog
 * @deprecated
 */
class DatabaseLog implements CakeLogInterface {

    /**
     * @var Changelog
     */
    public $Changelog = null;

    /**
     * Contruct the model class
     * @deprecated
     */
    public function __construct($options = []) {
        $this->Changelog = ClassRegistry::init('Changelog');
    }

    /**
     * Write the log to database
     * @deprecated
     */
    public function write($action, $serialized_data_string = '') {
        ///*, $objecttype_id, $user_id, $data, $name*/
        if (is_string($serialized_data_string) && $serialized_data = @unserialize($serialized_data_string)) {
            //$serialized_data = unserialize($serialized_data_string);
            $changelog = [
                'model'         => ucwords(Inflector::singularize($serialized_data['controller'])),
                'action'        => $serialized_data['action'],
                'object_id'     => $serialized_data['object_id'],
                'objecttype_id' => $serialized_data['objecttype_id'],
                'user_id'       => $serialized_data['user_id'],
                'data'          => serialize($serialized_data['data']),
                'name'          => $serialized_data['name'],
                'Container'     => [
                    'Container' => $serialized_data['container_id'],
                ],
            ];

            return $this->Changelog->saveAll($changelog);
        }

    }
}