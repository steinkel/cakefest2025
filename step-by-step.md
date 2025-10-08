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

# Complete reservation

In BookingsController add the new action

```
    public function startReservation(?int $roomId)
    {
        $checkIn = new Date($this->request->getQuery('check_in'));
        $checkOut = new Date($this->request->getQuery('check_out'));

        if (!$checkIn || !$checkOut) {
            $this->Flash->error(__('Please provide check-in and check-out dates.'));
            return $this->redirect(['action' => 'search']);
        }

        $room = $this->Bookings->Rooms->get($roomId, [
            'contain' => ['RoomTypes', 'Hotels']
        ]);

        // Calculate total amount
        $nights = $checkIn->diffInDays($checkOut);
        $totalAmount = $room->room_type->base_price * $nights;

        $booking = $this->Bookings->newEmptyEntity();

        if ($this->request->is('post')) {
            $result = $this->Bookings->getConnection()->transactional(function () use ($roomId, $checkIn, $checkOut, $totalAmount, $booking) {
                // Lock the room
                $this->Bookings->Rooms->find()
                    ->where(['Rooms.id' => $roomId])
                    ->epilog('FOR UPDATE')
                    ->firstOrFail();

                $hasConflict = $this->Bookings
                        ->find('overlapping', roomId: (int)$roomId, checkIn: $checkIn, checkOut: $checkOut)
                        ->count() > 0;

                if ($hasConflict) {
                    return ['success' => false, 'conflict' => true, 'booking' => $booking];
                }

                // Patch entity with associated data
                $data = $this->request->getData();
                $data['check_in_date'] = $checkIn;
                $data['check_out_date'] = $checkOut;
                $data['total_amount'] = $totalAmount;
                $data['room_id'] = $roomId;
                $booking = $this->Bookings->patchEntity($booking, $data, [
                    'associated' => ['Customers']
                ]);

                if ($this->Bookings->save($booking, ['associated' => ['Customers']])) {
                    return ['success' => true, 'booking' => $booking];
                }

                return ['success' => false, 'conflict' => false, 'booking' => $booking];
            });

            if ($result['success'] ?? false) {
                $booking = $result['booking'];
                $this->Flash->success(__('Your reservation has been created successfully!'));
                return $this->redirect(['action' => 'view', $booking->id]);
            }

            $booking = $result['booking'] ?? $booking;
            if ($result['conflict']) {
                $this->Flash->error(__('Selected room is not available for the chosen dates.'));
            } else {
                $this->Flash->error(__('Unable to create your reservation. Please check the form and try again.'));
            }
        }

        $this->set(compact('booking', 'room', 'checkIn', 'checkOut', 'nights', 'totalAmount'));
    }
```
* We have an opportunity to refactor this method, how?

In BookingsTable, create the finder

```
    public function findOverlapping(SelectQuery $query, int $roomId, Date $checkIn, Date $checkOut): SelectQuery
    {
        return $query
            ->find('active')
            ->where([$this->aliasField('room_id') => $roomId])
            ->where([
                $this->aliasField('check_in_date') . ' <' => $checkOut,
                $this->aliasField('check_out_date') . ' >' => $checkIn,
            ]);
    }
```

Add new template Bookings/start_reservation.php

```
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
```
* Do you see repeated code in this template that could be refactored?

# View Booking

in BookingsController, add new action

```
public function view(int $id)
    {
        $booking = $this->Bookings->get(
            $id,
            contain: [
                'Customers',
                'Rooms' => ['RoomTypes', 'Hotels'],
                'Payments'
            ],
        );

        $this->set(compact('booking'));
    }
```

Add new template Bookings/view.php
```
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
```
* Now that you identified refactor options for templates, give the view template a better structure

# Public permissions

* Copy permissions file from cakedc/users and add
```
        [
            'role' => '*',
            'prefix' => false,
            'controller' => 'Bookings',
            'action' => '*',
            'bypassAuth' => true,
        ],
```

# Softdelete

* https://github.com/UseMuffin/Trash
```
composer require muffin/trash
ddev cake plugin load Muffin/Trash
```
* Add to HotelsTable
```
$this->addBehavior('Muffin/Trash.Trash');
```
* Add the required migration

```
ddev cake bake migration DeletedToHotels
```

```
public function change(): void
    {
        $this->table('hotels')
            ->addColumn('deleted', 'datetime', [
                'default' => null,
                'null' => true,
            ])
            ->update();
    }
```

# MCP Server

* We will be using Claude Desktop. Download it and register for a free account.
* Install the MCP Plugin

```
ddev composer config minimum-stability dev
ddev composer require cakedc/cakephp-mcp:dev-master
```

* Create bin/mcp file and set execution (+x) permissions
* Ensure you override your project root
```
#!/usr/bin/env sh

cd YOUR_PROJECT_ROOT && ddev exec php /var/www/html/vendor/cakedc/cakephp-mcp/bin/mcp-server
```

* Edit your Claude local config

```
vi $HOME/.config/Claude/claude_desktop_config.json
```

```
{
    "mcpServers": {
        "default-server": {
            "command": "FULL_PATH_TO_YOUR/bin/mcp",
            "args": [

            ],
            "env": {
                "FILE_LOG": "1"
            }
        }
    }
}
```

* Now let's create a couple endpoints to use
* Add the following 2 files to src/Mcp folder

Bookings.php
```
<?php

namespace App\Mcp;

use App\Application;
use Cake\Core\Configure;
use Cake\Datasource\ModelAwareTrait;
use Cake\Http\Server;
use Cake\I18n\Date;
use Cake\ORM\Locator\LocatorAwareTrait;
use Mcp\Capability\Attribute\McpTool;
use Mcp\JsonRpc\Handler;
use Mcp\Server\ServerBuilder;
use Mcp\Server\TransportInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

class Bookings
{
    use LocatorAwareTrait;

    protected const PAGE_SIZE = 20;


    /**
     * Searches for bookings within the provided check-in date range. The results are paginated.
     *
     * @param string $checkinFromDate The start date of the check-in range in 'YYYY-MM-DD' format.
     * @param string $checkinToDate The end date of the check-in range in 'YYYY-MM-DD' format.
     * @param int|null $page The page number for pagination. Defaults to 1 if not provided.
     * @return array An array containing the search results with booking data and pagination details.
     */
    #[McpTool(name: 'searchByCheckinDate')]
    public function searchByCheckinDate(string $checkinFromDate, string $checkinToDate, ?int $page = 1): array
    {
        $bookingsQuery = $this->fetchTable('Bookings')
            ->find()
            ->limit(self::PAGE_SIZE)
            ->page($page)
            ->where([
                'check_in_date >=' => new Date($checkinFromDate),
                'check_in_date <=' => new Date($checkinToDate)
            ])
            ->contain(['Customers', 'Rooms.RoomTypes', 'Rooms.Hotels'])
            ->disableHydration()
            ->disableResultsCasting();

        $total = $bookingsQuery->count();

        return [
            'data' => $bookingsQuery->toArray(),
            'pagination' => [
                'page' => $page,
                'pageSize' => 20,
                'total' => $total,
                'hasMore' => ($page * self::PAGE_SIZE) < $total,
            ],
        ];
    }
}
```

Customers.php
```
<?php

namespace App\Mcp;

use Cake\Mailer\Mailer;
use Cake\ORM\Locator\LocatorAwareTrait;
use Mcp\Capability\Attribute\McpTool;

class Customers
{
    use LocatorAwareTrait;

    /**
     * Send email to customer
     *
     * @param int $customerId
     * @param string $subject
     * @param string $message
     * @return array
     */
    #[McpTool(name: 'sendEmailToCustomer')]
    public function sendEmailToCustomer(int $customerId, string $subject, string $message): array
    {
        try {
            $customer = $this->fetchTable('Customers')->get($customerId);
            $mailer = new Mailer('default');
            $mailer->setFrom(['me@example.com' => 'My Site'])
                ->setTo($customer->email)
                ->setSubject($subject)
                ->deliver($message);
        } catch (\Throwable $e) {
            return [
                'success' => false,
                'message' => 'Failed to send email to customer ' . $e->getMessage(),
            ];
        }

        return [
            'success' => true,
            'message' => 'Email sent successfully to customer ' . $customer->email,
        ];
    }
}
```

* Now run Claude desktop, you should be able to see the default-server enabled

# Sending emails & commands

* Create src/Command/BookingsUpcomingCommand.php

```
<?php

declare(strict_types=1);

namespace App\Command;

use Cake\Command\Command;
use Cake\Command\Helper\ProgressHelper;
use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;
use Cake\Mailer\MailerAwareTrait;

/**
 * BookingsUpcoming command.
 */
class BookingsUpcomingCommand extends Command
{
    use MailerAwareTrait;

    /**
     * The name of this command.
     *
     * @var string
     */
    protected string $name = 'bookings_upcoming';

    /**
     * Get the default command name.
     *
     * @return string
     */
    public static function defaultName(): string
    {
        return 'bookings_upcoming';
    }

    /**
     * Get the command description.
     *
     * @return string
     */
    public static function getDescription(): string
    {
        return 'Send upcoming emails to bookings';
    }

    /**
     * Hook method for defining this command's option parser.
     *
     * @see https://book.cakephp.org/5/en/console-commands/commands.html#defining-arguments-and-options
     * @param \Cake\Console\ConsoleOptionParser $parser The parser to be defined
     * @return \Cake\Console\ConsoleOptionParser The built parser.
     */
    public function buildOptionParser(ConsoleOptionParser $parser): ConsoleOptionParser
    {
        return parent::buildOptionParser($parser)
            ->addArgument('when', [
                'description' => 'Send upcoming email to all bookings checking in before `when` date, for example "tomorrow"',
                'default' => 'tomorrow',
            ])
            ->setDescription(static::getDescription());
    }

    /**
     * Implement this method with your command's logic.
     *
     * @param \Cake\Console\Arguments $args The command arguments.
     * @param \Cake\Console\ConsoleIo $io The console io
     * @return int|null|void The exit code or null for success
     */
    public function execute(Arguments $args, ConsoleIo $io)
    {
        $when = $args->getArgument('when');
        /**
         * @var ProgressHelper $progress
         */
        $progress = $io->helper('Progress');
        $bookings = $this->fetchTable('Bookings')
            ->find()
            ->where(['Bookings.check_in_date <=' => $when])
            ->contain(['Customers', 'Rooms.RoomTypes', 'Rooms.Hotels']);
        $progress->init([
            'total' => $bookings->count(),
        ]);
        $mailer = $this->getMailer('Bookings');
        $bookings
            ->disableBufferedResults()
            ->all()
            ->each(function ($booking) use ($progress, $mailer, $when) {
                $mailer->send('upcoming', [$booking, $when]);
                $progress->increment();
                $progress->draw();
            });
    }
}
```

* Create src/Mailer/BookingsMailer.php
```
<?php

declare(strict_types=1);

namespace App\Mailer;

use App\Model\Entity\Booking;
use Cake\I18n\Date;
use Cake\Mailer\Mailer;

/**
 * Bookings mailer.
 */
class BookingsMailer extends Mailer
{
    /**
     * Mailer's name.
     *
     * @var string
     */
    public static string $name = 'Bookings';

    public function upcoming(Booking $booking, string $when): void
    {
        $bookingsTable = $this->fetchTable('Bookings');
        if ($booking->check_in_date > new Date($when)) {
            return;
        }
        if (!$booking->customer) {
            $bookingsTable->loadInto($booking, ['Customers']);
        }
        if (!$booking->room?->hotel) {
            $bookingsTable->loadInto($booking, ['Rooms.Hotels']);
        }

        $this
            ->setTo($booking->customer->email)
            ->setSubject('Upcoming Booking')
            ->setViewVars([
                'booking' => $booking,
            ]);
    }
}
```

* Create the related templates/email/text/upcoming.php
```
Hi <?= h($booking->customer->first_name) ?>,

This is a reminder of your upcoming reservation at <?= h($booking->room->hotel->name) ?>.

Thank you,
```
