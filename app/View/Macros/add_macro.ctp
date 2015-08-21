<?php
// Copyright (C) <2015>  <it-novum GmbH>
// 
// This file is dual licensed
// 1.
//     This program is free software: you can redistribute it and/or modify
//     it under the terms of the GNU General Public License as published by
//     the Free Software Foundation, version 3 of the License
// 
//     This program is distributed in the hope that it will be useful,
//     but WITHOUT ANY WARRANTY; without even the implied warranty of
//     MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//     GNU General Public License for more details.
// 
//     You should have received a copy of the GNU General Public License
//     along with this program.  If not, see <http://www.gnu.org/licenses/>.
// 2.
//     If you purchased a openITCOCKPIT 'License key' you can use this file
//     under the terms of the it-novum licence
//     You can find a copy of the licence at
//     /usr/share/openitcockpit/commercial/it-novum-LICENCE.txt
//     on your system

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
<tr>
	<?php if($macroCount <= 256): ?>
		<td class="text-primary"><?php echo $newMacro; ?></td>
		<td>
			<div class="form-group">
				<input type="hidden" value="<?php echo $newMacro; ?>" macro="name" uuid="<?php echo $uuid; ?>" name="data[<?php echo $uuid; ?>][Macro][name]" />
				<input class="form-control systemsetting-input" type="text" maxlength="255" name="data[<?php echo $uuid; ?>][Macro][value]">
			</div>
		</td>
		<td>
			<input class="form-control systemsetting-input" type="text" maxlength="255" name="data[<?php echo $uuid; ?>][Macro][description]">
		</td>
		<td><a style="padding: 6px;" class="btn btn-default btn-sx txt-color-red deleteMacro" href="javascript:void(0);"><i class="fa fa-trash-o fa-lg"></i></a></td>
	<?php else: ?>
		<td class="txt-color-red"><?php echo $newMacro; ?></td>
		<td>
			<span class="txt-color-red"><?php echo __('the maximum number of 256 macros is exceeded'); ?></span>
		</td>
		<td><a style="padding: 6px;" class="btn btn-default btn-sx txt-color-red deleteMacro" href="javascript:void(0);"><i class="fa fa-trash-o fa-lg"></i></a></td>
	<?php endif; ?>
</tr>