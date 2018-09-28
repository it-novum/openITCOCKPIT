<div style="width: 100%;">
  <div class="btn-group pull-right">
    <button class="btn btn-xs btn-success" ng-click="addMetric()">
        <?php echo __('Add metric'); ?>
    </button>
    <button class="btn btn-xs btn-danger">
        <?php echo __('Remove panel'); ?>
    </button>
  </div>
</div>

<ul>
    <li ng-repeat="metric in panel">
        <grafana-metric metric="metric"></grafana-metric>
    </li>
</ul>


<!-- Add new metric to panel modal -->
<div id="addMetricToPanelModal_{{rowId}}_{{panelId}}" class="modal" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">
                    <i class="fa fa-pencil"></i>
                    <?php echo __('Add new metric'); ?>
                </h4>
            </div>
            <div class="modal-body">

                <div class="row">

                    <div class="col-xs-12">
                        <div class="form-group smart-form hintmark_red">
                            <?php echo __('Select service'); ?>
                        </div>
                    </div>
                    <div class="col-xs-12">
                        <div class="form-group" ng-class="{'has-error': errors.object_id}">
                            <select
                                    data-placeholder="<?php echo __('Please choose'); ?>"
                                    class="form-control"
                                    chosen="services"
                                    callback="loadMoreServices"
                                    ng-options="itemObject.key as itemObject.value for itemObject in services"
                                    ng-model="currentServiceId">
                            </select>
                            <div ng-repeat="error in errors.object_id">
                                <div class="help-block text-danger">{{ error }}</div>
                            </div>
                        </div>
                    </div>
                    <br />

                    <div class="col-xs-12">
                        <div class="form-group smart-form hintmark_red">
                            <?php echo __('Select metric'); ?>
                        </div>
                    </div>
                    <div class="col-xs-12">
                        <div class="form-group" ng-class="{'has-error': errors.object_id}">
                            <select
                                    data-placeholder="<?php echo __('Please choose'); ?>"
                                    class="form-control"
                                    chosen="metrics"
                                    callback="loadMoreServices"
                                    ng-options="key as value for (key , value) in metrics"
                                    ng-model="currentServiceMetric">
                            </select>
                            <div ng-repeat="error in errors.object_id">
                                <div class="help-block text-danger">{{ error }}</div>
                            </div>
                        </div>
                    </div>
                </div>
                <br/>


            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">
                    <?php echo __('Close'); ?>
                </button>

                <button type="button" class="btn btn-primary" ng-click="saveLine()">
                    <?php echo __('Save'); ?>
                </button>
            </div>
        </div>
    </div>
</div>
