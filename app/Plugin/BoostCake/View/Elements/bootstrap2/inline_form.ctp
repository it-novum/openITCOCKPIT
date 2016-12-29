<?php echo $this->Form->create('BoostCake', [
    'inputDefaults' => [
        'div'       => false,
        'label'     => false,
        'wrapInput' => false,
    ],
    'class'         => 'well form-inline',
]); ?>
<?php echo $this->Form->input('email', [
    'class'       => 'input-small',
    'placeholder' => 'Email',
]); ?>
<?php echo $this->Form->input('password', [
    'class'       => 'input-small',
    'placeholder' => 'Password',
]); ?>
<?php echo $this->Form->input('remember', [
    'label'       => [
        'text'  => 'Remember me',
        'class' => 'checkbox',
    ],
    'checkboxDiv' => false,
]); ?>
<?php echo $this->Form->submit('Sign in', [
    'div'   => false,
    'class' => 'btn',
]); ?>
<?php echo $this->Form->end(); ?>