<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Hotel Entity
 *
 * @property int $id
 * @property string $name
 * @property string $address
 * @property string $city
 * @property string|null $state
 * @property string|null $country
 * @property string|null $email
 * @property int|null $star_rating
 * @property \Cake\I18n\DateTime|null $created
 * @property \Cake\I18n\DateTime|null $modified
 *
 * @property \App\Model\Entity\Room[] $rooms
 */
class Hotel extends Entity
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
        'name' => true,
        'address' => true,
        'city' => true,
        'state' => true,
        'country' => true,
        'email' => true,
        'star_rating' => true,
        'created' => true,
        'modified' => true,
        'rooms' => true,
    ];
}
