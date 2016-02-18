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
<!-- Modal window -->
<script><?php // TODO replace this with smart notifications ?>
	window.bootstrapModalContent = <?php echo json_encode($modals) ?>;
	window.App.host_uuids = <?php echo json_encode($host_uuids); ?>;
	window.App.loaded_graph_config = <?php echo json_encode($graph_configuration); ?>;
</script>

<div class="row">
	<div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
		<h1 class="page-title txt-color-blueDark">
			<i class="fa fa-area-chart fa-fw"></i>
			<?php echo __('Monitoring'); ?>
			<span>>
				<?php echo __('Graphgenerator'); ?>
			</span>
		</h1>
	</div>
</div>
<?php //debug($this->Html->chosenPlaceholder($host_ids_for_select));
//debug($host_ids_for_select); ?>
<div class="overlay" style="display: none;">
	<div id="nag_longoutput_loader"
		 style="position: absolute; top: 50%; left: 50%; margin-top: -29px; margin-left: -23px; z-index: 20; font-size: 40px; color: #fff;">
		<i class="fa fa-cog fa-lg fa-spin"></i>
	</div>
</div>

<section id="widget-grid" class="">
	<div class="row">
		<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
			<div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-1" data-widget-editbutton="false">
				<header>
					<div class="widget-toolbar" role="menu"></div>
					<div class="jarviswidget-ctrls" role="menu"></div>
					<span class="widget-icon"><i class="fa fa-area-chart"></i></span>

					<h2 class="hidden-mobile hidden-tablet"><?php echo __('Graphgenerator'); ?></h2>
					<ul class="nav nav-tabs pull-right padding-left-20" id="widget-tab-1">
						<li<?php echo $is_config_loaded ? '' : ' class="active"' ?>>
							<a href="/graphgenerators/index">
								<i class="fa fa-lg fa-plus"></i>
								<span class="hidden-mobile hidden-tablet"> <?php echo __('New'); ?></span>
							</a>
						</li>
						<?php if($is_config_loaded): ?>
						<li class="active">
							<a>
								<i class="fa fa-lg fa-edit"></i>
								<span class="hidden-mobile hidden-tablet"> <?php echo __('Edit'); ?></span>
							</a>
						</li>
						<?php endif; ?>
						<li>
							<?php if($this->Acl->hasPermission('listing')):
								echo $this->Html->link(__('List'), '/'.$this->params['controller'].'/listing', array('class' => 'hidden-mobile hidden-tablet', 'icon' => 'fa fa-lg fa-list-alt'));
								echo " "; //Need a space for nice buttons
							endif; ?>
						</li>
					</ul>
				</header>

				<!-- widget div-->
				<div>
					<!-- widget edit box -->
					<div class="jarviswidget-editbox">
						<!-- This area used as dropdown edit box -->
					</div>
					<!-- end widget edit box -->

					<!-- widget content -->
					<div class="widget-body no-padding">
						<div class="tab-content">
							<div id="new-edit" class="tab-pane fade active in">
								<div class="padding-top-10"></div>

								<?php echo $this->Form->create('Graphgenerator', [
									'class' => 'form-horizontal clear',
								]); ?>

								<div class="row">
									<div class="col-xs-12 col-md-9 col-lg-7">
										<div class="row form-group">
											<div class="col-md-2 control-label">
												<label for="GraphgeneratorGraphConfigurationName" class=""><?php echo __('Name'); ?></label>
											</div>
											<div class="col-md-10">
												<div class="input-group">
													<input type="text"
														   name="data[Graphgenerator][name]"
														   class="form-control"
													       <?php if($is_config_loaded): ?>
														   value = "<?php echo $graph_configuration['GraphgenTmpl']['name']; ?>"
														   <?php endif; ?>
														   id="GraphgeneratorName">

													<div class="input-group-btn">
														<button id="saveGraph" type="button" class="btn btn-default btn-primary">
															<span class="glyphicon glyphicon-save"></span>
															<?php echo __('Save Configuration'); ?>
														</button>
													</div>

													<div class="icon-warning input-group-btn"></div>
												</div>
											</div>
										</div>
										<?php
										$default_value = [];
										if($is_config_loaded){
											$loaded_time = (int) $graph_configuration['GraphgenTmpl']['relative_time'];
											$default_value['default'] = $loaded_time;
										}
										$relative_time_options = [ // TODO move to controller
											1800 => __('Last 30 Minutes'),
											3600 => __('Last 1 Hour'),
											10800 => __('Last 3 Hours'),
											21600 => __('Last 6 Hours'),
											43200 => __('Last 12 Hours'),
											86400 => __('Last 24 Hours'),
											259200 => __('Last 3 Days'),
											604800 => __('Last 7 Days'),
											1209600 => __('Last 14 Days'),
											2592000 => __('Last 30 Days'),
										];
										$options = [
											'options' => $relative_time_options,
											'label' => __('Time'),
											'class' => 'chosen col col-xs-12',
										];
										$options += $default_value;
										echo $this->Form->input('relative_time', $options);

										$hosts = $this->Html->chosenPlaceholder($host_ids_for_select);
										asort($hosts);
										$options = [
											'options' => $hosts,
											'label' => __('Host'),
											'class' => 'chosen col col-xs-12',
										];
										echo $this->Form->input('host_uuid', $options);

//										echo '<select name="data[Graphgenerator][host_uuid]" class="chosen col col-xs-12" id="GraphgeneratorHostUuid" style="display: none;">';
//										echo '</select';

										echo $this->Form->input('service_uuid', [
											'options' => $this->Html->chosenPlaceholder([]),
											'label' => __('Services'),
											'class' => 'chosen col col-xs-12',
										]);
										?>
									</div>

									<div class="col-xs-12 col-md-3 col-lg-5">
										<div class="row bold"><?php echo __('Servicerules'); ?></div>
										<div id="serviceRules" style="overflow: hidden">
											<!-- content added by AJAX --></div>
									</div>
								</div>
								<div class="row">
									<div class="col-xs-12">
										<hr>
										<div class="padding-top-10 padding-right-10 pull-right">
											<a href="javascript:void(0);" class="btn btn-danger" id="resetGraph">
												<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
												<?php echo __('Reset graph'); ?>
											</a>

											<a href="javascript:void(0);" class="btn btn-success" id="refreshGraph">
												<span class="glyphicon glyphicon-refresh" aria-hidden="true"></span>
												<?php echo __('Refresh graph'); ?>
											</a>
										</div>
										<div class="padding-top-20 clearfix"></div>
										<!-- avoid that buttons be overlapped by graph -->
										<div class="padding-top-20 clearfix"></div>
										<!-- avoid that buttons be overlapped by graph -->
									</div>
								</div>
								<div class="row">
									<div class="col-xs-1"></div>
									<div class="col-xs-10">
										<div class="padding-10" id="graph_container">
											<div class="graph_legend" style="display: none;"></div>
											<div id="graph_loader" style="display: none; text-align: center;">
												<i class="fa fa-cog fa-4x fa-spin"></i>
											</div>
											<div id="graph_data_tooltip"></div>
											<div id="graph">
												<!-- Content will be added by JavaScript GraphComponent -->
											</div>
										</div>
									</div>
								</div>

								<?php echo $this->Form->end(); ?>
							</div>
						</div>
						<!-- close tab content -->
					</div>
					<div class="padding-top-20"></div>
					<div class="padding-top-20"></div>
				<!-- close widget body -->
				</div>
			</div>
		</article>
	</div>
</section>

