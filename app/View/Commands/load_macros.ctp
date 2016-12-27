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
<table id="macrosTable" class="table table-striped table-bordered smart-form" style="">
    <thead>
    <tr>
        <th><?php echo __('Name'); ?></th>
        <th><?php echo __('Value'); ?></th>
        <th><?php echo __('Description'); ?></th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($all_macros as $macro): ?>
        <tr>
            <td class="text-primary"><?php echo $macro['Macro']['name']; ?></td>
            <td><?php echo $macro['Macro']['value']; ?></td>
            <td><?php echo $macro['Macro']['description']; ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>