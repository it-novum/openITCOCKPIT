<?php

class BuildBootstrapTask extends AppShell {
    /**
     * Compiles bootstrap
     * @return void
     */
    public function execute() {
        $this->out('Building Bootstrap from LESS files');
        $bootstrapLessPath = OLD_APP . 'Vendor/bootstrap/custom_bootstrap.less';
        $targetPath = OLD_APP . 'webroot/css/vendor/bootstrap/css/bootstrap.css';
        $minTargetPath = OLD_APP . 'webroot/css/vendor/bootstrap/css/bootstrap.min.css';

        $lesscPath = Configure::read('paths.lessc.' . ENVIRONMENT);

        $command = sprintf('%s %s > %s', $lesscPath, $bootstrapLessPath, $targetPath);

        $out = $err = [];

        $this->_exec($command, $out, $err);

        $commandMin = sprintf('lessc --compress %s > %s', $bootstrapLessPath, $minTargetPath);
        $this->_exec($commandMin, $out, $err);
        $this->out('Done');
    }

    /**
     * Executes a shell command and writes output lines to $stdout and $stderr
     *
     * @param string $cmd
     * @param array $stdout
     * @param array $stderr
     *
     * @return int  Exit code
     */
    protected function _exec($cmd, &$stdout, &$stderr) {
        $outfile = tempnam(".", "cmd");
        $errfile = tempnam(".", "cmd");
        $descriptorspec = [
            0 => ["pipe", "r"],
            1 => ["file", $outfile, "w"],
            2 => ["file", $errfile, "w"],
        ];
        $proc = proc_open($cmd, $descriptorspec, $pipes);

        if (!is_resource($proc)) return 255;

        fclose($pipes[0]);    //Don't really want to give any input

        $exit = proc_close($proc);
        $stdout = file($outfile);
        $stderr = file($errfile);

        #print_r($stdout);
        #print_r($stderr);

        unlink($outfile);
        unlink($errfile);

        return $exit;
    }
}
