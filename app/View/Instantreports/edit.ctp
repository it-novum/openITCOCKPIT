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

$evaluationOptions = [];
foreach ($evaluations as $evaluationValue => $evaluationArray){
    $evaluationOptions[$evaluationValue] = '<i class="fa fa-'.$evaluationArray['icon'].'"></i> '.$evaluationArray['label'];
}
?>
<div class="row">
    <div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
        <h1 class="page-title txt-color-blueDark">
            <i class="fa fa-file-image-o fa-fw "></i>
            <?php echo __('Adhoc Reports'); ?>
            <span>>
                <?php echo __('Instant Report'); ?>
			</span>
        </h1>
    </div>
</div>
<div id="error_msg"></div>
<div class="jarviswidget">
    <header>
        <span class="widget-icon"> <i class="fa fa-pencil-square-o"></i> </span>
        <h2><?php echo __('Edit Instant Report'); ?></h2>
        <div class="widget-toolbar" role="menu">
            <?php echo $this->Utils->backButton(); ?>
        </div>
    </header>
    <div>
        <div class="widget-body">
            <?php
            echo $this->Form->create('Instantreport', [
                'class' => 'form-horizontal clear',
            ]);

            echo $this->Form->input('Instantreport.container_id', [
                    'options' => $containers,
                    'class'   => 'chosen',
                    'style'   => 'width: 100%',
                    'label'   => __('Container'),
                ]
            );

            echo $this->Form->input('name', [
                'label'     => ['text' => __('Name')],
                'wrapInput' => 'col col-xs-10 col-md-10 col-lg-10',
            ]);

            echo $this->Form->input('Instantreport.evaluation', [
                'before'  => '<label class="col col-md-2 text-right">'.__('Evaluation').'</label>',
                'type'    => 'radio',
                'options' => $evaluationOptions,
                'class'   => 'padding-right-10',
                'default' => '1',
            ]);

            echo $this->Form->input('Instantreport.type', [
                    'options' => $types,
                    'class'   => 'chosen',
                    'style'   => 'width: 100%',
                    'label'   => __('Type'),
                ]
            );

            echo $this->Form->input('Instantreport.Hostgroup', [
                'div'      => 'form-group checkbox-group multiple-select select-type select-type-'.Instantreport::TYPE_HOSTGROUPS,
                'options'  => Hash::combine($hostgroups, '{n}.Hostgroup.id', '{n}.Container.name'),
                'class'    => 'chosen',
                'multiple' => true,
                'style'    => 'width:100%;',
                'label' => __('<i class="fa fa-desktop"></i> Host groups'),
                'data-placeholder' => __('Please choose a host group'),
                'wrapInput' => ['tag' => 'div', 'class' => 'col col-xs-10']
            ]);

            echo $this->Form->input('Instantreport.Servicegroup', [
                'div'      => 'form-group checkbox-group multiple-select select-type select-type-'.Instantreport::TYPE_SERVICEGROUPS,
                'options'  => Hash::combine($servicegroups, '{n}.Servicegroup.id', '{n}.Container.name'),
                'class'    => 'chosen',
                'multiple' => true,
                'style'    => 'width:100%;',
                'label'    => __('<i class="fa fa-gears"></i> Service groups'),
                'data-placeholder' => __('Please choose a service group'),
                'wrapInput'=> ['tag'   => 'div', 'class' => 'col col-xs-10']
            ]);

            echo $this->Form->input('Instantreport.Host', [
                'div'      => 'form-group checkbox-group multiple-select select-type select-type-'.Instantreport::TYPE_HOSTS,
                'options'  => $hosts,
                'class'    => 'chosen',
                'multiple' => true,
                'style'    => 'width:100%;',
                'label' => __('<i class="fa fa-desktop"></i> Hosts'),
                'data-placeholder' => __('Please choose a host'),
                'wrapInput' => ['tag' => 'div', 'class' => 'col col-xs-10'],
                'itn-ajax' => '/Hosts/ajaxGetByTerm'
            ]);

            echo $this->Form->input('Instantreport.Service', [
                'div'      => 'form-group checkbox-group multiple-select select-type select-type-'.Instantreport::TYPE_SERVICES,
                'options'  => Hash::combine($services, ['%s', '{n}.Service.id'], ['%s/%s', '{n}.Host.name', '{n}.{n}.ServiceDescription'], '{n}.Host.name'),
                'class'    => 'chosen',
                'multiple' => true,
                'style'    => 'width:100%;',
                'label'    => __('<i class="fa fa-gears"></i> Services'),
                'data-placeholder' => __('Please choose a service'),
                'wrapInput'        => ['tag'   => 'div', 'class' => 'col col-xs-10'],
                'itn-ajax' => '/Services/ajaxGetByTerm'
            ]);

            echo $this->Form->input('Instantreport.timeperiod_id', ['options' => $this->Html->chosenPlaceholder($timeperiods), 'data-placeholder' => __('Please select...'), 'class' => 'chosen', 'label' => __('Timeperiod'), 'style' => 'width:100%;']);

            echo $this->Form->input('Instantreport.reflection', [
                'options'          => $reflectionStates,
                'data-placeholder' => __('Please select...'),
                'class'            => 'chosen',
                'label'            => __('Reflection state'),
                'style'            => 'width:100%;',
            ]);
            ?>
            <div class="form-group">
                <?php
                echo $this->Form->fancyCheckbox('Instantreport.downtimes', [
                    'caption'          => __('Consider downtimes'),
                    'wrapGridClass'    => 'col col-md-1',
                    'captionGridClass' => 'col col-md-2',
                    'captionClass'     => 'control-label',
                    'checked'          => isset($this->request->data['Instantreport']['downtimes']) && $this->request->data['Instantreport']['downtimes'] === '1',
                ]);
                ?>
            </div>
            <div class="form-group">
                <?php
                echo $this->Form->fancyCheckbox('Instantreport.summary', [
                    'caption'          => __('Summary display'),
                    'wrapGridClass'    => 'col col-md-1',
                    'captionGridClass' => 'col col-md-2',
                    'captionClass'     => 'control-label',
                    'checked'          => isset($this->request->data['Instantreport']['summary']) && $this->request->data['Instantreport']['summary'] === '1',
                ]);
                ?>
            </div>
            <div class="form-group">
                <?php
                echo $this->Form->fancyCheckbox('Instantreport.send_email', [
                    'caption'          => __('Send email'),
                    'wrapGridClass'    => 'col col-md-1',
                    'captionGridClass' => 'col col-md-2',
                    'captionClass'     => 'control-label',
                    'checked'          => isset($this->request->data['Instantreport']['send_email']) && $this->request->data['Instantreport']['send_email'] === '1',
                ]);
                ?>
            </div>
            <div class="send-interval-holder">
                <?php
                echo $this->Form->input('Instantreport.send_interval', [
                    'div'              => 'form-group',
                    'options'          => $sendIntervals,
                    'data-placeholder' => __('Please select...'),
                    'class'            => 'chosen',
                    'label'            => __('Send interval'),
                    'style'            => 'width:100%;',
                ]);
                echo $this->Form->input('Instantreport.User', [
                    'div'      => 'form-group checkbox-group multiple-select',
                    'options'  => Hash::combine($usersToSend, ['%s', '{n}.User.id'], ['%s %s', '{n}.User.firstname', '{n}.User.lastname']),
                    'class'    => 'chosen',
                    'multiple' => true,
                    'style'    => 'width:100%;',
                    'label'    => __('Users to send'),
                    'data-placeholder' => __('Please choose users'),
                    'wrapInput'        => ['tag'   => 'div', 'class' => 'col col-xs-10']
                ]);
                ?>
            </div>
            <div class="well formactions"><div class="pull-right">
                    <?php
                    echo $this->Form->submit(__('Save'), ['div' => false, 'class' => 'btn btn-primary save-submit-class', 'name' => 'save_submit']).'&nbsp;';
                    echo $this->Html->link(__('Cancel'), ['action' => 'index'], ['class' => 'btn btn-default']);
                    ?>
            </div></div>
        </div>
    </div>
</div>
