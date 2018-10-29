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

App.Components.ListFilterComponent = Frontend.Component.extend({
    render: function(){
        $('.list-filter a.toggle').click(this._onToggleClick.bind(this));
        $('.oitc-list-filter').click(this._onOitcToggleClick.bind(this));
        if($('.oitc-list-filter').attr('hide-on-render') == 'true'){
            $('.list-filter').removeClass('opened').hide();
        }
    },
    _onToggleClick: function(e){
        var $a = $(e.currentTarget);
        var $filter = $a.parent().parent();
        var $content = $filter.find('> .content');

        if($filter.hasClass('opened')){
            $a.text('open');
            $content.hide();
            $filter.removeClass('opened').addClass('closed');
        }else{
            $content.show();
            $filter.addClass('opened').removeClass('closed');
            $a.text('close');
        }
    },

    _onOitcToggleClick: function(event){
        var $filter = $('.list-filter');
        if($filter.hasClass('opened')){
            $filter.removeClass('opened').addClass('closed');
            $filter.hide();
        }else{
            $filter.addClass('opened').removeClass('closed');
            $filter.show();
        }
    }
});
