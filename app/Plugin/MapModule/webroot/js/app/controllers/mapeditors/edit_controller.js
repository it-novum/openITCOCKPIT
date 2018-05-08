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

App.Controllers.MapeditorsEditController = Frontend.AppController.extend({

    $mapContainer: null,
    current: null,
    currentLine: null,
    currentGadget: null,
    currentIcon: null,
    currentText: null,
    mapEditorContainer: '#jsPlumb_playground',

    gridContainer: 'GridContainer',
    gridEnabled: null,
    gridSizeX: null,
    gridSizeY: null,
    gridColor: null,

    magneticGridEnabled: null,

    scaleTextWithGrid: null,

    autohideMenuEnabled: null,
    menuHidden: false,
    originalMenuSize: null,

    defaultZIndex: 0,

    components: ['Ajaxloader', 'Uuid', 'Gadget', 'Grid', 'Line'],

    _initialize: function(){
        var self = this;

        this.Ajaxloader.setup();

        //load initial data
        this.loadInitialData('#addHostObjectId');
        this.loadInitialData('#addServiceHostObjectId');
        this.loadInitialData('#addHostLineObjectId');
        this.loadInitialData('#addServiceLineHostObjectId');
        this.loadInitialData('#addServiceGadgetHostObjectId');

        this.loadElementHostsByAjax();
        this.loadGadgetHostsByAjax();
        this.loadLineHostsByAjax();

        $(document).on('click', '.background', function () {
            self.changeBackground({el: this});
        });

        //on hover on background class show delete button
        $(document).on('mouseenter', '.background-thumbnail', function(){
            //append remove background button
            $(this).append('<div class="deleteBackgroundBtn txt-color-blueDark"><i class="fa fa-trash fa-2x"></i></div>');
        });

        $(document).on('click', '.deleteBackgroundBtn', function(){
            var filename = $(this).parent().find('img').attr('filename-id');
            //write filename to modal dialog
            $('#backgoundFilename').val(filename);
            $('#deleteBackgroundModal').modal('show');

        });

        $('#confirmDeleteBackgroundBtn').click(function(){
            var filename = $('#backgoundFilename').val();
            self.deleteBackground(filename);
            $('#deleteBackgroundModal').modal('hide');
        });

        $(document).on('mouseleave', '.background-thumbnail', function(){
            //delete remove background button
            $(this).children('.deleteBackgroundBtn').remove();
        });

        $(document).on('mouseenter', '.iconset-thumbnail', function(){
            //append remove background button
            $(this).append('<div class="deleteIconsetBtn txt-color-blueDark"><i class="fa fa-trash fa-2x"></i></div>');
        });

        $(document).on('click', '.deleteIconsetBtn', function(){
            var iconSetID = $(this).parent().find('img').attr('iconset-id');
            //write filename to modal dialog
            $('#IconsetId').val(iconSetID);
            $('#deleteIconsetModal').modal('show');

        });

        $('#confirmDeleteIconsetBtn').click(function(){
            var iconSetId = $('#IconsetId').val();
            self.deleteIconsSet(iconSetId);
            $('#deleteIconsetModal').modal('hide');
        });

        $(document).on('mouseleave', '.iconset-thumbnail', function(){
            $(this).children('.deleteIconsetBtn').remove();
        });

        $('#autohide_menu').change(function(){
            if($(this).prop('checked')){
                self.autohideMenuEnabled = true;
            }else{
                self.autohideMenuEnabled = false;
                self.menuAutohide('reset');
            }
        });

        $('#mapMenuMinimizeBtn').click(function(){
            self.menuAutohide('open');
        });

        $('#mapMenuPanel').mouseleave(function(){
            self.menuAutohide('close');
        });

        /*
         *	Grid Container
         */
        var mapEditorContainerHeight = $(self.mapEditorContainer).height();
        var mapEditorContainerWidth = $(self.mapEditorContainer).width();

        var GridContainerStyle = {
            'height': mapEditorContainerHeight,
            'width': mapEditorContainerWidth
        }

        $(self.mapEditorContainer).append($('<div>', {
            id: self.gridContainer,
        }).css(GridContainerStyle));

        $('#MapGridColor').colorpicker({container: $('#OptionsModal')});

        $('#MapGridColor').on('show', function(){
            //set z-index to the colorpicker because otherwise it will appear behind the modal dialog
            // default z-index of modal dialog is 1040
            $('.colorpicker').css({'z-index': '1041'});
        });

        //write HEX color direct into the field
        $('#MapGridColor').change(function(){
            var value = $(this).val();
            $(this).colorpicker('setValue', value);
        })

        // grid colorpicker change event
        $('#MapGridColor').on('changeColor', function(ev){
            if(self.gridEnabled){
                self.gridColor = ev.color.toHex();
                //set value to the input field
                $('#MapGridColor').val(self.gridColor);
                var options = {
                    sizeX: self.gridSizeX,
                    sizeY: self.gridSizeY,
                    gridColor: self.gridColor
                }
                self.showGrid(options);
            }
        });

        $('#removeBG').click(function(){
            self.changeBackground({remove: true});
        });

        this.$mapContainer = $(self.mapEditorContainer);

        //create new text
        $('#createText').click(function(){
            $('#insert-link-area').hide();
            $('#docuText').val('');
            $('#deleteTextPropertiesBtn').hide();
            var textPosition = {};
            //bind click handler to the map after the create text btn was clicked
            $(self.mapEditorContainer).click(function(e){
                //determine the position where the Text shall be positioned
                textPosition = {
                    'x': parseInt(e.pageX - $(this).offset().left),
                    'y': parseInt(e.pageY - $(this).offset().top),
                }

                //create a new uuid
                var elementUuid = self.Uuid.v4();
                self.currentText = {};

                if(self.magneticGridEnabled){
                    //magnetic grid enabled
                    //snap to the nearest grid axis
                    var newPosX = self.roundCoordinates(textPosition.x);
                    var newPosY = self.roundCoordinates(textPosition.y);

                    //create the Text container div
                    $('<div>', {
                        id: elementUuid
                    }).addClass('textContainerStyle dragElement')
                        .css({'top': newPosY + 'px', 'left': newPosX + 'px'})
                        .appendTo(self.$mapContainer);

                    //append the current id to the global Text obj
                    self.currentText = {
                        elementUuid: elementUuid,
                        x: newPosX,
                        y: newPosY
                    }
                    $(self.mapEditorContainer).unbind('click');

                    $('#textWizardModal').modal('show');
                }else{
                    //grid is disabled
                    //free text creation
                    //console.log('grid isnt active -> free text creation');

                    //create the Text container div
                    $('<div>', {
                        id: elementUuid
                    }).addClass('textContainerStyle dragElement')
                        .css({'top': textPosition.y + 'px', 'left': textPosition.x + 'px'})
                        .appendTo(self.$mapContainer);

                    //append the current id to the global Text obj
                    self.currentText = {
                        elementUuid: elementUuid,
                        x: textPosition.x,
                        y: textPosition.y
                    }


                    $(self.mapEditorContainer).unbind('click');

                    $('#textWizardModal').modal('show');
                }
            });
        });

        /*
         * save text
         */
        $('#saveTextPropertiesBtn').click(function(){
            var test = self.currentText

            //console.log($('#editText *'));
            // $('#editText *').filter(':input').each(function(){
            // 	if($(this).hasClass('textInput')){
            // 		self.currentText[$(this).attr('content')] = $(this).val();
            // 	}
            // });

            var zIndex = parseInt($('#editTextZIndex').val(),10);
            if(zIndex < 0){
                zIndex = 0;
            }

            self.currentText['z_index'] = zIndex;
            self.currentText['text'] = $('#docuText').val();

            self.saveText(self.currentText);
        });

        $('.textElement').dblclick(function(){
            $('#tempTextUUID').val($(this).parent().attr('id'));
            self.editText(this);
            //self.editTextElement(this);
        });

        $('#deleteTextPropertiesBtn').click(function(){
            var elUuid = $('#tempTextUUID').val();
            self.deleteText(elUuid);
        });

        // $('.textElement').each(function(){
        // 	var fontSize = $(this).css('font-size');
        //
        // 	$(this).basify({fontSize:fontSize});
        // 	//lineHeight wont be set with the basify plugin in this call
        // 	$(this).css({'line-height':fontSize});
        // });

        $('#dismissTextProperties').click(function(){
            //clear form fields
            $('#editTextText').val('');
            $('#editTextFontSize').val('12');
        });


        /*
         * Grid checkbox
         */

        // Initialize the right value for the hidden input field.
        var $mapGridSizeField = $('#MapGridSizeX');
        var sliderDisabled = true;
        self.gridEnabled = false;
        $('#enable_Grid_Slider').change(function(){
            if($(this).prop('checked')){
                $mapGridSizeField.slider('enable');
                $('#_MapGridSizeX').prop('disabled', false);
                $('#MapGridColor').prop('disabled', false);
                $('#enable_Magnetic_Grid').prop('disabled', false);
                $('#enable_Text_Scale_With_Grid').prop('disabled', false);
                sliderDisabled = false;
                self.gridEnabled = true;
                self.gridSizeX = $('#_MapGridSizeX').val();
                self.gridSizeY = $('#_MapGridSizeX').val();
                self.showGrid({sizeX: self.gridSizeX, sizeY: self.gridSizeY, gridColor: self.gridColor});

                self.refreshTextSize({fontSize: self.gridSizeX});
            }else{
                $mapGridSizeField.slider('disable');
                $('#_MapGridSizeX').prop('disabled', true);
                $('#MapGridColor').prop('disabled', true);
                $('#enable_Magnetic_Grid').prop('disabled', true);
                $('#enable_Text_Scale_With_Grid').prop('disabled', true);
                sliderDisabled = true;
                self.gridEnabled = false;
                self.Grid.removeGrid(self.gridContainer);
            }
        });

        //Grid slider
        var onSlideStop = function(ev){
            if(ev.value == null){
                ev.value = 0;
            }

            $('#_' + $(this).attr('id')).val(ev.value);
            $(this)
                .val(ev.value)
                .trigger('change');

            self.gridSizeX = ev.value;
            self.gridSizeY = ev.value;

            var options = {
                sizeX: self.gridSizeX,
                sizeY: self.gridSizeY,
                gridColor: self.gridColor
            }
            self.showGrid(options);

            self.refreshTextSize({fontSize: self.gridSizeX});
        };

        var $slider = $('input.slider');
        $slider.slider({tooltip: 'hide'});
        $slider.slider('on', 'slide', onSlideStop);
        $slider.slider('on', 'slideStop', onSlideStop);


        // Input this.fields for sliders
        var onChangeSliderInput = function(){
            var $this = $(this);
            $('#' + $this.attr('slider-for'))
                .slider('setValue', parseInt($this.val(), 10))
                .val($this.val())
                .attr('value', $this.val())
                .trigger('change');
            $mapGridSizeField.trigger('change');
        };
        $('.slider-input')
            .on('change.slider', onChangeSliderInput)
            .on('keyup', onChangeSliderInput);

        $('.slider-input').change(function(){
            self.gridSizeX = $(this).val();
            self.gridSizeY = $(this).val();
            options = {
                sizeX: self.gridSizeX,
                sizeY: self.gridSizeY,
                gridColor: self.gridColor,
            }
            self.showGrid(options);

            self.refreshTextSize({fontSize: self.gridSizeX});
        })

        $mapGridSizeField.slider('disable');
        $('#_MapGridSizeX').prop('disabled', sliderDisabled);
        $('#MapGridColor').prop('disabled', sliderDisabled);
        $('#enable_Magnetic_Grid').prop('disabled', sliderDisabled);
        $('#enable_Text_Scale_With_Grid').prop('disabled', sliderDisabled);

        self.scaleTextWithGrid = false;
        self.magneticGridEnabled = false;
        $('#enable_Magnetic_Grid').click(function(){
            if($(this).prop('checked')){
                if(self.gridEnabled){
                    self.magneticGridEnabled = true;
                }
            }else{
                self.magneticGridEnabled = false;
            }
        });

        $('#enable_Text_Scale_With_Grid').click(function(){
            if($(this).prop('checked')){
                if(self.gridEnabled && self.magneticGridEnabled){
                    self.scaleTextWithGrid = true;
                }
            }else{
                self.scaleTextWithGrid = false;
            }
        });

        //eventlistener for the collapsible grid options
        $('#enable_Grid_Slider').change(function(){
            if(self.gridEnabled){
                $('#MapGridCollapseOptions').collapse('show');
            }else{
                $('#MapGridCollapseOptions').collapse('hide');
            }

        });

        /*
         * Create drag and drop upload
         */
        var dropzoneOptObj = {
            //url: '/map_module/backgroundUploads/upload/', //php upload script
            method: 'post',
            maxFilesize: 55, //MB
            acceptedFiles: 'image/*', //mimetypes
            paramName: "file",
            success: function(obj){
                $('#backgrounds-upload-success').show().append(obj.name + ': Successfully uploaded<br />');
                self.refreshBackgroundThumbnails();
            },
            error: function(error, errorMessage, xhr){
                if(typeof errorMessage === 'string'){
                    $('#backgrounds-upload-error').show().append(error.name + ': ' + errorMessage + '<br />');
                }else{
                    $('#backgrounds-upload-error').show().append(errorMessage.data.message + '<br />');
                }
            },
        };

        var dropzoneIconsOptObj = {
            method: 'post',
            maxFilesize: 55, //MB
            acceptedFiles: '.zip', //mimetypes
            paramName: "file",
            success: function(obj){
                $('#icons-upload-success').show().append(obj.name + ': Successfully uploaded<br />');
                self.refreshItemsThumbnails();
                self.refreshItemsDropdown();
            },
            error: function(error, errorMessage, xhr){
                if(typeof errorMessage === 'string'){
                    $('#icons-upload-error').show().append(error.name + ': ' + errorMessage + '<br />');
                }else{
                    $('#icons-upload-error').show().append(errorMessage.data.message + '<br />');
                }
            },
        };

        //prevent Dropzone from throwing unnecessary errors (more than one instance...)
        Dropzone.autoDiscover = false;
        $('.background-dropzone').dropzone(dropzoneOptObj);


        //prevent Dropzone from throwing unnecessary errors (more than one instance...)
        $('.icons-dropzone').dropzone(dropzoneIconsOptObj);

        $('#background-upload-btn').click(function(){
            $('#backgrounds-upload-success').hide().html('');
            $('#backgrounds-upload-error').hide().html('');
            Dropzone.forElement(".background-dropzone").removeAllFiles(true);
        });

        $('#icons-upload-btn').click(function(){
            $('#icons-upload-success').hide().html('');
            $('#icons-upload-error').hide().html('');
            Dropzone.forElement(".icons-dropzone").removeAllFiles(true);
        });

        /*
         * Build up the Gadgets menu in the Panel
         */
        var gadgets = self.Gadget.availableGadgets;

        /* translation and scaling for every gadget must be made here by hand */
        var gadgetScale = {
            'Tacho': {
                value: '0.35',
                translate: ['56', '-140'],
                rotate: '0'
            },
            'Cylinder': {
                value: '0.70',
                translate: ['38', '-6'],
                rotate: '0'
            },
            'Text': {
                value: '0.55',
                translate: ['35', '15'],
                rotate: '0'
            },
            'TrafficLight': {
                value: '0.5',
                translate: ['60', '-48'],
                rotate: '0'
            },
            'RRDGraph': {
                value: '0.26',
                translate: ['70', '-128'],
                rotate: '0'
            },
            'Default': {
                value: '0.5',
                translate: ['0', '0'],
                rotate: '0'
            }
        }

        for(var i = 0; i < gadgets.length; i++){
            //create the container in the menu panel
            $('#gadget-panel')
                .append('<div id="' + gadgets[i] + 'ThumbnailContainer" class="drag-element col-xs-6 col-sm-6 col-md-6 backgroundContainer gadget" data-gadget="' + gadgets[i] + '"></div>');
            //create the thumbnail Container
            $('#' + gadgets[i] + 'ThumbnailContainer').append('<div id="' + gadgets[i] + 'ThumbnailThumbnailContainer" class="thumbnail thumbnailSize"></div>');
            //create the Gadget Thumbnail Container
            $('#' + gadgets[i] + 'ThumbnailThumbnailContainer').append('<div id="' + gadgets[i] + 'Thumbnail"></div>')
            //draw every gadget
            self.Gadget['draw' + gadgets[i]](gadgets[i] + 'Thumbnail', {id: i, contain: false, demo: true});

            if(gadgets[i] in gadgetScale){
                //scale the svg tag for every Gadget
                $('#' + gadgets[i] + 'Thumbnail')
                    .css({
                        '-webkit-transform': 'rotate(' + gadgetScale[gadgets[i]].rotate + ') scale(' + gadgetScale[gadgets[i]].value + ') translate(' + gadgetScale[gadgets[i]].translate[0] + 'px,' + gadgetScale[gadgets[i]].translate[1] + 'px)',
                        '-moz-transform': 'rotate(' + gadgetScale[gadgets[i]].rotate + ') scale(' + gadgetScale[gadgets[i]].value + ') translate(' + gadgetScale[gadgets[i]].translate[0] + 'px,' + gadgetScale[gadgets[i]].translate[1] + 'px)',
                        '-ms-transform': 'rotate(' + gadgetScale[gadgets[i]].rotate + ') scale(' + gadgetScale[gadgets[i]].value + ') translate(' + gadgetScale[gadgets[i]].translate[0] + 'px,' + gadgetScale[gadgets[i]].translate[1] + 'px)',
                        '-o-transform': 'rotate(' + gadgetScale[gadgets[i]].rotate + ') scale(' + gadgetScale[gadgets[i]].value + ') translate(' + gadgetScale[gadgets[i]].translate[0] + 'px,' + gadgetScale[gadgets[i]].translate[1] + 'px)',
                        'transform': 'rotate(' + gadgetScale[gadgets[i]].rotate + ') scale(' + gadgetScale[gadgets[i]].value + ') translate(' + gadgetScale[gadgets[i]].translate[0] + 'px,' + gadgetScale[gadgets[i]].translate[1] + 'px)'
                    });

                //ugly fix for style issue in chrome
                $('#' + gadgets[i] + 'Thumbnail').css({'width': '0px'});
            }else{
                //scale the svg tag for every Gadget with deafult values if there is nothing defined in the gadgetScale Obj
                $('#' + gadgets[i] + 'Thumbnail')
                    .children('svg')
                    .attr({'transform': 'scale(' + gadgetScale['Default'].value + ') translate(' + gadgetScale['Default'].translate[0] + ',' + gadgetScale['Default'].translate[1] + ')'});
            }
        }


        /*
         * Change event for object selector in modal ELEMENT
         */
        $('#ElementWizardChoseType').change(function(){
            var type = $(this).val();
            $('#addElement_' + type).show();
            self.current['type'] = type;

            switch(type){
                case 'host':
                    $('#addHostX').val(self.current['x']);
                    $('#addHostY').val(self.current['y']);
                    $('#addHostZIndex').val(self.current['z_index']);
                    if ('object_id' in self.current) {
                        var selector = '#addHostObjectId';
                        var $selector = $(selector);
                        self.loadInitialData(selector, self.current['object_id'], function(){
                            $selector.val(self.current['object_id']).trigger('chosen:updated');
                        });
                    }
                    //Selected iconset in chosen selectbox
                    $('#addHostIconset').val(self.current['iconset']).trigger("chosen:updated");
                    //hide other forms
                    $('#addElement_service').hide();
                    $('#addElement_hostgroup').hide();
                    $('#addElement_servicegroup').hide();
                    $('#addElement_map').hide();
                    break;
                case 'service':
                    $('#addServiceX').val(self.current['x']);
                    $('#addServiceY').val(self.current['y']);
                    $('#addServiceZIndex').val(self.current['z_index']);
                    if ('host_object_id' in self.current) {
                        var selector = '#addServiceHostObjectId';
                        var $selector = $(selector);
                        self.loadInitialData(selector, self.current['host_object_id'], function(){
                            //insert the host for the service
                            $selector.val(self.current['host_object_id']).trigger('chosen:updated');
                            //trigger change event so that the services can be loaded
                            $selector.change();
                        });
                    }
                    if('object_id' in self.current){
                        $('#addServiceObjectId').val(self.current['object_id']).trigger('chosen:updated');

                    }
                    //Selected iconset in chosen selectbox
                    $('#addServiceIconset').val(self.current['iconset']).trigger("chosen:updated");
                    //hide other forms
                    $('#addElement_host').hide();
                    $('#addElement_hostgroup').hide();
                    $('#addElement_servicegroup').hide();
                    $('#addElement_map').hide();
                    break;
                case 'servicegroup':
                    $('#addServicegroupX').val(self.current['x']);
                    $('#addServicegroupY').val(self.current['y']);
                    $('#addServicegroupZIndex').val(self.current['z_index']);
                    if('object_id' in self.current){
                        $('#addServicegroupObjectId').val(self.current['object_id']).trigger('chosen:updated');
                    }
                    //Selected iconset in chosen selectbox
                    $('#addServicegroupIconset').val(self.current['iconset']).trigger("chosen:updated");
                    //hide other forms
                    $('#addElement_host').hide();
                    $('#addElement_service').hide();
                    $('#addElement_hostgroup').hide();
                    $('#addElement_map').hide();
                    break;
                case 'hostgroup':
                    $('#addHostgroupX').val(self.current['x']);
                    $('#addHostgroupY').val(self.current['y']);
                    $('#addHostgroupZIndex').val(self.current['z_index']);
                    if('object_id' in self.current){
                        $('#addHostgroupObjectId').val(self.current['object_id']).trigger('chosen:updated');
                    }
                    //Selected iconset in chosen selectbox
                    $('#addHostgroupIconset').val(self.current['iconset']).trigger("chosen:updated");
                    //hide other forms
                    $('#addElement_host').hide();
                    $('#addElement_service').hide();
                    $('#addElement_servicegroup').hide();
                    $('#addElement_map').hide();
                    break;
                case 'map':
                    $('#addMapX').val(self.current['x']);
                    $('#addMapY').val(self.current['y']);
                    $('#addMapZIndex').val(self.current['z_index']);
                    if('object_id' in self.current){
                        $('#addMapObjectId').val(self.current['object_id']).trigger('chosen:updated');
                    }
                    //Selected iconset in chosen selectbox
                    $('#addMapIconset').val(self.current['iconset']).trigger("chosen:updated");
                    //hide other forms
                    $('#addElement_host').hide();
                    $('#addElement_service').hide();
                    $('#addElement_hostgroup').hide();
                    $('#addElement_servicegroup').hide();
                    break;
            }
        });


        /*
         * Change event for object selector in modal LINE
         */
        $('#LineWizardChoseType').change(function(){
            var type = $(this).val();
            $('#addLine_' + type).show();
            self.currentLine['type'] = type;

            switch(type){
                case 'host':
                    $('#addHostLineStartX').val(self.currentLine['startX']);
                    $('#addHostLineEndX').val(self.currentLine['endX']);
                    $('#addHostLineStartY').val(self.currentLine['startY']);
                    $('#addHostLineEndY').val(self.currentLine['endY']);
                    $('#addHostLineZIndex').val(self.currentLine['z_index']);
                    if ('object_id' in self.currentLine) {
                        var selector = '#addHostLineObjectId';
                        var $selector = $(selector);
                        self.loadInitialData(selector, self.currentLine['object_id'], function(){
                            $selector.val(self.currentLine['object_id']).trigger('chosen:updated');
                        });
                    }
                    //hide other forms
                    $('#addLine_service').hide();
                    $('#addLine_hostgroup').hide();
                    $('#addLine_servicegroup').hide();
                    $('#addLine_stateless').hide();
                    break;
                case 'service':
                    $('#addServiceLineStartX').val(self.currentLine['startX']);
                    $('#addServiceLineEndX').val(self.currentLine['endX']);
                    $('#addServiceLineStartY').val(self.currentLine['startY']);
                    $('#addServiceLineEndY').val(self.currentLine['endY']);
                    $('#addServiceLineZIndex').val(self.currentLine['z_index']);
                    if ('host_object_id' in self.currentLine) {
                        var selector = '#addServiceLineHostObjectId';
                        var $selector = $(selector);
                        self.loadInitialData(selector, self.currentLine['host_object_id'], function(){
                            //insert the host for the service
                            $selector.val(self.currentLine['host_object_id']).trigger('chosen:updated');
                            //trigger change event so that the services can be loaded
                            $selector.change();
                        });
                    }

                    if('object_id' in self.currentLine){
                        $('#addServiceLineObjectId').val(self.currentLine['object_id']).trigger('chosen:updated');
                    }
                    //hide other forms
                    $('#addLine_host').hide();
                    $('#addLine_hostgroup').hide();
                    $('#addLine_servicegroup').hide();
                    $('#addLine_stateless').hide();
                    break;
                case 'servicegroup':
                    $('#addServicegroupLineStartX').val(self.currentLine['startX']);
                    $('#addServicegroupLineEndX').val(self.currentLine['endX']);
                    $('#addServicegroupLineStartY').val(self.currentLine['startY']);
                    $('#addServicegroupLineEndY').val(self.currentLine['endY']);
                    $('#addServicegroupLineZIndex').val(self.currentLine['z_index']);
                    if('object_id' in self.currentLine){
                        $('#addServicegroupLineObjectId').val(self.currentLine['object_id']).trigger('chosen:updated');
                    }
                    //hide other forms
                    $('#addLine_host').hide();
                    $('#addLine_service').hide();
                    $('#addLine_hostgroup').hide();
                    $('#addLine_stateless').hide();
                    break;
                case 'hostgroup':
                    $('#addHostgroupLineStartX').val(self.currentLine['startX']);
                    $('#addHostgroupLineEndX').val(self.currentLine['endX']);
                    $('#addHostgroupLineStartY').val(self.currentLine['startY']);
                    $('#addHostgroupLineEndY').val(self.currentLine['endY']);
                    $('#addHostgroupLineZIndex').val(self.currentLine['z_index']);
                    if('object_id' in self.currentLine){
                        $('#addHostgroupLineObjectId').val(self.currentLine['object_id']).trigger('chosen:updated');
                    }
                    //hide other forms
                    $('#addLine_host').hide();
                    $('#addLine_service').hide();
                    $('#addLine_servicegroup').hide();
                    $('#addLine_stateless').hide();
                    break;
                case 'stateless':
                    $('#addStatelessLineStartX').val(self.currentLine['startX']);
                    $('#addStatelessLineEndX').val(self.currentLine['endX']);
                    $('#addStatelessLineStartY').val(self.currentLine['startY']);
                    $('#addStatelessLineEndY').val(self.currentLine['endY']);
                    $('#addStatelessLineZIndex').val(self.currentLine['z_index']);

                    //hide other forms
                    $('#addLine_host').hide();
                    $('#addLine_service').hide();
                    $('#addLine_servicegroup').hide();
                    $('#addLine_hostgroup').hide();
                    break;
            }
        });

        /*
         * Change event for object selector in modal GADGET
         */
        $('#GadgetWizardChoseType').change(function(){
            var type = $(this).val();
            type = 'service';
            $('#addGadget_' + type).show();
            self.currentGadget['type'] = type;

            if(self.currentGadget['gadget'] == "RRDGraph"){
                var $form = $('#addGadget_' + type).find('form');
                $form.find('.rrdBackground').removeClass('hidden');
            }

            if(self.currentGadget['gadget'] == "Text"){
                var $form = $('#addGadget_' + type).find('form');
                $form.find('.showLabel').removeClass('hidden');
            }

            switch(type){
                case 'service':
                    $('#addServiceGadgetX').val(self.currentGadget['x']);
                    $('#addServiceGadgetY').val(self.currentGadget['y']);
                    $('#addServiceGadgetZIndex').val(self.currentGadget['z_index']);
                    if ('host_object_id' in self.currentGadget) {

                        var selector = '#addServiceGadgetHostObjectId';
                        var $selector = $(selector);
                        self.loadInitialData(selector, self.currentGadget['host_object_id'], function(){
                            //insert the host for the service
                            $selector.val(self.currentGadget['host_object_id']).trigger('chosen:updated');
                            //trigger change event so that the services can be loaded
                            $selector.change();
                        });
                    }

                    if('object_id' in self.currentGadget){
                        $('#addServiceGadgetObjectId').val(self.currentGadget['object_id']).trigger('chosen:updated');
                    }
                    $('#addServiceGadgetTransparentBackground').prop('checked', parseInt(self.currentGadget['transparent_background']));


                    if(self.currentGadget['show_label'] != null){
                        $('#addServiceGadgetShowLabel').prop('checked', parseInt(self.currentGadget['show_label']));
                    }

                    if(self.currentGadget['font_size'] != null){
                        $('#addServiceGadgetFontSize').val(self.currentGadget['font_size']);
                    }
                    break;
            }
        });


        /*
         * Catch modal save event ELEMENT
         */
        $('#saveElementPropertiesBtn').click(function(){
            $('#addElement_' + self.current['type'] + ' *').filter(':input').each(function(){
                if($(this).hasClass('elementInput')){
                    self.current[$(this).attr('content')] = $(this).val();
                }
            });

            var zIndex = parseInt(self.current['z_index'],10);
            if(zIndex < 0){
                self.current['z_index'] = 0;
            }
            //update element if exist
            if($('#' + self.current['elementUuid']).length > 0){
                var $currentElement = $('#' + self.current['elementUuid']);
                $currentElement.css({'top': self.current['y'] + 'px', 'left': self.current['x'] + 'px', 'z-index': self.current['z_index']});
                $currentElement.children('img').attr('src', '/map_module/img/items/' + self.current['iconset'] + '/ok.png');

                $currentElement.children().filter(':input').each(function(){
                    var fieldKey = $(this).data('key');
                    for(var key in self.current){
                        if(fieldKey == key){
                            $(this).val(self.current[key]);
                        }
                    }
                });
            }else{
                //create new element
                //Set icon to map
                //console.log(self.current['type']);
                if(typeof(self.current['type']) !== 'undefined'){
                    //create new element
                    //Set icon to map
                    self.$mapContainer.append('<div class="itemElement iconContainer dragElement" id="' + self.current['elementUuid'] + '" style="position:absolute; top: ' + self.current['y'] + 'px; left: ' + self.current['x'] + 'px; z-index:'+self.current['z_index']+';"><img src="/map_module/img/items/' + self.current['iconset'] + '/ok.png"></div>');
                    //Save object configuration
                    var $currentElement = $('#' + self.current['elementUuid']);
                    for(var key in self.current){
                        $currentElement.append('<input type="hidden" name="data[Mapitem][' + self.current['elementUuid'] + '][' + key + ']" data-key="' + key + '" value="' + self.current[key] + '" />');
                    }

                    //add eventlistener on newly created items
                    var el = document.getElementById(self.current['elementUuid']);
                    el.addEventListener('dblclick', function(){
                        self.editElements(this);
                    });

                    //draggable function
                    self.makeDraggable();
                }

            }

            $('#addElement_host').hide();
            $('#addElement_service').hide();
            $('#addElement_hostgroup').hide();
            $('#addElement_servicegroup').hide();
            $('#addElement_map').hide();
            $('#ElementWizardModal').modal('hide');
        });

        /*
         * Catch modal save event LINE
         */
        $('#saveLinePropertiesBtn').click(function(){
            $('#addLine_' + self.currentLine['type'] + ' *').filter(':input').each(function(){
                if($(this).hasClass('lineInput')){
                    self.currentLine[$(this).attr('content')] = $(this).val();
                }
            })

            var zIndex = parseInt(self.currentLine['z_index'],10);
            if(zIndex < 0){
                self.currentLine['z_index'] = 0;
            }

            var $currentElement = $('#' + self.currentLine['elementUuid']);
            var currentLineId = $currentElement.data('lineid');

            //check if there are hidden fields in the current element
            //if true the element is in edit mode

            //update line if already exist
            if($('#' + self.currentLine['elementUuid']).length > 0){
                //update the hidden form fields of the line
                $('.lineContainer').filter(function(){
                    if($(this).data('lineid') == currentLineId){
                        $(this).children().each(function(){
                            var $currentField = $(this);
                            for(var key in self.currentLine){
                                if($currentField.data('key') == key){
                                    $currentField.val(self.currentLine[key]);
                                }
                            }
                        });
                    }
                });

                var start = [];
                var end = [];

                start['x'] = parseInt(self.currentLine.startX);
                start['y'] = parseInt(self.currentLine.startY);
                end['x'] = parseInt(self.currentLine.endX);
                end['y'] = parseInt(self.currentLine.endY);

                var currentLineContainerUuid = '';
                var currentLineUuid = '';
                $('.lineHoverElement').filter(function(){
                    if($(this).data('lineId') == currentLineId){
                        currentLineContainerUuid = $(this).parent().attr('id');
                        currentLineUuid = currentLineContainerUuid.replace(/svgLineContainer_/, '');
                    }
                });
                var redrawObj = {
                    start: start,
                    end: end,
                    id: currentLineUuid,
                    svgContainer: currentLineContainerUuid,
                    lineId: currentLineId,
                    z_index: self.currentLine['z_index']
                }


                self.Line.redrawLine(redrawObj);

            }else{
                if(!self.currentLine['object_id']){
                    self.currentLine['object_id'] = null;
                }
                //create new line
                //Save object configuration
                self.$mapContainer.append('<div class="itemElement lineContainer" id="' + self.currentLine['elementUuid'] + '" style="position:absolute; top: ' + self.currentLine['y'] + 'px; left: ' + self.currentLine['x'] + 'px;"></div>');
                var $currentElement = $('#' + self.currentLine['elementUuid']);
                for(var key in self.currentLine){
                    $currentElement.append('<input type="hidden" name="data[Mapline][' + self.currentLine['elementUuid'] + '][' + key + ']" data-key="' + key + '" value="' + self.currentLine[key] + '" />');
                }
                //draggable function
                self.makeDraggable();
            }

            $('#addLine_host').hide();
            $('#addLine_service').hide();
            $('#addLine_hostgroup').hide();
            $('#addLine_servicegroup').hide();
            $('#LineWizardModal').modal('hide');
        });


        /*
         * Catch modal save event GADGET
         */
        $('#saveGadgetPropertiesBtn').click(function(){
            $('#addGadget_' + self.currentGadget['type'] + ' *').filter(':input').each(function(){
                if($(this).hasClass('gadgetInput')){
                    if($(this).attr('type') == 'checkbox'){
                        self.currentGadget[$(this).attr('content')] = +($(this).prop('checked'));
                    }else{
                        self.currentGadget[$(this).attr('content')] = $(this).val();
                    }

                }
            });

            var zIndex = parseInt(self.currentGadget['z_index'],10);
            if(zIndex < 0){
                self.currentGadget['z_index'] = 0;
            }

            //update Gadget if exist
            if($('#' + self.currentGadget['elementUuid']).length > 0){
                options = {};

                var $currentGadget = $('#' + self.currentGadget['elementUuid']);
                $currentGadget.children().filter(':input').each(function(){
                    var fieldKey = $(this).data('key');
                    for(var key in self.currentGadget){
                        if(fieldKey == key){
                            $(this).val(self.currentGadget[key]);
                            options[key] = self.currentGadget[key];
                        }
                    }
                });

                if(typeof(self.currentGadget['font_size']) != null && typeof(self.currentGadget['show_label']) != null){
                    options['fontSize'] = self.currentGadget['font_size'];
                    options['showLabel'] = self.currentGadget['show_label'];
                }
                if(typeof(self.currentGadget['size_x']) != null && typeof(self.currentGadget['size_y']) != null){
                    options['sizeX'] = self.currentGadget['size_x'];
                    options['sizeY'] = self.currentGadget['size_y'];
                }

                options['z_index'] = self.currentGadget['z_index'];

                options['demo'] = true;

                gadgetId = options['id'];

                var gadgetUUID = self.getGadgetUuidFromDataUuid(self.currentGadget['elementUuid']);

                var strippedId = gadgetUUID.replace(/svgContainer_/, '');
                options['id'] = strippedId;
                //clear the svg element
                $('#' + gadgetUUID).children('svg').empty();
                //redraw gadget
                self.Gadget['draw' + self.currentGadget['gadget']](gadgetUUID, options);

            }else{
                //create new gadget
                //Set icon to map
                self.$mapContainer.append('<div class="itemElement gadgetContainer" id="' + self.currentGadget['elementUuid'] + '" style="position:absolute; top: ' + self.currentGadget['y'] + 'px; left: ' + self.currentGadget['x'] + 'px;"></div>');
                //Save object configuration
                var $currentGadget = $('#' + self.currentGadget['elementUuid']);

                //create container div for the gadget
                $('<div id="svgContainer_' + self.currentGadget['elementUuid'] + '"></div>')
                    .appendTo(self.mapEditorContainer);

                //call the gadgetComponent to create the SVG
                self.Gadget['draw' + self.currentGadget['gadget']]('svgContainer_' + self.currentGadget['elementUuid'], {
                    id: self.currentGadget['elementUuid'],
                    x: self.currentGadget['x'],
                    y: self.currentGadget['y'],
                    sizeX: self.currentGadget['size_x'],
                    sizeY: self.currentGadget['size_y'],
                    showLabel: self.currentGadget['show_label'],
                    fontSize: self.currentGadget['font_size'],
                    demo: true
                });
                //fill the hidden data fields for the Gadget
                for(var key in self.currentGadget){
                    $currentGadget.append('<input type="hidden" name="data[Mapgadget][' + self.currentGadget['elementUuid'] + '][' + key + ']" data-key="' + key + '" value="' + self.currentGadget[key] + '" />');
                    $('#svgContainer_' + self.currentGadget['elementUuid']).css({'z-index': self.currentGadget['z_index']}).addClass('itemElement dragElement gadgetSVGContainer');
                }

                $('#' + self.currentGadget['elementUuid']).append('<div class="saveBeforeEdit">Please Save before edit!</div>');

                //draggable function
                self.makeDraggable();
            }

            $('#GadgetWizardModal').modal('hide');
        });

        /*
         * Catch modal edit event from STATELESSICON
         */

        $('#saveStatelessIconPropertiesBtn').click(function(){
            $('#editStatelessIcons *').filter(':input').each(function(){
                if($(this).hasClass('statelessIconInput')){
                    self.currentIcon[$(this).attr('content')] = $(this).val();
                }
            });

            var zIndex = parseInt(self.currentIcon['z_index'],10);
            if(zIndex < 0){
                self.currentIcon['z_index'] = 0;
            }

            var $currentIcon = $('#' + self.currentIcon['elementUuid']);
            $currentIcon.css({'top': self.currentIcon['y'] + 'px', 'left': self.currentIcon['x'] + 'px', 'z-index': self.currentIcon['z_index']});

            $currentIcon.children().filter(':input').each(function(){
                var fieldKey = $(this).data('key');
                for(var key in self.currentIcon){
                    if(fieldKey == key){
                        $(this).val(self.currentIcon[key]);
                    }
                }
            });

            $('#StatelessIconWizardModal').modal('hide');
        });

        /*
         * Catch modal close event ELEMENT
         */
        $('#ElementWizardModal').on('hidden.bs.modal', function(){
            $('#ElementWizardChoseType').val('').trigger('chosen:updated');
            //Clear form
            $("[element-property]").each(function(intKey, object){
                var $object = $(object);
                switch($object.attr('element-property')){
                    case 'chosen':
                        $object.val('').trigger('chosen:updated');
                        break;

                    default:
                        $object.val('');
                        break;
                }
            });

            //set text for Element save button
            $('#saveElementPropertiesBtn').text($('#elementAddSaveText').val());

            $('#addElement_host').hide();
            $('#addElement_service').hide();
            $('#addElement_hostgroup').hide();
            $('#addElement_servicegroup').hide();
            $('#addElement_map').hide();
        });


        /*
         * Catch modal close event LINE
         */
        $('#LineWizardModal').on('hidden.bs.modal', function(){
            $('#LineWizardChoseType').val('').trigger('chosen:updated');
            //Clear form
            $("[element-property]").each(function(intKey, object){
                var $object = $(object);
                switch($object.attr('element-property')){
                    case 'chosen':
                        $object.val('').trigger('chosen:updated');
                        break;

                    default:
                        $object.val('');
                        break;
                }
            });

            $('#addLine_host').hide();
            $('#addLine_service').hide();
            $('#addLine_hostgroup').hide();
            $('#addLine_servicegroup').hide();
        });

        /*
         * Catch modal close event Gadget
         */
        $('#GadgetWizardModal').on('hidden.bs.modal', function(){
            $('#GadgetWizardChoseType').val('').trigger('chosen:updated');
            //Clear form
            $("[element-property]").each(function(intKey, object){
                var $object = $(object);
                switch($object.attr('element-property')){
                    case 'chosen':
                        $object.val('').trigger('chosen:updated');
                        break;

                    case 'text':
                        if($object.attr('id') != 'addServiceGadgetFontSize'){
                            $object.val('');
                        }
                        break;

                    default:
                        if($object.attr('id') != 'addServiceGadgetShowLabel'){
                            $object.val('');
                        }
                        break;
                }
            });

            //set text for Element save button
            $('#saveGadgetPropertiesBtn').text($('#gadgetAddSaveText').val());

            $('.rrdBackground').addClass('hidden');
            $('.showLabel').addClass('hidden');
        });

        $('#addServiceHostObjectId, #addServiceLineHostObjectId, #addServiceGadgetHostObjectId').change(function () {
            var triggeredType = this.id;

            var hostId = $(this).val();

            $.ajax({
                url: "/map_module/mapeditors/servicesForWizard/" + encodeURIComponent(hostId),
                type: "POST",
                dataType: "html",
                error: function(){
                },
                success: function(){
                },
                complete: function(response){
                    if(triggeredType == 'addServiceLineHostObjectId'){
                        $('#addServiceLineObjectId').html(response.responseText);
                        $('#addServiceLineObjectId').trigger('chosen:updated');
                        $('#addServiceLineObjectId').val(self.currentLine['object_id']).trigger('chosen:updated');
                    }else if(triggeredType == 'addServiceGadgetHostObjectId'){
                        $('#addServiceGadgetObjectId').html(response.responseText);
                        $('#addServiceGadgetObjectId').trigger('chosen:updated');
                        $('#addServiceGadgetObjectId').val(self.currentGadget['object_id']).trigger('chosen:updated');
                    }else{
                        $('#addServiceObjectId').html(response.responseText);
                        $('#addServiceObjectId').trigger('chosen:updated');
                        $('#addServiceObjectId').val(self.current['object_id']).trigger('chosen:updated');
                    }
                }.bind(self)
            });
        })

        //open edit mode
        $(document).ready(function(){
            $('.itemElement').on('dblclick', function(e){
                self.editElements(this);
            });
        });


        //create gadgets if there are some..
        if(this.getVar('map_gadgets')){
            //reconstruct gadgets
            var mapGadgets = this.getVar('map_gadgets');
            for(var i = 0; i < mapGadgets.length; i++){
                //skip gadgets which are falsely migrated (no gadget name)
                if(mapGadgets[i]['gadget'] == 'null' || mapGadgets[i]['gadget'] == ''){
                    continue;
                }
                var tempUuid = this.Uuid.v4();

                $('<div id="svgContainer_' + tempUuid + '"></div>')
                    .appendTo(self.mapEditorContainer);

                var containerData = {
                    gadgetId: mapGadgets[i]['id'],
                }

                self.Gadget['draw' + mapGadgets[i]['gadget']]('svgContainer_' + tempUuid, {
                    id: tempUuid,
                    x: mapGadgets[i]['x'],
                    y: mapGadgets[i]['y'],
                    sizeX: mapGadgets[i]['size_x'],
                    sizeY: mapGadgets[i]['size_y'],
                    containerData: containerData,
                    fontSize: mapGadgets[i]['font_size'],
                    showLabel: mapGadgets[i]['show_label'],
                    demo: true
                });
                $('#svgContainer_' + tempUuid).css({'z-index': mapGadgets[i]['z_index']}).attr({'data-gadgetid': mapGadgets[i]['id']}).addClass('itemElement dragElement gadgetSVGContainer');
            }
            ;
        }


        start = [];
        end = [];
        wasClicked = false;

        if((this.getVar('map_lines'))){
            var mapLines = this.getVar('map_lines');

            for(var i = 0; i < mapLines.length; i++){
                var tempUuid = self.Uuid.v4();

                start['x'] = parseInt(mapLines[i]['startX']);
                start['y'] = parseInt(mapLines[i]['startY']);
                end['x'] = parseInt(mapLines[i]['endX']);
                end['y'] = parseInt(mapLines[i]['endY']);

                $('<div id="svgLineContainer_' + tempUuid + '"></div>')
                    .appendTo(this.mapEditorContainer);
                var drawRect = true;
                if(mapLines[i].type == 'stateless'){
                    drawRect = false;
                }
                var tempObj = {
                    start: start,
                    end: end,
                    lineId: mapLines[i]['id'],
                    id: tempUuid,
                    svgContainer: 'svgLineContainer_' + tempUuid,
                    z_index: mapLines[i]['z_index']
                    //drawRect:drawRect,
                };
                self.Line.drawSVGLine(tempObj);
            }
        }

        $('#createLine').click(function(){
            $(self.mapEditorContainer).css('cursor', 'crosshair');

            //show info box
            $('#lineInfoBox').show();
            $('#lineInfoText').text($('#linePoint1').val());

            $(self.mapEditorContainer).click(function(e){
                self.drawLine(e);
            });
        });

        /*
         * make the elements draggable at editor init
         */
        self.activateItemsDraggable();
        self.makeDraggable();


        this.$currentColor = $('#currentColor');
        this.$textarea = $('#docuText');

        // Bind click event for color selector
        $("[select-color='true']").click(function(){
            var color = $(this).attr('color');
            self.$textarea.surroundSelectedText("[color='" + color + "']", '[/color]');
        });

        // Bind click event for font size selector
        $("[select-fsize='true']").click(function(){
            var fontSize = $(this).attr('fsize');
            self.$textarea.surroundSelectedText("[text='" + fontSize + "']", "[/text]");
        });


        // Bind click event to insert hyperlinks
        $('#perform-insert-link').click(function(){
            var url;
            if($('#insert-link-type').val() == '0'){
                url = $('#insert-link-map').val();
            }else{
                url = $('#insert-link-url').val();
            }
            var description = $('#insert-link-description').val();
            var sel = self.$textarea.getSelection();
            var newTab = $('#insert-link-tab').is(':checked') ? " tab" : "";
            self.$textarea.insertText("[url='" + url + "'" + newTab + "]" + description + '[/url]', sel.start, "collapseToEnd");
            $('#insert-link-area').hide();
            $('#insert-text-area').show();
            $('#insert-modal-footer').show();
        });

        $('#insert-link-type').change(function(){
            if($(this).val() == '0'){
                $('#link-map-area').show();
                $('#link-url-area').hide();
            }else{
                $('#link-map-area').hide();
                $('#link-url-area').show();
            }
        });

        // Bind other buttons
        $("[wysiwyg='true']").click(function(){
            var task = $(this).attr('task');
            switch(task){
                case 'bold':
                    self.$textarea.surroundSelectedText('[b]', '[/b]');
                    break;

                case 'italic':
                    self.$textarea.surroundSelectedText('[i]', '[/i]');
                    break;

                case 'underline':
                    self.$textarea.surroundSelectedText('[u]', '[/u]');
                    break;

                case 'left':
                    self.$textarea.surroundSelectedText('[left]', '[/left]');
                    break;

                case 'center':
                    self.$textarea.surroundSelectedText('[center]', '[/center]');
                    break;

                case 'right':
                    self.$textarea.surroundSelectedText('[right]', '[/right]');
                    break;

                case 'justify':
                    self.$textarea.surroundSelectedText('[justify]', '[/justify]');
                    break;
            }
        });

        $('#insert-link').click(function(){
            $('#insert-link-url').val('');
            $('#insert-link-description').val('');
            $('#insert-link-area').show();
            $('#insert-text-area').hide();
            $('#insert-modal-footer').hide();
        });

        $('#cancel-insert-link').click(function(){
            $('#insert-link-area').hide();
            $('#insert-text-area').show();
            $('#insert-modal-footer').show();
        });

        $('.textElement').each(function(){
            $(this).html(self.convertBb2Html($(this).html()));
        });
    },

    loadLineHostsByAjax:function(){
        var LineAjaxObj = new ChosenAjax({
            id: 'addHostLineObjectId' //Target select box
        });

        LineAjaxObj.setCallback(function(searchString){
            console.log('Searchstring: ' + searchString);

            $.ajax({
                dataType: "json",
                url: '/hosts/loadHostsByString.json',
                data: {
                    'angular': true,
                    'filter[Host.name]': searchString,
                    'selected[]': [] //ids
                },
                success: function(response){
                    LineAjaxObj.addOptions(response.hosts);
                }
            });
        });
        LineAjaxObj.render();


        var LineServiceHostsAjaxObj = new ChosenAjax({
            id: 'addServiceLineHostObjectId' //Target select box
        });

        LineServiceHostsAjaxObj.setCallback(function(searchString){
            console.log('Searchstring: ' + searchString);

            $.ajax({
                dataType: "json",
                url: '/hosts/loadHostsByString.json',
                data: {
                    'angular': true,
                    'filter[Host.name]': searchString,
                    'selected[]': [] //ids
                },
                success: function(response){
                    LineServiceHostsAjaxObj.addOptions(response.hosts);
                    //fix for single result in checkbox
                    if(response.hosts.length == 1){
                        $('#addServiceLineHostObjectId').trigger('change');
                    }
                }
            });
        });
        LineServiceHostsAjaxObj.render();
    },

    loadGadgetHostsByAjax: function(selectedHostIds){
        if(selectedHostIds == null || selectedHostIds.length < 1){
            selectedHostIds = [];
        }else{
            if(!Array.isArray(selectedHostIds)){
                selectedHostIds = [selectedHostIds];
            }
        }

        var GadgetAjaxObj = new ChosenAjax({
            id: 'addServiceGadgetHostObjectId' //Target select box
        });

        GadgetAjaxObj.setSelected(selectedHostIds);

        GadgetAjaxObj.setCallback(function(searchString){
            console.log('Searchstring: ' + searchString);

            $.ajax({
                dataType: "json",
                url: '/hosts/loadHostsByString.json',
                data: {
                    'angular': true,
                    'filter[Host.name]': searchString,
                    'selected[]': selectedHostIds //ids
                },
                success: function(response){
                    GadgetAjaxObj.addOptions(response.hosts);
                    //fix for single result in checkbox
                    if(response.hosts.length == 1){
                        $('#addServiceGadgetHostObjectId').trigger('change');
                    }
                }
            });
        });
        GadgetAjaxObj.render();
    },

    loadInitialData: function(selector, selectedHostIds, callback){
        var self = this;
        if(selectedHostIds == null || selectedHostIds.length < 1){
            selectedHostIds = [];
        }else{
            if(!Array.isArray(selectedHostIds)){
                selectedHostIds = [selectedHostIds];
            }
        }

        $.ajax({
            dataType: "json",
            url: '/hosts/loadHostsByString.json',
            data: {
                'angular': true,
                'selected[]': selectedHostIds //ids
            },
            success: function(response){
                var $selector = $(selector);
                var list = self.buildList(response.hosts);
                $selector.empty();
                $selector.append(list);
                $selector.val(selectedHostIds);
                $selector.trigger('chosen:updated');

                if(callback != undefined){
                    callback();
                }
            }
        });
    },


    buildList: function(data){
        var html = '';
        for(var i in data){
            html += '<option value="' + data[i].key + '">'+htmlspecialchars(data[i].value)+'</option>';
        }
        return html;
    },

    loadElementHostsByAjax: function(){
        var ElementAjaxObj = new ChosenAjax({
            id: 'addHostObjectId' //Target select box
        });

        ElementAjaxObj.setCallback(function(searchString){
            console.log('Searchstring: ' + searchString);

            $.ajax({
                dataType: "json",
                url: '/hosts/loadHostsByString.json',
                data: {
                    'angular': true,
                    'filter[Host.name]': searchString,
                    'selected[]': [] //ids
                },
                success: function(response){
                    ElementAjaxObj.addOptions(response.hosts);
                }
            });
        });

        ElementAjaxObj.render();

        var ElementServiceHostsAjaxObj = new ChosenAjax({
            id: 'addServiceHostObjectId' //Target select box
        });

        ElementServiceHostsAjaxObj.setCallback(function(searchString){
            console.log('Searchstring: ' + searchString);

            $.ajax({
                dataType: "json",
                url: '/hosts/loadHostsByString.json',
                data: {
                    'angular': true,
                    'filter[Host.name]': searchString,
                    'selected[]': [] //ids
                },
                success: function(response){
                    ElementServiceHostsAjaxObj.addOptions(response.hosts);
                    //fix for single result in checkbox
                    if(response.hosts.length == 1){
                        $('#addServiceHostObjectId').trigger('change');
                    }
                }
            });
        });

        ElementServiceHostsAjaxObj.render();
    },

    getGadgetUuidFromDataUuid: function(dataFieldUuid){
        if($('#' + dataFieldUuid).length > 0){
            var gadgetId = $('#' + dataFieldUuid).data('gadgetid');
            var $element = $(document).find("[data-gadgetid='" + gadgetId + "'][id^='svgContainer_']");
            return $element.attr('id');
        }
        return false;
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

    activateItemsDraggable: function(){
        var self = this;
        /*
         * Draggable icons
         */
        $(self.mapEditorContainer).droppable({
            accept: '.drag-element',
            drop: function(e, ui){
                if(ui.draggable.hasClass('gadget')){
                    //is gadget
                    var offset = $('#MapContainer').offset();
                    var options = {
                        type: 'gadget',
                        x: e.pageX - offset.left,
                        y: e.pageY - offset.top,
                        data: ui.draggable.data('gadget'),
                    }
                    self.dropOptions(options);
                }else if(ui.draggable.hasClass('statelessIcon')){
                    //is icon
                    var offset = $('#MapContainer').offset();
                    var options = {
                        type: 'icon',
                        x: e.pageX - offset.left,
                        y: e.pageY - offset.top,
                        icon: ui.draggable.find('img').attr('icon')
                    }
                    self.dropOptions(options);

                    self.saveStatelessIcon();
                }else{
                    //is item
                    var drop = [];
                    var offset = $('#MapContainer').offset();
                    var options = {
                        type: 'item',
                        x: e.pageX - offset.left,
                        y: e.pageY - offset.top,
                        iconset: $(ui.draggable).find('img').attr('iconset')
                    }
                    self.dropOptions(options);
                }
            },
        });

        $('.drag-element').draggable({
            helper: 'clone',
            revert: 'invalid',
            appendTo: 'body',
            cursorAt: {
                top: 5,
                left: 5
            },
            start: function(e, ui){
                if(ui.helper.children('img').hasClass('iconset')){
                    ui.helper.removeClass('thumbnail');
                }
                ui.helper.removeClass('col-xs-6 col-sm-6 col-md-6');
                ui.helper.children().removeClass('thumbnail');
            }
        });
    },


    /**
     * Draw a line to the map
     * @param  {object} event The click event when the user clicks on the map after clicking the create line btn
     * @return {void}
     */
    drawLine: function(event){
        var offset = $(this.mapEditorContainer).offset();

        if(!wasClicked){
            //show info box
            $('#lineInfoText').text($('#linePoint2').val());

            if(this.magneticGridEnabled){
                var positions = {
                    x: event.pageX - offset.left,
                    y: event.pageY - offset.top
                }
                var newPositions = this.magneticPosition(positions);
                start['x'] = newPositions.x;
                start['y'] = newPositions.y;
            }else{
                start['x'] = event.pageX - offset.left;
                start['y'] = event.pageY - offset.top;
            }
            wasClicked = true;
            return true;
        }

        if(wasClicked){
            this.currentLine = [];

            if(this.magneticGridEnabled){
                var positions = {
                    x: event.pageX - offset.left,
                    y: event.pageY - offset.top
                }
                var newPositions = this.magneticPosition(positions);
                end['x'] = newPositions.x;
                end['y'] = newPositions.y;
            }else{
                end['x'] = event.pageX - offset.left;
                end['y'] = event.pageY - offset.top;
            }
            wasClicked = false;

            var tempUuid = this.Uuid.v4();


            $(this.mapEditorContainer).append('<div id="svgLineContainer_' + tempUuid + '"></div>');
            $('<div id="svgLineContainer_' + tempUuid + '"></div>')
                .appendTo(this.mapEditorContainer);

            var el2append = '<div class="saveBeforeEdit">Please Save before edit!</div>'

            var obj = {
                start: start,
                end: end,
                id: tempUuid,
                el2append: el2append,
                svgContainer: 'svgLineContainer_' + tempUuid
            }

            this.Line.drawSVGLine(obj);

            this.currentLine['elementUuid'] = tempUuid;
            this.currentLine['startX'] = parseInt(obj.start['x']);
            this.currentLine['endX'] = parseInt(obj.end['x']);
            this.currentLine['startY'] = parseInt(obj.start['y']);
            this.currentLine['endY'] = parseInt(obj.end['y']);
            this.currentLine['iconset'] = 'std_line';
            this.currentLine['z_index'] = this.defaultZIndex;

            $(this.mapEditorContainer).css('cursor', 'auto');
            $(this.mapEditorContainer).unbind('click');

            //reset the box text
            $('#lineInfoText').text($('#linePoint1').val());
            //close info box
            $('#lineInfoBox').hide();

            //show modal edit dialog
            $('#LineWizardModal').modal('show');
        }
    },


    /**
     * delete a item from the map
     *
     * @param  {String} el The item which shall be deleted
     * @return {void}
     */
    deleteElement: function(el){
        //delete element and all its child html elements
        $(el).remove();
        //close modal box
        $('#ElementWizardModal').modal('hide');
    },


    /**
     * delete a Gadget from the map
     * and also its data fields
     *
     * @param  {string} el The Gadget wich shall be deleted
     * @return {void}
     */
    deleteGadget: function(el){
        $('.gadgetContainer[data-gadgetid]').each(function(){
            if($(this).data('gadgetid') == $(el).data('gadgetid')){
                //remove the div which contains all the data of the gadget
                $(this).remove();
            }
        });
        //remove the element
        $(el).remove();

        /*$('.elementHover[data-gadgetid]').each(function(){
         if($(this).data('gadgetid') == $(el).data('gadgetid')){
         //remove the svg drawing
         var $gadgetElement = $(this).parentsUntil('svg', 'g');
         $gadgetElement.remove();
         }
         });*/
        $('#GadgetWizardModal').modal('hide');
    },


    /**
     * delete a Line from the map
     *
     * @param  {string} el The Line which shall be deleted
     * @return {void}
     */
    deleteLine: function(el){
        var lineId = $(el).data('lineId');
        var strippedId = el.id.replace(/_rect/, '');
        $('[id^=' + strippedId + ']').each(function(){
            //remove the line and all elements with the same id except the data container
            $(this).remove();
        });

        //get values from input fields
        $('.itemElement[data-lineId]').each(function(){
            if($(this).data('lineid') == lineId){
                $(this).remove();
            }
        });
        $('#LineWizardModal').modal('hide');
    },


    /**
     * delete a stateless Icon
     *
     * @param  {string} el The Icon which shall be deleted
     * @return {void}
     */
    deleteIcon: function(el){
        //delete element and all its child html elements
        $(el).remove();
        //close modal box
        $('#StatelessIconWizardModal').modal('hide');
    },


    /**
     * Make the Elements on the Map draggable.
     * To refresh the Drag handler you can also call this function
     *
     * @return {void}
     */
    makeDraggable: function(){
        var self = this;
        $('.dragElement').draggable({
            drag: function(e, ui){
                //snap to grid while dragging
                var $currentElement = $(this);
                if(ui.helper.attr('data-gadgetid')){
                    var gadgetId = ui.helper.data('gadgetid');
                    //gadget
                    $('.gadgetContainer[data-gadgetid]').each(function(){
                        if($(this).data('gadgetid') == gadgetId){
                            $(this).children().each(function(){
                                switch($(this).data('key')){
                                    case 'x':
                                        if(self.magneticGridEnabled){
                                            //snap element to grid
                                            ui.position.left = self.roundCoordinates(ui.position.left);
                                            $currentElement.css({'left': self.roundCoordinates(ui.position.left)});
                                        }
                                        break;
                                    case 'y':
                                        if(self.magneticGridEnabled){
                                            //snap element to grid
                                            ui.position.top = self.roundCoordinates(ui.position.top);
                                            $currentElement.css({'top': self.roundCoordinates(ui.position.top)});
                                        }
                                        break;
                                }
                            });
                        }
                    });
                }else{
                    ui.helper.children().each(function(){
                        switch($(this).data('key')){
                            case 'x':
                                if(self.magneticGridEnabled){
                                    //snap element to grid
                                    ui.position.left = self.roundCoordinates(ui.position.left);
                                    $currentElement.css({'left': self.roundCoordinates(ui.position.left)});
                                }
                                break;
                            case 'y':
                                if(self.magneticGridEnabled){
                                    //snap element to grid
                                    ui.position.top = self.roundCoordinates(ui.position.top);
                                    $currentElement.css({'top': self.roundCoordinates(ui.position.top)});
                                }
                                break;
                        }
                    });
                }
            },
            stop: function(e, ui){
                var $currentElement = $(this);
                //save the new coordinates on drag to the form fields
                if(ui.helper.attr('data-gadgetid')){
                    var gadgetId = ui.helper.data('gadgetid');
                    //gadget
                    $('.gadgetContainer[data-gadgetid]').each(function(){
                        if($(this).data('gadgetid') == gadgetId){
                            $(this).children().each(function(){
                                switch($(this).data('key')){
                                    case 'x':
                                        if(self.magneticGridEnabled){
                                            //snap element to grid
                                            $currentElement.css({'left': self.roundCoordinates(ui.position.left)});
                                            $(this).val(self.roundCoordinates(ui.position.left));
                                        }else{
                                            //free element drag
                                            $(this).val(ui.position.left);
                                        }
                                        break;
                                    case 'y':
                                        if(self.magneticGridEnabled){
                                            //snap element to grid
                                            $currentElement.css({'top': self.roundCoordinates(ui.position.top)});
                                            $(this).val(self.roundCoordinates(ui.position.top));
                                        }else{
                                            //free element drag
                                            $(this).val(ui.position.top);
                                        }
                                        break;
                                }
                            });
                        }
                    });
                }else{
                    ui.helper.children().each(function(){
                        switch($(this).data('key')){
                            case 'x':
                                if(self.magneticGridEnabled){
                                    //snap element to grid
                                    $currentElement.css({'left': self.roundCoordinates(ui.position.left)});
                                    $(this).val(self.roundCoordinates(ui.position.left));
                                }else{
                                    //free element drag
                                    $(this).val(ui.position.left);
                                }
                                break;
                            case 'y':
                                if(self.magneticGridEnabled){
                                    //snap element to grid
                                    $currentElement.css({'top': self.roundCoordinates(ui.position.top)});
                                    $(this).val(self.roundCoordinates(ui.position.top));
                                }else{
                                    //free element drag
                                    $(this).val(ui.position.top);
                                }
                                break;
                        }
                    });
                }
            },
        });
    },


    /**
     * changes the Background picture of the Map
     *
     * @param  {object} opt The Options for chaning the Background
     * @return {void}
     */
    changeBackground: function(opt){
        var self = this;
        var opt = opt || {};
        var el = opt.el || null;
        var remove = opt.remove || false;
        var $mapContainer = $(this.mapEditorContainer);
        var $gridContainer = $('#' + this.gridContainer);
        if(!remove){
            var imageSrc = $(el).attr('original');

            $mapContainer.css({'background-image': 'url(' + imageSrc + ')', 'background-repeat': 'no-repeat'});

            //Resizing the map container to new image size
            var image = new Image();
            $(image).on('load', function(){

                $mapContainer.css({'height': image.height + 'px', 'width': image.width + 'px'});
                $gridContainer.css({'height': image.height + 'px', 'width': image.width + 'px'});

                if(self.gridEnabled){
                    var options = {
                        sizeX: self.gridSizeX,
                        sizeY: self.gridSizeY,
                        gridColor: self.gridColor
                    }
                    self.showGrid(options);
                }
            });
            image.src = imageSrc;
            $('#MapBackground').val($(el).attr('filename'));

            $('#removeBG').show();
        }else{
            //background shall be removed
            $('#MapBackground').val('');
            $mapContainer.css({
                'background-image': 'none',
                'background-repeat': 'repeat',
                'width': '100%',
                'height': '100%'
            });

            mapContainerHeight = $mapContainer.height();
            mapContainerWidth = $mapContainer.width();
            $gridContainer.css({'height': mapContainerHeight + 'px', 'width': mapContainerWidth + 'px'})

            $('#removeBG').hide();
        }
    },


    /**
     * Get the Host from a service object id
     *
     * @param  {number} serviceObjectId The Service Object Id of the service from wich the host shall be received
     * @param  {string} type            The Type of the wizard which shall be triggered after the complete event was fired
     * @return {void}
     */
    hostFromService: function(serviceObjectId, type){
        //get the host from a serviceID
        var self = this;
        $.ajax({
            url: "/map_module/mapeditors/hostFromService/" + encodeURIComponent(serviceObjectId),
            type: "POST",
            //dataType: "json",
            error: function () {
            },
            success: function(){
            },

            complete: function (response) {
                switch (type) {
                    case 'element':
                        self.current['host_object_id'] = response.responseText;
                        $('#ElementWizardChoseType').val(self.current.type);
                        $('#ElementWizardChoseType').trigger("chosen:updated");
                        $('#ElementWizardChoseType').trigger("change");
                        break;
                    case 'line':
                        self.currentLine['host_object_id'] = response.responseText;
                        $('#LineWizardChoseType').val(self.currentLine.type);
                        $('#LineWizardChoseType').trigger("chosen:updated");
                        $('#LineWizardChoseType').trigger("change");
                        break;
                    case 'gadget':

                        self.currentGadget['host_object_id'] = response.responseText;
                        $('#GadgetWizardChoseType').val(self.currentGadget.type);
                        $('#GadgetWizardChoseType').trigger("chosen:updated");
                        $('#GadgetWizardChoseType').trigger("change");
                        break;
                }

            }.bind(self)
        });
    },


    /**
     * Edit Elements like Gadgets, Lines, Items or stateless Icons
     *
     * @param  {string} el The Element which shall be edited
     * @return {void}
     */
    editElements: function(el){
        var self = this;
        var currentElement = el;

        if($(currentElement).hasClass('gadgetSVGContainer')){
            //gadget
            //check if there is a Gadget id
            if($(currentElement).data('gadgetid') != undefined){
                if($('#deleteGadgetBtn').length > 0){
                    $('#deleteGadgetBtn').remove();
                }
                $('.gadgetWizardFooter').prepend($('<button>', {
                    id: 'deleteGadgetBtn',
                    click: function(){
                        self.deleteGadget(currentElement);
                    },
                    type: 'button',
                    class: 'btn btn-danger',
                    text: 'Delete'
                }));

                self.currentGadget = [];

                //show modal edit dialog
                $('#GadgetWizardModal').modal('show');
                //trigger change event for dropdown list
                $('#GadgetWizardChoseType').trigger('change');

                $('#saveGadgetPropertiesBtn').text($('#gadgetEditSaveText').val());

                var gadgetId = $(currentElement).data('gadgetid');
                gadgetId = parseInt(gadgetId);
                //get values from input fields
                $('.gadgetContainer[data-gadgetid]').each(function(){
                    if($(this).data('gadgetid') == gadgetId){
                        $(this).children().each(function(){
                            var gadgetKey = $(this).data('key');
                            var gadgetValue = $(this).val();
                            self.currentGadget[gadgetKey] = gadgetValue;
                        });
                        self.currentGadget['elementUuid'] = this.id;
                    }
                });

                if(self.currentGadget.type == 'service'){
                    self.hostFromService(self.currentGadget['object_id'], 'gadget');
                }else{
                    $('#GadgetWizardChoseType').val(self.currentGadget.type);
                    $('#GadgetWizardChoseType').trigger("chosen:updated");
                    $('#GadgetWizardChoseType').trigger("change");
                }

            }
        }else if($(currentElement).hasClass('lineSVGContainer')){
            //line
            //check if there is a line id
            if($(currentElement).data('lineId') != undefined){
                //add delete button if there isnt already one
                if($('#deleteLineBtn').length > 0){
                    $('#deleteLineBtn').remove();
                }
                $('.lineWizardFooter').prepend($('<button>', {
                    id: 'deleteLineBtn',
                    click: function(){
                        self.deleteLine(currentElement);
                    },
                    type: 'button',
                    class: 'btn btn-danger',
                    text: 'Delete'
                }));

                self.currentLine = [];

                //show modal edit dialog
                $('#LineWizardModal').modal('show');
                var lineId = $(currentElement).data('lineId');
                lineId = parseInt(lineId);
                //get values from input fields
                $('.itemElement[data-lineId]').each(function(){
                    if($(this).data('lineid') == lineId){
                        $(this).children().each(function(){
                            var lineKey = $(this).data('key');
                            var lineValue = $(this).val();
                            self.currentLine[lineKey] = lineValue;
                        });
                        self.currentLine['elementUuid'] = this.id;
                    }
                });

                if(self.currentLine.type == 'service'){
                    self.hostFromService(self.currentLine['object_id'], 'line');
                }else{
                    $('#LineWizardChoseType').val(self.currentLine.type);
                    $('#LineWizardChoseType').trigger("chosen:updated");
                    $('#LineWizardChoseType').trigger("change");
                }
            }
        }else if($(currentElement).hasClass('statelessIconContainer')){
            //stateless Icon
            //add delete button if there isnt already one
            if($('#deleteStatelessIconBtn').length > 0){
                $('#deleteStatelessIconBtn').remove();
            }
            $('.statelessIconWizardFooter').prepend($('<button>', {
                id: 'deleteStatelessIconBtn',
                click: function(){
                    self.deleteIcon(currentElement);
                },
                type: 'button',
                class: 'btn btn-danger',
                text: 'Delete'
            }));

            self.currentIcon = [];

            $(currentElement).children().filter(':input').each(function(){
                var iconKey = $(this).data('key');
                var iconValue = $(this).val();
                self.currentIcon[iconKey] = iconValue;
            });
            self.currentIcon['elementUuid'] = currentElement.id;

            var zIndex = parseInt(self.currentIcon['z_index'],10);
            if(zIndex < 0){
                self.currentIcon['z_index'] = 0;
            }
            $('#editStatelessIconX').val(self.currentIcon['x']);
            $('#editStatelessIconY').val(self.currentIcon['y']);
            $('#editStatelessIconZIndex').val(self.currentIcon['z_index']);
            $('#StatelessIconWizardModal').modal('show');
        }else{
            //item
            //add delete button if there isnt already one
            if($('#deleteElementBtn').length > 0){
                $('#deleteElementBtn').remove();
            }
            $('.elementWizardFooter').prepend($('<button>', {
                id: 'deleteElementBtn',
                click: function(){
                    self.deleteElement(currentElement);
                },
                type: 'button',
                class: 'btn btn-danger',
                text: 'Delete'
            }));

            self.current = [];

            //set new text for the element save button
            $('#saveElementPropertiesBtn').text($('#elementEditSaveText').val());

            //show modal edit dialog
            $('#ElementWizardModal').modal('show');
            $(currentElement).children().filter(':input').each(function(){
                var elementKey = $(this).data('key');
                var elementValue = $(this).val();
                self.current[elementKey] = elementValue;
            });
            self.current['elementUuid'] = currentElement.id;

            if(self.current.type == 'service'){
                self.hostFromService(self.current['object_id'], 'element');
            }else{
                $('#ElementWizardChoseType').val(self.current.type);
                $('#ElementWizardChoseType').trigger("chosen:updated");
                $('#ElementWizardChoseType').trigger("change");
            }

        }
    },


    /**
     * Displays the Grid on the Map
     *
     * @param  {object} options The Grid options
     * @return {void}
     */
    showGrid: function(options){
        this.Grid.refreshGrid(this.gridContainer, options);
    },


    /**
     * save a Stateless icon to the map
     *
     * @return {void}
     */
    saveStatelessIcon: function(){
        self = this;
        $mapContainer = $(self.mapEditorContainer);
        //create new element
        //Set icon to map
        self.$mapContainer.append('<div class="itemElement statelessIconContainer dragElement" id="' + self.currentIcon['elementUuid'] + '" style="position:absolute; top: ' + self.currentIcon['y'] + 'px; left: ' + self.currentIcon['x'] + 'px;"><img src="/map_module/img/icons/' + self.currentIcon['icon'] + '"></div>');
        //Save object configuration
        var $currentIcon = $('#' + self.currentIcon['elementUuid']);
        for(var key in self.currentIcon){
            $currentIcon.append('<input type="hidden" name="data[Mapicon][' + self.currentIcon['elementUuid'] + '][' + key + ']" data-key="' + key + '" value="' + self.currentIcon[key] + '" />');
        }

        //add eventlistener on newly created items
        var el = document.getElementById(self.currentIcon['elementUuid']);
        el.addEventListener('dblclick', function(){
            self.editElements(this);
        });

        //draggable function
        self.makeDraggable();
    },


    /**
     * Writes the Current element Properties into its typedependent global Object
     *
     * @param  {object} opt the Properties for the Element
     * @return {void}
     */
    dropOptions: function(opt){
        opt = opt || {};
        var type = opt.type || null; //icon, item, gadget
        var x = opt.x || null;
        var y = opt.y || null;
        var data = opt.data || null;
        var icon = opt.icon || null;
        var iconset = opt.iconset || null;

        switch(type){
            case 'item':
                //is item
                var drop = [];
                var offset = $('#MapContainer').offset();
                if(this.magneticGridEnabled){
                    var positions = {
                        x: x,
                        y: y
                    }
                    var newPositions = this.magneticPosition(positions);
                    drop['x'] = newPositions.x;
                    drop['y'] = newPositions.y;
                }else{
                    drop['x'] = parseInt(x);
                    drop['y'] = parseInt(y);
                }

                drop['iconset'] = iconset;
                drop['elementUuid'] = this.Uuid.v4();
                drop['z_index'] = this.defaultZIndex;

                $('#ElementWizardModal').modal('show');
                $('.chosen-container').css('width', '100%');
                this.current = [];
                this.current = drop;
                break;
            case 'icon':
                var drop = [];
                if(this.magneticGridEnabled){
                    var positions = {
                        x: x,
                        y: y
                    }
                    var newPositions = this.magneticPosition(positions);
                    drop['x'] = newPositions.x;
                    drop['y'] = newPositions.y;
                }else{
                    drop['x'] = parseInt(x);
                    drop['y'] = parseInt(y);
                }

                drop['elementUuid'] = this.Uuid.v4();
                drop['icon'] = icon;
                drop['z_index'] = this.defaultZIndex;

                this.currentIcon = [];
                this.currentIcon = drop;
                break;
            case 'gadget':
                //is gadget
                var drop = [];
                if(this.magneticGridEnabled){
                    var positions = {
                        x: x,
                        y: y
                    }
                    var newPositions = this.magneticPosition(positions);
                    drop['x'] = newPositions.x;
                    drop['y'] = newPositions.y;
                }else{
                    drop['x'] = parseInt(x);
                    drop['y'] = parseInt(y);
                }

                drop['elementUuid'] = this.Uuid.v4();
                drop['z_index'] = this.defaultZIndex;

                $('#GadgetWizardModal').modal('show');
                $('.chosen-container').css('width', '100%');


                this.currentGadget = [];
                this.currentGadget = drop;
                //this.currentGadget['transparent_background'] = 0;
                this.currentGadget['gadget'] = data;

                $('#GadgetWizardChoseType').trigger('change');
                break;
        }
    },


    /**
     * Magnetic position of an element
     *
     * @param  {object} opt An Object with x and y coordinate
     * @return {object}     rounded coordinates
     */
    magneticPosition: function(opt){
        var opt = opt || {};
        var x = opt.x || null;
        var y = opt.y || null;

        var newPosition = {};
        if(x != undefined && y != undefined){
            newPosition = {
                x: this.roundCoordinates(x),
                y: this.roundCoordinates(y)
            }
        }
        return newPosition;
    },


    /**
     * Round the Corrdinates up or down which is dependent of the Grid Size and the Position
     * where the element has been dropped (this is used for the magnetic grid)
     *
     * @param  {string | number} number A single coordinate of the element
     * @return {number}        the Rounded number
     */
    roundCoordinates: function(number){
        //round the coordiantes up or down

        number = parseInt(number);
        var gridSize = parseInt(this.gridSizeX);
        var rest = number % gridSize;
        var halfGrid = gridSize / 2
        if(rest >= halfGrid){
            //round up
            var value = number % gridSize;
            return number + gridSize - value;
        }else{
            //round down
            var value = number % gridSize;
            return number - value;
        }
    },


    /**
     * Autohide the menu if the 'Autohide Menu' Button is enabled
     *
     * @param  {String} param The Action of the Menu. Possible Values: open, close, reset
     * @return {void}
     */
    menuAutohide: function(param){
        var self = this;
        var animationDuration = 200;//ms
        if(this.autohideMenuEnabled){
            if(param == 'open'){
                //open the menu
                if(this.menuHidden == true){
                    var anim2Size = menuPanelSize - 50;
                    $('#mapMenuPanelBody').show();
                    $('#mapMenuPanel').animate({
                        height: self.originalMenuSize + 'px'
                    }, {
                        duration: animationDuration,
                        start: function(){
                            //show menu btn
                            $('#mapMenuMinimizeBtn').hide(animationDuration);
                        },
                        complete: function(){
                            self.menuHidden = false;
                        }
                    })
                }
            }
            if(param == 'close'){
                //close the menu
                if(this.menuHidden == false){
                    //menu is not hidden
                    //container which shall be made smaller
                    var menuPanelSize = $('#mapMenuPanel').height();
                    if(self.originalMenuSize == null){
                        this.originalMenuSize = menuPanelSize;
                    }

                    $('#mapMenuPanel').css({'height': menuPanelSize + 'px'});
                    var anim2Size = menuPanelSize - 50;


                    $('#mapMenuPanel').animate({
                        height: '-=' + anim2Size + 'px'
                    }, {
                        duration: animationDuration,
                        start: function(){
                            //show menu btn
                            $('#mapMenuMinimizeBtn').show(animationDuration);
                        },
                        complete: function(){
                            self.menuHidden = true;
                            $('#mapMenuPanelBody').hide();
                        }
                    })
                }
            }
        }else{
            if(param == 'reset'){
                //reset / open the menu -> autohide deactivated
                var anim2Size = menuPanelSize - 50;
                $('#mapMenuPanelBody').show();
                $('#mapMenuPanel').animate({
                    height: self.originalMenuSize + 'px'
                }, {
                    duration: animationDuration,
                    start: function(){
                        //show menu btn
                        $('#mapMenuMinimizeBtn').hide(animationDuration);
                    },
                    complete: function(){
                        self.menuHidden = false;
                    }
                })
            }
        }
    },


    /**
     * Refresh the Text Size when the Grid is resized and the button
     * 'Scale Text with Grid' is enabled
     *
     * @param  {object} opt The Options Object (currently there is just the font size in it)
     * @return {void}
     */
    refreshTextSize: function(opt){
        var self = this;
        opt = opt || {};
        var newFontSize = opt.fontSize || null;

        if(this.scaleTextWithGrid){
            $('.textElement').each(function(){
                var $parent = $(this).parent();
                var PosX = $parent.css('left');
                var PosY = $parent.css('top');

                $parent.css({'left': self.roundCoordinates(PosX), 'top': self.roundCoordinates(PosY)});

                $(this).basify({fontSize: newFontSize});
            });
        }
    },


    /**
     * save a Text Layer Element
     *
     * @param  {object} textObj The Data Object of the Element Which shall be saved
     * @return {void}
     */
    saveText: function(textObj){
        var self = this;
        //check if the Text div already has its hidden fields
        if($('#' + textObj.elementUuid + ' *').filter(':input').length <= 0){
            //Fields not exist -> Add mode
            //text field
            $('<div>', {
                id: 'spanText_' + textObj.elementUuid
            })
                .html(self.convertBb2Html(textObj.text))
                .css({'font-size': '11px'})
                .addClass('textElement')
                .appendTo($('#' + textObj.elementUuid));

            $('#' + textObj.elementUuid).css({'z-index':textObj['z_index']});

            //add eventlistener on newly created items
            var el = document.getElementById('spanText_' + self.currentText['elementUuid']);
            el.addEventListener('dblclick', function(){
                $('#tempTextUUID').val($(this).parent().attr('id'));
                self.editText(el);
            });

            //text value field
            $('<input>', {
                type: 'hidden',
                value: textObj.text,
                name: 'data[Maptext][' + textObj.elementUuid + '][text]',
            }).data({'key': 'text'})
                .appendTo($('#' + textObj.elementUuid));

            //x field
            $('<input>', {
                type: 'hidden',
                value: textObj.x,
                name: 'data[Maptext][' + textObj.elementUuid + '][x]',
            }).data({'key': 'x'})
                .appendTo($('#' + textObj.elementUuid));

            //y field
            $('<input>', {
                type: 'hidden',
                value: textObj.y,
                name: 'data[Maptext][' + textObj.elementUuid + '][y]',
            })
                .data({'key': 'y'})
                .appendTo($('#' + textObj.elementUuid));

            $('<input>', {
                type: 'hidden',
                value: textObj['z_index'],
                name: 'data[Maptext][' + textObj.elementUuid + '][z_index]',
            }).data({'key': 'z_index'})
                .appendTo($('#' + textObj.elementUuid));


            self.makeDraggable();
        }else{
            //Fields exist -> Edit mode
            //change text and font size
            $('#spanText_' + textObj.elementUuid).html(self.convertBb2Html(textObj.text));//.css({'font-size':textObj.font_size+'px'});
            //rearrange the text
            //$('#spanText_'+textObj.elementUuid).basify({fontSize:textObj.font_size});
            $('#' + textObj.elementUuid).css({'z-index':textObj['z_index']});
            //update form fields
            $('#' + textObj.elementUuid).children().filter(':input').each(function(){
                $(this).val(textObj[$(this).data('key')]);
            });

        }

        //clear form fields
        $('#editTextText').val('');
        $('#editTextFontSize').val('');
        $('#editTextZIndex').val('0');
        //hide delete button
        $('#deleteTextPropertiesBtn').hide();
        //close modal dialog
        $('#textWizardModal').modal('hide');
    },


    /**
     * edit a Text Layer element
     *
     * @param  {String} el The HTML String of the Text Layer
     * @return {void}
     */
    editText: function(el){
        var self = this;
        self.currentText = {};

        var $parent = $(el).parent()
        self.currentText['elementUuid'] = $parent.attr('id');

        $parent.children().filter(':input').each(function(){

            self.currentText[$(this).data('key')] = $(this).val()
        });
        //fill form fields
        self.currentText.text = self.currentText.text.replace(/<br\s*[\/]?>/gi, "\n");
        $('#docuText').val(self.currentText.text);

        $parent.css({'z-index':self.currentText['z_index']});
        //$('#editTextFontSize').val(self.currentText.font_size);
        $('#editTextZIndex').val(self.currentText['z_index']);

        $('#deleteTextPropertiesBtn').show();
        $('#insert-link-area').hide();
        $('#insert-text-area').show();
        $('#insert-modal-footer').show();
        //show dialog
        $('#textWizardModal').modal('show');

    },


    /**
     * deletes a Text Layer element
     *
     * @param  {String} uuid The uuid of the Text Layer container
     * @return {void}
     */
    deleteText: function(uuid){
        //delete Text container and all its fields
        $('#' + uuid).remove();
        $('#textWizardModal').modal('hide');
    },

    capitaliseFirstLetter: function(string){
        return string.charAt(0).toUpperCase() + string.slice(1);
    },


    refreshBackgroundThumbnails: function(){
        var self = this;
        //get new background list by ajax
        $.ajax({
            url: "/map_module/mapeditors/getBackgroundImages",
            type: "POST",
            dataType: "html",
            success: function(response){
                $('#background-panel').empty().html(response);
            }.bind(self)
        });
    },

    refreshItemsThumbnails: function(){
        var self = this;
        $.ajax({
            url: "/map_module/mapeditors/getIconImages",
            type: "POST",
            dataType: "html",
            success: function(response){
                $('#item-panel').empty().html(response);
                self.activateItemsDraggable();
            }.bind(self)
        });
    },

    refreshItemsDropdown: function(){
        var self = this;
        $.ajax({
            url: "/map_module/mapeditors/getIconsetsList",
            type: "POST",
            dataType: "html",
            error: function(){
            },
            success: function(response){
                $('#addHostIconset').empty().html(response);
                $('#addServiceIconset').empty().html(response);
                $('#addServicegroupIconset').empty().html(response);
                $('#addHostgroupIconset').empty().html(response);
                $('#addMapIconset').empty().html(response);
            }.bind(self)
        });
    },

    deleteBackground: function(filenameId){
        var self = this;
        //ajax call to delete background
        $.ajax({
            url: "/map_module/BackgroundUploads/delete/" + filenameId,
            type: "POST",
            dataType: "html",
            error: function(){
            },
            success: function(response){
                self.refreshBackgroundThumbnails();
            }.bind(self)
        });
        //also remove the background from the editor
        self.changeBackground({remove: true});
    },

    deleteIconsSet: function(iconSetId){
        var self = this;
        //ajax call to delete background
        $.ajax({
            url: "/map_module/BackgroundUploads/deleteIconsSet/" + iconSetId,
            type: "POST",
            dataType: "html",
            error: function(){
            },
            success: function(response){
                self.refreshItemsThumbnails();
                self.refreshItemsDropdown();
            }.bind(self)
        });
    }
});