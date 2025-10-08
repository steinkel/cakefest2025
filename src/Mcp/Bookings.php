<?php

namespace App\Mcp;

use App\Application;
use Cake\Core\Configure;
use Cake\Datasource\ModelAwareTrait;
use Cake\Http\Server;
use Cake\I18n\Date;
use Cake\ORM\Locator\LocatorAwareTrait;
use Mcp\Capability\Attribute\McpTool;
use Mcp\JsonRpc\Handler;
use Mcp\Server\ServerBuilder;
use Mcp\Server\TransportInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

class Bookings
{
    use LocatorAwareTrait;

    protected const PAGE_SIZE = 20;


    /**
     * Searches for bookings within the provided check-in date range. The results are paginated.
     *
     * @param string $checkinFromDate The start date of the check-in range in 'YYYY-MM-DD' format.
     * @param string $checkinToDate The end date of the check-in range in 'YYYY-MM-DD' format.
     * @param int|null $page The page number for pagination. Defaults to 1 if not provided.
     * @return array An array containing the search results with booking data and pagination details.
     */
    #[McpTool(name: 'searchByCheckinDate')]
    public function searchByCheckinDate(string $checkinFromDate, string $checkinToDate, ?int $page = 1): array
    {
        $bookingsQuery = $this->fetchTable('Bookings')
            ->find()
            ->limit(self::PAGE_SIZE)
            ->page($page)
            ->where([
                'check_in_date >=' => new Date($checkinFromDate),
                'check_in_date <=' => new Date($checkinToDate)
            ])
            ->contain(['Customers', 'Rooms.RoomTypes', 'Rooms.Hotels'])
            ->disableHydration()
            ->disableResultsCasting();

        $total = $bookingsQuery->count();

        return [
            'data' => $bookingsQuery->toArray(),
            'pagination' => [
                'page' => $page,
                'pageSize' => 20,
                'total' => $total,
                'hasMore' => ($page * self::PAGE_SIZE) < $total,
            ],
        ];
    }
}
