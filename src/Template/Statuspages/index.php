<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\Statuspage> $statuspages
 */
?>
<div class="statuspages index content">
    <?= $this->Html->link(__('New Statuspage'), ['action' => 'add'], ['class' => 'button float-right']) ?>
    <h3><?= __('Statuspages') ?></h3>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th><?= $this->Paginator->sort('id') ?></th>
                    <th><?= $this->Paginator->sort('name') ?></th>
                    <th><?= $this->Paginator->sort('description') ?></th>
                    <th><?= $this->Paginator->sort('public') ?></th>
                    <th><?= $this->Paginator->sort('show_comments') ?></th>
                    <th><?= $this->Paginator->sort('created') ?></th>
                    <th><?= $this->Paginator->sort('modified') ?></th>
                    <th class="actions"><?= __('Actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($statuspages as $statuspage): ?>
                <tr>
                    <td><?= $this->Number->format($statuspage->id) ?></td>
                    <td><?= h($statuspage->name) ?></td>
                    <td><?= h($statuspage->description) ?></td>
                    <td><?= h($statuspage->public) ?></td>
                    <td><?= h($statuspage->show_comments) ?></td>
                    <td><?= h($statuspage->created) ?></td>
                    <td><?= h($statuspage->modified) ?></td>
                    <td class="actions">
                        <?= $this->Html->link(__('View'), ['action' => 'view', $statuspage->id]) ?>
                        <?= $this->Html->link(__('Edit'), ['action' => 'edit', $statuspage->id]) ?>
                        <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $statuspage->id], ['confirm' => __('Are you sure you want to delete # {0}?', $statuspage->id)]) ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div class="paginator">
        <ul class="pagination">
            <?= $this->Paginator->first('<< ' . __('first')) ?>
            <?= $this->Paginator->prev('< ' . __('previous')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('next') . ' >') ?>
            <?= $this->Paginator->last(__('last') . ' >>') ?>
        </ul>
        <p><?= $this->Paginator->counter(__('Page {{page}} of {{pages}}, showing {{current}} record(s) out of {{count}} total')) ?></p>
    </div>
</div>
