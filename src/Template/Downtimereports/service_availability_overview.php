<div class="row margin-2 downtime-report-service-widget"">
    <div class=" col-lg-4">
        <canvas id="servicePieChart-{{data.Service.id}}"></canvas>
    </div>
    <div class="col-lg-8 font-sm">
        <div class="row padding-bottom-3" ng-style="{'background':  color}"
             title="{{(data.Service.name === null)?data.Servicetemplate.name:data.Service.name}}">
            <div class="col-lg-12 no-padding font-md">
                <h5 class="no-padding ellipsis" ng-class="{'text-white': dynamicColor}">
                    <i class="fa fa-cog"> </i>
                    <?php if ($this->Acl->hasPermission('browser', 'services')): ?>
                        <a ui-sref="ServicesBrowser({id:data.Service.id})" ng-class="{'text-white': dynamicColor}">
                            {{(data.Service.name === null)?data.Servicetemplate.name:data.Service.name}}
                        </a>
                    <?php else: ?>
                        {{(data.Service.name === null)?data.Servicetemplate.name:data.Service.name}}
                    <?php endif; ?>
                </h5>
            </div>
        </div>
        <div class="row padding-bottom-3">
            <div class="col-lg-12 no-padding font-sm ellipsis">
                <i class="fa-solid fa-pen-to-square"> </i>
                {{data.Servicetemplate.template_name}}
            </div>
        </div>
        <div class="row no-padding">
            <div class="col-lg-12 btn-success downtime-report-state-overview padding-left-2">
                {{data.pieChartData.widgetOverview[0].percent}} % ({{data.pieChartData.widgetOverview[0].human}})
            </div>
        </div>
        <div class="row no-padding">
            <div class="col-lg-12 btn-warning downtime-report-state-overview padding-left-2">
                {{data.pieChartData.widgetOverview[1].percent}} % ({{data.pieChartData.widgetOverview[1].human}})
            </div>
        </div>
        <div class="row no-padding">
            <div class="col-lg-12 btn-danger downtime-report-state-overview padding-left-2">
                {{data.pieChartData.widgetOverview[2].percent}} % ({{data.pieChartData.widgetOverview[2].human}})
            </div>
        </div>
        <div class="row no-padding">
            <div class="col-lg-12 btn-dark downtime-report-state-overview padding-left-2">
                {{data.pieChartData.widgetOverview[3].percent}} % ({{data.pieChartData.widgetOverview[3].human}})
            </div>
        </div>
    </div>
</div>
