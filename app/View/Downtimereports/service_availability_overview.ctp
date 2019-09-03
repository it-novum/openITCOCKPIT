<div class="col-xs-12 col-md-6 col-lg-3 padding-2">
    <div class="col col-md-12 padding-2"
         ng-style="{'background': color}">
        <div class="col col-xs-4 col-md-4 col-lg-4 no-padding">
            <canvas id="servicePieChart-{{data.Service.id}}"></canvas>
        </div>
        <div class="col col-xs-8 col-md-8 col-lg-8 no-padding font-sm">
            <div class="row padding-bottom-3" ng-class="{'txt-color-white':dynamicColor}"
                 title="{{(data.Service.name === null)?data.Servicetemplate.name:data.Service.name}}">
                <div class="col-md-12 no-padding font-md">
                    <h5 class="no-padding ellipsis">
                        <i class="fa fa-cog"> </i>
                        {{(data.Service.name === null)?data.Servicetemplate.name:data.Service.name}}
                    </h5>
                </div>
            </div>
            <div class="row padding-bottom-3" ng-class="{'txt-color-white':dynamicColor}">
                <div class="col-md-12 no-padding font-sm ellipsis">
                    <i class="fa fa-pencil-square-o"> </i>
                    {{data.Servicetemplate.template_name}}
                </div>
            </div>
            <div class="row no-padding">
                <div class="col-md-12 btn-success downtime-report-state-overview padding-left-2">
                    {{data.pieChartData.widgetOverview[0].percent}} % ({{data.pieChartData.widgetOverview[0].human}})
                </div>
            </div>
            <div class="row no-padding">
                <div class="col-md-12 btn-warning downtime-report-state-overview padding-left-2">
                    {{data.pieChartData.widgetOverview[1].percent}} % ({{data.pieChartData.widgetOverview[1].human}})
                </div>
            </div>
            <div class="row no-padding">
                <div class="col-md-12 btn-danger downtime-report-state-overview padding-left-2">
                    {{data.pieChartData.widgetOverview[2].percent}} % ({{data.pieChartData.widgetOverview[2].human}})
                </div>
            </div>
            <div class="row no-padding">
                <div class="col-md-12 btn-unknown downtime-report-state-overview padding-left-2">
                    {{data.pieChartData.widgetOverview[3].percent}} % ({{data.pieChartData.widgetOverview[3].human}})
                </div>
            </div>
        </div>
    </div>
</div>