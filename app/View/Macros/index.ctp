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
            <i class="fa fa-usd fa-fw "></i>
            <?php echo __('Monitoring'); ?>
            <span>>
                <?php echo __('User defined macros'); ?>
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
                        <button type="button" class="btn btn-xs btn-success addMacro" id="addNewMacro"><i
                                    class="fa fa-plus"></i> <?php echo __('New'); ?></button>
                    </div>
                    <div class="jarviswidget-ctrls" role="menu">
                    </div>
                    <span class="widget-icon hidden-mobile"> <i class="fa fa-usd"></i> </span>
                    <h2 class="hidden-mobile hidden-tablet"><?php echo __('User defined macros'); ?> </h2>

                </header>

                <!-- widget div-->

                <div>

                    <!-- widget content -->
                    <div class="widget-body no-padding">
                        <?php
                        echo $this->Form->create('Macro', [
                            'class' => 'weNeedNoClassHere',
                        ]);
                        ?>
                        <div class="mobile_table">
                            <table id="macrosTable" class="table table-striped table-bordered smart-form" style="">
                                <thead>
                                <tr>
                                    <?php $order = $this->Paginator->param('order'); ?>
                                    <th><?php echo $this->Utils->getDirection($order, 'Macro.name');
                                        echo $this->Paginator->sort('name', 'Name'); ?></th>
                                    <th><?php echo $this->Utils->getDirection($order, 'Macro.value');
                                        echo $this->Paginator->sort('value', 'Value'); ?></th>
                                    <th colspan="2"><?php echo $this->Utils->getDirection($order, 'Macro.description');
                                        echo $this->Paginator->sort('description', 'Description'); ?></th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                App::uses('UUID', 'Lib');
                                $i = 0;
                                foreach ($all_macros as $macro): ?>
                                    <tr>
                                        <td class="text-primary"><?php echo $macro['Macro']['name']; ?></td>
                                        <td>
                                            <div class="form-group">
                                                <?php
                                                // Generating a unique random key for the POST data array
                                                $uuid = sha1(UUID::v4());
                                                ?>
                                                <?php if (isset($macro['Macro']['id'])): ?>
                                                    <input class="form-control" type="hidden"
                                                           value="<?php echo $macro['Macro']['id']; ?>"
                                                           name="data[<?php echo $i; ?>][Macro][id]">
                                                <?php endif; ?>
                                                <input class="form-control" type="hidden" macro="name"
                                                       uuid="<?php echo $uuid; ?>"
                                                       value="<?php echo $macro['Macro']['name']; ?>"
                                                       name="data[<?php echo $i; ?>][Macro][name]">
                                                <input class="form-control systemsetting-input <?php echo ($macro['Macro']['password']) ? 'macroPassword' : ''; ?>"
                                                       type="text" maxlength="255"
                                                       value="<?php echo $macro['Macro']['value']; ?>"
                                                       name="data[<?php echo $i; ?>][Macro][value]">
                                            </div>
                                        </td>
                                        <td>
                                            <?php if (isset($macro['Macro']['id'])): ?>
                                                <input class="form-control systemsetting-input" type="text"
                                                       maxlength="255"
                                                       value="<?php echo $macro['Macro']['description']; ?>"
                                                       name="data[<?php echo $i; ?>][Macro][description]">
                                                <input class="form-control" type="hidden"
                                                       value="<?php echo (int)$macro['Macro']['password']; ?>"
                                                       name="data[<?php echo $i; ?>][Macro][password]">
                                            <?php else: ?>
                                                <input class="form-control systemsetting-input" type="text"
                                                       maxlength="255" value=""
                                                       name="data[<?php echo $i; ?>][Macro][description]">
                                                <input class="form-control" type="hidden" value="0"
                                                       name="data[<?php echo $i; ?>][Macro][password]">
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <a style="padding: 6px;"
                                               class="isPassword <?php echo (!$macro['Macro']['password']) ? 'txt-color-red' : 'txt-color-blue'; ?>"
                                               href="javascript:void(0);"><i
                                                        class="fa <?php echo (!$macro['Macro']['password']) ? 'fa-eye-slash' : 'fa-eye'; ?> fa-lg"></i></a>
                                            <a style="padding: 6px;"
                                               class="btn btn-default btn-sx txt-color-red deleteMacro"
                                               href="javascript:void(0);"><i class="fa fa-trash-o fa-lg"></i></a>
                                        </td>
                                    </tr>
                                    <?php $i++; ?>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <?php if (empty($all_macros)): ?>
                            <div class="noMatch">
                                <center>
                                    <span class="txt-color-red italic"><?php echo __('No entries match the selection'); ?></span>
                                </center>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <?php echo $this->Form->formActions(); ?>
            </div>
        </article>
    </div>
    <div class="row">
        <span class="col col-md-10 col-xs-12 txt-color-redLight"><i
                    class="fa fa-exclamation-circle"></i> <?php echo __('empty macros will be removed automatically'); ?></span>
        <span class="col col-md-10 col-xs-12 text-info"><i
                    class="fa fa-info-circle"></i> <?php echo __('Nagios supports up to 256 $USERx$ macros ($USER1$ through $USER256$)'); ?></span>
    </div>
</section>