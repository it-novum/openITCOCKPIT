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

App.Components.WebsocketSudoComponent = Frontend.Component.extend({

    _wsUrl: null,
    _key: null,
    _connection: null,
    _callback: function(e){
    },
    _errorCallback: function(){
    },
    _success: function(e){
    },
    _dispatcher: function(transmitted){
    },
    _event: function(transmitted){
    },
    _uniqid: null,
    _keepAliveIntervalObject: null,
    _keepAliveInterval: 30000,
    setup: function(wsURL, key){
        this._wsUrl = wsURL;
        this._key = key;
    },

    connect: function(){

        if(this._connection === null){
            this._connection = new WebSocket(this._wsUrl);
        }
        this._connection.onopen = this._onConnectionOpen.bind(this);
        this._connection.onmessage = this._onResponse.bind(this);
        this._connection.onerror = this._onError.bind(this);
        return this._connection;
    },
    send: function(json, connection){
        connection = connection || this._connection;
        connection.send(json);
    },

    _onConnectionOpen: function(e){
        // Eine ID vom WebSocket Server bekommen:
        this.requestUniqId();
    },


    _onError: function(){
        this._errorCallback();
    },

    _onResponse: function(e){
        var transmitted = JSON.parse(e.data);
        switch(transmitted.type){
            case 'connection':
                // Es handelt sich um einen Verbidungsaufbau, dieser speichert die durch PHP generietere uniqid in die Klassenvariable
                this._uniqid = transmitted.uniqid;

                // Die Verbindung zum SudoWebsocket Server wurde erfolgreich hergestellt und es wird die _success callback function aufgerufen
                this.__success(e);
                break;

            case 'response':
                // Es handelt sich um eine Antwort auf eine Anfrage, wenn diese für "mich" ist wird sie an die callback function übergeben
                if(this._uniqid === transmitted.uniqid){
                    this._callback(transmitted);
                }
                break;

            case 'dispatcher':
                // Eine Aufgabe wurde vom SudoWebsocketserver abgearbeiet und es kann auf das event über die dispatcher callback function reagiert werden
                this._dispatcher(transmitted);
                break;

            case 'event':
                // Eine Aufgabe wurde vom SudoWebsocketserver abgearbeiet und es kann auf das event über die event callback function reagiert werden
                if(this._uniqid === transmitted.uniqid){
                    this._event(transmitted);
                }
                break;

            case 'keepAlive':
                // Server is still alive :-)
                break;
        }
    },

    requestUniqId: function(){
        this.send(this.toJson('requestUniqId', ''));
    },

    toJson: function(task, data){
        var jsonArr = [];
        jsonArr = JSON.stringify({
            task: task,
            data: data,
            uniqid: this._uniqid,
            key: this._key
        });
        return jsonArr;
    },

    keepAlive: function(){
        if(this._keepAliveIntervalObject == null){
            this._keepAliveIntervalObject = setInterval(function(){
                this.send(this.toJson('keepAlive', ''));
            }.bind(this), this._keepAliveInterval);
        }
    },

    __success: function(e){
        this.keepAlive();
        this._success(e);
    }
});
