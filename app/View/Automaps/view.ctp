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
    <div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
        <h1 class="page-title txt-color-blueDark">
            <i class="fa fa-magic fa-fw "></i>
            <?php echo __('Monitoring'); ?>
            <span>>
                <?php echo __('Automaps'); ?>
            </span>
        </h1>
    </div>
</div>
<div id="error_msg"></div>
<div class="alert alert-success alert-block" id="flashSuccess" style="display:none;">
    <a href="#" data-dismiss="alert" class="close">×</a>
    <h4 class="alert-heading"><i class="fa fa-check-circle-o"></i> <?php echo __('Command sent successfully'); ?></h4>
    <?php echo __('Page refresh in'); ?> <span id="autoRefreshCounter"></span> <?php echo __('seconds...'); ?>
</div>

<div class="jarviswidget" id="wid-id-0">
    <header>
        <span class="widget-icon"> <i class="fa fa-magic"></i> </span>
        <h2><?php echo __('View: ');
            echo h($automap['Automap']['name']); ?></h2>
        <div class="widget-toolbar" role="menu">
            <?php echo $this->Utils->backButton(); ?>
        </div>
    </header>
    <div>
        <div class="widget-body">
            <div class="row">
                <div class="col-xs-12 col-md-5">
                    <strong><?php echo __('Regular expression for hosts') ?>:</strong>
                    <?php echo h($automap['Automap']['host_regex']); ?>
                </div>
                <div class="col-xs-12 col-md-5">
                    <strong><?php echo __('Regular expression for services') ?>:</strong>
                    <?php echo h($automap['Automap']['service_regex']); ?>
                </div>
                <div class="col-xs-12 col-md-2">
                    <strong><?php echo __('Recursive') ?>:</strong>
                    <?php if ((bool)$automap['Automap']['recursive'] == true): ?>
                        <i class="fa fa-check txt-color-greenDark"></i>
                    <?php else: ?>
                        <i class="fa fa-times txt-color-red"></i>
                    <?php endif; ?>
                </div>

                <?php if ($automap['Automap']['group_by_host']): ?>

                <?php if ($automap['Automap']['show_label']): ?>
                    <?php $prevHost = null; ?>
                    <?php foreach ($services as $service): ?>
                        <?php if ($prevHost !== $service['Service']['host_id']): ?>
                            <?php if (!is_null($prevHost)): ?>
                                <div class="col-lg-12">&nbsp;</div>
                            <?php endif; ?>
                            <div class="col-lg-12"><h3 class="margin-bottom-5">
                                    <strong><?= h($hosts[$service['Service']['host_id']]); ?></strong></h3></div>
                        <?php endif; ?>
                        <div class="col-xs-12 col-md-4 col-lg-3 ellipsis"
                             style="font-size:<?php echo $fontSizes[$automap['Automap']['font_size']]; ?>">
								<span style="cursor:pointer;" class="triggerModal"
                                      service-id="<?php echo h($service['Service']['id']); ?>">
									<?php echo $this->Status->automapIcon($service, false); ?>
                                    <?php
                                    $serviceName = $service['Service']['name'];
                                    if ($serviceName == null || $serviceName == ''):
                                        $serviceName = $service['Servicetemplate']['name'];
                                    endif;
                                    echo h($serviceName);
                                    $prevHost = $service['Service']['host_id'];
                                    ?>
								</span>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                <?php $prevHost = null;
                $innerCounter = 3; ?>
                <?php foreach ($services as $service): ?>
                <?php if ($prevHost !== $service['Service']['host_id']): $counter = 2; ?>
                    <?php if ($innerCounter < 3) {
                        echo '</div>';
                    } ?>
                    <?php if (!is_null($prevHost)): ?>
                        <div class="col-lg-12">&nbsp;</div>
                    <?php endif; ?>
                    <div class="col-lg-12"><h3 class="margin-bottom-5">
                            <strong><?= h($hosts[$service['Service']['host_id']]); ?></strong></h3></div>
                <?php endif; ?>
                <?php if (++$counter % 3 == 0):
                $innerCounter = 0; ?>
                <div class="col-xs-4 col-sm-3 col-md-2 col-lg-1 text-left"
                     style="font-size:<?php echo $fontSizes[$automap['Automap']['font_size']]; ?>;">
                    <?php endif; ?>
                    <span style="cursor:pointer;" class="triggerModal"
                          service-id="<?php echo h($service['Service']['id']); ?>">
									<?php
                                    echo $this->Status->automapIcon($service);
                                    $prevHost = $service['Service']['host_id'];
                                    ?>
								</span>
                    <?php if (++$innerCounter % 3 == 0) {
                        $counter = 2;
                        echo '</div>';
                    } ?>
                    <?php endforeach; ?>
                    <?php endif; ?>

                    <?php else: ?>
                        <?php if ($automap['Automap']['show_label']): ?>
                            <?php foreach ($services as $service): ?>
                                <div class="col-xs-12 col-md-4 col-lg-3 ellipsis"
                                     style="font-size:<?php echo $fontSizes[$automap['Automap']['font_size']]; ?>">
  							<span style="cursor:pointer;" class="triggerModal"
                                  service-id="<?php echo h($service['Service']['id']); ?>">
  								<?php echo $this->Status->automapIcon($service, false); ?>
                                <?php
                                $serviceName = $service['Service']['name'];
                                if ($serviceName == null || $serviceName == ''):
                                    $serviceName = $service['Servicetemplate']['name'];
                                endif;
                                echo h($hosts[$service['Service']['host_id']]).'/'.h($serviceName);
                                echo h($serviceName);
                                $prevHost = $service['Service']['host_id'];
                                ?>
  							</span>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <?php foreach (array_chunk($services, 3) as $servicesPair): ?>
                                <div class="col-xs-6 col-md-4 col-lg-1">
                                    <table class="text-left"
                                           style="width: 100%; font-size:<?php echo $fontSizes[$automap['Automap']['font_size']]; ?>;">
                                        <tr>
                                            <?php foreach ($servicesPair as $service): ?>
                                                <td style="cursor:pointer;" class="triggerModal"
                                                    service-id="<?php echo h($service['Service']['id']); ?>">
                                                    <?php echo $this->Status->automapIcon($service); ?>
                                                </td>
                                            <?php endforeach; ?>
                                        </tr>
                                    </table>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    <?php endif; ?>


                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="serviceDetailsModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                        &times;
                    </button>
                    <h4 class="modal-title" id="myModalLabel"><i
                                class="fa fa-cogs"></i> <?php echo __('Service details'); ?></h4>
                </div>
                <div class="modal-body">

                    <div class="row">
                        <div id="modalLoading">
                            <center>
                                <h1><i class="fa fa-cog fa-spin fa-lg"></i></h1>
                            </center>
                        </div>

                        <div id="modalContent">
                            <div class="col-xs-12 col-md-10">
                                <strong><?php echo __('Service details'); ?></strong>
                                <br/>
                                <table>
                                    <tr>
                                        <td><strong><?php echo __('Host'); ?></strong></td>
                                        <td id="modalHostname" class="padding-left-5"></td>
                                    </tr>
                                    <tr>
                                        <td><strong><?php echo __('Service'); ?></strong></td>
                                        <td id="modelServicename" class="padding-left-5"></td>
                                    </tr>
                                    <tr>
                                        <td><strong><?php echo __('Current state'); ?></strong></td>
                                        <td id="modalServicestate" class="padding-left-5"></td>
                                    </tr>
                                    <tr>
                                        <td><strong><?php echo __('State type'); ?></strong></td>
                                        <td id="modalStateType" class="padding-left-5"></td>
                                    </tr>
                                    <tr>
                                        <td><strong><?php echo __('Last check'); ?></strong></td>
                                        <td id="modalLastCheck" class="padding-left-5"></td>
                                    </tr>
                                    <tr>
                                        <td><strong><?php echo __('State since'); ?></strong></td>
                                        <td id="modalStateSince" class="padding-left-5"></td>
                                    </tr>
                                    <tr>
                                        <td><strong><?php echo __('Output'); ?></strong></td>
                                        <td id="modalOutput" class="padding-left-5"></td>
                                    </tr>
                                    <tr>
                                        <td><strong><?php echo __('Perfdata'); ?></strong></td>
                                        <td id="modalPerfdata" class="padding-left-5"></td>
                                    </tr>
                                    <tr id="modalDowntime" style="display:none;">
                                        <td><i class="fa fa-power-off fa-lg"></i></td>
                                        <td><?php echo __('The service is currently in a planned maintenance period.'); ?></td>
                                    </tr>
                                    <tr id="modalAck" style="display:none;">
                                        <td><i class="fa fa-user fa-lg"></i></td>
                                        <td id="modalAckText"></td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-xs-12 col-md-2">
                                <strong><?php echo __('Commands'); ?></strong>
                                <br/>
                                <table>
                                    <tr>
                                        <td>
                                            <a href="javascript:void(0)" id="modalReschedule"
                                               data-original-title="<?php echo __('Reset check time'); ?>"
                                               data-placement="left" rel="tooltip" data-container="body"
                                               class="btn btn-default"><i class="fa fa-refresh"></i></a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <a href="javascript:void(0)" id="modalDowntime" data-toggle="modal"
                                               data-target="#nag_command_schedule_downtime"
                                               data-original-title="<?php echo __('Set planned maintenance times'); ?>"
                                               data-placement="left" rel="tooltip" data-container="body"
                                               class="btn btn-default"><i class="fa fa-clock-o"></i></a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <a
                                                    href="javascript:void(0);"
                                                    data-toggle="modal"
                                                    data-target="#nag_command_ack_state"
                                                    data-original-title="<?php echo __('Acknowledge status'); ?>"
                                                    data-placement="left"
                                                    rel="tooltip"
                                                    data-container="body"
                                                    class="btn btn-default">
                                                <i class="fa fa-user"></i>
                                            </a>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <div class="col-xs-12">
                            <br/>
                            <div id="graph_legend" class="graph_legend"></div>
                        </div>
                        <div class="col-xs-12" id="serviceGraph">
                            <!-- Graph will get loaded by javascript -->
                        </div>

                        <div class="col-xs-12">
                            <div id="graph_data_tooltip" style="z-index: 9999;"></div>
                            <div id="graph_loader">
                                <center>
                                    <h1><i class="fa fa-cog fa-spin fa-lg"></i></h1>
                                </center>
                            </div>
                        </div>

                    </div>

                </div>
                <div class="modal-footer">
                    <a href="#" class="btn btn-primary" id="modalBrowserLink"><i
                                class="fa fa-cog"></i> <?php echo __('Browser'); ?></a>
                    <button type="button" class="btn btn-default" data-dismiss="modal">
                        <?php echo __('Close'); ?>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="nag_command_schedule_downtime" tabindex="-1" role="dialog"
         aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                        &times;
                    </button>
                    <h4 class="modal-title" id="myModalLabel"><?php echo __('Set planned maintenance times'); ?></h4>
                </div>
                <div class="modal-body">

                    <div class="row">
                        <div class="txt-color-red padding-bottom-20" id="validationErrorServiceDowntime"
                             style="display:none;"><i
                                    class="fa fa-exclamation-circle"></i> <?php echo __('Please enter a valide date'); ?>
                        </div>
                        <?php
                        echo $this->Form->create('CommitServiceDowntime', [
                            'class' => 'form-horizontal clear',
                        ]); ?>
                        <?php echo $this->Form->input('comment', ['value' => __('In progress'), 'label' => __('Comment').':']); ?>

                        <!-- from -->
                        <div class="form-group">
                            <label class="col col-md-2 control-label"
                                   for="CommitServiceDowntimeFromDate"><?php echo __('From'); ?>:</label>
                            <div class="col col-xs-5" style="padding-right: 0px;">
                                <input type="text" id="CommitServiceDowntimeFromDate"
                                       value="<?php echo date('d.m.Y'); ?>" class="form-control"
                                       name="data[CommitServiceDowntime][from_date]">
                            </div>
                            <div class="col col-xs-5" style="padding-left: 0px;">
                                <input type="text" id="CommitServiceDowntimeFromTime" value="<?php echo date('h:m'); ?>"
                                       class="form-control" name="data[CommitServiceDowntime][from_time]">
                            </div>
                        </div>

                        <!-- to -->
                        <div class="form-group">
                            <label class="col col-md-2 control-label"
                                   for="CommitServiceDowntimeToDate"><?php echo __('To'); ?>:</label>
                            <div class="col col-xs-5" style="padding-right: 0px;">
                                <input type="text" id="CommitServiceDowntimeToDate"
                                       value="<?php echo date('d.m.Y', strtotime('+3 days')); ?>" class="form-control"
                                       name="data[CommitServiceDowntime][to_date]">
                            </div>
                            <div class="col col-xs-5" style="padding-left: 0px;">
                                <input type="text" id="CommitServiceDowntimeToTime" value="<?php echo date('h:m'); ?>"
                                       class="form-control" name="data[CommitServiceDowntime][to_time]">
                            </div>
                        </div>

                        <?php echo $this->Form->input('author', ['type' => 'hidden', 'value' => $username]) ?>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" id="submitCommitServiceDowntime">
                        <?php echo __('Send'); ?>
                    </button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">
                        <?php echo __('Cancel'); ?>
                    </button>
                </div>
                <?php echo $this->Form->end(); ?>
            </div>
        </div>
    </div>

    <div class="modal fade" id="nag_command_ack_state" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
         aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                        &times;
                    </button>
                    <h4 class="modal-title" id="myModalLabel"><?php echo __('Acknowledge Service status'); ?></h4>
                </div>
                <div class="modal-body">

                    <div class="row">
                        <?php
                        echo $this->Form->create('CommitServiceAck', [
                            'class' => 'form-horizontal clear',
                        ]); ?>
                        <?php echo $this->Form->input('comment', ['value' => __('In progress'), 'label' => __('Comment').':']); ?>
                        <?php echo $this->Form->input('sticky', ['type' => 'checkbox', 'label' => __('Sticky')]); ?>
                        <?php echo $this->Form->input('author', ['type' => 'hidden', 'value' => $username]) ?>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" data-dismiss="modal" id="submitServiceAck">
                        <?php echo __('Send'); ?>
                    </button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">
                        <?php echo __('Cancel'); ?>
                    </button>
                </div>
                <?php echo $this->Form->end(); ?>
            </div>
        </div>
    </div>