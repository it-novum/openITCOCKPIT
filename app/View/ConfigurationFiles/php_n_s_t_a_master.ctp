<?php
// Copyright (C) <2018>  <it-novum GmbH>
//
// This file is dual licensed
//
// 1.
//  This program is free software: you can redistribute it and/or modify
//  it under the terms of the GNU General Public License as published by
//  the Free Software Foundation, version 3 of the License.
//
//  This program is distributed in the hope that it will be useful,
//  but WITHOUT ANY WARRANTY; without even the implied warranty of
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//  GNU General Public License for more details.
//
//  You should have received a copy of the GNU General Public License
//  along with this program.  If not, see <http://www.gnu.org/licenses/>.
//
// 2.
//  If you purchased an openITCOCKPIT Enterprise Edition you can use this file
//  under the terms of the openITCOCKPIT Enterprise Edition license agreement.
//  License agreement and license key will be shipped with the order
//  confirmation.


use itnovum\openITCOCKPIT\ConfigGenerator\phpNSTAMaster;

/** @var phpNSTAMaster $phpNstaMaster */
?>


<form ng-submit="submit();" class="form-horizontal">

    <div class="row">
        <fieldset>
            <legend><?php echo __('Monitoring engine'); ?></legend>

            <div class="form-group required"
                 ng-class="{'has-error': errors.Configfile.use_spooldir}">
                <label class="col col-md-2 control-label">
                    use_spooldir
                </label>
                <div class="col col-xs-10">
                    <select
                            class="form-control"
                            ng-model="post.string.use_spooldir">
                        <option value="1"><?php echo __('Use check_result_path'); ?></option>
                        <option value="2"><?php echo __('Use external command file (nagios.cmd)'); ?></option>
                        <option value="3"><?php echo __('Use Naemon Query Handler (nagios.qh recommended)'); ?></option>
                    </select>
                    <div ng-repeat="error in errors.Configfile.use_spooldir">
                        <div class="help-block text-danger">{{ error }}</div>
                    </div>
                </div>
                <div class="helpText text-muted col-md-offset-2 col-md-6">
                    <?php echo h($phpNstaMaster->getHelpText('use_spooldir')); ?>
                </div>
            </div>

            <div class="form-group required" ng-class="{'has-error': errors.Configfile.max_checks}">
                <label class="col col-md-2 control-label">
                    max_checks
                </label>
                <div class="col col-xs-10">
                    <input
                            class="form-control"
                            type="number"
                            min="0"
                            ng-model="post.int.max_checks">
                    <div ng-repeat="error in errors.Configfile.max_checks">
                        <div class="help-block text-danger">{{ error }}</div>
                    </div>
                </div>
                <div class="helpText text-muted col-md-offset-2 col-md-6">
                    <?php echo h($phpNstaMaster->getHelpText('max_checks')); ?>
                </div>
            </div>

            <div class="form-group required" ng-class="{'has-error': errors.Configfile.max_threads}">
                <label class="col col-md-2 control-label">
                    max_threads
                </label>
                <div class="col col-xs-10">
                    <input
                            class="form-control"
                            type="number"
                            min="0"
                            ng-model="post.int.max_threads">
                    <div ng-repeat="error in errors.Configfile.max_threads">
                        <div class="help-block text-danger">{{ error }}</div>
                    </div>
                </div>
                <div class="helpText text-muted col-md-offset-2 col-md-6">
                    <?php echo h($phpNstaMaster->getHelpText('max_threads')); ?>
                </div>
            </div>
        </fieldset>
    </div>

    <div class="row">
        <fieldset>
            <legend><?php echo __('SSH configuration'); ?></legend>

            <div class="form-group required" ng-class="{'has-error': errors.Configfile.use_ssh_tunnel}">
                <label class="col col-md-2 control-label" for="use_ssh_tunnel">
                    use_ssh_tunnel
                </label>
                <div class="col col-md-10 padding-top-7">
                    <input
                            type="checkbox"
                            id="use_ssh_tunnel"
                            ng-false-value="0"
                            ng-true-value="1"
                            ng-model="post.bool.use_ssh_tunnel">
                    <div ng-repeat="error in errors.Configfile.use_ssh_tunnel">
                        <div class="help-block text-danger">{{ error }}</div>
                    </div>
                </div>
                <div class="helpText text-muted col-md-offset-2 col-md-6">
                    <?php echo h($phpNstaMaster->getHelpText('use_ssh_tunnel')); ?>
                </div>
            </div>

            <div class="form-group required" ng-class="{'has-error': errors.Configfile.ssh_username}">
                <label class="col col-md-2 control-label">
                    ssh_username
                </label>
                <div class="col col-xs-10">
                    <input
                            class="form-control"
                            type="text"
                            ng-model="post.string.ssh_username">
                    <div ng-repeat="error in errors.Configfile.ssh_username">
                        <div class="help-block text-danger">{{ error }}</div>
                    </div>
                </div>
                <div class="helpText text-muted col-md-offset-2 col-md-6">
                    <?php echo h($phpNstaMaster->getHelpText('ssh_username')); ?>
                </div>
            </div>

            <div class="form-group required" ng-class="{'has-error': errors.Configfile.ssh_port}">
                <label class="col col-md-2 control-label">
                    ssh_port
                </label>
                <div class="col col-xs-10">
                    <input
                            class="form-control"
                            type="number"
                            min="0"
                            ng-model="post.int.ssh_port">
                    <div ng-repeat="error in errors.Configfile.ssh_port">
                        <div class="help-block text-danger">{{ error }}</div>
                    </div>
                </div>
                <div class="helpText text-muted col-md-offset-2 col-md-6">
                    <?php echo h($phpNstaMaster->getHelpText('ssh_port')); ?>
                </div>
            </div>

            <div class="form-group required" ng-class="{'has-error': errors.Configfile.private_path}">
                <label class="col col-md-2 control-label">
                    private_path
                </label>
                <div class="col col-xs-10">
                    <input
                            class="form-control"
                            type="text"
                            ng-model="post.string.private_path">
                    <div ng-repeat="error in errors.Configfile.private_path">
                        <div class="help-block text-danger">{{ error }}</div>
                    </div>
                </div>
                <div class="helpText text-muted col-md-offset-2 col-md-6">
                    <?php echo h($phpNstaMaster->getHelpText('private_path')); ?>
                </div>
            </div>

            <div class="form-group required" ng-class="{'has-error': errors.Configfile.public_path}">
                <label class="col col-md-2 control-label">
                    public_path
                </label>
                <div class="col col-xs-10">
                    <input
                            class="form-control"
                            type="text"
                            ng-model="post.string.public_path">
                    <div ng-repeat="error in errors.Configfile.public_path">
                        <div class="help-block text-danger">{{ error }}</div>
                    </div>
                </div>
                <div class="helpText text-muted col-md-offset-2 col-md-6">
                    <?php echo h($phpNstaMaster->getHelpText('public_path')); ?>
                </div>
            </div>

            <div class="form-group required" ng-class="{'has-error': errors.Configfile.port_range}">
                <label class="col col-md-2 control-label">
                    port_range
                </label>
                <div class="col col-xs-10">
                    <input
                            class="form-control"
                            type="text"
                            ng-model="post.string.port_range">
                    <div ng-repeat="error in errors.Configfile.port_range">
                        <div class="help-block text-danger">{{ error }}</div>
                    </div>
                </div>
                <div class="helpText text-muted col-md-offset-2 col-md-6">
                    <?php echo h($phpNstaMaster->getHelpText('port_range')); ?>
                </div>
            </div>
        </fieldset>
    </div>

    <div class="row">
        <fieldset>
            <legend><?php echo __('Logging'); ?></legend>

            <div class="form-group required"
                 ng-class="{'has-error': errors.Configfile.loglevel}">
                <label class="col col-md-2 control-label">
                    loglevel
                </label>
                <div class="col col-xs-10">
                    <select
                            class="form-control"
                            ng-model="post.string.loglevel">
                        <option value="-1"><?php echo __('Disable loging'); ?></option>
                        <option value="0"><?php echo __('Only some information about phpNSTA'); ?></option>
                        <option value="1"><?php echo __('Process fork information'); ?></option>
                        <option value="2"><?php echo __('File operation information'); ?></option>
                        <option value="3"><?php echo __('Nagios process information'); ?></option>
                        <option value="4"><?php echo __('SSH tunnel information'); ?></option>
                        <option value="5"><?php echo __('System time synchronization information'); ?></option>
                        <option value="6"><?php echo __('Events triggerd by the SAT-Systems'); ?></option>
                        <option value="7"><?php echo __('Child process monitoring'); ?></option>
                        <option value="9"><?php echo __('Data for Mod_Gearman'); ?></option>
                        <option value="10"><?php echo __('Bulk Transmission'); ?></option>
                        <option value="11"><?php echo __('Custom data transmission'); ?></option>
                        <option value="12"><?php echo __('ALL'); ?></option>
                    </select>
                    <div ng-repeat="error in errors.Configfile.loglevel">
                        <div class="help-block text-danger">{{ error }}</div>
                    </div>
                </div>
                <div class="helpText text-muted col-md-offset-2 col-md-6">
                    <?php echo h($phpNstaMaster->getHelpText('loglevel')); ?>
                </div>
            </div>

            <div class="form-group required" ng-class="{'has-error': errors.Configfile.logrotate_date_format}">
                <label class="col col-md-2 control-label">
                    logrotate_date_format
                </label>
                <div class="col col-xs-10">
                    <input
                            class="form-control"
                            type="text"
                            ng-model="post.string.logrotate_date_format">
                    <div ng-repeat="error in errors.Configfile.logrotate_date_format">
                        <div class="help-block text-danger">{{ error }}</div>
                    </div>
                </div>
                <div class="helpText text-muted col-md-offset-2 col-md-6">
                    <?php echo h($phpNstaMaster->getHelpText('logrotate_date_format')); ?>
                </div>
            </div>

            <div class="form-group required" ng-class="{'has-error': errors.Configfile.date_format}">
                <label class="col col-md-2 control-label">
                    date_format
                </label>
                <div class="col col-xs-10">
                    <input
                            class="form-control"
                            type="text"
                            ng-model="post.string.date_format">
                    <div ng-repeat="error in errors.Configfile.date_format">
                        <div class="help-block text-danger">{{ error }}</div>
                    </div>
                </div>
                <div class="helpText text-muted col-md-offset-2 col-md-6">
                    <?php echo h($phpNstaMaster->getHelpText('date_format')); ?>
                </div>
            </div>

            <div class="form-group required" ng-class="{'has-error': errors.Configfile.cleanup_fileage}">
                <label class="col col-md-2 control-label">
                    cleanup_fileage
                </label>
                <div class="col col-xs-10">
                    <input
                            class="form-control"
                            type="number"
                            min="0"
                            ng-model="post.int.cleanup_fileage">
                    <div ng-repeat="error in errors.Configfile.cleanup_fileage">
                        <div class="help-block text-danger">{{ error }}</div>
                    </div>
                </div>
                <div class="helpText text-muted col-md-offset-2 col-md-6">
                    <?php echo h($phpNstaMaster->getHelpText('cleanup_fileage')); ?>
                </div>
            </div>
        </fieldset>
    </div>

    <div class="row">
        <fieldset>
            <legend><?php echo __('Supervisor'); ?></legend>


            <div class="form-group required" ng-class="{'has-error': errors.Configfile.supervisor_username}">
                <label class="col col-md-2 control-label">
                    supervisor_username
                </label>
                <div class="col col-xs-10">
                    <input
                            class="form-control"
                            type="text"
                            ng-model="post.string.supervisor_username">
                    <div ng-repeat="error in errors.Configfile.supervisor_username">
                        <div class="help-block text-danger">{{ error }}</div>
                    </div>
                </div>
                <div class="helpText text-muted col-md-offset-2 col-md-6">
                    <?php echo h($phpNstaMaster->getHelpText('supervisor_username')); ?>
                </div>
            </div>

            <div class="form-group required" ng-class="{'has-error': errors.Configfile.supervisor_password}">
                <label class="col col-md-2 control-label">
                    supervisor_password
                </label>
                <div class="col col-xs-10">
                    <input
                            class="form-control"
                            type="text"
                            ng-model="post.string.supervisor_password">
                    <div ng-repeat="error in errors.Configfile.supervisor_password">
                        <div class="help-block text-danger">{{ error }}</div>
                    </div>
                </div>
                <div class="helpText text-muted col-md-offset-2 col-md-6">
                    <?php echo h($phpNstaMaster->getHelpText('supervisor_password')); ?>
                </div>
            </div>
        </fieldset>
    </div>

    <div class="row">
        <fieldset>
            <legend><?php echo __('Time synchronisation'); ?></legend>


            <div class="form-group required" ng-class="{'has-error': errors.Configfile.synchronize_time}">
                <label class="col col-md-2 control-label" for="synchronize_time">
                    synchronize_time
                </label>
                <div class="col col-md-10 padding-top-7">
                    <input
                            type="checkbox"
                            id="synchronize_time"
                            ng-false-value="0"
                            ng-true-value="1"
                            ng-model="post.bool.synchronize_time">
                    <div ng-repeat="error in errors.Configfile.synchronize_time">
                        <div class="help-block text-danger">{{ error }}</div>
                    </div>
                </div>
                <div class="helpText text-muted col-md-offset-2 col-md-6">
                    <?php echo h($phpNstaMaster->getHelpText('synchronize_time')); ?>
                </div>
            </div>

            <div class="form-group required"
                 ng-class="{'has-error': errors.Configfile.tsync_every}">
                <label class="col col-md-2 control-label">
                    tsync_every
                </label>
                <div class="col col-xs-10">
                    <select
                            class="form-control"
                            ng-model="post.string.tsync_every">
                        <option value="hour"><?php echo __('Hour'); ?></option>
                        <option value="day"><?php echo __('Day'); ?></option>
                        <option value="minute"><?php echo __('Minute'); ?></option>
                    </select>
                    <div ng-repeat="error in errors.Configfile.tsync_every">
                        <div class="help-block text-danger">{{ error }}</div>
                    </div>
                </div>
                <div class="helpText text-muted col-md-offset-2 col-md-6">
                    <?php echo h($phpNstaMaster->getHelpText('tsync_every')); ?>
                </div>
            </div>
        </fieldset>
    </div>

    <div class="row">
        <div class="col-xs-12 margin-top-10">
            <div class="well formactions ">
                <div class="pull-right">
                    <input class="btn btn-primary" type="submit" value="<?php echo __('Save'); ?>">&nbsp;
                    <a href="/ConfigurationFiles/index" class="btn btn-default">
                        <?php echo __('Cancel'); ?>
                    </a>
                </div>
            </div>
        </div>
    </div>

</form>
