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

$widgetData = $widgetMaps[$widget['Widget']['id']];
//debug($widgetData);
$graphId = null;
if (!empty($widgetData['Widget']['Widget']['graph_id'])):
    $graphId = $widgetData['Widget']['Widget']['graph_id'];
endif;
?>
<div class="widget-body graph-body">
    <div class="padding-10">
        <div style="border:1px solid #c3c3c3;" class="padding-10">
            <div class="row">
                <div class="col-xs-12">
                    <select class="chosen graphSelectGraph" data-widget-id="<?php echo $widget['Widget']['id']; ?>"
                            placeholder="<?php echo __('Please select'); ?>" style="width:100%;">
                        <option></option>
                        <?php foreach ($graphListForWidget as $key => $val):
                            $selected = '';
                            if ($graphId !== null && $val['GraphgenTmpl']['id'] == $graphId):
                                $selected = 'selected="selected"';
                            endif;
                            ?>
                            <option value="<?php echo $val['GraphgenTmpl']['id']; ?>" <?php echo $selected; ?>><?php echo h($val['GraphgenTmpl']['name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        </div>
    </div>
    <div class="graphWrapper">
        <?php
        if ($graphId): ?>
            <div class="graphContainer" data-id-graph="<?php echo $graphId; ?>">
                <iframe width="100%" height="401" style="border:0px;" scrolling="no"
                        src="/graphgenerators/view/<?php echo $graphId; ?>"/>
                </iframe>
            </div>
        <?php else: ?>
            <div class="graphContainer" data-id-graph="0">
                <center><?php echo __('No graph selected or selected graph has been deleted'); ?></center>
            </div>
        <?php endif; ?>
    </div>
</div>
