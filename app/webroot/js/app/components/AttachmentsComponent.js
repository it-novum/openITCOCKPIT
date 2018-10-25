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

App.Components.AttachmentsComponent = Frontend.Component.extend({
    _element: null,
    setup: function($element, options){
        this._element = $element;
        options = options || {};

        var defaultOptions = {
            model: this._element.data('model'),
            foreignKey: this._element.data('foreign-key'),
            endpoint: '/attachments/handle_upload'
        };
        this.options = $.extend({}, defaultOptions, options);
        this.refreshList();
        this._element.find('.uploader').fineUploader({
            request: {
                endpoint: this.options.endpoint,
                params: {
                    model: this.options.model,
                    foreignKey: this.options.foreignKey
                }
            },
            template: this._element.parent().find('#fine-uploader-template').html(),
            failedUploadTextDisplay: {
                mode: 'custom',
                maxChars: 100,
                responseProperty: 'error',
                enableTooltip: true
            }
        })
            .on('complete', this._onUploadCompleted.bind(this));
    },
    _onUploadCompleted: function(){
        this.refreshList();
    },
    // @fixme: list new entry only if successful upload
    refreshList: function(){
        var url = {
            controller: 'attachments',
            action: 'get_list',
            pass: [this.options.model, this.options.foreignKey]
        };
        App.Main.loadWidget(url, {
            target: this._element.find('.list'),
            onComplete: function(){
                // TODO: highlight new row
            }
        });
    }
});
