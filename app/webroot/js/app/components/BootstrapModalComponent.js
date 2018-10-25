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

App.Components.BootstrapModalComponent = Frontend.Component.extend({

    bootstrap_template: '\
		<div class="modal fade" id="bs-modal-component-window" tabindex="-1" role="dialog" \
			aria-labelledby="myModalLabel" aria-hidden="true">\
			<div class="modal-dialog">\
				<div class="modal-content">\
					<div class="modal-header">\
						<button type="button" class="close" data-dismiss="modal">\
							<span aria-hidden="true">&times;</span>\
							<span class="sr-only">Close</span>\
						</button>\
						<h4 class="modal-title" id="myModalLabel"></h4>\
					</div>\
					\
					<div class="modal-body"></div>\
					\
					<div class="modal-footer">\
						<button type="button" class="btn btn-default" data-dismiss="modal">OK</button>\
					</div>\
				</div>\
			</div>\
		</div>',

    content: {}, // An array of objects. Each object should have a `title` and a `body`.
    on_close: function(){
    },

    /**
     * @param {Object} settings This object needs to contain a `content` object.
     *                            This content entry is required to be an object of objects.
     *                            Each object should have a `title` and a `body`.
     */
    setup: function(settings){
        var self = this;

        if(settings != null){
            self.content = typeof settings['content'] === 'object' ? settings['content'] : [];
            self.on_close = typeof settings['on_close'] === 'function' ? settings['on_close'] : function(){
            };
        }

        self.$modal = $('#bs-modal-component-window');
        if(self.$modal.length == 0){
            $('body').prepend(self.bootstrap_template);
            self.$modal = $('#bs-modal-component-window');
        }

        self.$modal.on('hidden.bs.modal', function(){
            self.on_close.call(this);
        });
    },

    show: function(modal_name, wrap_with_paragraphs){
        wrap_with_paragraphs = typeof wrap_with_paragraphs != null ? wrap_with_paragraphs : false;
        if(modal_name == '' || Object.keys(this.content).length == 0){
            return false;
        }

        this.$modal.find('.modal-title').html(this.content[modal_name].title);

        var body = this.content[modal_name].body;
        if($.isArray(body)){
            if(wrap_with_paragraphs){
                body = '<p>' + body.join('<p></p>') + '</p>';
            }else{
                body = body.join(' ');
            }
        }

        this.$modal.find('.modal-body').html(body);
        this.$modal.modal(); // Displays the modal window.

        return true;
    },

    /**
     * @param {Object} content The content variable needs to be an object of objects.
     *                           Each of these object should have a `title` and a `body`.
     */
    setContent: function(content){
        this.content = content;
    },

    /**
     * @returns {Object|null}
     */
    getContent: function(){
        return this.content;
    }
});
