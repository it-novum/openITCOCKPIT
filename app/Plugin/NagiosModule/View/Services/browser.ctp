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
<div class="row">
    <div class="col-xs-12 col-sm-7 col-md-6 col-lg-6">
        <h1 class="page-title <?php echo $this->Status->ServiceStatusColor($service[0]['ServiceStatus']['current_state']); ?>">
            <i class="fa fa-gear fa-fw"></i>
            <?php echo $service[0]['Objects']['name2']; ?>
            <span>
                (<a href="/nagios_module/hosts/browser/<?php echo $service[0]['Service']['host_object_id']; ?>"><?php echo $service[0]['Objects']['name1']; ?></a>)
            </span>
        </h1>
    </div>
    <div class="col-xs-12 col-sm-5 col-md-6 col-lg-6">
        <h5>
            <span class="pull-right"><i class="fa fa-cog "></i> <span class="underline">E</span>dit service&nbsp;&nbsp;&nbsp;</span>
            <span class="pull-right"><i class="fa fa-refresh "></i> <span class="underline">R</span>efresh&nbsp;&nbsp;&nbsp;</span>
        </h5>
    </div>
</div>

<div class="row">
    <article class="col-xs-12 col-sm-12 col-md-12 col-lg-8 sortable-grid ui-sortable">
        <div data-widget-custombutton="false" data-widget-fullscreenbutton="false" data-widget-deletebutton="false"
             data-widget-togglebutton="false" data-widget-editbutton="false" data-widget-colorbutton="false"
             id="wid-id-11" class="jarviswidget jarviswidget-sortable" role="widget">
            <header role="heading">
                <h2><strong><?php echo __('Service'); ?>:</strong></h2>
                <ul class="nav nav-tabs pull-right" id="widget-tab-1">
                    <li class="active">
                        <a href="#tab1" data-toggle="tab"> <i class="fa fa-lg fa-info"></i> <span
                                    class="hidden-mobile hidden-tablet"> <?php echo __('status.information'); ?></span>
                        </a>
                    </li>
                    <!--
            <li class="">
                <a href="#tab2" data-toggle="tab"> <i class="fa fa-lg fa-hdd-o"></i> <span class="hidden-mobile hidden-tablet"> <?php //echo __('device.information'); ?> </span></a>
            </li>
        -->
                    <li class="">
                        <a href="#tab3" data-toggle="tab"> <i class="fa fa-lg fa-envelope-o"></i> <span
                                    class="hidden-mobile hidden-tablet"> <?php echo __('notification.information'); ?> </span></a>
                    </li>
                    <li class="">
                        <a href="#tab4" data-toggle="tab"> <i class="fa fa-lg fa-gear"></i> <span
                                    class="hidden-mobile hidden-tablet"> <?php echo __('service.commands'); ?> </span></a>
                    </li>
                </ul>
                <span class="jarviswidget-loader"><i class="fa fa-refresh fa-spin"></i></span></header>
            <!-- widget div-->
            <div role="content">
                <!-- widget edit box -->
                <div class="jarviswidget-editbox">
                    <!-- This area used as dropdown edit box -->
                </div>
                <!-- end widget edit box -->
                <!-- widget content -->
                <div class="widget-body no-padding">
                    <!-- widget body text-->
                    <div class="tab-content padding-10" style="min-height: 304px;">
                        <div id="tab1" class="tab-pane fade active in">
                            <?php echo $service[0]['Objects']['name2'].' (' ?>
                            <a href="/nagios_module/hosts/browser/<?php echo $service[0]['Service']['host_object_id']; ?>">
                                <?php echo $service[0]['Objects']['name1'] ?>
                            </a>
                            <?php echo ')'; ?> <strong>available
                                since: <?php echo $this->Time->format($service[0]['ServiceStatus']['last_state_change'], $this->Auth->user('dateformat'), false, $this->Auth->user('timezone')); ?></strong>
                            <br/><br/>
                            <p>The last system check occurred at
                                <strong><?php echo $this->Time->format($service[0]['ServiceStatus']['status_update_time'], $this->Auth->user('dateformat'), false, $this->Auth->user('timezone')); ?></strong>
                                according to Hard check.</p>
                            <dl>
                                <dt>Flap Detection:</dt>
                                <dd><?php echo $this->Nagios->checkFlapDetection($service[0]['ServiceStatus']['flap_detection_enabled'])['html']; ?></dd>
                                <dt>Check options:</dt>
                                <dd>Maximum attempts per
                                    check: <?php echo $service[0]['Host']['max_check_attempts']; ?></dd>
                                <dt>Check command:</dt>
                                <dd>
                                    <code class="<?php echo $this->Nagios->colorServiceOutput($service[0]['ServiceStatus']['current_state']); ?>"><?php echo $service[0]['ServiceStatus']['check_command']; ?></code>
                                </dd>
                                <dd>Command name:</dd>
                                <dd>Command line:</dd>
                                <dt>Output</dt>
                                <dd>
                                    <code class="<?php echo $this->Nagios->colorServiceOutput($service[0]['ServiceStatus']['current_state']); ?>"><?php echo $service[0]['ServiceStatus']['output']; ?></code>
                                </dd>
                            </dl>
                        </div>
                        <div id="tab2" class="tab-pane fade">
                            <strong>Client:</strong> it-novum<br/>
                            <strong>Location:</strong> Fulda<br/>
                            <strong>Device group:</strong> Server
                            <br/>
                            <br/>
                            <strong>IP address:</strong> <code><?php echo $service[0]['Host']['address']; ?></code><br/>
                            <strong>Description:</strong><br/>
                            <i class="txt-color-blue"><?php echo $service[0]['Service']['service_description']; ?></i>
                        </div>
                        <div id="tab3" class="tab-pane fade">
                            <strong>Notification period:</strong> 24x7<br/>
                            <strong>Notification interval:</strong> 2h 0m 0s<br/>
                            <br/>
                            <dl>
                                <dt>Notification occurs in the following cases:
                                <dt>
                                    <?php echo $this->Nagios->formatNotifyOnService([
                                        'notify_on_warning'  => $service[0]['Service']['notify_on_warning'],
                                        'notify_on_critical' => $service[0]['Service']['notify_on_critical'],
                                        'notify_on_unknown'  => $service[0]['Service']['notify_on_unknown'],
                                        'notify_on_recovery' => $service[0]['Service']['notify_on_recovery'],
                                        'notify_on_flapping' => $service[0]['Service']['notify_on_flapping'],
                                        'notify_on_downtime' => $service[0]['Service']['notify_on_downtime'],
                                    ]); ?>
                            </dl>
                            <dl>
                                <dt>The following persons are notified:
                                <dt>
                                <dd>openitcockpitSupport</dd>
                            </dl>
                        </div>
                        <div id="tab4" class="tab-pane fade">
                            <h5><span class="nag_command" data-toggle="modal" data-target="#nag_command_reschedule"><i
                                            class="fa fa-refresh"></i> Reset check time</span><br/></h5>
                            <h5><span class="nag_command" data-toggle="modal" data-target="#nag_command_reschedule"><i
                                            class="fa fa-download"></i> Passive transfer of check results</span><br/>
                            </h5>
                            <h5><span class="nag_command" data-toggle="modal" data-target="#nag_command_reschedule"><i
                                            class="fa fa-clock-o"></i> Set planned maintenance times</span><br/></h5>
                            <h5><span class="nag_command" data-toggle="modal" data-target="#nag_command_reschedule"><i
                                            class="fa fa-adjust"></i> Enables/disables flap detection for a particular service</span><br/>
                            </h5>
                        </div>
                    </div>
                    <!-- end widget body text-->
                    <!-- widget footer -->
                    <div class="widget-footer text-right"></div>
                    <!-- end widget footer -->
                </div>
                <!-- end widget content -->
            </div>
            <!-- end widget div -->
        </div>
    </article>
    <article class="col-xs-12 col-sm-12 col-md-12 col-lg-4 sortable-grid ui-sortable">
        <div data-widget-custombutton="false" data-widget-fullscreenbutton="false" data-widget-deletebutton="false"
             data-widget-togglebutton="false" data-widget-editbutton="false" data-widget-colorbutton="false"
             id="wid-id-11" class="jarviswidget jarviswidget-sortable" role="widget">
            <header role="heading">
                <h2><strong><?php echo __('Services (all)'); ?>:</strong></h2>
                <span class="jarviswidget-loader"><i class="fa fa-refresh fa-spin"></i></span>
            </header>
            <!-- widget div-->
            <!-- widget div-->
            <div>

                <!-- widget edit box -->
                <div class="jarviswidget-editbox">
                    <!-- This area used as dropdown edit box -->

                </div>
                <!-- end widget edit box -->

                <!-- widget content -->
                <div class="widget-body no-padding">
                    <?php //echo $this->ListFilter->renderFilterbox($filters, array(), '<i class="fa fa-search"></i> '.__('search'), false, false); ?>
                    <div class="widget-body-toolbar"></div>
                    <div class="custom-scroll table-responsive">
                        <table class="table table-bordered" id="service_browser_service_table">
                            <thead>
                            <tr>
                                <th><?php echo __('Status'); ?></th>
                                <th><?php echo __('Servicedescription'); ?></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $current_service = $service[0]['Objects']['name2']; ?>
                            <?php foreach ($all_services as $service): ?>
                                <tr class="<?php echo ($current_service == $service['Objects']['name2']) ? 'success' : ''; ?>">
                                    <td>
                                        <center><?php echo $this->Status->humanServiceStatus($service['ServiceStatus']['current_state'])['html_icon']; ?></center>
                                    </td>
                                    <td>
                                        <a href="/nagios_module/services/browser/<?php echo $service['Service']['service_object_id']; ?>"><?php echo $service['Objects']['name2']; ?></a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php if (empty($all_services)): ?>
                        <center><?php echo __('No entries match the selection'); ?></center>
                    <?php endif; ?>
                </div>
                <!-- end widget content -->

            </div>
            <!-- end widget div -->
            <!-- end widget div -->
        </div>
    </article>
    <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12 sortable-grid ui-sortable">
        <div data-widget-custombutton="false" data-widget-fullscreenbutton="false" data-widget-deletebutton="false"
             data-widget-togglebutton="false" data-widget-editbutton="false" data-widget-colorbutton="false"
             id="wid-id-11" class="jarviswidget jarviswidget-sortable" role="widget">
            <header role="heading">
                <h2><strong><i class="fa fa-area-chart"></i> <?php echo __('Graphs'); ?></strong></h2>
                <ul class="nav nav-tabs pull-right" id="widget-tab-1">
                    <?php $ds = 1; ?>
                    <?php foreach ($perfdata as $key => $perfdata_rule): ?>
                        <?php
                        echo '<li '.(($key == 0) ? 'class="active"' : '').'><a is-graph="true" href="#graph'.$key.'" ds="'.$ds.'" data-toggle="tab">'.$perfdata_rule['label'].'</a></li>';
                        ?>
                        <?php $ds++; ?>
                    <?php endforeach; ?>
                </ul>
                <div id="switch-1" class="widget-toolbar" role="menu">
                    <span data-original-title="Draw threshold values in graph" data-placement="bottom" rel="tooltip"
                          class="onoffswitch-title"><i class="fa fa-stethoscope"></i></span>
                    <span class="onoffswitch">
					<input type="checkbox" id="drawThresholdInGraph" class="onoffswitch-checkbox" name="onoffswitch">
					<label for="drawThresholdInGraph" class="onoffswitch-label"> 
						<span data-swchoff-text="OFF" data-swchon-text="ON" class="onoffswitch-inner"></span> 
					<span class="onoffswitch-switch"></span> </label> 
				</span>
                </div>
            </header>
            <!-- widget div-->
            <div role="content">
                <!-- widget edit box -->
                <div class="jarviswidget-editbox">
                    <!-- This area used as dropdown edit box -->
                </div>
                <!-- end widget edit box -->
                <!-- widget content -->
                <div class="widget-body no-padding">
                    <!-- widget body text-->
                    <div class="tab-content padding-10">
                        <div id="graph_data_tooltip"></div>
                        <?php foreach ($perfdata as $key => $perfdata_rule): ?>
                            <?php
                            echo '<div class="tab-pane fade'.(($key == 0) ? ' active in' : '').'" id="graph'.$key.'" style="height:200px;">'.$perfdata_rule['label'].'<h1><br/><br/><center><i class="fa fa-cog fa-spin"></i></center></h1></div>';
                            ?>
                        <?php endforeach; ?>
                    </div>
                    <!-- end widget body text-->
                    <!-- widget footer -->
                    <div class="widget-footer text-right"></div>
                    <!-- end widget footer -->
                </div>
                <!-- end widget content -->
            </div>
            <!-- end widget div -->
        </div>
    </article>
</div>