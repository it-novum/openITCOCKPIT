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

        $this
            ->allow('ChangecalendarsController', 'index')
            ->allow('ChangecalendarsController', 'view')
            ->allow('ChangecalendarsController', 'add')
            ->allow('ChangecalendarsController', 'edit');


        ///////////////////////////////
        //    Add dependencies       //
        //////////////////////////////
        $this
            ->dependency('ChangecalendarsController', 'delete', 'ChangecalendarsController', 'edit')
            ->dependency('ChangecalendarsController', 'widget', 'ChangecalendarsController', 'index');
    }
}
