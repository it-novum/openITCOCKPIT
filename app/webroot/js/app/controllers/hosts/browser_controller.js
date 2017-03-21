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

App.Controllers.HostsBrowserController = Frontend.AppController.extend({
    hostUuid: null,
    /**
     * @constructor
     * @return {void}
     */
    $jqconsole: null,

    components: ['WebsocketSudo', 'Ajaxloader', 'Utils', 'Externalcommand', 'Rrd', 'Qr'],

    _initialize: function () {
        this.Ajaxloader.setup();
        this.Utils.flapping();
        this.Rrd.bindPopup({
            Time: this.Time,
        });
        this.Qr.setup();
        this.Externalcommand.setup();

        var self = this;

        /*
         * Render Datepickers
         */
        $('#CommitHostDowntimeFromDate').datepicker({
            format: this.getVar('dateformat')
        });

        $('#CommitHostDowntimeToDate').datepicker({
            format: this.getVar('dateformat')
        });

        /*
         * Bind ing click events
         */
        $('#submitRescheduleHost').click(function () {
            this.WebsocketSudo.send(this.WebsocketSudo.toJson('rescheduleHost', [this.hostUuid, $('#nag_commandRescheduleHost').val(), $('#nag_commandSatelliteId').val()]));
            this.Externalcommand.refresh();
        }.bind(this));

        $('#submitCommitPassiveResult').click(function () {
            this.WebsocketSudo.send(this.WebsocketSudo.toJson('commitPassiveResult', [this.hostUuid, $('#CommitPassiveResultComment').val(), $('#CommitPassiveResultStatus').val(), $('#CommitPassiveResultForceHardstate').prop('checked'), $('#CommitPassiveResultRepetitions').val()]));
            this.Externalcommand.refresh();
        }.bind(this));

        $('#submitEnableOrDisableHostFlapdetection').click(function () {
            this.WebsocketSudo.send(this.WebsocketSudo.toJson('enableOrDisableHostFlapdetection', [this.hostUuid, $('#enableOrDisableHostFlapdetectionCondition').val()]));
            this.Externalcommand.refresh();
        }.bind(this));

        $('#submitCustomHostNotification').click(function () {
            var type = 0;

            if ($('#CommitCustomHostNotificationBroadcast').prop('checked') == true) {
                type = 1;
            }

            if ($('#CommitCustomHostNotificationForced').prop('checked') == true) {
                type = 2;
            }

            if ($('#CommitCustomHostNotificationBroadcast').prop('checked') == true && $('#CommitCustomHostNotificationForced').prop('checked') == true) {
                type = 3;
            }


            this.WebsocketSudo.send(this.WebsocketSudo.toJson('sendCustomHostNotification', [this.hostUuid, type, $('#CommitCustomHostNotificationAuthor').val(), $('#CommitCustomHostNotificationComment').val()]));
            this.Externalcommand.refresh();
        }.bind(this));

        $('#submitHoststateAck').click(function () {
            var sticky = 0;

            if ($('#CommitHoststateAckSticky').prop('checked') == true) {
                sticky = 2;
            }
            this.WebsocketSudo.send(this.WebsocketSudo.toJson('submitHoststateAck', [this.hostUuid, $('#CommitHoststateAckComment').val(), $('#CommitHoststateAckAuthor').val(), sticky, $('#CommitHoststateAckType').val()]));
            this.Externalcommand.refresh();
        }.bind(this));

        $('#submitCommitHostDowntime').click(function () {
            this.validateDowntimeInput();

        }.bind(this));

        $('#submitEnableNotifications').click(function () {
            if ($('#enableNotificationsIsEnabled').val().toString() == '1') {
                this.WebsocketSudo.send(this.WebsocketSudo.toJson('submitDisableHostNotifications', [this.hostUuid, $('#enableNotificationsType').val()]));
            } else {
                this.WebsocketSudo.send(this.WebsocketSudo.toJson('submitEnableHostNotifications', [this.hostUuid, $('#enableNotificationsType').val()]));
            }
            this.Externalcommand.refresh();
        }.bind(this));

        this.hostUuid = this.getVar('hostUuid');

        $('#nagShowLongOutput').click(function () {
            $('#nag_longout_preview').hide();
            $('#nag_longoutput_container').show();
            $('#nag_longoutput_loader').show();
            $('#nag_longoutput_content').html('');
            $.ajax({
                url: "/Hosts/longOutputByUuid/" + encodeURIComponent(this.hostUuid),
                type: "GET",
                cache: false,
                error: function () {
                },
                success: function () {
                },
                complete: function (response) {
                    $('#nag_longoutput_content').html(response.responseText);
                    $('#nag_longoutput_loader').hide();
                }
            });
        }.bind(this));

        this.WebsocketSudo.setup(this.getVar('websocket_url'), this.getVar('akey'));

        this.WebsocketSudo._errorCallback = function () {
            $('#error_msg').html('<div class="alert alert-danger alert-block"><a href="#" data-dismiss="alert" class="close">×</a><h5 class="alert-heading"><i class="fa fa-warning"></i> Error</h5>Could not connect to SudoWebsocket Server</div>');
        }

        this.WebsocketSudo.connect();
        this.WebsocketSudo._success = function (e) {
            return true;
        }.bind(this)

        this.WebsocketSudo._callback = function (transmitted) {
            return true;
        }.bind(this);

        $('#host_browser_service_table').dataTable({
            "bPaginate": false,
            "bFilter": true,
            "bInfo": false,
            "bStateSave": true
        });

        $('#host_browser_disabled_service_table').dataTable({
            "bPaginate": false,
            "bFilter": true,
            "bInfo": false,
            "bStateSave": true
        });

        $('div.dataTables_filter')
            .attr('style', 'width: 100% !important;padding-right: 20px;')
            .children('.input-group')
            .attr('style', 'width: 100% !important;');

        $('.nag_command').mouseenter(function () {
            $(this).addClass('text-primary pointer');
        });

        $('.nag_command').mouseleave(function () {
            $(this).removeClass('text-primary pointer');
        });

        /*
         * Blind click event to #runPing
         */
        $('#runPing').click(function () {
            var $this = $(this);
            self.execPing($this.attr('target'));
        });
    },

    validateDowntimeInput: function () {
        this.Ajaxloader.show();
        var fromData = $('#CommitHostDowntimeFromDate').val();
        var fromTime = $('#CommitHostDowntimeFromTime').val();
        var toData = $('#CommitHostDowntimeToDate').val();
        var toTime = $('#CommitHostDowntimeToTime').val();

        ret = $.ajax({
            url: "/downtimes/validateDowntimeInputFromBrowser",
            type: "POST",
            cache: false,
            data: {from: fromData + ' ' + fromTime, to: toData + ' ' + toTime},
            error: function () {
            },
            success: function (response) {
                if (response == 1) {
                    this.WebsocketSudo.send(this.WebsocketSudo.toJson('submitHostDowntime', [this.hostUuid, fromData + ' ' + fromTime, toData + ' ' + toTime, $('#CommitHostDowntimeComment').val(), $('#CommitHostDowntimeAuthor').val(), $('#CommitHostDowntimeType').val()]));
                    $('#nag_command_schedule_downtime').modal('hide');
                    this.Externalcommand.refresh();
                } else {
                    $('#validationErrorHostDowntime').show();
                }
                this.Ajaxloader.hide();
            }.bind(this),
            complete: function (response) {
            }
        });
    },

    execPing: function (address, caption) {
        var $runPing = $('#runPing');
        var caption = $runPing.html();
        $runPing.html('<i class="fa fa-spin fa-refresh"></i>');

        $('#console').fadeIn('fast');
        this.$jqconsole = $('#console').jqconsole('', '');
        this.$jqconsole.Disable();
        this.$jqconsole.Write('ping ' + address + " -c 4 -W 5\n");

        $.ajax({
            url: "/hosts/ping/address:" + encodeURIComponent(address) + '.json',
            type: "GET",
            cache: false,
            error: function () {
                this.$jqconsole.Write("\033[31mError: Could not connect server");
            }.bind(this),
            success: function (response) {
                for (var key in response.output) {
                    //console.log(response.output[key]);
                    this.$jqconsole.Write(response.output[key] + "\n");
                }

                $runPing.html(caption);
            }.bind(this),
            complete: function (response) {
            }
        });

    }

});