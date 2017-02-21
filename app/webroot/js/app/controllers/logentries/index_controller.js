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

App.Controllers.LogentriesIndexController = Frontend.AppController.extend({
    $table: null,

    _initialize: function () {
        var self = this;
        $('.select_datatable').click(function () {
            self.fnShowHide($(this).attr('my-column'), $(this).children());
        });

        $('#logentries_list').dataTable({
            "bPaginate": false,
            "bFilter": false,
            "bInfo": false,
            "ordering": false,
            "bStateSave": true,
            "aoColumnDefs": [{
                "bSortable": false,
                "aTargets": ["no-sort"]
            }]
        });

        this.$table = $('#logentries_list');

        //Mark checkbox as checked
        $('.select_datatable').find('input').prop('checked', true);

        /*
         * Bind listoptions events
         */
        $('.listoptions_action').click(function () {
            $this = $(this);
            // Set selected value in "fance selectbox"
            $($this.attr('selector')).html($this.html());
            // Set selected value in hidden field, for HTLM submit
            $($this.attr('submit_target')).val($this.attr('value'));
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
            event.stopPropagation();
        });

        /*
         * Bind click event for .tick_all
         */
        $('.tick_all').click(function () {
            $checkboxes = $(this).parent().parent().find(':checkbox');
            $checkboxes.each(function (intIndex, checkboxObject) {
                $(checkboxObject).prop('checked', true);
            });
        });

        /*
         * Bind click event for .untick_all
         */
        $('.untick_all').click(function () {
            $checkboxes = $(this).parent().parent().find(':checkbox');
            $checkboxes.each(function (intIndex, checkboxObject) {
                $(checkboxObject).prop('checked', false);
            });
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
    }
});