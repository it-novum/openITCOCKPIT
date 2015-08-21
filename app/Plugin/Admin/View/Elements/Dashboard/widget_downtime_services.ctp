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
<?php if(empty($servicesInDowntime)): ?>
	<div class="text-center text-success padding-top-50">
		<h3><i class="fa fa-check"></i> <?php echo __('Currently are no services in scheduled downtime');?></h3>
	</div>
<?php else: ?>
	<div class="table-responsive">
		<table class="table table-bordered table-striped">
			<tbody>
				<?php foreach($servicesInDowntime as $service): ?>
					<tr>
						<td><a href="/services/browser/<?php echo $service['Service']['id']; ?>">
							<?php
							if($service['Service']['name'] !== null && $service['Service']['name'] !== ''):
								echo h($service['Service']['name']);
							else:
								echo h($service['Servicetemplate']['name']);
							endif;
							?>
						</a></td>
					</tr>
				<?php endforeach;?>
			</tbody>
		</table>
	</div>
<?php endif;?>
</div>
