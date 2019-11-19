<h2 class="pull-left"><?php echo $pluralHumanName ?></h2>

<div class="list-actions pull-right">
    <?php echo "<?php echo \$this->Utils->addButton(__('" . Inflector::underscore($modelClass) . ".add')); ?>"; ?>

</div>

<?php echo '
<?php 
#echo $this->ListFilter->renderFilterbox($filters);
#$this->Paginator->options(array(\'url\' => $this->params[\'named\']));
?>
';
?>

<table class="table table-bordered table-striped">
    <thead>
    <tr>
        <?php foreach ($fields as $field): ?>
            <th><?php echo "<?php echo \$this->Paginator->sort('{$field}'); ?>"; ?></th>
        <?php endforeach; ?>
        <th class="actions"><?php echo "<?php echo __('Actions'); ?>"; ?></th>
    </tr>
    </thead>
    <tbody>
    <?php
    echo "\t\t<?php foreach(\${$pluralVar} as \${$singularVar}): ?>\n";
    echo "\t\t\t<tr>\n";
    foreach ($fields as $field) {
        $isKey = false;
        if (!empty($associations['belongsTo'])) {
            foreach ($associations['belongsTo'] as $alias => $details) {
                if ($field === $details['foreignKey']) {
                    $isKey = true;
                    echo "\t\t\t\t<td>\n\t\t\t<?php echo \$this->Html->link(\${$singularVar}['{$alias}']['{$details['displayField']}'], array('controller' => '{$details['controller']}', 'action' => 'view', \${$singularVar}['{$alias}']['{$details['primaryKey']}'])); ?>\n\t\t</td>\n";
                    break;
                }
            }
        }
        if ($isKey !== true) {
            echo "\t\t\t\t<td><?php echo h(\${$singularVar}['{$modelClass}']['{$field}']); ?>&nbsp;</td>\n";
        }
    }

    echo "\t\t\t\t<td class=\"actions\">\n";
    echo "\t\t\t\t\t<?php echo \$this->Html->link(__('details'), array('action' => 'view', \${$singularVar}['{$modelClass}']['{$primaryKey}']), array('class' => 'btn btn-xs')); ?>\n";
    echo "\t\t\t\t\t<?php echo \$this->Utils->editButton(__('edit'), \${$singularVar}['{$modelClass}']['{$primaryKey}']); ?>\n";
    echo "\t\t\t\t\t<?php echo \$this->Form->postLink(__('delete'), array('action' => 'delete', \${$singularVar}['{$modelClass}']['{$primaryKey}']), array('class' => 'btn btn-xs btn-danger'), __('Really delete?', \${$singularVar}['{$modelClass}']['{$primaryKey}'])); ?>\n";
    echo "\t\t\t\t</td>\n";
    echo "\t\t\t</tr>\n";

    echo "\t\t<?php endforeach; ?>\n";
    ?>
    </tbody>
</table>

<?php echo '<?php echo $this->Paginator->pagination(); ?>'; ?>