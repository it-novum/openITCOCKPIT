<h1>Dashboard</h1>
<div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-3" data-widget-togglebutton="false" data-widget-editbutton="false" data-widget-fullscreenbutton="false" data-widget-colorbutton="false" data-widget-deletebutton="false">
	<header>
		<h2><strong><i class="fa-fw fa fa-dashboard"></i>Dashboards </strong><i>(Dashboard 1)</i></h2>
		<ul id="widget-tab-1" class="nav nav-tabs pull-right">
			<li class="active">
				<a data-toggle="tab" href="#hr1"> <i class="fa fa-lg fa-arrow-circle-o-down"></i> <span class="hidden-mobile hidden-tablet"> Dashboard 1 </span> </a>
			</li>
			<li>
				<a data-toggle="tab" href="#hr2"> <i class="fa fa-lg fa-arrow-circle-o-up"></i> <span class="hidden-mobile hidden-tablet"> Dashboard 2 </span></a>
			</li>
			<li>
				<a data-toggle="tab" href="#hr3"> <i class="fa fa-lg fa-arrow-circle-o-up"></i> <span class="hidden-mobile hidden-tablet"> Dashboard 3 </span></a>
			</li>
			<li>
				<a data-toggle="tab" href="#hr4"> <i class="fa fa-lg fa-arrow-circle-o-up"></i> <span class="hidden-mobile hidden-tablet"> Dashboard 4 </span></a>
			</li>
		</ul>
	</header>
</div>

<!-- widget grid -->
<section class="tab-content" id="widget-grid">
	<div class="row">
		<article class="col-sm-12 col-md-12 col-lg-6 sortable-grid ui-sortable">
			<div class="jarviswidget jarviswidget-sortable" data-widget-deletebutton="false" data-widget-colorbutton="false" data-widget-fullscreenbutton="true" data-widget-editbutton="true" data-widget-togglebutton="false" style="position: relative; opacity: 1; left: 0px; top: 0px;" role="widget">
			<header>
					<span class="widget-icon"> <i class="fa fa-pie-chart"></i>
 </span>
					<h2><?php echo __('Overview Hosts'); ?></h2>
					<div class="jarviswidget-ctrls" role="menu">
							<a class="button-icon jarviswidget-toggle-btn" data-placement="bottom" title="" rel="tooltip" href="#" data-original-title="Collapse">
								<i class="fa fa-minus "></i>
							</a>
							<a class="button-icon jarviswidget-fullscreen-btn" data-placement="bottom" title="" rel="tooltip" href="javascript:void(0);" data-original-title="Fullscreen">
								<i class="fa fa-expand"></i>
							</a>
							<a class="button-icon jarviswidget-delete-btn" data-placement="bottom" title="" rel="tooltip" href="javascript:void(0);" data-original-title="Delete">
								<i class="fa fa-times"></i>
							</a>
						</div>
				</header>
				<!-- widget div-->
				<div>
					<!-- end widget edit box -->
					<div class="widget-body padding-10 text-center">
					<?php
						$state_total = array_sum($state_array_host);
							if($state_total > 0):
							$overview_chart =  $this->PieChart->createPieChart($state_array_host);
							echo $this->Html->image(
								'/img/charts/'.$overview_chart
							);
							$state_colors = [
								'text-success',
								'text-danger',
								'txt-color-blueLight'
							];?>
							<div class="col-md-12 text-center padding-bottom-10 font-xs">
							<?php
								foreach($state_array_host as $state => $state_count):?>
									<div class="col-md-4 no-padding">
										<a href="<?php echo Router::url(['controller' => 'hosts', 'action' => 'index', 'plugin' => '', 'Filter.Hoststatus.current_state['.$state.']' => 1]); ?>">
											<i class="fa fa-square <?php echo $state_colors[$state]?>"></i>
											<?php echo $state_count.' ('.round($state_count/$state_total*100, 2).' %)'; ?>
										</a>
									</div>
							<?php endforeach; ?>
							</div>
						<?php else:?>
							<div class="text-muted padding-top-20"><?php echo __('No hosts are monitored on your system. Please create first a host'); ?></div>
						<?php endif; ?>
					</div>

				</div>
		</article>
		<article class="col-sm-12 col-md-12 col-lg-6 sortable-grid ui-sortable">
			<div class="jarviswidget jarviswidget-sortable" id="wid-id-12" data-widget-deletebutton="false" data-widget-colorbutton="false" data-widget-fullscreenbutton="true" data-widget-editbutton="true" data-widget-togglebutton="false" style="position: relative; opacity: 1; left: 0px; top: 0px;" role="widget">
			<header>
					<span class="widget-icon"> <i class="fa fa-pie-chart"></i>
</i> </span>
					<h2><?php echo __('Overview Services'); ?></h2>
					<div class="jarviswidget-ctrls" role="menu">
							<a class="button-icon jarviswidget-toggle-btn" data-placement="bottom" title="" rel="tooltip" href="#" data-original-title="Collapse">
								<i class="fa fa-minus "></i>
							</a>
							<a class="button-icon jarviswidget-fullscreen-btn" data-placement="bottom" title="" rel="tooltip" href="javascript:void(0);" data-original-title="Fullscreen">
								<i class="fa fa-expand"></i>
							</a>
							<a class="button-icon jarviswidget-delete-btn" data-placement="bottom" title="" rel="tooltip" href="javascript:void(0);" data-original-title="Delete">
								<i class="fa fa-times"></i>
							</a>
						</div>
				</header>
				<!-- widget div-->
				<div>
					<!-- end widget edit box -->
					<div class="widget-body padding-10 text-center">
					<?php
						$state_total = array_sum($state_array_service);
						if($state_total > 0):
							$overview_chart =  $this->PieChart->createPieChart($state_array_service);

							echo $this->Html->image(
								'/img/charts/'.$overview_chart
							);
							$state_colors = [
								'text-success',
								'text-warning',
								'text-danger',
								'txt-color-blueLight'
							];?>
							<div class="col-md-12 text-center padding-bottom-10 font-xs">
							<?php
							
								foreach($state_array_service as $state => $state_count):?>
									<div class="col-md-3 no-padding">
										<a href="<?php echo Router::url(['controller' => 'services', 'action' => 'index', 'plugin' => '', 'Filter.Servicestatus.current_state['.$state.']' => 1]); ?>">
											<i class="fa fa-square <?php echo $state_colors[$state]?>"></i>
											<?php
											//Fix for a system without host or services
											if($state_total == 0):
												$state_total = 1;
												if($state == 3):
													$state_count = 1;
												endif;
											endif;
											?>
											<?php echo $state_count.' ('.round($state_count/$state_total*100, 2).' %)'; ?>
										</a>
									</div>
							<?php endforeach;?>
							</div>
						<?php else:?>
							<div class="text-muted padding-top-20"><?php echo __('No services are monitored on your system. Please create first a service'); ?></div>
						<?php endif;?>
					</div>
				</div>
		</article>
	</div>

	<!-- end row -->

	<!-- row -->

	<div class="row">
		<article class="col-sm-12 col-md-12 col-lg-6 sortable-grid ui-sortable">
			<div class="jarviswidget jarviswidget-sortable" id="wid-id-12" data-widget-deletebutton="false" data-widget-colorbutton="false" data-widget-fullscreenbutton="true" data-widget-editbutton="true" data-widget-togglebutton="false" style="position: relative; opacity: 1; left: 0px; top: 0px;" role="widget">
				<header>
					<span class="widget-icon"> <i class="fa fa-map-marker"></i> </span>
					<h2>Status Map (Germany)</h2>
					<div class="jarviswidget-ctrls" role="menu">
							<a class="button-icon jarviswidget-toggle-btn" data-placement="bottom" title="" rel="tooltip" href="#" data-original-title="Collapse">
								<i class="fa fa-minus "></i>
							</a>
							<a class="button-icon jarviswidget-fullscreen-btn" data-placement="bottom" title="" rel="tooltip" href="javascript:void(0);" data-original-title="Fullscreen">
								<i class="fa fa-expand"></i>
							</a>
							<a class="button-icon jarviswidget-delete-btn" data-placement="bottom" title="" rel="tooltip" href="javascript:void(0);" data-original-title="Delete">
								<i class="fa fa-times"></i>
							</a>
						</div>
					<div class="widget-toolbar hidden-mobile">
						<span class="onoffswitch-title"><i class="fa fa-location-arrow"></i> Realtime</span>
						<span class="onoffswitch">
							<input type="checkbox" name="onoffswitch" class="onoffswitch-checkbox" checked="checked" id="myonoffswitch">
							<label class="onoffswitch-label" for="myonoffswitch"> <span class="onoffswitch-inner" data-swchon-text="YES" data-swchoff-text="NO"></span> <span class="onoffswitch-switch"></span> </label> </span>
					</div>
				</header>

				<!-- widget div-->
				<div>
					<!-- widget edit box -->
					<div class="jarviswidget-editbox">
						<div>
							<label>Title:</label>
							<input type="text" />
						</div>
					</div>
					<!-- end widget edit box -->

					<div class="widget-body no-padding">
						<!-- content goes here -->

						<div id="vector-map-germany" class="vector-map"></div>
					<!--
						<div id="heat-fill">
							<span class="fill-a">0</span>

							<span class="fill-b">5,000</span>
						</div>
					-->
						<table class="table table-striped table-hover table-condensed">
							<thead>
								<tr>
									<th>Region</th>
									<th class="text-align-center">Hosts (Gesamt)</th>
									<th class="text-align-center">OK</th>
									<th class="text-align-center">DOWN</th>
									<th class="text-align-center">UNREACHABLE</th>
									<th></th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td><a href="javascript:void(0);">Nordrhein-Westfalen</a></td>
									<td>4873</td>
									<td class="text-align-center">4629</td>
									<td class="text-align-center">244</td>
									<td class="text-align-center">-</td>
									<td class="text-align-center">
										<div class="sparkline display-inline" data-sparkline-type='pie' data-sparkline-piecolor='["#47A447", "#D2322D", "#C4C4C4"]' data-sparkline-offset="90" data-sparkline-piesize="23px">
										95,5,0
									</div>

									</td>
								</tr>
								<tr>
									<td><a href="javascript:void(0);">Bayern</a></td>
									<td>80</td>
									<td class="text-align-center">80</td>
									<td class="text-align-center">-</td>
									<td class="text-align-center">-</td>
									<td class="text-align-center">
										<div class="sparkline display-inline" data-sparkline-type='pie' data-sparkline-piecolor='["#47A447", "#D2322D", "#C4C4C4"]' data-sparkline-offset="90" data-sparkline-piesize="23px">
										100,0,0
									</div>

									</td>
								</tr>
								<tr>
									<td><a href="javascript:void(0);">Hessen</a></td>
									<td>155</td>
									<td class="text-align-center">93</td>
									<td class="text-align-center">15</td>
									<td class="text-align-center">47</td>
									<td class="text-align-center">
										<div class="sparkline display-inline" data-sparkline-type='pie' data-sparkline-piecolor='["#47A447", "#D2322D", "#C4C4C4"]' data-sparkline-offset="90" data-sparkline-piesize="23px">
										60,10,30
									</div>

									</td>
								</tr>
							</tbody>
							<tfoot>
							<!--
								<tr>
									<td colspan=5>
									<ul class="pagination pagination-xs no-margin">
										<li class="prev disabled">
											<a href="javascript:void(0);">Previous</a>
										</li>
										<li class="active">
											<a href="javascript:void(0);">1</a>
										</li>
										<li>
											<a href="javascript:void(0);">2</a>
										</li>
										<li>
											<a href="javascript:void(0);">3</a>
										</li>
										<li class="next">
											<a href="javascript:void(0);">Next</a>
										</li>
									</ul></td>
								</tr>
							-->
							</tfoot>
						</table>

						<!-- end content -->

					</div>

				</div>
				<!-- end widget div -->
			</div>
			<!-- end widget -->

			<!-- new widget -->
			<div class="jarviswidget jarviswidget-color-blue" id="wid-id-4" data-widget-editbutton="false" data-widget-colorbutton="false">

				<!-- widget options:
				usage: <div class="jarviswidget" id="wid-id-0" data-widget-editbutton="false">

				data-widget-colorbutton="false"
				data-widget-editbutton="false"
				data-widget-togglebutton="false"
				data-widget-deletebutton="false"
				data-widget-fullscreenbutton="false"
				data-widget-custombutton="false"
				data-widget-collapsed="true"
				data-widget-sortable="false"

				-->

				<header>
					<span class="widget-icon"> <i class="fa fa-check txt-color-white"></i> </span>
					<h2> ToDo's </h2>
					<!-- <div class="widget-toolbar">
					add: non-hidden - to disable auto hide

					</div>-->
				</header>

				<!-- widget div-->
				<div>
					<!-- widget edit box -->
					<div class="jarviswidget-editbox">
						<div>
							<label>Title:</label>
							<input type="text" />
						</div>
					</div>
					<!-- end widget edit box -->

					<div class="widget-body no-padding smart-form">
						<!-- content goes here -->
						<h5 class="todo-group-title"><i class="fa fa-warning"></i> Critical Tasks (<small class="num-of-tasks">1</small>)</h5>
						<ul id="sortable1" class="todo">
							<li>
								<span class="handle"> <label class="checkbox">
										<input type="checkbox" name="checkbox-inline">
										<i></i> </label> </span>
								<p>
									<strong>Ticket #17643</strong> - Hotfix for WebApp interface issue [<a href="javascript:void(0);" class="font-xs">More Details</a>] <span class="text-muted">Sea deep blessed bearing under darkness from God air living isn't. </span>
									<span class="date">Jan 1, 2014</span>
								</p>
							</li>
						</ul>
						<h5 class="todo-group-title"><i class="fa fa-exclamation"></i> Important Tasks (<small class="num-of-tasks">3</small>)</h5>
						<ul id="sortable2" class="todo">
							<li>
								<span class="handle"> <label class="checkbox">
										<input type="checkbox" name="checkbox-inline">
										<i></i> </label> </span>
								<p>
									<strong>Ticket #1347</strong> - Inbox email is being sent twice <small>(bug fix)</small> [<a href="javascript:void(0);" class="font-xs">More Details</a>] <span class="date">Nov 22, 2013</span>
								</p>
							</li>
							<li>
								<span class="handle"> <label class="checkbox">
										<input type="checkbox" name="checkbox-inline">
										<i></i> </label> </span>
								<p>
									<strong>Ticket #1314</strong> - Call customer support re: Issue <a href="javascript:void(0);" class="font-xs">#6134</a><small>(code review)</small>
									<span class="date">Nov 22, 2013</span>
								</p>
							</li>
							<li>
								<span class="handle"> <label class="checkbox">
										<input type="checkbox" name="checkbox-inline">
										<i></i> </label> </span>
								<p>
									<strong>Ticket #17643</strong> - Hotfix for WebApp interface issue [<a href="javascript:void(0);" class="font-xs">More Details</a>] <span class="text-muted">Sea deep blessed bearing under darkness from God air living isn't. </span>
									<span class="date">Jan 1, 2014</span>
								</p>
							</li>
						</ul>

						<h5 class="todo-group-title"><i class="fa fa-check"></i> Completed Tasks (<small class="num-of-tasks">1</small>)</h5>
						<ul id="sortable3" class="todo">
							<li class="complete">
								<span class="handle" style="display:none"> <label class="checkbox state-disabled">
										<input type="checkbox" name="checkbox-inline" checked="checked" disabled="disabled">
										<i></i> </label> </span>
								<p>
									<strong>Ticket #17643</strong> - Hotfix for WebApp interface issue [<a href="javascript:void(0);" class="font-xs">More Details</a>] <span class="text-muted">Sea deep blessed bearing under darkness from God air living isn't. </span>
									<span class="date">Jan 1, 2014</span>
								</p>
							</li>
						</ul>

						<!-- end content -->
					</div>

				</div>
				<!-- end widget div -->
			</div>
			<!-- end widget -->

		</article>

		<article class="col-sm-12 col-md-12 col-lg-6 sortable-grid ui-sortable">

			<!-- new widget -->
			<div class="jarviswidget jarviswidget-sortable" id="wid-id-2" data-widget-colorbutton="false" data-widget-editbutton="false" data-widget-fullscreenbutton="false" data-widget-collapsed="true">

				<!-- widget options:
				usage: <div class="jarviswidget" id="wid-id-0" data-widget-editbutton="false">

				data-widget-colorbutton="false"
				data-widget-editbutton="false"
				data-widget-togglebutton="false"
				data-widget-deletebutton="false"
				data-widget-fullscreenbutton="false"
				data-widget-custombutton="false"
				data-widget-collapsed="true"
				data-widget-sortable="false"

				-->

				<header role="heading">
					<span class="widget-icon"> <i class="fa fa-map-marker"></i> </span>
						<h2>Status Map (World)</h2>
						<div class="jarviswidget-ctrls" role="menu">
							<a class="button-icon jarviswidget-toggle-btn" data-placement="bottom" title="" rel="tooltip" href="#" data-original-title="Collapse">
								<i class="fa fa-minus "></i>
							</a>
							<a class="button-icon jarviswidget-fullscreen-btn" data-placement="bottom" title="" rel="tooltip" href="javascript:void(0);" data-original-title="Fullscreen">
								<i class="fa fa-expand"></i>
							</a>
							<a class="button-icon jarviswidget-delete-btn" data-placement="bottom" title="" rel="tooltip" href="javascript:void(0);" data-original-title="Delete">
								<i class="fa fa-times"></i>
							</a>
						</div>
					<div class="widget-toolbar hidden-mobile">
							<span class="onoffswitch-title"><i class="fa fa-location-arrow"></i> Realtime</span>
							<span class="onoffswitch">
								<input type="checkbox" name="onoffswitch" class="onoffswitch-checkbox" checked="checked" id="myonoffswitch">
								<label class="onoffswitch-label" for="myonoffswitch"> <span class="onoffswitch-inner" data-swchon-text="YES" data-swchoff-text="NO"></span> <span class="onoffswitch-switch"></span> </label> </span>
						</div>
				</header>

				<!-- widget div-->
				<div>
					<!-- widget edit box -->
					<div class="jarviswidget-editbox">
						<div>
							<label>Title:</label>
							<input type="text" />
						</div>
					</div>
					<!-- end widget edit box -->

					<div class="widget-body no-padding">
						<!-- content goes here -->

						<div id="vector-map" class="vector-map"></div>
					<!--
						<div id="heat-fill">
							<span class="fill-a">0</span>

							<span class="fill-b">5,000</span>
						</div>
					-->
						<table class="table table-striped table-hover table-condensed">
							<thead>
								<tr>
									<th>Country</th>
									<th class="text-align-center">Hosts (Gesamt)</th>
									<th class="text-align-center">OK</th>
									<th class="text-align-center">DOWN</th>
									<th class="text-align-center">UNREACHABLE</th>
									<th></th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td><a href="javascript:void(0);">GERMANY</a></td>
									<td>134</td>
									<td class="text-align-center">107</td>
									<td class="text-align-center">20</td>
									<td class="text-align-center">7</td>
									<td class="text-align-center">
										<div class="sparkline display-inline" data-sparkline-type='pie' data-sparkline-piecolor='["#47A447", "#D2322D", "#C4C4C4"]' data-sparkline-offset="90" data-sparkline-piesize="23px">
										80,15,5
									</div>

									</td>
								</tr>
								<tr>
									<td><a href="javascript:void(0);">USA</a></td>
									<td>80</td>
									<td class="text-align-center">48</td>
									<td class="text-align-center">28</td>
									<td class="text-align-center">4</td>
									<td class="text-align-center">
										<div class="sparkline display-inline" data-sparkline-type='pie' data-sparkline-piecolor='["#47A447", "#D2322D", "#C4C4C4"]' data-sparkline-offset="90" data-sparkline-piesize="23px">
										60,35,5
									</div>

									</td>
								</tr>
								<tr>
									<td><a href="javascript:void(0);">CANADA</a></td>
									<td>155</td>
									<td class="text-align-center">155</td>
									<td class="text-align-center">-</td>
									<td class="text-align-center">-</td>
									<td class="text-align-center">
										<div class="sparkline display-inline" data-sparkline-type='pie' data-sparkline-piecolor='["#47A447", "#D2322D", "#C4C4C4"]' data-sparkline-offset="90" data-sparkline-piesize="23px">
										100,0,0
									</div>

									</td>
								</tr>
								<tr>
									<td><a href="javascript:void(0);">BRAZIL</a></td>
									<td>40</td>
									<td class="text-align-center">40</td>
									<td class="text-align-center">-</td>
									<td class="text-align-center">-</td>
									<td class="text-align-center">
										<div class="sparkline display-inline" data-sparkline-type='pie' data-sparkline-piecolor='["#47A447", "#D2322D", "#C4C4C4"]' data-sparkline-offset="90" data-sparkline-piesize="23px">
										100,0,0
									</div>
									</td>
								</tr>
							</tbody>
							<tfoot>
							<!--
								<tr>
									<td colspan=5>
									<ul class="pagination pagination-xs no-margin">
										<li class="prev disabled">
											<a href="javascript:void(0);">Previous</a>
										</li>
										<li class="active">
											<a href="javascript:void(0);">1</a>
										</li>
										<li>
											<a href="javascript:void(0);">2</a>
										</li>
										<li>
											<a href="javascript:void(0);">3</a>
										</li>
										<li class="next">
											<a href="javascript:void(0);">Next</a>
										</li>
									</ul></td>
								</tr>
							-->
							</tfoot>
						</table>

						<!-- end content -->

					</div>

				</div>
				<!-- end widget div -->
			</div>
			<!-- end widget -->

			<!-- new widget -->
			<div class="jarviswidget jarviswidget-color-blue" id="wid-id-4" data-widget-editbutton="false" data-widget-colorbutton="false">

				<!-- widget options:
				usage: <div class="jarviswidget" id="wid-id-0" data-widget-editbutton="false">

				data-widget-colorbutton="false"
				data-widget-editbutton="false"
				data-widget-togglebutton="false"
				data-widget-deletebutton="false"
				data-widget-fullscreenbutton="false"
				data-widget-custombutton="false"
				data-widget-collapsed="true"
				data-widget-sortable="false"

				-->

				<header>
					<span class="widget-icon"> <i class="fa fa-check txt-color-white"></i> </span>
					<h2> ToDo's </h2>
					<!-- <div class="widget-toolbar">
					add: non-hidden - to disable auto hide

					</div>-->
				</header>

				<!-- widget div-->
				<div>
					<!-- widget edit box -->
					<div class="jarviswidget-editbox">
						<div>
							<label>Title:</label>
							<input type="text" />
						</div>
					</div>
					<!-- end widget edit box -->

					<div class="widget-body no-padding smart-form">
						<!-- content goes here -->
						<h5 class="todo-group-title"><i class="fa fa-warning"></i> Critical Tasks (<small class="num-of-tasks">1</small>)</h5>
						<ul id="sortable1" class="todo">
							<li>
								<span class="handle"> <label class="checkbox">
										<input type="checkbox" name="checkbox-inline">
										<i></i> </label> </span>
								<p>
									<strong>Ticket #17643</strong> - Hotfix for WebApp interface issue [<a href="javascript:void(0);" class="font-xs">More Details</a>] <span class="text-muted">Sea deep blessed bearing under darkness from God air living isn't. </span>
									<span class="date">Jan 1, 2014</span>
								</p>
							</li>
						</ul>
						<h5 class="todo-group-title"><i class="fa fa-exclamation"></i> Important Tasks (<small class="num-of-tasks">3</small>)</h5>
						<ul id="sortable2" class="todo">
							<li>
								<span class="handle"> <label class="checkbox">
										<input type="checkbox" name="checkbox-inline">
										<i></i> </label> </span>
								<p>
									<strong>Ticket #1347</strong> - Inbox email is being sent twice <small>(bug fix)</small> [<a href="javascript:void(0);" class="font-xs">More Details</a>] <span class="date">Nov 22, 2013</span>
								</p>
							</li>
							<li>
								<span class="handle"> <label class="checkbox">
										<input type="checkbox" name="checkbox-inline">
										<i></i> </label> </span>
								<p>
									<strong>Ticket #1314</strong> - Call customer support re: Issue <a href="javascript:void(0);" class="font-xs">#6134</a><small>(code review)</small>
									<span class="date">Nov 22, 2013</span>
								</p>
							</li>
							<li>
								<span class="handle"> <label class="checkbox">
										<input type="checkbox" name="checkbox-inline">
										<i></i> </label> </span>
								<p>
									<strong>Ticket #17643</strong> - Hotfix for WebApp interface issue [<a href="javascript:void(0);" class="font-xs">More Details</a>] <span class="text-muted">Sea deep blessed bearing under darkness from God air living isn't. </span>
									<span class="date">Jan 1, 2014</span>
								</p>
							</li>
						</ul>

						<h5 class="todo-group-title"><i class="fa fa-check"></i> Completed Tasks (<small class="num-of-tasks">1</small>)</h5>
						<ul id="sortable3" class="todo">
							<li class="complete">
								<span class="handle" style="display:none"> <label class="checkbox state-disabled">
										<input type="checkbox" name="checkbox-inline" checked="checked" disabled="disabled">
										<i></i> </label> </span>
								<p>
									<strong>Ticket #17643</strong> - Hotfix for WebApp interface issue [<a href="javascript:void(0);" class="font-xs">More Details</a>] <span class="text-muted">Sea deep blessed bearing under darkness from God air living isn't. </span>
									<span class="date">Jan 1, 2014</span>
								</p>
							</li>
						</ul>

						<!-- end content -->
					</div>

				</div>
				<!-- end widget div -->
			</div>
			<!-- end widget -->

		</article>
	</div>
</div>
	<!-- end row -->
<div class="tab-pane fade" id="hr2">
	<div class="row">

		<article class="col-sm-12 col-md-12 col-lg-12">
			<!-- new widget -->
			<div class="jarviswidget jarviswidget-color-blueLight" id="wid-id-calendar" data-widget-colorbutton="false">

				<!-- widget options:
				usage: <div class="jarviswidget" id="wid-id-0" data-widget-editbutton="false">

				data-widget-colorbutton="false"
				data-widget-editbutton="false"
				data-widget-togglebutton="false"
				data-widget-deletebutton="false"
				data-widget-fullscreenbutton="false"
				data-widget-custombutton="false"
				data-widget-collapsed="true"
				data-widget-sortable="false"

				-->
				<header>
					<span class="widget-icon"> <i class="fa fa-calendar"></i> </span>
					<h2>Downtimes</h2>
					<div class="widget-toolbar">
						<!-- add: non-hidden - to disable auto hide -->
						<div class="btn-group">
							<button class="btn dropdown-toggle btn-xs btn-default" data-toggle="dropdown">
								Showing <i class="fa fa-caret-down"></i>
							</button>
							<ul class="dropdown-menu js-status-update pull-right">
								<li>
									<a href="javascript:void(0);" id="mt">Month</a>
								</li>
								<li>
									<a href="javascript:void(0);" id="ag">Timeline</a>
								</li>
								<li>
									<a href="javascript:void(0);" id="td">Today</a>
								</li>
							</ul>
						</div>
					</div>
				</header>

				<!-- widget div-->
				<div>
					<!-- widget edit box -->
					<div class="jarviswidget-editbox">

						<input class="form-control" type="text">

					</div>
					<!-- end widget edit box -->

					<div class="widget-body no-padding">
						<!-- content goes here -->
						<div class="widget-body-toolbar">

							<div id="calendar-buttons">

								<div class="btn-group">
									<a href="javascript:void(0)" class="btn btn-default btn-xs" id="btn-prev"><i class="fa fa-chevron-left"></i></a>
									<a href="javascript:void(0)" class="btn btn-default btn-xs" id="btn-next"><i class="fa fa-chevron-right"></i></a>
								</div>
							</div>
						</div>
						<div id="calendar"></div>

						<!-- end content -->
					</div>

				</div>
				<!-- end widget div -->
			</div>
			<!-- end widget -->
		</article>
	</div>
</div>
<div class="tab-pane fade" id="hr3">
	Hallo Dashboard 3 3 3 3 3 3 3 Text
</div>
<div class="tab-pane fade" id="hr4">
	Hallo Dashboard 4 4 4 4 4 4 4 Text
</div>
</section>
<!-- end widget grid -->
<script type="text/javascript">
	// DO NOT REMOVE : GLOBAL FUNCTIONS!
	pageSetUp();

	/*
	 * PAGE RELATED SCRIPTS
	 */

	$(".js-status-update a").click(function () {
	    var selText = $(this).text();
	    $this = $(this);
	    $this.parents('.btn-group').find('.dropdown-toggle').html(selText + ' <span class="caret"></span>');
	    $this.parents('.dropdown-menu').find('li').removeClass('active');
	    $this.parent().addClass('active');
	});

	/*
	 * TODO: add a way to add more todo's to list
	 */

	// initialize sortable
/*
	$(function () {
	    $("#sortable1, #sortable2").sortable({
	        handle: '.handle',
	        connectWith: ".todo",
        	update: countTasks
	    }).disableSelection();
	});
*/
	// check and uncheck
	$('.todo .checkbox > input[type="checkbox"]').click(function () {
	    $this = $(this).parent().parent().parent();

	    if ($(this).prop('checked')) {
	        $this.addClass("complete");

	        // remove this if you want to undo a check list once checked
	        //$(this).attr("disabled", true);
	        $(this).parent().hide();

	        // once clicked - add class, copy to memory then remove and add to sortable3
	        $this.slideUp(500, function () {
	            $this.clone().prependTo("#sortable3").effect("highlight", {}, 800);
	            $this.remove();
	            countTasks();
	        });
	    } else {
	        // insert undo code here...
	    }

	});

	// count tasks
	function countTasks() {

	    $('.todo-group-title').each(function () {
	        $this = $(this);
	        $this.find(".num-of-tasks").text($this.next().find("li").size());
	    });

	}

	/*
	 * RUN PAGE GRAPHS
	 */

	// Load FLOAT dependencies (related to page)
	loadScript("/smartadmin/js/plugin/flot/jquery.flot.cust.js", loadFlotResize);


	function loadFlotResize() {
	    loadScript("/smartadmin/js/plugin/flot/jquery.flot.resize.js", loadFlotToolTip);
	}

	function loadFlotToolTip() {
//	    loadScript("/smartadmin/js/plugin/flot/jquery.flot.tooltip.js", generatePageGraphs);
	}
	generatePageGraphs();
	if ($('#pie-chart').length) {
    	var data = [
	    {label: "Ok", data:150},
	    {label: "Warning", data:100},
	    {label: "Critical", data:250},
	    {label: "Unknown", data:1},
		];
		var options = {
			series: {
				pie: {
					show: true,
					radius: "auto",	// actual radius of the visible pie (based on full calculated radius if <=1, or hard pixel value)
					innerRadius: 0.2, /* for donut */
					startAngle: 3/2,
					tilt: 1,
					shadow: {
						left: 15,	// shadow left offset
						top: 25,	// shadow top offset
						alpha: 0.8	// shadow alpha
					},
					offset: {
						top: 0,
						left: "auto"
					},
					stroke: {
						color: "#fff",
						width: 1
					},
					label: {
						show: true,
						formatter: function(label, slice) {
							/*
							return "<div style='font-size:x-small;text-align:center;padding:2px;color:" + slice.color + ";'>" + label + "<br/>" + slice.percent.toFixed(2)+ "% ("+slice.data[0][1]+")</div>";
							*/
							return "<div style='font-size:x-small;text-align:center;padding:2px;color:#4C4F53;'>" + label + "<br/>" + slice.percent.toFixed(2)+ "% ("+slice.data[0][1]+")</div>";
						},	// formatter function
						radius: 1,	// radius at which to place the labels (based on full calculated radius if <=1, or hard pixel value)
						background: {
	                        opacity: 0.8,
	                        color: '#ffffff'
	                    },
						threshold: 0	// percentage at which to hide the label (i.e. the slice is too narrow)
					},
					combine: {
						threshold: -1,	// percentage at which to combine little slices into one larger slice
						color: null,	// color to give the new slice (auto-generated if null)
						label: "Other"	// label to give the new slice
					},
					highlight: {
						//color: "#fff",		// will add this functionality once parseColor is available
						opacity: 0.5
					},

				}
			},
			legend: {
				show: false,
			},
			colors: ['#47A447', '#ED9C28', '#D2322D', '#EBEBEB']
		};

		$.plot($("#pie-chart"), data, options);

	}

	function generatePageGraphs() {

	    /* TAB 1: UPDATING CHART */
	    // For the demo we use generated data, but normally it would be coming from the server

	    var data = [],
	        totalPoints = 200,
	        $UpdatingChartColors = $("#updating-chart").css('color');

	    function getRandomData() {
	        if (data.length > 0)
	            data = data.slice(1);

	        // do a random walk
	        while (data.length < totalPoints) {
	            var prev = data.length > 0 ? data[data.length - 1] : 50;
	            var y = prev + Math.random() * 10 - 5;
	            if (y < 0)
	                y = 0;
	            if (y > 100)
	                y = 100;
	            data.push(y);
	        }

	        // zip the generated y values with the x values
	        var res = [];
	        for (var i = 0; i < data.length; ++i)
	            res.push([i, data[i]])
	        return res;
	    }

	    // setup control widget
	    var updateInterval = 1500;

	    /*end updating chart*/

	    /* TAB 2: Host Details */



	    // END TAB 2

	    // TAB THREE GRAPH //
	    /* TAB 3: Revenew  */

	    $(function () {

	        var trgt = [
	            [1354586000000, 153],
	            [1364587000000, 658],
	            [1374588000000, 198],
	            [1384589000000, 663],
	            [1394590000000, 801],
	            [1404591000000, 1080],
	            [1414592000000, 353],
	            [1424593000000, 749],
	            [1434594000000, 523],
	            [1444595000000, 258],
	            [1454596000000, 688],
	            [1464597000000, 364]
	        ],
	            rta = [
	                [1394612280000, 83],
	                [1394612880000, 20],
	                [1394613480000, 25],
	                [1394614080000, 20],
	                [1394614680000, 10],
	                [1394615280000, 23],
	                [1394615880000, 79],
	                [1394616480000, 88],
	                [1394617080000, 36]
	            ],
	            sgnups = [
	                [1354586000000, 647],
	                [1364587000000, 435],
	                [1374588000000, 784],
	                [1384589000000, 346],
	                [1394590000000, 487],
	                [1404591000000, 463],
	                [1414592000000, 479],
	                [1424593000000, 236],
	                [1434594000000, 843],
	                [1444595000000, 657],
	                [1454596000000, 241],
	                [1464597000000, 341]
	            ],
	            toggles = $("#rev-toggles"),
	            target = $("#flotcontainer");

	        var data = [{
	            label: "Target Profit",
	            data: trgt,
	            bars: {
	                show: true,
	                align: "center",
	                barWidth: 30 * 30 * 60 * 1000 * 80
	            }
	        }, {
	            label: "rta",
	            data: rta,
	            lines: {
	                show: true,
	                lineWidth: 1,
	                fill: .5
	            },
	            points: {
	                show: true
	            }
	        }, {
	            label: "Actual Signups",
	            data: sgnups,
	            color: '#71843F',
	            lines: {
	                show: true,
	                lineWidth: 1
	            },
	            points: {
	                show: true
	            }
	        }]

	        var options = {
	        	colors: [$("#updating-chart").css('color')],
	            grid: {
	                hoverable: true
	            },
	            tooltip: true,
	            tooltipOpts: {
	                //content: '%x - %y',
	                //dateFormat: '%b %y',
	                defaultTheme: false
	            },
	            xaxis: {
	                mode: "time"
	            },
	            yaxes: {
	                tickFormatter: function (val, axis) {
	                    return "$" + val;
	                },
	                max: 1200
	            }

	        };

	        plot2 = null;

	        function plotNow() {
	            var d = [];
	            toggles.find(':checkbox').each(function () {
	                if ($(this).is(':checked')) {
	                    d.push(data[$(this).attr("name").substr(4, 1)]);
	                }
	            });
	            if (d.length > 0) {
	                if (plot2) {
	                    plot2.setData(d);
	                    plot2.draw();
	                } else {
	                    plot2 = $.plot(target, d, options);
	                }
	            }

	        };

	        toggles.find(':checkbox').on('change', function () {
	            plotNow();
	        });
	        plotNow()

	    });

	}

	/*
	 * VECTOR MAP
	 */

	data_array = {
	    "US": 80,
	    "AU": 4873,
	    "IN": 3671,
	    "BR": 40,
	    "TR": 1476,
	    "CN": 146,
	    "CA": 155,
	    "BD": 100,
	    "DE": 134
	 //   "DE": 'OK : 150, DOWN: 10, UNREACHABLE: 3'
	};

	// Load Map dependency 1 then call for dependency 2
	loadScript("/smartadmin/js/plugin/vectormap/jquery-jvectormap-1.2.2.min.js", loadMapFile);

	// Load Map dependency 2 then rendeder Map
	function loadMapFile() {
	    loadScript("/smartadmin/js/plugin/vectormap/jquery-jvectormap-world-mill-en.js", renderVectorMap);
	    loadScript("/smartadmin/js/plugin/vectormap/jquery-jvectormap-de-mill-en.js", renderVectorMapGermany);
	}


	function renderVectorMap() {
	    $('#vector-map').vectorMap({
	        map: 'world_mill_en',
	        backgroundColor: '#fff',
	        regionStyle: {
	            initial: {
	                fill: '#c4c4c4'
	            },
	            hover: {
	                "fill-opacity": 1
	            }
	        },
	        series: {
	            regions: [{
	                values: data_array,
	                scale: ['#85a8b6', '#4d7686'],
	                normalizeFunction: 'polynomial'
	            }]
	        },
	        markerStyle: {
		      initial: {
		        fill: '#800000',
		        stroke: '#383f47'
		      }
		    },
		    markers: [
		    	{latLng: [41.50, -87.37], 	name: 'Chicago | OK:20 DOWN:5', type : "chicago"},
		    //  	{latLng: [39.16, -84.46], 	name: 'Test2 - 2010'},
		    //  	{latLng: [39.25, -84.46], 	name: 'Test3 - 2010'},
		      	{latLng: [50.55, 9.68], 	name: 'Fulda | OK:80 DOWN:5 UNREACHABLE: 2'},
		      	{latLng: [49.45, 11.07], 	name: 'Nürnberg  | OK:60'},
		      	{latLng: [50.77, 6.08], 	name: 'Aachen | OK:20 DOWN:1 UNREACHABLE: 1'},
		    ],
	        onRegionLabelShow: function (e, el, code) {
	            if (typeof data_array[code] == 'undefined') {
	                e.preventDefault();
	            } else {
	                var countrylbl = data_array[code];
	                el.html(el.html() + ': Hosts: ' + countrylbl);
	            }
	        }
	    });


	}

	data_array_de = {
	    "DE-BY": 80,
	    "DE-NW": 4873,
	    "DE-HE": 500,
	 //   "DE": 'OK : 150, DOWN: 10, UNREACHABLE: 3'
	};

	function renderVectorMapGermany() {
		$('#vector-map-germany').vectorMap({
	        map: 'de_mill_en',
	        backgroundColor: '#fff',
	        regionStyle: {
	            initial: {
	                fill: '#c4c4c4'
	            },
	            hover: {
	                "fill-opacity": 1
	            }
	        },
	        series: {
	            regions: [{
	                values: data_array_de,
	                scale: ['#85a8b6', '#4d7686'],
	                normalizeFunction: 'polynomial'
	            }]
	        },
	        markerStyle: {
		      initial: {
		        fill: '#800000',
		        stroke: '#383f47'
		      }
		    },
		    markers: [
		    //	{latLng: [41.50, -87.37], 	name: 'Chicago | OK:20 DOWN:5', type : "chicago"},
		    //  	{latLng: [39.16, -84.46], 	name: 'Test2 - 2010'},
		    //  	{latLng: [39.25, -84.46], 	name: 'Test3 - 2010'},
		      	{latLng: [50.55, 9.68], 	name: 'Fulda | OK:80 DOWN:5 UNREACHABLE: 2'},
		      	{latLng: [49.45, 11.07], 	name: 'Nürnberg  | OK:60'},
		      	{latLng: [50.77, 6.08], 	name: 'Aachen | OK:20 DOWN:1 UNREACHABLE: 1'},
		    ],
	        onRegionLabelShow: function (e, el, code) {
	            if (typeof data_array_de[code] == 'undefined') {
	                e.preventDefault();
	            } else {
	                var countrylbl = data_array_de[code];
	                el.html(el.html() + ': Hosts: ' + countrylbl);
	            }
	        }
	    });
	}

	/*
	 * FULL CALENDAR JS
	 */

	// Load Calendar dependency then setup calendar
	//loadScript("/smartadmin/js/plugin/fullcalendar/jquery.fullcalendar.min.js", setupCalendar);

	setupCalendar();
	function setupCalendar() {

	    if ($("#calendar").length) {
	        var date = new Date();
	        var d = date.getDate();
	        var m = date.getMonth();
	        var y = date.getFullYear();

	        var calendar = $('#calendar').fullCalendar({

	            editable: true,
	            draggable: true,
	            selectable: false,
	            selectHelper: true,
	            unselectAuto: false,
	            disableResizing: false,

	            header: {
	                left: 'title', //,today
	                center: 'prev, next, today',
	                right: 'month, agendaWeek, agenDay' //month, agendaDay,
	            },

	            select: function (start, end, allDay) {
	                var title = prompt('Event Title:');
	                if (title) {
	                    calendar.fullCalendar('renderEvent', {
	                            title: title,
	                            start: start,
	                            end: end,
	                            allDay: allDay
	                        }, true // make the event "stick"
	                    );
	                }
	                calendar.fullCalendar('unselect');
	            },
	            events: [{
	                title: 'Wartungsarbeiten Rechenzentrum',
	                start: new Date(y, m, 1),
	                description: '(Switche | Server)',
	                className: ["event", "bg-color-greenLight"],
	                icon: 'fa-check'
	            }, {
	                title: 'localhost (127.0.0.1)',
	                start: new Date(y, m, d - 5),
	                end: new Date(y, m, d - 2),
	                className: ["event", "bg-color-redLight"],
	                icon: 'fa fa-desktop'
	            }, {
	                id: 999,
	                title: 'Regelmäßige Downtime "Localhost:PING-LAN"',
	                start: new Date(y, m, d - 3, 16, 0),
	                allDay: false,
	                className: ["event", "bg-color-blue"],
	                icon: 'fa-clock-o'
	            }, {
	                id: 999,
	                title: 'Regelmäßige Downtime "Localhost:PING-LAN"',
	                start: new Date(y, m, d + 4, 16, 0),
	                allDay: false,
	                className: ["event", "bg-color-blue"],
	                icon: 'fa-clock-o'
	            }, {
	                title: 'srvcom01:PING-LAN',
	                start: new Date(y, m, d, 10, 30),
	                allDay: false,
	                className: ["event", "bg-color-darken"],
	                icon: 'fa-gear'
	            }, {
	                title: 'srvcom02:PING-LAN',
	                start: new Date(y, m, d, 12, 0),
	                end: new Date(y, m, d, 14, 0),
	                allDay: false,
	                className: ["event", "bg-color-darken"],
	                icon: 'fa-gear'
	            }, {
	                title: 'srvcom02:PING-LAN',
	                start: new Date(y, m, d + 1, 19, 0),
	                end: new Date(y, m, d + 1, 22, 30),
	                allDay: false,
	                className: ["event", "bg-color-darken"],
	                icon: 'fa-gear'
	            }, {
	                title: 'localhost (127.0.0.1)',
	                start: new Date(y, m, 28),
	                end: new Date(y, m, 29),
	                className: ["event", "bg-color-redLight"],
	                con: 'fa fa-desktop'
	            }],
	            timeFormat: 'H:mm{ - H:mm}',
	            eventRender: function (event, element, icon) {
	                if (!event.description == "") {
	                    element.find('.fc-event-title').append("<br/><span class='ultra-light'>" + event.description +
	                        "</span>");
	                }
	                if (!event.icon == "") {
	                    element.find('.fc-event-title').append("<i class='air air-top-right fa " + event.icon +
	                        " '></i>");
	                }
	            }
	        });

	    };

	    /* hide default buttons */
	    $('.fc-header-right, .fc-header-center').hide();

	}

	// calendar prev
	$('#calendar-buttons #btn-prev').click(function () {
	    $('.fc-button-prev').click();
	    return false;
	});

	// calendar next
	$('#calendar-buttons #btn-next').click(function () {
	    $('.fc-button-next').click();
	    return false;
	});

	// calendar today
	$('#calendar-buttons #btn-today').click(function () {
	    $('.fc-button-today').click();
	    return false;
	});

	// calendar month
	$('#mt').click(function () {
	    $('#calendar').fullCalendar('changeView', 'month');
	});

	// calendar agenda week
	$('#ag').click(function () {
	    $('#calendar').fullCalendar('changeView', 'agendaWeek');
	});

	// calendar agenda day
	$('#td').click(function () {
	    $('#calendar').fullCalendar('changeView', 'agendaDay');
	});

	/*
	 * CHAT
	 */

//	$.filter_input = $('#filter-chat-list');
//	$.chat_users_container = $('#chat-container > .chat-list-body')
//	$.chat_users = $('#chat-users')
//	$.chat_list_btn = $('#chat-container > .chat-list-open-close');
//	$.chat_body = $('#chat-body');

	/*
	 * LIST FILTER (CHAT)
	 */

	// custom css expression for a case-insensitive contains()
/*
	jQuery.expr[':'].Contains = function (a, i, m) {
	    return (a.textContent || a.innerText || "").toUpperCase().indexOf(m[3].toUpperCase()) >= 0;
	};

	function listFilter(list) { // header is any element, list is an unordered list
	    // create and add the filter form to the header

	    $.filter_input.change(function () {
	        var filter = $(this).val();
	        if (filter) {
	            // this finds all links in a list that contain the input,
	            // and hide the ones not containing the input while showing the ones that do
	            $.chat_users.find("a:not(:Contains(" + filter + "))").parent().slideUp();
	            $.chat_users.find("a:Contains(" + filter + ")").parent().slideDown();
	        } else {
	            $.chat_users.find("li").slideDown();
	        }
	        return false;
	    }).keyup(function () {
	        // fire the above change event after every letter
	        $(this).change();

	    });

	}

	// on dom ready
	listFilter($.chat_users);

	// open chat list
	$.chat_list_btn.click(function () {
	    $(this).parent('#chat-container').toggleClass('open');
	})

	$.chat_body.animate({
	    scrollTop: $.chat_body[0].scrollHeight
	}, 500);
*/
</script>
