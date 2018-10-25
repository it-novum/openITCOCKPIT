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

App.Controllers.HostescalationsEditController = Frontend.AppController.extend({

    components: ['Ajaxloader', 'ContainerSelectbox'],

    _initialize: function(){
        var self = this;

        this.Ajaxloader.setup();
        this.ContainerSelectbox.setup(this.Ajaxloader);
        this.ContainerSelectbox.addContainerEventListener({
            selectBoxSelector: '#HostescalationContainerId',
            event: 'change.hostContainer',
            ajaxUrl: '/Hostescalations/loadElementsByContainerId/:selectBoxValue:.json',
            fieldTypes: {
                hosts: '#HostescalationHost',
                hostsExcluded: '#HostescalationHostExcluded',
                hostgroups: '#HostescalationHostgroup',
                hostgroupsExcluded: '#HostescalationHostgroupExcluded',
                timeperiods: '#HostescalationTimeperiodId',
                contacts: '#HostescalationContact',
                contactgroups: '#HostescalationContactgroup'
            },
            dataPlaceholderEmpty: self.getVar('data_placeholder_empty'),
            dataPlaceholder: self.getVar('data_placeholder')
        });

        $('[id^=HostescalationHost]').change(function(){
            $this = $(this);
            self.refreshHosts($this.val(), $this, $this.attr('target'));
        });
        if($('#HostescalationHost').val() !== null || $('#HostescalationHostExcluded').val() !== null){
            $('#HostescalationHost').children().each(function(intKey, OptionObject){
                if(in_array(OptionObject.value, $('#HostescalationHostExcluded').val())){
                    $OptionObject = $(OptionObject);
                    $OptionObject.prop('disabled', true);
                }
            });
            $('#HostescalationHostExcluded').children().each(function(intKey, OptionObject){
                if(in_array(OptionObject.value, $('#HostescalationHost').val())){
                    $OptionObject = $(OptionObject);
                    $OptionObject.prop('disabled', true);
                }
            });
            $('#HostescalationHost').trigger("chosen:updated");
            $('#HostescalationHostExcluded').trigger("chosen:updated");
        }
        if($('#HostescalationHostgroup').val() !== null || $('#HostescalationHostgroupExcluded').val() !== null){
            $('#HostescalationHostgroup').children().each(function(intKey, OptionObject){
                if(in_array(OptionObject.value, $('#HostescalationHostgroupExclude').val())){
                    $OptionObject = $(OptionObject);
                    $OptionObject.prop('disabled', true);
                }
            });
            $('#HostescalationHostgroupExcluded').children().each(function(intKey, OptionObject){
                if(in_array(OptionObject.value, $('#HostescalationHostgroup').val())){
                    $OptionObject = $(OptionObject);
                    $OptionObject.prop('disabled', true);
                }
            });
            $('#HostescalationHostgroup').trigger("chosen:updated");
            $('#HostescalationHostgroupExcluded').trigger("chosen:updated");
        }
    },

    refreshHosts: function(selected_hosts, selectboxObject, target){
        //Disable the selected option in $target selectbox, to avoid duplicate selections
        for(var key in selected_hosts){
            $(target).children().each(function(intKey, OptionObject){
                $OptionObject = $(OptionObject);
                if($OptionObject.val() == selected_hosts[key]){
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
            if(selected_hosts == null){
                selected_hosts = [];
            }

            if(!in_array($OptionObject.val(), selected_hosts) && !in_array($OptionObject.val(), targetValue)){
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
