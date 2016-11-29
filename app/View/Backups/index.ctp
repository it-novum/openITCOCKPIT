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
<?php $this->Paginator->options(array('url' => $this->params['named'])); ?>
<div class="row">
        <div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
                <h1 class="page-title txt-color-blueDark">
                        <i class="fa fa-database fa-fw"></i>
                                <?php echo __('Administration'); ?>
                        <span>>
                                <?php echo __('Backup / Restore'); ?>
                        </span>
                </h1>
        </div>
</div>

<div id="error_msg"></div>

<div class="jarviswidget" id="wid-id-0">
    <header>
        <h2><?php echo __('Backupmanagement'); ?></h2>
    </header>
    <div>
        <div class="widget-body">
            <?php
            echo $this->Form->create('Backup', array('url' => 'restore'));

            echo $this->Form->input('backupfile', [
                    'options' => $backupfiles,
                    'multiple' => false,
                    'class' => 'chosen',
                    'style' => 'width: 80%',
                    'label' => ['text' => __('Backupfile for Restore'), 'class' => 'col col-xs-2 col-md-2 col-lg-2'],
                    'wrapInput' => 'col col-xs-8 col-md-8 col-lg-8',
                ]
            );

            ?>
            <br><br>
            <div class="row">
                <span class="col col-md-2 hidden-tablet hidden-mobile"><!-- spacer for nice layout --></span>
                <?php
                $options_restore = array(
                    'class' => 'btn btn-primary',
                    'style' => 'submit'
                );
                echo "<div class='col col-xs-6 col-md-6 col-lg-6'> </div>";
                echo "<div class=' col col-xs-2 col-md-2 col-lg-2'><div class='pull-right'>";
                echo $this->Form->button('Start Restore', $options_restore);
                echo "</div>";
                ?>
            </div>
        </div>
    </div>
    <hr>
    <div class="widget-body">
        <label class="col col-xs-2 col-md-2 col-lg-2" for="CreateBackup">Create new Backup</label>
        <?php
        $options_backup = array(
            'class' => 'btn btn-primary',
            'formaction' => Router::url(array('controller' => 'backups', 'action' => 'backup'))
        );
        echo "<div class='col col-xs-6 col-md-6 col-lg-6'> </div>";
        echo "<div class=' col col-xs-2 col-md-2 col-lg-2'><div class='pull-right'>";
            echo $this->Form->button('Start Backup', $options_backup);
        echo "</div>";
        ?>
    </div>
</div>
</div>
