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
	<!-- Widget ID (each widget will need unique ID)-->
	<div class="jarviswidget jarviswidget-sortable host-status-list-body">
		<header class="status_datatable_header">
			<span class="widget-icon"><i class="fa fa-gears"></i></span>
			<h2>
				<?php echo __('Host Status');?>
				<i class="fa fa-pause pointer padding-left-10"></i>
				<i class="fa fa-arrow-left pointer "></i>
				<i class="fa fa-arrow-up pointer "></i>
			</h2>
			<div class="widget-toolbar host-status-list-toolbar">
				Hosts per page: <input class="form-control hosts-per-page" type="text" value="3">
				Refresh interval (min): <input class="form-control refresh-interval" type="text" value="3">
			</div>
		</header>
		<div class="no-padding status_datatable_header">
			<div class="slider-slim">
				<div class="pagingInterval">Paging interval (secs): <div class="pagingIntervalValue"></div></div>
				<input class="slider slider-primary" data-slider-min="3" data-slider-max="10" data-slider-value="5" />
			</div>
			<div class="host_list_save">Save</div>
			<div class="widget-toolbar" role="menu">
				<i class="fa fa-square up"></i> <input type="checkbox" class="filter-up" checked />
				<i class="fa fa-square down"></i> <input type="checkbox"  class="filter-down" checked/>
				<i class="fa fa-square unreachable"></i> <input type="checkbox"  class="filter-unreachable" checked/>
				<span><i class="fa fa-link padding-left-5"></i></span>
				<i class="fa fa-user padding-left-5"></i> <input type="checkbox"  class="filter-acknowledged"/>
				<i class="fa fa-power-off padding-left-5"></i> <input type="checkbox"  class="filter-downtime"/>
			</div>
		</div>
		<!-- widget div-->
		<div class="no-padding">
			<!-- widget content -->
			<div class="status_datatable_widget no-padding font-xs">
				<table class="statusListHosts status_datatable table table-bordered" animation="animated ">
					<thead>
						<tr>
							<th><?php echo __('State'); ?></th>
							<th></th>
							<th></th>
							<th><?php echo __('Host'); ?></th>
							<th><?php echo __('Status since'); ?></th>
						</tr>
					</thead>
				</table>
			</div>
			<!-- end widget content -->
		</div>
		<!-- end widget div -->
	</div>
</div>
