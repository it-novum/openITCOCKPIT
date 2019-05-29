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
            <i class="fa fa-magic fa-fw "></i>
            <?php echo __('Monitoring'); ?>
            <span>>
                <?php echo __('Automaps'); ?>
			</span>
            <div class="third_level"> <?php echo ucfirst($this->params['action']); ?></div>
        </h1>
    </div>
</div>


<div class="jarviswidget" id="wid-id-0">
    <header>
        <span class="widget-icon"> <i class="fa fa-terminal"></i> </span>
        <h2><?php echo __('Add new automap'); ?></h2>
        <div class="widget-toolbar" role="menu">
            <?php echo $this->Utils->backButton(); ?>
        </div>
    </header>
    <div>
        <div class="widget-body">
            <div class="row">
                <div class="col-xs-12 col-md-12 col-lg-12">
                    <?php
                    echo $this->Form->create('Automap', [
                        'class' => 'form-horizontal clear',
                    ]);
                    echo $this->Form->input('container_id', [
                        'options'   => $containers,
                        'class'     => 'chosen',
                        'style'     => 'width: 100%;',
                        'label'     => ['text' => __('Container'), 'class' => 'col-xs-1 col-md-1 col-lg-1'],
                        'wrapInput' => 'col col-xs-10 col-md-10 col-lg-10',
                    ]);
                    ?>
                    <div class="form-group padding-left-20">
                        <?php echo $this->Form->fancyCheckbox('recursive', [
                            'caption'          => __('Recursive container lookup'),
                            'wrapGridClass'    => 'col col-xs-2',
                            'captionGridClass' => 'col col-xs-2 no-padding',
                            'captionClass'     => 'control-label text-left no-padding',
                            'icon'             => '<i class="fa fa-sitemap"></i> ',
                            'checked'          => (boolean)$this->Html->getParameter('Automap.recursive', false),
                        ]); ?>
                    </div>
                    <?php
                    echo $this->Form->input('name', [
                        'label'     => ['text' => __('Name'), 'class' => 'col-xs-1 col-md-1 col-lg-1'],
                        'wrapInput' => 'col col-xs-10 col-md-10 col-lg-10',
                    ]);
                    echo $this->Form->input('description', [
                        'label'     => ['text' => __('Description'), 'class' => 'col-xs-1 col-md-1 col-lg-1'],
                        'wrapInput' => 'col col-xs-10 col-md-10 col-lg-10',
                    ]);

                    echo $this->Form->input('host_regex', [
                        'label'     => ['text' => __('Host RegEx'), 'class' => 'col-xs-1 col-md-1 col-lg-1'],
                        'wrapInput' => 'col col-xs-10 col-md-10 col-lg-10',
                    ]);
                    echo $this->Form->input('service_regex', [
                        'label'     => ['text' => __('Service RegEx'), 'class' => 'col-xs-1 col-md-1 col-lg-1'],
                        'wrapInput' => 'col col-xs-10 col-md-10 col-lg-10',
                    ]);
                    ?>

                    <?php $errorClass = $this->CustomValidationErrors->errorClass('show_ok'); ?>
                    <div class="form-group padding-left-20 <?php echo $errorClass; ?>">
                        <?php echo $this->Form->fancyCheckbox('show_ok', [
                            'caption'          => __('Show Ok'),
                            'wrapGridClass'    => 'col col-xs-2',
                            'captionGridClass' => 'col col-xs-2 no-padding',
                            'captionClass'     => 'control-label text-left no-padding ',
                            'icon'             => '<i class="fa fa-square ok"></i> ',
                            'required'         => false,
                            'checked'          => (boolean)$this->Html->getParameter('Automap.show_ok', true),
                        ]); ?>
                    </div>
                    <div class="form-group padding-left-20 <?php echo $errorClass; ?>">
                        <?php echo $this->Form->fancyCheckbox('show_warning', [
                            'caption'          => __('Show Warning'),
                            'wrapGridClass'    => 'col col-xs-2',
                            'captionGridClass' => 'col col-xs-2 no-padding',
                            'captionClass'     => 'control-label text-left no-padding',
                            'icon'             => '<i class="fa fa-square warning"></i> ',
                            'checked'          => (boolean)$this->Html->getParameter('Automap.show_warning', true),
                        ]); ?>
                    </div>
                    <div class="form-group padding-left-20 <?php echo $errorClass; ?>">
                        <?php echo $this->Form->fancyCheckbox('show_critical', [
                            'caption'          => __('Show Critical'),
                            'wrapGridClass'    => 'col col-xs-2',
                            'captionGridClass' => 'col col-xs-2 no-padding',
                            'captionClass'     => 'control-label text-left no-padding',
                            'icon'             => '<i class="fa fa-square critical"></i> ',
                            'checked'          => (boolean)$this->Html->getParameter('Automap.show_critical', true),
                        ]); ?>
                    </div>
                    <div class="form-group padding-left-20 <?php echo $errorClass; ?>">
                        <?php echo $this->Form->fancyCheckbox('show_unknown', [
                            'caption'          => __('Show Unknown'),
                            'wrapGridClass'    => 'col col-xs-2',
                            'captionGridClass' => 'col col-xs-2 no-padding',
                            'captionClass'     => 'control-label text-left no-padding',
                            'icon'             => '<i class="fa fa-square unknown"></i> ',
                            'checked'          => (boolean)$this->Html->getParameter('Automap.show_unknown', true),
                        ]); ?>
                    </div>
                    <div class="form-group padding-left-20 <?php echo $errorClass; ?>">
                        <?php echo $this->Form->fancyCheckbox('show_downtime', [
                            'caption'          => __('Show Downtime'),
                            'wrapGridClass'    => 'col col-xs-2',
                            'captionGridClass' => 'col col-xs-2 no-padding',
                            'captionClass'     => 'control-label text-left no-padding',
                            'icon'             => '<i class="fa fa-power-off"></i> ',
                            'checked'          => (boolean)$this->Html->getParameter('Automap.show_downtime', true),
                        ]); ?>
                    </div>
                    <div class="form-group padding-left-20 <?php echo $errorClass; ?>">
                        <?php echo $this->Form->fancyCheckbox('show_acknowledged', [
                            'caption'          => __('Show Acknowledged'),
                            'wrapGridClass'    => 'col col-xs-2',
                            'captionGridClass' => 'col col-xs-2 no-padding',
                            'captionClass'     => 'control-label text-left no-padding',
                            'icon'             => '<i class="fa fa-user"></i> ',
                            'checked'          => (boolean)$this->Html->getParameter('Automap.show_acknowledged', true),
                        ]); ?>
                    </div>
                    <div class="<?php echo $errorClass; ?>">
                        <div class="col col-xs-2"><!-- spacer --></div>
                        <div class="col col-xs-10">
                            <?php echo $this->CustomValidationErrors->errorHTML('show_ok', ['style' => 'margin-left: 15px;']); ?>
                        </div>
                    </div>
                    <div class="form-group padding-left-20">
                        <?php echo $this->Form->fancyCheckbox('show_label', [
                            'caption'          => __('Show Label'),
                            'wrapGridClass'    => 'col col-xs-2',
                            'captionGridClass' => 'col col-xs-2 no-padding',
                            'captionClass'     => 'control-label text-left no-padding',
                            'icon'             => '<i class="fa fa-tag"></i> ',
                            'checked'          => (boolean)$this->Html->getParameter('Automap.show_label', false),
                        ]); ?>
                    </div>
                    <div class="form-group padding-left-20">
                        <?php echo $this->Form->fancyCheckbox('group_by_host', [
                            'caption'          => __('Group by host'),
                            'wrapGridClass'    => 'col col-xs-2',
                            'captionGridClass' => 'col col-xs-2 no-padding',
                            'captionClass'     => 'control-label text-left no-padding',
                            'icon'             => '<i class="fa fa-sitemap"></i> ',
                            'checked'          => (boolean)$this->Html->getParameter('Automap.group_by_host', false),
                        ]); ?>
                    </div>

                    <div class="form-group form-group-slider">
                        <label class="col col-md-1 control-label text-left"
                               for="AutomapFontSize"><?php echo __('Icon size'); ?></label>
                        <div class="col col-md-2">
                            <input
                                    type="text"
                                    id="AutomapFontSize"
                                    maxlength="1"
                                    name="data[Automap][font_size]"
                                    value="normal"
                                    class="form-control slider slider-success"
                                    data-slider-min="1"
                                    data-slider-max="7"
                                    data-slider-value="4"
                                    data-slider-selection="before"
                                    data-slider-step="1">

                            <div class="text-center" id="fontExample" style="font-size:medium;">
                                <?php if ((boolean)$this->Html->getParameter('Automap.show_label', false) === true): ?>
                                    <span><i class="fa fa-square txt-color-greenLight"></i> <?php echo __('Hostname/Servicedescription'); ?></span>
                                <?php else: ?>
                                    <span><i class="fa fa-square txt-color-greenLight"></i></span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <?php
            //echo $this->Form->input('font_size', []);
            ?>

            <?php echo $this->Form->formActions(); ?>
        </div>
    </div>
</div>
