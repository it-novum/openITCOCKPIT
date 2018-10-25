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
App.Components.WebsocketChatComponent = Frontend.Component.extend({

    $container: null,
    _wsUrl: null,
    _connection: null,

    _keepAliveIntervalObject: null,
    _keepAliveInterval: 30000,

    _titleFlapIntervalObject: null,
    _titleFlapIntervalFunction: function(){
        return true;
    },
    _errorCallback: function(){
        return true;
    },

    _orginalTitle: document.title,

    new_message: 'new message',

    setup: function($el, wsURL){
        this.$container = $el;
        this._wsUrl = wsURL;
        this.username = null;
        this.user_id = null;

        /*
         * Set orginal title if window is in focus
         */
        $(window).focus(function(){
            window.clearInterval(this._titleFlapIntervalObject);
            this._titleFlapIntervalObject = null;
            document.title = this._orginalTitle;
            this._titleFlapIntervalFunction = function(){
                return true;
            };
        }.bind(this));


        /*
         * Only bind the flap window title function, if the window/tab lost its focus
         */
        $(window).blur(function(){
            this._titleFlapIntervalFunction = function(){
                //console.log('flappiong function');
                this._titleFlapIntervalObject = setInterval(function(){
                    if(document.title == this._orginalTitle){
                        document.title = this.new_message;
                    }else{
                        document.title = this._orginalTitle;
                    }
                }.bind(this), 1500);
            }.bind(this);
        }.bind(this));

        //Send on 'Send Button' is clicked
        this.$container.find('.textarea-controls button').click(function(){
            this._onMessageFormSend('message');
        }.bind(this));

        //Send on ENTER key
        this.$container.find('textarea').keyup(function(e){
            if(e.keyCode === 13 && this.$container.find('input[type=checkbox]#subscription').is(':checked')){
                this._onMessageFormSend('message');
            }
        }.bind(this));

    },
    connect: function(){
        this._connection = new WebSocket(this._wsUrl);
        this._connection.onopen = this._onConnectionOpen.bind(this);
        this._connection.onmessage = this._onConnectionMessage.bind(this);
        this._connection.onerror = this._onError.bind(this);
    },
    _onMessageFormSend: function(type){
        var $textarea = this.$container.find('textarea');
        var jsonArr = [];
        jsonArr = JSON.stringify({
            message: $textarea.val(),
            user_id: this.user_id,
            type: type
        });
        this._connection.send(jsonArr);
        $textarea.val('');
    },

    _onError: function(){
        this._errorCallback();
    },

    _onConnectionOpen: function(e){
        this.addMessage(null, 'Chat Client', 'You have been connected.', 'server_message');
        this._onMessageFormSend('connection');
        this.keepAlive();
    },
    _onConnectionMessage: function(e){
        var messageData = JSON.parse(e.data);
        //console.log(messageData);
        switch(messageData.type){
            case 'message':
            case 'server_message':
                this.addMessage(messageData.time, messageData.user, messageData.message, messageData.type, messageData.image);
                break;
        }
    },
    addMessage: function(time, username, message, type, userimage){

        if(type == 'keepAlive'){
            //Server is still alive :)
            return;
        }

        if(type == 'server_message'){
            var $message = $('<li/>');
            $message.addClass('message');
            var $text = $('<div class="alert alert-info fade in"><button class="close" data-dismiss="alert"> × </button><i class="fa-fw fa fa-info"></i></div>');

            $text.css('margin-left', '0px');
            var $username = $('<a/>');
            $text.append(username + ' <i class="fa-fw fa fa-arrow-right "></i> ' + message);
        }else{
            this.flapTitle();
            var $message = $('<li/>');
            $message.addClass('message');
            //$message.append('<img src="/smartadmin/img/avatars/male.png" class="online" alt="">');
            $message.append('<img src="' + userimage + '" style="height:50px;" class="online" alt="">');
            var $text = $('<div class="message-text" style="min-height: 35px;"></div>');
            if(time){
                $text.append('<time>' + time + '</time>');
            }
            var $username = $('<a/>');
            $text.append($username);
            $text.append(message);
        }

        $username.addClass('username').text(username);
        $message.append($text);

        this.$container.find('.chat-body > ul').append($message);

        this.$container.find('#chat-body').animate({
            scrollTop: this.$container.find('#chat-body')[0].scrollHeight
        }, 500);
    },

    setUsername: function(username){
        this.username = username;
    },

    setUserId: function(user_id){
        this.user_id = user_id;
    },

    keepAlive: function(){
        if(this._keepAliveIntervalObject == null){
            this._keepAliveIntervalObject = setInterval(function(){
                this._onMessageFormSend('keepAlive');
            }.bind(this), this._keepAliveInterval);
        }
    },

    flapTitle: function(){
        if(this._titleFlapIntervalObject === null){
            this._titleFlapIntervalFunction();
        }
    }
});