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
            <i class="fa fa-location-arrow fa-fw "></i>
            <?php echo __('Monitoring'); ?>
            <span>>
                <?php echo __('Locations'); ?>
            </span>
            <div class="third_level"> <?php echo ucfirst($this->params['action']); ?></div>
        </h1>
    </div>
</div>
<div id="error_msg"></div>

<div class="jarviswidget" id="wid-id-0">
    <header>
        <span class="widget-icon"> <i class="fa fa-location-arrow"></i> </span>
        <h2><?php echo $this->action == 'edit' ? 'Edit' : 'Add' ?><?php echo __('Locations'); ?></h2>
        <div class="widget-toolbar" role="menu">
            <?php echo $this->Utils->backButton() ?>
        </div>
    </header>
    <div>
        <div class="widget-body">
            <?php
            echo $this->Form->create('Location', [
                'class' => 'form-horizontal clear',
            ]);
            echo $this->Form->input('Container.parent_id', ['options' => $container, 'class' => 'chosen', 'style' => 'width: 100%', 'label' => __('Container')]);
            echo $this->Form->input('Container.name', ['label' => __('Name')]);
            echo $this->Form->input('description', ['label' => __('Description')]);
            echo $this->Form->input('timezone', ['options' => CakeTime::listTimezones(), 'class' => 'chosen', 'style' => 'width: 100%;', 'label' => __('Timezone')]);
            ?>
            <div id="locationPonts">
                <?php
                echo $this->Form->input('latitude', [
                    'label' => __('Latitude'),
                    'type'  => 'text'
                ]);
                echo $this->Form->input('longitude', [
                    'label' => __('Longitude'),
                    'type'  => 'text'
                ]);
                ?>
            </div>
            <div class="form-group has-error" id="LatitudeRangeError" style="display:none;">
                <div class="col col-xs-10 col-xs-offset-2 required">
                    <span class="help-block text-danger"><?php echo __('Latitude or Longitude is out of range'); ?></span>
                </div>
            </div>
            <br/><br/>
            <div id="mapDiv" class="vector-map"></div>
            <br/>
            <?php echo $this->Form->formActions(); ?>
        </div>
    </div>
</div>