<?php echo $this->Form->create('BoostCake', [
    'inputDefaults' => [
        'div'       => false,
        'wrapInput' => false,
    ],
    'class'         => 'well',
]); ?>
    <fieldset>
        <legend>Legend</legend>
        <?php echo $this->Form->input('text', [
            'label'       => 'Label name',
            'placeholder' => 'Type something…',
            'after'       => '<span class="help-block">Example block-level help text here.</span>',
        ]); ?>
        <?php echo $this->Form->input('checkbox', [
            'label' => 'Check me out',
        ]); ?>
        <?php echo $this->Form->submit('Submit', [
            'div'   => false,
            'class' => 'btn',
        ]); ?>
    </fieldset>
<?php echo $this->Form->end(); ?>