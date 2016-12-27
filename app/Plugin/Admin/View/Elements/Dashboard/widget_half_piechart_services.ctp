<?php
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

?>
<div class="widget-body pieChartServices">
    <?php
    $state_total = array_sum($state_array_service);
    if ($state_total > 0):
        $overview_chart = $this->PieChart->createHalfPieChart($state_array_service);
        echo $this->Html->image(
            '/img/charts/'.$overview_chart
        );
        $state_colors = [
            'ok',
            'warning',
            'critical',
            'unknown',
        ];
        $arrayDefault = array_fill(0, 4, 0);
        $counterNotHandled = $arrayDefault;
        $counterByHost = $arrayDefault;
        $counterAchknowledged = $arrayDefault;
        $counterPlaned = $arrayDefault;
        $counterPassive = $arrayDefault;
        ?>
        <div class="detailsForPiechart">
            <?php
            foreach ($allServices as $service):
                if ($service['Servicestatus']['current_state'] > 0):
                    if ($service['Servicestatus']['problem_has_been_acknowledged'] === '0'):
                        $counterNotHandled[$service['Servicestatus']['current_state']]++;
                    else:
                        $counterAchknowledged[$service['Servicestatus']['current_state']]++;
                    endif;
                    if ($service['Servicestatus']['scheduled_downtime_depth'] > 0):
                        $counterPlaned[$service['Servicestatus']['current_state']]++;
                    endif;
                    if ($service['Servicestatus']['active_checks_enabled'] === '0'):
                        $counterPassive[$service['Servicestatus']['current_state']]++;
                    endif;
                    if ($service['Hoststatus']['current_state'] > 0):
                        $counterByHost[$service['Servicestatus']['current_state']]++;
                    endif;
                endif;
            endforeach;
            foreach ($state_array_service as $state => $state_count):
                if ($state > 0):?>
                    <div class="stateColService">
                        <div class="stateService_<?php echo $state ?>"><?php echo $state_count." ".$state_colors[$state]; ?> </div>
                        <div class="stateColServiceList">
                            <?php
                            if ($counterNotHandled[$state] > 0):
                                echo "<div class='stateColServiceListItem'><a href='/services/index/Filter.Servicestatus.current_state[".$state."]:1/Filter.Servicestatus.problem_has_been_acknowledged[0]:1'>( ".$counterNotHandled[$state]." ) not handled</a></div>";
                            else:
                                echo "<div class='stateColServiceListItem'>( ".$counterNotHandled[$state]." ) not handled</div>";
                            endif;

                            if ($counterByHost[$state] > 0):
                                echo "<div class='stateColServiceListItem'><a href='/services/index/Filter.Servicestatus.current_state[".$state."]:1//Filter.Hoststatus.current_state[1]:1/Filter.Hoststatus.current_state[2]:1'>( ".$counterByHost[$state]." ) by host</a></div>";
                            else:
                                echo "<div class='stateColServiceListItem'>( ".$counterByHost[$state]." ) by host</div>";
                            endif;

                            if ($counterAchknowledged[$state] > 0):
                                echo "<div class='stateColServiceListItem'><a href='/services/index/Filter.Servicestatus.current_state[".$state."]:1/Filter.Servicestatus.problem_has_been_acknowledged[1]:1'>( ".$counterAchknowledged[$state]." ) acknowledged</a></div>";
                            else:
                                echo "<div class='stateColServiceListItem'>( ".$counterAchknowledged[$state]." ) acknowledged</div>";
                            endif;

                            if ($counterPlaned[$state] > 0):
                                echo "<div class='stateColServiceListItem'><a href='/services/index/Filter.Servicestatus.scheduled_downtime_depth[0]:1'>( ".$counterPlaned[$state]." ) planned</a></div>";
                            else:
                                echo "<div class='stateColServiceListItem'>( ".$counterPlaned[$state]." ) planned</div>";
                            endif;

                            if ($counterPassive[$state] > 0):
                                echo "<div class='stateColServiceListItem'><a href='/services/index/Filter.Servicestatus.current_state[".$state."]:1/Filter.Servicestatus.active_checks_enabled[0]:1'>( ".$counterPassive[$state]." ) passive</a></div>";
                            else:
                                echo "<div class='stateColServiceListItem'>( ".$counterPassive[$state]." ) passive</div>";
                            endif; ?>
                        </div>
                    </div>
                <?php endif;
            endforeach; ?>
        </div>
        <div class="toggleDetailsForPiechart"><i class="fa fa-angle-down"></i></div>
        <div class="col-md-12 text-center padding-bottom-10 font-xs">
            <?php foreach ($state_array_service as $state => $state_count): ?>
                <div class="col-md-3 no-padding">
                    <a href="<?php echo Router::url([
                        'controller'                                     => 'services',
                        'action'                                         => 'index',
                        'plugin'                                         => '',
                        'Filter.Servicestatus.current_state['.$state.']' => 1,
                    ]); ?>">
                        <i class="fa fa-square <?php echo $state_colors[$state] ?>"></i>
                        <?php
                        //Fix for a system without host or services
                        if ($state_total == 0):
                            $state_total = 1;
                            if ($state == 3):
                                $state_count = 1;
                            endif;
                        endif;
                        ?>
                        <?php echo $state_count.' ('.round($state_count / $state_total * 100, 2).' %)'; ?>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="text-muted padding-top-20">
            <?php echo __('No services are monitored on your system. Please create first a service'); ?>
        </div>
    <?php endif; ?>
</div>
