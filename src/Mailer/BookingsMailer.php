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
