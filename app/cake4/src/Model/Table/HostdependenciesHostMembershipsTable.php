<?php

namespace App\Model\Table;


use Cake\ORM\Table;

class HostdependenciesHostMembershipsTable extends Table {
    public function initialize(array $config) :void {
        $this->setTable('hosts_to_hostdependencies');
        $this->belongsTo('Hosts');
    }
}
