<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Booking Entity
 *
 * @property int $id
 * @property int $customer_id
 * @property int $room_id
 * @property \Cake\I18n\Date $check_in_date
 * @property \Cake\I18n\Date $check_out_date
 * @property int $number_of_guests
 * @property string $total_amount
 * @property string|null $booking_status
 * @property string|null $special_requests
 * @property \Cake\I18n\DateTime|null $created
 * @property \Cake\I18n\DateTime|null $modified
 *
 * @property \App\Model\Entity\Customer $customer
 * @property \App\Model\Entity\Room $room
 * @property \App\Model\Entity\Payment[] $payments
 */
class Booking extends Entity
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
        'customer_id' => true,
        'room_id' => true,
        'check_in_date' => true,
        'check_out_date' => true,
        'number_of_guests' => true,
        'total_amount' => true,
        'booking_status' => true,
        'special_requests' => true,
        'created' => true,
        'modified' => true,
        'customer' => true,
        'room' => true,
        'payments' => true,
    ];
}
