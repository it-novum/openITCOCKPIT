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
    'class'    => 'checkbox inline',
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
    'before'  => '<label class="control-label">Radio</label>',
    'legend'  => false,
    'options' => [
        1 => 'Option one is this and that—be sure to include why it\'s great',
        2 => 'Option two can be something else and selecting it will deselect option one',
    ],
]); ?>
<?php echo $this->Form->input('username', [
    'placeholder' => 'Username',
    'div'         => 'control-group',
    'label'       => [
        'text' => 'Prepend',
    ],
    'beforeInput' => '<div class="input-prepend"><span class="add-on">@</span>',
    'afterInput'  => '</div>',
]); ?>
<?php echo $this->Form->input('price', [
    'label'       => [
        'text' => 'Append',
    ],
    'beforeInput' => '<div class="input-append">',
    'afterInput'  => '<span class="add-on">.00</span></div>',
]); ?>
<?php echo $this->Form->input('price_error', [
    'label'       => [
        'text' => 'Append Error',
    ],
    'beforeInput' => '<div class="input-append">',
    'afterInput'  => '<span class="add-on">.00</span></div>',
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
    'label'      => ['class' => null],
    'afterInput' => '<span class="help-block">Checkbox Bootstrap Style</span>',
]); ?>
<?php echo $this->Form->input('checkbox', [
    'div'        => false,
    'label'      => false,
    'before'     => '<label class="control-label">Checkbox</label>',
    'wrapInput'  => 'controls',
    'afterInput' => '<span class="help-block">Checkbox CakePHP Style</span>',
]); ?>
    <div class="form-actions">
        <?php echo $this->Form->submit('Save changes', [
            'div'   => false,
            'class' => 'btn btn-primary',
        ]); ?>
        <button type="button" class="btn">Cancel</button>
    </div>
<?php echo $this->Form->end(); ?>