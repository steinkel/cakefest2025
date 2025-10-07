<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Hotel $hotel
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('Edit Hotel'), ['action' => 'edit', $hotel->id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete Hotel'), ['action' => 'delete', $hotel->id], ['confirm' => __('Are you sure you want to delete # {0}?', $hotel->id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Hotels'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Hotel'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column column-80">
        <div class="hotels view content">
            <h3><?= h($hotel->name) ?></h3>
            <table>
                <tr>
                    <th><?= __('Name') ?></th>
                    <td><?= h($hotel->name) ?></td>
                </tr>
                <tr>
                    <th><?= __('Address') ?></th>
                    <td><?= h($hotel->address) ?></td>
                </tr>
                <tr>
                    <th><?= __('City') ?></th>
                    <td><?= h($hotel->city) ?></td>
                </tr>
                <tr>
                    <th><?= __('State') ?></th>
                    <td><?= h($hotel->state) ?></td>
                </tr>
                <tr>
                    <th><?= __('Country') ?></th>
                    <td><?= h($hotel->country) ?></td>
                </tr>
                <tr>
                    <th><?= __('Email') ?></th>
                    <td><?= h($hotel->email) ?></td>
                </tr>
                <tr>
                    <th><?= __('Id') ?></th>
                    <td><?= $this->Number->format($hotel->id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Star Rating') ?></th>
                    <td><?= $hotel->star_rating === null ? '' : $this->Number->format($hotel->star_rating) ?></td>
                </tr>
                <tr>
                    <th><?= __('Created') ?></th>
                    <td><?= h($hotel->created) ?></td>
                </tr>
                <tr>
                    <th><?= __('Modified') ?></th>
                    <td><?= h($hotel->modified) ?></td>
                </tr>
            </table>
            <div class="related">
                <h4><?= __('Related Rooms') ?></h4>
                <?php if (!empty($hotel->rooms)) : ?>
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
                        <?php foreach ($hotel->rooms as $room) : ?>
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