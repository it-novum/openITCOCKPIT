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
        <h2><?php echo __('Add command'); ?></h2>
        <div class="widget-toolbar" role="menu">
            <?php if ($this->Acl->hasPermission('index', 'macros')): ?>
                <a href="javascript:void(0);" data-toggle="modal" id="loadMacrosOberview" data-target="#MacrosOverview"
                   class="btn btn-primary btn-xs"><i class="fa fa-usd"></i> <?php echo __('Macros overview'); ?></a>
            <?php endif; ?>
            <?php echo $this->Utils->backButton(); ?>
        </div>
    </header>
    <div>

        <div class="widget-body">
            <form ng-submit="submit();" class="form-horizontal">
                <div class="row">
                    <div class="form-group">
                        <label class="col col-md-2 control-label">
                            <?php echo __('Command type'); ?>
                        </label>
                        <div class="col col-xs-10">
                            <select
                                    class="form-control"
                                    chosen="commandtypes"
                                    ng-init="post.Command.command_type='1'"
                                    ng-model="post.Command.command_type">
                                <?php
                                $command_types = [
                                    CHECK_COMMAND        => __('Service check command'),
                                    HOSTCHECK_COMMAND    => __('Host check command'),
                                    NOTIFICATION_COMMAND => __('Notification command'),
                                    EVENTHANDLER_COMMAND => __('Eventhandler command'),
                                ];
                                foreach ($command_types as $key => $value) :
                                    printf('<option value="%s">%s</option>', $key, $value);
                                endforeach;
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group required">
                        <label class="col col-md-2 control-label">
                            <?php echo __('Name'); ?>
                        </label>
                        <div class="col col-xs-10">
                            <input class="form-control" type="text" ng-model="post.Command.name">
                        </div>
                    </div>
                    <div class="form-group required">
                        <label class="col col-md-2 control-label">
                            <?php echo __('Command line'); ?>
                        </label>
                        <div class="col col-xs-10 required">
                            <textarea class="form-control" type="text" ng-model="post.Command.command_line"
                                      cols="30" rows="6">
                            </textarea>
                        </div>
                    </div>
                    <div class="col col-md-2 hidden-mobile hidden-tablet"><!-- space for nice layout --></div>
                    <div class="col col-md-10 col-xs-12 text-info padding-bottom-10">
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
                    <div class="form-group">
                        <label class="col col-md-2 control-label">
                            <?php echo __('Description'); ?>
                        </label>
                        <div class="col col-xs-10">
                            <textarea class="form-control" type="text" ng-model="post.Command.description"
                                      cols="30" rows="6">
                            </textarea>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="widget-body">
            <fieldset class=" form-inline required padding-10">
                <legend class="font-sm">
                    <div>
                        <label><?php echo __('Arguments'); ?>:</label>
                    </div>
                </legend>
                <div id="command_args">
                    <!-- empty because we create a new command! -->
                </div>
                <div class="col-xs-12 padding-top-10">
                    <a class="btn btn-success btn-xs pull-right" id="add_new_arg" href="javascript:void(0);">
                        <i class="fa fa-plus"></i>
                        <?php echo __('Add argument'); ?>
                    </a>
                </div>
            </fieldset>
            <?php if ($this->Acl->hasPermission('terminal')): ?>
                <br/>
                <div id="console"></div>
            <?php endif; ?>
            <br/>
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
