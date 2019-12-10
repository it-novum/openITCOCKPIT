<?php
// Copyright (C) <2015>  <it-novum GmbH>
//
// This file is dual licensed
//
// 1.
//	This program is free software: you can redistribute it and/or modify
//	it under the terms of the GNU General Public License as published by
//	the Free Software Foundation, version 3 of the License.
//
//	This program is distributed in the hope that it will be useful,
//	but WITHOUT ANY WARRANTY; without even the implied warranty of
//	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//	GNU General Public License for more details.
//
//	You should have received a copy of the GNU General Public License
//	along with this program.  If not, see <http://www.gnu.org/licenses/>.
//

// 2.
//	If you purchased an openITCOCKPIT Enterprise Edition you can use this file
//	under the terms of the openITCOCKPIT Enterprise Edition license agreement.
//	License agreement and license key will be shipped with the order
//	confirmation.

/**
 * @var \App\View\AppView $this
 */

use App\Lib\Environments;
use Cake\Core\Configure;
use Cake\Filesystem\Folder;
use itnovum\openITCOCKPIT\Core\AngularJS\AngularAssets;

$AngularAssets = new AngularAssets();
$scripts = $AngularAssets->getJsFiles();

$appScripts = [];
if (ENVIRONMENT === Environments::PRODUCTION) {
    $compressedAngularControllers = WWW_ROOT . 'js' . DS . 'compressed_angular_controllers.js';
    $compressedAngularDirectives = WWW_ROOT . 'js' . DS . 'compressed_angular_directives.js';
    $compressedAngularServices = WWW_ROOT . 'js' . DS . 'compressed_angular_services.js';
    $compressedAngularStates = WWW_ROOT . 'js' . DS . 'compressed_angular_states.js';
    if (file_exists($compressedAngularControllers) && file_exists($compressedAngularDirectives) && file_exists($compressedAngularServices)) {
        $appScripts[] = str_replace(WWW_ROOT, '', $compressedAngularServices);
        $appScripts[] = str_replace(WWW_ROOT, '', $compressedAngularDirectives);
        $appScripts[] = str_replace(WWW_ROOT, '', $compressedAngularControllers);
        if (file_exists($compressedAngularStates)) {
            $appScripts[] = str_replace(WWW_ROOT, '', $compressedAngularStates);
        }
    }
} else {
    $core = new Folder(WWW_ROOT . 'js' . DS . 'scripts');
    $uncompressedAngular = str_replace(WWW_ROOT, '', $core->findRecursive('.*\.js'));
    foreach (\Cake\Core\Plugin::loaded() as $pluginName) {
        $plugin = new Folder(PLUGIN . $pluginName . DS . 'webroot' . DS . 'js' . DS . 'scripts');
        $filenames = str_replace(PLUGIN . $pluginName . DS . 'webroot' . DS, '', $plugin->findRecursive('.*\.js'));
        if (!empty($filenames)) {
            $fullPath = [];
            foreach ($filenames as $filename) {
                $fullPath[] = $pluginName . DS . $filename;
            }
            $uncompressedAngular = array_merge($uncompressedAngular, $fullPath);
        }
    }
    $appScripts = array_merge($appScripts, $uncompressedAngular);
}

?>
<!DOCTYPE html>
<html lang="en" ng-app="openITCOCKPIT">
<head>


    <!--[if IE]>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <![endif]-->
    <?php echo $this->Html->charset(); ?>

    <!--
                                ___ _____ ____ ___   ____ _  ______ ___ _____
          ___  _ __   ___ _ __ |_ _|_   _/ ___/ _ \ / ___| |/ /  _ \_ _|_   _|
         / _ \| '_ \ / _ \ '_ \ | |  | || |  | | | | |   | ' /| |_) | |  | |
        | (_) | |_) |  __/ | | || |  | || |__| |_| | |___| . \|  __/| |  | |
         \___/| .__/ \___|_| |_|___| |_| \____\___/ \____|_|\_\_|  |___| |_|
              |_|

                            Open Source Monitoring Solution

        Website: https://openitcockpit.io/
        GitHub: https://github.com/it-novum/openITCOCKPIT
    -->

    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script data-pace-options='{ "ajax": false }' src='/legacy/smartadmin/js/plugin/pace/pace.min.js'></script>
    <title>
        Monitoring powered by openITCOCKPIT
    </title>
    <?php
    $fileVersion = '?v' . time();
    if (ENVIRONMENT === Environments::PRODUCTION) {
        Configure::load('version');
        $fileVersion = '?v' . OPENITCOCKPIT_VERSION;
    }


    echo $this->Html->meta('icon');

    foreach ($scripts as $script):
        printf('<script src="/%s%s"></script>%s', $script, $fileVersion, PHP_EOL);
    endforeach;

    foreach ($appScripts as $appScript):
        printf('<script src="/%s%s"></script>%s', $appScript, $fileVersion, PHP_EOL);
    endforeach;

    foreach ($AngularAssets->getCssFiles() as $cssFile):
        printf('<link rel="stylesheet" type="text/css" href="%s%s">%s', $cssFile, $fileVersion, PHP_EOL);
    endforeach;
    ?>
</head>
<body ng-cloak class="ng-cloak">
<script>
    Dropzone.autoDiscover = false;
</script>

<div id="global-loading">
    <i class="fa fa-refresh fa-spin"></i>
</div>

<?= $this->element('header') ?>
<?= $this->element('sidebar') ?>

<div id="uglyDropdownMenuHack"></div>
<div id="main" role="main" ng-controller="LayoutController">
    <div id="ribbon" class="hidden-mobile hidden-tablet">
        <span class="ribbon-button-alignment"></span>
        <ol class="breadcrumb">
            <li>
                Hardcoded 1
            </li>
            <li>
                Hardcoded 2
            </li>
        </ol>
        <?php if ($showstatsinmenu): ?>
            <menustats></menustats>
        <?php endif; ?>

        <div class="pull-right">
            <span id="global_ajax_loader"><i class="fa fa-refresh fa-spin"></i> <?php echo __('Loading data'); ?></span>
        </div>

    </div>

    <div id="content" style="opacity: 1;">

        <div ui-view>
            <?= $this->Flash->render() ?>
            <?= $this->fetch('content') ?>
        </div>
    </div>
</div>

<div id="scroll-top-container">
    <i class="fa fa-arrow-up fa-2x" title="<?php echo __('Scroll back to top'); ?>"></i>
</div>

<?php printf('<script src="/%s"></script>', 'legacy/smartadmin/js/app.js'); ?>

</body>
</html>

