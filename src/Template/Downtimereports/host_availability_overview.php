<div class="card margin-top-10" style="width: 100%">
    <div class="card-header">
        <h4 ng-style="{'color':  color}">
            <strong>
                <i class="fa fa-desktop" ></i>
                <?php if ($this->Acl->hasPermission('browser', 'hosts')): ?>
                    <a ui-sref="HostsBrowser({id:data.Host.id})">
                        {{data.Host.name}}
                    </a>
                <?php else: ?>
                    {{data.Host.name}}
                <?php endif; ?>
            </strong>
        </h4>
    </div>
    <div class="card-body">
        <div class="row margin-0">
            <div class="col-lg-1 no-padding">
                <canvas id="hostPieChart-{{data.Host.id}}"></canvas>
            </div>
            <div class="col-lg-11 no-padding font-sm">
                <div class="row">
                    <div class="col-lg-3 ">
                        <?php echo __('Description'); ?>
                    </div>
                    <div class="col-lg-9">
                        {{data.Host.description}}&nbsp;
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-3">
                        <?php echo __('IP address'); ?>
                    </div>
                    <div class="col-lg-9">
                        {{data.Host.address}}
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-3">
                        <?php echo __('Status'); ?>
                    </div>
                    <div class="col-lg-3 btn-success downtime-report-state-overview font-sm padding-5">
                        <strong>
                            {{data.pieChartData.widgetOverview[0].percent}} %
                            ({{data.pieChartData.widgetOverview[0].human}})
                        </strong>
                    </div>
                    <div class="col-lg-3 btn-danger downtime-report-state-overview font-sm padding-5">
                        <strong>
                            {{data.pieChartData.widgetOverview[1].percent}} %
                            ({{data.pieChartData.widgetOverview[1].human}})
                        </strong>
                    </div>
                    <div class="col-lg-3 btn-dark downtime-report-state-overview font-sm padding-5">
                        <strong>
                            {{data.pieChartData.widgetOverview[2].percent}} %
                            ({{data.pieChartData.widgetOverview[2].human}})
                        </strong>
                    </div>
                </div>
            </div>
        </div>
        <div class="row margin-0">
            <service-availability-overview data="service"
                                           dynamic-color="dynamicColor"
                                           ng-repeat="service in data.Services"
                                           ng-if="evaluationType == 1"
                                           class="col-lg-3">
            </service-availability-overview>
        </div>
    </div>

</div>
