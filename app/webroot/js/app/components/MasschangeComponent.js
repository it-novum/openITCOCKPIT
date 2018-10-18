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

App.Components.MasschangeComponent = Frontend.Component.extend({
    massCount: 0,
    controller: '',
    group: '',
    checkboxattr: 'hostname',
    useDeleteMessage: true,
    extendUrl: '',

    storeUuidsAsArray: false,
    uuidsArray: [],

    selectedIds: [],
    selectedUuids: [],

    setup: function(conf){
        conf = conf || {};
        conf.controller = conf.controller || '';
        conf.group = conf.group || '';
        this.useDeleteMessage = conf.useDeleteMessage || this.useDeleteMessage;
        this.controller = conf.controller;
        this.group = conf.group;
        this.checkboxattr = conf.checkboxattr || this.checkboxattr;
        this.extendUrl = conf.extendUrl || this.extendUrl;
        this.storeUuidsAsArray = conf.storeUuidsAsArray || this.storeUuidsAsArray;
        var self = this;

        /*
         * Bind "Select all"
         */
        $('#selectAll').click(function(){
            self.selectedIds = [];
            self.selectedUuids = [];
            self.uuidsArray = [];
            $('.massChange').each(function(intIndex, checkboxObject){
                $(checkboxObject).prop('checked', true);
                self.addSelection($(checkboxObject).val(), $(checkboxObject).attr('uuid'));
                if(self.storeUuidsAsArray == true){
                    self.uuidsArray[$(checkboxObject).attr('uuid')] = $(checkboxObject).attr('host-uuid');
                }
            });
            self.showCount();
        });

        /*
         * Bind "Undo selection"
         */
        $('#untickAll').click(function(){
            $('.massChange').prop('checked', null);
            self.selectedIds = [];
            self.selectedUuids = [];
            self.uuidsArray = [];
            self.showCount();
        });

        /*
         * Check if there are some picked select boxes, browsers like firefox check them on page reload for example
         */
        $('.massChange').each(function(intIndex, checkboxObject){
            if($(checkboxObject).prop('checked') == true){
                self.addSelection($(checkboxObject).val(), $(checkboxObject).attr('uuid'));
                if(self.storeUuidsAsArray == true){
                    self.uuidsArray[$(checkboxObject).attr('uuid')] = $(checkboxObject).attr('host-uuid');
                }
            }
        });
        self.showCount();

        /*
         * Bind change event
         */
        $('.massChange').change(function(){
            var $clickedObject = $(this);
            if($clickedObject.prop('checked') == true){
                self.addSelection($clickedObject.val(), $clickedObject.attr('uuid'));
                if(self.storeUuidsAsArray == true){
                    self.uuidsArray[$clickedObject.attr('uuid')] = $clickedObject.attr('host-uuid');
                }
            }else{
                self.removeSelection($clickedObject.val(), $clickedObject.attr('uuid'));
            }

            self.showCount();
        });

    },

    addSelection: function(id, uuid){
        this.selectedIds.push(id);
        this.selectedUuids.push(uuid);
    },

    removeSelection: function(id, uuid){
        var index = this.selectedIds.indexOf(id);
        if(index != -1){
            this.selectedIds.splice(index, 1);
        }

        var index = this.selectedUuids.indexOf(uuid);
        if(index != -1){
            this.selectedUuids.splice(index, 1);
        }

        if(this.storeUuidsAsArray == true){
            if(typeof this.uuidsArray[uuid] != 'undefined'){
                delete this.uuidsArray[uuid];
            }
        }
    },

    showCount: function(){
        if(this.selectedIds.length > 0){
            $('#selectionCount').html('(' + this.selectedIds.length + ')');
        }else{
            $('#selectionCount').html('');
        }
        this.createDeleteAllHref();
        this.createEditDetailAllHref();
        this.createCopyAllHref();
        this.createDisableAllHref();
        this.createAppendGroupAllHref();
    },

    createDeleteAllHref: function(){
        if(this.selectedIds.length > 0){
            if(this.useDeleteMessage === true){
                var hostnames = this.fetchHostnames();
                var $yes = $('#message_yes');
                var $no = $('#message_no');
                $('#deleteAll').off('click').on('click', function(e){
                    SmartMSGboxCount = 0;
                    $.SmartMessageBox({
                        title: "<span class='text-danger'>" + $('#delete_message_h1').val() + "</span>",
                        sound: false,
                        sound_on: false,
                        content: $('#delete_message_h2').val() + hostnames,
                        buttons: '[' + $no.val() + '][' + $yes.val() + ']'
                    }, function(ButtonPressed){
                        if(ButtonPressed === $yes.val()){
                            window.location = '/' + this.controller + '/mass_delete/' + this.selectedIds.join('/') + this.extendUrl;
                        }else{
                            $('#MsgBoxBack').fadeOut();
                        }
                    }.bind(this));
                    //e.preventDefault();
                }.bind(this));
            }else{
                $('#deleteAll').attr('href', '/' + this.controller + '/mass_delete/' + this.selectedIds.join('/') + this.extendUrl);
            }


        }else{
            $('#deleteAll').attr('href', 'javascript:void(0);');
            $('#deleteAll').unbind('click');
        }
    },

    createDisableAllHref: function(){
        if(this.selectedIds.length > 0){
            var hostnames = this.fetchHostnames();
            var $yes = $('#message_yes');
            var $no = $('#message_no');
            $('#disableAll').click(function(e){
                $.SmartMessageBox({
                    title: "<span class='text-info'>" + $('#disable_message_h1').val() + "</span>",
                    sound: false,
                    sound_on: false,
                    content: $('#disable_message_h2').val() + hostnames,
                    buttons: '[' + $no.val() + '][' + $yes.val() + ']'
                }, function(ButtonPressed){
                    if(ButtonPressed === $yes.val()){
                        window.location = '/' + this.controller + '/mass_deactivate/' + this.selectedIds.join('/') + this.extendUrl;
                    }else{
                        $('#MsgBoxBack').fadeOut();
                    }
                }.bind(this));

                //e.preventDefault();
            }.bind(this));

        }else{
            $('#disableAll').attr('href', 'javascript:void(0);');
            $('#disableAll').unbind('click');
        }
    },

    createCopyAllHref: function(){
        if(this.selectedIds.length > 0){
            $('#copyAll').attr('href', '/' + this.controller + '/copy/' + this.selectedIds.join('/') + this.extendUrl);
            var mySelectedIds = this.selectedIds;
            var myController = this.controller;
            var myExtendUrl = this.extendUrl;
            $('.copyAll-too').each(function(){
                var myObj = $(this);
                myObj.attr('href', '/' + myController + '/' + myObj.attr('data-action') + '/' + mySelectedIds.join('/') + myExtendUrl);
            });
        }else{
            $('#copyAll').attr('href', 'javascript:void(0);');
            $('.copyAll-too').each(function(){
                var myObj = $(this);
                myObj.attr('href', 'javascript:void(0);');
            });
        }
    },

    createAppendGroupAllHref: function(){
        if(this.selectedIds.length > 0){
            $('#addToGroupAll').attr('href', '/' + this.group + '/mass_add/' + this.selectedIds.join('/') + this.extendUrl);
        }else{
            $('#addToGroupAll').attr('href', 'javascript:void(0);');
        }
    },

    createEditDetailAllHref: function(){
        if(this.selectedIds.length > 0){
            $('#editDetailAll').attr('href', '/' + this.controller + '/edit_details/' + this.selectedIds.join('/') + this.extendUrl);
        }else{
            $('#editDetailAll').attr('href', 'javascript:void(0);');
        }
    },

    fetchHostnames: function(){
        var html = '<br />';
        $('.massChange').each(function(intIndex, checkboxObject){
            if(this.selectedIds.indexOf($(checkboxObject).val()) != -1){
                html += ' - ' + $(checkboxObject).attr(this.checkboxattr) + '<br />';
            }
        }.bind(this));
        return html;
    }
});
