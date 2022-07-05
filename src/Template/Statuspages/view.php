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
            <h3><?= h($statuspage->id) ?></h3>
            <table>
                <tr>
                    <th><?= __('User') ?></th>
                    <td><?= $statuspage->has('user') ? $this->Html->link($statuspage->user->id, ['controller' => 'Users', 'action' => 'view', $statuspage->user->id]) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('Id') ?></th>
                    <td><?= $this->Number->format($statuspage->id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Public') ?></th>
                    <td><?= $statuspage->public ? __('Yes') : __('No'); ?></td>
                </tr>
            </table>
            <div class="related">
                <h4><?= __('Related Hostgroups To Statuspages') ?></h4>
                <?php if (!empty($statuspage->hostgroups_to_statuspages)) : ?>
                <div class="table-responsive">
                    <table>
                        <tr>
                            <th><?= __('Id') ?></th>
                            <th><?= __('Statuspage Id') ?></th>
                            <th><?= __('Hostgroup Id') ?></th>
                            <th><?= __('Display Name') ?></th>
                            <th class="actions"><?= __('Actions') ?></th>
                        </tr>
                        <?php foreach ($statuspage->hostgroups_to_statuspages as $hostgroupsToStatuspages) : ?>
                        <tr>
                            <td><?= h($hostgroupsToStatuspages->id) ?></td>
                            <td><?= h($hostgroupsToStatuspages->statuspage_id) ?></td>
                            <td><?= h($hostgroupsToStatuspages->hostgroup_id) ?></td>
                            <td><?= h($hostgroupsToStatuspages->display_name) ?></td>
                            <td class="actions">
                                <?= $this->Html->link(__('View'), ['controller' => 'HostgroupsToStatuspages', 'action' => 'view', $hostgroupsToStatuspages->id]) ?>
                                <?= $this->Html->link(__('Edit'), ['controller' => 'HostgroupsToStatuspages', 'action' => 'edit', $hostgroupsToStatuspages->id]) ?>
                                <?= $this->Form->postLink(__('Delete'), ['controller' => 'HostgroupsToStatuspages', 'action' => 'delete', $hostgroupsToStatuspages->id], ['confirm' => __('Are you sure you want to delete # {0}?', $hostgroupsToStatuspages->id)]) ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
                <?php endif; ?>
            </div>
            <div class="related">
                <h4><?= __('Related Hosts To Statuspages') ?></h4>
                <?php if (!empty($statuspage->hosts_to_statuspages)) : ?>
                <div class="table-responsive">
                    <table>
                        <tr>
                            <th><?= __('Id') ?></th>
                            <th><?= __('Statuspage Id') ?></th>
                            <th><?= __('Host Id') ?></th>
                            <th><?= __('Display Name') ?></th>
                            <th class="actions"><?= __('Actions') ?></th>
                        </tr>
                        <?php foreach ($statuspage->hosts_to_statuspages as $hostsToStatuspages) : ?>
                        <tr>
                            <td><?= h($hostsToStatuspages->id) ?></td>
                            <td><?= h($hostsToStatuspages->statuspage_id) ?></td>
                            <td><?= h($hostsToStatuspages->host_id) ?></td>
                            <td><?= h($hostsToStatuspages->display_name) ?></td>
                            <td class="actions">
                                <?= $this->Html->link(__('View'), ['controller' => 'HostsToStatuspages', 'action' => 'view', $hostsToStatuspages->id]) ?>
                                <?= $this->Html->link(__('Edit'), ['controller' => 'HostsToStatuspages', 'action' => 'edit', $hostsToStatuspages->id]) ?>
                                <?= $this->Form->postLink(__('Delete'), ['controller' => 'HostsToStatuspages', 'action' => 'delete', $hostsToStatuspages->id], ['confirm' => __('Are you sure you want to delete # {0}?', $hostsToStatuspages->id)]) ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
                <?php endif; ?>
            </div>
            <div class="related">
                <h4><?= __('Related Servicegroups To Statuspages') ?></h4>
                <?php if (!empty($statuspage->servicegroups_to_statuspages)) : ?>
                <div class="table-responsive">
                    <table>
                        <tr>
                            <th><?= __('Id') ?></th>
                            <th><?= __('Statuspage Id') ?></th>
                            <th><?= __('Servicegroup Id') ?></th>
                            <th><?= __('Display Name') ?></th>
                            <th class="actions"><?= __('Actions') ?></th>
                        </tr>
                        <?php foreach ($statuspage->servicegroups_to_statuspages as $servicegroupsToStatuspages) : ?>
                        <tr>
                            <td><?= h($servicegroupsToStatuspages->id) ?></td>
                            <td><?= h($servicegroupsToStatuspages->statuspage_id) ?></td>
                            <td><?= h($servicegroupsToStatuspages->servicegroup_id) ?></td>
                            <td><?= h($servicegroupsToStatuspages->display_name) ?></td>
                            <td class="actions">
                                <?= $this->Html->link(__('View'), ['controller' => 'ServicegroupsToStatuspages', 'action' => 'view', $servicegroupsToStatuspages->id]) ?>
                                <?= $this->Html->link(__('Edit'), ['controller' => 'ServicegroupsToStatuspages', 'action' => 'edit', $servicegroupsToStatuspages->id]) ?>
                                <?= $this->Form->postLink(__('Delete'), ['controller' => 'ServicegroupsToStatuspages', 'action' => 'delete', $servicegroupsToStatuspages->id], ['confirm' => __('Are you sure you want to delete # {0}?', $servicegroupsToStatuspages->id)]) ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
                <?php endif; ?>
            </div>
            <div class="related">
                <h4><?= __('Related Services To Statuspages') ?></h4>
                <?php if (!empty($statuspage->services_to_statuspages)) : ?>
                <div class="table-responsive">
                    <table>
                        <tr>
                            <th><?= __('Id') ?></th>
                            <th><?= __('Statuspage Id') ?></th>
                            <th><?= __('Service Id') ?></th>
                            <th><?= __('Display Name') ?></th>
                            <th class="actions"><?= __('Actions') ?></th>
                        </tr>
                        <?php foreach ($statuspage->services_to_statuspages as $servicesToStatuspages) : ?>
                        <tr>
                            <td><?= h($servicesToStatuspages->id) ?></td>
                            <td><?= h($servicesToStatuspages->statuspage_id) ?></td>
                            <td><?= h($servicesToStatuspages->service_id) ?></td>
                            <td><?= h($servicesToStatuspages->display_name) ?></td>
                            <td class="actions">
                                <?= $this->Html->link(__('View'), ['controller' => 'ServicesToStatuspages', 'action' => 'view', $servicesToStatuspages->id]) ?>
                                <?= $this->Html->link(__('Edit'), ['controller' => 'ServicesToStatuspages', 'action' => 'edit', $servicesToStatuspages->id]) ?>
                                <?= $this->Form->postLink(__('Delete'), ['controller' => 'ServicesToStatuspages', 'action' => 'delete', $servicesToStatuspages->id], ['confirm' => __('Are you sure you want to delete # {0}?', $servicesToStatuspages->id)]) ?>
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
