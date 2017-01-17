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
<div class="widget-body">
    <?php if (empty($parentOutages)): ?>
        <div class="text-center text-success padding-10">
            <h3 class="padding-top-20">
                <i class="fa fa-check"></i>
                <?php echo __('Currently are no network segment issues'); ?>
            </h3>
        </div>
    <?php else: ?>
        <div style="overflow:auto;">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <tbody>
                    <?php foreach ($parentOutages as $host): ?>
                        <tr>
                            <?php $class = 'text-danger';
                            if ($host['Hoststatus']['current_state'] == 2):
                                $class = 'txt-color-blueDark';
                            endif; ?>
                            <td title="<?php echo h($host['Hoststatus']['output']); ?>">
                                <a class="<?php echo $class; ?>"
                                   href="/hosts/browser/<?php echo $host['Host']['id']; ?>">
                                    <?php echo h($host['Host']['name']); ?>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php endif; ?>
</div>
