<div class="col-xs-12 col-md-12 col-lg-12 padding-5">
    <div class="jarviswidget">
        <header role="heading" ng-style="{'background':  color}">
            <h2 class="txt-color-white">
                <strong>
                    <i class="fa fa-desktop"></i>
                    <?php if ($this->Acl->hasPermission('browser', 'hosts')): ?>
                        <a ui-sref="HostsBrowser({id:data.Host.id})" class="txt-color-white">
                            {{data.Host.name}}
                        </a>
                    <?php else: ?>
                        {{data.Host.name}}
                    <?php endif; ?>
                </strong>
            </h2>
        </header>
        <div class="widget-body">
            <div class="col col-md-12 padding-2">
                <div class="col col-xs-1 col-md-1 col-lg-1 no-padding">
                    <canvas id="hostPieChart-{{data.Host.id}}"></canvas>
                </div>
                <div class="col col-xs-11 col-md-11 col-lg-11 no-padding font-sm">
                    <div class="col-md-3 ">
                        <?php echo __('Description'); ?>
                    </div>
                    <div class="col-md-9 no-padding">
                        {{data.Host.description}}&nbsp;
                    </div>
                    <div class="col-md-3">
                        <?php echo __('IP address'); ?>
                    </div>
                    <div class="col-md-9 no-padding">
                        {{data.Host.address}}
                    </div>
                    <div class="col-md-3">
                        <?php echo __('Status'); ?>
                    </div>
                    <div class="col-md-3 btn-success downtime-report-state-overview font-sm padding-5">
                        <strong>
                            {{data.pieChartData.widgetOverview[0].percent}} %
                            ({{data.pieChartData.widgetOverview[0].human}})
                        </strong>
                    </div>
                    <div class="col-md-3 btn-danger downtime-report-state-overview font-sm padding-5">
                        <strong>
                            {{data.pieChartData.widgetOverview[1].percent}} %
                            ({{data.pieChartData.widgetOverview[1].human}})
                        </strong>
                    </div>
                    <div class="col-md-3 btn-unknown downtime-report-state-overview font-sm padding-5">
                        <strong>
                            {{data.pieChartData.widgetOverview[2].percent}} %
                            ({{data.pieChartData.widgetOverview[2].human}})
                        </strong>
                    </div>
                </div>
            </div>
            <service-availability-overview data="service"
                                           dynamic-color="dynamicColor"
                                           ng-repeat="service in data.Services"
                                           ng-if="evaluationType == 1">
            </service-availability-overview>
        </div>
    </div>
</div>

