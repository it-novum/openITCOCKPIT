<div class="grafana-row">
    <div class="row">
        <div class="col-xs-12 padding-5">
            <div class="btn-group pull-right">
                <button class="btn btn-xs btn-success" ng-show="row.length < 4" ng-click="addPanel()">
                    <i class="fa fa-plus"> </i>
                    <?php echo __('Add Panel'); ?>
                </button>

                <button class="btn btn-xs btn-danger" ng-click="removeRow()">
                    <i class="fa fa-plus"> </i>
                    <?php echo __('Remove Row'); ?>
                </button>
            </div>
        </div>
    </div>

    <div class="row padding-bottom-10">
        <div class="col-xs-12 col-md-6" ng-class="panelClass" ng-repeat="panel in row">
            <grafana-panel id="id" panel="panel" panel-id="panel.id" remove-callback="removePanel"
                           grafana-units="grafanaUnits" container-id="containerId"></grafana-panel>
        </div>
    </div>
</div>
