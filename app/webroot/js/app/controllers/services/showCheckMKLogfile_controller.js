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

App.Controllers.ServicesShowCheckMKLogfileController = Frontend.AppController.extend({

    components: ['Masschange','Ajaxloader'],

    arrayKeys: [],

    _initialize: function() {
        this.Ajaxloader.setup();
        this.Masschange.setup({
            'controller': 'services',
            'group': 'servicegroups',
            'checkboxattr': 'servicename',
            'storeUuidsAsArray': true
        });

        var self = this;

        $('#deleteAllSelectedEntries').click(function () {
            self.arrayKeys.length = 0;
            $('.massChange').each(function(arrayKeys){
                if($(this).prop('checked')){
                    console.log($(this).attr('array-index'));
                    self.arrayKeys.push($(this).attr('array-index'));
                }
            })
            console.log(self.arrayKeys);
            self.Ajaxloader.show();
            $.ajax({
                url: "/services/modifyCheckMkLogfile/.json",
                type: "POST",
                data: ({
                    host_uuid:$('#hostUuid').val(),
                    service_name:$('#serviceName').val(),
                    arrayKeys:self.arrayKeys
                }),
                error: function(){},
                success: function(){},
                complete: function(response){
                    self.Ajaxloader.hide();
                    location.reload();
                }.bind(self)
            });
        })
    }
});