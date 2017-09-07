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

App.Controllers.HostgroupsEditController = Frontend.AppController.extend({
	$table: null,
	$hosts: null,

	components: ['Ajaxloader', 'ContainerSelectbox'],

	_initialize: function() {

		var self = this;

		this.Ajaxloader.setup();
		this.ContainerSelectbox.setup(this.Ajaxloader);
		// Bind change event for Container Selectbox
		this.ContainerSelectbox.addContainerEventListener({
			selectBoxSelector: '#ContainerParentId',
			ajaxUrl: '/hostgroups/loadHosts/:selectBoxValue:' + '.json',
            dataToSend: '#HostgroupHost',
			fieldTypes: {
				hosts: '#HostgroupHost',
			},
			dataPlaceholderEmpty: self.getVar('data_placeholder_empty'),
			dataPlaceholder: self.getVar('data_placeholder_host')
		});
        this.ContainerSelectbox.addContainerEventListener({
            selectBoxSelector: '#ContainerParentId',
            ajaxUrl: '/hostgroups/loadHosttemplates/:selectBoxValue:' + '.json',
            fieldTypes: {
                hosttemplates: '#HostgroupHosttemplate',
            },
            dataPlaceholderEmpty: self.getVar('data_placeholder_empty'),
            dataPlaceholder: self.getVar('data_placeholder_hosttemplate')
        });
	},
});

