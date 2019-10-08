<?php
$config = [
    'assets' => [
        'css_bs4' => [
            'lib/jquery-jvectormap-1.2.2.css',
            'lib/jquery.imgareaselect-0.9.10/imgareaselect-animated.css',
            'lib/jquery.svg.css',
            '/vendor/node_modules/bootstrap/dist/js/bootstrap.css',
            '/vendor/node_modules/bootstrap/dist/js/bootstrap-grid.css',
            //'vendor/jquery.gridster.css',
            'vendor/chosen/chosen',
            'vendor/chosen/chosen-bootstrap',
            'list_filter',
            'vendor/fineuploader/fineuploader-3.2',
            'vendor/select2/select2',
            'vendor/select2/select2-bootstrap',
            'vendor/bootstrap-datepicker.css',
            'vendor/bootstrap-datetimepicker.min.css',
            'vendor/gauge/css/gauge.css',
            '/vendor/node_modules/font-awesome/css/font-awesome.css',
            '/smartadmin4/dist/css/fa-brands.css',
            '/smartadmin4/dist/css/fa-regular.css',
            '/smartadmin4/dist/css/fa-solid.css',
            '/smartadmin4/dist/css/vendors.bundle.css',
            '/smartadmin4/dist/css/app.bundle.css',
            '/smartadmin4/dist/css/themes/cust-theme-10.css',
            '/vendor/node_modules/@fullcalendar/core/main.css',
            '/vendor/node_modules/@fullcalendar/daygrid/main.css',
            '/vendor/node_modules/@fullcalendar/timegrid/main.css',
            '/vendor/node_modules/@fullcalendar/list/main.css',
            //'/smartadmin/css/your_style.css',                             //check if we need this anymore
            //'/smartadmin/css/animate.css',

            'lockscreen.css',
            'base',
            'app',
            'status',
            'lists',
            'ansi',
            'console',
            'animate_new',
            'vendor/prism.css',
            'vendor/gridstack/gridstack.min.css',
            'vendor/vis-4.21.0/dist/vis.css',
            'my_vis.css',
            'vendor/noty/noty.css',
            'flippy.css',
            'vendor/image-picker.css',
            '/vendor/node_modules/angular-gridster/dist/angular-gridster.css',
            'vendor/radio_buttons.css',
            '/css/vendor/highlight-within-textarea/jquery.highlight-within-textarea.css'
        ],
        'css' => [
            'lib/jquery-jvectormap-1.2.2.css',
            'lib/jquery.imgareaselect-0.9.10/imgareaselect-animated.css',
            'lib/jquery.svg.css',
            'vendor/bootstrap/css/bootstrap.min',
            //'vendor/jquery.gridster.css',
            'vendor/chosen/chosen',
            'vendor/chosen/chosen-bootstrap',
            'list_filter',
            'vendor/fineuploader/fineuploader-3.2',
            'vendor/select2/select2',
            'vendor/select2/select2-bootstrap',
            'vendor/bootstrap-datepicker.css',
            'vendor/bootstrap-datetimepicker.min.css',
            'vendor/gauge/css/gauge.css',
            '/smartadmin/css/font-awesome.min.css',
            '/smartadmin/css/smartadmin-production.min.css',
            '/smartadmin/css/smartadmin-production-plugins.min.css',
            '/smartadmin/css/smartadmin-skins.css',
            '/smartadmin/css/demo.css',
//          '/smartadmin/js/plugin/fullcalendar-2.3.1/fullcalendar.min.css', <---- Old
            '/smartadmin/js/plugin/fullcalendar-4.2.0/packages/core/main.css',      // <-- NEW FC 4.2.0
            '/smartadmin/js/plugin/fullcalendar-4.2.0/packages/daygrid/main.css',   // <-- NEW FC 4.2.0
            '/smartadmin/js/plugin/fullcalendar-4.2.0/packages/timegrid/main.css',   // <-- NEW FC 4.2.0
            '/smartadmin/js/plugin/fullcalendar-4.2.0/packages/list/main.css',   // <-- NEW FC 4.2.0
            '/smartadmin/css/your_style.css',
            '/smartadmin/css/animate.css',
            'lockscreen.css',
            'base',
            'app',
            'status',
            'lists',
            'ansi',
            'console',
            'animate_new',
            'vendor/prism.css',
            'vendor/gridstack/gridstack.min.css',
            'vendor/vis-4.21.0/dist/vis.css',
            'my_vis.css',
            'vendor/noty/noty.css',
            'flippy.css',
            'vendor/image-picker.css',
            '/vendor/angular-gridster/dist/angular-gridster.css',
            'vendor/radio_buttons.css',
            '/css/vendor/highlight-within-textarea/jquery.highlight-within-textarea.css'
        ],

        'js'                        => [
            'lib/php.js',
            'lib/colr.js',
            'lib/ColorGenerator.js',
            'lib/GraphDefaults.js',
            'app/bootstrap',
            'app/layoutfix',
            'vendor/jquery.blockUI',
            'lib/ui_blocker',
            'lib/dialog',
            'lib/jquery.hotkeys.js',
            'lib/jqconsole.min.js',
            'vendor/bootstrap.min',
            'vendor/chosen.jquery.min',
            'vendor/moment-with-locales.min.js',
            'vendor/fineuploader/jquery.fineuploader-3.2',
            'vendor/select2.min.js',
            'vendor/bootstrap-datepicker.js',
            'vendor/bootstrap-datetimepicker.js',
            'plugin/bootstrap-slider/bootstrap-slider.min.js',
            'plugin/bootstrap-tags/bootstrap-tagsinput.min.js',

            '/smartadmin/js/notification/SmartNotification.js',
            '/smartadmin/js/demo.js',
            '/smartadmin/js/app.js',
            '/smartadmin/js/smartwidgets/jarvis.widget.js',
            '/smartadmin/js/plugin/easy-pie-chart/jquery.easy-pie-chart.min.js',
            '/smartadmin/js/plugin/sparkline/jquery.sparkline.min.js',
            '/smartadmin/js/plugin/flot/jquery.flot.cust.js',
            '/smartadmin/js/plugin/flot/jquery.flot.time.js',
            '/smartadmin/js/plugin/jquery-validate/jquery.validate.min.js',
            '/smartadmin/js/plugin/flot/jquery.flot.orderBar.js',
            '/smartadmin/js/plugin/flot/jquery.flot.fillbetween.js',
            '/smartadmin/js/plugin/flot/jquery.flot.pie.min.js',
            '/smartadmin/js/plugin/flot/jquery.flot.resize.js',
            '/smartadmin/js/plugin/flot/jquery.flot.navigate.js',
            '/smartadmin/js/plugin/flot/jquery.flot.threshold.js',
            '/smartadmin/js/plugin/flot/jquery.flot.selection.js',
            '/smartadmin/js/plugin/colorpicker/bootstrap-colorpicker.min.js',

            '/smartadmin/js/plugin/dropzone/dropzone.min.js',
            '/smartadmin/js/plugin/datatables/jquery.dataTables.js',
            '/smartadmin/js/plugin/datatables/dataTables.bootstrap.min.js',
            '/smartadmin/js/plugin/datatables/dataTables.colReorder.min.js',
            '/smartadmin/js/plugin/datatables/dataTables.colVis.min.js',
            '/smartadmin/js/plugin/datatables/dataTables.tableTools.min.js',
            '/smartadmin/js/plugin/datatables/fnPagingInfo.js',

//          '/smartadmin/js/plugin/fullcalendar-2.3.1/fullcalendar.min.js',                 <-- Old
            '/smartadmin/js/plugin/fullcalendar-4.2.0/packages/core/main.js',           //  <-- NEW FC 4.2.0
            '/smartadmin/js/plugin/fullcalendar-4.2.0/packages/interaction/main.js',    //  <-- NEW FC 4.2.0
            '/smartadmin/js/plugin/fullcalendar-4.2.0/packages/daygrid/main.js',        //  <-- NEW FC 4.2.0


            '/smartadmin/js/plugin/fuelux/wizard/wizard.js',
            'lib/jquery-cookie.js',
            'lib/jquery.nestable.js',
            'lib/jquery-jvectormap-1.2.2.min.js',
            'lib/maps/jquery-jvectormap-world-mill-en.js',
            'lib/jquery-ui-1.11.2.min.js',
            'lib/rangyinputs-jquery-1.1.2.min.js',
            'lib/jquery.imgareaselect-0.9.10/scripts/jquery.imgareaselect.min.js',
            'lib/jquery.svg.min.js',
            //'vendor/jquery.gridster.js',
            'vendor/bootbox.min.js',
            'lib/jquery.svgfilter.min.js',
            'vendor/jquery.qrcode.min.js',
            'vendor/jquery.blockUI.js',

            'vendor/prism.js',
            'vendor/gauge/gauge.min.js',
            'vendor/lodash/lodash.min.js',
            'vendor/gridstack/dist/gridstack.min.js',
            'lib/ChosenAjax.js',
            'vendor/UUID.js-4.0.3/dist/uuid.core.js',
        ],
        'jsFrontendPluginDependent' => [],
    ],
];

