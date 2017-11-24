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
                <?php echo $this->Utils->pluralize($servicesToCopy, __('Service'), __('Services')); ?>
            </span>
            <div class="third_level"> <?php echo ucfirst($this->params['action']); ?></div>
        </h1>
    </div>
</div>
<div id="error_msg"></div>

<div class="jarviswidget" id="wid-id-0">
    <header>
        <span class="widget-icon hidden-mobile hidden-tablet"> <i class="fa fa-pencil-square-o"></i> </span>
        <h2 class="hidden-mobile hidden-tablet">
            <?php printf(
                '%s %s %s',
                __('Copy'),
                $this->Utils->pluralize($servicesToCopy, __('service'), __('services')),
                __('to host')
            ); ?>
        </h2>
        <div class="widget-toolbar hidden-mobile hidden-tablet" role="menu">
            <?php echo $this->Utils->backButton(__('Back'), $back_url); ?>
        </div>
    </header>
    <div>
        <div class="widget-body">
            <?php
            echo $this->Form->create('Service', [
                'class' => 'form-horizontal clear',
            ]);

            echo $this->Form->input('Service.host_id', [
                'options'          => $this->Html->chosenPlaceholder($hosts),
                'data-placeholder' => __('Please select...'),
                'class'            => 'chosen',
                'wrapInput'        => 'col col-xs-7',
                'style'            => 'width: 100%',
                'label'            => [
                    'class' => 'col col-xs-1 control-label',
                    'text'  => __('Host'),
                ],
            ]); ?>
            <div class="row">
                <div class="col-xs-12 col-md-9 col-lg-7">
                    <div class="padding-bottom-10">
                        <strong><?php echo __('Source host'); ?>:</strong> <?php echo h($sourceHost['Host']['name']); ?>
                    </div>
                    <i class="fa fa-files-o"></i> <strong><?php echo __('The following services will copied'); ?>
                        :</strong>
                    <ul>
                        <?php foreach ($servicesToCopy as $key => $serviceToCopy): ?>
                            <li>
                                <?php
                                if ($serviceToCopy['Service']['name'] == null || $serviceToCopy['Service']['name'] == ''):
                                    echo h($serviceToCopy['Servicetemplate']['name']);
                                else:
                                    echo h($serviceToCopy['Service']['name']);
                                endif; ?>
                                <?php echo $this->Form->input('Service.source.' . $key, ['value' => $serviceToCopy['Service']['id'], 'type' => 'hidden']); ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>

                    <?php if (!empty($servicesCantCopy)): ?>
                        <strong class="text-info"><i
                                    class="fa fa-info-circle"></i> <?php echo __('The following Service cannot be copied'); ?>
                        </strong> <i class="text-info"><?php echo __('(Dynamic service discovery)'); ?>:</i>
                        <ul>
                            <?php foreach ($servicesCantCopy as $serviceCantCopy): ?>
                                <li><?php echo h($serviceCantCopy); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>

                </div> <!-- close col -->
            </div> <!-- close row-->
            <br/>
            <?php echo $this->Form->formActions(); ?>
        </div> <!-- close widget body -->
    </div>
</div> <!-- end jarviswidget -->
