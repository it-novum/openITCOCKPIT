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
$defaultActions = [
    'all'    => [
        'icon'  => 'asterisk',
        'class' => 'txt-color-greenLight',
    ],
    'index'  => [
        'icon'  => 'eye',
        'class' => 'text-primary',
    ],
    'add'    => [
        'icon'  => 'plus',
        'class' => 'text-success',
    ],
    'edit'   => [
        'icon'  => 'pencil',
        'class' => 'text-primary',
    ],
    'delete' => [
        'icon'  => 'trash-o',
        'class' => 'text-danger',
    ],
];
?>
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <h1 class="page-title txt-color-blueDark">
            <i class="fa fa-users fa-fw "></i>
            <?php echo __('Administration'); ?>
            <span>>
                <?php echo __('Manage User Roles'); ?>
			</span>
        </h1>
    </div>
</div>
<div class="jarviswidget">
    <header>
        <span class="widget-icon"> <i class="fa fa-users"></i> </span>
        <h2><?php echo __('Edit User Role'); ?></h2>
        <div class="widget-toolbar" role="menu">
            <?php echo $this->Utils->backButton() ?>
        </div>
    </header>
    <div>
        <div class="widget-body">
            <?php
            echo $this->Form->create('Usergroup', [
                'class' => 'form-horizontal clear',
            ]);
            echo $this->Form->input('id', [
                'type'  => 'hidden',
                'value' => $usergroup['Usergroup']['id'],
            ]);
            echo $this->Form->input('Usergroup.name', [
                'value' => $usergroup['Usergroup']['name'],
            ]);
            echo $this->Form->input('Usergroup.description', [
                'value' => $usergroup['Usergroup']['description'],
            ]);
            if (!empty($acos)):
            ?>

        <?php if ($usergroup['Usergroup']['name'] === 'Administrator'): ?>
            <div class="row">
                <div class="col-xs-12">
                    <div class="alert alert-info alert-block">
                        <h4 class="alert-heading"><?php echo __('Notice!'); ?></h4>
                        <?php echo __('Permissions of the user role <strong>Administrator</strong> will be set back to default on every update of %s!', $systemname); ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>

            <div class="padding-left-50 row">
                <div class="row">
                    <div class="col-md-2 no-padding">
                        <div class="row pointer" id="collapseAll">
                            <i class="fa fa-folder text-primary"
                               title="<?php echo __('Collapse all'); ?>"></i> <?php echo __('Collapse all'); ?>
                        </div>
                        <div class="row pointer" id="expandAll">
                            <i class="fa fa-folder-open text-primary"
                               title="<?php echo __('Expand all'); ?>"></i> <?php echo __('Expand all'); ?>
                        </div>
                    </div>
                    <div class="col-xs-7 col-md-7 col-lg-7 col-xs-offset-1 col-md-offset-1 col-lg-offset-1">
                        <div class="row">
                            <?php
                            foreach ($defaultActions as $action => $actionDetails):?>
                                <div class="col-xs-1 col-md-1 col-lg-1 text-center">
                                    <i class="fa fa-<?php echo $actionDetails['icon'] . ' ' . $actionDetails['class']; ?> "
                                       title="<?php echo ucfirst(__($action)); ?>"></i>
                                </div>
                            <?php
                            endforeach;
                            ?>
                        </div>
                        <div class="row text-center">
                            <?php
                            foreach ($defaultActions as $action => $actionDetails):?>
                                <div class="no-padding col-xs-1 col-md-1 col-lg-1">
                                    <i class="fa fa-check-square-o pointer txt-color-blueDark"
                                       title="<?php echo __('Select all'); ?>" data-action="<?php echo $action; ?>"
                                       click-action="on"></i>
                                    <i class="fa fa-square-o pointer txt-color-blueDark"
                                       title="<?php echo __('Deselect all'); ?>" data-action="<?php echo $action; ?>"
                                       click-action="off"></i>
                                </div>
                            <?php
                            endforeach;
                            ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row padding-top-20 padding-left-50">
                <div>
                    <div id="tree" class="tree custom-tree">
                        <ul>
                            <?php
                            foreach ($acos as $rootElement => $rootArray):?>
                                <li>
									<span class="label btn-primary font-sm">
										<i class="fa fa-lg fa-folder-open"></i>
                                        <?php echo __('Access Control Objects'); ?>
									</span>

                                    <ul>
                                        <?php
                                        foreach ($rootArray['children'] as $key => $controllerWithActions):?>
                                            <?php
                                            if (!empty($controllerWithActions['children'])):
                                                $isModule = preg_match('/Module/', $controllerWithActions['Aco']['alias']);
                                                ?>
                                                <li>
												<span class="font-sm no-padding">
													<i class="fa fa-lg fa-folder-open text-<?php echo ($isModule) ? 'success module-controller' : 'primary'; ?>"></i>
                                                    <?php
                                                    echo h(preg_replace('/Controller/', '', $controllerWithActions['Aco']['alias'])); ?>
												</span>
                                                    <ul>
                                                        <?php
                                                        foreach ($controllerWithActions['children'] as $action):
                                                            if (!$isModule):
                                                                //Hide always allowed acos
                                                                if (!isset($alwaysAllowedAcos[$action['Aco']['id']]) && !isset($dependenAcoIds[$action['Aco']['id']])): ?>
                                                                    <li>
                                                                        <?php
                                                                        echo $this->Form->input('Usergroup.Aco.' . $action['Aco']['id'], [
                                                                                'type'      => 'checkbox',
                                                                                'label'     => [
                                                                                    'text'  => $action['Aco']['alias'],
                                                                                    'class' => 'aco-' . $action['Aco']['alias'],
                                                                                ],
                                                                                'wrapInput' => false,
                                                                                'div'       => [
                                                                                    'class' => 'padding-right-5',
                                                                                ],
                                                                                'value'     => 1,
                                                                                'class'     => '_' . $action['Aco']['alias'],
                                                                                'checked'   => in_array($action['Aco']['id'], $aros),
                                                                            ]
                                                                        );
                                                                        ?>
                                                                    </li>
                                                                <?php
                                                                endif;
                                                            else:
                                                                if (!empty($action['children'])):?>
                                                                    <li class="awesomeTest">
																	<span class="font-sm no-padding">
																		<i class="fa fa-lg fa-folder text-success"></i>
                                                                        <?php
                                                                        echo h(preg_replace('/Controller/', '', $action['Aco']['alias'])); ?>
																	</span>
                                                                        <ul>
                                                                            <?php
                                                                            foreach ($action['children'] as $moduleAction):
                                                                                if (!isset($alwaysAllowedAcos[$moduleAction['Aco']['id']]) && !isset($dependenAcoIds[$moduleAction['Aco']['id']])): ?>
                                                                                    <li>
                                                                                        <?php
                                                                                        echo $this->Form->input('Usergroup.Aco.' . $moduleAction['Aco']['id'], [
                                                                                                'type'      => 'checkbox',
                                                                                                'label'     => [
                                                                                                    'text'  => $moduleAction['Aco']['alias'],
                                                                                                    'class' => 'aco-' . $moduleAction['Aco']['alias'],
                                                                                                ],
                                                                                                'wrapInput' => false,
                                                                                                'div'       => [
                                                                                                    'class' => 'padding-right-5',
                                                                                                ],
                                                                                                'value'     => 1,
                                                                                                'class'     => '_' . $moduleAction['Aco']['alias'],
                                                                                                'checked'   => in_array($moduleAction['Aco']['id'], $aros),
                                                                                            ]
                                                                                        );
                                                                                        ?>
                                                                                    </li>
                                                                                <?php
                                                                                endif;
                                                                            endforeach;
                                                                            ?>
                                                                        </ul>
                                                                    </li>
                                                                <?php
                                                                endif;
                                                            endif;
                                                        endforeach;
                                                        ?>
                                                    </ul>
                                                </li>
                                            <?php
                                            endif;
                                        endforeach; ?>
                                    </ul>
                                </li>
                            <?php
                            endforeach;
                            ?>
                        </ul>
                    </div>
                </div>
                <?php
                endif;
                ?>
            </div>
            <?php echo $this->Form->formActions(); ?>
        </div>
    </div>
</div>
