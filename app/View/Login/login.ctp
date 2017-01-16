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
<div class="well no-padding" style="margin-top: 60px">
<<<<<<< HEAD
    <?php echo $this->Form->create('LoginUser', [
        'url'           => '/login/login',
        'id'            => 'login-form',
        'class'         => 'smart-form client-form',
        'inputDefaults' => [
            'wrapInput' => false,
            'label'     => false,
            'div'       => false,
        ],
    ]); ?>
    <header><i class="fa fa-sign-in fa-lg txt-color-blueLight"></i> <?php echo __('Sign In'); ?></header>
    <fieldset>
        <?php if ($displayMethod === true): ?>
            <section>
                <label class="label"><?php echo __('Authentication method'); ?>:</label>
                <label class="input">
                    <?php echo $this->Form->input('auth_method', ['options' => $authMethods, 'selected' => $selectedMethod]); ?>
                </label>
            </section>
        <?php endif; ?>
        <section>
            <label class="label"><?php echo __('Username'); ?>:</label>
            <label class="input"> <i class="icon-append fa fa-user"></i>
                <?php echo $this->Form->input('samaccountname'); ?>
                <b class="tooltip tooltip-top-right"><i
                            class="fa fa-user txt-color-teal"></i> <?php echo __('Please enter your LDAP Username'); ?>
                </b>
            </label>
        </section>
        <section>
            <label class="label"><?php echo __('Email'); ?>:</label>
            <label class="input"> <i class="icon-append fa fa-user"></i>
                <?php echo $this->Form->input('email'); ?>
                <b class="tooltip tooltip-top-right"><i
                            class="fa fa-user txt-color-teal"></i> <?php echo __('Please enter your email address'); ?>
                </b>
            </label>
        </section>
        <section>
            <label class="label"><?php echo __('Password'); ?>:</label>
            <label class="input"> <i class="icon-append fa fa-lock"></i>
                <?php echo $this->Form->input('password'); ?>
                <b class="tooltip tooltip-top-right"><i
                            class="fa fa-lock txt-color-teal"></i> <?php echo __('Please enter your password'); ?></b>
            </label>
        </section>
        <section>
            <label for="LoginUserRememberMe" class="checkbox">
                <?php
                echo $this->Form->input('remember_me', [
                    'type'        => 'checkbox',
                    'checkboxDiv' => false,
                ]);
                ?>
                <i></i><?php echo __('Stay signed in'); ?>
            </label>
        </section>
    </fieldset>
    <footer>
        <button type="submit" class="btn btn-success">
            <i class="fa fa-sign-in"></i> <?php echo __('Sign in'); ?>
        </button>
        <a href="/login/login" class="btn btn-danger">
            <i class="fa fa-times"></i> <?php echo __('Cancel'); ?>
        </a>
    </footer>
    <div>
        <ul class="list-inline text-center">
            <li>
                <a class="btn btn-default btn-circle" sn="twitter" target="_blank"
                   href="https://twitter.com/openITCOCKPIT" title="Follow us on Twitter"><i
                            class="fa fa-twitter"></i></a>
            </li>
            <li>
                <a class="btn btn-default btn-circle" sn="google+" target="_blank"
                   href="https://plus.google.com/114164613553417237066" title="Follow us on Google plus"><i
                            class="fa fa-google-plus"></i></a>
            </li>
            <li>
                <a class="btn btn-default btn-circle" sn="youtube" target="_blank"
                   href="https://www.youtube.com/user/openitcockpit" title="Follow us on YouTube"><i
                            class="fa fa-youtube"></i></a>
            </li>
            <li>
                <a class="btn btn-default btn-circle" sn="facebook" target="_blank"
                   href="https://www.facebook.com/openitcockpit" title="Follow us on Facebook"><i
                            class="fa fa-facebook"></i></a>
            </li>
        </ul>
        <br/>
    </div>
    <?php echo $this->Form->end(); ?>
=======
	<?php echo $this->Form->create('LoginUser', array(
		'url' => '/login/login',
		'id' => 'login-form',
		'class' => 'smart-form client-form',
		'inputDefaults' => array(
			'wrapInput' => false,
			'label' => false,
			'div' => false
		)
	)); ?>
		<header><i class="fa fa-sign-in fa-lg txt-color-blueLight"></i> <?php echo __('Sign In'); ?></header>
		<fieldset>
			<?php if($displayMethod === true): ?>
				<section>
					<label class="label"><?php echo __('Authentication method'); ?>:</label>
					<label class="input">
						<?php echo $this->Form->input('auth_method', ['options' => $authMethods, 'selected' => $selectedMethod]); ?>
					</label>
				</section>
			<?php endif;?>
			<section>
				<label class="label"><?php echo __('Username'); ?>:</label>
				<label class="input"> <i class="icon-append fa fa-user"></i>
					<?php echo $this->Form->input('samaccountname'); ?>
					<b class="tooltip tooltip-top-right"><i class="fa fa-user txt-color-teal"></i> <?php echo __('Please enter your LDAP Username'); ?></b>
				</label>
			</section>
			<section>
				<label class="label"><?php echo __('Email'); ?>:</label>
				<label class="input"> <i class="icon-append fa fa-user"></i>
					<?php echo $this->Form->input('email'); ?>
					<b class="tooltip tooltip-top-right"><i class="fa fa-user txt-color-teal"></i> <?php echo __('Please enter your email address'); ?></b>
				</label>
			</section>
			<section>
				<label class="label"><?php echo __('Password'); ?>:</label>
				<label class="input"> <i class="icon-append fa fa-lock"></i>
					<?php echo $this->Form->input('password'); ?>
					<b class="tooltip tooltip-top-right"><i class="fa fa-lock txt-color-teal"></i> <?php echo __('Please enter your password'); ?></b> 
				</label>
			</section>
			<section>
				<label for="LoginUserRememberMe" class="checkbox">
					<?php
					echo $this->Form->input('remember_me', [
						'type' => 'checkbox',
						'checkboxDiv' => false,
					]);
					?>
					<i></i><?php echo __('Stay signed in');?>
				</label>
			</section>
		</fieldset>
		<footer>
			<button type="submit" class="btn btn-success">
				<i class="fa fa-sign-in"></i> <?php echo __('Sign in'); ?>
			</button>
			<a href="/login/login" class="btn btn-danger">
				<i class="fa fa-times"></i> <?php echo __('Cancel'); ?>
			</a>
			<?php foreach($ssoButtons as $ssoButton): ?>
				<a href="<?= $ssoButton['href'] ?>" class="btn btn-primary pull-left">
					<?= $ssoButton['text'] ?>
				</a>
			<?php endforeach; ?>
		</footer>
		<div>
			<ul class="list-inline text-center">
				<li>
					<a class="btn btn-default btn-circle" sn="twitter" target="_blank" href="https://twitter.com/openITCOCKPIT" title="Follow us on Twitter"><i class="fa fa-twitter"></i></a>
				</li>
				<li>
					<a class="btn btn-default btn-circle" sn="google+" target="_blank" href="https://plus.google.com/114164613553417237066" title="Follow us on Google plus"><i class="fa fa-google-plus"></i></a>
				</li>
				<li>
					<a class="btn btn-default btn-circle" sn="youtube" target="_blank" href="https://www.youtube.com/user/openitcockpit" title="Follow us on YouTube"><i class="fa fa-youtube"></i></a>
				</li>
				<li>
					<a class="btn btn-default btn-circle" sn="facebook" target="_blank" href="https://www.facebook.com/openitcockpit" title="Follow us on Facebook"><i class="fa fa-facebook"></i></a>
				</li>
			</ul>
			<br />
		</div>
	<?php echo $this->Form->end(); ?>
>>>>>>> 5cea74ba9e632b57c9d96b28c934f69f9714fa75
</div>
