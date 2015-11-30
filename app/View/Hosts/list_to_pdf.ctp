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
	'/css/vendor/bootstrap/css/bootstrap.css',
	//'/css/vendor/bootstrap/css/bootstrap-theme.css',
	'/smartadmin/css/font-awesome.css',
	'/smartadmin/css/smartadmin-production.css',
	'/smartadmin/css/your_style.css',
	'/css/app.css',
	'/css/bootstrap_pdf.css'
];
?>

<?php
foreach($css as $cssFile): ?>
	 <link rel="stylesheet" type="text/css" href="<?php echo $cssFile; ?>" />
<?php endforeach; ?>

</head>
<body>
	<div class="well">
		<div class="row margin-top-10 font-lg no-padding">
			<div class="col-md-9 text-left">
				<i class="fa fa-desktop txt-color-blueDark"></i>
				<?php echo __('Hosts'); ?>
			</div>
			<div class="col-md-3 text-left">
				<img src="/img/logo.png" width="200" />
			</div>
		</div>
		<div class="row padding-left-10 margin-top-10 font-md">
			<div class="col-md-3 text-left">
				<i class="fa fa-calendar txt-color-blueDark"></i> <?php echo date('F d, Y H:i:s'); ?>
			</div>
		</div>
		<div class="row padding-left-10 margin-top-10 font-md">
			<div class="col-md-3 text-left">
				<i class="fa fa-list-ol txt-color-blueDark"></i> <?php echo __('Number of Hosts: '.$hostCount); ?>
			</div>
		</div>
		<br>
		<div class="mobile_table">
			<table id="" class="table table-striped table-bordered smart-form" style="">
				<thead>
					<tr>
						<th><?php echo __('Status'); ?></th>
						<th class="no-sort text-center" ><i class="fa fa-user fa-lg"></i></th>
						<th><?php echo __('Host'); ?></th>
						<th><?php echo __('Status since'); ?></th>
						<th><?php echo __('Last check'); ?></th>
						<th><?php echo __('Next check'); ?></th>
					</tr>
				</thead>
				<tbody>
					<?php foreach($hoststatus as $k => $host): ?>
						<tr>
							<td class="text-center">
							<?php
							if($host['Hoststatus']['is_flapping'] == 1):
								echo $this->Monitoring->hostFlappingIconColored($host['Hoststatus']['is_flapping'], '', $host['Hoststatus']['current_state']);
							else:
								echo $this->Status->humanHostStatus($host['Host']['uuid'], '/hosts/browser/'.$host['Host']['id'], [$host['Host']['uuid'] => ['Hoststatus' => ['current_state' => $host['Hoststatus']['current_state']]]])['html_icon'];
							endif;
							?>
							</td>
							<td class="text-center">
								<?php if($host['Hoststatus']['problem_has_been_acknowledged'] > 0):?>
									<i class="fa fa-user fa-lg "></i>
								<?php endif;?>
							</td>
							<td><?php echo $host['Host']['name']; ?></td>
							<td><?php echo $host['Hoststatus']['last_state_change']; ?></td>
							<td><?php echo $host['Hoststatus']['last_check']; ?></td>
							<td><?php echo $host['Hoststatus']['next_check']; ?></td>
						</tr>
					<?php endforeach; ?>

					<?php if(empty($hoststatus)):?>
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