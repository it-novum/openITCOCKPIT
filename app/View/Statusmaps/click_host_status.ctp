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
//debug($uuid);
$returnedHoststatusfield = $this->StatusMaps->hoststatusField($uuid);
$hostStatus = $this->StatusMaps->hoststatus($uuid);
//@TODO servicestatus
//debug($returnedHoststatusfield);
$returnedServiceStatus = [];
foreach ($servicestatus as $counter => $servicestate) {
	$returnedServiceStatus[$counter] = $this->StatusMaps->servicestatus($servicestate['Objects']['name2']);
}

$serviceAmount = count($servicestatus);
if(isset($returnedServiceStatus) && $serviceAmount > 0 ){
	$stateArr = [];
	foreach ($returnedServiceStatus as $key => $state) {
		$stateArr[$key] = $state['state'];
	}
	$cumulatedState = (int)max($stateArr);
}else{
	$cumulatedState = (int)$hostStatus['state'];
}
?>
<table class="table table-bordered popoverTable" style="padding:1px;">
	<tr>
		<th colspan="2" class="h6"><?php echo __('Host'); ?></th>
	</tr>
	<tr>
		<td class="col-md-3 col-xs-3"><?php echo __('Hostname'); ?></td>
		<td class="col-md-9 col-xs-9"><?php echo $returnedHoststatusfield[0]['name']; ?></td>
	</tr>
	<tr>
		<td class="col-md-3 col-xs-3"><?php echo __('description'); ?></td>
		<td class="col-md-9 col-xs-9"><?php echo $returnedHoststatusfield[0]['description']; ?></td>
	</tr>
	<tr>
		<td class="col-md-3 col-xs-3"><?php echo __('State (State Type)'); ?></td>
		<td class="col-md-9 col-xs-9 <?php echo $this->Status->HostStatusColorSimple($hostStatus['state'])['class']; ?> "><?php echo $hostStatus['human_state']; ?></td>
	</tr>
	<tr>
		<td class="col-md-3 col-xs-3"><?php echo __('Output'); ?></td>
		<td class="col-md-9 col-xs-9"><?php echo $returnedHoststatusfield['output']; ?></td>
	</tr>
	<tr>
		<td class="col-md-3 col-xs-3"><?php echo __('Perfdata'); ?></td>
		<td class="col-md-9 col-xs-9"><?php echo $returnedHoststatusfield['long_output']; ?></td>
	</tr>
	<tr>
		<td class="col-md-3 col-xs-3"><?php echo __('Current attempt'); ?></td>
		<td class="col-md-9 col-xs-9"><?php echo $returnedHoststatusfield['current_check_attempt'].'/'.$returnedHoststatusfield['max_check_attempts']; ?></td>
	</tr>
	<tr>
		<td class="col-md-3 col-xs-3"><?php echo __('Last Check'); ?></td>
		<td class="col-md-9 col-xs-9"><?php echo $returnedHoststatusfield['last_check']; ?></td>
	</tr>
	<tr>
		<td class="col-md-3 col-xs-3"><?php echo __('Next Check'); ?></td>
		<td class="col-md-9 col-xs-9"><?php echo $returnedHoststatusfield['next_check']; ?></td>
	</tr>
	<tr>
		<td class="col-md-3 col-xs-3"><?php echo __('Last State Change'); ?></td>
		<td class="col-md-9 col-xs-9"><?php echo $returnedHoststatusfield['last_state_change']; ?></td>
	</tr>
	<tr>
		<td class="col-md-3 col-xs-3"><?php echo __('Summary State'); ?></td>
		<?php if(isset($returnedServiceStatus) && $serviceAmount > 0): ?>
		<td class="col-md-9 col-xs-9 <?php echo $this->Status->ServiceStatusColorSimple($cumulatedState)['class']; ?>"> <?php echo $this->Status->ServiceStatusColorSimple($cumulatedState)['human_state']; ?></td>
		<?php else: ?>
			<td class="col-md-9 col-xs-9"> <?php echo __('No Summary State possible') ?></td>
		<?php endif; ?>
	</tr>
	<tr>
		<td class="col-md-3 col-xs-3"><?php echo __('Summary Output'); ?></td>
		<td class="col-md-9 col-xs-9"><?php echo $hostStatus['human_state']; ?>. There are <?php echo $serviceAmount ?> Services</td>
	</tr>
</table>

<?php if(isset($returnedServiceStatus) && $serviceAmount > 0) : ?>
<table class="table table-bordered popoverListTable">
	<tr>
		<th class="col-md-4 col-xs-3 h6"><?php echo __('Service Name'); ?></th>
		<th class="col-md-1 col-xs-1 h6"><?php echo __('State'); ?></th>
		<th class="col-md-7 col-xs-8 h6"><?php echo __('Output'); ?></th>
	</tr>
	<?php
		foreach ($servicestatus as $counter => $service) : ?>
		<tr>
			<td title="<?php
				if(isset($service['Service']['name']) && $service['Service']['name'] != ''){
					echo $service['Service']['name'];
				}else{
					echo $service['Servicetemplate']['name'];
				}
			?>">
			<?php
				if(isset($service['Service']['name']) && $service['Service']['name'] != ''){
					echo $service['Service']['name'];
				}else{
					echo $service['Servicetemplate']['name'];
				}
			?>
			</td>
			<td class="<?php echo $this->Status->ServiceStatusColorSimple($returnedServiceStatus[$counter]['state'])['class']; ?>"><?php echo $returnedServiceStatus[$counter]['human_state']; ?></td>
			<td title="<?php echo $service['Servicestatus']['output']; ?>"><?php echo $service['Servicestatus']['output']; ?></td>
		</tr>
	<?php endforeach; ?>
</table>
<?php endif; ?>