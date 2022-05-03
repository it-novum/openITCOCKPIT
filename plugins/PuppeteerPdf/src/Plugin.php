<?php
// Copyright (C) <2012>  <it-novum GmbH>
//
// Licensed under The MIT License
//

declare(strict_types=1);

namespace PuppeteerPdf;

use Cake\Core\BasePlugin;

/**
 * Plugin for PuppeteerPdf
 */
class Plugin extends BasePlugin {
    /**
     * Load routes or not
     *
     * @var bool
     */
    protected $routesEnabled = false;

    /**
     * Enable middleware
     *
     * @var bool
     */
    protected $middlewareEnabled = false;

    /**
     * Console middleware
     *
     * @var bool
     */
    protected $consoleEnabled = false;
}
