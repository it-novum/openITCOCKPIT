<?php

namespace App\Model\Table;


use Cake\ORM\Table;

class ServiceescalationsServicegroupMembershipsTable extends Table {
    public function initialize(array $config) {
        $this->setTable('servicegroups_to_serviceescalations');
        $this->belongsTo('Servicegroups');
    }
}
