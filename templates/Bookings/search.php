<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Hotel[] $hotels
 * @var string|null $city
 * @var string|null $checkIn
 * @var string|null $checkOut
 * @var bool $searchPerformed
 */
?>
<div class="bookings search content">
    <h1><?= __('Search Hotels') ?></h1>

    <div class="search-form">
        <?= $this->Form->create(null, [
            'type' => 'get',
            'valueSources' => 'query',
        ]) ?>
        <fieldset>
            <legend><?= __('Available Hotels') ?></legend>

            <?= $this->Form->control('city', [
                'label' => __('City'),
                'placeholder' => __('Enter city name'),
                'required' => true,
                'value' => h($city)
            ]) ?>

            <?= $this->Form->control('check_in', [
                'type' => 'date',
                'label' => __('Check-in Date'),
                'required' => true,
                'min' => new \Cake\I18n\Date(),
                'value' => h($checkIn)
            ]) ?>

            <?= $this->Form->control('check_out', [
                'type' => 'date',
                'label' => __('Check-out Date'),
                'required' => true,
                'min' => new \Cake\I18n\Date('tomorrow'),
                'value' => h($checkOut)
            ]) ?>
        </fieldset>

        <?= $this->Form->button(__('Search'), ['class' => 'button']) ?>
        <?= $this->Form->end() ?>
    </div>

    <?php if ($searchPerformed) : ?>

        <?php if (!empty($hotels)) : ?>
            <h2><?= __('Hotels Found!') ?></h2>
            <p><?= __('Found {0} hotel(s) in {1}', count($hotels), h($city)) ?></p>

            <?php foreach ($hotels as $hotel) : ?>
                <div class="hotel">
                    <h3><?= h($hotel->name) ?></h3>

                    <p>
                        <strong><?= __('Address:') ?></strong>
                        <?= h($hotel->address) ?>,
                        <?= h($hotel->city) ?>,
                        <?= h($hotel->state) ?>,
                        <?= h($hotel->country) ?>
                    </p>

                    <?php if ($hotel->star_rating) : ?>
                        <p>
                            <strong><?= __('Rating:') ?></strong>
                            <?= str_repeat('⭐', $hotel->star_rating) ?>
                        </p>
                    <?php endif; ?>

                    <p>
                        <strong><?= __('Contact:') ?></strong>
                        <?= h($hotel->email) ?>
                    </p>

                    <p>
                        <strong><?= __('Availability:') ?></strong>
                        <?= __('Rooms available for your selected dates') ?>
                    </p>

                    <?= $this->Html->link(
                        __('View Available Rooms'),
                        [
                            'action' => 'availableRooms',
                            $hotel->id,
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
    <?php endif; ?>
</div>
