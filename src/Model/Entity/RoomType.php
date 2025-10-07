<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * RoomType Entity
 *
 * @property int $id
 * @property string $type_name
 * @property string|null $description
 * @property int $max_occupancy
 * @property string $base_price
 * @property bool|null $pets_allowed
 * @property \Cake\I18n\DateTime|null $created
 * @property \Cake\I18n\DateTime|null $modified
 *
 * @property \App\Model\Entity\Room[] $rooms
 */
class RoomType extends Entity
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
        'type_name' => true,
        'description' => true,
        'max_occupancy' => true,
        'base_price' => true,
        'pets_allowed' => true,
        'created' => true,
        'modified' => true,
        'rooms' => true,
    ];
}
