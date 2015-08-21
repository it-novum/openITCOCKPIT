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
	<?php echo $this->Form->input('select', array(
		'label' => array(
			'text' => 'Select Nested Options'
		),
		'empty' => '選択してください',
		'options' => array(
			'東京' => array(
				1 => '渋谷',
				2 => '秋葉原'
			),
			'大阪' => array(
				3 => '梅田',
				4 => '難波'
			)
		),
	)); ?>
	<?php echo $this->Form->input('select', array(
		'label' => array(
			'text' => 'Select Nested Options Checkbox'
		),
		'class' => 'checkbox inline',
		'multiple' => 'checkbox',
		'options' => array(
			'東京' => array(
				1 => '渋谷',
				2 => '秋葉原'
			),
			'大阪' => array(
				3 => '梅田',
				4 => '難波'
			)
		)
	)); ?>
	<?php echo $this->Form->input('radio', array(
		'type' => 'radio',
		'before' => '<label class="control-label">Radio</label>',
		'legend' => false,
		'options' => array(
			1 => 'Option one is this and that—be sure to include why it\'s great',
			2 => 'Option two can be something else and selecting it will deselect option one'
		)
	)); ?>
	<?php echo $this->Form->input('username', array(
		'placeholder' => 'Username',
		'div' => 'control-group',
		'label' => array(
			'text' => 'Prepend',
		),
		'beforeInput' => '<div class="input-prepend"><span class="add-on">@</span>',
		'afterInput' => '</div>'
	)); ?>
	<?php echo $this->Form->input('price', array(
		'label' => array(
			'text' => 'Append',
		),
		'beforeInput' => '<div class="input-append">',
		'afterInput' => '<span class="add-on">.00</span></div>'
	)); ?>
	<?php echo $this->Form->input('price_error', array(
		'label' => array(
			'text' => 'Append Error',
		),
		'beforeInput' => '<div class="input-append">',
		'afterInput' => '<span class="add-on">.00</span></div>'
	)); ?>
	<?php echo $this->Form->input('password', array(
		'label' => array(
			'text' => 'Show Error Message'
		),
		'placeholder' => 'Password',
	)); ?>
	<?php echo $this->Form->input('password', array(
		'label' => array(
			'text' => 'Hide Error Message'
		),
		'placeholder' => 'Password',
		'errorMessage' => false
	)); ?>
	<?php echo $this->Form->input('checkbox', array(
		'label' => array('class' => null),
		'afterInput' => '<span class="help-block">Checkbox Bootstrap Style</span>'
	)); ?>
	<?php echo $this->Form->input('checkbox', array(
		'div' => false,
		'label' => false,
		'before' => '<label class="control-label">Checkbox</label>',
		'wrapInput' => 'controls',
		'afterInput' => '<span class="help-block">Checkbox CakePHP Style</span>'
	)); ?>
	<div class="form-actions">
		<?php echo $this->Form->submit('Save changes', array(
			'div' => false,
			'class' => 'btn btn-primary'
		)); ?>
		<button type="button" class="btn">Cancel</button>
	</div>
<?php echo $this->Form->end(); ?>