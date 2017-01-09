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

/*
 *         _                    _               
 *   __ _ (_) __ ___  __ __   _(_) _____      __
 *  / _` || |/ _` \ \/ / \ \ / / |/ _ \ \ /\ / /
 * | (_| || | (_| |>  <   \ V /| |  __/\ V  V / 
 *  \__,_|/ |\__,_/_/\_\   \_/ |_|\___| \_/\_/  
 *      |__/                                    
*/
//Generating a unique random key for the POST data array
App::uses('UUID', 'Lib');
$uuid = sha1(UUID::v4());
?>
<?php if ($argumentsCount <= 32): ?>
    <div class="col-xs-12 padding-top-10">
        <div class="col-xs-1 text-primary">
            <?php echo $newArgument; ?>
        </div>
        <div class="col-xs-10">
            <label class="control-label"><?php echo __('Name'); ?></label>
            <input type="hidden" name="data[Commandargument][<?php echo $uuid; ?>][command_id]"
                   value="<?php echo $id; ?>"/>
            <input type="hidden" name="data[Commandargument][<?php echo $uuid; ?>][name]"
                   value="<?php echo $newArgument; ?>" uuid="<?php echo $uuid; ?>" argument="name"/>
            <input class="form-control" style="width:100%" type="text" uuid="<?php echo $uuid; ?>"
                   placeholder="<?php echo __('Please enter a name'); ?>"
                   name="data[Commandargument][<?php echo $uuid; ?>][human_name]" value=""/>
        </div>
        <div class="col-xs-1">
            <label><!-- just a spacer for a nice layout --> &nbsp;</label>
            <br/>
            <a class="btn btn-default btn-sx txt-color-red deleteCommandArg" href="javascript:void(0);"
               delete="<?php echo $uuid; ?>">
                <i class="fa fa-trash-o fa-lg"></i>
            </a>
        </div>
    </div>
<?php else: ?>
    <div class="col-xs-12 padding-top-10">
        <span class="txt-color-red"><?php echo __('the maximum number of 32 arguments is exceeded'); ?></span>
    </div>
<?php endif; ?>
