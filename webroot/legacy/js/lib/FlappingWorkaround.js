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

//THIS IS ONLY A WORKAOUND AND WILL BE REMOVED SOME DAY
//This workaround is in use, while the status data for history pages is not loaded via Angular

$(document).ready(function(){

    function jQueryFlapping(){
        var flappingInterval = 750;
        var flappingIntervalObject = null;
        var $flappingContainer = $('.flapping_airport');

        if($flappingContainer.length > 0){
            var i = 0;

            if(flappingIntervalObject != null){
                //Stop the old interval
                clearInterval(flappingIntervalObject);
            }

            flappingIntervalObject = setInterval(function(){
                if(i == 0){
                    $flappingContainer.html('<i class="fa fa-circle"></i> <i class="fa fa-circle-o"></i>');

                    i = 1;
                }else{
                    $flappingContainer.html('<i class="fa fa-circle-o"></i> <i class="fa fa-circle"></i>');

                    i = 0;
                }
            }, flappingInterval);
        }
    }

    jQueryFlapping();

});
