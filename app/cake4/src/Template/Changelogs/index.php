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
?>
<?php $this->Paginator->options(['url' => $this->params['named']]); ?>
    <div class="row">
        <div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
            <h1 class="page-title txt-color-blueDark">
                <i class="fa fa-code-fork fa-fw "></i>
                <?php echo __('System'); ?>
                <span>>
                    <?php echo __('Changelog'); ?>
            </span>
            </h1>
        </div>
    </div>
    <!-- widget grid -->
    <section id="widget-grid" class="">
        <!-- row -->
        <div class="row">
            <!-- NEW WIDGET START -->
            <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <!-- Widget ID (each widget will need unique ID)-->
                <div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-1" data-widget-editbutton="false">
                    <header>
                        <div class="widget-toolbar" role="menu">
                            <?php echo $this->Html->link(__('Filter'), 'javascript:', ['class' => 'oitc-list-filter btn btn-xs btn-primary toggle', 'hide-on-render' => 'true', 'icon' => 'fa fa-filter']); ?>
                            <?php
                            if ($isFilter):
                                echo $this->ListFilter->resetLink(null, ['class' => 'btn-danger btn-xs', 'icon' => 'fa fa-times']);
                            endif;
                            ?>
                        </div>
                        <span class="widget-icon"> <i class="fa fa-fw"></i> </span>
                        <h2><?php echo __('Changelog'); ?></h2>
                    </header>

                    <!-- widget div-->
                    <div>
                        <?php echo $this->ListFilter->renderFilterbox($filters, [], '<i class="fa fa-filter"></i> ' . __('Filter'), false, false); ?>
                        <!-- widget edit box -->
                        <div class="jarviswidget-editbox">
                            <!-- This area used as dropdown edit box -->
                        </div>
                        <!-- end widget edit box -->
                        <!-- widget content -->
                        <div class="widget-body no-padding">
                            <div class="well well-sm">
                                <div class="smart-timeline">
                                    <ul class="smart-timeline-list">
                                        <?php $niceTime = true; ?>
                                        <?php
                                        $replacement_keys_for_objects = [
                                            'Command'    => [
                                                'command_type' => [
                                                    1 => 'CHECK_COMMAND',
                                                    2 => 'HOSTCHECK_COMMAND',
                                                    3 => 'NOTIFICATION_COMMAND',
                                                    4 => 'EVENTHANDLER_COMMAND',
                                                ],
                                            ],
                                            'Timeperiod' => [
                                                'day' => [
                                                    1 => __('Monday'),
                                                    2 => __('Tuesday'),
                                                    3 => __('Wednesday'),
                                                    4 => __('Thursday'),
                                                    5 => __('Friday'),
                                                    6 => __('Saturday'),
                                                    7 => __('Sunday'),
                                                ],
                                            ],
                                            'Timeperiod.timeperiod_timeranges'  => [
                                                'day' => [
                                                    1 => __('Monday'),
                                                    2 => __('Tuesday'),
                                                    3 => __('Wednesday'),
                                                    4 => __('Thursday'),
                                                    5 => __('Friday'),
                                                    6 => __('Saturday'),
                                                    7 => __('Sunday'),
                                                ],
                                            ],
                                        ];

                                        $hide_field_names = ['HostCommands', 'HostTimeperiod', 'ServiceCommands', 'ServiceTimeperiod', 'Host'];
                                        $link_ids = [];
                                        foreach ($all_changes as $change):?>
                                            <?php
                                            if (!isset($link_ids[$change['Changelog']['model']])):
                                                $link_ids[$change['Changelog']['model']] = [];
                                            endif;
                                            if (!array_key_exists($change['Changelog']['object_id'], $link_ids[$change['Changelog']['model']])):
                                                $link_ids[$change['Changelog']['model']][$change['Changelog']['object_id']] = recordExists($change['Changelog']['model'], $change['Changelog']['object_id']);
                                            endif;
                                            ?>
                                            <?php $data = unserialize($change['Changelog']['data']); ?>
                                            <li>
                                                <div class="smart-timeline-icon bg-color-<?php echo h($this->Changelog->getActionColors($change['Changelog']['action'])); ?>">
                                                    <i class="fa <?php echo h($this->Changelog->getActionIcon($change['Changelog']['action'])); ?>"></i>
                                                </div>
                                                <div class="smart-timeline-time">
                                                    <?php
                                                    if ($niceTime === true && (time() - strtotime($change['Changelog']['created'])) <= 8100):
                                                        $time = $this->Time->timeAgoInWords($change['Changelog']['created'], ['timezone' => $this->Auth->user('timezone')]);
                                                    else:
                                                        //let us save some CPU power
                                                        $niceTime = false;
                                                        $time = $change['Changelog']['created'];
                                                    endif;
                                                    ?>
                                                    <small><?php echo h($time); ?></small>
                                                </div>
                                                <div class="smart-timeline-content">
                                                    <p>
                                            <span><?php echo h(__($change['Changelog']['model'])) ?>:
                                                <strong> <?php
                                                    if (array_key_exists($change['Changelog']['object_id'], $link_ids[$change['Changelog']['model']]) && $link_ids[$change['Changelog']['model']][$change['Changelog']['object_id']]):
                                                        echo $this->Html->link(h($change['Changelog']['name']), [
                                                            'controller' => $link_ids[$change['Changelog']['model']][$change['Changelog']['object_id']],
                                                            'action'     => 'edit',
                                                            $change['Changelog']['object_id'],
                                                        ],
                                                            ['class' => 'light_blue', 'escape' => false]);

                                                    else:?>
                                                        <span class="changelog_delete">
                                                    <?php
                                                    echo h($change['Changelog']['name']); ?>
                                                    </span>
                                                    <?php
                                                    endif;
                                                    ?>
                                                </strong>
                                                <?php if ($showUser): ?>
                                                    <?php if ($change['User']['lastname'] !== null && $change['User']['firstname'] !== null && $change['User']['id'] !== null): ?>
                                                        <?php echo __('by'); ?>
                                                        <a href="/users/edit/<?php echo h($change['User']['id']); ?>"><?php echo h($change['User']['firstname']) . ' ' . h($change['User']['lastname']); ?></a>
                                                    <?php
                                                    else:
                                                        echo $change['Changelog']['user_id'] === '0' ? __('with Cron Job') : __('by deleted user');
                                                    endif;
                                                endif; ?>
                                                </span>

                                                    </p>
                                                    <?php
                                                    if (!empty($data)):?>
                                                        <blockquote>
                                                            <?php
                                                            foreach ($data as $values_arr):
                                                                foreach ($values_arr as $key => $value):?>
                                                                    <?php
                                                                    if (isset($value['current_data'])):
                                                                        $show_identifier = (in_array($key, $hide_field_names) && !preg_match('/^' . $key . '$/i', $change['Changelog']['model'])) ? false : true;
                                                                        ?>
                                                                        <p class="font-xs padding-top-10"><?php echo h($key); ?></p>
                                                                        <?php
                                                                        //if old data has been deleted => dimensions $value['current_data'] = 0
                                                                        $current_data_dimension_counter = Hash::dimensions($value['current_data']);
                                                                        $after_data_dimension_counter = (isset($value['after'])) ? Hash::dimensions($value['after']) : 0;
                                                                        if ($current_data_dimension_counter === 0):
                                                                            if (empty($value['before']) && !empty($value['after'])):
                                                                                if ($after_data_dimension_counter === 1):
                                                                                    foreach (array_keys($value['after']) as $field_name):
                                                                                        if ($field_name !== 'id'):?>
                                                                                            <small><?php echo h(($show_identifier) ? $field_name . ':' : ''); ?>
                                                                                                <span class="txt-color-blue">
                                                                                        <?php
                                                                                        echo h((isset($replacement_keys_for_objects[$change['Changelog']['model']]) && array_key_exists($field_name, $replacement_keys_for_objects[$change['Changelog']['model']])) ? $replacement_keys_for_objects[$change['Changelog']['model']][$field_name][$value['after'][$field_name]] : $value['after'][$field_name]); ?>
                                                                                    </span>
                                                                                            </small>
                                                                                        <?php
                                                                                        endif;
                                                                                    endforeach;
                                                                                elseif ($after_data_dimension_counter === 2):
                                                                                    foreach ($value['after'] as $counter => $after_data_value):
                                                                                        foreach (array_keys($after_data_value) as $field_identifier):
                                                                                            if ($field_identifier !== 'id'):?>
                                                                                                <small><?php echo h(($show_identifier) ? $field_identifier . ':' : ''); ?>
                                                                                                    <span class="txt-color-blue"><?php
                                                                                                        echo h((isset($replacement_keys_for_objects[$change['Changelog']['model']]) && array_key_exists($field_identifier, $replacement_keys_for_objects[$change['Changelog']['model']])) ? $replacement_keys_for_objects[$change['Changelog']['model']][$field_identifier][$after_data_value[$field_identifier]] : $after_data_value[$field_identifier]);
                                                                                                        ?>
                                                                                    </span>
                                                                                                </small>
                                                                                            <?php
                                                                                            endif;
                                                                                        endforeach;
                                                                                    endforeach;
                                                                                endif;
                                                                            endif;
                                                                        elseif ($current_data_dimension_counter === 1):
                                                                            foreach (array_keys($value['current_data']) as $field_name):
                                                                                if (isset($value['before']) && !array_key_exists($field_name, $value['before'])):
                                                                                    continue;
                                                                                endif;
                                                                                if ($field_name !== 'id'):?>
                                                                                    <small><?php echo h(($show_identifier) ? $field_name . ':' : ''); ?>
                                                                                        <?php
                                                                                        switch ($change['Changelog']['action']):
                                                                                            case 'edit':
                                                                                                ?>
                                                                                                <span class="txt-color-red">
                                                                                    <?php
                                                                                    echo h((isset($replacement_keys_for_objects[$change['Changelog']['model']]) && array_key_exists($field_name, $replacement_keys_for_objects[$change['Changelog']['model']])) ? $replacement_keys_for_objects[$change['Changelog']['model']][$field_name][$value['before'][$field_name]] : $value['before'][$field_name]); ?>
                                                                                </span>
                                                                                                <i class="fa fa-caret-right"></i>
                                                                                                <span class="txt-color-green">
                                                                                    <?php
                                                                                    echo h((isset($replacement_keys_for_objects[$change['Changelog']['model']]) && array_key_exists($field_name, $replacement_keys_for_objects[$change['Changelog']['model']])) ? $replacement_keys_for_objects[$change['Changelog']['model']][$field_name][$value['after'][$field_name]] : (!empty($value['after'][$field_name]))?$value['after'][$field_name]:''); ?>
                                                                                </span>
                                                                                                <?php
                                                                                                break;
                                                                                            case 'add':
                                                                                            case 'copy':
                                                                                                ?>
                                                                                                <span class="txt-color-blue">
                                                                                    <?php
                                                                                    echo h((isset($replacement_keys_for_objects[$change['Changelog']['model']]) && array_key_exists($field_name, $replacement_keys_for_objects[$change['Changelog']['model']]) ? $replacement_keys_for_objects[$change['Changelog']['model']][$field_name][$value['current_data'][$field_name]] : $value['current_data'][$field_name])); ?>
                                                                                    </span>
                                                                                                <?php
                                                                                                break;
                                                                                        endswitch;
                                                                                        ?>
                                                                                    </small>
                                                                                <?php
                                                                                endif;
                                                                            endforeach;
                                                                        elseif ($current_data_dimension_counter === 2):
                                                                            foreach ($value['current_data'] as $counter => $current_data_value):
                                                                                $changelog_min_action = 'changelog_new';
                                                                                $ids_after_save = (isset($value['after'])) ? Hash::extract($value['after'], '{n}.id') : [];
                                                                                $ids_before_save = (isset($value['before'])) ? Hash::extract($value['before'], '{n}.id') : [];
                                                                                $isCopied = !isset($value['before'], $value['after']);
                                                                                if (isset($current_data_value['id'])):
                                                                                    if (!in_array($current_data_value['id'], $ids_before_save) && !in_array($current_data_value['id'], $ids_after_save) && ($change['Changelog']['action'] != 'add' && $change['Changelog']['action'] != 'copy')):
                                                                                        continue;
                                                                                    endif;
                                                                                    if (in_array($current_data_value['id'], $ids_before_save) && !in_array($current_data_value['id'], $ids_after_save)):
                                                                                        $changelog_min_action = 'changelog_delete';
                                                                                    elseif (in_array($current_data_value['id'], $ids_before_save) && in_array($current_data_value['id'], $ids_after_save)):
                                                                                        $changelog_min_action = 'changelog_edit';
                                                                                    endif;

                                                                                elseif (!isset($current_data_value['id']) && !$isCopied):
                                                                                    continue;
                                                                                endif;
                                                                                foreach (array_keys($current_data_value) as $field_identifier):
                                                                                    if ($field_identifier !== 'id'):?>
                                                                                        <small><?php echo h(($show_identifier) ? $field_identifier . ':' : ''); ?>
                                                                                            <?php
                                                                                            switch ($changelog_min_action):
                                                                                                case 'changelog_delete':
                                                                                                    ?>
                                                                                                    <span class="txt-color-blueDark <?php
                                                                                                    echo h($changelog_min_action); ?>"><?php
                                                                                                        echo h((isset($replacement_keys_for_objects[$change['Changelog']['model']]) && array_key_exists($field_identifier, $replacement_keys_for_objects[$change['Changelog']['model']])) ? $replacement_keys_for_objects[$change['Changelog']['model']][$field_identifier][$current_data_value[$field_identifier]] : $current_data_value[$field_identifier]);
                                                                                                        ?></span>
                                                                                                    <?php
                                                                                                    break;
                                                                                                case 'changelog_edit':
                                                                                                    ?>
                                                                                                    <?php
                                                                                                    $value_after_edit = (isset(Hash::extract($value['after'], '{n}[id=' . $current_data_value['id'] . '].' . $field_identifier)[0])) ? Hash::extract($value['after'], '{n}[id=' . $current_data_value['id'] . '].' . $field_identifier)[0] : '';
                                                                                                    ?>
                                                                                                    <span class="txt-color-red"><?php
                                                                                                        echo h((isset($replacement_keys_for_objects[$change['Changelog']['model']]) && array_key_exists($field_identifier, $replacement_keys_for_objects[$change['Changelog']['model']])) ? $replacement_keys_for_objects[$change['Changelog']['model']][$field_identifier][$current_data_value[$field_identifier]] : $current_data_value[$field_identifier]);
                                                                                                        ?>
                                                                                        </span>
                                                                                                    <i class="fa fa-caret-right"></i>
                                                                                                    <span class="txt-color-green">
                                                                                            <?php
                                                                                            echo h((isset($replacement_keys_for_objects[$change['Changelog']['model']]) && array_key_exists($field_identifier, $replacement_keys_for_objects[$change['Changelog']['model']])) ? $replacement_keys_for_objects[$change['Changelog']['model']][$field_identifier][$value_after_edit] : $value_after_edit);
                                                                                            ?>
                                                                                        </span>
                                                                                                    <?php
                                                                                                    break;
                                                                                                case 'changelog_new':
                                                                                                    ?>
                                                                                                    <span class="txt-color-blue"><?php
                                                                                                        echo h((isset($replacement_keys_for_objects[$change['Changelog']['model']]) && array_key_exists($field_identifier, $replacement_keys_for_objects[$change['Changelog']['model']])) ? $replacement_keys_for_objects[$change['Changelog']['model']][$field_identifier][$current_data_value[$field_identifier]] : $current_data_value[$field_identifier]);
                                                                                                        ?>
                                                                                        </span>
                                                                                                    <?php
                                                                                                    break;
                                                                                            endswitch;
                                                                                            ?>
                                                                                        </small>
                                                                                    <?php
                                                                                    endif;
                                                                                endforeach;
                                                                            endforeach;
                                                                            if (isset($value['after']) && !empty($value['after'])):
                                                                                foreach ($value['after'] as $saved_value):
                                                                                    if (array_key_exists('id', $saved_value) && in_array($saved_value['id'], $ids_before_save) && !empty($value['current_data'])):
                                                                                        //if new value
                                                                                        continue;
                                                                                    endif;
                                                                                    foreach (array_keys($saved_value) as $field_identifier):
                                                                                        if ($field_identifier == 'id'):
                                                                                            continue;
                                                                                        endif;
                                                                                        ?>
                                                                                        <small><?php echo h(($show_identifier) ? $field_identifier . ':' : ''); ?>
                                                                                            <span class="txt-color-blue">
                                                                                    <?php
                                                                                    echo h((isset($replacement_keys_for_objects[$change['Changelog']['model']]) && array_key_exists($field_identifier, $replacement_keys_for_objects[$change['Changelog']['model']])) ? $replacement_keys_for_objects[$change['Changelog']['model']][$field_identifier][$saved_value[$field_identifier]] : $saved_value[$field_identifier]);
                                                                                    ?>
                                                                                </span>
                                                                                        </small>
                                                                                    <?php
                                                                                    endforeach;
                                                                                endforeach;
                                                                            endif;
                                                                        endif;
                                                                    endif;
                                                                endforeach;
                                                            endforeach;
                                                            ?>
                                                        </blockquote>
                                                    <?php
                                                    endif;
                                                    ?>
                                                </div>
                                            </li>
                                        <?php
                                        endforeach; ?>
                                    </ul>
                                </div>
                            </div>
                            <!-- end widget content -->
                        </div>
                    </div>
                    <!-- end widget div -->
                    <div style="padding: 5px 10px;">
                        <div class="row">
                            <div class="col-sm-12 text-right">
                                <div class="dataTables_paginate paging_bootstrap">
                                    <?php echo $this->Paginator->pagination([
                                        'ul' => 'pagination',
                                    ]); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end widget -->
        </div>
        <!-- end row -->
    </section>
<?php
function recordExists($model_name, $id) {
    $excluded_models = ['Timeperiod.timeperiod_timeranges', 'Hosttemplatecommandargumentvalue', 'Servicetemplatecommandargumentvalue'];
    if (in_array($model_name, $excluded_models)) {
        return false;
    }
    $replacement_for_modelname = [
        'HostCommands'    => 'Command',
        'ServiceCommands' => 'Command',
        'CheckCommand'    => 'Command',
        'CheckPeriod'     => 'Timeperiod',
        'NotifyPeriod'    => 'Timeperiod',
        'Parenthost'      => 'Host',
    ];
    if (array_key_exists($model_name, $replacement_for_modelname)) {
        $model_name = $replacement_for_modelname[$model_name];
    }
    $model = ClassRegistry::init($model_name);

    return ($model->exists($id)) ? Inflector::pluralize(strtolower($model_name)) : false;
}
