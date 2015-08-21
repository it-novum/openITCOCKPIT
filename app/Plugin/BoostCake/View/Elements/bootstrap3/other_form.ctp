<?php echo $this->Form->create('BoostCake', array(
	'inputDefaults' => array(
		'div' => 'form-group',
		'label' => array(
			'class' => 'col col-md-3 control-label'
		),
		'wrapInput' => 'col col-md-9',
		'class' => 'form-control'
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
		'class' => 'checkbox-inline',
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
		'before' => '<label class="col col-md-3 control-label">Radio</label>',
		'legend' => false,
		'class' => false,
		'options' => array(
			1 => 'Option one is this and that—be sure to include why it\'s great',
			2 => 'Option two can be something else and selecting it will deselect option one'
		)
	)); ?>
	<?php echo $this->Form->input('username', array(
		'placeholder' => 'Username',
		'label' => array(
			'text' => 'Prepend',
		),
		'beforeInput' => '<div class="input-group"><span class="input-group-addon">@</span>',
		'afterInput' => '</div>'
	)); ?>
	<?php echo $this->Form->input('price', array(
		'label' => array(
			'text' => 'Append',
		),
		'beforeInput' => '<div class="input-group">',
		'afterInput' => '<span class="input-group-addon">.00</span></div>'
	)); ?>
	<?php echo $this->Form->input('price_error', array(
		'label' => array(
			'text' => 'Append Error',
		),
		'beforeInput' => '<div class="input-group">',
		'afterInput' => '<span class="input-group-addon">.00</span></div>'
	)); ?>
	<?php echo $this->Form->input('password', array(
		'label' => array(
			'text' => 'Show Error Message'
		),
		'placeholder' => 'Password'
	)); ?>
	<?php echo $this->Form->input('password', array(
		'label' => array(
			'text' => 'Hide Error Message'
		),
		'placeholder' => 'Password',
		'errorMessage' => false
	)); ?>
	<?php echo $this->Form->input('checkbox', array(
		'wrapInput' => 'col col-md-9 col-md-offset-3',
		'label' => array('class' => null),
		'class' => false,
		'afterInput' => '<span class="help-block">Checkbox Bootstrap Style</span>'
	)); ?>
	<?php echo $this->Form->input('checkbox', array(
		'before' => '<label class="col col-md-3 control-label">Checkbox</label>',
		'label' => false,
		'class' => false,
		'afterInput' => '<span class="help-block">Checkbox CakePHP Style</span>'
	)); ?>
	<div class="form-group">
		<div class="col col-md-9 col-md-offset-3">
			<?php echo $this->Form->submit('Save changes', array(
				'div' => false,
				'class' => 'btn btn-primary'
			)); ?>
			<button type="button" class="btn btn-default">Cancel</button>
		</div>
	</div>
<?php echo $this->Form->end(); ?>
