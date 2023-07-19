<?php
declare(strict_types=1);

namespace ChangecalendarModule\Command;

use App\Model\Table\ServicetemplategroupsTable;
use Cake\Command\Command;
use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;
use Cake\ORM\Entity;
use Cake\ORM\TableRegistry;
use ChangecalendarModule\Model\Table\ChangecalendarEventsTable;
use ChangecalendarModule\Model\Table\ChangecalendarsTable;
use PhpParser\Node\Arg;

/**
 * Import command.
 */
class ImportCommand extends Command {
    /**
     * Hook method for defining this command's option parser.
     *
     * @see https://book.cakephp.org/4/en/console-commands/commands.html#defining-arguments-and-options
     * @param \Cake\Console\ConsoleOptionParser $parser The parser to be defined
     * @return \Cake\Console\ConsoleOptionParser The built parser.
     */
    public function buildOptionParser(ConsoleOptionParser $parser): ConsoleOptionParser {
        $parser = parent::buildOptionParser($parser);

        $parser->addOption('filepath', [
            'short'    => 'f',
            'help'     => 'Path to imported file',
            'default'  => false,
            'required' => true
        ]);

        return $parser;
    }

    /**
     * Implement this method with your command's logic.
     *
     * @param \Cake\Console\Arguments $args The command arguments.
     * @param \Cake\Console\ConsoleIo $io The console io
     * @return null|void|int The exit code or null for success
     */
    public function execute(Arguments $args, ConsoleIo $io) {
        $args->getArgument('file');

        $filepath = $args->getOption('filepath');
        if (empty($filepath)) {
            $io->error('Please specify the file to import');
            exit (1);
        }

        if (!file_exists($filepath) || !is_readable($filepath)) {
            $io->error("File $filepath does not exist or is not readable.");
            exit (1);
        }


        $a = $this->getFile($filepath);


        $line = 0;
        foreach ($a as $b) {
            if ($line === 0) {
                $line++;
                continue;
            }
            $this->importRow($b);
        }
        exit(0);

    }

    private function getFile(string $filepath): \SplFileObject {

        $csvFile = new \SplFileObject($filepath, 'r');
        $csvFile->setFlags(
            \SplFileObject::READ_CSV
            | \SplFileObject::READ_AHEAD
            | \SplFileObject::SKIP_EMPTY
            | \SplFileObject::DROP_NEW_LINE
        );
        #$this->csvFile->setCsvControl($this->importer->getDelimeter());
        return $csvFile;
    }

    private function importRow(array $row): void {
        // NORMALIZE $H!T
        $obj = [
            'uid'                 => $row[0],
            'changecalendar_name' => $row[1],
            'begin'               => new \DateTime($row[2]),
            'end'                 => new \DateTime($row[3]),
            'name'                => $row[4],
            'description'         => $row[5]
        ];


        // Search for the calendar for the entity to be put or moved in.
        $changeCalendar = $this->getChangeCalendar($obj);
        $obj['changecalendar_id'] = $changeCalendar->id;

        // Either fetch the existing event or create an empty entity.
        $changeCalendarEvent = $this->getEvent($obj);

        // Now override the entity with the new data
        $changeCalendarEvent->set($obj);


        // Save the $h!t to the db.
        /** @var ChangecalendarEventsTable $changecalendarEventsTable */
        $changecalendarEventsTable = TableRegistry::getTableLocator()->get('ChangecalendarModule.ChangecalendarEvents');
        $changecalendarEventsTable->save($changeCalendarEvent);
    }

    private function getChangeCalendar(array $obj): Entity {
        /** @var ChangecalendarsTable $ChangecalendarsTable */
        $ChangecalendarsTable = TableRegistry::getTableLocator()->get('ChangecalendarModule.Changecalendars');

        $a = $ChangecalendarsTable
            ->find()
            ->where([
                'name' => $obj['changecalendar_name']
            ])
            ->firstOrFail();

        return $a;
    }

    private function getEvent(array $obj): Entity {

        /** @var ChangecalendarEventsTable $changecalendarEventsTable */
        $changecalendarEventsTable = TableRegistry::getTableLocator()->get('ChangecalendarModule.ChangecalendarEvents');


        $event = $changecalendarEventsTable
            ->find()
            ->where([
                'uid' => $obj['uid']
            ])
            ->first();

        if (empty($event)) {
            return $changecalendarEventsTable->newEmptyEntity();
        }

        return $event;
    }


}
