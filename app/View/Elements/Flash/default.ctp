<?php if($message !== 'This action is not allowed.'): ?>
    <div id="<?php echo h($key) ?>Message" class="<?php echo h($class) ?>"><?php echo $message ?></div>
<?php endif; ?>
