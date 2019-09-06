<?php

namespace App\Model\Table;


use Cake\ORM\Table;

class ServiceescalationsServiceMembershipsTable extends Table {
    public function initialize(array $config) :void {
        $this->setTable('services_to_serviceescalations');
        $this->belongsTo('Services');
    }
}
