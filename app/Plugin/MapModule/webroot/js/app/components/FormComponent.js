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

App.Components.FormComponent = Frontend.Component.extend({

    configOverlayFormFields: function (type) {
        var formType;
        switch (type) {
            case 'host':
                var hostObj = {};

                //build up host Object for the dropdown values
                //$.each(this.getVar('hosts'),function(k,val){
                //	console.log(val);
                //	//hostObj[val.Host.uuid] = val.Host.name;
                //});
                formType = {
                    hostName: {
                        type: 'dropdown',
                        values: hostObj,
                    },
                    posX: {type: 'text'},
                    posY: {type: 'text'},
                    hoverChildLimit: {type: 'text'},
                    url: {type: 'text'}
                };
                break;
            case 'service':

                var hostObj = {};
                var serviceObj = {};

                //build up host Object for the dropdown values
                $.each(this.hosts, function (k, val) {
                    hostObj[val.Host.uuid] = val.Host.name;
                });

                $.each(this.services, function (k, val) {
                    //if name is empty -> take servicetemplatename
                    serviceObj[val.Service.uuid] = val.Service.name;
                });

                formType = {
                    hostName: {
                        type: 'dropdown',
                        values: hostObj,
                    },
                    serviceName: {
                        type: 'dropdown',
                        values: serviceObj
                    },
                    posX: {type: 'text'},
                    posY: {type: 'text'},
                    hoverChildLimit: {type: 'text'},
                    url: {type: 'text'}
                };
                break;
            case 'servicegroup':
                var servicegroupObj = {};
                //build up servicegroup Object for the dropdown values
                $.each(this.servicegroups, function (k, val) {
                    servicegroupObj[val.Servicegroup.uuid] = val.Servicegroup.description;
                });
                formType = {
                    servicegroupName: {
                        type: 'dropdown',
                        values: servicegroupObj
                    },
                    posX: {type: 'text'},
                    posY: {type: 'text'},
                    hoverChildLimit: {type: 'text'},
                    url: {type: 'text'}
                };
                break;
            case 'hostgroup':
                var hostgroupObj = {};
                //build up hostgroup Object for the dropdown values
                $.each(this.hostgroups, function (k, val) {
                    hostgroupObj[val.Hostgroup.uuid] = val.Hostgroup.description;
                });
                formType = {
                    hostgroupName: {
                        type: 'dropdown',
                        values: hostgroupObj
                    },
                    posX: {type: 'text'},
                    posY: {type: 'text'},
                    hoverChildLimit: {type: 'text'},
                    url: {type: 'text'},
                };
                break;
            default:
                //console.error('invalid type!');
                formType = {
                    error: 'error',
                }
                break;
        }
        return formType;
    },


    //generate HTML Form for Overlay
    htmlFormGenerator: function (obj) {
        //console.log(obj);
        //console.log(self.elementData);
        $('<div>', {
            id: 'genericFormContent'
        }).appendTo('#ElementWizardModalContent');

        var i = 0;
        $.each(obj, function (name, type) {
            $('<div>', {
                id: this.type + 'Row' + i,
                class: 'row genericFormContentRow',
            }).appendTo('#genericFormContent');

            switch (this.type) {
                case 'text':
                    $('<input>', {
                        id: name,
                        type: this.type,
                        class: 'col-md-6 genericFormContentField'
                    })
                        .appendTo('#' + this.type + 'Row' + i);
                    break;
                case 'dropdown':
                case 'chosen':

                    $('<div>', {
                        id: name + '_container',
                        class: 'col-md-6',
                        style: 'padding:0px'
                    }).appendTo('#' + this.type + 'Row' + i);

                    $('<select>', {
                        id: name,
                        type: this.type,
                        class: 'chosen genericFormContentField',
                    }).appendTo('#' + name + '_container');

                    //console.log(this);

                    //build up option list
                    if (('values' in this)) {
                        if (this.values != undefined) {
                            $.each(this.values, function (id, ObjName) {
                                $('<option>', {
                                    text: ObjName,
                                    id: id,
                                    value: id
                                }).appendTo('#' + name);
                            });
                        } else {
                            console.log('there are no entries');
                        }
                    } else {
                        console.log('es gibt keine values dazu');
                    }

                    $('#' + name).chosen();

                    $('#' + name + '_container')
                        .before($('<span>', {
                            id: name + '_text',
                            text: name,
                            class: 'col-md-3'
                        }));

                    $('.chosen-container').css('width', '100%');
                    break;
                default:
                    $('<div>', {
                        id: name,
                        type: this.type,
                        text: 'Type not found!',
                        class: 'col-md-6',
                    }).appendTo('#' + this.type + 'Row' + i);
                    break;
            }

            if (this.type != 'dropdown') {
                $('#' + name)
                    .before($('<span>', {
                        id: name + '_text',
                        text: name,
                        class: 'col-md-3'
                    }));
            }

            i++;
        })
    }
});