<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Room Entity
 *
 * @property int $id
 * @property int $hotel_id
 * @property int $room_type_id
 * @property string $room_number
 * @property bool|null $is_available
 * @property \Cake\I18n\DateTime|null $created
 * @property \Cake\I18n\DateTime|null $modified
 *
 * @property \App\Model\Entity\Hotel $hotel
 * @property \App\Model\Entity\RoomType $room_type
 * @property \App\Model\Entity\Booking[] $bookings
 */
class Room extends Entity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array<string, bool>
     */
    protected array $_accessible = [
        'hotel_id' => true,
        'room_type_id' => true,
        'room_number' => true,
        'is_available' => true,
        'created' => true,
        'modified' => true,
        'hotel' => true,
        'room_type' => true,
        'bookings' => true,
    ];
}
