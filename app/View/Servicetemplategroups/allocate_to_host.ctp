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
<div class="row">
    <div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
        <h1 class="page-title txt-color-blueDark">
            <i class="fa fa-pencil-square-o fa-fw "></i>
            <?php echo __('Monitoring'); ?>
            <span>>
                <?php echo __('Servicetemplategroup'); ?>
			</span>
            <div class="third_level"> <?php __('Allocate to host'); ?></div>
        </h1>
    </div>
</div>
<div id="error_msg"></div>

<div class="jarviswidget" id="wid-id-0">
    <header>
        <span class="widget-icon hidden-mobile hidden-tablet"> <i class="fa fa-pencil-square-o"></i> </span>
        <h2 class="hidden-mobile hidden-tablet"><?php echo __('Allocate servicetemplategroup'); ?>
            <strong><?php echo $servicetemplategroup['Container']['name']; ?></strong> <?php echo __('to host'); ?></h2>
        <div class="widget-toolbar hidden-mobile hidden-tablet" role="menu">
            <?php echo $this->Utils->backButton(__('Back'), $back_url); ?>
        </div>
    </header>
    <div>
        <div class="widget-body">
            <?php
            echo $this->Form->create('Service', [
                'class' => 'form-horizontal clear',
            ]); ?>
            <div class="row">
                <div class="col-xs-12 col-md-8">
                    <?php echo $this->Form->input('Service.host_id', [
                        'options'          => $this->Html->chosenPlaceholder($hosts),
                        'data-placeholder' => __('Please select...'),
                        'class'            => 'chosen',
                        'wrapInput'        => 'col col-xs-12 col-md-8',
                        'style'            => 'width: 100%',
                        'label'            => [
                            'class' => 'col col-xs-1 control-label',
                            'text'  => __('Host'),
                        ],
                    ]); ?>

                    <fieldset>
                        <legend><?php echo __('Services out of servicetemplategroup:'); ?></legend>
                        <?php foreach (Hash::sort($servicetemplategroup['Servicetemplate'], '{n}.name', 'asc') as $servicetemplate): ?>
                            <div class="padding-left-10 padding-bottom-5">
                                <input type="checkbox" class="createThisService"
                                       id="servicetemplate_<?php echo $servicetemplate['id']; ?>"
                                       value="<?php echo $servicetemplate['id']; ?>"
                                       name="data[Service][ServicesToAdd][]"/>
                                <label for="servicetemplate_<?php echo $servicetemplate['id']; ?>"><?php echo $servicetemplate['name']; ?>
                                    <i class="text-info">(<?php echo $servicetemplate['description']; ?>)</i></label>
                                <a style="display: none;" class="createServiceDuplicate"
                                   id="duplicate_<?php echo $servicetemplate['id']; ?>" href="javascript:void(0);"
                                   data-original-title="<?php echo __('Service already exist on selected host. Tick the box to create duplicate.'); ?>"
                                   data-placement="right" rel="tooltip" data-container="body"><i
                                            class="padding-left-5 fa fa-info-circle text-info"></i></a>
                                <a style="display: none;" class="txt-color-blueDark createServiceDuplicateDisabled"
                                   id="duplicateDisabled_<?php echo $servicetemplate['id']; ?>"
                                   href="javascript:void(0);"
                                   data-original-title="<?php echo __('Service already exist on selected host but is disabled. Tick the box to create duplicate.'); ?>"
                                   data-placement="right" rel="tooltip" data-container="body"><i
                                            class="padding-left-5 fa fa-plug"></i></a>
                            </div>
                        <?php endforeach; ?>
                    </fieldset>
                </div>
            </div>
            <br/>
            <?php echo $this->Form->formActions(); ?>
        </div>
    </div>
</div>
