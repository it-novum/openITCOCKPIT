<i class="fa fa-lg fa-area-chart"
   ng-mouseenter="mouseenter($event)"
ng-mouseleave="mouseleave()">
</i>

<div id="serviceGraphContainer-{{service.Service.uuid}}" class="popup-graph-container">
    <div class="text-center padding-top-20 padding-bottom-20" style="width:100%;" ng-show="isLoading">
        <i class="fa fa-refresh fa-4x fa-spin"></i>
    </div>
    <div id="graph-{{service.Service.uuid}}"></div>
</div>
