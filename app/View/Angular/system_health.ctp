<span id="activity" class="activity-dropdown" data-original-title="<?php echo __('System health'); ?>"
      data-placement="right" rel="tooltip" data-container="body">
    <i class="fa fa-heartbeat {{ class }}"></i>
</span>

<div class="ajax-dropdown" style="display: none;">
    <div>
        <strong><?php echo __('System health overview'); ?></strong>
    </div>


    <div class="ajax-notifications custom-scroll">
        <ul class="notification-body" ng-if="systemHealth.state !== 'unknown'">
            <li ng-if="!systemHealth.isNagiosRunning">
                <span>
                    <div class="bar-holder no-padding">
                        <p class="margin-bottom-5">
                            <i class="fa fa-warning text-danger"></i>
                            <strong><?php echo __('Critical'); ?></strong>
                            <br/>
                            <i><?php echo __('Monitoring engine is not running!'); ?></i>
                        </p>
                    </div>
                </span>
            </li>

            <li ng-if="!systemHealth.gearman_reachable">
                <span>
                    <div class="bar-holder no-padding">
                        <p class="margin-bottom-5">
                            <i class="fa fa-warning text-danger"></i>
                            <strong><?php echo __('Critical'); ?></strong>
                            <br/>
                            <i><?php echo __('Gearman job server not reachable!'); ?></i>
                        </p>
                    </div>
                </span>
            </li>

            <li ng-if="!systemHealth.gearman_worker_running">
                <span>
                    <div class="bar-holder no-padding">
                        <p class="margin-bottom-5">
                            <i class="fa fa-warning text-danger"></i>
                            <strong><?php echo __('Critical'); ?></strong>
                            <br/>
                            <i><?php echo __('Service gearman_worker is not running!'); ?></i>
                        </p>
                    </div>
                </span>
            </li>

            <li ng-if="systemHealth.isNdoInstalled && !systemHealth.isNdoRunning">
                <span>
                    <div class="bar-holder no-padding">
                        <p class="margin-bottom-5">
                            <i class="fa fa-warning text-danger"></i>
                            <strong><?php echo __('Critical'); ?></strong>
                            <br/>
                            <i><?php echo __('Database connector NDOUtils is not running!'); ?></i>
                        </p>
                    </div>
                </span>
            </li>

            <li ng-if="systemHealth.isStatusengineInstalled && !systemHealth.isStatusengineRunning">
                <span>
                    <div class="bar-holder no-padding">
                        <p class="margin-bottom-5">
                            <i class="fa fa-warning text-danger"></i>
                            <strong><?php echo __('Critical'); ?></strong>
                            <br/>
                            <i><?php echo __('Database connector Statusengine is not running!'); ?></i>
                        </p>
                    </div>
                </span>
            </li>

            <li ng-if="systemHealth.isStatusenginePerfdataProcessor && !systemHealth.isStatusengineRunning">
                <span>
                    <div class="bar-holder no-padding">
                        <p class="margin-bottom-5">
                            <i class="fa fa-warning warning"></i>
                            <strong><?php echo __('Warning'); ?></strong>
                            <br/>
                            <i><?php echo __('Performance data processer Statusengine is not running!'); ?></i>
                        </p>
                    </div>
                </span>
            </li>

            <li ng-if="!systemHealth.isStatusenginePerfdataProcessor && !systemHealth.isNpcdRunning">
                <span>
                    <div class="bar-holder no-padding">
                        <p class="margin-bottom-5">
                            <i class="fa fa-warning warning"></i>
                            <strong><?php echo __('Warning'); ?></strong>
                            <br/>
                            <i><?php echo __('Performance data processer NPCD is not running!'); ?></i>
                        </p>
                    </div>
                </span>
            </li>

            <li ng-if="!systemHealth.isSudoServerRunning">
                <span>
                    <div class="bar-holder no-padding">
                        <p class="margin-bottom-5">
                            <i class="fa fa-warning warning"></i>
                            <strong><?php echo __('Warning'); ?></strong>
                            <br/>
                            <i><?php echo __('Service sudo_server is not running!'); ?></i>
                        </p>
                    </div>
                </span>
            </li>

            <li ng-if="!systemHealth.isOitcCmdRunning">
                <span>
                    <div class="bar-holder no-padding">
                        <p class="margin-bottom-5">
                            <i class="fa fa-warning warning"></i>
                            <strong><?php echo __('Warning'); ?></strong>
                            <br/>
                            <i><?php echo __('Service oitc_cmd is not running!'); ?></i>
                        </p>
                    </div>
                </span>
            </li>

            <li ng-if="systemHealth.load.state !== 'ok'">
                <span>
                    <div class="bar-holder no-padding">
                        <p class="margin-bottom-5">
                            <i class="fa fa-warning text-danger"></i>
                            <strong><?php echo __('Critical'); ?></strong>
                            <br/>
                            <i><?php echo __('Current CPU load is too high!'); ?></i>
                            <br/>
                            <i>{{ systemHealth.load.load1 }}, {{ systemHealth.load.load5 }}, {{ systemHealth.load.load15 }}</i>
                        </p>
                    </div>
                </span>
            </li>

            <li ng-if="systemHealth.memory_usage.memory.state !== 'ok'">
                <span>
                    <div class="bar-holder no-padding">
                        <p class="margin-bottom-5">
                            <i><?php echo __('High memory usage.'); ?></i>
                            <span class="pull-right semi-bold text-muted">
                                {{ systemHealth.memory_usage.memory.percentage }}%
                            </span>
                        </p>
                        <div class="progress progress-sm">
                            <div class="progress-bar bg-color-darken"
                                 style="width: {{ systemHealth.memory_usage.memory.percentage }}%;"></div>
                        </div>
                    </div>
                </span>
            </li>

            <li ng-if="systemHealth.memory_usage.swap.state !== 'ok'">
                <span>
                    <div class="bar-holder no-padding">
                        <p class="margin-bottom-5">
                            <i><?php echo __('High Swap usage'); ?></i>
                            <span class="pull-right semi-bold text-muted">
                                {{ systemHealth.memory_usage.swap.percentage }}%
                            </span>
                        </p>
                        <div class="progress progress-sm">
                            <div class="progress-bar bg-color-darken"
                                 style="width: {{ systemHealth.swap.memory.percentage }}%;"></div>
                        </div>
                    </div>
                </span>
            </li>

            <li ng-repeat="disk in systemHealth.disk_usage" ng-if="disk.state !== 'ok'">
                <span>
                    <div class="bar-holder no-padding">
                        <p class="margin-bottom-5">
                            <i><?php echo __('Low disk space left for mountpoint:'); ?></i>
                            <br/>
                            <i>"{{ disk.mountpoint }}"</i>
                            <span class="pull-right semi-bold text-muted">
                                {{ disk.use_percentage }}%
                            </span>
                        </p>
                        <div class="progress progress-sm">
                            <div class="progress-bar bg-color-darken" style="width: {{ disk.use_percentage }}%;"></div>
                        </div>
                    </div>
                </span>
            </li>

            <li ng-if="systemHealth.isDistributeModuleInstalled && !systemHealth.isPhpNstaRunning">
                <span>
                    <div class="bar-holder no-padding">
                        <p class="margin-bottom-5">
                            <i class="fa fa-warning warning"></i>
                            <strong><?php echo __('Warning'); ?></strong>
                            <br/>
                            <i><?php echo __('Service phpnsta is not running!'); ?></i>
                        </p>
                    </div>
                </span>
            </li>

            <li ng-if="systemHealth.state == 'ok'">
                <span>
                    <div class="bar-holder no-padding">
                        <p class="margin-bottom-5">
                            <i class="fa fa-check txt-color-green"></i>
                            <i class="txt-color-green"><?php echo __('No issues detected. System operates normally.'); ?></i>
                        </p>
                    </div>
                </span>
            </li>

        </ul>

        <ul class="notification-body" ng-if="systemHealth.state === 'unknown'">
            <li ng-if="!systemHealth.isNagiosRunning">
                <span>
                    <div class="bar-holder no-padding">
                        <p class="margin-bottom-5">
                            <i class="fa fa-question-circle-o text-primary"></i>
                            <strong><?php echo __('Unknown'); ?></strong>
                            <br/>
                            <i><?php echo __('Could not detect system health status.'); ?></i>
                        </p>
                    </div>
                </span>
            </li>
        </ul>
    </div>

    <span><?php echo __('Last update'); ?>: {{ systemHealth.update }}</span>

</div>
