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
<?php //  Router::url(array('controller' => 'commands', 'action' => 'delete')) ?>
<div class="row">
	<div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
		<h1 class="page-title txt-color-blueDark">
			<i class="fa fa-desktop fa-fw "></i> 
				Nagios 
			<span>> 
				Host
			</span>
			<div class="third_level"> <?php echo ucfirst($this->params['action']); ?></div>
		</h1>
	</div>
</div>
<div id="error_msg"></div>

<div class="jarviswidget" id="wid-id-0">
	<header>
		<span class="widget-icon"> <i class="fa fa-desktop"></i> </span>
		<h2>Edit command</h2>
		<div class="widget-toolbar" role="menu">
			<?php echo $this->Utils->deleteButton(null, $host[0]['Host']['host_object_id']);?>
			<?php echo $this->Utils->backButton(__('Back'), $back_url);?>
		</div>
		<div class="widget-toolbar" role="menu">
				<span class="onoffswitch-title" rel="tooltip" data-placement="top" data-original-title="<?php echo __('auto DNS lookup'); ?>"><i class="fa fa-search"></i></span>
				<span class="onoffswitch">
					<input type="checkbox" id="autoDNSlookup" checked="checked" class="onoffswitch-checkbox" name="onoffswitch">
					<label for="autoDNSlookup" class="onoffswitch-label">
						<span data-swchoff-text="Off" data-swchon-text="On" class="onoffswitch-inner"></span>
						<span class="onoffswitch-switch"></span>
					</label>
				</span>
		</div>
	</header>
	<div>
		<div class="widget-body">
			<?php
				echo $this->Form->create('Command', array(
					'class' => 'form-horizontal clear'
				)); ?>
			<div class="row">
				<div class="col-xs-12 col-sm-12 col-md-12 col-lg-6">
					<span class="note">Basic configuration:</span>
					<?php
						echo $this->Form->input('Host.host_object_id', array('type' => 'hidden', 'value' => $host[0]['Host']['host_object_id']));
						echo $this->Form->input('Objects.name1', array('value' => $host[0]['Objects']['name1'], 'label' => __('Hostname')));
						echo $this->Form->input('Host.alias', array('value' => $host[0]['Host']['alias'], 'label' => __('Description')));
						echo $this->Form->input('Host.address', array('value' => $host[0]['Host']['address'], 'label' => __('Address')));
						echo $this->Form->input('Host.notes', array('value' => $host[0]['Host']['notes'], 'label' => __('Notes')));
					?>
					<div class="form-group">
						<label class="col col-md-2 control-label" for="HostCheckinterval">Checkinterval</label>
						<div class="col col-xs-10">
							<input type="text" id="HostCheckinterval" maxlength="255" value="" class="form-control slider slider-success" name="data[Host][check_interval]"
							data-slider-min="<?php echo Configure::read('NagiosModule.SLIDER_MIN'); ?>"
							data-slider-max="<?php echo Configure::read('NagiosModule.SLIDER_MAX'); ?>" 
							data-slider-value="<?php echo $host[0]['Host']['check_interval']; ?>" 
							data-slider-selection = "before" 
							data-slider-step="<?php echo Configure::read('NagiosModule.SLIDER_STEPSIZE'); ?>">
						</div>
					</div>
					<div class="form-group">
						<label class="col col-md-2 control-label" for="HostCheckinterval">Retryinterval</label>
						<div class="col col-xs-10">
							<input type="text" id="HostCheckinterval" maxlength="255" value="" class="form-control slider slider-primary" name="data[Host][retry_interval]"
							data-slider-min="<?php echo Configure::read('NagiosModule.SLIDER_MIN'); ?>"
							data-slider-max="<?php echo Configure::read('NagiosModule.SLIDER_MAX'); ?>" 
							data-slider-value="<?php echo $host[0]['Host']['retry_interval']; ?>" 
							data-slider-selection = "before" 
							data-slider-handle="round"
							data-slider-step="<?php echo Configure::read('NagiosModule.SLIDER_STEPSIZE'); ?>">
						</div>
					</div>
				</div>
				<div class="col-xs-12 col-sm-12 col-md-12 col-lg-6">
					<span class="note">Expert settings:</span>
					<?php
						echo $this->Form->input('Host.host_object_id', array('type' => 'hidden', 'value' => $host[0]['Host']['host_object_id']));
						echo $this->Form->input('Objects.name1', array('value' => $host[0]['Objects']['name1']));
						echo $this->Form->input('Host.alias', array('value' => $host[0]['Host']['alias']));
						echo $this->Form->input('Host.address', array('value' => $host[0]['Host']['address']));
					?>
					<div class="smart-form">
						<div class="form-group">
							<label class="col col-md-2 control-label" for="HostFlapDetection">Flap detection</label>
							<div class="col col-xs-10">
								<label class="toggle pull-left">
									<input type="checkbox" id="HostFlapDetection" name="data[Host][flap_detection_enabled]" value="true" />
										<i data-swchoff-text="OFF" data-swchon-text="ON"></i>
								</label>
							</div>
						</div>
					</div>
				</div>
			</div>
			<br />
			<?php echo $this->Form->formActions(); ?>
		</div>
	</div>
</div>
<?php
debug($host);
?>