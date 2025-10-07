<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Hotel $hotel
 * @var \App\Model\Entity\Room[] $availableRooms
 * @var string $checkIn
 * @var string $checkOut
 * @var int $nights
 */
?>
<div class="bookings available-rooms content">
    <?= $this->Html->link(__('← Back to Search'), ['action' => 'search', '?' => ['city' => $hotel->city, 'check_in' => $checkIn, 'check_out' => $checkOut]], ['class' => 'button']) ?>

    <h1><?= h($hotel->name) ?></h1>

    <p>
        <strong><?= __('Address:') ?></strong>
        <?= h($hotel->address) ?>, <?= h($hotel->city) ?>, <?= h($hotel->state) ?>, <?= h($hotel->country) ?>
    </p>

    <?php if ($hotel->star_rating) : ?>
        <p>
            <strong><?= __('Rating:') ?></strong>
            <?= str_repeat('⭐', $hotel->star_rating) ?>
        </p>
    <?php endif; ?>

    <p>
        <strong><?= __('Check-in:') ?></strong> <?= h($checkIn) ?>
        <strong><?= __('Check-out:') ?></strong> <?= h($checkOut) ?>
        <strong><?= __('Nights:') ?></strong> <?= $nights ?>
    </p>

    <h2><?= __('Available Rooms') ?></h2>

    <?php if (empty($availableRooms)) : ?>
        <p><?= __('Sorry, no rooms are available for the selected dates.') ?></p>
        <p><?= __('Please try different dates or choose another hotel.') ?></p>
    <?php else : ?>
        <p><?= __('Found {0} available room(s)', count($availableRooms)) ?></p>

        <?php foreach ($availableRooms as $room) : ?>
            <div class="room">
                <h3><?= h($room->room_type->type_name) ?> - <?= __('Room #') ?><?= h($room->room_number) ?></h3>

                <?php if ($room->room_type->description) : ?>
                    <p><?= h($room->room_type->description) ?></p>
                <?php endif; ?>

                <p>
                    <strong><?= __('Max Occupancy:') ?></strong>
                    <?= $room->room_type->max_occupancy ?>
                    <?= __('guest(s)') ?>
                </p>

                <p>
                    <strong><?= __('Pets Allowed:') ?></strong>
                    <?= $room->room_type->pets_allowed ? __('Yes') : __('No') ?>
                </p>

                <p>
                    <strong><?= __('Price per night:') ?></strong>
                    $<?= $this->Number->format($room->room_type->base_price, ['places' => 2]) ?>
                </p>

                <p>
                    <strong><?= __('Total for {0} night(s):', $nights) ?></strong>
                    $<?= $this->Number->format($room->room_type->base_price * $nights, ['places' => 2]) ?>
                </p>

                <?= $this->Html->link(
                    __('Book This Room'),
                    [
                        'action' => 'startReservation',
                        $room->id,
                        '?' => [
                            'check_in' => $checkIn,
                            'check_out' => $checkOut
                        ]
                    ],
                    ['class' => 'button']
                ) ?>
            </div>
            <hr>
        <?php endforeach; ?>
    <?php endif; ?>
</div>
