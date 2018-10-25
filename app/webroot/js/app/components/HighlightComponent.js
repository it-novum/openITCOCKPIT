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

App.Components.HighlightComponent = Frontend.Component.extend({

    $selector: null,

    highlight: function($selector, conf){
        conf = conf || {};
        conf.speed = conf.speed || 600;
        conf.html = conf.html || '';
        this.$selector = $selector;
        $div = $('<div class="highlight">' + conf.html + '</div>');
        $div.css({
            'width': this.$selector.width() + 'px',
            'height': this.$selector.height() + 'px',
            'left': this.$selector.css('padding-left')
        });
        this.$selector.append($div);
        $div.fadeOut(conf.speed, function(){
            $div.remove();
        });
    }
});
