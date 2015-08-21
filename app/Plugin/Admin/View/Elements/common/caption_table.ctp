<table class="clear table table-bordered table-condensed caption-table <?php echo $options['additionalClasses'] ?>">
	<tbody>
		<?php foreach($map as $k => $v): ?>
			<tr>
				<td class="caption"><?php echo $k ?></td>
				<td><?php echo h($v) ?></td>
			</tr>
		<?php endforeach; ?>
	</tbody>
</table>