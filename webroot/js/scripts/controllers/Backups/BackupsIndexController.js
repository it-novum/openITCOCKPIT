/*
 * Copyright (C) <2015-present>  <it-novum GmbH>
 *
 * This file is dual licensed
 *
 * 1.
 *     This program is free software: you can redistribute it and/or modify
 *     it under the terms of the GNU General Public License as published by
 *     the Free Software Foundation, version 3 of the License.
 *
 *     This program is distributed in the hope that it will be useful,
 *     but WITHOUT ANY WARRANTY; without even the implied warranty of
 *     MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *     GNU General Public License for more details.
 *
 *     You should have received a copy of the GNU General Public License
 *     along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * 2.
 *     If you purchased an openITCOCKPIT Enterprise Edition you can use this file
 *     under the terms of the openITCOCKPIT Enterprise Edition license agreement.
 *     License agreement and license key will be shipped with the order
 *     confirmation.
 */

angular.module('openITCOCKPIT')
    .controller('BackupsIndexController', function($scope, $http, $interval, NotyService, $state) {

        $scope.reset = function() {
            $scope.backupFiles = {};
            $scope.selectedBackup = null;
            $scope.filenameForBackup = 'mysql_oitc_bkp';
            $scope.checkActionRunningInterval = null;
            $scope.isActionRunning = false;
            $scope.backupRunning = false;
            $scope.restoreRunning = false;
        };

        $scope.load = function() {
            $http.get("/backups/index.json").then(function(result) {
                if(result.data.backup_files) {
                    $scope.backupFiles = result.data.backup_files;
                }
            });
        };

        $scope.restore = function() {
            if($scope.selectedBackup && $scope.selectedBackup !== '') {
                $http.post("/backups/restore.json", {
                    backupfile: $scope.selectedBackup
                }).then(function(result) {
                    if(result.data.error === true) {
                        NotyService.genericError({message: 'Restore was not successful.'});
                        return;
                    }

                    NotyService.info();
                    $scope.isActionRunning = true;
                    $scope.restoreRunning = true;
                    $scope.startCheckInterval(true, 'restore');
                }, function errorCallback(result) {
                    if(result.status === 403) {
                        $state.go('403');
                    }

                    if(result.status === 404) {
                        $state.go('404');
                    }
                });
            } else {
                NotyService.genericError({message: 'Please select a backupfile.'});
            }
        };

        $scope.delete = function() {
            if($scope.selectedBackup && $scope.selectedBackup !== '') {
                $http.post("/backups/deleteBackupFile.json", {
                    filename: $scope.selectedBackup
                }).then(function(result) {
                    if(result.data.deleteFinished.result.success !== true) {
                        NotyService.genericError({message: 'Deletion was not successful.'});
                        return;
                    }
                    NotyService.genericSuccess({message: 'Backup deleted successfully.'});
                    if(result.data.deleteFinished.backup_files) {
                        $scope.backupFiles = result.data.deleteFinished.backup_files;
                    }
                }, function errorCallback(result) {
                    if(result.status === 403) {
                        $state.go('403');
                    }

                    if(result.status === 404) {
                        $state.go('404');
                    }
                });
            } else {
                NotyService.genericError({message: 'Please select a backupfile.'});
            }
        };

        $scope.backup = function() {
            if(true) {
                $http.post("/backups/backup.json", {
                    filename: $scope.filenameForBackup
                }).then(function(result) {
                    if(result.data.error === true) {
                        NotyService.genericError({message: 'Backup was not successful. Your filename could be invalid.'});
                        return;
                    }

                    NotyService.info();
                    $scope.isActionRunning = true;
                    $scope.backupRunning = true;
                    $scope.startCheckInterval(false, 'backup');
                }, function errorCallback(result) {
                    if(result.status === 403) {
                        $state.go('403');
                    }

                    if(result.status === 404) {
                        $state.go('404');
                    }
                });
            } else {
                NotyService.genericError({message: 'Backup was not successful. Your filename could be invalid.'});
            }
        };

        $scope.startCheckInterval = function(withPageReload = false, caller = 'backup') {
            if($scope.checkActionRunningInterval === null) {
                $scope.checkActionRunningInterval = $interval(function() {
                    if($scope.isActionRunning === false) {
                        $interval.cancel($scope.checkActionRunningInterval);
                        $scope.checkActionRunningInterval = null;
                        return;
                    }

                    $http.get("/backups/checkBackupFinished.json").then(function(result) {
                        if(result.data.backupFinished) {
                            if(result.data.backupFinished.finished === true || result.data.backupFinished.finished === 1) {
                                $scope.isActionRunning = false;
                                if(result.data.backupFinished.error) {
                                    NotyService.genericError();
                                } else {
                                    let successMessage = null;
                                    switch(caller) {
                                        case 'backup':
                                            successMessage = 'Backup created successfully';
                                            $scope.backupRunning = false;
                                            break;
                                        case 'restore':
                                            successMessage = 'Database restored successfully';
                                            $scope.restoreRunning = false;
                                            break;
                                    }
                                    NotyService.genericSuccess({message: successMessage});

                                    if(result.data.backupFinished.backup_files) {
                                        $scope.backupFiles = result.data.backupFinished.backup_files;
                                    }
                                    if(withPageReload) {
                                        $scope.showPageReloadRequired(); // defined in ReloadRequiredDirective
                                    }
                                }
                            }
                        }
                    });
                }, 1000);
            }
        };

        $scope.download = function() {
            if($scope.selectedBackup && $scope.selectedBackup !== '') {
                for(var bf in $scope.backupFiles) {
                    if(bf === $scope.selectedBackup) {
                        var win = window.open('/backups/downloadBackupFile?filename=' + $scope.backupFiles[bf], '_blank');
                        win.focus();
                        return;
                    }
                }
            } else {
                NotyService.genericError({message: 'Please select a backupfile.'});
            }
        };

        $scope.reset();
        $scope.load();
    });
