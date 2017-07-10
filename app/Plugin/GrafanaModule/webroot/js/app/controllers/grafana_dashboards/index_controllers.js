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

App.Controllers.GrafanaDashboardsIndexController = Frontend.AppController.extend({

    components: ['Ajaxloader'],

    _initialize: function() {
        this.Ajaxloader.setup();
        var self = this;
        $('[id^=GrafanaDashboardConfigurationHostgroup]').change(function(){
            $this = $(this);
            self.refreshHostgroups($this.val(), $this, $this.attr('target'));
        });

        if($('#GrafanaDashboardConfigurationHostgroup').val() !== null || $('#GrafanaDashboardConfigurationHostgroupExcluded').val() !== null){
            $('#GrafanaDashboardConfigurationHostgroup').children().each(function(intKey, OptionObject){
                if(in_array(OptionObject.value, $('#GrafanaDashboardConfigurationHostgroupExcluded').val())){
                    $OptionObject = $(OptionObject);
                    $OptionObject.prop('disabled', true);
                }
            });
            $('#GrafanaDashboardConfigurationHostgroupExcluded').children().each(function(intKey, OptionObject){
                if(in_array(OptionObject.value, $('#GrafanaDashboardConfigurationHostgroup').val())){
                    $OptionObject = $(OptionObject);
                    $OptionObject.prop('disabled', true);
                }
            });
            $('#GrafanaDashboardConfigurationHostgroup').trigger("chosen:updated");
            $('#GrafanaDashboardConfigurationHostgroupExcluded').trigger("chosen:updated");
        }
    },
    refreshHostgroups: function(selected_hostgroups, selectboxObject, target){
        //Disable the selected option in $target selectbox, to avoid duplicate selections
        for (var key in selected_hostgroups){
            $(target).children().each(function(intKey, OptionObject){
                $OptionObject = $(OptionObject);
                if($OptionObject.val() == selected_hostgroups[key]){
                    //This is the option we need to disable
                    if(!$OptionObject.prop('disabled')){
                        $OptionObject.prop('disabled', true);
                    }
                }
            });
        }

        //Check if we need to re-enable something
        var targetValue = $(target).val();
        $(target).children().each(function(intKey, OptionObject){
            $OptionObject = $(OptionObject);
            if(targetValue == null){
                targetValue = [];
            }
            if(selected_hostgroups == null){
                selected_hostgroups = [];
            }

            if(!in_array($OptionObject.val(), selected_hostgroups) && !in_array($OptionObject.val(), targetValue)){
                //This is the option we need to enable
                if($OptionObject.prop('disabled')){
                    $OptionObject.prop('disabled', null);
                }
            }

        });

        //Update chosen
        $(target).trigger("chosen:updated");
    }
});
