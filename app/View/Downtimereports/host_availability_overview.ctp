<div class="col-xs-12 col-md-12 col-lg-12 padding-5">
    <div class="jarviswidget">
        <header role="heading" ng-style="{'background':  color}">
            <h2 class="txt-color-white">
                <strong>
                    <i class="fa fa-desktop"></i>
                    <a href="/hosts/browser/16" class="txt-color-white">
                        {{data.Host.name}}
                    </a>
                </strong>
            </h2>
        </header>
        <div class="widget-body">
            <div class="col-md-12">
                <div class="col-md-1">
                    <canvas id="hostPieChart-{{data.Host.id}}"></canvas>
                </div>
                <div class="col-md-11">
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
        </div>
    </div>
</div>
