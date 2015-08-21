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
	<div class="col-xs-12 col-sm-12 col-md-5 col-lg-5">
		<h1 class="page-title <?php echo $this->Status->ServiceStatusColor($service['Service']['uuid']); ?>">
			<?php echo $this->Monitoring->serviceFlappingIcon($this->Status->sget($service['Service']['uuid'], 'is_flapping'), 'padding-left-5'); ?>
			<i class="fa fa-cog fa-fw"></i>
				<?php
				if($service['Service']['name'] !== null && $service['Service']['name'] !== ''){
					echo $service['Service']['name'];
				}else{
					echo $service['Servicetemplate']['name'];
				}
				?><span>
				&nbsp;<?php echo __('on'); ?>&nbsp;
				<a href="/hosts/browser/<?php echo $service['Host']['id']; ?>"><?php echo $service['Host']['name']; ?> (<?php echo $service['Host']['address']; ?>)</a>
			</span>
		</h1>
	</div>
	<div class="col-xs-12 col-sm-12 col-md-7 col-lg-7">
		<h5>
			<div class="pull-right">
				<a href="/services/browser/<?php echo $service['Service']['id']; ?>" class="btn btn-primary btn-sm"><i class="fa fa-arrow-circle-left"></i> <?php echo $this->Html->underline('b', __('Back to Service')); ?></a>
				<?php echo $this->element('service_browser_menu'); ?>
			</div>
		</h5>
	</div>
</div>

<iframe src="https://172.16.2.44/pnp4nagios/index.php/graph?host=da6defe4-3a44-4df2-8195-25de55ad9379&srv=623a365f-4850-4c6f-bb36-4633011e841c" frameborder="0" style="width: 100%;"></iframe>

<script type="text/javascript">
$('iframe').ready(function(){
	$('iframe').css('height', '3500px');
});
</script>
<?php
/*
* PNP4Nagios will be removed with one of the next versions.
* This view has no own javascript controller!!!!
*/
	
?>