<?php

namespace App\Model\Table;


use Cake\ORM\Table;

class HostescalationsHostMembershipsTable extends Table {
    public function initialize(array $config) {
        $this->belongsTo('Hosts');
    }
}
