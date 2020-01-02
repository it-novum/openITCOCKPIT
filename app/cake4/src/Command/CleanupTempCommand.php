<?php
declare(strict_types=1);

namespace App\Command;

use Cake\Console\Arguments;
use Cake\Console\Command;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;
use Cake\Filesystem\Folder;
use itnovum\openITCOCKPIT\Core\Interfaces\CronjobInterface;

/**
 * CleanupTemp command.
 */
class CleanupTempCommand extends Command implements CronjobInterface {
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
        $io->out('Delete temporary files...', 0);
        $this->clenupGrapher();
        $this->clenupPiCharts();
        $io->success('   Ok');
        $io->hr();
    }

    public function clenupGrapher() {
        $path = WWW_ROOT . 'img' . DS . 'graphs';
        if (is_dir($path)) {
            $folder = new Folder($path);
            foreach ($folder->find() as $file) {
                if (filemtime($path . DS . $file) < (time() - 120)) {
                    unlink($path . DS . $file);
                }
            }
        }
    }

    public function clenupPiCharts() {
        $path = WWW_ROOT . 'img' . DS . 'charts';
        if (is_dir($path)) {
            $folder = new Folder($path);
            foreach ($folder->find() as $file) {
                if (filemtime($path . DS . $file) < (time() - 120)) {
                    unlink($path . DS . $file);
                }
            }
        }
    }
}
