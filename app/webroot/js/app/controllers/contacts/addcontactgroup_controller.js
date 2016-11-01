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

App.Controllers.ContactsAddContactgroupController = Frontend.AppController.extend({
	$table: null,
	$contacts: null,

	components: ['Ajaxloader'],

	_initialize: function() {
		this.Ajaxloader.setup();
		this.$contacts = $('#ContactId');
		var self = this;
		$('#ContainerParentId').change(function(){
			$this = $(this);
			self.loadContacts($this.val());
		});
	},
	
	loadContacts: function(container_id){
		this.Ajaxloader.show();
		$.ajax({
			url: "/contactgroups/loadContacts/"+encodeURIComponent(container_id)+'.json',
			type: "POST",
			cache: false,
			dataType: "json",
			error: function(){},
			success: function(){},
			complete: function(response){
				//empty the selectbox for the new results
				this.$contacts.html('');
				if(response.responseJSON.sizeof > 0){
					this.$contacts.attr('data-placeholder', this.getVar('data_placeholder'));
					for(var key in response.responseJSON.contacts){
						this.$contacts.append('<option value="'+key+'">'+response.responseJSON.contacts[key]+'</option>');
					}
				}else{
					this.$contacts.attr('data-placeholder', this.getVar('data_placeholder_empty'));
				}
				//Rerender the chosen selectbox
				this.$contacts.trigger("chosen:updated");
				this.Ajaxloader.hide();
			}.bind(this)
		});
	}
});
