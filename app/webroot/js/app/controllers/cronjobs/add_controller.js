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

App.Controllers.CronjobsAddController = Frontend.AppController.extend({

	components: ['Ajaxloader'],


	_initialize: function() {
		this.Ajaxloader.setup();
		var self = this;
		
		/*
		 * Bind change event on Plugin selectbox
		 */
		
		$('#CronjobPlugin').change(function(){
			var $this = $(this);
			var $taskSelect = $('#CronjobTask');
			self.Ajaxloader.show();
			var _this = self;
			$.ajax({
				url: "/cronjobs/loadTasksByPlugin/"+encodeURIComponent($this.val())+'.json',
				type: "GET",
				cache: false,
				error: function(){},
				success: function(){},
				complete: function(response){
					$taskSelect.html('');
					for(var key in response.responseJSON.tasks){
						$taskSelect.append('<option value="'+response.responseJSON.tasks[key]+'">'+response.responseJSON.tasks[key]+'</option>');
					}
					_this.Ajaxloader.hide();
				}
			});
		});
	}
});
