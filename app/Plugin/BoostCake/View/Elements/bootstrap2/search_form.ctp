<?php echo $this->Form->create('BoostCake', array(
	'inputDefaults' => array(
		'div' => false,
		'wrapInput' => false
	),
	'class' => 'well form-search'
)); ?>
	<?php echo $this->Form->input('text', array(
		'label' => false,
		'class' => 'input-medium search-query',
	)); ?>
	<?php echo $this->Form->submit('Search', array(
		'div' => false,
		'class' => 'btn'
	)); ?>
<?php echo $this->Form->end(); ?>