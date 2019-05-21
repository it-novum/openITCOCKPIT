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
                <i class="fa fa-terminal fa-fw "></i>
                <?php echo __('Monitoring'); ?>
                <span>>
                    <?php echo __('Commands'); ?>
                </span>
                <div class="third_level"> <?php echo ucfirst($this->params['action']); ?></div>
            </h1>
        </div>
    </div>
    <div id="error_msg"></div>

    <div class="jarviswidget" id="wid-id-0">
        <header>
            <span class="widget-icon"> <i class="fa fa-terminal"></i> </span>
            <h2><?php echo __('Edit command'); ?></h2>
            <div class="widget-toolbar" role="menu">
                <?php if ($this->Acl->hasPermission('index', 'macros')): ?>
                    <a href="javascript:void(0);" data-toggle="modal" id="loadMacrosOberview"
                       data-target="#MacrosOverview" class="btn btn-primary btn-xs"><i
                                class="fa fa-usd"></i> <?php echo __('Macros overview'); ?></a>
                <?php endif; ?>
                <?php if ($this->Acl->hasPermission('delete')): ?>
                    <?php echo $this->Utils->deleteButton(null, $command['Command']['id']); ?>
                <?php endif; ?>
                <?php echo $this->Utils->backButton(); ?>
            </div>
            <div class="widget-toolbar text-muted cursor-default hidden-xs hidden-sm hidden-md">
                <?php echo __('UUID: %s', h($command['Command']['uuid'])); ?>
            </div>
        </header>
        <div>
            <div class="widget-body">
                <div class="col-xs-12 col-md-offset-2 col-md-10">
                    <div class="alert alert-block alert-warning">
                        <a class="close" data-dismiss="alert" href="#">×</a>
                        <h4 class="alert-heading">
                            <i class="fa fa-exclamation-triangle"></i>
                            <?php echo __('Security notice'); ?>
                        </h4>
                        <?php echo __('User defined macros inside of command_line could lead to unwanted code execution.'); ?>
                        <br/>
                        <?php echo __('It is recommended to only provide access for a certain group of users to edit commands and user defined macros.'); ?>
                    </div>
                </div>

                <?php
                echo $this->Form->create('Command', [
                    'class' => 'form-horizontal clear',
                ]);
                echo $this->Form->input('id', ['type' => 'hidden', 'value' => $command['Command']['id']]);
                echo $this->Form->input('command_type', ['options' => $command_types, 'class' => 'chosen', 'style' => 'width: 100%;', 'selected' => $command['Command']['command_type'], 'label' => __('Command type')]);
                echo $this->Form->input('name', ['value' => $command['Command']['name'], 'label' => __('Name')]);
                echo $this->Form->input('command_line', ['value' => $command['Command']['command_line'], 'label' => __('Command line')]);
                ?>
                <div class="col col-md-2 hidden-mobile hidden-tablet"><!-- space for nice layout --></div>
                <div class="col col-md-10 col-xs-12 text-info">
                    <i class="fa fa-info-circle"></i>

                    <?php
                    $link = __('user defined macro');
                    if ($this->Acl->hasPermission('index', 'macros')):
                        $link = sprintf('<a href="/macros">%s</a>', $link);
                    endif;
                    ?>

                    <?php echo __('A $-sign needs to be escaped manually (\$). Semicolons (;) needs to be defined as %s.', $link); ?>
                    <br/>
                    <?php echo __('Nagios supports up to 32 $ARGx$ macros ($ARG1$ through $ARG32$)'); ?>
                </div>
                <br/><br/>
                <?php echo $this->Form->input('description', ['value' => $command['Command']['description'], 'label' => __('Description')]); ?>

                <br/>
                <fieldset class=" form-inline required padding-10">
                    <legend class="font-sm">
                        <div>
                            <label><?php echo __('Arguments'); ?>:</label>
                        </div>
                    </legend>
                    <div id="command_args">
                        <?php App::uses('UUID', 'Lib'); ?>
                        <?php foreach ($command['Commandargument'] as $commandarg): ?>
                            <?php $uuid = sha1(UUID::v4()); ?>
                            <div class="col-xs-12 padding-top-10">
                                <div class="col-xs-1 text-primary">
                                    <?php echo $commandarg['name']; ?>
                                </div>
                                <div class="col-xs-10">
                                    <label class="control-label"><?php echo __('Name'); ?></label>
                                    <input type="hidden" name="data[Commandargument][<?php echo $uuid; ?>][id]"
                                           argument="name" value="<?php echo $commandarg['id']; ?>"/>
                                    <input type="hidden" name="data[Commandargument][<?php echo $uuid; ?>][command_id]"
                                           value="<?php echo $command['Command']['id']; ?>"/>
                                    <input type="hidden" name="data[Commandargument][<?php echo $uuid; ?>][name]"
                                           value="<?php echo $commandarg['name']; ?>" uuid="<?php echo $uuid; ?>"
                                           argument="name"/>
                                    <input class="form-control" style="width:100%" type="text"
                                           uuid="<?php echo $uuid; ?>"
                                           name="data[Commandargument][<?php echo $uuid; ?>][human_name]"
                                           value="<?php echo $commandarg['human_name']; ?>"/>
                                </div>
                                <div class="col-xs-1">
                                    <label><!-- just a spacer for a nice layout --> &nbsp;</label>
                                    <br/>
                                    <a class="btn btn-default btn-sx txt-color-red deleteCommandArg"
                                       href="javascript:void(0);" delete="<?php echo $commandarg['id']; ?>">
                                        <i class="fa fa-trash-o fa-lg"></i>
                                    </a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="col-xs-12 padding-top-10">
                        <a class="btn btn-success btn-xs pull-right" id="add_new_arg" href="javascript:void(0);">
                            <i class="fa fa-plus"></i>
                            <?php echo __('Add argument'); ?>
                        </a>
                    </div>
                    <span class="col col-md-10 col-xs-12 txt-color-redLight"><i
                                class="fa fa-exclamation-circle"></i> <?php echo __('empty arguments will be removed automatically'); ?></span>
                </fieldset>
                <?php if ($this->Acl->hasPermission('terminal')): ?>
                    <br/>
                    <div id="console"></div>
                <?php endif; ?>
                <br/>
                <?php echo $this->Form->formActions(); ?>
            </div>
        </div>
    </div>

<?php if ($this->Acl->hasPermission('index', 'macros')): ?>
    <div class="modal fade" id="MacrosOverview" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
         aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                        &times;
                    </button>
                    <h4 class="modal-title" id="myModalLabel"><?php echo __('User defined macros'); ?></h4>
                </div>
                <div class="modal-body">

                    <div class="row">
                        <div class="col-12">
                            <div id="macros_loader">
								<span class="text-center">
									<h1>
										<i class="fa fa-cog fa-lg fa-spin"></i>
									</h1>
									<br/>
								</span>
                            </div>
                            <div id="MacroContent"><!-- content loaded by ajax --></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">
                        <?php echo __('Close'); ?>
                    </button>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>