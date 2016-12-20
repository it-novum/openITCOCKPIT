'use strict';
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

App.Controllers.DocumentationsWikiController = Frontend.AppController.extend({

	_initialize: function() {
		var self = this;

		//Start search if user is typing
		$('#search-documentation').keyup(function(){
			var searchKeyword = $.trim($(this).val()).toLowerCase();
			self.search(searchKeyword);
		});

		//Search if the user pressed back and browser auto-fills the input field
		var inputField = document.getElementById('search-documentation');
		if(inputField !== null){
			if(inputField.value.length > 0){
				self.search(inputField.value.toLowerCase());
			}
		}

		//Use browser back that the browser auto fills the search field
		$('#doku_back').click(function(){
			window.history.back();
		});
	},

	search: function(searchKeyword){
		var $target;
		var $search = $('.docs-container');
		var $results = $('.wiki-search-results');

		if(searchKeyword !== ''){
			$search.hide();
			$results.html('');
			$results.show();
			$search.find('.search-results').each(function(){
				$target = $(this).children('h4').children('a').html() + ' ' + $(this).children('div').children('.description').html();
				if($target.toLowerCase().match(searchKeyword)){
					$results.append($(this).clone());
				}
			});
		}else{
			$results.hide();
			$search.show();
		}
	}
});
