<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * RoomsFixture
 */
class RoomsFixture extends TestFixture
{
    /**
     * Init method
     *
     * @return void
     */
    public function init(): void
    {
        $this->records = [
            [
                'id' => 1,
                'hotel_id' => 1,
                'room_type_id' => 1,
                'room_number' => 'Lorem ip',
                'is_available' => 1,
                'created' => '2025-10-07 20:39:02',
                'modified' => '2025-10-07 20:39:02',
            ],
        ];
        parent::init();
    }
}
