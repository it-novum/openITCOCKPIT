<?php

namespace App\Model\Table;


use Cake\ORM\Table;

class HostescalationsHostgroupMembershipsTable extends Table {
    public function initialize(array $config) {
        $this->belongsTo('Hostgroups');
    }
}
