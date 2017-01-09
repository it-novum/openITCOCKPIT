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
            <i class="fa fa-area-chart fa-fw"></i>
            <?php echo __('Monitoring'); ?>
            <span>>
                <?php echo __('Graph Collections'); ?>
			</span>
        </h1>
    </div>
</div>

<div class="overlay" style="display: none;">
    <div id="nag_longoutput_loader"
         style="position: absolute; top: 50%; left: 50%; margin-top: -29px; margin-left: -23px; z-index: 20; font-size: 40px; color: #fff;">
        <i class="fa fa-cog fa-lg fa-spin"></i>
    </div>
</div>

<section id="widget-grid" class="">
    <div class="row">
        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-1" data-widget-editbutton="false">
                <header>
                    <div class="widget-toolbar" role="menu">
                        <?php echo $this->Utils->backButton(); ?>
                    </div>
                    <span class="widget-icon hidden-mobile"> <i class="fa fa-area-chart"></i> </span>
                    <h2 class="hidden-mobile"><?php echo __('View Graph Collection'); ?></h2>
                </header>
                <div>
                    <div class="widget-body">
                        <div class="tab-content">
                            <div id="choose-collection">
                                <div class="padding-top-10"></div>

                                <?php echo $this->Form->create('GraphCollection', [
                                    'class' => 'form-horizontal clear',
                                ]) ?>

                                <div class="row">
                                    <div class="col-xs-12 col-md-9 col-lg-7">

                                        <?php

                                        echo $this->Form->input('id', [
                                            'options'   => $this->Html->chosenPlaceholder($collections),
                                            'selected'  => $id,
                                            'class'     => 'chosen',
                                            'wrapInput' => 'col col-xs-8',
                                            'label'     => __('Collections'),
                                        ]);

                                        ?>
                                    </div>
                                </div>
                                <?php echo $this->Form->end(); ?>
                            </div>

                            <div id="render-graph">

                            </div>
                            <div id="graph_data_tooltip"></div>
                        </div>
                    </div>
                </div>
            </div>
        </article>
    </div>
</section>
