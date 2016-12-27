<?php echo $this->Form->create('BoostCake', [
    'inputDefaults' => [
        'div'       => 'form-group',
        'label'     => [
            'class' => 'col col-md-3 control-label',
        ],
        'wrapInput' => 'col col-md-9',
        'class'     => 'form-control',
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
    'wrapInput' => 'col col-md-9 col-md-offset-3',
    'label'     => 'Remember me',
    'class'     => false,
]); ?>
    <div class="form-group">
        <?php echo $this->Form->submit('Sign in', [
            'div'   => 'col col-md-9 col-md-offset-3',
            'class' => 'btn btn-default',
        ]); ?>
    </div>
<?php echo $this->Form->end(); ?>