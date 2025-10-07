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

    public function startReservation(int $roomId)
    {
        $checkIn = new Date($this->request->getQuery('check_in'));
        $checkOut = new Date($this->request->getQuery('check_out'));

        if (!$checkIn || !$checkOut) {
            $this->Flash->error(__('Please provide check-in and check-out dates.'));
            return $this->redirect(['action' => 'search']);
        }

        $room = $this->Bookings->Rooms->get(
            $roomId, contain: ['RoomTypes', 'Hotels']
        );

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
}
