<?php echo $this->Form->create('BoostCake', [
    'inputDefaults' => [
        'div'       => 'control-group',
        'label'     => [
            'class' => 'control-label',
        ],
        'wrapInput' => 'controls',
    ],
    'class'         => 'well form-horizontal',
]); ?>
<?php echo $this->Form->input('email', [
    'placeholder' => 'Email',
]); ?>
<?php echo $this->Form->input('password', [
    'placeholder' => 'Password',
]); ?>
<?php echo $this->Form->input('remember', [
    'label'      => 'Remember me',
    'afterInput' => $this->Form->submit('Sign in', [
        'class' => 'btn',
    ]),
]); ?>
<?php echo $this->Form->end(); ?>