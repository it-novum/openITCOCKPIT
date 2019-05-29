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
                <?php echo __('Edit host details'); ?>
            </span>
        </h1>
    </div>
</div>


<div class="jarviswidget" id="wid-id-0">
    <header>
        <span class="widget-icon hidden-mobile hidden-tablet"> <i class="fa fa-pencil-square-o"></i> </span>
        <h2 class="hidden-mobile hidden-tablet"><?php echo __('Edit host details'); ?></h2>
        <div class="widget-toolbar hidden-mobile hidden-tablet" role="menu">
            <?php echo $this->Utils->backButton(__('Back'), $back_url); ?>
        </div>
    </header>
    <div>
        <div class="widget-body">
            <?php
            echo $this->Form->create('Host', [
                'class' => 'form-horizontal clear',
            ]); ?>
            <div class="row">
                <div class="col-xs-12 col-md-9 col-lg-7">
                    <div class="padding-left-10">
                        <div class="editHostDetailFormInput">
                            <?php
                            echo $this->Form->input('edit_sharing', [
                                    'type'      => 'checkbox',
                                    'checked'   => false,
                                    'label'     => [
                                        'class' => 'text-primary',
                                        'text'  => __('Edit Sharing')
                                    ],
                                    'wrapInput' => false,
                                    'class'     => 'parent_checkbox'
                                ]
                            );
                            ?>
                            <div class="scope">
                                <?php

                                if ($this->Acl->hasPermission('sharing')) {
                                    echo $this->Form->input('Host.shared_container', [
                                            'options'   => $this->Html->chosenPlaceholder($sharingContainers),
                                            'multiple'  => true,
                                            //'selected'  => $sharedContainers,
                                            'class'     => 'chosen',
                                            'style'     => 'width: 100%',
                                            'label'     => __('Shared containers'),
                                            'wrapInput' => 'col col-xs-8',
                                            'disabled'  => true
                                        ]
                                    );
                                }

                                echo $this->Form->input('keep_sharing', [
                                        'type'     => 'checkbox',
                                        'checked'  => false,
                                        'label'    => __('Keep existing'),
                                        'disabled' => true
                                    ]
                                );
                                ?>
                            </div>
                        </div>
                        <hr/>
                        <div class="editHostDetailFormInput">
                            <?php echo $this->Form->input('edit_description', [
                                    'type'      => 'checkbox',
                                    'checked'   => false,
                                    'label'     => [
                                        'class' => 'text-primary',
                                        'text'  => __('Edit description')
                                    ],
                                    'wrapInput' => false,
                                    'class'     => 'parent_checkbox'
                                ]
                            );
                            ?>
                            <div class="scope">
                                <?php echo $this->Form->input('Host.description', ['type' => 'text', 'label' => __('Description'), 'disabled' => true]); ?>
                            </div>
                        </div>
                        <hr/>

                        <div class="editHostDetailFormInput">
                            <?php echo $this->Form->input('edit_contacts', ['type' => 'checkbox', 'checked' => false, 'label' => ['class' => 'text-primary', 'text' => __('Edit contacts')], 'wrapInput' => false, 'class' => 'parent_checkbox']); ?>
                            <div class="scope">
                                <?php echo $this->Form->input(
                                    'Host.Contact', [
                                    'options'   => $contacts,
                                    'multiple'  => true,
                                    'class'     => 'chosen',
                                    'style'     => 'width:100%;',
                                    'label'     => __('Contacts'),
                                    'wrapInput' => 'col col-xs-8',
                                    'disabled'  => true,
                                ]); ?>
                                <?php echo $this->Form->input('keep_contacts', ['type' => 'checkbox', 'checked' => false, 'label' => __('Keep existing'), 'disabled' => true]); ?>
                            </div>
                        </div>
                        <hr/>

                        <div class="editHostDetailFormInput">
                            <?php echo $this->Form->input('edit_contactgroups', ['type' => 'checkbox', 'checked' => false, 'label' => ['class' => 'text-primary', 'text' => __('Edit contact groups')], 'wrapInput' => false, 'class' => 'parent_checkbox']); ?>
                            <div class="scope">
                                <?php echo $this->Form->input(
                                    'Host.Contactgroup', [
                                    'options'   => $contactgroups,
                                    'multiple'  => true,
                                    'class'     => 'chosen',
                                    'style'     => 'width:100%;',
                                    'label'     => __('Contactgroups'),
                                    'wrapInput' => 'col col-xs-8',
                                    'disabled'  => true,
                                ]); ?>
                                <?php echo $this->Form->input('keep_contactgroups', ['type' => 'checkbox', 'checked' => false, 'label' => __('Keep existing'), 'disabled' => true]); ?>
                            </div>
                        </div>
                        <hr/>

                        <div class="editHostDetailFormInput">
                            <?php echo $this->Form->input('edit_url', ['type' => 'checkbox', 'checked' => false, 'label' => ['class' => 'text-primary', 'text' => __('Edit host URL')], 'wrapInput' => false, 'class' => 'parent_checkbox']); ?>
                            <div class="scope">
                                <?php echo $this->Form->input('Host.host_url', ['type' => 'text', 'label' => __('Host URL'), 'disabled' => true]); ?>
                            </div>
                        </div>
                        <hr/>

                        <div class="editHostDetailFormInput">
                            <?php echo $this->Form->input('edit_tags', ['type' => 'checkbox', 'checked' => false, 'label' => ['class' => 'text-primary', 'text' => __('Edit tags')], 'wrapInput' => false, 'class' => 'parent_checkbox']); ?>
                            <div class="scope">
                                <?php echo $this->Form->input('Host.tags', ['type' => 'text', 'label' => __('Tags'), 'disabled' => true]); ?>
                                <?php echo $this->Form->input('keep_tags', ['type' => 'checkbox', 'checked' => false, 'label' => __('Keep existing'), 'disabled' => true]); ?>
                            </div>
                        </div>
                        <hr/>

                        <div class="editHostDetailFormInput">
                            <?php echo $this->Form->input('edit_priority', ['type' => 'checkbox', 'checked' => false, 'label' => ['class' => 'text-primary', 'text' => __('Edit priority')], 'wrapInput' => false, 'class' => 'parent_checkbox']); ?>
                            <div class="scope">
                                <div class="form-group">
                                    <label class="col col-md-2 control-label"
                                           for="HostPriority"><?php echo __('Priority'); ?> </label>
                                    <div class="col col-xs-10 col-md-10 col-lg-10 smart-form">
                                        <div class="rating pull-left">
                                            <?php // The smallest priority is 1 at the moment
                                            for ($i = 5; $i > 0; $i--): ?>
                                                <input type="radio" id="Hoststars-rating-<?php echo $i; ?>"
                                                       value="<?php echo $i; ?>" name="data[Host][priority]"
                                                       disabled="disabled">
                                                <label for="Hoststars-rating-<?php echo $i; ?>"><i
                                                            class="fa fa-fire"></i></label>
                                            <?php endfor; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> <!-- close col -->
            </div> <!-- close row-->
            <br/>
            <?php echo $this->Form->formActions(); ?>
        </div> <!-- close widget body -->
    </div>
</div> <!-- end jarviswidget -->
