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

App.Components.AcoComponent = Frontend.Component.extend({
    setup: function(){
        var self = this;
        $('.tree > ul').attr('role', 'tree').find('ul').attr('role', 'group');
        $('.tree').find('li:has(ul)').addClass('parent_li')
            .attr('role', 'treeitem')
            .find(' > span')
            .attr('title', 'Collapse this branch').click(function(e){

            var children = $(this).parent('li.parent_li').find(' > ul > li');
            if(children.is(':visible')){
                children.hide('fast');
                $(this).attr('title', 'Expand this branch')
                    .find(' > i')
                    .toggleClass('fa-folder-open fa-folder');
            }else{
                children.show('fast');
                $(this).attr('title', 'Collapse this branch')
                    .find(' > i')
                    .toggleClass('fa-folder fa-folder-open');
            }
        });
        $('#expandAll').click(function(){
            self.expandTree();
        });
        $('#collapseAll').click(function(){
            self.collapseTree();
        });
        $("i[data-action]").each(function(){
            $(this).click(function(){
                var className = $(this).data('action');
                if($(this).attr('click-action') === 'on'){
                    self.setChecked(className);
                }else{
                    self.unsetChecked(className);
                }
            });
        });
    },
    collapseTree: function(){
        $('.tree li ul li ul> li').hide();
        $('#tree .fa-folder-open').toggleClass('fa-folder-open fa-folder');
    },
    expandTree: function(){
        $('.tree li ul > li').show();
        $('#tree .fa-folder').toggleClass('fa-folder fa-folder-open');
    },
    setChecked: function(className){
        var classNameFilter = '';
        if(className !== 'all'){
            classNameFilter = '[class*=' + className + ']';
        }
        $('#tree input:checkbox' + classNameFilter + ':not(:checked)').prop('checked', true);
    },
    unsetChecked: function(className){
        var classNameFilter = '';
        if(className !== 'all'){
            classNameFilter = '[class*=' + className + ']';
        }
        $('#tree input:checkbox' + classNameFilter + ':checked').prop('checked', false);
    }
});
