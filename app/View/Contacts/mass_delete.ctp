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
<?php
if (!isset($count)):
    $count = 1;
endif;

if (!isset($contactsToDelete)):
    $contactsToDelete = [];
endif;

if (!isset($contactsCanotDelete)):
    $contactsCanotDelete = [];
endif;
?>
<div class="row">
    <div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
        <h1 class="page-title txt-color-blueDark">
            <i class="fa fa-user fa-fw "></i>
            <?php echo __('Monitoring'); ?>
            <span>>
                <?php echo $this->Utils->pluralize($count, __('Contact'), __('Contacts')); ?>
            </span>
            <div class="third_level"> <?php echo __('Delete'); ?></div>
        </h1>
    </div>
</div>
<div id="error_msg"></div>

<div class="jarviswidget" id="wid-id-0">
    <header>
        <span class="widget-icon hidden-mobile hidden-tablet"> <i class="fa fa-user"></i> </span>
        <h2 class="hidden-mobile hidden-tablet"><?php echo __('Delete'); ?><?php echo $this->Utils->pluralize($count, __('contact'), __('contacts')); ?></h2>
        <?php if (isset($back_url)): ?>
            <div class="widget-toolbar hidden-mobile hidden-tablet" role="menu">
                <?php echo $this->Utils->backButton(__('Back'), $back_url); ?>
            </div>
        <?php endif; ?>
    </header>
    <div>
        <div class="widget-body">
            <div class="row">
                <?php echo $this->Form->create('Contact', [
                    'class' => 'form-horizontal clear',
                ]); ?>
                <?php if (!empty($contactsToDelete)): ?>
                    <div class="col-xs-12 col-md-12 col-lg-6">
                        <ul class="list-group">
                            <li class="list-group-item group-item-danger">
                                <i class="fa fa-trash-o"></i>
                                <strong><?php echo __('The following %s will deleted', $this->Utils->pluralize($count, __('contact'), __('contacts'))); ?>
                                    :</strong>
                            </li>
                            <?php foreach ($contactsToDelete as $key => $contactToDelete): ?>
                                <li class="list-group-item list-group-item-danger">
                                    <?php echo h($contactToDelete['Contact']['name']); ?>
                                    <?php echo $this->Form->input('Contact.delete.' . $key, ['value' => $contactToDelete['Contact']['id'], 'type' => 'hidden']); ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
                <div class="col-xs-12 col-md-12 col-lg-6">
                    <?php if (!empty($contactsCanotDelete)): ?>
                        <ul class="list-group">
                            <li class="list-group-item active">
                                <strong><i class="fa fa-info-circle"></i> <?php echo __('The following %s can\'t be deleted.', $this->Utils->pluralize($count, __('contact'), __('contacts'))); ?>
                                </strong> <i><?php echo __('(Used by other object)'); ?>:</i>
                            </li>
                            <?php foreach ($contactsCanotDelete as $contactId => $contactCanotDelete): ?>
                                <li class="list-group-item list-group-item-info">
                                    <?php echo h($contactCanotDelete);
                                    if ($hasRootPrivileges && $this->Acl->hasPermission('usedBy')):
                                        echo $this->Html->link(__('Used by ...'), [
                                            'controller' => 'contacts',
                                            'action'     => 'usedBy',
                                            $contactId
                                        ], [
                                            'target' => '_blank',
                                            'icon'   => 'fa fa-reply-all fa-flip-horizontal',
                                            'class'  => 'padding-10'
                                        ]);
                                    endif;
                                    ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>

                </div>
            </div>
            <br/>
            <?php echo $this->Form->formActions(__('Delete'), ['saveClass' => 'btn btn-danger']); ?>
        </div>
    </div>
</div>
