<?php

namespace TelegramModule\Lib;

use App\Lib\PluginAclDependencies;

class AclDependencies extends PluginAclDependencies {

    public function __construct() {
        parent::__construct();

        $this->allow('TelegramWebhook', 'notify');
    }
}
