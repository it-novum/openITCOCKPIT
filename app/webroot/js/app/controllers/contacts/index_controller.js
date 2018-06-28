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

App.Controllers.ContactsIndexController = Frontend.AppController.extend({
    $table: null,
    /**
     * @constructor
     * @return {void}
     */

    components: ['Masschange'],

    _initialize: function (){
        var self = this;
        this.Masschange.setup({
            'controller': 'contacts',
            'checkboxattr': 'contactname',
            'useDeleteMessage': 'false'
        });


        /*
         * Fix chosen width, if rendered in a filter list
         */
        $(".oitc-list-filter").click(function(){
            $('.chosen-container').css('width', '100%');
        });

        // test fancyCheckbox stuff
        $('.onoffswitch-checkbox').each(function(i){
            if(i % 2 === 0){
                $(this).trigger('click');
            }
        });
    }

});