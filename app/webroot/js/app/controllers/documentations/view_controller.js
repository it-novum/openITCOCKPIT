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

//App.Controllers.DocumentationsViewController = Frontend.AppController.extend({
//    $currentColor: null,
//    $textarea: null,

//    //components: ['Ajaxloader'],

//    _initialize: function(){
//        var self = this;

//        this.$currentColor = $('#currentColor');
//        this.$textarea = $('#docuText');

//        // Bind click event for color selector
//        $("[select-color='true']").click(function(){
//            var color = $(this).attr('color');
//            self.$textarea.surroundSelectedText('[color=' + color + ']', '[/color]');
//        });

//        // Bind click event for font size selector
//        $("[select-fsize='true']").click(function(){
//            var fontSize = $(this).attr('fsize');
//            self.$textarea.surroundSelectedText('[' + fontSize + ']', '[/' + fontSize + ']');
//        });

//        // Bind click event to insert hyperlinks
//        $('#insertWysiwygHyperlink').click(function(){
//            var url = $('#url').val();
//            var description = $('#description').val();
//            var sel = self.$textarea.getSelection();
//            self.$textarea.insertText('[url=' + url + ']' + description + '[/url]', sel.start, "collapseToEnd");
//        });

//        // Bind other buttons
//        $("[wysiwyg='true']").click(function(){
//            var task = $(this).attr('task');
//            switch(task){
//                case 'bold':
//                    self.$textarea.surroundSelectedText('[b]', '[/b]');
//                    break;

//                case 'italic':
//                    self.$textarea.surroundSelectedText('[i]', '[/i]');
//                    break;

//                case 'underline':
//                    self.$textarea.surroundSelectedText('[u]', '[/u]');
//                    break;

//                case 'left':
//                    self.$textarea.surroundSelectedText('[left]', '[/left]');
//                    break;

//                case 'center':
//                    self.$textarea.surroundSelectedText('[center]', '[/center]');
//                    break;

//                case 'right':
//                    self.$textarea.surroundSelectedText('[right]', '[/right]');
//                    break;

//                case 'justify':
//                    self.$textarea.surroundSelectedText('[justify]', '[/justify]');
//                    break;

//                case 'code':
//                    self.$textarea.surroundSelectedText('[code]', '[/code]');
//                    break;
//            }
//        });
//    }
//});
