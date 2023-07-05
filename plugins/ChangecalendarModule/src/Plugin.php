<?php
// Copyright (C) <2015-present>  <it-novum GmbH>
//
// This file is licensed under the terms of the openITCOCKPIT Enterprise Edition license agreement.
// The license agreement and license key were sent with the order confirmation.

declare(strict_types=1);


namespace ChangecalendarModule;

use Cake\Core\BasePlugin;
use Cake\Core\PluginApplicationInterface;
use Cake\Routing\RouteBuilder;

/**
 * Plugin for ChangecalendarModule
 */
class Plugin extends BasePlugin {

    /**
     * Add routes for the plugin.
     *
     * If your plugin has many routes and you would
     * like to isolate them into a separate file, you can create
     * `$plugin/config/routes.php` and delete this method.
     *
     * @param \Cake\Routing\RouteBuilder $routes The route builder to update.
     * @return void
     */
    public function routes(RouteBuilder $routes): void {
        $routes->plugin(
            'ChangecalendarModule',
            ['path' => '/changecalendar_module'],
            function (RouteBuilder $builder) {
                // Add custom routes here

                $builder->fallbacks();
            }
        );
    }
}
