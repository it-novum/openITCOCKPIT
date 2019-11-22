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

App.Components.QrComponent = Frontend.Component.extend({

    $scancodeContainer: null,

    setup: function(){
        var self = this;
        this.$scancodeContainer = $('#scancodeContainer');

        $('#QRPrint').click(function(){
            self._print();
        }.bind(self));

        $('#QRSize').slider({
            tooltip: 'hide'
        });

        $('#QRSize').slider().on('slideStop', function(ev){
            this.$scancodeContainer.html('');
            //console.log($('#QRSize').slider('getValue'));
            this.$scancodeContainer.qrcode({
                render: 'canvas',
                //ecLevel: 'L',
                width: $('#QRSize').slider('getValue'),
                height: $('#QRSize').slider('getValue'),
                text: document.location.href
            });
        }.bind(this));


        $('#qrmodal').on('shown.bs.modal', function(){
            self.show();
        }.bind(self));
    },

    show: function(){
        this.$scancodeContainer.html('');
        this.$scancodeContainer.qrcode({
            render: 'canvas',
            //ecLevel: 'L',
            width: 150,
            height: 150,
            text: document.location.href
        });
    },

    _print: function(){
        var size = $('#QRSize').slider('getValue');
        ;
        window.open('/qr/index/?url=' + encodeURIComponent(document.location.href) + '&width=' + size + '&height=' + size, '', 'width=' + size + ', height=' + size);
    },

    _printPage: function(url, width, height){
        $('#QrContainer').qrcode({
            render: 'canvas',
            //ecLevel: 'L',
            width: width,
            height: height,
            text: url
        });
        window.print();
    }

});
