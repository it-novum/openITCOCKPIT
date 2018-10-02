<?php
use itnovum\openITCOCKPIT\Grafana\GrafanaTargetUnits;
$GrafanaUnits = new GrafanaTargetUnits();
$allGrafanaUnits = $GrafanaUnits->getUnits();
?>
<div class="grafana-panel padding-5">

    <div class="row padding-bottom-5">
        <div class="col-xs-12 no-padding">
            <label><?php echo __('Panel unit:'); ?></label>
            <select ng-model="panel.unit">
                <?php foreach($allGrafanaUnits as $category => $units): ?>
                    <optgroup label="<?php echo h($category); ?>">
                        <?php foreach($units as $unitKey => $unitName): ?>
                            <option value="<?php echo h($unitKey); ?>"><?php echo h($unitName); ?></option>
                        <?php endforeach; ?>
                    </optgroup>
                <?php endforeach;; ?>
            </select>

            <div class="btn-group pull-right">
                <button class="btn btn-xs btn-default txt-color-green" ng-click="addMetric()">
                    <i class="fa fa-plus"> </i>
                    <?php echo __('Add Metric'); ?>
                </button>
                <button class="btn btn-xs btn-default txt-color-red" ng-click="removePanel()">
                    <i class="fa fa-trash"> </i>
                    <?php echo __('Remove Panel'); ?>
                </button>
            </div>
        </div>
    </div>

    <div class="fuelux padding-bottom-5" ng-repeat="metric in panel.metrics">
        <div class="wizard no-steps-container" style="border-radius: 0;">
            <ul class="steps" title="{{metric.Host.hostname}}/{{metric.Service.servicename}}/{{metric.metric}}">
                <li style="height: 24px; line-height: 24px; font-size: 13px; padding-left: 2px; padding-right: 2px; background-color: #fff; color: #333;">
                    <?php if ($this->Acl->hasPermission('browser', 'hosts', '')): ?>
                        <a href="/hosts/browser/{{metric.Host.id}}">{{metric.Host.hostname}}</a>
                    <?php else: ?>
                        {{metric.Host.hostname}}
                    <?php endif; ?>
                </li>
                <li style="height: 24px; line-height: 24px; font-size: 13px; padding-left: 2px; padding-right: 2px; background-color: #fff; color: #333;">
                    <i class="fa fa-chevron-right"></i>
                    <?php if ($this->Acl->hasPermission('browser', 'services', '')): ?>
                        <a href="/services/browser/{{metric.Service.id}}">{{metric.Service.servicename}}</a>
                    <?php else: ?>
                        {{metric.Service.servicename}}
                    <?php endif; ?>
                </li>
                <li style="height: 24px; line-height: 24px; font-size: 13px; padding-left: 2px; padding-right: 2px; background-color: #fff; color: #333;">
                    <i class="fa fa-chevron-right"></i>
                    {{metric.metric}}
                </li>
            </ul>
            <div class="actions">
                <i class="fa fa-trash-o text-danger pull-right pointer"
                   style="height: 24px; line-height: 24px; font-size: 13px"
                   ng-click="removeMetric(metric)"></i>
            </div>
        </div>
    </div>

    <div class="row"  ng-show="panel.metrics.length == 0">
        <div class="col-xs-12 text-center padding-bottom-5 text-info">
            <i class="fa fa-info-circle"></i>
            <?php echo __('This panel is empty. Start by adding metrics.'); ?>
        </div>
    </div>


</div>

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
                        <div class="form-group" ng-class="{'has-error': errors.service_id}">
                            <select
                                    data-placeholder="<?php echo __('Please choose'); ?>"
                                    class="form-control"
                                    chosen="services"
                                    callback="loadMoreServices"
                                    ng-options="itemObject.key as itemObject.value for itemObject in services"
                                    ng-model="currentServiceId">
                            </select>
                            <div ng-repeat="error in errors.service_id">
                                <div class="help-block text-danger">{{ error }}</div>
                            </div>
                        </div>
                    </div>
                    <br/>

                    <div class="col-xs-12">
                        <div class="form-group smart-form hintmark_red">
                            <?php echo __('Select metric'); ?>
                        </div>
                    </div>
                    <div class="col-xs-12">
                        <div class="form-group" ng-class="{'has-error': errors.metric}">
                            <select
                                    data-placeholder="<?php echo __('Please choose'); ?>"
                                    class="form-control"
                                    chosen="metrics"
                                    callback="loadMoreServices"
                                    ng-options="key as value for (key , value) in metrics"
                                    ng-model="currentServiceMetric">
                            </select>
                            <div ng-repeat="error in errors.metric">
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

                <button type="button" class="btn btn-primary" ng-click="saveMetric()">
                    <?php echo __('Add metric'); ?>
                </button>
            </div>
        </div>
    </div>
</div>
