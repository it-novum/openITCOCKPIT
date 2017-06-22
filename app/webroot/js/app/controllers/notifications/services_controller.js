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

App.Controllers.NotificationsServicesController = Frontend.AppController.extend({
    $table: null,

    _initialize: function () {
        var self = this;
        //this.showHostOrService(this.getVar('HostOrService'));
        $('.select_datatable').click(function () {
            self.fnShowHide($(this).attr('my-column'), $(this).children());
        });

        var highestTime = 0, highestValue, pageUrl, dataTableValue, dataTableValueParsed;
        for (var i = 0, len = localStorage.length; i < len; ++i) {
            pageUrl = localStorage.key(i);
            dataTableValue = localStorage.getItem(pageUrl);
            if (typeof dataTableValue == 'undefined' || dataTableValue == 'undefined') continue;
            dataTableValueParsed = JSON.parse(dataTableValue);
            if (pageUrl.indexOf('DataTables_notification_list_/notifications') !== -1) {
                if (dataTableValueParsed.time > highestTime) {
                    highestTime = dataTableValueParsed.time;
                    highestValue = dataTableValue;
                }
            }
        }

        self.setDataTableFilter(highestValue);

        $('#notification_list').dataTable({
            "bSort": false,
            "bPaginate": false,
            "bFilter": false,
            "bInfo": false,
            "bStateSave": true,
            "aoColumnDefs": [{
                "bSortable": false,
                "aTargets": ["no-sort"]
            }],
            "fnInitComplete": function (dtObject) {
                var vCols = [];
                var $checkboxObjects = $('.select_datatable');

                //Enable all checkboxes
                $('.select_datatable').find('input').prop('checked', true);

                $.each(dtObject.aoColumns, function (count) {
                    if (dtObject.aoColumns[count].bVisible == false) {
                        //Uncheck checkboxes of hidden colums
                        $checkboxObjects.each(function (intKey, object) {
                            var $object = $(object);
                            if ($object.attr('my-column') == count) {
                                var $input = $(object).find('input');
                                $input.prop('checked', false);
                            }
                        });
                    }
                });
            }
        });

        this.$table = $('#notification_list');


        /*
         * Bind listoptions events
         */
        $('.listoptions_action').click(function () {
            $this = $(this);
            // Set selected value in "fance selectbox"
            $($this.attr('selector')).html($this.html());
            // Set selected value in hidden field, for HTLM submit
            $($this.attr('submit_target')).val($this.attr('value'));
            //self.showHostOrService($this.attr('value'));
        });

        /*
         * Bind click evento to .listoptions_checkbox to make a `<a />` to a label
         */
        $('.listoptions_checkbox').click(function (event) {
            $this = $(this);
            if (event.target == event.currentTarget) {
                $checkbox = $this.find(':checkbox');
                // Lets make t `<a />` to an 'label'
                if ($checkbox.prop('checked') == true) {
                    // Checkbox is enabled, so we need to remove the 'check'
                    $checkbox.prop('checked', false);
                } else {
                    // Checkbox is disabled, so we set the 'check'
                    $checkbox.prop('checked', true);
                }
            }
        });

    },
    fnShowHide: function (iCol, inputObject) {
        /* Get the DataTables object again - this is not a recreation, just a get of the object */
        var oTable = this.$table.dataTable();

        var bVis = oTable.fnSettings().aoColumns[iCol].bVisible;
        if (bVis == true) {
            inputObject.prop('checked', false);
        } else {
            inputObject.prop('checked', true);
        }
        oTable.fnSetColumnVis(iCol, bVis ? false : true);
    },

    showHostOrService: function (value) {
        // NOT IMPLEMENTED YET!
        value = value || '';

        //The user changed something in the "select box"
        if (in_array(value, ['hostOnly', 'serviceOnly'])) {
            if (value == 'hostOnly') {
                $('.ServiceNotificationStateTypse').hide();
                $('.HostNotificationStateTypse').show();
            } else {
                $('.HostNotificationStateTypse').hide();
                $('.ServiceNotificationStateTypse').show();
            }
        }

    },
    setDataTableFilter: function (storageValue) {
        var currentURL = window.location.href;
        var postTextURL = currentURL.substring(currentURL.indexOf('notifications') + 13);
        localStorage.setItem('DataTables_notification_list_/notifications' + postTextURL, storageValue);
    }
});