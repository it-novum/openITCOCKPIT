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

App.Controllers.LoginLoginController = Frontend.AppController.extend({
	
	
	_initialize: function(){
		var self = this;
		this.modifyForm(this.getVar('selectedMethod'));
		
		/*
		 * Mouse over effect for icons
		 */
		$('.btn-circle').mouseenter(function(){
			switch($(this).attr('sn')){
				case 'facebook':
					$(this).css('color', '#3C599F');
					break;
				
				case 'twitter':
					$(this).css('color', '#5DD7FC');
					break;
				
				case 'google+':
					$(this).css('color', '#D54334');
					break;
				
				case 'youtube':
					$(this).css('color', '#C02F2A');
					break;
			}
		});
	
		$('.btn-circle').mouseleave(function(){
			$(this).css('color', '#333333');
		});
		
		/*
		 * Bind change event for LoginUserAuthMethod selectbox
		 */
		$('#LoginUserAuthMethod').change(function(){
			self.modifyForm($(this).val());
		});
	},
	
	modifyForm: function(value){
		if(value == 'session'){
			$('#LoginUserSamaccountname').parent().parent().hide();
			$('#LoginUserEmail').parent().parent().show();
		}
		
		if(value == 'ldap'){
			$('#LoginUserSamaccountname').parent().parent().show();
			$('#LoginUserEmail').parent().parent().hide();
		}
	}

});
