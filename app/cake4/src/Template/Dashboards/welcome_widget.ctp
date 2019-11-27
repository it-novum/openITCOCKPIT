<?php
// Copyright (C) <2015>  <it-novum GmbH>
//
// This file is dual licensed
//
// 1.
//  This program is free software: you can redistribute it and/or modify
//  it under the terms of the GNU General Public License as published by
//  the Free Software Foundation, version 3 of the License.
//
//  This program is distributed in the hope that it will be useful,
//  but WITHOUT ANY WARRANTY; without even the implied warranty of
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//  GNU General Public License for more details.
//
//  You should have received a copy of the GNU General Public License
//  along with this program.  If not, see <http://www.gnu.org/licenses/>.
//
// 2.
//  If you purchased an openITCOCKPIT Enterprise Edition you can use this file
//  under the terms of the openITCOCKPIT Enterprise Edition license agreement.
//  License agreement and license key will be shipped with the order
//  confirmation.
?>


<div class="pull-left">
    <?php
    if ($this->Auth->user('image') != null && $this->Auth->user('image') != ''):
        if (file_exists(WWW_ROOT . 'userimages' . DS . $this->Auth->user('image'))):
            $img = '/userimages' . DS . $this->Auth->user('image');
        else:
            $img = '/img/fallback_user.png';
        endif;
    else:
        $img = '/img/fallback_user.png';
    endif;

    echo $this->html->image($img, [
        'width'  => 120,
        'height' => 'auto',
        'id'     => 'userImage',
        'style'  => 'border-left: 3px solid #40AC2B;'
    ]);

    ?>
</div>
<div class="pull-left col-md-7">
    <strong>{{hostCount}}</strong ng-if="hostCount"> <?php echo __('hosts are monitored'); ?>

    <br/>
    <strong>{{serviceCount}}</strong> <?php echo __('services are monitored'); ?>
    <br/>
    <br/>
    <?php echo __('Your selected Timezone is '); ?>
    <strong><?php echo h($this->Auth->user('timezone')); ?></strong>
    <?php if ($this->Auth->user('timezone') !== date_default_timezone_get()): ?>
        <br/>
        <?php echo __('Server timezone is:'); ?>
        <strong><?php echo date_default_timezone_get(); ?></strong>
    <?php endif; ?>
</div>

