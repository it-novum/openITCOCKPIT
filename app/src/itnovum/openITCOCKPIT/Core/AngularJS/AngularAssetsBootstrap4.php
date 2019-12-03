<?php
// Copyright (C) <2018>  <it-novum GmbH>
//
// This file is dual licensed
//
// 1.
//    This program is free software: you can redistribute it and/or modify
//    it under the terms of the GNU General Public License as published by
//    the Free Software Foundation, version 3 of the License.
//
//    This program is distributed in the hope that it will be useful,
//    but WITHOUT ANY WARRANTY; without even the implied warranty of
//    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//    GNU General Public License for more details.
//
//    You should have received a copy of the GNU General Public License
//    along with this program.  If not, see <http://www.gnu.org/licenses/>.
//

// 2.
//    If you purchased an openITCOCKPIT Enterprise Edition you can use this file
//    under the terms of the openITCOCKPIT Enterprise Edition license agreement.
//    License agreement and license key will be shipped with the order
//    confirmation.

namespace itnovum\openITCOCKPIT\Core\AngularJS;


class AngularAssetsBootstrap4 implements AngularAssetsInterface {

    /**
     * @var array
     */
    private $jsFiles = [
        'vendor/node_modules/jquery/dist/jquery.min.js',
        'vendor/node_modules/jquery-ui-dist/jquery-ui.min.js',
        'vendor/node_modules/popper.js/dist/umd/popper.min.js',
        'vendor/node_modules/popper.js/dist/umd/popper-utils.min.js',
        'vendor/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js',
        'vendor/jquery-throttle-debounce/jquery.ba-throttle-debounce.min.js',
        'vendor/jquery-snippets/jquery-snippets.js',
        'vendor/node_modules/jquery-slimscroll/jquery.slimscroll.js',
        'vendor/node_modules/node-waves/dist/waves.js',
        'vendor/node_modules/angular/angular.min.js',
        'vendor/node_modules/angular-ui-router/release/angular-ui-router.min.js',
        'vendor/node_modules/jquery.cookie/jquery.cookie.js',
        'js/vendor/chosen.jquery.min.js',
       // 'vendor/node_modules/bootstrap-tagsinput/dist/bootstrap-tagsinput.js',
        'vendor/node_modules/bootstrap4-tagsinput/tagsinput.js',
        //'vendor/node_modules/jquery-tageditor/jquery.tag-editor.js',
        //'vendor/node_modules/jquery-tageditor/jquery.caret.min.js',
        'js/app/layoutfix.js',
        'js/lib/ColorGenerator.js',
        'js/lib/colr.js',
        'js/lib/php.js',
       /* 'smartadmin/js/plugin/flot/jquery.flot.cust.js',                        //legacy but no new alternative
        'vendor/node_modules/flot-axislabels/jquery.flot.axislabels.js',
        'vendor/node_modules/flot/jquery.flot.time.js',
        'vendor/node_modules/flot/jquery.flot.fillbetween.js',
        'vendor/node_modules/flot/jquery.flot.resize.js',
        'vendor/node_modules/flot/jquery.flot.threshold.js',
        'vendor/node_modules/flot/jquery.flot.selection.js',*/
        'vendor/node_modules/jquery-nestable/jquery.nestable.js',
        'js/lib/angular-nestable.js',
        'js/lib/parseuri.js',
        //'js/vendor/vis-4.21.0/dist/vis.js',
        'js/scripts/ng.app.js',
        'js/vendor/UUID.js-4.0.3/dist/uuid.core.js',
        'vendor/node_modules/underscore/underscore-min.js',
        'vendor/node_modules/noty/lib/noty.min.js',
        'js/vendor/gauge.min.js',
        'js/lib/jquery.svg.min.js',
        'js/lib/jquery.svgfilter.min.js',
        'vendor/node_modules/dropzone/dist/min/dropzone.min.js',
        /*'smartadmin/js/notification/SmartNotification.js',
        'vendor/node_modules/noty/lib/noty.min.js',
        'vendor/node_modules/rangyinputs/rangyinputs-jquery.js',
        'vendor/node_modules/javascript-detect-element-resize/jquery.resize.js',*/
        'vendor/node_modules/angular-gridster/dist/angular-gridster.min.js',
        'js/lib/GraphDefaults.js',
        /*'js/lib/jqconsole.min.js',
        'js/vendor/jquery.blockUI.js', */
        'vendor/node_modules/jquery-blockui/jquery.blockUI.js'
        /*'js/lib/jquery-jvectormap-1.2.2.min.js',
        'js/lib/maps/jquery-jvectormap-world-mill-en.js',
        'vendor/node_modules/@fullcalendar/core/main.js',
        'vendor/node_modules/@fullcalendar/interaction/main.js',
        'vendor/node_modules/@fullcalendar/daygrid/main.js',
        'vendor/node_modules/@fullcalendar/timegrid/main.js',
        'vendor/node_modules/@fullcalendar/list/main.js',
        'js/vendor/Chart.min.js',
        'js/vendor/chartjs-plugin-piechart-center-element.min.js',
        'js/lib/highlight-within-textarea/jquery.highlight-within-textarea.js' */
    ];

    /**
     * @return array
     */
    public function getJsFiles() {
        $jsFiles = $this->jsFiles;
        //Load Plugin configuration files
        $loadedModules = array_filter(\CakePlugin::loaded(), function ($value) {
            return strpos($value, 'Module') !== false;
        });

        foreach ($loadedModules as $loadedModule) {
            $file = OLD_APP . 'Plugin' . DS . $loadedModule . DS . 'Lib' . DS . 'AngularAssets.php';
            if (file_exists($file)) {
                require_once $file;
                $dynamicAngularAssets = sprintf('itnovum\openITCOCKPIT\%s\AngularAssets\AngularAssets', $loadedModule);
                $ModuleAngularAssets = new $dynamicAngularAssets();
                /** @var AngularAssetsInterface $ModuleAngularAssets */
                foreach ($ModuleAngularAssets->getJsFiles() as $jsFile) {
                    $jsFiles[] = $jsFile;
                }
            }
        }
        return $jsFiles;
    }

}