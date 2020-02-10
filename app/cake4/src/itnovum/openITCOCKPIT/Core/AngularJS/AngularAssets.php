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


use Cake\Filesystem\Folder;

class AngularAssets implements AngularAssetsInterface {

    /**
     * @var array
     */
    private $jsFiles = [
        'node_modules/jquery/dist/jquery.min.js',
        'node_modules/jquery-ui-dist/jquery-ui.min.js',
        'node_modules/popper.js/dist/umd/popper.min.js',
        'node_modules/popper.js/dist/umd/popper-utils.min.js',
        'node_modules/bootstrap/dist/js/bootstrap.bundle.min.js',
        'legacy/smartadmin/js/plugin/throttle-debounce/jquery.ba-throttle-debounce.min.js',
        'js/jquery-snippets.js',
        'node_modules/jquery-slimscroll/jquery.slimscroll.js',
        'node_modules/node-waves/dist/waves.js',
        'node_modules/angular/angular.min.js',
        'node_modules/angular-ui-router/release/angular-ui-router.min.js',
        'node_modules/jquery.cookie/jquery.cookie.js',
        'legacy/js/vendor/chosen.jquery.min.js',
        'node_modules/bootstrap4-tagsinput/tagsinput.js',
        'legacy/js/app/layoutfix.js',
        'legacy/js/lib/ColorGenerator.js',
        'legacy/js/lib/colr.js',
        'legacy/js/lib/php.js',
        'legacy/smartadmin/js/plugin/flot/jquery.flot.cust.js', //legacy but no new alternative
        'node_modules/flot-axislabels/jquery.flot.axislabels.js',
        'node_modules/flot/jquery.flot.time.js',
        'node_modules/flot/jquery.flot.fillbetween.js',
        //'node_modules/flot/jquery.flot.pie.js',
        'node_modules/flot/jquery.flot.resize.js',
        //'node_modules/flot/jquery.flot.navigate.js',
        'node_modules/flot/jquery.flot.threshold.js',
        'node_modules/flot/jquery.flot.selection.js',
        'node_modules/jquery-nestable/jquery.nestable.js',
        'legacy/js/lib/angular-nestable.js',
        'legacy/js/lib/parseuri.js',
        //'js/vendor/vis-4.21.0/dist/vis.js',
        'js/scripts/ng.app.js',
        'legacy/js/vendor/UUID.js-4.0.3/dist/uuid.core.js',
        'node_modules/underscore/underscore-min.js',
        'node_modules/noty/lib/noty.min.js',
        'legacy/js/vendor/gauge.min.js',
        'legacy/js/lib/jquery.svg.min.js',
        'legacy/js/lib/jquery.svgfilter.min.js',
        'node_modules/dropzone/dist/min/dropzone.min.js',
        //'smartadmin/js/notification/SmartNotification.js',
        'node_modules/rangyinputs/rangyinputs-jquery.js',
        //'vendor/node_modules/javascript-detect-element-resize/jquery.resize.js',
        //'node_modules/javascript-detect-element-resize/jquery.resize.js',
        'node_modules/angular-gridster/dist/angular-gridster.min.js',
        'legacy/js/lib/GraphDefaults.js',
        'legacy/js/lib/jqconsole.min.js',
        'node_modules/jquery-blockui/jquery.blockUI.js',
        'legacy/js/lib/jquery-jvectormap-1.2.2.min.js',
        'legacy/js/lib/maps/jquery-jvectormap-world-mill-en.js',
        'node_modules/@fullcalendar/core/main.js',
        'node_modules/@fullcalendar/interaction/main.js',
        'node_modules/@fullcalendar/daygrid/main.js',
        'node_modules/@fullcalendar/timegrid/main.js',
        'node_modules/@fullcalendar/list/main.js',
        'node_modules/@fullcalendar/bootstrap/main.js',
        'node_modules/bootstrap-datepicker/dist/js/bootstrap-datepicker.js',
        /*'js/vendor/Chart.min.js',
        'js/vendor/chartjs-plugin-piechart-center-element.min.js', */
        'node_modules/highlight-within-textarea/jquery.highlight-within-textarea.js',
        'node_modules/jquery-sparkline/jquery.sparkline.min.js',
        'js/lib/raphael-charts-cust/raphael.js',
        'js/lib/raphael-charts-cust/raphael-charts-0.2.1.js',
        'js/lib/raphael-charts-cust/g.raphael.js',
        'js/lib/raphael-charts-cust/g.bar.js',
        'js/lib/raphael-charts-cust/pielicious.js'
    ];

    private $cssFiles = [

        /*   'lib/jquery.imgareaselect-0.9.10/imgareaselect-animated.css',
           'lib/jquery.svg.css',*/
        /*'/vendor/node_modules/bootstrap/dist/js/bootstrap.css',
        '/vendor/node_modules/bootstrap/dist/js/bootstrap-grid.css',*/
        //'vendor/jquery.gridster.css',
        /*'vendor/chosen/chosen',
        'vendor/chosen/chosen-bootstrap',*/
        /*'list_filter',
        'vendor/fineuploader/fineuploader-3.2',
        'vendor/select2/select2',
        'vendor/select2/select2-bootstrap',
        'vendor/bootstrap-datepicker.css',
        'vendor/bootstrap-datetimepicker.min.css',
        'vendor/gauge/css/gauge.css',*/
        '/node_modules/jquery-ui-dist/jquery-ui.css',
        '/node_modules/node-waves/dist/waves.css',
        '/node_modules/bootstrap4-tagsinput/tagsinput.css',
        '/node_modules/bootstrap4c-chosen/dist/css/component-chosen.css',
        '/node_modules/highlight-within-textarea/jquery.highlight-within-textarea.css',
        '/css/timeline.css',
        '/node_modules/dropzone/dist/min/dropzone.min.css',
        //  '/vendor/node_modules/jquery-tageditor/jquery.tag-editor.css',
        /*'/smartadmin4/dist/css/fa-brands.css',
        '/smartadmin4/dist/css/fa-regular.css',
        '/smartadmin4/dist/css/fa-solid.css', */
        '/smartadmin4/dist/css/vendors.bundle.css',
        '/smartadmin4/dist/css/app.bundle.css',
        '/smartadmin4/dist/css/themes/cust-theme-10.css',
        '/legacy/css/lib/jquery-jvectormap-1.2.2.css',
        '/node_modules/font-awesome/css/font-awesome.css',
        '/node_modules/@fortawesome/fontawesome-free/css/all.min.css',
        '/css/console.css',
        '/css/openitcockpit-colors.css',
        '/css/openitcockpit-utils.css',
        '/css/openitcockpit.css',
        '/node_modules/@fullcalendar/core/main.css',
        '/node_modules/@fullcalendar/daygrid/main.css',
        '/node_modules/@fullcalendar/timegrid/main.css',
        '/node_modules/@fullcalendar/list/main.css',
        '/node_modules/@fullcalendar/bootstrap/main.css',
        '/node_modules/flag-icon-css/css/flag-icon.css',
        //'/smartadmin/css/your_style.css',                             //check if we need this anymore
        //'/smartadmin/css/animate.css',
        '/node_modules/animate.css/animate.min.css',
        '/legacy/css/ansi.css',
        '/node_modules/bootstrap-datepicker/dist/css/bootstrap-datepicker.css',
        /*'lockscreen.css',
        'base',
        'app',
        'status',
        'lists',
        'animate_new',
        'vendor/prism.css',
        'vendor/gridstack/gridstack.min.css',*/
        //'vendor/vis-4.21.0/dist/vis.css',
        //'my_vis.css',
        '/node_modules/noty/lib/noty.css',
        '/node_modules/angular-flippy/dist/css/angular-flippy.min.css',
        'legacy/css/vendor/image-picker.css',
        '/node_modules/angular-gridster/dist/angular-gridster.min.css',
        //'vendor/radio_buttons.css',

    ];

    /**
     * @return array
     */
    public function getJsFiles() {
        $jsFiles = $this->jsFiles;
        //Load Plugin configuration files

        $Folder = new Folder(PLUGIN);
        $folders = $Folder->subdirectories();

        $loadedModules = array_filter($folders, function ($value) {
            return strpos($value, 'Module') !== false;
        });

        foreach ($loadedModules as $loadedModule) {
            $file = $loadedModule . DS . 'src' . DS . 'Lib' . DS . 'AngularAssets.php';
            if (file_exists($file)) {
                require_once $file;
                $moduleNameArray = explode('/', $loadedModule);
                $moduleName = array_pop($moduleNameArray);
                $dynamicAngularAssets = sprintf('itnovum\openITCOCKPIT\%s\AngularAssets\AngularAssets', $moduleName);
                $ModuleAngularAssets = new $dynamicAngularAssets();
                /** @var AngularAssetsInterface $ModuleAngularAssets */
                foreach ($ModuleAngularAssets->getJsFiles() as $jsFile) {
                    $jsFiles[] = $jsFile;
                }
            }
        }
        return $jsFiles;
    }

    /**
     * @return array
     */
    public function getCssFiles() {
        $cssFiles = $this->cssFiles;
        //Load Plugin configuration files

        $Folder = new Folder(PLUGIN);
        $folders = $Folder->subdirectories();

        $loadedModules = array_filter($folders, function ($value) {
            return strpos($value, 'Module') !== false;
        });

        foreach ($loadedModules as $loadedModule) {
            $file = $loadedModule . DS . 'src' . DS . 'Lib' . DS . 'AngularAssets.php';
            if (file_exists($file)) {
                require_once $file;
                $moduleNameArray = explode('/', $loadedModule);
                $moduleName = array_pop($moduleNameArray);
                $dynamicAngularAssets = sprintf('itnovum\openITCOCKPIT\%s\AngularAssets\AngularAssets', $moduleName);
                $ModuleAngularAssets = new $dynamicAngularAssets();
                /** @var AngularAssetsInterface $ModuleAngularAssets */
                foreach ($ModuleAngularAssets->getCssFiles() as $cssFile) {
                    $cssFiles[] = $cssFile;
                }
            }
        }
        return $cssFiles;
    }

}
