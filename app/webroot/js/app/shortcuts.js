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

$(document).ready(function(){
	// Load Lockscreen
	if(navigator.platform.match(/Linux/)){
		$(document).on('keydown', null, 'alt+l', function(e){
			e.preventDefault();
			location.href='/login/lock';
		});
	}else{
		$(document).on('keydown', null, 'ctrl+l', function(e){
			e.preventDefault();
			location.href='/login/lock';
		});

		// Logout
		$(document).on('keydown', null, 'alt+l', function(e){
			e.preventDefault();
			location.href='/login/logout';
		});
	}

	// Load Shortcuts help ctrl+shift+?
	$(document).on('keydown', null, 'ctrl+h', function(e){
		e.preventDefault();
		$('#ShortcutsHelp').modal() 
	});
	
	// Collapse menu
	$(document).on('keydown', null, 'alt+c', function(e){
		e.preventDefault();
		$('body').toggleClass("minified");
	});
	
	// Trigger List Search
	$(document).on('keydown', null, 'alt+f', function(e){
		e.preventDefault();
		$('.oitc-list-filter').trigger('click');
	});
	
});
