<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\RoomType $roomType
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('Edit Room Type'), ['action' => 'edit', $roomType->id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete Room Type'), ['action' => 'delete', $roomType->id], ['confirm' => __('Are you sure you want to delete # {0}?', $roomType->id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Room Types'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Room Type'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column column-80">
        <div class="roomTypes view content">
            <h3><?= h($roomType->type_name) ?></h3>
            <table>
                <tr>
                    <th><?= __('Type Name') ?></th>
                    <td><?= h($roomType->type_name) ?></td>
                </tr>
                <tr>
                    <th><?= __('Id') ?></th>
                    <td><?= $this->Number->format($roomType->id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Max Occupancy') ?></th>
                    <td><?= $this->Number->format($roomType->max_occupancy) ?></td>
                </tr>
                <tr>
                    <th><?= __('Base Price') ?></th>
                    <td><?= $this->Number->format($roomType->base_price) ?></td>
                </tr>
                <tr>
                    <th><?= __('Created') ?></th>
                    <td><?= h($roomType->created) ?></td>
                </tr>
                <tr>
                    <th><?= __('Modified') ?></th>
                    <td><?= h($roomType->modified) ?></td>
                </tr>
                <tr>
                    <th><?= __('Pets Allowed') ?></th>
                    <td><?= $roomType->pets_allowed ? __('Yes') : __('No'); ?></td>
                </tr>
            </table>
            <div class="text">
                <strong><?= __('Description') ?></strong>
                <blockquote>
                    <?= $this->Text->autoParagraph(h($roomType->description)); ?>
                </blockquote>
            </div>
            <div class="related">
                <h4><?= __('Related Rooms') ?></h4>
                <?php if (!empty($roomType->rooms)) : ?>
                <div class="table-responsive">
                    <table>
                        <tr>
                            <th><?= __('Id') ?></th>
                            <th><?= __('Hotel Id') ?></th>
                            <th><?= __('Room Type Id') ?></th>
                            <th><?= __('Room Number') ?></th>
                            <th><?= __('Is Available') ?></th>
                            <th><?= __('Created') ?></th>
                            <th><?= __('Modified') ?></th>
                            <th class="actions"><?= __('Actions') ?></th>
                        </tr>
                        <?php foreach ($roomType->rooms as $room) : ?>
                        <tr>
                            <td><?= h($room->id) ?></td>
                            <td><?= h($room->hotel_id) ?></td>
                            <td><?= h($room->room_type_id) ?></td>
                            <td><?= h($room->room_number) ?></td>
                            <td><?= h($room->is_available) ?></td>
                            <td><?= h($room->created) ?></td>
                            <td><?= h($room->modified) ?></td>
                            <td class="actions">
                                <?= $this->Html->link(__('View'), ['controller' => 'Rooms', 'action' => 'view', $room->id]) ?>
                                <?= $this->Html->link(__('Edit'), ['controller' => 'Rooms', 'action' => 'edit', $room->id]) ?>
                                <?= $this->Form->postLink(
                                    __('Delete'),
                                    ['controller' => 'Rooms', 'action' => 'delete', $room->id],
                                    [
                                        'method' => 'delete',
                                        'confirm' => __('Are you sure you want to delete # {0}?', $room->id),
                                    ]
                                ) ?>
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