<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\Hotel> $hotels
 */
?>
<div class="hotels index content">
    <?= $this->Html->link(__('New Hotel'), ['action' => 'add'], ['class' => 'button float-right']) ?>
    <h3><?= __('Hotels') ?></h3>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th><?= $this->Paginator->sort('id') ?></th>
                    <th><?= $this->Paginator->sort('name') ?></th>
                    <th><?= $this->Paginator->sort('address') ?></th>
                    <th><?= $this->Paginator->sort('city') ?></th>
                    <th><?= $this->Paginator->sort('state') ?></th>
                    <th><?= $this->Paginator->sort('country') ?></th>
                    <th><?= $this->Paginator->sort('email') ?></th>
                    <th><?= $this->Paginator->sort('star_rating') ?></th>
                    <th><?= $this->Paginator->sort('created') ?></th>
                    <th><?= $this->Paginator->sort('modified') ?></th>
                    <th class="actions"><?= __('Actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($hotels as $hotel): ?>
                <tr>
                    <td><?= $this->Number->format($hotel->id) ?></td>
                    <td><?= h($hotel->name) ?></td>
                    <td><?= h($hotel->address) ?></td>
                    <td><?= h($hotel->city) ?></td>
                    <td><?= h($hotel->state) ?></td>
                    <td><?= h($hotel->country) ?></td>
                    <td><?= h($hotel->email) ?></td>
                    <td><?= $hotel->star_rating === null ? '' : $this->Number->format($hotel->star_rating) ?></td>
                    <td><?= h($hotel->created) ?></td>
                    <td><?= h($hotel->modified) ?></td>
                    <td class="actions">
                        <?= $this->Html->link(__('View'), ['action' => 'view', $hotel->id]) ?>
                        <?= $this->Html->link(__('Edit'), ['action' => 'edit', $hotel->id]) ?>
                        <?= $this->Form->postLink(
                            __('Delete'),
                            ['action' => 'delete', $hotel->id],
                            [
                                'method' => 'delete',
                                'confirm' => __('Are you sure you want to delete # {0}?', $hotel->id),
                            ]
                        ) ?>
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