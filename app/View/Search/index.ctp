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
            <i class="fa fa-search fa-fw "></i>
            <?php echo __('Search'); ?>
        </h1>
    </div>
</div>


<div class="jarviswidget" id="wid-id-0">
    <header>
        <div class="widget-toolbar" role="menu">
            <?php echo $this->Utils->backButton(__('Back'), $backUrl); ?>
        </div>
        <span class="widget-icon"> <i class="fa fa-search"></i> </span>
        <h2><?php echo __('Search...'); ?></h2>
        <ul class="nav nav-tabs pull-left padding-left-10" id="widget-tab-1">
            <li class="active">
                <a href="#tab1" data-toggle="tab"> <?php echo __('default'); ?> </a>
            </li>
            <li class="">
                <a href="#tab3" data-toggle="tab"><?php echo __('by address'); ?></a>
            </li>
            <li class="">
                <a href="#tab4" data-toggle="tab"><?php echo __('by macro'); ?></a>
            </li>
            <li class="">
                <a href="#tab5" data-toggle="tab"><?php echo __('by UUID'); ?></a>
            </li>
        </ul>
    </header>
    <div>
        <div role="content">
            <div class="widget-body no-padding">
                <!-- widget body text-->
                <div class="tab-content padding-10">
                    <div id="tab1" class="tab-pane fade active in">
                        <?php
                        echo $this->Form->create('SearchDefault', [
                            'class' => 'form-horizontal clear',
                        ]);
                        ?>
                        <div class="row">
                            <div class="col-xs-12 col-md-9 col-lg-7">
                                <strong><i class="fa fa-desktop"></i> <?php echo __('Search for hosts'); ?>:</strong>
                                <br/>
                                <?php
                                echo $this->Form->input('Hostname', [
                                    'label' => __('Host Name'),
                                    'help'  => __('This is a wildcard search, you don\'t need to add * or %'),
                                ]);


                                echo $this->Form->fancyCheckbox('Hoststatus.0', [
                                    'caption'          => __('Up'),
                                    'wrapGridClass'    => 'col col-md-10',
                                    'captionGridClass' => 'col col-md-2 no-padding',
                                    'captionClass'     => 'control-label no-padding',
                                    'checked'          => true,
                                    'icon'             => '<i class="fa fa-square txt-color-greenLight"></i> ',
                                    'value'            => 1,
                                ]);

                                echo $this->Form->fancyCheckbox('Hoststatus.1', [
                                    'caption'          => __('Down'),
                                    'wrapGridClass'    => 'col col-md-10',
                                    'captionGridClass' => 'col col-md-2 no-padding',
                                    'captionClass'     => 'control-label no-padding',
                                    'checked'          => true,
                                    'icon'             => '<i class="fa fa-square txt-color-redLight"></i> ',
                                    'value'            => 1,
                                ]);

                                echo $this->Form->fancyCheckbox('Hoststatus.2', [
                                    'caption'          => __('Unreachable'),
                                    'wrapGridClass'    => 'col col-md-10',
                                    'captionGridClass' => 'col col-md-2 no-padding',
                                    'captionClass'     => 'control-label no-padding',
                                    'checked'          => true,
                                    'icon'             => '<i class="fa fa-square txt-color-blueDark"></i> ',
                                    'value'            => 1,
                                ]);
                                ?>
                            </div>
                        </div>
                        <div class="padding-top-10"> <!-- spacer for nice layout --></div>
                        <div class="padding-top-20"> <!-- spacer for nice layout --></div>
                        <div class="row">
                            <div class="col-xs-12 col-md-9 col-lg-7">
                                <strong><i class="fa fa-cog"></i> <?php echo __('Search for services'); ?>:</strong>
                                <br/>
                                <?php
                                echo $this->Form->input('Servicename', [
                                    'label' => __('Servicename'),
                                    'help'  => __('This is a wildcard search, you don\'t need to add * or %'),
                                ]);


                                echo $this->Form->fancyCheckbox('Servicestatus.0', [
                                    'caption'          => __('Ok'),
                                    'wrapGridClass'    => 'col col-md-10',
                                    'captionGridClass' => 'col col-md-2 no-padding',
                                    'captionClass'     => 'control-label no-padding',
                                    'checked'          => true,
                                    'icon'             => '<i class="fa fa-square txt-color-greenLight"></i> ',
                                    'value'            => 1,
                                ]);

                                echo $this->Form->fancyCheckbox('Servicestatus.1', [
                                    'caption'          => __('Warning'),
                                    'wrapGridClass'    => 'col col-md-10',
                                    'captionGridClass' => 'col col-md-2 no-padding',
                                    'captionClass'     => 'control-label no-padding',
                                    'checked'          => true,
                                    'icon'             => '<i class="fa fa-square txt-color-orange"></i> ',
                                    'value'            => 1,
                                ]);

                                echo $this->Form->fancyCheckbox('Servicestatus.2', [
                                    'caption'          => __('Critical'),
                                    'wrapGridClass'    => 'col col-md-10',
                                    'captionGridClass' => 'col col-md-2 no-padding',
                                    'captionClass'     => 'control-label no-padding',
                                    'checked'          => true,
                                    'icon'             => '<i class="fa fa-square txt-color-redLight"></i> ',
                                    'value'            => 1,
                                ]);

                                echo $this->Form->fancyCheckbox('Servicestatus.3', [
                                    'caption'          => __('Unknown'),
                                    'wrapGridClass'    => 'col col-md-10',
                                    'captionGridClass' => 'col col-md-2 no-padding',
                                    'captionClass'     => 'control-label no-padding',
                                    'checked'          => true,
                                    'icon'             => '<i class="fa fa-square txt-color-blueDark"></i> ',
                                    'value'            => 1,
                                ]);
                                ?>
                            </div>
                        </div>
                        <div class="padding-top-20"> <!-- spacer for nice layout --></div>
                        <?php echo $this->Form->formActions(__('Search'), ['cancelButton' => false]); ?>
                    </div>


                    <div id="tab3" class="tab-pane fade">
                        <?php
                        echo $this->Form->create('SearchAddress', [
                            'class' => 'form-horizontal clear',
                        ]);
                        ?>
                        <div class="row">
                            <div class="col-xs-12 col-md-9 col-lg-7">
                                <strong><i class="fa fa-desktop"></i> <?php echo __('Search for hosts'); ?>:</strong>
                                <br/>
                                <?php
                                echo $this->Form->input('Hostaddress', [
                                    'label' => __('Hostaddress'),
                                    'help'  => __('This is a wildcard search, you don\'t need to add * or %. Example: 172.16. or openitcockpit.org'),
                                ]);
                                ?>
                            </div>
                        </div>
                        <div class="padding-top-20"> <!-- spacer for nice layout --></div>
                        <?php echo $this->Form->formActions(__('Search'), ['cancelButton' => false]); ?>
                    </div>


                    <div id="tab4" class="tab-pane fade">
                        <?php
                        echo $this->Form->create('SearchMacros', [
                            'class' => 'form-horizontal clear',
                        ]);
                        ?>
                        <div class="row">
                            <div class="col-xs-12 col-md-9 col-lg-7">
                                <strong><i class="fa fa-desktop"></i> <?php echo __('Search for host macros'); ?>
                                    :</strong>
                                <br/>
                                <?php
                                echo $this->Form->input('Hostmacro', [
                                    'label' => __('Host macro'),
                                    'help'  => __('This is a wildcard search, you don\'t need to add * or %.'),
                                ]);
                                ?>
                            </div>
                        </div>
                        <div class="padding-top-10"> <!-- spacer for nice layout --></div>
                        <div class="padding-top-20"> <!-- spacer for nice layout --></div>
                        <div class="row">
                            <div class="col-xs-12 col-md-9 col-lg-7">
                                <strong><i class="fa fa-cog"></i> <?php echo __('Search for service macros'); ?>
                                    :</strong>
                                <br/>
                                <?php
                                echo $this->Form->input('Servicemacro', [
                                    'label' => __('Service macro'),
                                    'help'  => __('This is a wildcard search, you don\'t need to add * or %.'),
                                ]);

                                ?>
                            </div>
                        </div>
                        <div class="padding-top-20"> <!-- spacer for nice layout --></div>
                        <?php echo $this->Form->formActions(__('Search'), ['cancelButton' => false]); ?>
                    </div>


                    <div id="tab5" class="tab-pane fade">
                        <?php
                        echo $this->Form->create('SearchUuid', [
                            'class' => 'form-horizontal clear',
                        ]);
                        ?>
                        <div class="row">
                            <div class="col-xs-12 col-md-9 col-lg-7">
                                <strong><i class="fa fa-cube"></i> <?php echo __('Search for object'); ?>:</strong>
                                <br/>
                                <?php
                                echo $this->Form->input('UUID', [
                                    'label' => __('UUID'),
                                ]);
                                ?>
                            </div>
                        </div>
                        <div class="padding-top-20"> <!-- spacer for nice layout --></div>
                        <?php echo $this->Form->formActions(__('Search'), ['cancelButton' => false]); ?>
                    </div>


                </div>
            </div>
        </div>
    </div>
</div>
