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
        'legacy/vendor/jquery/dist/jquery.min.js',
        'legacy/vendor/jqueryui/jquery-ui.min.js',
        'legacy/vendor/bootstrap/dist/js/bootstrap.min.js',
        'legacy/vendor/angular/angular.min.js',
        'legacy/vendor/angular-ui-router/release/angular-ui-router.min.js',
        'legacy/js/lib/jquery-cookie.js',
        'legacy/js/vendor/chosen.jquery.min.js',
        'legacy/js/plugin/bootstrap-tags/bootstrap-tagsinput.min.js',
        //'legacy/vendor/angular-bootstrap/ui-bootstrap-tpls.min.js',
        'legacy/js/app/layoutfix.js',
        'legacy/js/lib/ColorGenerator.js',
        'legacy/js/lib/colr.js',
        'legacy/js/lib/php.js',
        'legacy/smartadmin/js/plugin/flot/jquery.flot.cust.js',
        'legacy/smartadmin/js/plugin/flot/jquery.flot.axislabels.js',
        'legacy/smartadmin/js/plugin/flot/jquery.flot.time.js',
        //'legacy/smartadmin/js/plugin/jquery-validate/jquery.validate.min.js', //
        //'legacy/smartadmin/js/plugin/flot/jquery.flot.orderBar.js', //
        'legacy/smartadmin/js/plugin/flot/jquery.flot.fillbetween.js',
        //'legacy/smartadmin/js/plugin/flot/jquery.flot.pie.min.js', //
        'legacy/smartadmin/js/plugin/flot/jquery.flot.resize.js',
        //'legacy/smartadmin/js/plugin/flot/jquery.flot.navigate.js', //
        'legacy/smartadmin/js/plugin/flot/jquery.flot.threshold.js',
        'legacy/smartadmin/js/plugin/flot/jquery.flot.selection.js', //
        'legacy/js/lib/jquery.nestable.js',
        'legacy/js/lib/angular-nestable.js',
        'legacy/js/lib/parseuri.js',
        'legacy/js/vendor/vis-4.21.0/dist/vis.js',
        'legacy/js/vendor/UUID.js-4.0.3/dist/uuid.core.js',
        'legacy/js/vendor/lodash/vendor/underscore/underscore.js',
        'legacy/js/vendor/noty/noty.min.js',
        'legacy/js/vendor/gauge.min.js',
        'legacy/js/lib/jquery.svg.min.js',
        'legacy/js/lib/jquery.svgfilter.min.js',
        'legacy/smartadmin/js/plugin/dropzone/dropzone.min.js',
        'legacy/smartadmin/js/notification/SmartNotification.js',
        'legacy/vendor/noty/noty.min.js',
        'legacy/js/lib/rangyinputs-jquery-1.1.2.min.js',
        'legacy/vendor/javascript-detect-element-resize/jquery.resize.js',
        'legacy/vendor/angular-gridster/dist/angular-gridster.min.js',
        'legacy/js/lib/GraphDefaults.js',
        'legacy/js/lib/jqconsole.min.js',
        'legacy/js/vendor/jquery.blockUI.js',
        'legacy/js/lib/jquery-jvectormap-1.2.2.min.js',
        'legacy/js/lib/maps/jquery-jvectormap-world-mill-en.js',
        'legacy/smartadmin/js/plugin/fullcalendar-4.2.0/packages/core/main.js',               //  <-- NEW FC 4.2.0
        'legacy/smartadmin/js/plugin/fullcalendar-4.2.0/packages/interaction/main.js',        //  <-- NEW FC 4.2.0
        'legacy/smartadmin/js/plugin/fullcalendar-4.2.0/packages/daygrid/main.js',            //  <-- NEW FC 4.2.0
        'legacy/smartadmin/js/plugin/fullcalendar-4.2.0/packages/timegrid/main.js',           //  <-- NEW FC 4.2.0
        'legacy/smartadmin/js/plugin/fullcalendar-4.2.0/packages/list/main.js',               //  <-- NEW FC 4.2.0
        'legacy/js/vendor/Chart.min.js',
        'legacy/js/vendor/chartjs-plugin-piechart-center-element.min.js',
         //'legacyjs/vendor/chartjs-plugin-piechart-outlabels.min.js'
        'legacy/js/lib/highlight-within-textarea/jquery.highlight-within-textarea.js',


        'js/scripts/ng.app.js',
    ];

    private $cssFiles = [
        '/legacy/css/lib/jquery-jvectormap-1.2.2.css',
        '/legacy/css/lib/jquery.imgareaselect-0.9.10/imgareaselect-animated.css',
        '/legacy/css/lib/jquery.svg.css',
        '/legacy/css/vendor/bootstrap/css/bootstrap.min.css',
        //'legacy/css/vendor/jquery.gridster.css',
        '/legacy/css/vendor/chosen/chosen.css',
        '/legacy/css/vendor/chosen/chosen-bootstrap.css',
        '/legacy/css/list_filter.css',
        '/legacy/css/vendor/fineuploader/fineuploader-3.2.css',
        '/legacy/css/vendor/select2/select2.css',
        '/legacy/css/vendor/select2/select2-bootstrap.css',
        '/legacy/css/vendor/bootstrap-datepicker.css',
        '/legacy/css/vendor/bootstrap-datetimepicker.min.css',
        '/legacy/css/vendor/gauge/css/gauge.css',
        '/legacy/smartadmin/css/font-awesome.min.css',
        '/legacy/smartadmin/css/smartadmin-production.min.css',
        '/legacy/smartadmin/css/smartadmin-production-plugins.min.css',
        '/legacy/smartadmin/css/smartadmin-skins.css',
        '/legacy/smartadmin/css/demo.css',
        //'legacy/smartadmin/js/plugin/fullcalendar-2.3.1/fullcalendar.min.css', <---- Old
        '/legacy/smartadmin/js/plugin/fullcalendar-4.2.0/packages/core/main.css',      // <-- NEW FC 4.2.0
        '/legacy/smartadmin/js/plugin/fullcalendar-4.2.0/packages/daygrid/main.css',   // <-- NEW FC 4.2.0
        '/legacy/smartadmin/js/plugin/fullcalendar-4.2.0/packages/timegrid/main.css',   // <-- NEW FC 4.2.0
        '/legacy/smartadmin/js/plugin/fullcalendar-4.2.0/packages/list/main.css',   // <-- NEW FC 4.2.0
        '/legacy/smartadmin/css/your_style.css',
        '/legacy/smartadmin/css/animate.css',
        '/legacy/css/lockscreen.css',
        '/legacy/css/base.css',
        '/legacy/css/app.css',
        '/legacy/css/status.css',
        '/legacy/css/lists.css',
        '/legacy/css/ansi.css',
        '/legacy/css/console.css',
        '/legacy/css/animate_new.css',
        '/legacy/css/vendor/prism.css',
        '/legacy/css/vendor/gridstack/gridstack.min.css',
        '/legacy/css/vendor/vis-4.21.0/dist/vis.css',
        '/legacy/css/my_vis.css',
        '/legacy/css/vendor/noty/noty.css',
        '/legacy/css/flippy.css',
        '/legacy/css/vendor/image-picker.css',
        '/legacy/vendor/angular-gridster/dist/angular-gridster.css',
        '/legacy/css/vendor/radio_buttons.css',
        '/legacy/css/vendor/highlight-within-textarea/jquery.highlight-within-textarea.css'
    ];

    /**
     * @return array
     */
    public function getJsFiles() {
        $jsFiles = $this->jsFiles;
        //Load Plugin configuration files

        $Folder = new Folder(PLUGIN);
        $folders = $Folder->find();

        $loadedModules = array_filter($folders, function ($value) {
            return strpos($value, 'Module') !== false;
        });

        foreach ($loadedModules as $loadedModule) {
            $file = PLUGIN . $loadedModule . DS . 'Lib' . DS . 'AngularAssets.php';
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

    /**
     * @return array
     */
    public function getCssFiles() {
        $cssFiles = $this->cssFiles;
        //Load Plugin configuration files

        $Folder = new Folder(PLUGIN);
        $folders = $Folder->find();

        $loadedModules = array_filter($folders, function ($value) {
            return strpos($value, 'Module') !== false;
        });

        foreach ($loadedModules as $loadedModule) {
            $file = PLUGIN . $loadedModule . DS . 'Lib' . DS . 'AngularAssets.php';
            if (file_exists($file)) {
                require_once $file;
                $dynamicAngularAssets = sprintf('itnovum\openITCOCKPIT\%s\AngularAssets\AngularAssets', $loadedModule);
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