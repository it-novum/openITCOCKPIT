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

App.Controllers.MapeditorsViewController = Frontend.AppController.extend({

    components: ['Uuid', 'Gadget', 'Line'],

    mapViewContainer: '#jsPlumb_playground',

    _initialize: function(){
        self = this;
        //wait till everything is loaded (needed for the lines)
        $(document).ready(function(){
            $('.elementHover').mouseenter(function(){
                var el = this;
                var $popovers = $('#divSmallBoxes').children();
                $popovers.hide(200, function(){
                    $(this).remove();
                });
                timer = window.setTimeout(function(){
                    self.getPopoverInfo(el);
                }, 400);
            });

            $('.elementHover').mouseleave(function(){
                clearTimeout(timer);
                var $el = $('#divSmallBoxes').children();
                $el.hide(200, function(){
                    $(this).remove();
                });
            });
        });

        //reload the page in a 90sec interval
        //Do not refresh inside of a rotation
        if(self.getVar('interval') == 0){
            if(self.getVar('refresh_interval')){
                if(self.getVar('refresh_interval') < 10000){
                    setTimeout(self.refreshPage, 90000);
                }else{
                    setTimeout(self.refreshPage, self.getVar('refresh_interval'));
                }
            }else{
                setTimeout(self.refreshPage, 90000);
            }
        }else{
            //fire up rotation timer
            setTimeout(function(){
                var rotation_ids = this.getVar('rotation_ids');
                var url = '/map_module/mapeditors/view'
                $(rotation_ids).each(function(intKey, value){
                    url += '/rotate[' + intKey + ']:' + value;
                });
                url += '/interval:' + this.getVar('interval');
                if(this.getVar('is_fullscren') == true){
                    url += '/fullscreen:1';
                }
                window.location.href = url;
            }.bind(this), parseInt(this.getVar('interval') * 1000));
        }
        if (this.getVar('is_fullscren') == true) {
            $('#MapContainer').css('height', ($(window).height()-96)+'px');
        }
        //check if there are Gadgets
        if(this.getVar('map_gadgets')){
            var mapGadgets = this.getVar('map_gadgets');
            for(var i = 0; i < mapGadgets.length; i++){
                //draw every gadget
                //self.Gadget is the Gadget Component and the "index" is the function call
                //eg. drawTacho, drawText, drawCylinder ...

                var currentElementData = self.findParentGadgetData(mapGadgets[i]['id']);
                $(self.mapViewContainer).append('<div id="svgContainer_' + currentElementData['currentUuid'] + '"></div>');
                $('<div id="svgContainer_' + mapGadgets[i]['id'] + '"></div>')
                    .appendTo(self.mapViewContainer);

                var state = currentElementData['currentState'];
                var flapping = currentElementData['currentFlapping'];
                var containerData = {
                    'uuid': currentElementData['currentUuid'],
                    type: self.capitaliseFirstLetter(currentElementData['currentType'])
                };
                var options = {
                    id: currentElementData['currentUuid'],
                    x: mapGadgets[i]['x'],
                    y: mapGadgets[i]['y'],
                    containerData: containerData,
                    perfdata: currentElementData['currentPerfdata'],
                    state: state.toString(),
                    flapping: flapping,
                    RRDGraphLink: currentElementData['currentRRDGraphLink'],
                    color: currentElementData['currentColor'],
                    showLabel: currentElementData['currentShowLabel'],
                    fontSize: currentElementData['currentFontSize'],
                    z_index:currentElementData['currentZIndex']
                }
                //check if the RRD grah link is empty
                if(!currentElementData['currentRRDGraphLink']){
                    var opt = {
                        demo: true
                    }
                    //merge the demo property into the options array so that there will be displayed
                    //the dummy graph (as in the edit mode) instead of nothing
                    options = $.extend({}, options, opt);
                }
                self.Gadget['draw' + mapGadgets[i]['gadget']]('svgContainer_' + mapGadgets[i]['id'], options);

                //wrap browser link around the gadget
                $('#svgContainer_' + mapGadgets[i]['id']).children().wrap('<a target="_parent" href="' + currentElementData['currentLink'] + '"></a>');
            }
            ;
        }


        start = [];
        end = [];
        wasClicked = false;

        //create the SVG paper
        $(self.mapViewContainer).svg();
        svg = $(self.mapViewContainer).svg('get');

        //check if there are Lines
        if(this.getVar('map_lines')){
            var mapLines = this.getVar('map_lines');
            for(var i = 0; i < mapLines.length; i++){
                //parse the line coordinates to integer
                start['x'] = parseInt(mapLines[i]['startX']);
                start['y'] = parseInt(mapLines[i]['startY']);
                end['x'] = parseInt(mapLines[i]['endX']);
                end['y'] = parseInt(mapLines[i]['endY']);

                var tempUuid = this.Uuid.v4();
                var currentElementData = this.findParentLineData(mapLines[i]['id']);

                $(self.mapViewContainer).append('<div id="svgLineContainer_' + tempUuid + '"></div>');
                $('<div id="svgLineContainer_' + tempUuid + '"></div>')
                    .appendTo(this.mapViewContainer);

                var drawRect = true;
                if(mapLines[i].type == 'stateless'){
                    drawRect = false;
                }

                //fill the object for the current line
                var tempObj = {
                    id: tempUuid,
                    svgContainer: 'svgLineContainer_' + tempUuid,
                    start: start,
                    end: end,
                    lineId: mapLines[i]['id'],
                    link: true,
                    linkData: '',
                    objData: currentElementData,
                    drawRect: drawRect,
                    z_index: currentElementData['currentZIndex']
                };
                //draw the Lines
                self.Line.drawSVGLine(tempObj);
            }
            ;
        }
        $('.textElement').each(function(){
            $(this).html(self.convertBb2Html($(this).html()));
        });
    },

    convertBb2Html: function(bbCode){
        var resString = bbCode;
        resString = resString.replace(/(?:\r\n|\r|\n)/g, '<br />');
        resString = resString.replace(/\[b\]/gi, '<strong>');
        resString = resString.replace(/\[\/b\]/gi, '</strong>');
        resString = resString.replace(/\[i\]/gi, '<i>');
        resString = resString.replace(/\[\/i\]/gi, '</i>');
        resString = resString.replace(/\[u\]/gi, '<u>');
        resString = resString.replace(/\[\/u\]/gi, '</u>');

        resString = resString.replace(/\[left\]/gi, '<div class="text-left">');
        resString = resString.replace(/\[\/left\]/gi, '</div>');
        resString = resString.replace(/\[right\]/gi, '<div class="text-right">');
        resString = resString.replace(/\[\/right\]/gi, '</div>');
        resString = resString.replace(/\[center\]/gi, '<div class="text-center">');
        resString = resString.replace(/\[\/center\]/gi, '</div>');
        resString = resString.replace(/\[justify\]/gi, '<div class="text-justify">');
        resString = resString.replace(/\[\/justify\]/gi, '</div>');

        resString = resString.replace(/\[color ?= ?'(#[\w]{6})' ?\]/gi, '<span style="color:$1">');
        resString = resString.replace(/\[\/color\]/gi, '</span>');

        resString = resString.replace(/\[text ?= ?'([\w\-]+)' ?\]/gi, '<span style="font-size:$1">');
        resString = resString.replace(/\[\/text\]/gi, '</span>');

        resString = resString.replace(/\[url ?= ?'([\w\-:\/\[\]\. ]+)' ?tab ?\]/gi, '<a href="$1" target="_blank">');
        resString = resString.replace(/\[url ?= ?'([\w\-:\/\[\]\. ]+)' ?\]/gi, '<a href="$1">');
        resString = resString.replace(/\[\/url\]/gi, '</a>');
        return resString;
    },

    getPopoverInfo: function(el){
        var $this = $(el);
        var elementType = $this.data('type');
        var elementUuid = $this.data('uuid');
        var titleAndIconColor = 'rgb(90,90,90)';
        $.ajax({
            url: "/map_module/mapeditors/popover" + elementType + "Status/" + encodeURIComponent(elementUuid),
            type: "POST",
            dataType: "html",
            error: function(){
            },
            success: function(){
            },
            complete: function(response){
                $.smallBox({
                    title: elementType,
                    content: response.responseText,
                    color: 'rgba(249, 249, 249, 1)',
                    //timeout: 8000,
                    icon: "fa fa-desktop"
                });
                $('.textoFoto').first('<span>').css({'color': titleAndIconColor});
                $('.textoFoto').css('color', '#000000');
                $('.foto').css({'color': titleAndIconColor});
            }
        });
    },


    findParentLineData: function(lineId){
        var currentData = {};
        $('.popoverTypeHidden').each(function(){
            var elementLineId = this.id.replace(/popoverType_/, '');
            if(elementLineId == lineId){
                currentData = {
                    currentUuid: $(this).data('uuid'),
                    currentType: $(this).data('type'),
                    currentColor: $(this).data('color'),
                    currentLink: $(this).data('link'),
                    currentZIndex: $(this).data('zindex'),
                }
            }
        });
        return currentData;
    },

    findParentGadgetData: function(gadgetId){
        var currentData = {};
        $('.popoverGadgetTypeHidden').each(function(){
            var elementGadgetId = this.id.replace(/popoverGadgetType_/, '');
            var perfdata = window['popoverGadgetPerfdata_' + elementGadgetId];
            if(elementGadgetId == gadgetId){
                currentData = {
                    currentUuid: $(this).data('uuid'),
                    currentType: $(this).data('type'),
                    currentLink: $(this).data('link'),
                    currentPerfdata: perfdata,
                    currentState: $(this).data('state'),
                    currentFlapping: $(this).data('flapping'),
                    currentRRDGraphLink: $(this).data('rrdlink'),
                    currentColor: $(this).data('color'),
                    currentShowLabel: $(this).data('showlabel'),
                    currentFontSize: $(this).data('fontsize'),
                    currentZIndex: $(this).data('zindex'),
                }
            }
        });
        return currentData;
    },

    refreshPage: function(){
        //refresh page like cmd+shift+r if true
        //should be false, otherwise every 10sec the webserver has to send the whole page new
        var forceGet = false;
        location.reload(forceGet);
    },

    popoverTitle: function(){
        var elementUuid = $(this).data('uuid');
        return '<h1>' + elementUuid + '</h1>';
    },

    capitaliseFirstLetter: function(string){
        return string.charAt(0).toUpperCase() + string.slice(1);
    },

});
