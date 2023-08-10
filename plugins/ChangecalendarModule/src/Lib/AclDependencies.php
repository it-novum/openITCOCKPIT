<?php
// Copyright (C) <2015-present>  <it-novum GmbH>
//
// This file is licensed under the terms of the openITCOCKPIT Enterprise Edition license agreement.
// The license agreement and license key were sent with the order confirmation.

namespace ChangecalendarModule\Lib;


use App\Lib\PluginAclDependencies;

class AclDependencies extends PluginAclDependencies {
    public function __construct() {
        parent::__construct();


        ///////////////////////////////
        //    Add dependencies       //
        //////////////////////////////
        $this
            ->dependency('Changecalendars', 'edit', 'Changecalendars', 'delete')
            ->dependency('Changecalendars', 'edit', 'Changecalendars', 'events')
            ->dependency('Changecalendars', 'edit', 'Changecalendars', 'add')
            ->dependency('Changecalendars', 'edit', 'Changecalendars', 'deleteEvent')
            ->dependency('Changecalendars', 'index', 'Changecalendars', 'widget');
    }
}
