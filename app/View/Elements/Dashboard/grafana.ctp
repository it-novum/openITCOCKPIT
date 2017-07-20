<?php
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
//debug($widgetData);
$grafanaHostId = null;
$widgetId = null;
if (!empty($widgetData['Widget']['Widget']['host_id'])):
    $grafanaHostId = $widgetData['Widget']['Widget']['host_id'];
endif;

if(!empty($widgetData['Widget']['Widget']['id'])){
    $widgetId = $widgetData['Widget']['Widget']['id'];
}
?>
<div class="widget-body map-body">
    <div class="padding-10 <?php if (!is_null($grafanaHostId)) {
        echo 'display-none';
    } ?>">
        <div style="border:1px solid #c3c3c3;" class="padding-10">
            <div class="row">
                <div class="col-xs-12">
                    <?php
                    echo $this->Form->create('dashboard', [
                        'class' => 'clear',
                        'url'   => 'saveGrafanaId',
                        'id'    => 'GrafanaForm-'.$widget['Widget']['id'],
                    ]);
                    ?>
                    <input type="hidden" name="data[dashboard][widgetId]" value="<?php echo $widgetId; ?>">
                    <select class="chosen tachoSelectService"
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
    <div class="grafanaWrapper">
        <?php
        if ($GrafanaDashboardExists): ?>
            <div class="grafanaContainer" data-id-map="<?php echo $grafanaHostId; ?>">
                <iframe src="<?php echo $GrafanaConfiguration->getIframeUrl(); ?>" width="100%"
                        onload="this.height=(screen.height+15);" frameBorder="0"></iframe>

            </div>
        <?php else: ?>
            <div class="mapContainer" data-id-map="0">
                <center><?php echo __('No host selected or selected host has been deleted'); ?></center>
            </div>
        <?php endif; ?>
    </div>
</div>