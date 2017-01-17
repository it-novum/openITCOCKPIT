<?php echo $this->Form->create('BoostCake', [
    'inputDefaults' => [
        'div'       => 'form-group',
        'wrapInput' => false,
        'class'     => 'form-control',
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
            'class' => false,
        ]); ?>
        <?php echo $this->Form->submit('Submit', [
            'div'   => 'form-group',
            'class' => 'btn btn-default',
        ]); ?>
    </fieldset>
<?php echo $this->Form->end(); ?>