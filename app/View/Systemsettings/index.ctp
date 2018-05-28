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
    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
        <h1 class="page-title txt-color-blueDark">
            <i class="fa fa-wrench fa-fw "></i>
            <?php echo __('System Settings'); ?>
        </h1>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
        <div class="alert alert-danger fade in">
            <button data-dismiss="alert" class="close">×</button>
            <i class="fa fa-exclamation "></i>
            <strong><?php echo __('Attention!'); ?></strong> <?php echo __("Do not change values, where you don't know what you are doing!"); ?>
        </div>
    </div>
</div>

<section id="widget-grid" class="">

    <div class="row">

        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-1" data-widget-editbutton="false">
                <header>
                    <span class="widget-icon hidden-mobile"> <i class="fa fa-wrench"></i> </span>
                    <h2 class="hidden-mobile"><?php echo __('System settings'); ?> </h2>

                </header>
                <div>

                    <div class="widget-body no-padding">
                        <?php
                        echo $this->Form->create('Systemsetting', [
                            'class' => 'form-horizontal clear',
                        ]);
                        ?>
                        <div class="mobile_table">
                            <table id="host_list" class="table table-striped table-hover table-bordered smart-form"
                                   style="">
                                <thead>
                                <tr>
                                    <th><?php echo __('Key'); ?></th>
                                    <th><?php echo __('Value'); ?></th>
                                    <th class="text-center"><?php echo __('Info'); ?></th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php $i = 0; ?>
                                <?php foreach ($all_systemsettings as $key => $values): ?>
                                    <tr>
                                        <td class="service_table_host_header text-primary" colspan="3">
                                            <strong><?php echo $key; ?></strong></td>
                                    <tr>
                                    <?php foreach ($values as $value): ?>
                                        <tr>
                                            <td><?php echo explode('.', $value['key'], 2)[1]; //This parse the PREFIX MONITORIN. or WEBSERVER. or WHATEVER. away ?></td>
                                            <td>
                                                <div class="form-group">
                                                    <div class="col col-xs-12">
                                                        <input type="hidden" id="SystemsettingId"
                                                               value="<?php echo h($value['id']); ?>"
                                                               class="form-control"
                                                               name="data[<?php echo $i; ?>][Systemsetting][id]">
                                                        <?php
                                                        switch ($value['key']):
                                                            case 'MONITORING.HOST.INITSTATE':
                                                                $options = [
                                                                    'o' => 'Up',
                                                                    'd' => 'Down',
                                                                    'u' => 'Unreachable',
                                                                ];
                                                                echo $this->Html->createSelect($options, 'data[' . $i . '][Systemsetting][value]', $value['value']);
                                                                break;

                                                            case 'MONITORING.SERVICE.INITSTATE':
                                                                $options = [
                                                                    'o' => 'Ok',
                                                                    'w' => 'Warning',
                                                                    'c' => 'Critical',
                                                                    'u' => 'Unknown',
                                                                ];
                                                                echo $this->Html->createSelect($options, 'data[' . $i . '][Systemsetting][value]', $value['value']);
                                                                break;

                                                            case 'FRONTEND.SHOW_EXPORT_RUNNING':
                                                                $options = [
                                                                    'yes' => 'True',
                                                                    'no'  => 'False'
                                                                ];
                                                                echo $this->Html->createSelect($options, 'data[' . $i . '][Systemsetting][value]', $value['value']);
                                                                break;

                                                            case 'FRONTEND.AUTH_METHOD':
                                                                $options = [
                                                                    'session'   => 'PHP session',
                                                                    'twofactor' => 'Two factor authentication (PHP session based)',
                                                                    'ldap'      => 'PHP LDAP',
                                                                    'sso'       => 'SSO'
                                                                ];
                                                                echo $this->Html->createSelect($options, 'data[' . $i . '][Systemsetting][value]', $value['value']);
                                                                break;

                                                            case 'FRONTEND.LDAP.TYPE':
                                                                $options = [
                                                                    'adldap'   => 'Active Directory LDAP',
                                                                    'openldap' => 'OpenLDAP'
                                                                ];
                                                                echo $this->Html->createSelect($options, 'data[' . $i . '][Systemsetting][value]', $value['value']);
                                                                break;

                                                            case 'FRONTEND.LDAP.PASSWORD':
                                                            case 'MONITORING.ACK_RECEIVER_PASSWORD':
                                                            case 'FRONTEND.SSO.CLIENT_SECRET':
                                                                ?><input type="password" id="SystemsettingValue"
                                                                         value="<?php echo h($value['value']); ?>"
                                                                         class="form-control systemsetting-input"
                                                                         name="data[<?php echo $i; ?>][Systemsetting][value]"><?php
                                                                break;

                                                            case 'FRONTEND.LDAP.USE_TLS':
                                                            case 'MONITORING.SINGLE_INSTANCE_SYNC':
                                                            case 'MONITORING.HOST_CHECK_ACTIVE_DEFAULT':
                                                            case 'MONITORING.SERVICE_CHECK_ACTIVE_DEFAULT':
                                                            case 'FRONTEND.HIDDEN_USER_IN_CHANGELOG':
                                                            case 'FRONTEND.DISABLE_LOGIN_ANIMATION':
                                                                $options = [
                                                                    0 => 'False',
                                                                    1 => 'True',
                                                                ];
                                                                echo $this->Html->createSelect($options, 'data[' . $i . '][Systemsetting][value]', $value['value']);
                                                                break;

                                                            case 'FRONTEND.PRESELECTED_DOWNTIME_OPTION':
                                                                $options = [
                                                                    '0' => 'Individual host',
                                                                    '1' => 'Host including services',
                                                                ];
                                                                echo $this->Html->createSelect($options, 'data[' . $i . '][Systemsetting][value]', $value['value']);
                                                                break;

                                                            case 'ARCHIVE.AGE.SERVICECHECKS':
                                                            case 'ARCHIVE.AGE.HOSTCHECKS':
                                                            case 'ARCHIVE.AGE.STATEHISTORIES':
                                                            case 'ARCHIVE.AGE.LOGENTRIES':
                                                            case 'ARCHIVE.AGE.NOTIFICATIONS':
                                                            case 'ARCHIVE.AGE.CONTACTNOTIFICATIONS':
                                                            case 'ARCHIVE.AGE.CONTACTNOTIFICATIONMETHODS':
                                                                $options = [];
                                                                for ($k = 1; $k < 107; $k++) {
                                                                    $options[$k] = $k;
                                                                }
                                                                echo $this->Html->createSelect($options, 'data[' . $i . '][Systemsetting][value]', $value['value']);
                                                                break;


                                                            default:
                                                                ?><input type="text" id="SystemsettingValue"
                                                                         value="<?php echo h($value['value']); ?>"
                                                                         class="form-control systemsetting-input"
                                                                         name="data[<?php echo $i; ?>][Systemsetting][value]"><?php
                                                                break;
                                                        endswitch;
                                                        ?>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-center"><a href="javascript:void(0);"
                                                                       data-original-title="<?php echo h($value['info']); ?>"
                                                                       data-placement="left" rel="tooltip"
                                                                       data-container="body"><i
                                                            class="padding-top-5 fa fa-info-circle fa-2x"></i></a></td>
                                        </tr>
                                        <?php $i++; ?>
                                    <?php endforeach; ?>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <br/>
                    <?php echo $this->Form->formActions(); ?>
                </div>
            </div>
    </div>
</section>
