<div class=" panel panel-default" style="background-color:#fcfcfc">
    <div class="panel-body padding-bottom-10">
        <div class="col-xs-12 margin-bottom-10">
            <div class="btn-group pull-right">
                <button class="btn btn-xs btn-success" ng-show="row.length < 4" ng-click="addPanel()">
                    <i class="fa fa-plus"> </i>
                    <?php echo __('Add Panel'); ?>
                </button>

                <button class="btn btn-xs btn-danger">
                    <i class="fa fa-plus"> </i>
                    <?php echo __('Remove Row'); ?>
                </button>
            </div>
        </div>
        <div class="col-xs-12 col-md-6" ng-class="panelClass" ng-repeat="panel in row">
            <grafana-panel id="id" panel="panel" panel-id="panel.id" remove-callback="removePanel"></grafana-panel>
        </div>
    </div>
</div>
<hr/>
