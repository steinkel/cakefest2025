<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\RoomType> $roomTypes
 */
?>
<div class="roomTypes index content">
    <?= $this->Html->link(__('New Room Type'), ['action' => 'add'], ['class' => 'button float-right']) ?>
    <h3><?= __('Room Types') ?></h3>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th><?= $this->Paginator->sort('id') ?></th>
                    <th><?= $this->Paginator->sort('type_name') ?></th>
                    <th><?= $this->Paginator->sort('max_occupancy') ?></th>
                    <th><?= $this->Paginator->sort('base_price') ?></th>
                    <th><?= $this->Paginator->sort('pets_allowed') ?></th>
                    <th><?= $this->Paginator->sort('created') ?></th>
                    <th><?= $this->Paginator->sort('modified') ?></th>
                    <th class="actions"><?= __('Actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($roomTypes as $roomType): ?>
                <tr>
                    <td><?= $this->Number->format($roomType->id) ?></td>
                    <td><?= h($roomType->type_name) ?></td>
                    <td><?= $this->Number->format($roomType->max_occupancy) ?></td>
                    <td><?= $this->Number->format($roomType->base_price) ?></td>
                    <td><?= h($roomType->pets_allowed) ?></td>
                    <td><?= h($roomType->created) ?></td>
                    <td><?= h($roomType->modified) ?></td>
                    <td class="actions">
                        <?= $this->Html->link(__('View'), ['action' => 'view', $roomType->id]) ?>
                        <?= $this->Html->link(__('Edit'), ['action' => 'edit', $roomType->id]) ?>
                        <?= $this->Form->postLink(
                            __('Delete'),
                            ['action' => 'delete', $roomType->id],
                            [
                                'method' => 'delete',
                                'confirm' => __('Are you sure you want to delete # {0}?', $roomType->id),
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