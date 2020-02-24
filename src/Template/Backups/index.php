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
            <i class="fa fa-database fa-fw "></i>
            <?php echo __('Backup / Restore'); ?>
        </h1>
    </div>
</div>
<div class="jarviswidget" id="wid-id-0">
    <header>
        <h2><?php echo __('Backup management'); ?></h2>
    </header>
    <div>
        <div class="widget-body">
            <div class="row">
                <div id="backupSuccessfully" class="col-xs-10 padding-top-20" style="display:none;">
                    <div class="alert alert-success alert-block">
                        <h4 class="alert-heading"><i class="fa fa-check"></i> <?php echo __('Success'); ?>
                        </h4>
                        <div id="successMessage"></div>
                    </div>
                </div>
                <div id="backupError" class="col-xs-10 padding-top-20" style="display:none;">
                    <div class="alert alert-danger alert-block">
                        <h4 class="alert-heading"><i class="fa fa-close"></i> <?php echo __('Error'); ?>
                        </h4>
                        <div id="errorMessage"></div>
                    </div>
                </div>
                <div id="backupWarning" class="col-xs-10 padding-top-20" style="display:none;">
                    <div class="alert alert-warning alert-block">
                        <h4 class="alert-heading"><i class="fa fa-warning"></i> <?php echo __('Warning'); ?>
                        </h4>
                        <div id="warningMessage"></div>
                    </div>
                </div>
            </div>
            <?php
            echo $this->Form->input('backupfile', [
                    'options'   => $backup_files,
                    'multiple'  => false,
                    'class'     => 'chosen',
                    'style'     => 'width: 80%',
                    'label'     => ['text' => __('Backupfile for Restore'), 'class' => 'col col-xs-2 col-md-2 col-lg-2'],
                    'wrapInput' => 'col col-xs-8 col-md-8 col-lg-8',
                ]
            );

            ?>
            <br><br>
            <div class="row">
                <span class="col col-md-2 hidden-tablet hidden-mobile"><!-- spacer for nice layout --></span>
                <?php
                echo "<div class='col col-xs-6 col-md-6 col-lg-6'> </div>";
                echo "<div class=' col col-xs-2 col-md-2 col-lg-2'><div class='pull-right'>";
                ?>
                <a href="javascript:void(0);" id="delete" class="btn btn-primary"><?php echo __('Delete file'); ?></a>
                <a href="javascript:void(0);" id="restore"
                   class="btn btn-primary"><?php echo __('Start Restore'); ?></a>
            </div>
        </div>
    </div>
</div>
<hr>
<div class="widget-body">
    <?php
    echo $this->Form->input('filenameForBackup', [
            'label'     => ['text' => __('Filename for Backup'), 'class' => 'col col-xs-2 col-md-2 col-lg-2'],
            'style'     => 'width: 100%',
            'value'     => 'mysql_oitc_bkp',
            'wrapInput' => 'col col-xs-8 col-md-8 col-lg-8',
        ]
    );
    ?>
    <br><br>
    <div class="row">
        <span class="col col-md-2 hidden-tablet hidden-mobile"><!-- spacer for nice layout --></span>
        <?php

        echo "<div class='col col-xs-6 col-md-6 col-lg-6'> </div>";
        echo "<div class=' col col-xs-2 col-md-2 col-lg-2'><div class='pull-right'>";
        ?>
        <a href="javascript:void(0);" id="backup" class="btn btn-primary"><?php echo __('Start Backup'); ?></a>
    </div>
</div>

<br><br>
<hr>
<div class="col-xs-2">
    State of choosen Action
</div>
<div class="col-xs-8">
    <div class="well" id="backupLog"></div>
</div>
<br><br><br><br>
</div>
</div>
