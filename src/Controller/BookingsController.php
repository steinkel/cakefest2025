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
                    ->find('withAvailableRooms', checkIn: $checkInDate, checkOut: $checkOutDate)
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
