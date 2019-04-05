<?php

namespace App\Model\Table;


use Cake\ORM\Table;

class HostdependenciesHostgroupMembershipsTable extends Table {
    public function initialize(array $config) {
        $this->setTable('hostgroups_to_hostdependencies');
        $this->belongsTo('Hostgroups');
    }
}
