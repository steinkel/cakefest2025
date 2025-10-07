<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * BookingsFixture
 */
class BookingsFixture extends TestFixture
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
                'customer_id' => 1,
                'room_id' => 1,
                'check_in_date' => '2025-10-07',
                'check_out_date' => '2025-10-07',
                'number_of_guests' => 1,
                'total_amount' => 1.5,
                'booking_status' => 'Lorem ipsum dolor sit amet',
                'special_requests' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
                'created' => '2025-10-07 20:39:02',
                'modified' => '2025-10-07 20:39:02',
            ],
        ];
        parent::init();
    }
}
