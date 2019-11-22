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

/**
 * Calculates the time based on the given server time.
 *
 * This is also true for the client time.
 * @deprecated
 */
App.Components.TimeComponent = Frontend.Component.extend({
    serverRenderTime: null,
    serverDate: {},
    serverOffset: 0,
    displayClientTime: false,
    initialized: false,
    serverUTC: 0,
    timezoneOffset: 0,
    server_time_utc: 0,

    setup: function(){
        $.ajax({
            url: '/angular/user_timezone.json',
            type: 'GET',
            cache: false,
            //async: false,
            error: function(){
            },
            success: function(response){
                this.timezoneOffset = response.timezone.user_offset;
                this.serverOffset = response.timezone.server_timezone_offset;
                this.server_time_utc = response.timezone.server_time_utc;
            }.bind(this),
            complete: function(response){
            }
        });

        this.pageLoaded = new Date().getTime();

        if(this.timezoneOffset != this.serverOffset){
            //Display the clock for users time
            this.displayClientTime = true;
        }

        this.initialized = true;
    },

    /**
     * @param {Number} number
     * @returns {String}
     * @private
     */
    _prependNumber: function(number){
        if(number < 10){
            return '0' + number;
        }
        return number.toString();
    },

    _isInitialized: function(){
        if(!this.initialized){
            if(typeof console.warn === 'function'){
                console.warn('TimeComponent isn\'t initialized. Please call the setup() method first, ' +
                    'before using this functions.');
            }
            return false;
        }
        return true;
    },

    getCurrentTimeWithOffset: function(offset){
        if(typeof(this.pageLoaded) === 'undefined'){
            this.pageLoaded = new Date().getTime();
        }
        return new Date((this.getServerUTC() + (offset || 0)) * 1000 + (new Date().getTime() - this.pageLoaded));
    },

    /**
     * @returns {Date}
     */
    getServerTime: function(){
        this._isInitialized();
        return this.getCurrentTimeWithOffset(this.serverOffset);
        //new Date((new Date).getTime() + this.serverOffset);
    },

    /**
     * Returns the server time in a hh:mm format.
     * @returns {string}
     */
    getServerTimeAsText: function(){
        return this.formatTimeAsText(this.getServerTime());
    },

    /**
     * @returns {Date}
     */
    getClientTime: function(){
        this._isInitialized();
        return this.getCurrentTimeWithOffset(this.timezoneOffset);
    },


    /**
     * @returns {Date}
     */
    getServerUTC: function(){
        return this.server_time_utc;

    },

    /**
     * Returns the client time in a hh:mm format.
     * @returns {string}
     */
    getClientTimeAsText: function(){
        return this.formatTimeAsText(this.getClientTime());
    },

    /**
     * Format a given date object and returns the formatted hours and minutes.
     * @param {Date} time
     * @returns {string}
     */
    formatTimeAsText: function(time){
        return this._prependNumber(time.getUTCHours()) + ':' + this._prependNumber(time.getUTCMinutes());
    }
});
