<div cass="col-xs-12">
    <div class="btn-group">
        <button class="btn btn-xs btn-success">
            <?php echo __('Add panel'); ?>
        </button>

        <button class="btn btn-xs btn-danger">
            <?php echo __('Remove row'); ?>
        </button>
    </div>
</div>
<div class="col-xs-12 {{panelClass}}" ng-repeat="(panelId, panel) in row" style="border: 1px solid blue;">
    <grafana-panel panel="panel" row-id="rowId" panel-id="panelId"></grafana-panel>
</div>
