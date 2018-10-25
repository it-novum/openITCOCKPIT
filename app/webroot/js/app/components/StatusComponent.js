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

App.Components.StatusComponent = Frontend.Component.extend({

    /**
     * Host Status Color
     * Returns Human readable State, the bootstrap class for the color and a hex color
     * @author Maximilian Pappert <maximilian.pappert@it-novum.com>
     * @param  {mixed} state    a host state either a string or a number
     * @return {Object}
     */
    hostStatusColor: function(state){
        if(state !== undefined){
            state = parseInt(state, 10);
            switch(state){
                case 0:
                    return {
                        'human_state': 'Ok',
                        'class': 'btn-success',
                        'hexColor': '#5CB85C'
                    };
                    break;
                case 1:
                    return {
                        'human_state': 'Down',
                        'class': 'btn-danger',
                        'hexColor': '#d9534f'
                    };
                    break;
                case 2:
                    return {
                        'human_state': 'Unreachable',
                        'class': 'btn-unknown',
                        'hexColor': '#4C4F53'
                    };
                    break;
                default:
                    return {
                        'human_state': 'Not Found',
                        'class': 'btn-primary',
                        'hexColor': '#337ab7'
                    };
                    break;
            }
        }
        return;
    },

    /**
     * Service Status Color
     * Returns Human readable State, the bootstrap class for the color and a hex color
     * @author Maximilian Pappert <maximilian.pappert@it-novum.com>
     * @param  {mixes} state    a service state either a string or a number
     * @return {Object}
     */
    serviceStatusColor: function(state){
        if(state !== undefined){
            state = parseInt(state, 10);
            switch(state){
                case 0:
                    return {
                        'human_state': 'Ok',
                        'class': 'btn-success',
                        'hexColor': '#5CB85C'
                    };
                    break;
                case 1:
                    return {
                        'human_state': 'Warning',
                        'class': 'btn-warning',
                        'hexColor': '#f0ad4e'
                    };
                    break;
                case 2:
                    return {
                        'human_state': 'Critical',
                        'class': 'btn-danger',
                        'hexColor': '#d9534f'
                    };
                    break;
                case 3:
                    return {
                        'human_state': 'Unknown',
                        'class': 'btn-unknown',
                        'hexColor': '#4C4F53'
                    };
                    break;
                default:
                    return {
                        'human_state': 'Not Found',
                        'class': 'btn-primary',
                        'hexColor': '#337ab7'
                    };
                    break;
            }
        }
        return;
    },
});