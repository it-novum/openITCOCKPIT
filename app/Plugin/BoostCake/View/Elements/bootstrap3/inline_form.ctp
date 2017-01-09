<?php echo $this->Form->create('BoostCake', [
    'inputDefaults' => [
        'div'       => 'form-group',
        'label'     => false,
        'wrapInput' => false,
        'class'     => 'form-control',
    ],
    'class'         => 'well form-inline',
]); ?>
<?php echo $this->Form->input('email', [
    'placeholder' => 'Email',
]); ?>
<?php echo $this->Form->input('password', [
    'placeholder' => 'Password',
]); ?>
<?php echo $this->Form->input('remember', [
    'div'   => 'checkbox',
    'class' => false,
    'label' => 'Remember me',
]); ?>
<?php echo $this->Form->submit('Sign in', [
    'div'   => 'form-group',
    'class' => 'btn btn-default',
]); ?>
<?php echo $this->Form->end(); ?>