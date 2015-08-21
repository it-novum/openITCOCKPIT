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
<?php $this->Paginator->options(array('url' => $this->params['named'])); ?>
<div class="row">
	<div class="col-xs-12 col-sm-7 col-md-7 col-lg-6">
		<h1 class="page-title txt-color-blueDark">
			<i class="fa fa-retweet fa-fw "></i> 
				<?php echo __('Administration')?> 
			<span>> 
				<?php echo __('refresh monitoring configuration'); ?>
			</span>
		</h1>
	</div>
</div>
<div id="error_msg"></div>
<section id="widget-grid" class="">
	<div class="row">
		<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
			<div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-1" data-widget-editbutton="false" >
				<header>
					<div class="widget-toolbar" role="menu">
						<?php echo $this->AdditionalLinks->renderAsLinks($additionalLinksTop); ?>
					</div>
					<div class="jarviswidget-ctrls" role="menu">
					</div>
					<span class="widget-icon"> <i class="fa fa-retweet"></i> </span>
					<h2><?php echo __('Refresh monitoring configuration'); ?> </h2>
				</header>

				<div>
					<div class="jarviswidget-editbox">
					</div>
					<div class="widget-body">
							<div class="form-group ">
								<label class="col col-md-2 control-label text-left" for="CreateBackup"><i class="fa fa-hdd-o"></i> <?php echo __('Create backup?');?></label>
								<div class="col col-md-1">
									<div class="">
										<span class="onoffswitch">
											<input type="hidden" value="0" id="CreateBackup_" name="data[Export][create_backup]">
											<input type="checkbox" id="CreateBackup" showlabel="1" value="1" checked="checked"  class="onoffswitch-checkbox notification_control" name="data[Export][create_backup]">
												<label class="onoffswitch-label" for="CreateBackup">
													<span class="onoffswitch-inner" data-swchon-text="<?php echo __('On'); ?>" data-swchoff-text="<?php echo __('Off'); ?>"></span>
													<span class="onoffswitch-switch"></span>
												</label>
										</span>
									</div>
								</div>
							</div>
							<br />
							<br />
							<div class="progress progress-striped active" id="exportProgressbar" style="display:none;">
								<?php echo $this->Html->progressbar(100, [
									'useThresholds' => false,
									'bgColor' => 'bg-color-purple',
									'caption' => __('In progress'),
									'color' => '#FFF'
								]); ?>
							</div>
							<div id="logoutput" class="padding-left-20" style="display:none;">
								<h4><?php echo __('Additional information'); ?>:</h4>
								<br />
							</div>
							<br />
							<br />
							<div class="well formactions ">
								<div class="pull-right">
									<input type="submit" id="exportAll" value="<?php echo __('Refresh configuration'); ?>" class="btn btn-success">
									&nbsp;
									<a class="btn btn-default" href="/services"><?php echo __('Cancel'); ?></a>
								</div>
							</div>
					</div>
				</div>
			</div>
		</article>
	</div>
</section>