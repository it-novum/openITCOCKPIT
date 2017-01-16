<?php
// Copyright (C) <2017>  <it-novum GmbH>
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
            <i class="fa fa-star fa-fw "></i>
            <?= __('OAuth2'); ?>
            <span>
                <?= __('Overview'); ?>
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
                        <?php if ($this->Acl->hasPermission('add')): ?>
                            <?= $this->Html->link(__('New'), '/'.$this->params['plugin'].'/'.$this->params['controller'].'/add', ['class' => 'btn btn-xs btn-success', 'icon' => 'fa fa-plus']); ?>
                        <?php endif; ?>
                    </div>
                    <div class="jarviswidget-ctrls" role="menu">
                    </div>
                    <span class="widget-icon"> <i class="fa fa-star"></i> </span>
                    <h2><?= __('OAuth2 Client Connections') ?> </h2>
                </header>
                <!-- widget div-->
                <div>
                    <!-- widget edit box -->
                    <div class="jarviswidget-editbox">
                        <!-- This area used as dropdown edit box -->
                    </div>
                    <!-- end widget edit box -->
                    <!-- widget content -->
                    <div class="widget-body no-padding">
                        <table id="contactgroup_list" class="table table-striped table-bordered smart-form" style="">
                            <thead>
                            <tr>
                                <th class="no-sort"><?= __('Provider'); ?></th>
                                <th class="no-sort"><?= __('Client ID'); ?></th>
                                <th class="no-sort"><?= __('Show login page'); ?></th>
                                <th class="no-sort"><?= __('Active'); ?></th>
                                <th class="no-sort" style="width: 60px;"></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($allOAuth2Clients as $client): ?>
                                <tr>
                                    <td><?= $client['Oauth2client']['provider']; ?></td>
                                    <td><?= $client['Oauth2client']['client_id']; ?></td>
                                    <td>
                                        <i class="fa fa-<?= $client['Oauth2client']['show_login_page'] === '1' ? 'check' : 'close' ?>"></i>
                                    </td>
                                    <td>
                                        <i class="fa fa-<?= $client['Oauth2client']['active'] === '1' ? 'check' : 'close' ?>"></i>
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <?php if ($this->Acl->hasPermission('edit')): ?>
                                                <a href="/<?= $this->params['plugin'].'/'.$this->params['controller']; ?>/edit/<?= $client['Oauth2client']['id']; ?>"
                                                   class="btn btn-default">&nbsp;<i class="fa fa-cog"></i>&nbsp;</a>
                                            <?php else: ?>
                                                <a href="javascript:void(0);" class="btn btn-default">&nbsp;<i
                                                            class="fa fa-cog"></i>&nbsp;</a>
                                            <?php endif; ?>
                                            <a href="javascript:void(0);" data-toggle="dropdown"
                                               class="btn btn-default dropdown-toggle"><span class="caret"></span></a>
                                            <ul class="dropdown-menu">
                                                <?php if ($this->Acl->hasPermission('edit')): ?>
                                                    <li>
                                                        <a href="/<?= $this->params['plugin'].'/'.$this->params['controller']; ?>/edit/<?= $client['Oauth2client']['id']; ?>"><i
                                                                    class="fa fa-edit"></i> <?= __('Edit'); ?></a>
                                                    </li>
                                                <?php endif; ?>
                                                <?php if ($this->Acl->hasPermission('delete')): ?>
                                                    <li class="divider"></li>
                                                    <li>
                                                        <?= $this->Form->postLink('<i class="fa fa-trash-o"></i> '.__('Delete'), ['controller' => 'oauth2', 'action' => 'delete', $client['Oauth2client']['id']], ['class' => 'txt-color-red', 'escape' => false]); ?>
                                                    </li>
                                                <?php endif; ?>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>

                    </div>
                </div>
                <!-- end widget div -->
            </div>
            <!-- end widget -->
    </div>
    <!-- end row -->
</section>
<!-- end widget grid -->