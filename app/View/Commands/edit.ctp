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
            <?php echo __('Commands'); ?>
            <span>>
                <?php echo __('Edit'); ?>
            </span>
        </h1>
    </div>
</div>

<div class="alert alert-danger alert-block" ng-show="hasWebSocketError">
    <a href="#" data-dismiss="alert" class="close">×</a>
    <h5 class="alert-heading">
        <i class="fa fa-warning"></i>
        <?php echo __('Error'); ?>
    </h5>
    <?php echo __('Could not connect to SudoWebsocket Server'); ?>
</div>
<div class="jarviswidget" id="wid-id-0">
    <header>
        <span class="widget-icon"> <i class="fa fa-terminal"></i> </span>
        <h2><?php echo __('Create new command'); ?></h2>
        <div class="widget-toolbar" role="menu">
            <?php if ($this->Acl->hasPermission('index', 'macros')): ?>
                <a ng-click="showMacros()"
                   class="btn btn-primary btn-xs"><i class="fa fa-usd"></i> <?php echo __('Macros overview'); ?></a>
            <?php endif; ?>
            <a class="btn btn-default" ui-sref="CommandsIndex">
                <i class="fa fa-arrow-left"></i>
                <?php echo __('Back to list'); ?>
            </a>
        </div>
    </header>
    <div>
        <form ng-submit="submit();" class="form-horizontal"
              ng-init="successMessage=
            {objectName : '<?php echo __('Command'); ?>' , message: '<?php echo __('saved successfully'); ?>'}">
            <div class="widget-body">

                <div class="row">
                    <div class="col-xs-12 col-md-offset-2 col-md-10" style="padding-right: 0; padding-left: 0">
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
                </div>

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
                    <div class="form-group required" ng-class="{'has-error': errors.name}">
                        <label class="col col-md-2 control-label">
                            <?php echo __('Name'); ?>
                        </label>
                        <div class="col col-xs-10">
                            <input class="form-control" type="text" ng-model="post.Command.name">
                            <div ng-repeat="error in errors.name">
                                <div class="help-block text-danger">{{ error }}</div>
                            </div>
                        </div>

                    </div>
                    <div class="form-group required" ng-class="{'has-error': errors.command_line}">
                        <label class="col col-md-2 control-label">
                            <?php echo __('Command line'); ?>
                        </label>
                        <div class="col col-xs-10 required">
                            <textarea class="form-control code-font" type="text" ng-model="post.Command.command_line"
                                      cols="30" rows="6">
                            </textarea>
                            <div ng-repeat="error in errors.command_line">
                                <div class="help-block text-danger">{{ error }}</div>
                            </div>
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

                    <div ng-repeat="arg in args">
                        <div class="col-md-12 padding-top-5">
                            <div class="col-md-1 text-primary padding-top-10">
                                {{arg.name}}
                            </div>
                            <div class="col-md-10">
                                <label class="col col-md-1 control-label">
                                    <?php echo __('Name'); ?>
                                </label>
                                <div class="col col-md-11">
                                    <input class="form-control input-sm" type="text"
                                           placeholder="<?php echo __('Please enter a name'); ?>"
                                           name="data[Commandargument][{{arg.id}}][human_name]"
                                           ng-model="arg.human_name"
                                           style="width: 100%;">
                                </div>
                            </div>
                            <div class="col-md-1">
                                <a class="btn btn-default btn-sm txt-color-red deleteCommandArg"
                                   href="javascript:void(0);"
                                   ng-click="removeArg(arg)">
                                    <i class="fa fa-trash-o fa-lg"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 padding-top-10">
                        <a class="btn btn-success btn-xs pull-right" id="add_new_arg" href="javascript:void(0);"
                           ng-click="addArg()">
                            <i class="fa fa-plus"></i>
                            <?php echo __('Add argument'); ?>
                        </a>
                    </div>
                    <div ng-show="args.length > 0">
                        <span class="col col-md-10 col-xs-12 txt-color-redLight">
                            <i class="fa fa-exclamation-circle"></i>
                            <?php echo __('empty arguments will be removed automatically'); ?>
                        </span>
                    </div>
                </fieldset>
                <?php if ($this->Acl->hasPermission('terminal')): ?>
                    <br/>
                    <div id="console"></div>
                <?php endif; ?>
                <br/>
            </div>
            <div class="col-xs-12 margin-top-10">
                <div class="well formactions ">
                    <div class="pull-right">
                        <input class="btn btn-primary" type="submit" value="<?php echo __('Update command'); ?>">
                        <a ui-sref="CommandsIndex" class="btn btn-default"><?php echo __('Cancel'); ?></a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<?php if ($this->Acl->hasPermission('index', 'macros')): ?>
    <div class="modal fade" role="dialog" aria-labelledby="myModalLabel" id="MacrosOverview">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">
                        &times;
                    </button>
                    <h4 class="modal-title" id="myModalLabel"><?php echo __('User defined macros'); ?></h4>
                </div>
                <div class="modal-body padding-5">
                    <div class="row">
                        <div class="col-12">
                            <div id="MacroContent">
                                <table id="macrosTable" class="table table-bordered table-striped">
                                    <thead>
                                    <tr>
                                        <th class="no-sort">
                                            <?php echo __('Name'); ?>
                                        </th>
                                        <th class="no-sort">
                                            <?php echo __('Value'); ?>
                                        </th>
                                        <th class="no-sort">
                                            <?php echo __('Description'); ?>
                                        </th>
                                        <th>
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr ng-repeat="macro in macros">
                                        <td class="text-primary bold">
                                            {{macro.name}}
                                        </td>
                                        <td>
                                            <code ng-class="{'macroPassword': macro.password}">
                                                {{macro.value}}
                                            </code>
                                        </td>
                                        <td>
                                            {{macro.description}}
                                        </td>
                                        <td class="text-center">
                                            <button class="btn btn-default txt-color-red btn-xs"
                                                    title="<?php echo __('Hide value'); ?>"
                                                    ng-click="macro.password = 1"
                                                    ng-hide="macro.password">
                                                <i class="fa fa-eye-slash"></i>
                                            </button>

                                            <button class="btn btn-default txt-color-blue btn-xs"
                                                    title="<?php echo __('Show value'); ?>"
                                                    ng-click="macro.password = 0"
                                                    ng-show="macro.password">
                                                <i class="fa fa-eye"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
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
