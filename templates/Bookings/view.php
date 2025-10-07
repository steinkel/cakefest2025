<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Booking $booking
 */
?>
<div class="bookings view content">
    <?= $this->Html->link(__('← Back to All Bookings'), ['action' => 'index'], ['class' => 'button']) ?>

    <h1><?= __('Booking Confirmation') ?></h1>

    <?php if ($booking->booking_status === \App\Model\Entity\Booking::STATUS_CONFIRMED) : ?>
        <h2><?= __('Your reservation is confirmed!') ?></h2>
    <?php elseif ($booking->booking_status === \App\Model\Entity\Booking::STATUS_CANCELLED) : ?>
        <h2><?= __('This booking has been cancelled') ?></h2>
    <?php else : ?>
        <h2><?= __('Booking Details') ?></h2>
    <?php endif; ?>

    <p><?= __('Booking ID: #{0}', $booking->id) ?></p>

    <h3><?= __('Hotel Information') ?></h3>
    <table>
        <tr>
            <th><?= __('Hotel Name:') ?></th>
            <td><?= h($booking->room->hotel->name) ?></td>
        </tr>
        <tr>
            <th><?= __('Address:') ?></th>
            <td>
                <?= h($booking->room->hotel->address) ?><br>
                <?= h($booking->room->hotel->city) ?>, <?= h($booking->room->hotel->state) ?><br>
                <?= h($booking->room->hotel->country) ?> <?= h($booking->room->hotel->postal_code) ?>
            </td>
        </tr>
        <tr>
            <th><?= __('Email:') ?></th>
            <td><?= h($booking->room->hotel->email) ?></td>
        </tr>
        <?php if ($booking->room->hotel->star_rating) : ?>
            <tr>
                <th><?= __('Rating:') ?></th>
                <td><?= str_repeat('⭐', $booking->room->hotel->star_rating) ?></td>
            </tr>
        <?php endif; ?>
    </table>

    <h3><?= __('Room Information') ?></h3>
    <table>
        <tr>
            <th><?= __('Room Type:') ?></th>
            <td><?= h($booking->room->room_type->type_name) ?></td>
        </tr>
        <tr>
            <th><?= __('Room Number:') ?></th>
            <td><?= h($booking->room->room_number) ?></td>
        </tr>
        <tr>
            <th><?= __('Max Occupancy:') ?></th>
            <td><?= $booking->room->room_type->max_occupancy ?> <?= __('guest(s)') ?></td>
        </tr>
        <tr>
            <th><?= __('Pets Allowed:') ?></th>
            <td><?= $booking->room->room_type->pets_allowed ? __('Yes') : __('No') ?></td>
        </tr>
    </table>

    <h3><?= __('Guest Information') ?></h3>
    <table>
        <tr>
            <th><?= __('Guest Name:') ?></th>
            <td><?= h($booking->customer->first_name) ?> <?= h($booking->customer->last_name) ?></td>
        </tr>
        <tr>
            <th><?= __('Email:') ?></th>
            <td><?= h($booking->customer->email) ?></td>
        </tr>
        <tr>
            <th><?= __('Number of Guests:') ?></th>
            <td><?= $this->Number->format($booking->number_of_guests) ?></td>
        </tr>
    </table>

    <h3><?= __('Reservation Details') ?></h3>
    <table>
        <tr>
            <th><?= __('Check-in Date:') ?></th>
            <td><?= h($booking->check_in_date->format('Y-m-d')) ?></td>
        </tr>
        <tr>
            <th><?= __('Check-out Date:') ?></th>
            <td><?= h($booking->check_out_date->format('Y-m-d')) ?></td>
        </tr>
        <tr>
            <th><?= __('Number of Nights:') ?></th>
            <td><?= $booking->check_in_date->diffInDays($booking->check_out_date) ?></td>
        </tr>
        <tr>
            <th><?= __('Total Amount:') ?></th>
            <td><strong>$<?= $this->Number->format($booking->total_amount, ['places' => 2]) ?></strong></td>
        </tr>
        <tr>
            <th><?= __('Booking Status:') ?></th>
            <td><?= h(ucfirst($booking->booking_status)) ?></td>
        </tr>
        <tr>
            <th><?= __('Booking Date:') ?></th>
            <td><?= h($booking->created->format('Y-m-d H:i:s')) ?></td>
        </tr>
    </table>

    <?php if ($booking->special_requests) : ?>
        <h4><?= __('Special Requests:') ?></h4>
        <p><?= h($booking->special_requests) ?></p>
    <?php endif; ?>

    <?php if ($booking->booking_status === \App\Model\Entity\Booking::STATUS_CONFIRMED) : ?>
        <?= $this->Form->postLink(
            __('Cancel Booking'),
            ['action' => 'cancel', $booking->id],
            [
                'confirm' => __('Are you sure you want to cancel this booking?'),
                'class' => 'button'
            ]
        ) ?>
    <?php endif; ?>
</div>
