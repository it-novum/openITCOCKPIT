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
<?php echo $this->Form->input('select', [
    'label'   => [
        'text' => 'Select Nested Options',
    ],
    'empty'   => '選択してください',
    'options' => [
        '東京' => [
            1 => '渋谷',
            2 => '秋葉原',
        ],
        '大阪' => [
            3 => '梅田',
            4 => '難波',
        ],
    ],
]); ?>
<?php echo $this->Form->input('select', [
    'label'    => [
        'text' => 'Select Nested Options Checkbox',
    ],
    'class'    => 'checkbox-inline',
    'multiple' => 'checkbox',
    'options'  => [
        '東京' => [
            1 => '渋谷',
            2 => '秋葉原',
        ],
        '大阪' => [
            3 => '梅田',
            4 => '難波',
        ],
    ],
]); ?>
<?php echo $this->Form->input('radio', [
    'type'    => 'radio',
    'before'  => '<label class="col col-md-3 control-label">Radio</label>',
    'legend'  => false,
    'class'   => false,
    'options' => [
        1 => 'Option one is this and that—be sure to include why it\'s great',
        2 => 'Option two can be something else and selecting it will deselect option one',
    ],
]); ?>
<?php echo $this->Form->input('username', [
    'placeholder' => 'Username',
    'label'       => [
        'text' => 'Prepend',
    ],
    'beforeInput' => '<div class="input-group"><span class="input-group-addon">@</span>',
    'afterInput'  => '</div>',
]); ?>
<?php echo $this->Form->input('price', [
    'label'       => [
        'text' => 'Append',
    ],
    'beforeInput' => '<div class="input-group">',
    'afterInput'  => '<span class="input-group-addon">.00</span></div>',
]); ?>
<?php echo $this->Form->input('price_error', [
    'label'       => [
        'text' => 'Append Error',
    ],
    'beforeInput' => '<div class="input-group">',
    'afterInput'  => '<span class="input-group-addon">.00</span></div>',
]); ?>
<?php echo $this->Form->input('password', [
    'label'       => [
        'text' => 'Show Error Message',
    ],
    'placeholder' => 'Password',
]); ?>
<?php echo $this->Form->input('password', [
    'label'        => [
        'text' => 'Hide Error Message',
    ],
    'placeholder'  => 'Password',
    'errorMessage' => false,
]); ?>
<?php echo $this->Form->input('checkbox', [
    'wrapInput'  => 'col col-md-9 col-md-offset-3',
    'label'      => ['class' => null],
    'class'      => false,
    'afterInput' => '<span class="help-block">Checkbox Bootstrap Style</span>',
]); ?>
<?php echo $this->Form->input('checkbox', [
    'before'     => '<label class="col col-md-3 control-label">Checkbox</label>',
    'label'      => false,
    'class'      => false,
    'afterInput' => '<span class="help-block">Checkbox CakePHP Style</span>',
]); ?>
<div class="form-group">
    <div class="col col-md-9 col-md-offset-3">
        <?php echo $this->Form->submit('Save changes', [
            'div'   => false,
            'class' => 'btn btn-primary',
        ]); ?>
        <button type="button" class="btn btn-default">Cancel</button>
    </div>
</div>
<?php echo $this->Form->end(); ?>
