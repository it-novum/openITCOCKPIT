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

App.Controllers.BackupsIndexController = Frontend.AppController.extend({
    $exportLog: null,
    worker: null,
    components: ['Ajaxloader'],

    _initialize: function(){
        this.Ajaxloader.setup();
        var content = "";
        var finish = true;
        var self = this;
        var error = false;
        self.worker = function(){
            $.ajax({
                type: "GET",
                cache: false,
                url: "/backups/checkBackupFinished.json",
                success: function(response){
                    if(jQuery.type(response) === "string"){
                        $('#warningMessage').html("You have been restored an old Backup. You have to run openitcockpit-update and set the user rights correctly.");
                        $('#backupWarning').show();
                    }else{
                        var $backupLog = $('#backupLog');
                        if(response.backupFinished.finished == false && error == false){
                            setTimeout(self.worker, 1000);
                            var html = '<div data-finished="0"><i class="fa fa-spin fa-refresh"></i> <span>' + content + ' is running</span>';
                            $backupLog.html(html);
                            finish = false;
                        }else if(response.backupFinished.error == true){
                            var html = '<div data-finished="0"><i class="fa fa-close text-danger"></i> <span>' + content + ' has caused an error</span>';
                            $backupLog.html(html);
                            $('#errorMessage').html(content + " was not successfully finished");
                            $('#backupError').show();
                            finish = true;
                        }else if(error == true){
                            var html = '<div data-finished="0"><i class="fa fa-close text-danger"></i> <span>' + content + ' has caused an error</span>';
                            $backupLog.html(html);
                            finish = true;
                        }else{
                            var html = '<div data-finished="1"><i class="fa fa-check text-success"></i> <span>' + content + ' is finished</span>';
                            $backupLog.html(html);
                            $('#successMessage').html(content + " successfully done.");
                            $('#backupSuccessfully').show();
                            var backupfiles = $('#backupfile');
                            backupfiles.find('option').remove();
                            $.each(response.backupFinished.backup_files, function(val, text){
                                backupfiles.append("<option value=" + val + ">" + text + "</option>");
                            });
                            finish = true;
                            backupfiles.trigger("chosen:updated");
                        }
                    }
                },
                complete: function(response){
                    var $backupLog = $('#backupLog');
                    if(jQuery.type(response) === "string"){
                        var html = '<div data-finished="1"><i class="fa fa-check text-success"></i> <span>' + content + ' is finished</span>';
                        $backupLog.html(html);
                        $('#warningMessage').html("You have been restored an old Backup. You have to run openitcockpit-update and set the user rights correctly.");
                        $('#backupWarning').show();
                    }else{
                        if(typeof  response.backupFinished === 'undefined'){
                            console.log("LEER");
                        }else{
                            if(response.backupFinished.finished == false && error == false){
                                setTimeout(self.worker, 1000);
                                var html = '<div data-finished="0"><i class="fa fa-spin fa-refresh"></i> <span>' + content + ' is running</span>';
                                $backupLog.html(html);
                                finish = false;
                            }else if(response.backupFinished.error == true){
                                var html = '<div data-finished="0"><i class="fa fa-close text-danger"></i> <span>' + content + ' has caused an error</span>';
                                $backupLog.html(html);
                                $('#errorMessage').html(content + " was not successfully finished");
                                $('#backupError').show();
                                finish = true;
                            }else if(error == true){
                                var html = '<div data-finished="0"><i class="fa fa-close text-danger"></i> <span>' + content + ' has caused an error</span>';
                                $backupLog.html(html);
                                finish = true;
                            }else{
                                var html = '<div data-finished="1"><i class="fa fa-check text-success"></i> <span>' + content + ' is finished</span>';
                                $backupLog.html(html);
                                $('#successMessage').html(content + " successfully done.");
                                $('#backupSuccessfully').show();
                                var backupfiles = $('#backupfile');
                                backupfiles.find('option').remove();
                                $.each(response.backupFinished.backup_files, function(val, text){
                                    backupfiles.append("<option value=" + val + ">" + text + "</option>");
                                });
                                finish = true;
                                backupfiles.trigger("chosen:updated");
                            }
                        }

                    }
                }
            });
        }.bind(this);

        //Backup beendet?
        if(finish == false && error == false){
            self.worker();
        }

        $('#backup').click(function(){
            $('#backupSuccessfully').hide();
            $('#backupError').hide();
            $('#backupWarning').hide();
            $('#backupLog').empty();
            var fileForBackup = $('#filenameForBackup').val();
            content = "Backup";
            finish = false;
            $.ajax({
                url: '/backups/backup.json',
                cache: false,
                type: "GET",
                data: {
                    filename: fileForBackup
                },
                success: function(response){
                    if(response.backup.error == true){
                        error = true;
                        finish = true;
                        $('#errorMessage').html("Backup was not successful. Your filename is invalid.");
                        $('#backupError').show();
                    }else{
                        self.worker();
                    }
                },
                complete: function(){

                }
            });
        }.bind(this));

        $('#restore').click(function(){
            $('#backupSuccessfully').hide();
            $('#backupError').hide();
            $('#backupWarning').hide();
            $('#backupLog').empty();
            var fileForBackup = $('#backupfile').val();
            var filenameParts = fileForBackup.split("/");
            var r = confirm("Are you sure to restore this backupfile " + filenameParts[5] + "?");
            if(r == true){
                finish = false;
                content = "Restore";
                $.ajax({
                    url: '/backups/restore.json',
                    cache: false,
                    type: "GET",
                    data: {
                        backupfile: fileForBackup
                    },
                    success: function(response){
                        self.worker();
                    },
                    complete: function(){

                    }
                });
            }
        }.bind(this));

        $('#delete').click(function(){
            $('#backupSuccessfully').hide();
            $('#backupError').hide();
            $('#backupWarning').hide();
            $('#backupLog').empty();
            var fileToDelete = $('#backupfile').val();
            var filenameParts = fileToDelete.split("/");
            var r = confirm("Are you sure to delete this backupfile " + filenameParts[5] + "?");
            if(r == true){
                $.ajax({
                    url: '/backups/deleteBackupFile.json',
                    cache: false,
                    type: "GET",
                    data: {
                        fileToDelete: fileToDelete
                    },
                    success: function(response){
                        if(response.success.result == true){
                            $('#successMessage').html("Backupfile " + filenameParts[5] + " successfully deleted.");
                            $('#backupSuccessfully').show();
                            var backupfiles = $('#backupfile');
                            backupfiles.find('option').remove();
                            $.each(response.success.backup_files, function(val, text){
                                backupfiles.append("<option value=" + val + ">" + text + "</option>");
                            });
                            backupfiles.trigger("chosen:updated");
                            finish = true;
                        }else{
                            $('#errorMessage').html("Backupfile " + filenameParts[5] + " could not deleted.");
                            $('#backupError').show();
                            finish = true
                        }

                    },
                    complete: function(){

                    }
                });
            }
        }.bind(this));
    }
});
