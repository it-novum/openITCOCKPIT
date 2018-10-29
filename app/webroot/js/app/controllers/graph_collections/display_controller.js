'use strict';
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

App.Controllers.GraphCollectionsDisplayController = Frontend.AppController.extend({
    components: ['Rrd', 'Ajaxloader', 'Time'],

    _initialize: function(){
        this.Time.setup();
        var self = this;

        self.bindSelectBoxEvent('#GraphCollectionId', '#render-graph');
        if(self.getVar('graphCollectionId') != null){
            $('#GraphCollectionId').trigger('change');
        }
        self.Ajaxloader.setup();


        self.GRAPH_HEIGHT = 350;
    },

    bindSelectBoxEvent: function(select_box_selector, target_selector){
        var self = this;
        var $select_box = $(select_box_selector);
        var $target = $(target_selector);

        if($select_box.length < 1 || $target.length < 1){
            throw new Error('Either the selector for the select box or the selector for the target container is invalid.');
        }

        $select_box.on('change', function(){
            var collection_id = parseInt($(this).val(), 10);
            if(isNaN(collection_id) || collection_id < 1){
                $target.html('');
                return;
            }

            self.loadCollectionGraphData(collection_id, function(data){
                self.Ajaxloader.show();
                if(data == null ||
                    data.responseJSON == null ||
                    data.responseJSON.collection == null ||
                    data.responseJSON.collection.length === 0
                ){
                    return;
                }

                var template_amount = data.responseJSON.collection.GraphgenTmpl.length,
                    templates = data.responseJSON.collection.GraphgenTmpl,
                    target_classes,
                    result,
                    i;

                if(template_amount === 0){
                    return;
                }

                $target.html('');

                $.each(templates, function(i, elem){
                    var host_and_service_uuids = elem.HostAndServiceUuids,
                        service_rules = elem.ServiceRules,
                        time_period = self.createTimePeriod(elem.relative_time),

                        total_sec = parseInt(elem.relative_time, 10),
                        formatted_time = moment.duration(total_sec * 1000).humanize(),
                        result = [],
                        target_class = 'graph-' + (i + 1),
                        $div_graph = $('<div>', {
                            class: 'graph ' + target_class
                        }),
                        $div_legend = $('<div>', {
                            class: 'graph_legend graph_legend_' + (i + 1)
                        }),
                        $div_title = $('<div>', {
                            class: 'title'
                        }).append($('<h4>', {
                            text: elem.name + ' - ' + formatted_time,
                            style: 'margin-bottom: 10px'
                        }));

                    result.push($div_title);
                    result.push($div_legend);
                    result.push($div_graph);

                    $.fn.append.apply($target, result);


                    self.Rrd.setup({
                        url: '/Graphgenerators/fetchGraphData.json',
                        host_and_service_uuids: service_rules,
                        selector: '.' + target_class,
                        height: self.GRAPH_HEIGHT + 'px',
                        timeout_in_ms: self.user_default_timeout,
                        async: false,
                        timezoneOffset: self.Time.timezoneOffset, //Rename to user timesone offset
                        error_callback: function(response, status){
                            throw new Error('An error occured with self.Rrd.setup()');
                        },
                        flot_options: {
                            zoom: {
                                interactive: false // Deactivates zoom.
                            },
                            pan: {
                                interactive: false // Deactivates pan.
                            },
                            legend: {
                                container: $('.graph_legend_' + (i + 1))
                            }
                        }
                    });

                    self.Rrd.drawServiceRules(host_and_service_uuids, time_period, function(){
                        $('.graph_legend').show();
                    });
                });

                self.Ajaxloader.hide();
            });
        });
    },

    createTimePeriod: function(relative_time){
        var now = parseInt(this.Time.getCurrentTimeWithOffset(0).getTime() / 1000, 10),
            substract_seconds;

        if(relative_time > 0){
            substract_seconds = parseInt(relative_time, 10);
        }else{
            substract_seconds = 3600 * 3;
        }

        return {
            'start': now - substract_seconds,
            'end': now
        };
    },

    loadCollectionGraphData: function(collection_id, on_complete){
        $.ajax({
            url: '/graph_collections/loadCollectionGraphData/' + collection_id + '.json',
            type: 'post',
            cache: false,
            dataType: 'json',
            error: function(){
            },
            success: function(){
            },
            complete: on_complete
        });
    }
});
