<?php if(!isset($excludeActionWrapper)): ?>
	<div class="controller <?php echo $this->name ?>_<?php echo $this->action ?>">
<?php endif; ?>
	<?php echo $content_for_layout; ?>

<?php if(!isset($excludeActionWrapper)): ?>
	</div>
<?php endif; ?>