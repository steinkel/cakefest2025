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

