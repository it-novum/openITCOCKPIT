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
<head>

<?php
$css = [
	'css/vendor/bootstrap/css/bootstrap.css',
	//'css/vendor/bootstrap/css/bootstrap-theme.css',
	'smartadmin/css/font-awesome.css',
	'smartadmin/css/smartadmin-production.css',
	'smartadmin/css/your_style.css',
	'css/app.css',
	'css/bootstrap_pdf.css'
];
?>

<?php
foreach($css as $cssFile): ?>
	<!-- <link rel="stylesheet" type="text/css" href="<?php // echo WWW_ROOT.$cssFile; ?>" /> -->
<?php endforeach; ?>

</head>
<body>
	<div class="well">
		<div class="row margin-top-10 font-lg no-padding">
			<div class="col-md-9 text-left padding-left-10">
				<i class="fa fa-sitemap txt-color-blueDark padding-left-10"></i>
				<?php echo __('Servicegroups'); ?>
			</div>
			<div class="col-md-3 text-left">
				<img src="/img/logo.png" width="200" />
			</div>
		</div>
		<div class="row padding-left-10 margin-top-10 font-sm">
			<div class="text-left padding-left-10">
				<i class="fa fa-calendar txt-color-blueDark"></i> <?php echo date('F d, Y H:i:s'); ?>
			</div>
		</div>
		<div class="row padding-left-10 margin-top-10 font-sm">
			<div class="text-left padding-left-10">
				<i class="fa fa-list-ol txt-color-blueDark"></i> <?php echo __('Number of Servicegroups: '.$servicegroupCount); ?>
			</div>
		</div>
		<div class="row padding-left-10 margin-top-10 font-sm">
			<div class="text-left padding-left-10">
				<i class="fa fa-list-ol txt-color-blueDark"></i> <?php echo __('Number of Hosts: '.$hostCount); ?>
			</div>
		</div>
		<div class="row padding-left-10 margin-top-10 font-sm">
			<div class="text-left padding-left-10">
				<i class="fa fa-list-ol txt-color-blueDark"></i> <?php echo __('Number of Services: '.$serviceCount); ?>
			</div>
		</div>
		<div class="padding-top-10">
			<table id="" class="table table-striped table-bordered smart-form" style="">
				<thead>
					<tr>
						<th style="font-size:x-small;"><?php echo __('Status'); ?></th>
						<th style="font-size:x-small;" class="no-sort text-center" ><i class="fa fa-user fa-lg"></i></th>
						<th style="font-size:x-small;" class="no-sort text-center" ><i class="fa fa-power-off fa-lg"></i></th>
						<th style="font-size:x-small;"><?php echo __('Servicename'); ?></th>
						<th style="font-size:x-small;"><?php echo __('Status since'); ?></th>
						<th style="font-size:x-small;"><?php echo __('Last check'); ?></th>
						<th style="font-size:x-small;"><?php echo __('Next check'); ?></th>
						<th style="font-size:x-small;"><?php echo __('Service output'); ?></th>
					</tr>
				</thead>
				<tbody>
					<?php foreach($servicegroupstatus as $k => $servicegroup): ?>
						<!-- Servicegroup -->
						<tr>
							<td class="bg-color-lightGray" colspan="8">
								<span style="font-size:small;font-weight:bold;"><?php echo $servicegroup['Container']['name']; ?></span>
							</td>
						</tr>
						<?php foreach ($servicegroup['Host'] as $host): ?>
							<!-- Host -->
							<tr>
								<td class="bg-color-lightGray" colspan="8">
									<span class="padding-left-10" style="font-size:x-small;"><?php echo $host['Host']['name']; ?></span>
								</td>
							</tr>
							<?php if(!empty($host['Host']['Service'])): ?>
								<?php foreach ($host['Host']['Service'] as $key => $servicestatus): ?>
									<!-- Status -->
									<tr> 
										<td class="text-center">
											<?php
												if($servicestatus[0]['Servicestatus']['is_flapping'] == 1):
													echo $this->Monitoring->serviceFlappingIconColored($servicestatus[0]['Servicestatus']['is_flapping'], '', $servicestatus[0]['Servicestatus']['current_state']);
												else:
													echo '<i class="fa fa-square '.$this->Status->ServiceStatusTextColor($servicestatus[0]['Servicestatus']['current_state']).'"></i>';
												endif;
											?>
										</td>
										<!-- ACK -->
										<td style="font-size:x-small;" class="text-center">
											<?php if($servicestatus[0]['Servicestatus']['problem_has_been_acknowledged'] > 0):?>
												<i class="fa fa-user fa-lg"></i>
											<?php endif;?>
										</td>
										<!-- downtime -->
										<td style="font-size:x-small;" class="text-center">
											<?php if($servicestatus[0]['Servicestatus']['scheduled_downtime_depth'] > 0): ?>
												<i class="fa fa-power-off fa-lg"></i>
											<?php endif; ?>
										</td>
										<!-- name -->
										<td style="font-size:x-small;">
										<?php if(!empty($servicestatus[0]['Service']['name'])){
													echo $servicestatus[0]['Service']['name'];
												}else{
													echo $servicestatus[0]['Servicetemplate']['name'];
												} 
											?>
										</td>
										<!-- Status Since -->
										<td style="font-size:x-small;" data-original-title="<?php echo h($this->Time->format($servicestatus[0]['Servicestatus']['last_state_change'], $this->Auth->user('dateformat'), false, $this->Auth->user('timezone'))); ?>" data-placement="bottom" rel="tooltip" data-container="body">
											<?php echo h($this->Utils->secondsInHumanShort(time() - strtotime($servicestatus[0]['Servicestatus']['last_state_change']))); ?>
										</td>
										<!-- Last check -->
										<td style="font-size:x-small;"><?php echo $this->Time->format($servicestatus[0]['Servicestatus']['last_check'], $this->Auth->user('dateformat'), false, $this->Auth->user('timezone')); ?></td>
										<!-- Next check -->
										<td style="font-size:x-small;"><?php echo $this->Time->format($servicestatus[0]['Servicestatus']['next_check'], $this->Auth->user('dateformat'), false, $this->Auth->user('timezone')); ?></td>
										<!-- Service output -->
										<td style="font-size:x-small;"><?php echo $servicestatus[0]['Servicestatus']['output']; ?></td>
									</tr>
								<?php endforeach; ?>
							<?php else: ?>
								<tr>
									<td style="font-size:xx-small;" class="text-center" colspan="8"><?php echo __('This host has no Services'); ?></td>
								</tr>
							<?php endif;?>
						<?php endforeach; ?>
					<?php endforeach; ?>
					
					<?php if(empty($servicegroupstatus)):?>
						<div class="noMatch">
							<center>
								<span class="txt-color-red italic"><?php echo __('search.noVal'); ?></span>
							</center>
						</div>
					<?php endif;?>
				</tbody>
			</table>
		</div>
	</div>
</body>