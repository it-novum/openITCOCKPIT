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

require_once APP.'Vendor'.DS.'parsedown'.DS.'Parsedown.php';
require_once APP.'Vendor'.DS.'parsedown'.DS.'ParsedownExtra.php';

$widgetData = $widgetNotices[$widget['Widget']['id']];

$widgetNoticeId = null;
$note = null;
if (isset($widgetData['Widget']['WidgetNotice']['id']) && $widgetData['Widget']['WidgetNotice']['id'] !== null && is_numeric($widgetData['Widget']['WidgetNotice']['id'])):
    $widgetNoticeId = $widgetData['Widget']['WidgetNotice']['id'];
    $note = htmlspecialchars_decode($widgetData["Widget"]["WidgetNotice"]["note"]);
    $parsedown = new ParsedownExtra();
    $note = $parsedown->text($note);
endif;
?>
<div class="widget-body notice-body" style="padding:0;">
    <div class="panel-group smart-accordion-default" id="accordion-<?php echo $widget['Widget']['id']; ?>">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion-<?php echo $widget['Widget']['id']; ?>"
                       href="#collapseOne-1-<?php echo $widget['Widget']['id']; ?>" aria-expanded="false"
                       class="collapsed">
                        <i class="fa fa-lg fa-angle-down pull-right"></i> <i
                                class="fa fa-lg fa-angle-up pull-right"></i> <?php echo __('Edit Notice'); ?>
                    </a>
                </h4>
            </div>
            <div id="collapseOne-1-<?php echo $widget['Widget']['id']; ?>"
                 class="panel-collapse collapse <?php echo ($note === null) ? 'in' : ''; ?>" aria-expanded="true">
                <div class="panel-body">
                    <?php
                    echo $this->Form->create('dashboard', [
                        'class' => 'clear',
                        'url'   => 'saveNotice',
                        'id'    => 'Notice-'.$widget['Widget']['id'],
                    ]); ?>
                    <?php
                    echo $this->Form->input('noticeText', [
                        'class'     => 'form-control notice-text',
                        'type'      => 'textarea',
                        'maxlength' => '4000',
                        'value'     => $note,
                    ]);
                    echo $this->Form->input('tabId', [
                        'type'  => 'hidden',
                        'value' => $widget['Widget']['dashboard_tab_id'],
                        'form'  => 'Notice-'.$widget['Widget']['id'],
                    ]);
                    echo $this->Form->input('widgetId', [
                        'type'  => 'hidden',
                        'value' => $widget['Widget']['id'],
                        'form'  => 'Notice-'.$widget['Widget']['id'],
                    ]);
                    if ($widgetNoticeId !== null):
                        echo $this->Form->input('WidgetNoticeId', [
                            'type'  => 'hidden',
                            'value' => $widgetNoticeId,
                            'form'  => 'Notice-'.$widget['Widget']['id'],
                        ]);
                    endif;
                    ?>
                    <div class="col-xs-12">
                        <div class="pull-right padding-top-10">
                            <?php
                            echo $this->Form->submit(__('Save'), [
                                'class' => [
                                    'btn btn-primary',
                                ],
                                'form'  => 'Notice-'.$widget['Widget']['id'],
                                'div'   => false,
                                'value' => 1,
                            ]); ?>
                            <?php echo $this->Form->end(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion-<?php echo $widget['Widget']['id']; ?>"
                       href="#collapseTwo-1-<?php echo $widget['Widget']['id']; ?>" aria-expanded="true" class="">
                        <i class="fa fa-lg fa-angle-down pull-right"></i> <i
                                class="fa fa-lg fa-angle-up pull-right"></i> <?php echo h('Notice'); ?>
                    </a>
                </h4>
            </div>
            <div id="collapseTwo-1-<?php echo $widget['Widget']['id']; ?>"
                 class="panel-collapse collapse <?php echo ($note === null) ? '' : 'in'; ?>" aria-expanded="true">
                <div class="panel-body" style="padding:10px 15px;">
                    <?php echo $note; ?>
                </div>
            </div>
        </div>
    </div>
</div>
