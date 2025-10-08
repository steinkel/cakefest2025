<?php

declare(strict_types=1);

namespace App\Command;

use Cake\Command\Command;
use Cake\Command\Helper\ProgressHelper;
use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;
use Cake\Mailer\MailerAwareTrait;

/**
 * BookingsUpcoming command.
 */
class BookingsUpcomingCommand extends Command
{
    use MailerAwareTrait;

    /**
     * The name of this command.
     *
     * @var string
     */
    protected string $name = 'bookings_upcoming';

    /**
     * Get the default command name.
     *
     * @return string
     */
    public static function defaultName(): string
    {
        return 'bookings_upcoming';
    }

    /**
     * Get the command description.
     *
     * @return string
     */
    public static function getDescription(): string
    {
        return 'Send upcoming emails to bookings';
    }

    /**
     * Hook method for defining this command's option parser.
     *
     * @see https://book.cakephp.org/5/en/console-commands/commands.html#defining-arguments-and-options
     * @param \Cake\Console\ConsoleOptionParser $parser The parser to be defined
     * @return \Cake\Console\ConsoleOptionParser The built parser.
     */
    public function buildOptionParser(ConsoleOptionParser $parser): ConsoleOptionParser
    {
        return parent::buildOptionParser($parser)
            ->addArgument('when', [
                'description' => 'Send upcoming email to all bookings checking in before `when` date, for example "tomorrow"',
                'default' => 'tomorrow',
            ])
            ->setDescription(static::getDescription());
    }

    /**
     * Implement this method with your command's logic.
     *
     * @param \Cake\Console\Arguments $args The command arguments.
     * @param \Cake\Console\ConsoleIo $io The console io
     * @return int|null|void The exit code or null for success
     */
    public function execute(Arguments $args, ConsoleIo $io)
    {
        $when = $args->getArgument('when');
        /**
         * @var ProgressHelper $progress
         */
        $progress = $io->helper('Progress');
        $bookings = $this->fetchTable('Bookings')
            ->find()
            ->where(['Bookings.check_in_date <=' => $when])
            ->contain(['Customers', 'Rooms.RoomTypes', 'Rooms.Hotels']);
        $progress->init([
            'total' => $bookings->count(),
        ]);
        $mailer = $this->getMailer('Bookings');
        $bookings
            ->disableBufferedResults()
            ->all()
            ->each(function ($booking) use ($progress, $mailer, $when) {
                $mailer->send('upcoming', [$booking, $when]);
                $progress->increment();
                $progress->draw();
            });
    }
}
