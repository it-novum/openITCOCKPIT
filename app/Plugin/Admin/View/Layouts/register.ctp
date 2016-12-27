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
<!DOCTYPE html>
<html lang="en">
<head>
    <!--[if IE]>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <![endif]-->
    <?php echo $this->Html->charset(); ?>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script data-pace-options='{ "ajax": false }' src='/smartadmin/js/plugin/pace/pace.min.js'></script>
    <title>
        <?php echo $title_for_layout; ?> - <?php echo Configure::read('general.site_name') ?>
    </title>
    <?php
    echo $this->Html->meta('icon');
    echo $this->element('assets');
    ?>
</head>
<body class="">
<?php
echo $this->Html->script('vendor/endcredits/endcredits.js');
echo $this->Html->css('vendor/endcredits/endcredits.css');
echo $this->element('credits');
?>

<?php echo $this->element('Admin.layout/header') ?>
<?php echo $this->element('Admin.layout/sidebar') ?>
<div id="uglyDropdownMenuHack"></div>
<div id="main" role="main">
    <div id="ribbon" class="hidden-mobile hidden-tablet">
        <span class="ribbon-button-alignment"></span>
        <ol class="breadcrumb">
            <li>
                <a href="<?php Router::url('/'); ?>"><i class="fa fa-home"></i> <?php echo __('Home'); ?></a>
            </li>
            <li>
                <a href="javascript:void(0);"><i class="fa fa-cube"></i> <?php echo $title_for_layout ?></a>
            </li>
        </ol>
        <span id="global_ajax_loader"><i class="fa fa-refresh fa-spin"></i> <?php echo __('Loading data'); ?></span>
    </div>

    <div id="content" style="opacity: 1;">
        <div class="controller <?php echo $this->name ?>_<?php echo $this->action ?>">
            <?php echo $this->Session->flash(); ?>
            <?php echo $this->Session->flash('auth'); ?>
            <?php echo $content_for_layout; ?>
            <?php echo $this->element('Admin.sql_dump'); ?>
        </div>
    </div>
</div>
<?php
// Gibt das div der Tastenkombinationen aus
echo $this->element('shortcuts');
?>
</body>
</html>