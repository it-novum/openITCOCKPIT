<div class=" panel panel-default" style="background-color:#fcfcfc">
    <div class="panel-body padding-bottom-10">
        <div class="margin-bottom-10">
            <div class="btn-group">
                <button class="btn btn-xs btn-success">
                  <i class="fa fa-plus"> </i>
                    <?php echo __('Add Panel'); ?>

                </button>

                <button class="btn btn-xs btn-danger">
                  <i class="fa fa-trash"> </i>
                    <?php echo __('Remove Row'); ?>
                </button>
            </div>
        </div>
        <div class="col-xs-12 {{panelClass}}" ng-repeat="panel in row">
            <grafana-panel id="id" panel="panel" panel-id="panel.id"></grafana-panel>
        </div>
    </div>
</div>
<hr />
