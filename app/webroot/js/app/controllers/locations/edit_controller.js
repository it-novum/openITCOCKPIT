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

App.Controllers.LocationsEditController = Frontend.AppController.extend({
    latitude: null,
    longitude: null,
    $map: null,
    $mapDiv: null,
    /**
     * @constructor
     * @return {void}
     */

    //components: ['WebsocketSudo'],

    _initialize: function(){
        this.$mapDiv = $('#mapDiv');
        /*
         * Binding events
         */
        $('#LocationLatitude').keyup(function(){
            var locationLatitudeValue = $(this).val().replace(/,/gi, '.').replace(/[^\d.-]/g, '');
            if(locationLatitudeValue){
                $(this).val(locationLatitudeValue);
            }
        });

        $('#LocationLongitude').keyup(function(){
            var locationLongitudeValue = $(this).val().replace(/,/gi, '.').replace(/[^\d.-]/g, '');
            if(locationLongitudeValue){
                $(this).val(locationLongitudeValue);
            }
        });

        $('#LocationLatitude').change(function(){
            this.setMarker();
        }.bind(this));

        $('#LocationLongitude').change(function(){
            this.setMarker();
        }.bind(this));

        this.$mapDiv.vectorMap({
            map: 'world_mill_en',
            backgroundColor: '#fff',
            regionStyle: {
                initial: {
                    fill: '#c4c4c4'
                },
                hover: {
                    'hoverColor': '#4C4C4C'
                }
            },
            markers: [
                {latLng: [this.getVar('latitude'), this.getVar('longitude')]},
            ],

            markerStyle: {
                initial: {
                    fill: '#800000',
                    stroke: '#383f47'
                }
            },
        });
        this.$map = this.$mapDiv.vectorMap('get', 'mapObject');
        this.setFocus(this.getVar('latitude'), this.getVar('longitude'));
    },

    setMarker: function(){
        this.latitude = $('#LocationLatitude').val();
        this.latitude = parseFloat(this.latitude.replace(",", "."));
        this.longitude = $('#LocationLongitude').val();
        this.longitude = parseFloat(this.longitude.replace(",", "."));

        if(this.latitude){
            $('#LocationLatitude').val(this.latitude);
        }
        if(this.longitude){
            $('#LocationLongitude').val(this.longitude);
        }

        if(this.latitude && this.longitude){
            if((this.latitude > -505 && this.latitude < 533) && (this.longitude > -168 && this.longitude < 191)){
                $('#LatitudeRangeError').hide();

                this.$map.removeAllMarkers();
                this.$map.reset();
                this.$map.addMarker('markerIndex', {latLng: [this.latitude, this.longitude]});
                this.setFocus(this.latitude, this.longitude);
            }else{
                $('#LatitudeRangeError').show();
            }
        }
    },

    setFocus: function(latitude, longitude){
        this.$map.reset();
        var points = this.$map.latLngToPoint(latitude, longitude);
        var map_x = points.x / this.$mapDiv.width();
        var map_y = points.y / this.$mapDiv.height();
        this.$map.setFocus(10, map_x, map_y);
    }
});