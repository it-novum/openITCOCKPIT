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

App.Controllers.HostsEditDetailsController = Frontend.AppController.extend({

    _initialize: function(){

        $('#HostTags').tagsinput();

        /*
         * Bind change event on checkboxes
         */
        $('.parent_checkbox').change(function(){
            var $this = $(this);
            if($this.prop('checked')){
                var $input = $this.parents('.editHostDetailFormInput').children('.scope').find(':input');

                $($input).each(function(key, input){
                    $_input = $(input);
                    if($_input.hasClass('chosen')){
                        $_input.prop('disabled', false);
                        $_input.trigger("chosen:updated");
                    }else{
                        $_input.prop('disabled', false);
                    }
                });
            }else{
                var $input = $this.parents('.editHostDetailFormInput').children('.scope').find(':input');

                $($input).each(function(key, input){
                    $_input = $(input);
                    if($_input.hasClass('chosen')){
                        $_input.prop('disabled', true);
                        $_input.val('').removeAttr('selected');
                        $_input.trigger("chosen:updated");
                    }else{
                        $_input.val('').removeAttr('checked');
                        $_input.prop('disabled', true);
                    }
                });
            }
        });
    }
});
