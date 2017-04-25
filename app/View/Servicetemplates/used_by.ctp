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
<?php $this->Paginator->options(['url' => $this->params['named']]); ?>
<div class="row">
    <div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
        <h1 class="page-title txt-color-blueDark">
            <i class="fa fa-code-fork fa-fw "></i>
            <?php echo __('Service Template'); ?>
            <span>>
                <?php echo __('used by...'); ?>
			</span>
        </h1>
    </div>
</div>

<section id="widget-grid" class="">

    <div class="row">
        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-1" data-widget-editbutton="false">
                <header>
                    <div class="widget-toolbar" role="menu">
                        <?php echo $this->Utils->backButton(__('Back'), $back_url); ?>
                    </div>

                    <div class="jarviswidget-ctrls" role="menu">
                    </div>
                    <span class="widget-icon"> <i class="fa fa-code-fork"></i> </span>
                    <h2><?php echo __('Service Template'); ?>
                        <strong><?php echo h($servicetemplate['Servicetemplate']['template_name']); ?></strong> <?php echo __('is used by the following'); ?> <?php echo $this->Utils->pluralize($all_hosts, __('host'), __('hosts')); ?>
                        (<?php echo sizeof($all_hosts); ?>):</h2>

                </header>

                <div>

                    <div class="widget-body no-padding">
                        <table id="host_list" class="table table-striped table-hover table-bordered smart-form" style="">
                            <thead>
                            <tr>
                                <?php $order = $this->Paginator->param('order'); ?>
                                <th class="no-sort" style="width: 15px;"><i class="fa fa-check-square-o fa-lg"></i></th>
                                <th class="no-sort"><?php echo __('Service name'); ?></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($all_hosts as $host): ?>
                                <?php
                                //Better performance, than run all the Hash::extracts if not necessary
                                $hasEditPermission = false;
                                if ($hasRootPrivileges === true):
                                    $hasEditPermission = true;
                                else:
                                    if ($this->Acl->isWritableContainer($host['Container'])):
                                        $hasEditPermission = true;
                                    endif;
                                endif;
                                ?>
                                <tr>
                                    <td class="bg-color-lightGray" colspan="2">
                                        <i class="fa fa-desktop"></i>
                                        &nbsp;
                                        <?php if ($this->Acl->hasPermission('browser', 'hosts')): ?>
                                            <a class="padding-left-5 txt-color-blueDark"
                                               href="/hosts/browser/<?php echo $host['Host']['id']; ?>"><?php echo h($host['Host']['name']); ?>
                                                (<?php echo h($host['Host']['address']); ?>)</a>
                                        <?php else: ?>
                                            <?php echo h($host['Host']['name']); ?>
                                        <?php endif; ?>
                                        <?php if ($this->Acl->hasPermission('serviceList', 'services')): ?>
                                            <a class="pull-right txt-color-blueDark"
                                               href="/services/serviceList/<?php echo $host['Host']['id']; ?>"><i
                                                        class="fa fa-list"
                                                        title="<?php echo __('Go to Service list'); ?>"></i></a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php foreach ($all_services[$host['Host']['id']] as $service): ?>
                                    <tr>
                                        <?php
                                        if ($service['Service']['name'] !== null && $service['Service']['name'] !== ''):
                                            $service_name = h($service['Service']['name']);
                                        else:
                                            $service_name = h($service['Servicetemplate']['template_name']);
                                        endif;
                                        ?>
                                        <td class="text-center" style="width: 15px;">
                                            <?php if ($this->Acl->hasPermission('edit', 'services') && $hasEditPermission): ?>
                                                <input type="checkbox" class="massChange"
                                                       servicename="<?php echo $service_name; ?>"
                                                       value="<?php echo $service['Service']['id']; ?>"/>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if ($this->Acl->hasPermission('browser', 'services')): ?>
                                                <a href="/services/browser/<?php echo $service['Service']['id']; ?>"><?php echo $service_name; ?></a>
                                            <?php else: ?>
                                                <?php echo $service_name; ?>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                        <?php if (empty($all_services)): ?>
                            <div class="noMatch">
                                <center>
                                    <span class="txt-color-red italic"><?php echo __('This service template is not used by any service'); ?></span>
                                </center>
                            </div>
                        <?php endif; ?>
                        <div class="padding-top-10"></div>
                        <?php echo $this->element('servicetemplate_mass_changes'); ?>
                        <div class="padding-top-10"></div>

                    </div>
                </div>
            </div>
    </div>
</section>