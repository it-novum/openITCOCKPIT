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

App.Controllers.MacrosIndexController = Frontend.AppController.extend({
	
	macroNames: null,
	
	components: ['Ajaxloader'],
	
	_initialize: function() {
		this.Ajaxloader.setup();
		var self = this;
	
	
		$('.addMacro').click(function(){
			this.addMacro();
		}.bind(this));
		
		$(document).on('click', '.deleteMacro', function(){
			$(this).parent().parent().remove();
			self.updateMacroNames();
		});
		
	},
	
	addMacro: function(){
		this.Ajaxloader.show();
		this.updateMacroNames();
		this.$button = $('.addMacro');
		this.$button.prop('disabled', true);
		$.ajax({
				url: "/Macros/addMacro/",
				type: "POST",
				data: this.macroNames,
				error: function(){},
				success: function(){},
				complete: function(response){
					$('#macrosTable > tbody:last').append(response.responseText);
					this.Ajaxloader.hide();
					this.$button.prop('disabled', false);
				}.bind(this)
		});
	},
	
	updateMacroNames: function(){
		this.macroNames = {};
		$("[macro='name']").each(function(intKey, nameObject){
			this.macroNames[$(nameObject).attr('uuid')] = $(nameObject).val();
		}.bind(this));
	}

});
