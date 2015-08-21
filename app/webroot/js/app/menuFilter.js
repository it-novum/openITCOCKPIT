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
	var searchResultList;
	//Search the menu, if the user type something
	var $mainMenuFilter = $('#filterMainMenu');

	$mainMenuFilter.on('keyup', function(e){
		//prevent new list creation when arrow up or arrow down is pressed
		if(e.which == 40 || e.which == 38){
			return;
		}

		var $menuSearchResult = $('#menuSearchResult');
		var searchString = $.trim($(this).val()).toLowerCase();

		$menuSearchResult.html('');
		if(searchString != ''){
			var menuElements = $('nav').find('a');
			$(menuElements).each(function(i, menuObject){
				//Dont search in the "Search +" button
				if($(menuObject).attr('id') != 'searchMainMenu' &&
					$(menuObject).children('span').html().toLowerCase().match(searchString)
				){
					//Cloning element for later manipulations
					var $matchedObject = $(menuObject).parent().clone();
					var regEx = new RegExp('(' + searchString + ')', 'gi');

					if($matchedObject.find('ul').length){ // Do not display expandable menu entries.
						return;
					}

					var $matchedObjectSpan = $matchedObject.find('span');
					var replacement = $matchedObjectSpan.html().replace(regEx, '<span class="search-highlight">$1</span>');
					$matchedObjectSpan.html(replacement);
					$('#menuSearchResult').append($matchedObject);
				}
			});
			$menuSearchResult.append('<hr style="margin: 5px 0;"/>');
			searchResultList = $menuSearchResult.children().filter('li');
		}else{
			$menuSearchResult.html('');
		}
	});

	//Go to host search, if the user press return (called enter key in german)
	$mainMenuFilter.on('keydown', null, 'return', function(e){
		e.preventDefault();
		var $selected = $('.search_list_item_active');
		if($selected.length){
			window.location = $selected.find('a').attr('href');
		}else{
			window.location = '/hosts/index/Filter.Host.name:' + encodeURIComponent($mainMenuFilter.val());
		}
	});

	//navigate through the menu filter list by using the arrow up or arrow down key
	var selectedItem;
	$mainMenuFilter.on('keydown', function(e){
		if(searchResultList != null){
			if(e.which === 40){
				//arrow down key
				if(selectedItem != null){
					selectedItem.removeClass('search_list_item_active');
					var nextEl = selectedItem.next('li');
					if(nextEl.length > 0){
						selectedItem = nextEl.addClass('search_list_item_active');
					}else{
						selectedItem = searchResultList.first().addClass('search_list_item_active');
					}
				}else{
					selectedItem = searchResultList.first().addClass('search_list_item_active');
				}
			}else if(e.which === 38){
				//arrow up key
				if(selectedItem != null){
					selectedItem.removeClass('search_list_item_active');
					var nextEl = selectedItem.prev('li');
					if(nextEl.length > 0){
						selectedItem = nextEl.addClass('search_list_item_active');
					}else{
						selectedItem = searchResultList.last().addClass('search_list_item_active');
					}
				}else{
					selectedItem = searchResultList.last().addClass('search_list_item_active');
				}
			}
		}
	});
});
