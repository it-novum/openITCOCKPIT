<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Statuspage $statuspage
 * @var string[]|\Cake\Collection\CollectionInterface $containers
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $statuspage->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $statuspage->id), 'class' => 'side-nav-item']
            ) ?>
            <?= $this->Html->link(__('List Statuspages'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="statuspages form content">
            <?= $this->Form->create($statuspage) ?>
            <fieldset>
                <legend><?= __('Edit Statuspage') ?></legend>
                <?php
                    echo $this->Form->control('name');
                    echo $this->Form->control('description');
                    echo $this->Form->control('public');
                    echo $this->Form->control('show_comments');
                    echo $this->Form->control('containers._ids', ['options' => $containers]);
                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
