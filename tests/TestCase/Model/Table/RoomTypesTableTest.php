<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\RoomTypesTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\RoomTypesTable Test Case
 */
class RoomTypesTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\RoomTypesTable
     */
    protected $RoomTypes;

    /**
     * Fixtures
     *
     * @var array<string>
     */
    protected array $fixtures = [
        'app.RoomTypes',
        'app.Rooms',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('RoomTypes') ? [] : ['className' => RoomTypesTable::class];
        $this->RoomTypes = $this->getTableLocator()->get('RoomTypes', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->RoomTypes);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @link \App\Model\Table\RoomTypesTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
