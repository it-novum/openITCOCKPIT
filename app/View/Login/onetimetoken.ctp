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
	<?php echo $this->Form->create('Onetimetoken', array(
		'class' => 'lockscreen animated flipInY',
		'inputDefaults' => array(
			'wrapInput' => false,
			'label' => false,
			'div' => false
		)
	));
	?>
		<div class="logo">
			<h1 class="semi-bold"><?php echo $this->html->image('itc_logo_ball.png');?> <?php echo $systemname; ?></h1>
		</div>
		<div>
			<?php //echo $this->html->image('daniel.jpg', array('width' => 120, 'height' => 120));?>
			<i class="fa fa-unlock-alt pull-left" style="font-size: 150px;"></i>
			<div>
				<h1><?php echo __('A One-time password was sent to your email address'); ?></h1>
				<p class="text-muted">
					<?php echo __('Please enter the code out of the email'); ?>
				</p>
				<?php echo $this->Form->hidden('id', array('value' => $user_id)); ?>
				<div class="input-group">
					<?php echo $this->Form->input('onetimetoken', array('placeholder' => __('One-time password'), 'tabindex' => '1')); ?>
					<div class="input-group-btn">
						<button class="btn btn-primary" type="submit">
							<i class="fa fa-key"></i>
						</button>
					</div>
				</div>
			</div>

		</div>
		<p class="font-xs margin-top-5">
			<?php echo __('Copyright'); ?> <a href="http://it-novum.com" target="_blank" ><?php echo __('it-novum GmbH'); ?></a> 2005 - <?php echo date('Y'); ?>
		</p>
	</form>
	<?php echo $this->Form->end(); ?>