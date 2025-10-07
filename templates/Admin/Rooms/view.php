<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Room $room
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('Edit Room'), ['action' => 'edit', $room->id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete Room'), ['action' => 'delete', $room->id], ['confirm' => __('Are you sure you want to delete # {0}?', $room->id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Rooms'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Room'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column column-80">
        <div class="rooms view content">
            <h3><?= h($room->room_number) ?></h3>
            <table>
                <tr>
                    <th><?= __('Hotel') ?></th>
                    <td><?= $room->hasValue('hotel') ? $this->Html->link($room->hotel->name, ['controller' => 'Hotels', 'action' => 'view', $room->hotel->id]) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('Room Type') ?></th>
                    <td><?= $room->hasValue('room_type') ? $this->Html->link($room->room_type->type_name, ['controller' => 'RoomTypes', 'action' => 'view', $room->room_type->id]) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('Room Number') ?></th>
                    <td><?= h($room->room_number) ?></td>
                </tr>
                <tr>
                    <th><?= __('Id') ?></th>
                    <td><?= $this->Number->format($room->id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Created') ?></th>
                    <td><?= h($room->created) ?></td>
                </tr>
                <tr>
                    <th><?= __('Modified') ?></th>
                    <td><?= h($room->modified) ?></td>
                </tr>
                <tr>
                    <th><?= __('Is Available') ?></th>
                    <td><?= $room->is_available ? __('Yes') : __('No'); ?></td>
                </tr>
            </table>
            <div class="related">
                <h4><?= __('Related Bookings') ?></h4>
                <?php if (!empty($room->bookings)) : ?>
                <div class="table-responsive">
                    <table>
                        <tr>
                            <th><?= __('Id') ?></th>
                            <th><?= __('Customer Id') ?></th>
                            <th><?= __('Room Id') ?></th>
                            <th><?= __('Check In Date') ?></th>
                            <th><?= __('Check Out Date') ?></th>
                            <th><?= __('Number Of Guests') ?></th>
                            <th><?= __('Total Amount') ?></th>
                            <th><?= __('Booking Status') ?></th>
                            <th><?= __('Special Requests') ?></th>
                            <th><?= __('Created') ?></th>
                            <th><?= __('Modified') ?></th>
                            <th class="actions"><?= __('Actions') ?></th>
                        </tr>
                        <?php foreach ($room->bookings as $booking) : ?>
                        <tr>
                            <td><?= h($booking->id) ?></td>
                            <td><?= h($booking->customer_id) ?></td>
                            <td><?= h($booking->room_id) ?></td>
                            <td><?= h($booking->check_in_date) ?></td>
                            <td><?= h($booking->check_out_date) ?></td>
                            <td><?= h($booking->number_of_guests) ?></td>
                            <td><?= h($booking->total_amount) ?></td>
                            <td><?= h($booking->booking_status) ?></td>
                            <td><?= h($booking->special_requests) ?></td>
                            <td><?= h($booking->created) ?></td>
                            <td><?= h($booking->modified) ?></td>
                            <td class="actions">
                                <?= $this->Html->link(__('View'), ['controller' => 'Bookings', 'action' => 'view', $booking->id]) ?>
                                <?= $this->Html->link(__('Edit'), ['controller' => 'Bookings', 'action' => 'edit', $booking->id]) ?>
                                <?= $this->Form->postLink(
                                    __('Delete'),
                                    ['controller' => 'Bookings', 'action' => 'delete', $booking->id],
                                    [
                                        'method' => 'delete',
                                        'confirm' => __('Are you sure you want to delete # {0}?', $booking->id),
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