<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Booking $booking
 * @var \App\Model\Entity\Room $room
 * @var string $checkIn
 * @var string $checkOut
 * @var int $nights
 * @var float $totalAmount
 */

?>
<div class="bookings form content">
    <?= $this->Html->link(
        __('← Back to Available Rooms'),
        [
            'action' => 'availableRooms',
            $room->hotel_id,
            '?' => [
                'check_in' => $checkIn,
                'check_out' => $checkOut
            ]
        ],
        ['class' => 'button']
    ) ?>

    <h1><?= __('Complete Your Reservation') ?></h1>

    <h2><?= __('Reservation Summary') ?></h2>

    <h3><?= __('Hotel Information') ?></h3>
    <p><strong><?= h($room->hotel->name) ?></strong></p>
    <p><?= h($room->hotel->address) ?></p>
    <p><?= h($room->hotel->city) ?>, <?= h($room->hotel->state) ?>, <?= h($room->hotel->country) ?></p>
    <?php if ($room->hotel->star_rating) : ?>
        <p><?= str_repeat('⭐', $room->hotel->star_rating) ?></p>
    <?php endif; ?>

    <h3><?= __('Room Details') ?></h3>
    <p><strong><?= h($room->room_type->type_name) ?></strong></p>
    <p><?= __('Room Number:') ?> <?= h($room->room_number) ?></p>
    <p><?= __('Max Occupancy:') ?> <?= $room->room_type->max_occupancy ?> <?= __('guest(s)') ?></p>
    <p><?= __('Pets:') ?> <?= $room->room_type->pets_allowed ? __('Allowed') : __('Not Allowed') ?></p>

    <h3><?= __('Stay Information') ?></h3>
    <p><strong><?= __('Check-in:') ?></strong> <?= h($checkIn) ?></p>
    <p><strong><?= __('Check-out:') ?></strong> <?= h($checkOut) ?></p>
    <p><strong><?= __('Number of Nights:') ?></strong> <?= $nights ?></p>
    <p><?= __('Rate per night:') ?> $<?= $this->Number->format($room->room_type->base_price, ['places' => 2]) ?></p>
    <p>
        <strong><?= __('Total Amount:') ?></strong>
        $<?= $this->Number->format($totalAmount, ['places' => 2]) ?>
    </p>

    <h2><?= __('Guest Information') ?></h2>

    <?= $this->Form->create($booking) ?>
    <fieldset>
        <legend><?= __('Customer Details') ?></legend>

        <?php
            $rnd = rand(1000, 9999);
        ?>
        <?= $this->Form->control('customer.first_name', [
            'label' => __('First Name'),
            'required' => true,
            'default' => 'Doe-' . $rnd,
        ]) ?>

        <?= $this->Form->control('customer.last_name', [
            'label' => __('Last Name'),
            'required' => true,
            'default' => 'Doe-' . $rnd,
        ]) ?>

        <?= $this->Form->control('customer.email', [
            'type' => 'email',
            'label' => __('Email Address'),
            'required' => true,
            'default' => 'test@example.com',
        ]) ?>

        <?= $this->Form->control('customer.phone', [
            'label' => __('Phone Number'),
            'required' => true,
            'default' => '555-555-5555',
        ]) ?>
    </fieldset>

    <fieldset>
        <legend><?= __('Reservation Details') ?></legend>

        <?= $this->Form->control('number_of_guests', [
            'type' => 'number',
            'label' => __('Number of Guests'),
            'min' => 1,
            'max' => $room->room_type->max_occupancy,
            'required' => true,
            'value' => 1
        ]) ?>

        <?= $this->Form->control('special_requests', [
            'type' => 'textarea',
            'label' => __('Special Requests (Optional)'),
            'placeholder' => __('Any special requirements or requests...'),
            'rows' => 4
        ]) ?>
    </fieldset>

    <?= $this->Form->button(__('Confirm Reservation'), ['class' => 'button']) ?>
    <?= $this->Html->link(
        __('Cancel'),
        [
            'action' => 'availableRooms',
            $room->hotel_id,
            '?' => [
                'check_in' => $checkIn,
                'check_out' => $checkOut
            ]
        ],
        ['class' => 'button']
    ) ?>
    <?= $this->Form->end() ?>
</div>
