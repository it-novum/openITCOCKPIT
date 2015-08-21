<?php echo $this->Form->create('BoostCake', array(
	'inputDefaults' => array(
		'div' => 'control-group',
		'label' => array(
			'class' => 'control-label'
		),
		'wrapInput' => 'controls'
	),
	'class' => 'well form-horizontal'
)); ?>
	<?php echo $this->Form->input('email', array(
		'placeholder' => 'Email'
	)); ?>
	<?php echo $this->Form->input('password', array(
		'placeholder' => 'Password'
	)); ?>
	<?php echo $this->Form->input('remember', array(
		'label' => 'Remember me',
		'afterInput' => $this->Form->submit('Sign in', array(
			'class' => 'btn'
		))
	)); ?>
<?php echo $this->Form->end(); ?>