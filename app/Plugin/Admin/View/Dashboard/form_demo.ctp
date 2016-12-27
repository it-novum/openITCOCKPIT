<pre><code class="language-php line-numbers">&lt;?php
$foo = 'abc';
$foo = new Bar();
Http::Request('http://openitcockpit.org');
</code></pre>

<?php


echo $this->Form->create('User', [
    'class' => 'form-horizontal clearfix',
]);
echo $this->Form->input('normal_text', [
    'label' => 'Normal Text Input',
]);
echo $this->Form->input('textarea_input', [
    'label' => 'Textarea Input',
    'type'  => 'textarea',
]);
echo $this->Form->input('simple_select', [
    'label'   => 'Simple Select',
    'options' => [
        'option_1' => 'Option 1',
        'option_2' => 'Option 2',
    ],
]);
echo $this->Form->input('fancy_select', [
    'label'   => 'Fancy Select',
    'options' => Configure::read('countries'),
    'class'   => 'chosen',
    'style'   => 'width: 100%',
]);

echo $this->Form->input('prepended_text', [
    'label'   => 'Prepended Text',
    'prepend' => '€',
]);
echo $this->Form->input('appended_text', [
    'label'  => 'Appended Text',
    'append' => '.00',
]);
echo $this->Form->input('prepended_appended_text', [
    'label'   => 'Prepended & Appended Text',
    'prepend' => '€',
    'append'  => '.00',
]);
echo $this->Form->input('multiple', [
    'options'  => [
        'option_1' => 'Option 1',
        'option_2' => 'Option 2',
        'option_3' => 'Option 3',
        'option_4' => 'Option 4',
    ],
    'multiple' => true,
]);
echo $this->Form->input('multiple_checkbox', [
    'options'  => [
        'option_1' => 'Option 1',
        'option_2' => 'Option 2',
        'option_3' => 'Option 3',
        'option_4' => 'Option 4',
    ],
    'multiple' => 'checkbox',
]);
echo $this->Form->input('multiple_fancy', [
    'options'  => [
        'option_1' => 'Option 1',
        'option_2' => 'Option 2',
        'option_3' => 'Option 3',
        'option_4' => 'Option 4',
    ],
    'multiple' => true,
    'class'    => 'chosen',
    'style'    => 'width: 100%',
]);
echo $this->Form->input('checkbox', [
    'type'  => 'checkbox',
    'label' => 'Single Checkbox',
    'value' => 1,
]);
echo $this->Form->input('radio', [
    'type'    => 'radio',
    'before'  => '<label class="col col-md-3 control-label">Radio</label>',
    'legend'  => false,
    'class'   => false,
    'options' => [
        1 => 'Option one is this and that—be sure to include why it\'s great',
        2 => 'Option two can be something else and selecting it will deselect option one',
    ],
]);

echo $this->Form->formActions();


?>