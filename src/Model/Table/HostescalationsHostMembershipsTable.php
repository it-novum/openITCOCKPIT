<?php

namespace App\Model\Table;


use Cake\ORM\Table;

class HostescalationsHostMembershipsTable extends Table {
    public function initialize(array $config) :void {
        $this->setTable('hosts_to_hostescalations');
        $this->belongsTo('Hosts');
    }
}
