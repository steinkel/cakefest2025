## Intro

Once upon a time, web development was productive and didn't require a steep learning curve.
A brave developer could get a project running within a couple days.
The workshop is aimed to developers who are starting with the framework, or never used it.

## The problem

* the problem: Hotel reservation system
* database schema
  * rooms, bookings, etc

## Project setup

* project setup, composer

```
composer create-project cakephp/app cakefest2025
```

* ddev setup
  * PHP 8.4
* let's check the folder structure

# Hotel Reservation System

* download the db schema from https://raw.githubusercontent.com/steinkel/cakefest2025/master/db.sql
* import schema and sample data into ddev

```
ddev import-db FILE
ddev add-on get ddev/ddev-phpmyadmin
ddev phpmyadmin
```

# Bake shell

```
ddev cake bake all --everything --prefix Admin
```

* fix Admin prefix routing

in config/routes.php
```
    $routes->scope('/', function (RouteBuilder $builder): void {
        $builder->prefix('Admin', function (RouteBuilder $builder) {
            $builder->fallbacks();
        });
    //...
```

# Navigate our admin app

* Validate booking statuses

src/Model/Entity/Booking.php

```
    public const STATUS_PENDING = 'pending';
    public const STATUS_CONFIRMED = 'confirmed';
    public const STATUS_COMPLETED = 'confirmed';
    public const STATUS_CANCELLED = 'cancelled';
    public const STATUSES = [
        self::STATUS_PENDING,
        self::STATUS_CONFIRMED,
        self::STATUS_COMPLETED,
        self::STATUS_CANCELLED,
    ];
```

in BookingsTable
```
            ->inList('booking_status', Booking::STATUSES);
```

OR if you prefer using an enum

```
<?php
namespace App\Enum;

enum Status: string
{
    case Pending = 'pending';
    case Confirmed = 'confirmed';
    case Completed = 'completed';
    case Cancelled = 'cancelled';
}

// and use
$status = Status::Pending;
echo $status->value; // 'pending'
```

* Improve Room display

in Room entity
```
    protected function _getRoomNumberDisplay(): string
    {
        return $this->hotel?->name . ' ' . $this->room_number;
    }
```

in Admin/BookingsController add/edit actions replace room loading with
```
$rooms = $this->Bookings->Rooms->find('list', limit: 200, contain: ['Hotels'])->all();
```

in RoomsTable initialize, replace displayField
```
$this->setDisplayField('room_number_display');
```

* Booking dates validation
  * How would you ensure check in and check out dates are valid?
  * https://book.cakephp.org/5/en/core-libraries/validation.html#using-custom-validation-rules

# Setup cakedc/users

```
ddev composer require cakedc/users
ddev cake plugin load CakeDC/Users
ddev cake migrations migrate -p CakeDC/Users
ddev cake users add_superuser -p password
```

# Hotel Search

in src/Controller/BookingsController

```
<?php
declare(strict_types=1);
namespace App\Controller;

use Cake\Datasource\ModelAwareTrait;
use Cake\I18n\Date;

/**
 * Bookings Controller
 *
 * @property \App\Model\Table\BookingsTable $Bookings
 */
class BookingsController extends AppController
{
    public function search()
    {
        $city = $this->request->getQuery('city');
        $checkIn = $this->request->getQuery('check_in');
        $checkOut = $this->request->getQuery('check_out');

        $hotels = [];
        $searchPerformed = false;

        if ($city && $checkIn && $checkOut) {
            $searchPerformed = true;
            $checkInDate = new Date($checkIn);
            $checkOutDate = new Date($checkOut);

            if ($checkOutDate->lessThanOrEquals($checkInDate)) {
                $this->Flash->error(__('Check-out date must be after check-in date.'));
            } else {
                $hotels = $this->Bookings->Rooms->Hotels
                    ->find()
                    //->find('withAvailableRooms', checkIn: $checkInDate, checkOut: $checkOutDate)
                    ->where(['Hotels.city LIKE' => '%' . $city . '%'])
                    ->toArray();

                if (empty($hotels)) {
                    $this->Flash->warning(__('No hotels with available rooms found in {0} for the selected dates.', h($city)));
                }
            }
        }

        $this->set(compact('hotels', 'city', 'checkIn', 'checkOut', 'searchPerformed'));
    }
}
```

in templates/Bookings/search.php

```
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
```

# Use the ORM

In BookingsController, uncomment the finder call
```
                    ->find('withAvailableRooms', checkIn: $checkInDate, checkOut: $checkOutDate)
```

In HotelsTable, add the custom finder
```
    public function findWithAvailableRooms(SelectQuery $query, Date $checkIn, Date $checkOut): SelectQuery
    {
        $roomsSubquery = $this->Rooms->find()
            ->select(['Rooms.id'])
            ->where(['Rooms.is_available' => true])
            ->where(['Rooms.hotel_id = Hotels.id'])
            ->where(function (QueryExpression $exp) use ($checkIn, $checkOut) {
                // NOT EXISTS any overlapping active booking for the room
                $overlap = $this->Rooms->Bookings->find('active')
                    ->select(['Bookings.id'])
                    ->where(['Bookings.room_id = Rooms.id'])
                    // check if dates overlap
                    ->where([
                        'Bookings.check_in_date <' => $checkOut,
                        'Bookings.check_out_date >' => $checkIn,
                    ]);

                return $exp->notExists($overlap);
            })
            ->limit(1);

        return $query->where(function (QueryExpression $exp) use ($roomsSubquery) {
            return $exp->exists($roomsSubquery);
        });
    }
```

In BookingsTable, add the custom finder

```
    public function findActive(SelectQuery $query): SelectQuery
    {
        return $query->whereNotInList(
            $this->aliasField('booking_status'),
            [Booking::STATUS_CANCELLED]
        );
    }
```

# Select Room

In BookingsController, add the new action

```
    public function availableRooms(int $hotelId)
    {
        $checkIn = $this->request->getQuery('check_in');
        $checkOut = $this->request->getQuery('check_out');
        if (!$checkIn || !$checkOut) {
            $this->Flash->error(__('Please provide check-in and check-out dates.'));
            return $this->redirect(['action' => 'search']);
        }
        $checkInDate = new Date($checkIn);
        $checkOutDate = new Date($checkOut);

        $hotel = $this->Bookings->Rooms->Hotels->get($hotelId);

        $availableRooms = $this->Bookings->Rooms
            ->find('availableForHotel', hotelId: $hotelId, checkIn: $checkInDate, checkOut: $checkOutDate)
            ->contain(['RoomTypes'])
            ->toArray();

        $nights = $checkInDate->diffInDays($checkOutDate);

        $this->set(compact('hotel', 'availableRooms', 'checkIn', 'checkOut', 'nights'));
    }
```

in RoomsTable Add the new finder

```
    public function findAvailableForHotel(SelectQuery $query, int $hotelId, Date $checkIn, Date $checkOut): SelectQuery
    {
        $conflicting = $this->Bookings->find('active')
            ->select(['room_id'])
                ->where([
                    'Bookings.check_in_date <' => $checkOut,
                    'Bookings.check_out_date >' => $checkIn,
                ]);

        $query
            ->where(['Rooms.hotel_id' => $hotelId])
            ->where(['Rooms.is_available' => true]);

        $conflictingRoomIds = $conflicting->all()->extract('room_id')->toList();
        if ($conflictingRoomIds) {
            $query->whereNotInList('Rooms.id', $conflictingRoomIds);
        }

        return $query;
    }
```
* Note there is a good opportunity for refactor & reuse here ...

Add new template under Bookings/available_rooms.php

```
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
```


