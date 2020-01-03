<div>
    <a href="javascript:void(0);" class="header-icon" data-toggle="dropdown"
       data-original-title="<?php echo __('System health'); ?>">
        <i class="fa fa-lg fa-heartbeat {{ class }}"></i>
        <span class="badge badge-icon">11</span>
    </a>


    <div class="dropdown-menu dropdown-menu-animated dropdown-xl scrollable-menu">
        <div
            class="dropdown-header bg-trans-gradient d-flex justify-content-center align-items-center rounded-top mb-2">
            <h4 class="m-0 text-center color-white">
                System Health
            </h4>
        </div>

        <div class="dropdown-item" ng-if="!systemHealth.isNagiosRunning">
            <a ui-sref="AdministratorsDebug" class="d-flex align-items-center" href="javascript:void();">
                <span class="mr-2">
                    <i class="d-inline-block fas fa-warning fa-3x down"></i>
                </span>
                <span class="d-flex flex-column flex-1 ml-1">
                    <span class="notification-title">
                        <?php echo __('Critical'); ?>
                    </span>
                    <span class="notification-message fs-sm">
                        <?php echo __('Monitoring engine is not running!'); ?>
                    </span>
                </span>
            </a>
        </div>
        <div class="dropdown-item" ng-if="!systemHealth.gearman_reachable">
            <a ui-sref="AdministratorsDebug" class="d-flex align-items-center" href="javascript:void();">
                <span class="mr-2">
                    <i class="d-inline-block fas fa-warning fa-3x down"></i>
                </span>
                <span class="d-flex flex-column flex-1 ml-1">
                    <span class="notification-title">
                        <?php echo __('Critical'); ?>
                    </span>
                    <span class="notification-message fs-sm">
                        <?php echo __('Gearman job server not reachable!'); ?>
                    </span>
                </span>
            </a>
        </div>
        <div class="dropdown-item" ng-if="!systemHealth.gearman_worker_running">
            <a ui-sref="AdministratorsDebug" class="d-flex align-items-center" href="javascript:void();">
                <span class="mr-2">
                    <i class="d-inline-block fas fa-warning fa-3x down"></i>
                </span>
                <span class="d-flex flex-column flex-1 ml-1">
                    <span class="notification-title">
                        <?php echo __('Critical'); ?>
                    </span>
                    <span class="notification-message fs-sm">
                        <?php echo __('Service gearman_worker is not running!'); ?>
                    </span>
                </span>
            </a>
        </div>
        <div class="dropdown-item" ng-if="systemHealth.isNdoInstalled && !systemHealth.isNdoRunning">
            <a ui-sref="AdministratorsDebug" class="d-flex align-items-center" href="javascript:void();">
                <span class="mr-2">
                    <i class="d-inline-block fas fa-warning fa-3x down"></i>
                </span>
                <span class="d-flex flex-column flex-1 ml-1">
                    <span class="notification-title">
                        <?php echo __('Critical'); ?>
                    </span>
                    <span class="notification-message fs-sm">
                        <?php echo __('Database connector NDOUtils is not running!'); ?>
                    </span>
                </span>
            </a>
        </div>
        <div class="dropdown-item" ng-if="systemHealth.isStatusengineInstalled && !systemHealth.isStatusengineRunning">
            <a ui-sref="AdministratorsDebug" class="d-flex align-items-center" href="javascript:void();">
                <span class="mr-2">
                    <i class="d-inline-block fas fa-warning fa-3x down"></i>
                </span>
                <span class="d-flex flex-column flex-1 ml-1">
                    <span class="notification-title">
                        <?php echo __('Critical'); ?>
                    </span>
                    <span class="notification-message fs-sm">
                        <?php echo __('Database connector Statusengine is not running!'); ?>
                    </span>
                </span>
            </a>
        </div>
        <div class="dropdown-item"
             ng-if="systemHealth.isStatusenginePerfdataProcessor && !systemHealth.isStatusengineRunning">
            <a ui-sref="AdministratorsDebug" class="d-flex align-items-center" href="javascript:void();">
                <span class="mr-2">
                    <i class="d-inline-block fas fa-warning fa-3x warning"></i>
                </span>
                <span class="d-flex flex-column flex-1 ml-1">
                    <span class="notification-title">
                        <?php echo __('Warning'); ?>
                    </span>
                    <span class="notification-message fs-sm">
                        <?php echo __('Performance data processer Statusengine is not running!'); ?>
                    </span>
                </span>
            </a>
        </div>
        <div class="dropdown-item" ng-if="!systemHealth.isStatusenginePerfdataProcessor && !systemHealth.isNpcdRunning">
            <a ui-sref="AdministratorsDebug" class="d-flex align-items-center" href="javascript:void();">
                <span class="mr-2">
                    <i class="d-inline-block fas fa-warning fa-3x warning"></i>
                </span>
                <span class="d-flex flex-column flex-1 ml-1">
                    <span class="notification-title">
                        <?php echo __('Warning'); ?>
                    </span>
                    <span class="notification-message fs-sm">
                        <?php echo __('Performance data processer NPCD is not running!'); ?>
                    </span>
                </span>
            </a>
        </div>
        <div class="dropdown-item" ng-if="!systemHealth.isSudoServerRunning">
            <a ui-sref="AdministratorsDebug" class="d-flex align-items-center" href="javascript:void();">
                <span class="mr-2">
                    <i class="d-inline-block fas fa-warning fa-3x warning"></i>
                </span>
                <span class="d-flex flex-column flex-1 ml-1">
                    <span class="notification-title">
                        <?php echo __('Warning'); ?>
                    </span>
                    <span class="notification-message fs-sm">
                        <?php echo __('Service sudo_server is not running!'); ?>
                    </span>
                </span>
            </a>
        </div>
        <div class="dropdown-item" ng-if="!systemHealth.isOitcCmdRunning">
            <a ui-sref="AdministratorsDebug" class="d-flex align-items-center" href="javascript:void();">
                <span class="mr-2">
                    <i class="d-inline-block fas fa-warning fa-3x warning"></i>
                </span>
                <span class="d-flex flex-column flex-1 ml-1">
                    <span class="notification-title">
                        <?php echo __('Warning'); ?>
                    </span>
                    <span class="notification-message fs-sm">
                        <?php echo __('Service oitc_cmd is not running!'); ?>
                    </span>
                </span>
            </a>
        </div>
        <div class="dropdown-item" ng-if="!systemHealth.isPushNotificationRunning">
            <a ui-sref="AdministratorsDebug" class="d-flex align-items-center" href="javascript:void();">
                <span class="mr-2">
                    <i class="d-inline-block fas fa-warning fa-3x warning"></i>
                </span>
                <span class="d-flex flex-column flex-1 ml-1">
                    <span class="notification-title">
                        <?php echo __('Warning'); ?>
                    </span>
                    <span class="notification-message fs-sm">
                        <?php echo __('Service push_notification is not running!'); ?>
                    </span>
                </span>
            </a>
        </div>
        <div class="dropdown-item" ng-if="!systemHealth.isNodeJsServerRunning">
            <a ui-sref="AdministratorsDebug" class="d-flex align-items-center" href="javascript:void();">
                <span class="mr-2">
                    <i class="d-inline-block fas fa-warning fa-3x warning"></i>
                </span>
                <span class="d-flex flex-column flex-1 ml-1">
                    <span class="notification-title">
                        <?php echo __('Warning'); ?>
                    </span>
                    <span class="notification-message fs-sm">
                        <?php echo __('NodeJS Server is not running'); ?>
                    </span>
                </span>
            </a>
        </div>
        <div class="dropdown-item" ng-if="systemHealth.load.state !== 'ok'">
            <a ui-sref="AdministratorsDebug" class="d-flex align-items-center" href="javascript:void();">
                <span class="mr-2">
                    <i class="d-inline-block fas fa-warning fa-3x warning"></i>
                </span>
                <span class="d-flex flex-column flex-1 ml-1">
                    <span class="notification-title">
                        <?php echo __('Current CPU load is too high!'); ?>
                    </span>
                    <span class="notification-message fs-sm">
                       {{ systemHealth.load.load1 }}, {{ systemHealth.load.load5 }}, {{ systemHealth.load.load15 }}
                    </span>
                </span>
            </a>
        </div>
        <div class="dropdown-item" ng-if="systemHealth.memory_usage.memory.state !== 'ok'">
            <a ui-sref="AdministratorsDebug" class="d-flex align-items-center" href="javascript:void();">
                <span class="mr-2">
                    <i class="d-inline-block fas fa-warning fa-3x warning"></i>
                </span>
                <span class="d-flex flex-column flex-1 ml-1">
                    <span class="notification-title">
                        <?php echo __('High memory usage.'); ?>
                    </span>
                    <span class="notification-message fs-sm">
                        <span class="pull-right semi-bold text-muted">
                            {{ systemHealth.memory_usage.memory.percentage }}%
                        </span>
                        <div class="progress progress-sm">
                            <div class="progress-bar bg-color-darken"
                                 style="width: {{ systemHealth.memory_usage.memory.percentage }}%;"></div>
                        </div>
                    </span>

                </span>
            </a>
        </div>
        <div class="dropdown-item" ng-if="systemHealth.memory_usage.swap.state !== 'ok'">
            <a ui-sref="AdministratorsDebug" class="d-flex align-items-center" href="javascript:void();">
                <span class="mr-2">
                    <i class="d-inline-block fas fa-warning fa-3x warning"></i>
                </span>
                <span class="d-flex flex-column flex-1 ml-1">
                    <span class="notification-title">
                        <?php echo __('High Swap usage'); ?>
                    </span>
                    <span class="notification-message fs-sm">
                        <span class="pull-right semi-bold text-muted">
                                {{ systemHealth.memory_usage.swap.percentage }}%
                        </span>
                        <div class="progress progress-sm">
                            <div class="progress-bar bg-color-darken"
                                 style="width: {{ systemHealth.memory_usage.swap.percentage }}%;">
                            </div>
                        </div>
                    </span>

                </span>
            </a>
        </div>
        <div class="dropdown-item" ng-repeat="disk in systemHealth.disk_usage" ng-if="disk.state !== 'ok'">
            <a ui-sref="AdministratorsDebug" class="d-flex align-items-center" href="javascript:void();">
                <span class="mr-2">
                    <i class="d-inline-block fas fa-warning fa-3x warning"></i>
                </span>
                <span class="d-flex flex-column flex-1 ml-1">
                    <span class="notification-title">
                        <?php echo __('Low disk space left for mountpoint:'); ?>
                    </span>
                    <span class="notification-message fs-sm">
                        "{{ disk.mountpoint }}"
                        <span class="pull-right semi-bold text-muted">
                            {{ disk.use_percentage }}%
                        </span>
                        <div class="progress progress-sm">
                            <div class="progress-bar bg-color-darken"
                                 style="width: {{ disk.use_percentage }}%;">
                            </div>
                        </div>
                    </span>

                </span>
            </a>
        </div>
        <div class="dropdown-item" ng-if="systemHealth.isDistributeModuleInstalled && !systemHealth.isPhpNstaRunning">
            <a ui-sref="AdministratorsDebug" class="d-flex align-items-center" href="javascript:void();">
                <span class="mr-2">
                    <i class="d-inline-block fas fa-warning fa-3x warning"></i>
                </span>
                <span class="d-flex flex-column flex-1 ml-1">
                    <span class="notification-title">
                        <?php echo __('Warning'); ?>
                    </span>
                    <span class="notification-message fs-sm">
                        <?php echo __('Service phpnsta is not running!'); ?>
                    </span>
                </span>
            </a>
        </div>
        <div class="dropdown-item" ng-if="systemHealth.state == 'ok'">
            <a ui-sref="AdministratorsDebug" class="d-flex align-items-center" href="javascript:void();">
                <span class="mr-2">
                    <i class="d-inline-block fas fa-check fa-3x ok"></i>
                </span>
                <span class="d-flex flex-column flex-1 ml-1">
                    <span class="notification-title">
                        <?php echo __('Alright!'); ?>
                    </span>
                    <span class="notification-message fs-sm">
                        <?php echo __('No issues detected. System operates normally.'); ?>
                    </span>
                </span>
            </a>
        </div>

        <div class="dropdown-item" ng-if="systemHealth.state === 'unknown'">
            <a ui-sref="AdministratorsDebug" class="d-flex align-items-center" href="javascript:void();">
                <span class="mr-2">
                    <i class="d-inline-block fas fa-question-circle fa-3x unknown"></i>
                </span>
                <span class="d-flex flex-column flex-1 ml-1">
                    <span class="notification-title">
                        <?php echo __('Unknown'); ?>
                    </span>
                    <span class="notification-message fs-sm">
                        <?php echo __('Could not detect system health status.'); ?>
                    </span>
                </span>
            </a>
        </div>
        <div
            class="py-2 px-3 bg-faded d-block rounded-bottom text-right border-faded border-bottom-0 border-right-0 border-left-0">
            <a href="#" class="fs-xs fw-500 ml-auto">view all notifications</a>
        </div>
    </div>
</div>
