<?php

namespace App\Model\Table;


use Cake\ORM\Table;

class ServicedependenciesServiceMembershipsTable extends Table {
    public function initialize(array $config) {
        $this->setTable('services_to_servicedependencies');
        $this->belongsTo('Services');
    }
}
