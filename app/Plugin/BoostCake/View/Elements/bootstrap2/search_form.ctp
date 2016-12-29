<?php echo $this->Form->create('BoostCake', [
    'inputDefaults' => [
        'div'       => false,
        'wrapInput' => false,
    ],
    'class'         => 'well form-search',
]); ?>
<?php echo $this->Form->input('text', [
    'label' => false,
    'class' => 'input-medium search-query',
]); ?>
<?php echo $this->Form->submit('Search', [
    'div'   => false,
    'class' => 'btn',
]); ?>
<?php echo $this->Form->end(); ?>