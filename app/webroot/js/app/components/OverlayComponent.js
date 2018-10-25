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
 * Activates and deactivates an overlay the input controls.
 */
App.Components.OverlayComponent = Frontend.Component.extend({

    /**
     * @param {Object} conf An array with the keys `$overlay`, `$ui`, `on_activate` and `on_deactivate`.
     *                       The `$overlay` and `$ui` variables should be jQuery Objects.
     *                       `on_activate` and `on_deactivate` have to functions.
     *
     *                       `$overlay` is the overlay which should be displayed as jQuery object.
     *                       `$ui` is the container which's controls should be deactivated.
     */
    setup: function(conf){
        conf = conf == null ? {} : conf;
        var defaults = {
            $overlay: $('.overlay'), // The overlay itself.
            $ui: $('#ui-widget'), // The object which should get covered by the overlay.
            on_activate: function(){
            },
            on_deactivate: function(){
            }
        };
        $.extend(this, defaults, conf);
    },

    /**
     * (Re)activates the user interface with it's controls.
     */
    activateUi: function(){
        var self = this;

        self.$overlay
            .animate({opacity: 0}, 250, function(){
                self.$overlay
                    .css({display: 'none'})
                    .width(0)
                    .height(0);
            });

        this.activateFields();
    },

    /**
     * Deactivates the user interface with it's controls.
     */
    deactivateUi: function(){
        this.$overlay
            .css({
                display: 'block',
                position: 'absolute',
                'background-color': '#000',
                'z-index': 10,
                opacity: 0
            })
            .width(this.$ui.width())
            .height(this.$ui.height())
            .animate({
                opacity: 0.5
            }, 250);

        this.deactivateFields();
    },

    deactivateFields: function(){
        this.$ui.find('input').prop('disabled', true);
        this.$ui.find('select')
            .prop('disabled', true)
            .trigger('chosen:updated');
        this.on_deactivate();
    },

    activateFields: function(){
        this.$ui.find('input').prop('disabled', false);
        this.$ui.find('select')
            .prop('disabled', false)
            .trigger('chosen:updated');
        this.on_activate();
    }
});
