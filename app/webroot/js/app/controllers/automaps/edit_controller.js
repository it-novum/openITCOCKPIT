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

App.Controllers.AutomapsEditController = Frontend.AppController.extend({

    _initialize: function(){

        $('#AutomapFontSize').slider({tooltip: 'hide'}).on('slideStop', function(ev){

            var fontsizes = {
                1: 'xx-small',
                2: 'x-small',
                3: 'small',
                4: 'medium',
                5: 'large',
                6: 'x-large',
                7: 'xx-large'
            };

            var sliderValue = $('#AutomapFontSize').slider('getValue');

            $('#fontExample').css('font-size', fontsizes[sliderValue]);

        }.bind(this));

        /*
         * Bind change event for AutomapShowLabel
         */
        $('#AutomapShowLabel').change(function(){
            if($(this).is(':checked') === true){
                $('#fontExample').html('<span><i class="fa fa-square txt-color-greenLight"></i> Hostname/Servicedescription</span>');
            }else{
                $('#fontExample').html('<span><i class="fa fa-square txt-color-greenLight"></i></span>');
            }
        });
    }
});
