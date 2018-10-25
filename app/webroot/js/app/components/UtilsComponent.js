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

App.Components.UtilsComponent = Frontend.Component.extend({
    flappingIntervalObject: null,
    flappingInterval: 750,
    $flappingContainer: null,

    flapping: function(){
        this.$flappingContainer = $('.flapping_airport');

        if(this.$flappingContainer.length > 0){
            //var current_state_class = (this.$flappingContainer.parent().attr('class').split(' ').pop())?this.$flappingContainer.parent().attr('class').split(' ').pop():'';
            var i = 0;

            if(this.flappingIntervalObject != null){
                //Stop the old interval
                clearInterval(this.flappingIntervalObject);
            }

            this.flappingIntervalObject = setInterval(function(){
                if(i == 0){
                    //	this.$flappingContainer.html('<i class="fa fa-circle '+current_state_class+'"></i> <i class="fa fa-circle-o '+current_state_class+'"></i>');
                    this.$flappingContainer.html('<i class="fa fa-circle"></i> <i class="fa fa-circle-o"></i>');

                    i = 1;
                }else{
                    //	this.$flappingContainer.html('<i class="fa fa-circle-o '+current_state_class+'"></i> <i class="fa fa-circle '+current_state_class+'"></i>');
                    this.$flappingContainer.html('<i class="fa fa-circle-o"></i> <i class="fa fa-circle"></i>');

                    i = 0;
                }
            }.bind(this), this.flappingInterval);
        }
    },

    browserDatatables: function(){
        $('#host-list-datatables').dataTable({
            "bPaginate": false,
            "bFilter": false,
            "bInfo": false,
            "bStateSave": true
        });

        $('div.dataTables_filter')
            .attr('style', 'width: 100% !important;padding-right: 20px;')
            .children('.input-group')
            .attr('style', 'width: 100% !important;');
    }
});
