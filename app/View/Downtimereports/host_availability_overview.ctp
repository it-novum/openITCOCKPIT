<div class="col-xs-12 col-md-6 col-lg-3 padding-5">
    <div class="col col-md-12 padding-5 rounded-box"
         ng-style="{'background':  color}"
         style="box-shadow: 1px 1px 3px #ccc;">
        <div class="col col-xs-4 col-md-4 col-lg-4 no-padding">
            <canvas id="hostPieChart-{{data.Host.id}}"></canvas>
        </div>
        <div class="col col-xs-8 col-md-8 col-lg-8 no-padding font-sm">
            <div class="row padding-bottom-3 txt-color-white">
                <div class="col-md-12 no-padding font-md ellipsis"
                     style="text-shadow: 1px 0px 1px rgba(0, 0, 0, 0.5);">
                    <h3 class="no-padding">
                        <i class="fa fa-desktop"> </i>
                        {{data.Host.name}}
                    </h3>

                </div>
            </div>
            <div class="row padding-bottom-5 txt-color-white">
                <div class="col-md-12 no-padding font-sm ellipsis"
                     style="text-shadow: 1px 0px 1px rgba(0, 0, 0, 0.5);">
                    <i class="fa fa-info"> </i>
                    {{data.Host.address}}
                </div>
            </div>
            <div class="row padding-bottom-3 txt-color-white">
                <div class="col-md-12 padding-bottom-3 padding-left-0 font-sm ellipsis"
                     title="{{data.Host.description}}">
                    {{data.Host.description}}
                    &nbsp;
                </div>
            </div>
            <div class="row no-padding font-sm">
                <div class="col-md-4 btn-success downtime-report-state-overview padding-left-2">
                    {{data.pieChartData.widgetOverview[0].percent}} %
                </div>
                <div class="col-md-8 btn-success downtime-report-state-overview padding-left-2">
                    {{data.pieChartData.widgetOverview[0].human}}
                </div>
            </div>
            <div class="row no-padding font-sm">
                <div class="col-md-4 btn-danger downtime-report-state-overview padding-left-2">
                    {{data.pieChartData.widgetOverview[1].percent}} %
                </div>
                <div class="col-md-8 btn-danger downtime-report-state-overview padding-left-2">
                    {{data.pieChartData.widgetOverview[1].human}}
                </div>
            </div>
            <div class="row no-padding font-sm">
                <div class="col-md-4 btn-unknown downtime-report-state-overview padding-left-2">
                    {{data.pieChartData.widgetOverview[2].percent}} %
                </div>
                <div class="col-md-8 btn-unknown downtime-report-state-overview padding-left-2">
                    {{data.pieChartData.widgetOverview[2].human}}
                </div>
            </div>
        </div>
    </div>
</div>
