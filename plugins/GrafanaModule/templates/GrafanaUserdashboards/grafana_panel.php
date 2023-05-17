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
        <ol class="breadcrumb breadcrumb-seperator-1 noWordBreak col-8 ellipsis no-padding"
            style="padding-top: 0px; padding-bottom: 0px; margin-bottom: 0px;">
            <li class="breadcrumb-item">
                <?php if ($this->Acl->hasPermission('browser', 'hosts', '')): ?>
                    <a ui-sref="HostsBrowser({id:metric.Host.id})">
                        {{ metric.Host.hostname }}
                    </a>
                <?php else: ?>
                    {{metric.Host.hostname}}
                <?php endif; ?>
            </li>
            <li class="breadcrumb-item">
                <?php if ($this->Acl->hasPermission('browser', 'services', '')): ?>
                    <a ui-sref="ServicesBrowser({id: metric.Service.id})">
                        {{metric.Service.servicename}}
                    </a>
                <?php else: ?>
                    {{metric.Service.servicename}}
                <?php endif; ?>
            </li>
            <li class="breadcrumb-item" title="{{metric.metric}}">
                {{metric.metric}}
            </li>
        </ol>
        <div class="actions col-2 no-padding text-right">
            <span class="fa-stack fa-xs" ng-if="metric.color">
                <i class="fa-solid fa-circle fa-stack-2x" style="color: {{metric.color}};"></i>
                <i class="fa-solid fa-terminal fa-stack-1x" style="color: {{metric.color}};"></i>
            </span>
            <span class="fa-stack fa-xs" ng-switch="panel.visualization_type">
                <i class="fa-solid fa-circle fa-stack-2x"></i>

                <i class="fa-solid fa-chart-column fa-stack-1x fa-inverse" title=" <?= __('Bar chart'); ?>"
                   ng-switch-when="barchart"></i>

                <i class="fa-solid fa-terminal fa-stack-1x fa-inverse" title=" <?= __('Stat'); ?>"
                   ng-switch-when="stat"></i>

                <i class="fa-solid fa-gauge-high fa-stack-1x fa-inverse" title=" <?= __('Gauge'); ?>"
                   ng-switch-when="gauge"></i>

                <i class="fa-solid fa-chart-simple fa-rotate-90 fa-stack-1x fa-inverse" title=" <?= __('Bar gauge'); ?>"
                   ng-switch-when="bargauge"></i>

                <i class="fa-solid fa-bars fa-stack-1x fa-inverse" title=" <?= __('Bar gauge (Retro LCD)'); ?>"
                   ng-switch-when="bargaugeretro"></i>

                <i class="fa-solid fa-chart-area fa-stack-1x fa-inverse" title=" <?= __('Time series'); ?>"
                   ng-switch-default></i>
            </span>
        </div>
        <div class="actions col-2 no-padding text-right">
            <i class="fa-solid fa-pencil text-primary pointer"
               ng-click="loadMetric(metric)"></i>
            <i class="fa fa-trash text-danger pointer px-1"
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
                    <span ng-hide="metric.id">
                        <?php echo __('Add new metric'); ?>
                    </span>
                    <span ng-show="metric.id">
                        <?php echo __('Update metric'); ?>
                    </span>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i class="fa fa-times"></i></span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="form-group col-lg-12 required" ng-class="{'has-error': errors.service_id}">
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
                        <div ng-repeat="error in errors.service_id">
                            <div class="help-block text-danger">{{ error }}</div>
                        </div>
                    </div>

                    <div class="form-group col-lg-12 required" ng-class="{'has-error': errors.metric}">
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
                        <div ng-repeat="error in errors.metric">
                            <div class="help-block text-danger">{{ error }}</div>
                        </div>
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
                <button type="button" class="btn btn-success" ng-click="saveMetric()" ng-hide="metric.id">
                    <?php echo __('Add metric'); ?>
                </button>
                <button type="button" class="btn btn-success" ng-click="updateMetric(metric.id)" ng-show="metric.id">
                    <?php echo __('Update metric'); ?>
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
                <div class="row pt-2">
                    <div class="col-12">
                        <label class="control-label">
                            <?= __('Visualization type'); ?>
                        </label>
                    </div>
                    <div class="col-xs-12 col-md-4 col-lg-4 my-1 px-1">
                        <button type="button"
                                class="btn btn-outline-primary waves-effect waves-themed w-100"
                                ng-click="changeVisualizationType('timeseries')"
                                ng-class="{'btn-primary text-white': panel.visualization_type === 'timeseries'}">
                            <i class="fa-solid fa-chart-area"></i>
                            <?= __('Time series'); ?>
                        </button>
                    </div>
                    <div class="col-xs-12 col-md-4 col-lg-4 my-1 px-1">
                        <button type="button"
                                class="btn btn-outline-primary waves-effect waves-themed w-100"
                                ng-click="changeVisualizationType('barchart')"
                                ng-class="{'btn-primary text-white': panel.visualization_type === 'barchart'}">
                            <i class="fa-solid fa-chart-column"></i>
                            <?= __('Bar chart'); ?>
                        </button>
                    </div>
                    <div class="col-xs-12 col-md-4 col-lg-4 my-1 px-1">
                        <button type="button"
                                class="btn btn-outline-primary waves-effect waves-themed w-100"
                                ng-click="changeVisualizationType('stat')"
                                ng-class="{'btn-primary text-white': panel.visualization_type === 'stat'}">
                            <i class="fa-solid fa-terminal"></i>
                            <?= __('Stat'); ?>
                        </button>
                    </div>
                    <div class="col-xs-12 col-md-4 col-lg-4 my-1 px-1">
                        <button type="button"
                                class="btn btn-outline-primary waves-effect waves-themed w-100"
                                ng-click="changeVisualizationType('gauge')"
                                ng-class="{'btn-primary text-white': panel.visualization_type === 'gauge'}">
                            <i class="fa-solid fa-gauge-high"></i>
                            <?= __('Gauge'); ?>
                        </button>
                    </div>
                    <div class="col-xs-12 col-md-4 col-lg-4 my-1 px-1">
                        <button type="button"
                                class="btn btn-outline-primary waves-effect waves-themed w-100"
                                ng-click="changeVisualizationType('bargauge')"
                                ng-class="{'btn-primary text-white': panel.visualization_type === 'bargauge'}">
                            <i class="fa-solid fa-chart-simple fa-rotate-90"></i>
                            <?= __('Bar gauge'); ?>
                        </button>
                    </div>
                    <div class="col-xs-12 col-md-4 col-lg-4 my-1 px-1">
                        <button type="button"
                                class="btn btn-outline-primary waves-effect waves-themed w-100"
                                ng-click="changeVisualizationType('bargaugeretro')"
                                ng-class="{'btn-primary text-white': panel.visualization_type === 'bargaugeretro'}">
                            <i class="fa-solid fa-bars"></i>
                            <?= __('Bar gauge'); ?>
                            <sub><?= __('Retro LCD'); ?></sub>
                        </button>
                    </div>
                </div>
                <div class="row pt-2">
                    <div class="col-12">
                        <label class="control-label">
                            <?= __('Stack series'); ?>
                            <span class="help-block">
                                <?= __('only available for time series and bar charts'); ?>
                            </span>
                        </label>
                    </div>
                    <div class="col-xs-12 col-md-4 col-lg-4 my-1 px-1">
                        <button type="button"
                                class="btn btn-xs waves-effect waves-themed w-100"
                                ng-click="changeStackingMode('none')"
                                ng-disabled="(!(panel.visualization_type === 'timeseries' || panel.visualization_type === 'barchart'))"
                                ng-class="{'btn-primary': panel.stacking_mode === 'none', 'btn-outline-primary': panel.stacking_mode !== 'none'}">
                            <?= __('Off'); ?>
                        </button>

                    </div>
                    <div class="col-xs-12 col-md-4 col-lg-4 my-1 px-1">
                        <button type="button"
                                class="btn btn-xs waves-effect waves-themed w-100"
                                ng-click="changeStackingMode('normal')"
                                ng-disabled="(!(panel.visualization_type === 'timeseries' || panel.visualization_type === 'barchart'))"
                                ng-class="{'btn-primary': panel.stacking_mode === 'normal', 'btn-outline-primary': panel.stacking_mode !== 'normal'}">
                            <?= __('Normal'); ?>
                        </button>
                    </div>
                    <div class="col-xs-12 col-md-4 col-lg-4 my-1 px-1">
                        <button type="button"
                                class="btn btn-xs waves-effect waves-themed w-100"
                                ng-click="changeStackingMode('percent')"
                                ng-disabled="(!(panel.visualization_type === 'timeseries' || panel.visualization_type === 'barchart'))"
                                ng-class="{'btn-primary': panel.stacking_mode === 'percent', 'btn-outline-primary': panel.stacking_mode !== 'percent'}">
                            <?= __('100%'); ?>
                        </button>
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
</div>
