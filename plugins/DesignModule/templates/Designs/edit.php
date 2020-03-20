<?php
/**
 * @var \App\View\AppView $this
 * @var \Cake\Datasource\EntityInterface $design
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $design->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $design->id), 'class' => 'side-nav-item']
            ) ?>
            <?= $this->Html->link(__('List Designs'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="designs form content">
            <?= $this->Form->create($design) ?>
            <fieldset>
                <legend><?= __('Edit Design') ?></legend>
                <?php
                    echo $this->Form->control('page_header');
                    echo $this->Form->control('header-btn');
                    echo $this->Form->control('page-sidebar');
                    echo $this->Form->control('nav-title');
                    echo $this->Form->control('nav-menu');
                    echo $this->Form->control('nav-menu-hover');
                    echo $this->Form->control('nav-tabs');
                    echo $this->Form->control('nav-tabs-hover');
                    echo $this->Form->control('page-content');
                    echo $this->Form->control('page-content-wrapper');
                    echo $this->Form->control('panel-hdr');
                    echo $this->Form->control('panel');
                    echo $this->Form->control('breadcrumb-links');
                    echo $this->Form->control('logo-in-header');
                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
