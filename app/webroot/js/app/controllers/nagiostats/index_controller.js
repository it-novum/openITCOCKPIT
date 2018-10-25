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

App.Controllers.NagiostatsIndexController = Frontend.AppController.extend({
    hostUuid: null,
    autoLoadMax: 60,
    autoLoadPosition: 60,
    autoLoadObject: null,

    /**
     * @constructor
     * @return {void}
     */

    components: ['WebsocketSudo', 'Ajaxloader'],

    _initialize: function(){
        this.autoUpdate();
        this.Ajaxloader.setup();

        /*
         * Bind click event for refresh button
         */
        /*
         * FixMe: Dont work after 5 minutes of browser idle ?
        $('.page_refresh').click(function(){
            this.loadStats();
        }.bind(this));
        */

        this.WebsocketSudo.setup(this.getVar('websocket_url'), this.getVar('akey'));

        this.WebsocketSudo._errorCallback = function(){
            $('#error_msg').html('<div class="alert alert-danger alert-block"><a href="#" data-dismiss="alert" class="close">×</a><h5 class="alert-heading"><i class="fa fa-warning"></i> Error</h5>Could not connect to SudoWebsocket Server</div>');
        }

        this.WebsocketSudo.connect();
        this.WebsocketSudo._success = function(e){
            this.loadStats();
        }.bind(this)

        this.WebsocketSudo._callback = function(transmitted){
            for(var key in transmitted.payload){
                this.updateValues(key, transmitted.payload[key]);
            }
            this.Ajaxloader.hide();
        }.bind(this);

        $('[nagiostats="MINPSVSVCPSC"]').html('sdfsdf');

    },

    loadStats: function(){
        this.Ajaxloader.show();
        this.WebsocketSudo.send(this.WebsocketSudo.toJson('nagiostats', []));
    },

    updateValues: function(key, value){
        var $object = $('[nagiostats="' + key + '"]');
        var unit = $object.attr('unit');
        if(unit == 's'){
            value = value / 1000;
        }
        $object.html(value + ' ' + unit);
        this.checkThresholds($object, value);
    },

    autoUpdate: function(){
        this.autoLoadObject = setInterval(function(){
            $("#autoLoadChart").sparkline([this.autoLoadPosition, (this.autoLoadMax - this.autoLoadPosition)], {
                type: 'pie',
                sliceColors: ['#3276B1', '#4C4F53']
            });
            this.autoLoadPosition--;
            if(this.autoLoadPosition == 0){
                this.Ajaxloader.show();
                this.WebsocketSudo.send(this.WebsocketSudo.toJson('nagiostats', []));
                this.autoLoadPosition = this.autoLoadMax;
            }
        }.bind(this), 250)

    },

    checkThresholds: function($object, value){
        var notice = false;
        value = parseInt(value);
        if(typeof $object.attr('warning') != "undefined" && typeof $object.attr('critical') != "undefined"){
            var warning = parseInt($object.attr('warning'));
            var critical = parseInt($object.attr('critical'));

            if(value >= warning && value < critical){
                //console.log(value+" >= "+warning+" && "+value+" < "+critical);
                $object.addClass('txt-color-orangeDark');
                $object.removeClass('txt-color-red');
                notice = true;
            }

            if(value >= critical){
                //console.log(value+" >= "+critical);
                $object.addClass('txt-color-red');
                $object.removeClass('txt-color-orangeDark');
                notice = true;
            }

        }else if(typeof $object.attr('critical') != "undefined"){
            var critical = parseInt($object.attr('critical'));
            //We only have a critical value, so we need to negative the operator
            if(value <= critical){
                $object.addClass('txt-color-red');
                $object.removeClass('txt-color-orangeDark');
                notice = true;
            }
        }

        /*if(typeof $object.attr('warning') != "undefined"){
            var warning = $object.attr('warning');
            if(value >= warning){
                $object.addClass('txt-color-orangeDark');
                $object.removeClass('txt-color-red');
                notice = true;
            }
        }*/
        /*
    if(typeof $object.attr('critical') != "undefined"){
        var critical = $object.attr('critical');

        if(typeof $object.attr('warning') == "undefined"){
            //We only have a critical value, so we need to negative the operator
            if(value <= critical){
                $object.addClass('txt-color-red');
                $object.removeClass('txt-color-orangeDark');
                notice = true;
            }
        }else{
            var warning = $object.attr('warning');
            if(value >= warning && value < critical){
                $object.addClass('txt-color-orangeDark');
                $object.removeClass('txt-color-red');
                notice = true;
            }
            if(value >= critical){
                $object.addClass('txt-color-red');
                $object.removeClass('txt-color-orangeDark');
                notice = true;
            }
        }
    }
    */
        if(notice === false){
            $object.removeClass('txt-color-orangeDark');
            $object.removeClass('txt-color-red');
        }
    }

});
	