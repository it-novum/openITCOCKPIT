<?php

namespace App\Model\Table;


use Cake\ORM\Table;

class ServicedependenciesServicegroupMembershipsTable extends Table {
    public function initialize(array $config) {
        $this->setTable('servicegroups_to_servicedependencies');
        $this->belongsTo('Servicegroups');
    }
}
