<?php echo "<?php echo \$this->Form->create('{$modelClass}', array(
	'class' => 'form-horizontal',
	'novalidate'
)); ?>\n"; ?>

<fieldset>
	<legend><?php printf("<?php echo __('%s %s'); ?>", Inflector::humanize($action), $singularHumanName); ?></legend>
<?php
	echo "\t<?php\n";
	foreach ($fields as $field) {
		if($field == $primaryKey) {
			echo "\t\t" . 'if($this->action == \'edit\') {' . "\n";
			echo "\t\t\techo \$this->Form->input('{$field}');\n";
			echo "\t\t}\n";
		} elseif (!in_array($field, array('created', 'modified', 'updated'))) {
			echo "\t\techo \$this->Form->input('{$field}', array(
			'label' => '{$field}'			
		));\n";
		}
	}
	if (!empty($associations['hasAndBelongsToMany'])) {
		foreach ($associations['hasAndBelongsToMany'] as $assocName => $assocData) {
			echo "\t\techo \$this->Form->input('{$assocName}');\n";
		}
	}
	echo "\t?>\n";
?>
</fieldset>

<?php echo '<?php echo $this->Form->formActions(); ?>' ?>
<?php return; ?>






<div class="actions">
	<h3><?php echo "<?php echo __('Actions'); ?>"; ?></h3>
	<ul>

<?php if (strpos($action, 'add') === false): ?>
		<li><?php echo "<?php echo \$this->Form->postLink(__('Delete'), array('action' => 'delete', \$this->Form->value('{$modelClass}.{$primaryKey}')), null, __('Are you sure you want to delete # %s?', \$this->Form->value('{$modelClass}.{$primaryKey}'))); ?>"; ?></li>
<?php endif; ?>
		<li><?php echo "<?php echo \$this->Html->link(__('List " . $pluralHumanName . "'), array('action' => 'index')); ?>"; ?></li>
<?php
		$done = array();
		foreach ($associations as $type => $data) {
			foreach ($data as $alias => $details) {
				if ($details['controller'] != $this->name && !in_array($details['controller'], $done)) {
					echo "\t\t<li><?php echo \$this->Html->link(__('List " . Inflector::humanize($details['controller']) . "'), array('controller' => '{$details['controller']}', 'action' => 'index')); ?> </li>\n";
					echo "\t\t<li><?php echo \$this->Html->link(__('New " . Inflector::humanize(Inflector::underscore($alias)) . "'), array('controller' => '{$details['controller']}', 'action' => 'add')); ?> </li>\n";
					$done[] = $details['controller'];
				}
			}
		}
?>
	</ul>
</div>
