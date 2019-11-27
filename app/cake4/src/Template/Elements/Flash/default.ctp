<?php if (!(strtolower($this->params['controller']) === 'login' && $message === 'This action is not allowed.')): ?>
    <?php if (empty($params['class'])):
        $params['class'] = 'alert alert-warning';
    endif; ?>
    <div id="<?php echo h($key) ?>Message" class="<?php echo h($params['class']) ?>"><?php echo $message ?></div>
<?php endif; ?>
