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

App.Controllers.ContactsAddController = Frontend.AppController.extend({
    components: ['Ajaxloader', 'CustomVariables'],

    _initialize: function(){
        var self = this;

        this.Ajaxloader.setup();
        self.CustomVariables.setup({
            controller: 'Contacts',
            ajaxUrl: 'Contacts/addCustomMacro',
            macrotype: 'CONTACT'
        });

        var timeperiodSelectors = [
            '#ContactHostTimeperiodId',
            '#ContactServiceTimeperiodId'
        ];

        $('#ContactHostPushNotificationsEnabled').change(function(){
            console.log('gfdgfddg');
            var selectedValues = $('#ContactHostCommands').val();
            if(selectedValues === null){
                selectedValues = [];
            }
            var newValues = [];
            var pushHostNotificationCommandId = '376';
            if($(this).prop('checked')){
                selectedValues.push(pushHostNotificationCommandId);
                $('#ContactHostCommands').val(selectedValues);
            }else{
                selectedValues.forEach(function(id){
                    if(id !== pushHostNotificationCommandId){
                        newValues.push(id);
                    }
                });
                $('#ContactHostCommands').val(newValues);
                //remove command from select box
            }
            $('#ContactHostCommands').trigger("chosen:updated");
        });

        $('#ContactServicePushNotificationsEnabled').change(function(){
            var selectedValues = $('#ContactServiceCommands').val();
            if(selectedValues === null){
                selectedValues = [];
            }
            var newValues = [];
            var pushServiceNotificationCommandId = '377';
            if($(this).prop('checked')){
                selectedValues.push(pushServiceNotificationCommandId);
                $('#ContactServiceCommands').val(selectedValues);
            }else{
                selectedValues.forEach(function(id){
                    if(id !== pushServiceNotificationCommandId){
                        newValues.push(id);
                    }
                });
                $('#ContactServiceCommands').val(newValues);
                //remove command from select box
            }
            $('#ContactServiceCommands').trigger("chosen:updated");
        });

        // Bind change event for Container Selectbox
        $('#ContainerContainer').change(function(){
            var containerIds = $(this).val();

            if(containerIds === null){
                for(var selectId in timeperiodSelectors){
                    var $timeperiodSelectbox = $(timeperiodSelectors[selectId]);
                    $timeperiodSelectbox.html('');
                    $timeperiodSelectbox.attr('data-placeholder', self.getVar('data_placeholder_empty'));
                    $timeperiodSelectbox.trigger("chosen:updated");
                }
                return;
            }

            if(containerIds.length > 0){
                self.Ajaxloader.show();
                $.ajax({
                    url: '/Contacts/loadTimeperiods/.json',
                    type: 'post',
                    cache: false,
                    data: {
                        container_ids: containerIds
                    },
                    dataType: 'json',
                    error: function(){
                    },
                    success: function(){
                    },
                    complete: function(response){
                        for(var selectId in timeperiodSelectors){
                            var $timeperiodSelectbox = $(timeperiodSelectors[selectId]);
                            $timeperiodSelectbox.html('');
                            $timeperiodSelectbox.attr('data-placeholder', self.getVar('data_placeholder_empty'));
                            var $timePeriods = response.responseJSON.timeperiods;

                            if(Object.keys($timePeriods).length > 0){
                                $timeperiodSelectbox.attr('data-placeholder', self.getVar('data_placeholder'));
                                for(var key in $timePeriods){
                                    $timeperiodSelectbox.append($('<option>', {
                                        value: $timePeriods[key].key,
                                        text: $timePeriods[key].value
                                    }));
                                }
                            }
                            $timeperiodSelectbox.trigger("chosen:updated");
                        }
                        self.Ajaxloader.hide()
                    }
                });

                $.ajax({
                    url: '/Contacts/loadUsersByContainerId/.json',
                    type: 'post',
                    cache: false,
                    data: {
                        container_ids: containerIds
                    },
                    dataType: 'json',
                    error: function(){
                    },
                    success: function(){
                    },
                    complete: function(response){
                        var $userSelectbox = $('#ContactUserId');
                        $userSelectbox.html('');
                        $userSelectbox.attr('data-placeholder', self.getVar('data_placeholder_empty'));
                        var $users = response.responseJSON.users;

                        if(Object.keys($users).length > 0){
                            $userSelectbox.attr('data-placeholder', self.getVar('data_placeholder'));
                            $userSelectbox.append($('<option>', {}));
                            for(var key in $users){
                                $userSelectbox.append($('<option>', {
                                    value: $users[key].key,
                                    text: $users[key].value
                                }));
                            }
                        }
                        $userSelectbox.trigger("chosen:updated");
                        self.Ajaxloader.hide()
                    }
                });
            }
        });
    }
});
