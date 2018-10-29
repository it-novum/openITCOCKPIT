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

App.Controllers.ExportsIndexController = Frontend.AppController.extend({
    $exportLog: null,
    worker: null,

    components: ['Ajaxloader'],

    _initialize: function(){
        this.Ajaxloader.setup();

        var _self = this;


        $('#selectAllSat').click(function(){
            $('.sync_instance').each(function(key, obj){
                $(obj).prop('checked', true);
            });
        });

        $('#deselectAllSat').click(function(){
            $('.sync_instance').each(function(key, obj){
                $(obj).prop('checked', false);
            });
        });


        $('#saveInstacesForSync').click(function(){
            _self.saveInstacesForSync();
        });

        this.worker = function(){
            var self = this;
            $.ajax({
                url: '/exports/broadcast.json',
                cache: false,
                type: "GET",
                success: function(response){
                    //console.log(response);
                    var $exportLog = $('#exportLog');
                    for(var key in response.exportRecords){
                        var $exportLogEntry = $exportLog.children('#' + key);
                        //console.log($exportLogEntry.length);
                        if($exportLogEntry.length == 0){
                            //Record does not exists, we need to create it
                            if(response.exportRecords[key].finished == 0){
                                var html = '<div id="' + key + '" data-finished="0"><i class="fa fa-spin fa-refresh"></i> <span>' + response.exportRecords[key].text + '</span></div>';
                            }else{
                                if(response.exportRecords[key].successfully == 1){
                                    var html = '<div id="' + key + '" data-finished="1"><i class="fa fa-check text-success"></i> <span>' + response.exportRecords[key].text + '</span></div>';
                                }else{
                                    var html = '<div id="' + key + '" data-finished="1"><i class="fa fa-times text-danger"></i> <span>' + response.exportRecords[key].text + '</span></div>';
                                }
                            }
                            $exportLog.append(html);
                        }else{
                            //Record exists, lets update it
                            if(response.exportRecords[key].finished == 0){
                                //If we overwrite existing records, the spin animation will flapp
                                if($exportLogEntry.data('finished') != 0){
                                    var html = '<i class="fa fa-spin fa-refresh"></i> <span>' + response.exportRecords[key].text + '</span>';
                                    $exportLogEntry.html(html);
                                }
                            }else{
                                if(response.exportRecords[key].successfully == 1){
                                    var html = '<i class="fa fa-check text-success"></i> <span>' + response.exportRecords[key].text + '</span>';
                                }else{
                                    var html = '<i class="fa fa-times text-danger"></i> <span>' + response.exportRecords[key].text + '</span>';
                                }
                                $exportLogEntry.html(html);
                            }
                        }
                    }

                    if(response.exportFinished.finished == true){
                        if(response.exportFinished.successfully == true){
                            $('#exportSuccessfully').show();
                        }

                        if(response.exportFinished.successfully == false){
                            $('#exportError').show();

                            //Query monitoring validation error if any
                            for(var key in response.exportRecords){
                                if(response.exportRecords[key].task == 'export_verify_new_configuration'){
                                    if(response.exportRecords[key].finished == 1 && response.exportRecords[key].successfully == 0){
                                        self.verify();
                                    }
                                }
                            }
                        }
                    }
                },
                complete: function(response){
                    // Schedule the next request when the current one's complete
                    if(response.responseJSON.exportFinished.finished == false){
                        setTimeout(self.worker, 1000);
                    }
                }
            });
        }.bind(this);

        //Export running?
        if(this.getVar('exportRunning') == true){
            $('#exportInfo').show();
            this.worker();
        }

        $('#launchExport').click(function(){
            var self = this;
            $('#exportInfo').show();
            $('#launchExport').parents('.formactions').remove();

            var createBackup = 1;
            if($('#CreateBackup').prop('checked') == false || $('#CreateBackup').prop('checked') == null){
                createBackup = 0;
            }

            var instacesToExport = [];
            $('.sync_instance').each(function(key, obj){
                if($(obj).prop('checked')){
                    instacesToExport.push($(obj).attr('instance'));
                }
            });

            $.ajax({
                url: '/exports/launchExport/' + createBackup + '.json',
                cache: false,
                type: "GET",
                data: {
                    instances: instacesToExport
                },
                success: function(response){
                    if(response.export.exportRunning == true){
                        $('#exportRunning').show();
                        $('#exportInfo').show();
                        $('#launchExport').parents('.formactions').remove();
                    }
                    self.worker();
                },
                complete: function(){

                }
            });
        }.bind(this));
    },

    verify: function(){
        var $verifyOutput = $('#verifyOutput');
        var RegExObject = new RegExp('(' + this.getVar('uuidRegEx') + ')', 'g');
        $('#verifyError').show();
        $.ajax({
            url: '/exports/verifyConfig.json',
            cache: false,
            type: "GET",
            success: function(response){
                for(var key in response.result.output){
                    var line = response.result.output[key];

                    //Replace UUID with links to forwarder
                    line = line.replace(RegExObject, '<a href="/forward/index/uuid:$1/action:edit">$1</a>');

                    var _class = 'txt-color-blueDark';
                    if(line.match('Warning')){
                        _class = 'txt-color-orangeDark';
                    }

                    if(line.match('Error')){
                        _class = 'txt-color-red';
                    }
                    $verifyOutput.append('<div class="' + _class + '">' + line + '</div>');
                }
            },
            complete: function(){
            }
        });
    },

    saveInstacesForSync: function(){
        this.Ajaxloader.show();
        var instacesToExport = [];
        $('.sync_instance').each(function(key, obj){
            if($(obj).prop('checked')){
                instacesToExport.push($(obj).attr('instance'));
            }
        });

        var self = this;
        $.ajax({
            url: '/exports/saveInstanceConfigSyncSelection.json',
            cache: false,
            type: "GET",
            data: {
                instances: instacesToExport
            },
            success: function(response){
                self.Ajaxloader.hide();
            },
            complete: function(){

            }
        });
    }
});
