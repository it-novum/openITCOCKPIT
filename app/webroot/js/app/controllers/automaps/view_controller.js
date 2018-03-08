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

App.Controllers.AutomapsViewController = Frontend.AppController.extend({

    currentHostUuid: null,
    currentServiceUuid: null,

    components: ['Ajaxloader', 'Rrd', 'WebsocketSudo', 'Externalcommand', 'Time'],

    _initialize: function(){
        this.Ajaxloader.setup();
        this.Time.setup();

        this.WebsocketSudo.setup(this.getVar('websocket_url'), this.getVar('akey'));

        this.WebsocketSudo._errorCallback = function(){
            $('#error_msg').html('<div class="alert alert-danger alert-block"><a href="#" data-dismiss="alert" class="close">×</a><h5 class="alert-heading"><i class="fa fa-warning"></i> Error</h5>Could not connect to SudoWebsocket Server</div>');
        };

        this.WebsocketSudo.connect();
        this.WebsocketSudo._success = function(e){
            return true;
        }.bind(this);

        this.WebsocketSudo._callback = function(transmitted){
            return true;
        }.bind(this);

        this.Externalcommand.setup();

        var self = this;


        /*
         * Bind click Event on automap icons
         */
        $('.triggerModal').click(function(){
            self.cleanupModal();
            self.loadServiceDetails($(this).attr('service-id'));
        });

        /*
         * Bind click event for modalReschedule
         */
        $('#modalReschedule').click(function(){
            self.WebsocketSudo.send(self.WebsocketSudo.toJson('rescheduleServiceWithQuery', [self.currentServiceUuid]));
            $('#serviceDetailsModal').modal('hide');
            self.Externalcommand.refresh();
        });

        /*
         * Service ACK
         */
        $('#submitServiceAck').click(function(){
            var sticky = 0;

            if($('#CommitServiceAckSticky').prop('checked') == true){
                sticky = 2;
            }

            self.WebsocketSudo.send(self.WebsocketSudo.toJson('submitServiceAckWithQuery', [self.currentHostUuid, self.currentServiceUuid, $('#CommitServiceAckComment').val(), $('#CommitServiceAckAuthor').val(), sticky]));

            $('#nag_command_ack_state').modal('hide');
            $('#serviceDetailsModal').modal('hide');
            self.Externalcommand.refresh();
        });

        /*
         * Set planned maintenance time
         */
        $('#submitCommitServiceDowntime').click(function(){
            self.validateDowntimeInput();

        });

        $('#serviceDetailsModal').on('hidden.bs.modal', function(){
            $('#graph_data_tooltip').hide();
        });
    },

    loadServiceDetails: function(serviceID){
        $('#serviceDetailsModal').modal('show');

        this.Ajaxloader.show();
        $('#graph_legend').hide();

        var self = this;

        $.ajax({
            url: "/Automaps/loadServiceDetails/" + encodeURIComponent(serviceID) + ".json",
            type: "GET",
            cache: false,
            error: function(){
            },
            success: function(){
            },
            complete: function(response){
                //console.log(response.responseJSON);
                $('#modalHostname').text(response.responseJSON.service.Host.name);
                $('#modelServicename').text(response.responseJSON.serviceName);
                $('#modalServicestate').text(response.responseJSON.servicestatus.Servicestatus.current_state);

                $('#modalStateType').text(response.responseJSON.servicestatus.Servicestatus.state_type);
                $('#modalLastCheck').text(response.responseJSON.servicestatus.Servicestatus.last_check);
                $('#modalStateSince').text(response.responseJSON.servicestatus.Servicestatus.last_state_change);

                $('#modalOutput').text(response.responseJSON.servicestatus.Servicestatus.output);
                $('#modalPerfdata').text(response.responseJSON.servicestatus.Servicestatus.perfdata);

                $('#modalBrowserLink').attr('href', '/services/browser/' + response.responseJSON.service.Service.id);

                self.currentHostUuid = response.responseJSON.service.Host.uuid;
                self.currentServiceUuid = response.responseJSON.service.Service.uuid;


                if(response.responseJSON.servicestatus.Servicestatus.scheduled_downtime_depth){
                    $('#modalDowntime').show();
                }

                if(response.responseJSON.servicestatus.Servicestatus.problem_has_been_acknowledged){
                    $('#modalAck').show();
                    $('#modalAckText').text(response.responseJSON.acknowledged);
                }

                self.Ajaxloader.hide();
                $('#modalLoading').hide();

                if(response.responseJSON.hasRrdGraph === true){
                    $('#graph_loader').show();
                    self.loadGraph(response.responseJSON.service.Host.uuid, response.responseJSON.service.Service.uuid);
                }
            }.bind(self)
        });
    },

    loadGraph: function(host_uuid, service_uuid){
        var self = this,
            host_and_service_uuids = {};

        host_and_service_uuids[host_uuid] = [service_uuid];
        self.Rrd.setup({
            url: '/Graphgenerators/fetchGraphData.json',
            host_and_service_uuids: host_and_service_uuids,
            selector: '#serviceGraph',
            //ds: $('#GraphgeneratorServicerule').val(),
            display_threshold_lines: true,
            timezoneOffset: self.Time.timezoneOffset, //Rename to user timesone offset
            height: '150px'
        });


        //var today = new Date(),
        var current_time = parseInt(self.Time.getCurrentTimeWithOffset(0).getTime() / 1000, 10),
            time_period = { // Current timestamp.
                start: current_time - (60 * 60 * 4),
                end: current_time
            },
            development_time_period = {
                start: current_time - (60 * 60 * 24 * 35),
                end: current_time - (60 * 60 * 24 * 30)
            };

        self.Rrd.fetchRrdData(time_period, function(){
            self.Rrd.renderGraph();
            $('#graph_legend').show();
            $('#graph_loader').hide();
            $('#serviceGraph').show();
        });

    },

    cleanupModal: function(){
        $('#graph_legend').hide();
        $('#serviceGraph').hide();
        $('#graph_loader').hide();

        $('#modalLoading').show();

        $('#modalHostname').empty();
        $('#modelServicename').empty();
        $('#modalServicestate').empty();

        $('#modalStateType').empty();
        $('#modalLastCheck').empty();
        $('#modalStateSince').empty();

        $('#modalOutput').empty();
        $('#modalPerfdata').empty();

        $('#modalDowntime').hide();
        $('#modalAck').hide();

        $('#modalBrowserLink').attr('href', '#');
    },

    validateDowntimeInput: function(){
        var self = this;
        self.Ajaxloader.show();
        var fromData = $('#CommitServiceDowntimeFromDate').val();
        var fromTime = $('#CommitServiceDowntimeFromTime').val();
        var toData = $('#CommitServiceDowntimeToDate').val();
        var toTime = $('#CommitServiceDowntimeToTime').val();

        ret = $.ajax({
            url: "/downtimes/validateDowntimeInputFromBrowser",
            type: "POST",
            cache: false,
            data: {from: fromData + ' ' + fromTime, to: toData + ' ' + toTime},
            error: function(){
            },
            success: function(response){
                if(response == 1){
                    self.WebsocketSudo.send(self.WebsocketSudo.toJson('submitServiceDowntime', [self.currentHostUuid, self.currentServiceUuid, fromData + ' ' + fromTime, toData + ' ' + toTime, $('#CommitServiceDowntimeComment').val(), $('#CommitServiceDowntimeAuthor').val()]));
                    $('#nag_command_schedule_downtime').modal('hide');
                    $('#serviceDetailsModal').modal('hide');
                    self.Externalcommand.refresh();
                }else{
                    $('#validationErrorServiceDowntime').show();
                }
                self.Ajaxloader.hide();
            }.bind(self),
            complete: function(response){
            }
        });
    }

});