<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Statuspage $statuspage
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('Edit Statuspage'), ['action' => 'edit', $statuspage->id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete Statuspage'), ['action' => 'delete', $statuspage->id], ['confirm' => __('Are you sure you want to delete # {0}?', $statuspage->id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Statuspages'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Statuspage'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="statuspages view content">
            <h3><?= h($statuspage->name) ?></h3>
            <table>
                <tr>
                    <th><?= __('Name') ?></th>
                    <td><?= h($statuspage->name) ?></td>
                </tr>
                <tr>
                    <th><?= __('Description') ?></th>
                    <td><?= h($statuspage->description) ?></td>
                </tr>
                <tr>
                    <th><?= __('Id') ?></th>
                    <td><?= $this->Number->format($statuspage->id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Created') ?></th>
                    <td><?= h($statuspage->created) ?></td>
                </tr>
                <tr>
                    <th><?= __('Modified') ?></th>
                    <td><?= h($statuspage->modified) ?></td>
                </tr>
                <tr>
                    <th><?= __('Public') ?></th>
                    <td><?= $statuspage->public ? __('Yes') : __('No'); ?></td>
                </tr>
                <tr>
                    <th><?= __('Show Comments') ?></th>
                    <td><?= $statuspage->show_comments ? __('Yes') : __('No'); ?></td>
                </tr>
            </table>
            <div class="related">
                <h4><?= __('Related Containers') ?></h4>
                <?php if (!empty($statuspage->containers)) : ?>
                <div class="table-responsive">
                    <table>
                        <tr>
                            <th><?= __('Id') ?></th>
                            <th><?= __('Containertype Id') ?></th>
                            <th><?= __('Name') ?></th>
                            <th><?= __('Parent Id') ?></th>
                            <th><?= __('Lft') ?></th>
                            <th><?= __('Rght') ?></th>
                            <th class="actions"><?= __('Actions') ?></th>
                        </tr>
                        <?php foreach ($statuspage->containers as $containers) : ?>
                        <tr>
                            <td><?= h($containers->id) ?></td>
                            <td><?= h($containers->containertype_id) ?></td>
                            <td><?= h($containers->name) ?></td>
                            <td><?= h($containers->parent_id) ?></td>
                            <td><?= h($containers->lft) ?></td>
                            <td><?= h($containers->rght) ?></td>
                            <td class="actions">
                                <?= $this->Html->link(__('View'), ['controller' => 'Containers', 'action' => 'view', $containers->id]) ?>
                                <?= $this->Html->link(__('Edit'), ['controller' => 'Containers', 'action' => 'edit', $containers->id]) ?>
                                <?= $this->Form->postLink(__('Delete'), ['controller' => 'Containers', 'action' => 'delete', $containers->id], ['confirm' => __('Are you sure you want to delete # {0}?', $containers->id)]) ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
                <?php endif; ?>
            </div>
            <div class="related">
                <h4><?= __('Related Statuspage Items') ?></h4>
                <?php if (!empty($statuspage->statuspage_items)) : ?>
                <div class="table-responsive">
                    <table>
                        <tr>
                            <th><?= __('Id') ?></th>
                            <th><?= __('Statuspage Id') ?></th>
                            <th><?= __('Type') ?></th>
                            <th><?= __('Object Id') ?></th>
                            <th><?= __('Display Text') ?></th>
                            <th><?= __('Created') ?></th>
                            <th><?= __('Modified') ?></th>
                            <th class="actions"><?= __('Actions') ?></th>
                        </tr>
                        <?php foreach ($statuspage->statuspage_items as $statuspageItems) : ?>
                        <tr>
                            <td><?= h($statuspageItems->id) ?></td>
                            <td><?= h($statuspageItems->statuspage_id) ?></td>
                            <td><?= h($statuspageItems->type) ?></td>
                            <td><?= h($statuspageItems->object_id) ?></td>
                            <td><?= h($statuspageItems->display_text) ?></td>
                            <td><?= h($statuspageItems->created) ?></td>
                            <td><?= h($statuspageItems->modified) ?></td>
                            <td class="actions">
                                <?= $this->Html->link(__('View'), ['controller' => 'StatuspageItems', 'action' => 'view', $statuspageItems->id]) ?>
                                <?= $this->Html->link(__('Edit'), ['controller' => 'StatuspageItems', 'action' => 'edit', $statuspageItems->id]) ?>
                                <?= $this->Form->postLink(__('Delete'), ['controller' => 'StatuspageItems', 'action' => 'delete', $statuspageItems->id], ['confirm' => __('Are you sure you want to delete # {0}?', $statuspageItems->id)]) ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
