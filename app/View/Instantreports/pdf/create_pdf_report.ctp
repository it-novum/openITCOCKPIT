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
	'/css/vendor/bootstrap/css/bootstrap-theme.css',
	'/smartadmin/css/font-awesome.css',
	'/smartadmin/css/smartadmin-production.css',
	'/smartadmin/css/your_style.css',
	'/css/app.css',
	'/css/bootstrap_pdf.css',
	'/css/pdf_list_style.css',
];
?>

<?php
foreach($css as $cssFile): ?>
	<link rel="stylesheet" type="text/css" href="<?php echo WWW_ROOT.$cssFile; ?>" />
<?php
endforeach; ?>
</head>
<body class="">
<div class="jarviswidget">
	<div class="well">
		<div class="row no-padding">
			<div class="col-md-9 text-left">
				<i class="fa fa-calendar txt-color-blueDark"></i>
				<?php
					echo __('Analysis period: ');
					echo h($this->Time->format($instantReportDetails['startDate'], $this->Auth->user('dateformat'), false, $this->Auth->user('timezone')));?>
				<i class="fa fa-long-arrow-right"></i>
				<?php
					echo h($this->Time->format($instantReportDetails['endDate'], $this->Auth->user('dateformat'), false, $this->Auth->user('timezone')));
				?>
			</div>
			<div class="col-md-3 text-left">
				<img src="<?php echo WWW_ROOT; ?>img/logo.png" width="200" />
			</div>
		</div>
		<?php
		foreach($instantReportData['Hosts'] as $hostUuid => $hostData):?>
		<section id="widget-grid" class="">
			<div class="row">
				<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12 sortable-grid ui-sortable font-md txt-color-blueDark">
					<?php
					if(isset($hostData[0], $hostData[1], $hostData[2])):?>
						<div class="jarviswidget jarviswidget-sortable" role="widget">
							<header role="heading">
								<h2 class="txt-color-blueDark" style="width:97%"><i class="fa fa-desktop txt-color-blueDark"></i> <?php echo h($hostData['Host']['name']);?>
								</h2>

							</header>
							<div class="well padding-bottom-10">
								<div class="row margin-top-10 font-md padding-bottom-20">
									<div class="col-md-12 text-left">
										<?php
										$overview_chart =  $this->PieChart->createPieChart([$hostData[0], $hostData[1], $hostData[2]]);

									//	echo $this->Html->image('/img/charts/'.$overview_chart);?>
									<img src="<?php echo WWW_ROOT; ?>img/charts/<?php echo $overview_chart; ?>" />
									</div>
									<div class="col-md-12 text-left font-md">
									<?php
									for($i = 0; $i<=2; $i++):?>

											<i class="fa fa-square no-padding <?php echo $this->Status->HostStatusTextColor($i);?> "></i>
											<em class="padding-right-20">
										<?php
											echo round($hostData[$i]/$instantReportDetails['totalTime']*100, 2).' % ('.$this->Status->humanSimpleHostStatus($i).')';
										?>
											</em>

									<?php
									endfor;
									?>
									</div>
								</div>
								<div>
									<?php
									if(isset($hostData['Services'])):
										foreach($hostData['Services'] as $serviceUuid => $serviceData):
											if(isset($serviceData[0], $serviceData[1], $serviceData[2], $serviceData[3])):?>
											<div class="padding-top-10 padding-bottom-5 font-md txt-color-blueDark"><i class="fa fa-gear txt-color-blueDark"></i> <?php echo h($serviceData['Service']['name']);?>
											</div>
											<div class="padding-left-20 text-left font-md txt-color-blueDark">
											<?php
												$overview_chart = $this->BarChart->createBarChart([$serviceData[0], $serviceData[1], $serviceData[2], $serviceData[3]]);

												//echo $this->Html->image(
												//	'/img/charts/'.$overview_chart
												//);
												?>
												<img src="<?php echo WWW_ROOT; ?>img/charts/<?php echo $overview_chart; ?>" />
												<?php
												for($i = 0; $i<=3; $i++):?>
														<i class="fa fa-square no-padding <?php echo $this->Status->ServiceStatusTextColor($i);?> "></i>
														<em class="padding-right-20 font-sm txt-color-blueDark">
													<?php
														echo round($serviceData[$i]/$instantReportDetails['totalTime']*100, 2).' % ('.$this->Status->humanSimpleServiceStatus($i).')';
													?>
														</em>

												<?php
												endfor;
											endif;?>
											</div>
											<?php
										endforeach;
									endif;
									?>
									<br />
								</div>
							</div>
						</div>
					<?php
					endif;
					?>
				</article>
			</div>
		</section>
		<?php
		endforeach;
		$hostsNotMonitored = Hash::extract($instantReportData, 'Hosts.{s}.HostsNotMonitored.{n}');
		$servicesNotMonitored = Hash::extract($instantReportData, 'Hosts.{s}.Services.ServicesNotMonitored.{s}');
		if(!empty($hostsNotMonitored)||!empty($servicesNotMonitored)):?>
			<section id="widget-grid" class="">
				<div class="row">
					<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12 sortable-grid ui-sortable">
						<div class="jarviswidget jarviswidget-sortable" role="widget">
							<header role="heading">
								<h2 style="width:100%;" class="txt-color-blueDark">
									<i class="fa fa-user-md txt-color-blueDark font-md"></i> <?php echo __('Not monitored'); ?>
								</h2>
							</header>
							<div class="well padding-bottom-10 txt-color-blueDark font-md">
								<?php
								foreach($hostsNotMonitored as $hostId => $hostName):?>
									<div class="txt-color-blueDark">
										<i class="fa fa-desktop txt-color-blueDark"></i> <?php echo h($hostName);?>
									</div>
								<?php
								endforeach;
								foreach($servicesNotMonitored as $serviceId => $serviceArray):?>
									<div class="txt-color-blueDark">
										<i class="fa fa-gear txt-color-blueDark"></i>
										<?php
										echo h((isset($serviceArray['Service']['name'])?$serviceArray['Service']['name']:$serviceArray['Servicetemplate']['name']));
										echo h(' ('.$serviceArray['Host']['name'].')');
										?>
									</div>
								<?php
								endforeach;
								?>
							</div>
						</div>
					</article>
				</div>
			</section>
		<?php
		endif;
		?>
	</div>
</div>
</body>
