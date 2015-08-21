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
<?php
echo $this->Form->create('Documentation', array(
	'class' => 'form-horizontal clear'
));
?>

<div class="row">
	<div class="col-xs-12 col-sm-7 col-md-6 col-lg-6">
		<h1 class="page-title">
			<i class="fa fa-book fa-fw"></i>
				<?php echo __('Documentation'); ?>
		</h1>
	</div>
	<div class="col-xs-12 col-sm-5 col-md-6 col-lg-6">
		<h5>
			<div class="pull-right">
				<a href="/hosts/browser/<?php echo $host['Host']['id']; ?>" class="btn btn-primary btn-sm"><i class="fa fa-arrow-circle-left"></i> <?php echo $this->Html->underline('b', __('Back to Host')); ?></a>
				<?php echo $this->element('host_browser_menu'); ?>
			</div>
		</h5>
	</div>
</div>

<div id="error_msg"></div>

<ul id="myTab1" class="nav nav-tabs bordered">
	<li class="active">
		<a href="#s1" data-toggle="tab"><i class="fa fa-file-text-o fa-fw "></i> <?php echo __('View'); ?></a>
	</li>
	<li>
		<a href="#s2" data-toggle="tab"><i class="fa fa-pencil-square-o"></i> <?php echo __('Edit'); ?></a>
	</li>
</ul>

<div id="myTabContent1" class="tab-content padding-10">
	<div class="tab-pane fade in active" id="s1">
		<div class="row">
			<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<div class="jarviswidget" >
					<?php if(!empty($post)): ?>
						<header>
							<h2><strong class="padding-right-10"><?php echo __('Modified:');?> <?php echo $this->Time->format($post['Documentation']['modified'], $this->Auth->user('dateformat'), false, $this->Auth->user('timezone')); ?></strong></h2>
						</header>
						<div>
							<div class="widget-body">
								<?php echo $this->Bbcode->asHtml($post['Documentation']['content']); ?>
							</div>
						</div>
					<?php else:?>
						<header>
							<h2><strong class="padding-right-10"><i class="fa fa-exclamation-triangle fa-lg txt-color-red"></i> <?php echo __('Empty page'); ?></strong></h2>
						</header>
						<div>
							<div class="widget-body">
								<i class="fa fa-exclamation-triangle fa-lg txt-color-red"></i> <span class="italic"><?php echo __('No documentation yet been written for this host. Click on "Create page" to start writing...'); ?></span>
							</div>
						</div>
					<?php endif;?>
				</div>
			</article>
		</div>
	</div>

	<!-- Tab nummer 2 -->
		<div class="tab-pane fade" id="s2">
			<div class="row">
			<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<div class="jarviswidget" >
					<header>
						<?php if(!empty($post)):?>
							<h2><strong class="padding-right-10"><?php echo __('Edit page'); ?></strong></h2>
						<?php else:?>
							<h2><strong class="padding-right-10"><?php echo __('Create page'); ?></strong></h2>
						<?php endif;?>
							<div class="widget-toolbar pull-left" role="menu">
								<div class="btn-group">
									<a href="javascript:void(0);" class="btn btn-xs btn-default"><i class="fa fa-font"></i> <?php echo __('Font size'); ?></a>
									<a href="javascript:void(0);" data-toggle="dropdown" class="btn btn-xs btn-default dropdown-toggle"><span class="caret"></span></a>
									<ul class="dropdown-menu">
										<li>
											<a href="javascript:void(0);" select-fsize="true" fsize="xx-small" ><?php echo __('smallest'); ?></a>
										</li>
										<li>
											<a href="javascript:void(0);" select-fsize="true" fsize="x-small" ><?php echo __('smaller'); ?></a>
										</li>
										<li>
											<a href="javascript:void(0);" select-fsize="true" fsize="small" ><?php echo __('small'); ?></a>
										</li>
										<li class="divider"></li>
										<li>
											<a href="javascript:void(0);" select-fsize="true" fsize="large" ><?php echo __('big'); ?></a>
										</li>
										<li>
											<a href="javascript:void(0);" select-fsize="true" fsize="x-large" ><?php echo __('bigger'); ?></a>
										</li>
										<li>
											<a href="javascript:void(0);" select-fsize="true" fsize="xx-large" ><?php echo __('biggest'); ?></a>
										</li>
									</ul>
								</div>
								<div class="widget-toolbar pull-left" style="border:0px;" role="menu">
									<a href="javascript:void(0);" class="dropdown-toggle color-box selector bg-color-darken" id="currentColor" color="#404040" current-color="bg-color-darken" data-toggle="dropdown"></a>
									<ul class="dropdown-menu arrow-box-up-right color-select pull-right">
										<li style="display: inline-block; margin:0; float: none;"><span data-original-title="<?php echo __('Green Grass'); ?>" data-placement="left" rel="tooltip" data-widget-setstyle="jarviswidget-color-green" select-color="true" color="#356E35" class="bg-color-green"></span></li>
										<li style="display: inline-block; margin:0; float: none;"><span data-original-title="<?php echo __('Dark Green'); ?>" data-placement="top" rel="tooltip" data-widget-setstyle="jarviswidget-color-greenDark" select-color="true" color="#496949" class="bg-color-greenDark"></span></li>
										<li style="display: inline-block; margin:0; float: none;"><span data-original-title="<?php echo __('Light Green'); ?>" data-placement="top" rel="tooltip" data-widget-setstyle="jarviswidget-color-greenLight" select-color="true" color="#71843F" class="bg-color-greenLight"></span></li>
										<li style="display: inline-block; margin:0; float: none;"><span data-original-title="<?php echo __('Purple'); ?>" data-placement="top" rel="tooltip" data-widget-setstyle="jarviswidget-color-purple" select-color="true" color="#6E587A" class="bg-color-purple"></span></li>
										<li style="display: inline-block; margin:0; float: none;"><span data-original-title="<?php echo __('Magenta'); ?>" data-placement="top" rel="tooltip" data-widget-setstyle="jarviswidget-color-magenta" select-color="true" color="#6E3671" class="bg-color-magenta"></span></li>
										<li style="display: inline-block; margin:0; float: none;"><span data-original-title="<?php echo __('Pink'); ?>" data-placement="right" rel="tooltip" data-widget-setstyle="jarviswidget-color-pink" select-color="true" color="#AC5287" class="bg-color-pink"></span></li>
										<li style="display: inline-block; margin:0; float: none;"><span data-original-title="<?php echo __('Fade Pink'); ?>" data-placement="left" rel="tooltip" data-widget-setstyle="jarviswidget-color-pinkDark" select-color="true" color="#A8829F" class="bg-color-pinkDark"></span></li>
										<li style="display: inline-block; margin:0; float: none;"><span data-original-title="<?php echo __('Light Blue'); ?>" data-placement="top" rel="tooltip" data-widget-setstyle="jarviswidget-color-blueLight" select-color="true" color="#92A2A8" class="bg-color-blueLight"></span></li>
										<li style="display: inline-block; margin:0; float: none;"><span data-original-title="<?php echo __('Teal'); ?>" data-placement="top" rel="tooltip" data-widget-setstyle="jarviswidget-color-teal" select-color="true" color="#568A89" class="bg-color-teal"></span></li>
										<li style="display: inline-block; margin:0; float: none;"><span data-original-title="<?php echo __('Ocean Blue'); ?>" data-placement="top" rel="tooltip" data-widget-setstyle="jarviswidget-color-blue" select-color="true" color="#57889C" class="bg-color-blue"></span></li>
										<li style="display: inline-block; margin:0; float: none;"><span data-original-title="<?php echo __('Night Sky'); ?>" data-placement="top" rel="tooltip" data-widget-setstyle="jarviswidget-color-blueDark" select-color="true" color="#4C4F53" class="bg-color-blueDark"></span></li>
										<li style="display: inline-block; margin:0; float: none;"><span data-original-title="<?php echo __('Night'); ?>" data-placement="right" rel="tooltip" data-widget-setstyle="jarviswidget-color-darken" select-color="true" color="#404040" class="bg-color-darken"></span></li>
										<li style="display: inline-block; margin:0; float: none;"><span data-original-title="<?php echo __('Day Light'); ?>" data-placement="left" rel="tooltip" data-widget-setstyle="jarviswidget-color-yellow" select-color="true" color="#B09B5B" class="bg-color-yellow"></span></li>
										<li style="display: inline-block; margin:0; float: none;"><span data-original-title="<?php echo __('Orange'); ?>" data-placement="bottom" rel="tooltip" data-widget-setstyle="jarviswidget-color-orange" select-color="true" color="#C79121" class="bg-color-orange"></span></li>
										<li style="display: inline-block; margin:0; float: none;"><span data-original-title="<?php echo __('Dark Orange'); ?>" data-placement="bottom" rel="tooltip" data-widget-setstyle="jarviswidget-color-orangeDark" select-color="true" color="#A57225" class="bg-color-orangeDark"></span></li>
										<li style="display: inline-block; margin:0; float: none;"><span data-original-title="<?php echo __('Red Rose'); ?>" data-placement="bottom" rel="tooltip" data-widget-setstyle="jarviswidget-color-red" select-color="true" color="#A90329" class="bg-color-red"></span></li>
										<li style="display: inline-block; margin:0; float: none;"><span data-original-title="<?php echo __('Light Red'); ?>" data-placement="bottom" rel="tooltip" data-widget-setstyle="jarviswidget-color-redLight" select-color="true" color="#A65858" class="bg-color-redLight"></span></li>
										<li style="display: inline-block; margin:0; float: none;"><span data-original-title="<?php echo __('Purity'); ?>" data-placement="right" rel="tooltip" data-widget-setstyle="jarviswidget-color-white" select-color="true" color="#FFFFFF" class="bg-color-white"></span></li>
									</ul>
								</div>
								<span class="padding-left-10"></span>
								<a href="javascript:void(0);" class="btn btn-default" wysiwyg="true" task="bold"><i class="fa fa-bold"></i></a>
								<a href="javascript:void(0);" class="btn btn-default" wysiwyg="true" task="italic"><i class="fa fa-italic"></i></a>
								<a href="javascript:void(0);" class="btn btn-default" wysiwyg="true" task="underline"><i class="fa fa-underline"></i></a>
								<span class="padding-left-10"></span>
								<a href="javascript:void(0);" class="btn btn-default" wysiwyg="true" task="left"><i class="fa fa-align-left"></i></a>
								<a href="javascript:void(0);" class="btn btn-default" wysiwyg="true" task="center"><i class="fa fa-align-center"></i></a>
								<a href="javascript:void(0);" class="btn btn-default" wysiwyg="true" task="right"><i class="fa fa-align-right"></i></a>
								<a href="javascript:void(0);" class="btn btn-default" wysiwyg="true" task="justify"><i class="fa fa-align-justify"></i></a>
								<span class="padding-left-10"></span>
								<a href="javascript:void(0);" class="btn btn-default" wysiwyg="true" task="code"><i class="fa fa-code"></i></a>
								<span class="padding-left-10"></span>
								<a href="javascript:void(0);" class="btn btn-default" data-toggle="modal" data-target="#hyerlinkModal"><i class="fa fa-link"></i></a>
							</div>
							<div class="widget-toolbar pull-right" role="menu">
								<button type="submit" class="btn btn-success"><i class="fa fa-save"></i> <?php echo __('Save'); ?></button>
							</div>
					</header>
					<div>
						<div class="jarviswidget-editbox">
							<input class="form-control" type="text">
							<span class="note"><i class="fa fa-check text-success"></i> Change title to update and save instantly!</span>
							
						</div>
						<div class="widget-body">
							<textarea class="form-control" name="data[Documentation][content]" id="docuText" style="width: 100%; height: 1500px;" ><?php if(!empty($post)): echo $post['Documentation']['content']; endif; ?></textarea>
						</div>
					</div>
				</div>
			</article>
		</div>
	</div>
</div>
<?php
if(!empty($post)){
	echo $this->Form->input('id', ['type' => 'hidden', 'value' => $post['Documentation']['id']]);
}
echo $this->Form->input('uuid', ['type' => 'hidden', 'value' => $uuid]);
?>
<?php echo $this->Form->end(); ?>

<div class="modal fade" id="hyerlinkModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
					&times;
				</button>
				<h4 class="modal-title" id="myModalLabel"><?php echo __('Insert hyperlink');?></h4>
			</div>
			<div class="modal-body">

				<div class="row">
					<?php echo $this->Form->input('url', ['label' => __('URL:'), 'placeholder' => 'http://www.openitcockpit.org', 'style="width: 100%;"']); ?>
					<?php echo $this->Form->input('description', ['label' => __('Description:'), 'placeholder' => __('Official page for openITCOCKPIT'), 'style="width: 100%;"']); ?>
				</div>

			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary" id="insertWysiwygHyperlink" data-dismiss="modal">
					<?php echo __('Insert'); ?>
				</button>
				<button type="button" class="btn btn-default" data-dismiss="modal">
					<?php echo __('Cancel'); ?>
				</button>
			</div>
		</div>
	</div>
</div>