<?php

use itnovum\openITCOCKPIT\Grafana\GrafanaTargetUnits;

$GrafanaUnits = new GrafanaTargetUnits();
$allGrafanaUnits = $GrafanaUnits->getUnits();

$GrafanaColors = new \itnovum\openITCOCKPIT\Grafana\GrafanaColors();

?>
<div class="grafana-panel padding-5">

    <div class="row padding-bottom-5">
        <div class="col-lg-12">
            {{panel.title}}
            <span class="text-muted italic" ng-show="humanUnit">
                in {{humanUnit}}
            </span>
            <div class="btn-group pull-right">
                <button class="btn btn-xs btn-default txt-color-green" ng-click="addMetric()">
                    <i class="fa fa-plus"> </i>
                    <?php echo __('Add Metric'); ?>
                </button>
                <button class="btn btn-xs btn-default" ng-click="openPanelOptions()">
                    <i class="fa fa-wrench fa-flip-horizontal"></i>
                    <?php echo __('Panel options'); ?>
                </button>
                <button class="btn btn-xs btn-default txt-color-red" ng-click="removePanel()">
                    <i class="fa fa-trash"> </i>
                    <?php echo __('Remove Panel'); ?>
                </button>
            </div>
        </div>
    </div>

    <div class="padding-2 row margin-5 slightBorder" ng-repeat="metric in panel.metrics">
        <ol class="breadcrumb breadcrumb-seperator-1 noWordBreak col-10"
            style="padding-top: 0px; padding-bottom: 0px; margin-bottom: 0px;">
            <li class="breadcrumb-item">
                <?php if ($this->Acl->hasPermission('browser', 'hosts', '')): ?>
                    <a href="/hosts/browser/{{metric.Host.id}}">{{metric.Host.hostname}}</a>
                <?php else: ?>
                    {{metric.Host.hostname}}
                <?php endif; ?>
            </li>
            <li class="breadcrumb-item">
                <?php if ($this->Acl->hasPermission('browser', 'services', '')): ?>
                    <a href="/services/browser/{{metric.Service.id}}">{{metric.Service.servicename}}</a>
                <?php else: ?>
                    {{metric.Service.servicename}}
                <?php endif; ?>
            </li>
            <li class="breadcrumb-item">
                {{metric.metric}}
            </li>
        </ol>
        <div class="actions col-1">
            <div ng-if="metric.color"
                 class="grafana-child-color" style="background-color: {{metric.color}};"></div>
        </div>
        <div class="actions col-1" style="padding-top: 4px;">
            <i class="fa fa-trash text-danger float-right pointer"
               ng-click="removeMetric(metric)"></i>
        </div>
    </div>

    <div class="row" ng-show="panel.metrics.length == 0">
        <div class="col-lg-12 text-center padding-bottom-5 text-info">
            <i class="fa fa-info-circle"></i>
            <?php echo __('This panel is empty. Start by adding metrics.'); ?>
        </div>
    </div>


</div>


<!-- Add new metric to panel modal -->
<div id="addMetricToPanelModal_{{rowId}}_{{panelId}}" class="modal" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-pencil-alt"></i>
                    <?php echo __('Add new metric'); ?>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i class="fa fa-times"></i></span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="form-group col-lg-12">
                        <label class="control-label">
                            <?php echo __('Select service'); ?>
                        </label>
                        <select
                            data-placeholder="<?php echo __('Please choose'); ?>"
                            class="form-control"
                            chosen="services"
                            callback="loadMoreServices"
                            ng-options="itemObject.key as itemObject.value for itemObject in services"
                            ng-model="currentServiceId">
                        </select>
                    </div>

                    <div class="form-group col-lg-12">
                        <label class="control-label">
                            <?php echo __('Select metric'); ?>
                        </label>
                        <select
                            data-placeholder="<?php echo __('Please choose'); ?>"
                            class="form-control"
                            chosen="metrics"
                            ng-options="key as value for (key , value) in metrics"
                            ng-model="currentServiceMetric">
                        </select>
                    </div>
                </div>
                <div class="row padding-top-10 padding-bottom-10">
                    <div class="col-12">
                        <?= _('Color'); ?>
                    </div>

                    <?php foreach ($GrafanaColors->getColors() as $color): ?>
                        <div class="col-xs-12 col-mg-6 col-lg-3 padding-top-10">
                            <table>
                                <tr>
                                    <td>
                                        <div class="grafana-main-color"
                                             ng-class="{'grafana-selected-color': currentMetricColor === '<?= h($color['main']); ?>'}"
                                             ng-click="currentMetricColor='<?= h($color['main']); ?>'"
                                             style="background: <?= h($color['main']); ?>"></div>
                                    </td>
                                    <td class="padding-left-10">
                                        <?= h($color['name']); ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="padding-top-10" colspan="2">
                                        <?php foreach ($color['children'] as $childColor): ?>
                                            <div class="grafana-child-color"
                                                 ng-class="{'grafana-selected-color': currentMetricColor === '<?= h($childColor); ?>'}"
                                                 ng-click="currentMetricColor='<?= h($childColor); ?>'"
                                                 style="background: <?= h($childColor); ?>;display: inline-block;"></div>
                                        <?php endforeach; ?>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" ng-click="saveMetric()">
                    <?php echo __('Add metric'); ?>
                </button>
                <button type="button" class="btn btn-default" data-dismiss="modal">
                    <?php echo __('Close'); ?>
                </button>
            </div>
        </div>
    </div>
</div>


<!-- Panel options modal -->
<div id="panelOptionsModal_{{rowId}}_{{panelId}}" class="modal" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fa fa-wrench fa-flip-horizontal"></i>
                    <?php echo __('Panel options'); ?>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i class="fa fa-times"></i></span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-12">

                        <div class="form-group">
                            <label class="control-label">
                                <?php echo __('Panel title'); ?>
                            </label>
                            <input
                                class="form-control"
                                type="text"
                                ng-model="panel.title"
                                ng-model-options="{debounce: 500}">
                        </div>
                    </div>

                    <div class="form-group col-lg-12 padding-top-10">
                        <label class="control-label">
                            <?php echo __('Panel unit'); ?>
                        </label>
                        <select
                            data-placeholder="<?php echo __('Please choose'); ?>"
                            class="form-control"
                            chosen="grafanaUnits"
                            ng-init="panel.unit = panel.unit || 'none'"
                            ng-model="panel.unit"
                            ng-model-options="{debounce: 500}">
                            <?php foreach ($allGrafanaUnits as $category => $units): ?>
                                <optgroup label="<?php echo h($category); ?>">
                                    <?php foreach ($units as $unitKey => $unitName): ?>
                                        <option value="<?php echo h($unitKey); ?>"><?php echo h($unitName); ?></option>
                                    <?php endforeach; ?>
                                </optgroup>
                            <?php endforeach;; ?>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 text-info">
                        <i class="fa fa-info-circle"></i>
                        <?php echo __('Changes will be saved automatically.'); ?>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">
                    <?php echo __('Close'); ?>
                </button>
            </div>
        </div>
    </div>
</div>


<!-- Panel options modal -->
<div id="panelOptionsModal_{{rowId}}_{{panelId}}" class="modal" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">
                    <i class="fa fa-wrench fa-flip-horizontal"></i>
                    <?php echo __('Panel options'); ?>
                </h4>
            </div>
            <div class="modal-body">

                <div class="row">

                    <div class="col-xs-12">
                        <div class="form-group smart-form">
                            <?php echo __('Panel title'); ?>
                        </div>
                    </div>
                    <div class="col-xs-12 smart-form">
                        <div class="form-group smart-form">
                            <label class="input"> <b class="icon-prepend">
                                    <i class="fa fa-pencil"></i>
                                </b>
                                <input type="text" class="input-sm"
                                       ng-model="panel.title"
                                       ng-model-options="{debounce: 500}">
                            </label>
                        </div>
                    </div>
                    <br/>

                    <div class="col-xs-12 padding-top-5">
                        <div class="form-group smart-form">
                            <?php echo __('Panel unit'); ?>
                        </div>
                    </div>
                    <div class="col-xs-12">
                        <div class="form-group">
                            <!-- Date comes from API and PHP. PHP is required for the optgroup API data is used to tell Angular to render the chosen box-->
                            <select
                                data-placeholder="<?php echo __('Please choose'); ?>"
                                class="form-control"
                                chosen="grafanaUnits"
                                ng-init="panel.unit = panel.unit || 'none'"
                                ng-model="panel.unit"
                                ng-model-options="{debounce: 500}">
                                <?php foreach ($allGrafanaUnits as $category => $units): ?>
                                    <optgroup label="<?php echo h($category); ?>">
                                        <?php foreach ($units as $unitKey => $unitName): ?>
                                            <option
                                                value="<?php echo h($unitKey); ?>"><?php echo h($unitName); ?></option>
                                        <?php endforeach; ?>
                                    </optgroup>
                                <?php endforeach;; ?>
                            </select>
                        </div>
                    </div>
                    <br/>
                </div>
                <br/>

                <div class="row">
                    <div class="col-xs-12 text-info">
                        <i class="fa fa-info-circle"></i>
                        <?php echo __('Changes will be saved automatically.'); ?>
                    </div>
                </div>


            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">
                    <?php echo __('Close'); ?>
                </button>
            </div>
        </div>
    </div>
</div>
