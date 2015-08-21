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

App.Controllers.UsersEditController = Frontend.AppController.extend({
	_initialize: function(){
		var self = this;
		var $userContainer = $('#UserContainer');
		if(self.getVar('rights') !== null){
			$('#rightLevels').removeClass('hidden');
			$.each(self.getVar('rights'), function( index, value ){
				self.addRightLevel(index, value);
			});
		}
		$userContainer.change(function(evt, params){
			if($userContainer.val() !== null){
				$('#rightLevels').removeClass('hidden');
			}else{
				$('#rightLevels').addClass('hidden');
			}
			if(params.selected){
				self.addRightLevel(params.selected, 1);
			}else if(params.deselected){
				self.removeRightLevel(params.deselected);
			}
		});
	},
	
	addRightLevel: function(selectedOptionValue, level){
		var label = $("#UserContainer option[value='"+selectedOptionValue+"']").text();
		var disabled = false;
		if(label === '/root'){
			disabled = true;
			level = 2;//read/write for root container
		}
		var $rightRadios = $('<fieldset id="'+selectedOptionValue+'"></fieldset>')
		.prepend(
			$('<legend class="no-padding font-sm text-primary">'+label+'</legend>'),
			$('<input />')
				.attr({
					'type':'radio',
					'name': 'data[ContainerUserMembership]['+selectedOptionValue+']',
					'value': '1',
					'checked': (level & 1)?true:false,
					'id': 'read-'+selectedOptionValue,
					'disabled': disabled
				}),
			$('<label />')
				.attr({
					'for':'read-'+selectedOptionValue,
					'class':'padding-10 font-sm'
				}).text('read'),
			$('<input />')
				.attr({
					'type':'radio',
					'name': 'data[ContainerUserMembership]['+selectedOptionValue+']',
					'value': '2',
					'checked': (level & 2)?true:false,
					'id': 'write-'+selectedOptionValue
				}),
			$('<label />')
				.attr({
					'for':'write-'+selectedOptionValue,
					'class':'padding-10 font-sm'
				}).text('read/write')
		);
		$('#rightLevels').append($rightRadios).trigger('create');
	},
	
	removeRightLevel: function(deSelectedOptionValue){
		$('#'+deSelectedOptionValue).remove();
	}
});
