´<?php
// Copyright (C) <2015>  <it-novum GmbH>
//
// This file is dual licensed
//
// 1.
//	This program is free software: you can redistribute it and/or modify
//	it under the terms of the GNU General Public License as published by
//	the Free Software Foundation, version 3 of the License.
//
//	This program is distributed in the hope that it will be useful,
//	but WITHOUT ANY WARRANTY; without even the implied warranty of
//	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//	GNU General Public License for more details.
//
//	You should have received a copy of the GNU General Public License
//	along with this program.  If not, see <http://www.gnu.org/licenses/>.
//

// 2.
//	If you purchased an openITCOCKPIT Enterprise Edition you can use this file
//	under the terms of the openITCOCKPIT Enterprise Edition license agreement.
//	License agreement and license key will be shipped with the order
//	confirmation.

$widgetData = $widgetGafana[$widget['Widget']['id']];
$grafanaHostId = null;
$widgetId = null;
if (!empty($widgetData['Widget']['Widget']['host_id'])):
    $grafanaHostId = $widgetData['Widget']['Widget']['host_id'];
endif;

if(!empty($widgetData['Widget']['Widget']['id'])){
    $widgetId = $widgetData['Widget']['Widget']['id'];
}
?>
<div class="widget-body grafana-body">
    <div class="padding-0 <?php //if (!is_null($grafanaHostId)) {
    // echo 'display-none';
    //} ?>">
        <div class="panel-group smart-accordion-default" id="accordion-<?php echo $widget['Widget']['id']; ?>">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <?php if ($grafanaHostId === null): ?>
                            <a data-toggle="collapse" data-parent="#accordion-<?php echo $widget['Widget']['id']; ?>"
                               href="#collapseOne-1-<?php echo $widget['Widget']['id']; ?>" aria-expanded="true" class="">
                                <i class="fa fa-lg fa-angle-down pull-right"></i> <i
                                        class="fa fa-lg fa-angle-up pull-right"></i> <?php echo __('Configuration'); ?>
                            </a>
                        <?php else: ?>
                            <a data-toggle="collapse" data-parent="#accordion-<?php echo $widget['Widget']['id']; ?>"
                               href="#collapseOne-1-<?php echo $widget['Widget']['id']; ?>" aria-expanded="false"
                               class="collapsed">
                                <i class="fa fa-lg fa-angle-down pull-right"></i> <i
                                        class="fa fa-lg fa-angle-up pull-right"></i> <?php echo __('Configuration'); ?>
                            </a>
                        <?php endif; ?>
                    </h4>
                </div>
                <div id="collapseOne-1-<?php echo $widget['Widget']['id']; ?>"
                     class="panel-collapse collapse <?php echo ($grafanaHostId === null) ? 'in' : ''; ?>" aria-expanded="true">
                    <div class="panel-body">
                        <div>
                            <div class="row">
                                <div class="col-xs-12">
                                    <?php
                                    echo $this->Form->create('dashboard', [
                                        'class' => 'clear',
                                        'url'   => 'saveGrafanaId',
                                        'id'    => 'GrafanaForm-'.$widget['Widget']['id'],
                                    ]);
                                    echo $this->Form->input('tabId', [
                                        'type'  => 'hidden',
                                        'value' => $widget['Widget']['dashboard_tab_id'],
                                        'form'  => 'GrafanaForm-'.$widget['Widget']['id'],
                                    ]);
                                    echo $this->Form->input('widgetId', [
                                        'type'  => 'hidden',
                                        'value' => $widget['Widget']['id'],
                                        'form'  => 'GrafanaForm-'.$widget['Widget']['id'],
                                    ]);
                                    ?>
                                    <select class="chosen grafanaSelectHost"
                                            data-widget-id="<?php echo $widget['Widget']['id']; ?>"
                                            placeholder="<?php echo __('Please select'); ?>"
                                            name="data[dashboard][hostId]" style="width:100%;">
                                        <option></option>
                                        <?php foreach ($grafanaHostListForWidget as $data): ?>
                                            <?php
                                            $grafanaData = $data['GrafanaDashboard'];
                                            $selected = '';
                                            if ($grafanaData['host_id'] !== null && $grafanaData['host_id'] == $grafanaHostId):
                                                $selected = 'selected="selected"';
                                            endif;
                                            ?>
                                            <option value="<?php echo $grafanaData['host_id']; ?>" <?php echo $selected; ?>><?php echo h($data['Host']['name']); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <br/>
                                    <br/>
                                    <div class="pull-right padding-top-10">
                                        <a href="javascript:void(0);" class="btn btn-default previewTacho"
                                           data-widget-id="<?php echo $widget['Widget']['id']; ?>">
                                            <?php echo __('Preview'); ?>
                                        </a>
                                        <?php
                                        echo $this->Form->submit(__('Save'), [
                                            'class' => [
                                                'btn btn-primary',
                                            ],
                                            'form'  => 'GrafanaForm-'.$widget['Widget']['id'],
                                            'div'   => false,
                                            'value' => 1,
                                        ]); ?>
                                    </div>
                                    <?php echo $this->Form->end(); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="grafanaWrapper">
        <?php
        if (isset($widgetData['GrafanaDashboardExists']) && $widgetData['GrafanaDashboardExists'] === true): ?>
            <div class="grafanaContainer" data-id-map="<?php echo $grafanaHostId; ?>">
                <iframe src="<?php echo $widgetData['GrafanaConfiguration']->getIframeUrl(); ?>" width="100%" frameBorder="0" onload="this.height=(screen.height+15);"></iframe>
            </div>
        <?php else: ?>
            <div class="mapContainer" data-id-map="0">
                <center><?php echo __('No host selected or selected host has been deleted'); ?></center>
            </div>
        <?php endif; ?>
    </div>

</div>
