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
            <?php echo __('Commands'); ?>
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
                    <h2><?php echo __('Command'); ?>
                        <strong><?php echo h($commandName); ?></strong> <?php echo __('is used by the following service templates'); ?>
                        (<?php echo sizeof($servicestemplates); ?>):</h2>

                </header>

                <div>

                    <div class="widget-body no-padding">
                        <table id="host_list" class="table table-striped table-hover table-bordered smart-form"
                               style="">
                            <thead>
                            <tr>
                                <?php $order = $this->Paginator->param('order'); ?>
                                <th class="no-sort" style="width: 15px;"><i class="fa fa-check-square-o fa-lg"></i></th>
                                <th class="no-sort"><?php echo __('Service template name'); ?></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($servicestemplates as $servicestemplate): ?>
                                <tr>
                                    <td class="text-center" style="width: 15px;">
                                        <input type="checkbox" class="massChange"
                                               servicename="<?php echo h($servicestemplate['Servicetemplate']['name']); ?>"
                                               value="<?php echo $servicestemplate['Servicetemplate']['id']; ?>"/>
                                    </td>
                                    <td>
                                        <a href="/servicetemplates/edit/<?php echo $servicestemplate['Servicetemplate']['id']; ?>"><?php echo h($servicestemplate['Servicetemplate']['name']); ?></a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                        <?php if (empty($servicestemplates)): ?>
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