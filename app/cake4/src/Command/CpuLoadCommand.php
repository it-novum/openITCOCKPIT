<?php
declare(strict_types=1);

namespace App\Command;

use Cake\Console\Arguments;
use Cake\Console\Command;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;
use itnovum\openITCOCKPIT\Core\Interfaces\CronjobInterface;

/**
 * CpuLoad command.
 */
class CpuLoadCommand extends Command implements CronjobInterface {

    /**
     * Hook method for defining this command's option parser.
     *
     * @see https://book.cakephp.org/3.0/en/console-and-shells/commands.html#defining-arguments-and-options
     *
     * @param \Cake\Console\ConsoleOptionParser $parser The parser to be defined
     * @return \Cake\Console\ConsoleOptionParser The built parser.
     */
    public function buildOptionParser(ConsoleOptionParser $parser): ConsoleOptionParser {
        $parser = parent::buildOptionParser($parser);

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
        if (file_exists('/proc/loadavg')) {
            $io->out('Fetch current CPU load...', 0);
            $load = file('/proc/loadavg');
            $records = [];
            if (file_exists(TMP . 'loadavg')) {
                $records = file(TMP . 'loadavg');
            }
            $newLoad = [];
            if (sizeof($records) > 15) {
                //Truncate file if more that 15 entries
                $records = array_reverse($records);
                for ($i = 0; $i < 15; $i++) {
                    $newLoad[] = $records[$i];
                }
                $newLoad = array_reverse($newLoad);
            } else {
                $newLoad = $records;
            }
            $newLoad[] = time() . ' ' . $load[0];
            unset($records);
            $file = fopen(TMP . 'loadavg', 'w+');
            foreach ($newLoad as $line) {
                fwrite($file, $line);
            }
            fclose($file);
            $io->success('   Ok');
            $io->hr();
        }
    }
}
