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

App.Controllers.ContainersIndexController = Frontend.AppController.extend({
    $table: null,
    /**
     * @constructor
     * @return {void}
     */

    components: ['Ajaxloader'],

    _initialize: function() {
        this.Ajaxloader.setup();
        /*
         * We need to submit the selectbox the laod the data ob the selected tenant
         */
        $(function(){
            $('.select_tenant').trigger('change');
        });



        /*
         * Loading jQuery Select2 Plugin
         */
        this.runSelect();

        /*
         * Binding events
         */
        var _this = this;
        $('.select_tenant').on('change', function(e){
            _this.Ajaxloader.show();
            var $self = $(this);
            var selectedId = $self.val();
            if(selectedId != '' && selectedId > 0){
                $('#ContainerSelectedTenant').val(selectedId);
                ret = $.ajax({
                    url: "/containers/byTenant/"+encodeURIComponent(selectedId),
                    type: "POST",
                    cache: false,
                    error: function(){},
                    success: function(){},
                    complete: function(response){
                        var __this = _this;
                        $('#ajax_result').append(response.responseText);
                        $.ajax({
                            url: "/containers/byTenantForSelect/"+encodeURIComponent(selectedId),
                            type: "POST",
                            error: function(){},
                            success: function(){},
                            complete: function(response){
                                $('#ajax_parent_nodes').html(response.responseText);
                                $('.select_path').chosen({
                                    width: '100%',
                                });
                                __this.Ajaxloader.hide();
                            }
                        });
                        $('#nestable').nestable({
                            noDragClass: 'dd-nodrag',
                    });
                    }
                });
            }
        });
    },

    runSelect: function(){
        if ($.fn.select2) {
            $('.select2').each(function() {
                $this = $(this);
                var width = $this.attr('data-select-width') || '100%';
                $this.select2({
                    allowClear : true,
                    width : width,
                    placeholder: 'Please select'
                })
            })
        }
    }
});