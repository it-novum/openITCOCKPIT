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

App.Components.ImageChooserComponent = Frontend.Component.extend({
    setup: function($dom){
        $dom.find('.image-chooser-input a.choose-image').click(this._onChooseImage.bind(this));
        $dom.find('.image-chooser-input a.remove-image').click(this._onRemoveImage.bind(this));
    },
    _onChooseImage: function(e){
        var finder = new CKFinder();
        finder.selectActionFunction = this._onSelectFile.bind(this);
        finder.selectActionData = {
            imageChooser: $(e.currentTarget).parents('.image-chooser-input'),
            finder: finder
        }
        finder.popup(600, 400);
    },
    _onRemoveImage: function(e){
        $('.image-chooser-input .image-name').html(__('forms.no_image_chosen'));
        $('.image-chooser-input .image-name-input').val('');
        $('.image-chooser-input .image-name').html(__('none'));
        $('.image-chooser-input .thumb img').hide();
        $('.image-chooser-input a.remove-image').hide();
    },
    _onSelectFile: function(file, params, all){
        params.selectActionData.imageChooser.find('.image-name').html(urldecode(file));
        params.selectActionData.imageChooser.find('.image-name-input').val(urldecode(file));
        params.selectActionData.imageChooser.find('.thumb img').attr({
            src: file
        }).show();
        params.selectActionData.finder.api.closePopup();
        removeLink = '<a class="btn btn-xs btn-default remove-image">Remove Image</a>';
        $(removeLink).insertAfter('.image-chooser-input a.choose-image').click(this._onRemoveImage.bind(this));
    }
});
