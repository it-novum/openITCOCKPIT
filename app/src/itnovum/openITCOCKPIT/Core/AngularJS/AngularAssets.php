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


class AngularAssets implements AngularAssetsInterface {

    /**
     * @var array
     */
    private $jsFiles = [
        'vendor/jquery/dist/jquery.min.js',
        'vendor/jqueryui/jquery-ui.min.js',
        'vendor/bootstrap/dist/js/bootstrap.min.js',
        'vendor/angular/angular.min.js',
        'vendor/angular-ui-router/release/angular-ui-router.min.js',
        'js/lib/jquery-cookie.js',
        'js/vendor/chosen.jquery.min.js',
        'js/plugin/bootstrap-tags/bootstrap-tagsinput.min.js',
        //'vendor/angular-bootstrap/ui-bootstrap-tpls.min.js',
        'js/app/layoutfix.js',
        'js/lib/ColorGenerator.js',
        'js/lib/colr.js',
        'js/lib/php.js',
        'smartadmin/js/plugin/flot/jquery.flot.cust.js',
        'smartadmin/js/plugin/flot/jquery.flot.axislabels.js',
        'smartadmin/js/plugin/flot/jquery.flot.time.js',
        //'smartadmin/js/plugin/jquery-validate/jquery.validate.min.js', //
        //'smartadmin/js/plugin/flot/jquery.flot.orderBar.js', //
        'smartadmin/js/plugin/flot/jquery.flot.fillbetween.js',
        //'smartadmin/js/plugin/flot/jquery.flot.pie.min.js', //
        'smartadmin/js/plugin/flot/jquery.flot.resize.js',
        //'smartadmin/js/plugin/flot/jquery.flot.navigate.js', //
        'smartadmin/js/plugin/flot/jquery.flot.threshold.js',
        'smartadmin/js/plugin/flot/jquery.flot.selection.js', //
        'js/lib/jquery.nestable.js',
        'js/lib/angular-nestable.js',
        'js/lib/parseuri.js',
        'js/vendor/vis-4.21.0/dist/vis.js',
        'js/scripts/ng.app.js',
        'js/vendor/UUID.js-4.0.3/dist/uuid.core.js',
        'js/vendor/lodash/vendor/underscore/underscore.js',
        'js/vendor/noty/noty.min.js',
        'js/vendor/gauge.min.js',
        'js/lib/jquery.svg.min.js',
        'js/lib/jquery.svgfilter.min.js',
        'smartadmin/js/plugin/dropzone/dropzone.min.js',
        'smartadmin/js/notification/SmartNotification.js',
        'vendor/noty/noty.min.js',
        'js/lib/rangyinputs-jquery-1.1.2.min.js',
        'vendor/javascript-detect-element-resize/jquery.resize.js',
        'vendor/angular-gridster/dist/angular-gridster.min.js',
        'js/lib/GraphDefaults.js',
        'js/lib/jqconsole.min.js',
        'js/vendor/jquery.blockUI.js',
        'js/lib/jquery-jvectormap-1.2.2.min.js',
        'js/lib/maps/jquery-jvectormap-world-mill-en.js',
        'smartadmin/js/plugin/fullcalendar-4.2.0/packages/core/main.js',               //  <-- NEW FC 4.2.0
        'smartadmin/js/plugin/fullcalendar-4.2.0/packages/interaction/main.js',        //  <-- NEW FC 4.2.0
        'smartadmin/js/plugin/fullcalendar-4.2.0/packages/daygrid/main.js',            //  <-- NEW FC 4.2.0
        'smartadmin/js/plugin/fullcalendar-4.2.0/packages/timegrid/main.js',           //  <-- NEW FC 4.2.0
        'smartadmin/js/plugin/fullcalendar-4.2.0/packages/list/main.js',               //  <-- NEW FC 4.2.0
        'js/vendor/Chart.min.js',
    //    'js/vendor/chartjs-plugin-piechart-outlabels.min.js'
        'js/lib/highlight-within-textarea/jquery.highlight-within-textarea.js'
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