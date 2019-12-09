<div>
    <a href="javascript:void(0);" class="header-icon" data-toggle="dropdown" data-original-title="<?php echo __('System health'); ?>">
        <i class="fa fa-lg fa-heartbeat {{ class }}"></i>
        <span class="badge badge-icon">11</span>
    </a>


    <div class="dropdown-menu dropdown-menu-animated dropdown-xl">
        <div class="dropdown-header bg-trans-gradient d-flex justify-content-center align-items-center rounded-top mb-2">
            <h4 class="m-0 text-center color-white">
                System Health
            </h4>
        </div>
        <ul class="nav nav-tabs nav-tabs-clean" role="tablist">
            <li class="nav-item">
                <a class="nav-link px-4 fs-md js-waves-on fw-500" data-toggle="tab" href="#tab-messages"
                   data-i18n="drpdwn.messages">Messages</a>
            </li>
        </ul>
        <div class="tab-content tab-notification">
            <div class="tab-pane active p-3 text-center">
                <h5 class="mt-4 pt-4 fw-500">
                    <span class="d-block fa-3x pb-4 text-muted">
                        <i class="ni ni-arrow-up text-gradient opacity-70"></i>
                    </span> Select a tab above to activate
                    <small class="mt-3 fs-b fw-400 text-muted">
                        This blank page message helps protect your privacy, or you can show the first message here
                        automatically through
                        <a href="#">settings page</a>
                    </small>
                </h5>
            </div>
            <div class="tab-pane" id="tab-messages" role="tabpanel">
                <div class="custom-scroll h-100">


                    <!-- NEW START -->


                    <ul class="notification" >
                        <li >
                            <a ui-sref="AdministratorsDebug" class="d-flex align-items-center" href="javascript:void();">
                                <span class="mr-2">
                                    <i class="d-inline-block fas  fa-warning fa-3x down"></i>
                                </span>
                                <span class="d-flex flex-column flex-1 ml-1">
                                    <span class="name"><?php echo __('Critical'); ?></span>
                                    <span class="msg-a fs-sm"><?php echo __('Monitoring engine is not running!'); ?></span>
                                </span>
                            </a>
                        </li>
                        <!--
                        <li ng-if="!systemHealth.gearman_reachable">
                <span>
                    <div class="bar-holder no-padding">
                        <p class="margin-bottom-5">
                            <i class="fa fa-warning down"></i>
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
                            <i class="fa fa-warning down"></i>
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
                            <i class="fa fa-warning down"></i>
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
                            <i class="fa fa-warning down"></i>
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

                        <li ng-if="!systemHealth.isPushNotificationRunning">
                <span>
                    <div class="bar-holder no-padding">
                        <p class="margin-bottom-5">
                            <i class="fa fa-warning warning"></i>
                            <strong><?php echo __('Warning'); ?></strong>
                            <br/>
                            <i><?php echo __('Service push_notification is not running!'); ?></i>
                        </p>
                    </div>
                </span>
                        </li>

                        <li ng-if="!systemHealth.isNodeJsServerRunning">
                <span>
                    <div class="bar-holder no-padding">
                        <p class="margin-bottom-5">
                            <i class="fa fa-warning warning"></i>
                            <strong><?php echo __('Warning'); ?></strong>
                            <br/>
                            <i><?php echo __('NodeJS Server is not running'); ?></i>
                        </p>
                    </div>
                </span>
                        </li>

                        <li ng-if="systemHealth.load.state !== 'ok'">
                <span>
                    <div class="bar-holder no-padding">
                        <p class="margin-bottom-5">
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
                                 style="width: {{ systemHealth.memory_usage.swap.percentage }}%;"></div>
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
                            <i class="fa fa-check up"></i>
                            <i class="up"><?php echo __('No issues detected. System operates normally.'); ?></i>
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
                        </li> -->
                    </ul>
                </div>
            </div>

        </div>
        <div class="py-2 px-3 bg-faded d-block rounded-bottom text-right border-faded border-bottom-0 border-right-0 border-left-0">
            <a href="#" class="fs-xs fw-500 ml-auto">view all notifications</a>
        </div>
    </div>
</div>


<!--




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
                            <i class="fa fa-warning down"></i>
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
                            <i class="fa fa-warning down"></i>
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
                            <i class="fa fa-warning down"></i>
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
                            <i class="fa fa-warning down"></i>
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
                            <i class="fa fa-warning down"></i>
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

            <li ng-if="!systemHealth.isPushNotificationRunning">
                <span>
                    <div class="bar-holder no-padding">
                        <p class="margin-bottom-5">
                            <i class="fa fa-warning warning"></i>
                            <strong><?php echo __('Warning'); ?></strong>
                            <br/>
                            <i><?php echo __('Service push_notification is not running!'); ?></i>
                        </p>
                    </div>
                </span>
            </li>

            <li ng-if="!systemHealth.isNodeJsServerRunning">
                <span>
                    <div class="bar-holder no-padding">
                        <p class="margin-bottom-5">
                            <i class="fa fa-warning warning"></i>
                            <strong><?php echo __('Warning'); ?></strong>
                            <br/>
                            <i><?php echo __('NodeJS Server is not running'); ?></i>
                        </p>
                    </div>
                </span>
            </li>

            <li ng-if="systemHealth.load.state !== 'ok'">
                <span>
                    <div class="bar-holder no-padding">
                        <p class="margin-bottom-5">
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
                                 style="width: {{ systemHealth.memory_usage.swap.percentage }}%;"></div>
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
                            <i class="fa fa-check up"></i>
                            <i class="up"><?php echo __('No issues detected. System operates normally.'); ?></i>
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
-->
