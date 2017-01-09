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
App.Controllers.ChatIndexController = Frontend.AppController.extend({
    $chatUsers: null,
    $filterInput: null,
    $chatBody: null,
    $chatContainer: null,
    /**
     * @type {Array}
     */
    components: ['WebsocketChat'],
    /**
     * @constructor
     * @return {void}
     */
    _initialize: function () {
        this.$chatUsers = this.$('#chat-users');
        this.$filterInput = this.$('#filter-chat-list');
        this.$chatBody = this.$('#chat-body');
        this.$chatContainer = this.$('#chat-container');
        this.$chatContainer.find('.chat-list-open-close').click(
            this._onChatListToggle.bind(this)
        );
        this._setupChatListFilter();
        //this.WebsocketChat.setup(this.$('.chat-widget'), this.getVar('websocket_host'), this.getVar('websocket_port'));
        this.WebsocketChat.setup(this.$('.chat-widget'), this.getVar('websocket_url'));

        this.WebsocketChat._errorCallback = function () {
            $('#error_msg').html('<div class="alert alert-danger alert-block"><a href="#" data-dismiss="alert" class="close">×</a><h5 class="alert-heading"><i class="fa fa-warning"></i> Error</h5>Could not connect to Chat Server</div>');
        }

        this.WebsocketChat.new_message = this.getVar('new_message');
        this.WebsocketChat.setUsername(this.getVar('username'));
        this.WebsocketChat.setUserId(this.getVar('user_id'));
        this.WebsocketChat.connect();
    },
    /**
     * Toggle visibility of the participants list
     * @return {void}
     */
    _onChatListToggle: function () {
        this.$chatContainer.toggleClass('open');
    },
    /**
     * Handles filtering/searching the participants list
     * @return {void}
     */
    _setupChatListFilter: function () {
        // custom css expression for a case-insensitive contains()
        jQuery.expr[':'].Contains = function (a, i, m) {
            return (a.textContent || a.innerText || "").toUpperCase().indexOf(m[3].toUpperCase()) >= 0;
        };
        this.$filterInput.change(function (e) {
            var filter = $(e.currentTarget).val();
            if (filter) {
                // this finds all links in a list that contain the input,
                // and hide the ones not containing the input while showing the ones that do
                this.$chatUsers.find("a:not(:Contains(" + filter + "))").parent().slideUp();
                this.$chatUsers.find("a:Contains(" + filter + ")").parent().slideDown();
            } else {
                this.$chatUsers.find("li").slideDown();
            }
            return false;
        }.bind(this)).keyup(function () {
            // fire the above change event after every letter
            $(this).change();
        });
    }
});