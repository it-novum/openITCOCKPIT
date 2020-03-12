<?php
declare(strict_types=1);

namespace App\Command;

use App\Lib\PluginManager;
use Cake\Console\Arguments;
use Cake\Command\Command;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;
use MatthiasMullie\Minify\JS;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

/**
 * Compress command.
 */
class CompressCommand extends Command {
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
        //angular controllers
        $io->out('Compress Angular controllers...    ', 0);
        $angularControllers = $this->fetchAllAngularControllers();
        $this->compressFiles($angularControllers, 'compressed_angular_controllers.js');
        $this->minifyJsFile('compressed_angular_controllers.js');
        $io->success('done');

        //angular directives
        $io->out('Compress Angular directives...    ', 0);
        $angularDirectives = $this->fetchAllAngularDirectives();
        $this->compressFiles($angularDirectives, 'compressed_angular_directives.js');
        $this->minifyJsFile('compressed_angular_directives.js');
        $io->success(' done');

        //angular services
        $io->out('Compress Angular services...    ', 0);
        $angularServices = $this->fetchAllAngularServices();
        $this->compressFiles($angularServices, 'compressed_angular_services.js');
        $this->minifyJsFile('compressed_angular_services.js');
        $io->success('done');
    }

    public function fetchAllAngularControllers() {
        return $this->fetchAllAngularFiles('controllers');
    }

    public function fetchAllAngularDirectives() {
        return $this->fetchAllAngularFiles('directives');
    }

    public function fetchAllAngularServices() {
        return $this->fetchAllAngularFiles('services');
    }

    private function fetchAllAngularFiles(string $subDir) {
        $Finder = new Finder();
        $Finder
            ->name('*.js')
            ->ignoreDotFiles(true)
            ->files();

        $allFiles = [];

        foreach ($Finder->in(WWW_ROOT . 'js' . DS . 'scripts' . DS . $subDir) as $file) {
            /** @var SplFileInfo $file */
            $path = $file->getPath();
            $filename = $file->getBasename();
            $allFiles[] = $path . DS . $filename;
        }

        foreach (PluginManager::getAvailablePlugins() as $pluginName) {
            $dir = PLUGIN . $pluginName . DS . 'webroot' . DS . 'js' . DS . 'scripts' . DS . $subDir;
            if (!is_dir($dir)) {
                continue;
            }

            $Finder = new Finder();
            $Finder
                ->name('*.js')
                ->ignoreDotFiles(true)
                ->files();

            foreach ($Finder->in($dir) as $file) {
                /** @var SplFileInfo $file */
                $path = $file->getPath();
                $filename = $file->getBasename();
                $allFiles[] = $path . DS . $filename;
            }

        }

        return $allFiles;
    }

    public function compressFiles($files, $outFileName) {
        $outFile = fopen(WWW_ROOT . 'dist' . DS . $outFileName, 'w+');

        foreach ($files as $file) {
            if (file_exists($file)) {
                //Remove strict because of js issue:
                //Uncaught SyntaxError: Octal literals are not allowed in strict mode.
                //Not all JS files are strict compatible
                $content = str_replace(["'use strict';", '"use strict";'], '', file_get_contents($file));
                fwrite($outFile, $content);
                fwrite($outFile, PHP_EOL);
            }
        }

        fclose($outFile);
    }

    public function minifyJsFile($fileName) {
        $minifier = new JS(WWW_ROOT . 'dist' . DS . $fileName);

        $file = fopen(WWW_ROOT . 'dist' . DS . $fileName, 'w+');
        fwrite($file, $minifier->minify());
        fclose($file);
    }


}
