<?php
declare(strict_types=1);

namespace ChangecalendarModule\Command;

use Cake\Command\Command;
use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;
use Cake\ORM\Entity;
use Cake\ORM\TableRegistry;
use ChangecalendarModule\Model\Table\ChangecalendarEventsTable;
use ChangecalendarModule\Model\Table\ChangecalendarsTable;

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
            $line++;
            if ($line === 1) {
                continue;
            }
            try {
                $this->importRow($b);
            } catch (\InvalidArgumentException $exception) {
                $io->error("Row $line is invalid. See:");
                $io->error($exception->getMessage());
            }
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

    /**
     * I will formulate an array of mostly valid data for the given $row.
     *
     * @param array $row
     * @return void
     * @throws \InvalidArgumentException In case the given $row is not valid.
     */
    private function formulate(array $row): array {
        if (empty($row[0])) {
            throw new \InvalidArgumentException('// UID is empty');
        }
        if (empty($row[1])) {
            throw new \InvalidArgumentException('// changecalendar name is empty');
        }
        if (empty($row[2])) {
            throw new \InvalidArgumentException('// begin is empty');
        }
        if (empty($row[3])) {
            throw new \InvalidArgumentException('// end is empty');
        }
        if (empty($row[4])) {
            throw new \InvalidArgumentException('// name is empty');
        }
        if (empty($row[5])) {
            $row[5] = '';
        }
        if (empty($row[6])) {
            var_dump($row);
            throw new \InvalidArgumentException('// context is empty');
        }
        $context = (array)json_decode(str_replace("'", '"', $row[6] ?? 'null'), true);
        var_dump($context);
        foreach ($context as $index => $fieldObject) {
            if (empty($fieldObject['name'])) {
                throw new \InvalidArgumentException("Context field (#$index) has no name.");
            }
            if (empty($fieldObject['value'] ?? '')) {
                $fieldObject['value'] = '';
            }
            if (empty($fieldObject['class'])) {
                $fieldObject['class'] = '';
            }

        }
        return [
            'uid'                 => $row[0],
            'changecalendar_name' => $row[1],
            'begin'               => new \DateTime($row[2]),
            'end'                 => new \DateTime($row[3]),
            'name'                => $row[4],
            'description'         => $row[5],
            'context'             => $context
        ];
    }

    /**
     * I will import the current $row of the CSV.
     *
     * @param array $row
     * @return void
     * @throws \Exception
     */
    private function importRow(array $row): void {
        $object = $this->formulate($row);

        // Search for the calendar for the entity to be put or moved in.
        $changeCalendar = $this->getChangeCalendar($object);
        $object['changecalendar_id'] = $changeCalendar->id;

        // Either fetch the existing event or create an empty entity.
        $changeCalendarEvent = $this->getEvent($object);

        // Now override the entity with the new data
        $changeCalendarEvent->set($object);


        // Save the $h!t to the db.
        /** @var ChangecalendarEventsTable $changecalendarEventsTable */
        $changecalendarEventsTable = TableRegistry::getTableLocator()->get('ChangecalendarModule.ChangecalendarEvents');
        $changecalendarEventsTable->save($changeCalendarEvent);
    }

    /**
     * I will find the Changecalendar Entity for the given $object.
     *
     * @param array $object
     * @return Entity
     */
    private function getChangeCalendar(array $object): Entity {
        /** @var ChangecalendarsTable $ChangecalendarsTable */
        $ChangecalendarsTable = TableRegistry::getTableLocator()->get('ChangecalendarModule.Changecalendars');

        $entity = $ChangecalendarsTable
            ->find()
            ->where([
                'name' => $object['changecalendar_name']
            ])
            ->firstOrFail();

        return $entity;
    }

    /**
     * I will find the correct ChangecalendarEvent Entity for the given $object.
     * If it does not exist yet, I will return an empty Entity.
     *
     * @param array $obj
     * @return Entity
     */
    private function getEvent(array $obj): Entity {

        /** @var ChangecalendarEventsTable $changecalendarEventsTable */
        $changecalendarEventsTable = TableRegistry::getTableLocator()->get('ChangecalendarModule.ChangecalendarEvents');

        $entity = $changecalendarEventsTable
            ->find()
            ->where([
                'uid' => $obj['uid']
            ])
            ->first();

        if (empty($entity) || !$entity instanceof Entity) {
            return $changecalendarEventsTable->newEmptyEntity();
        }

        return $entity;
    }


}
