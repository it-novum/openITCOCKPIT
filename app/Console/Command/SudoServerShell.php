<?php
// Copyright (C) <2015>  <it-novum GmbH>
//
// This file is dual licensed
//
// 1.
//	This program is free software: you can redistribute it and/or modify
//	it under the terms of the GNU General Public License as published by
//	the Free Software Foundation, version 3 of the License.
//
//	This program is distributed in the hope that it will be useful,
//	but WITHOUT ANY WARRANTY; without even the implied warranty of
//	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//	GNU General Public License for more details.
//
//	You should have received a copy of the GNU General Public License
//	along with this program.  If not, see <http://www.gnu.org/licenses/>.
//

// 2.
//	If you purchased an openITCOCKPIT Enterprise Edition you can use this file
//	under the terms of the openITCOCKPIT Enterprise Edition license agreement.
//	License agreement and license key will be shipped with the order
//	confirmation.

require APP.'/Vendor/Ratchet/vendor/autoload.php';
use Ratchet\Server\IoServer;
use Ratchet\WebSocket\WsServer;
use Ratchet\Http\HttpServer;

require_once APP.'/Lib/SudoMessageInterface.php';

App::import('Model', 'Export');

//public $tasks = array('WriteConfiguration');

class SudoServerShell extends AppShell
{

    public $uses = [MONITORING_EXTERNALCOMMAND, MONITORING_NAGIOSTAT, 'Systemsetting'];
    //public $tasks = ['NagiosExport'];
    //public $tasks = ['SudoWorker'];

    public function main()
    {
        Configure::load('nagios');

        App::uses('Folder', 'Utility');
        App::uses('File', 'Utility');

        $this->childWorkers = [];

        $this->parser = $this->getOptionParser();
        $this->pidFile = '/var/run/oitc_sudoserver.pid';

        if (array_key_exists('stop', $this->params)) {
            $this->stop();
        }

        if (array_key_exists('probe', $this->params)) {
            $this->probe();
        }

        foreach (['restart', 'try-restart', 'reload', 'force-reload'] as $key) {
            if (array_key_exists($key, $this->params)) {
                $this->restart();

                return;
            }
        }

        if (array_key_exists('status', $this->params)) {
            if ($this->status()) {
                $this->stdout->styles('green', ['text' => 'green']);
                $this->out('<green>SudoWebsocket Server is running</green>');
                exit(0);
            }

            $this->stdout->styles('red', ['text' => 'red']);
            $this->out('<red>SudoWebsocket Server not running</red>');
            exit(3);

        }

        $this->out('Starting SudoWebsocket Server');
        if (array_key_exists('daemon', $this->params)) {
            $this->daemonizing();
        } else {
            $this->stdout->styles('blue', ['text' => 'blue']);
            $this->out('<blue>This is not daemon mode! Exit with [STRG] + [C]</blue>');
            $this->_bootstrap();
        }
    }

    public function _welcome()
    {
        //Disable CakePHP welcome messages
    }

    public function getOptionParser()
    {
        $parser = parent::getOptionParser();
        $parser->addOptions([
            'daemon'       => ['short' => 'd', 'help' => __d('oitc_console', 'Starts SudoServer in daemon mode, instead of as a forground process')],
            'stop'         => ['short' => 'k', 'help' => __d('oitc_console', 'Stops the daemon')],
            'status'       => ['short' => 's', 'help' => __d('oitc_console', 'Resturn the status of the daemon')],
            'probe'        => ['short' => 'p', 'help' => __d('oitc_console', 'Pacemaker likes this, but we dont know why :) (it\'s recommended to use with -q e.g.: sudo_server -p -q)')],
            'restart'      => ['help' => __d('oitc_console', 'Restart the daemon')],
            'try-restart'  => ['help' => __d('oitc_console', 'Restart the daemon')],
            'reload'       => ['help' => __d('oitc_console', 'Restart the daemon')],
            'force-reload' => ['help' => __d('oitc_console', 'Restart the daemon')],
        ]);

        return $parser;
    }

    public function daemonizing()
    {
        $this->_systemCheck();
        if ($this->status()) {
            $this->out('<error>SudoServer already running</error>');
            exit(0);
        }

        $SudoServerPid = pcntl_fork();
        if (!$SudoServerPid) {
            $this->_bootstrap();
            exit(0);
        }


        $pidFile = fopen($this->pidFile, 'w+');
        fwrite($pidFile, $SudoServerPid);
        fclose($pidFile);

        //only root can edit this file
        chmod($this->pidFile, 0000);

        $this->stdout->styles('green', ['text' => 'green']);
        $this->out('<green>Finished daemonizing... [My PID = '.$SudoServerPid.']</green>');
    }

    public function status()
    {
        foreach ($this->_getPid() as $pid) {
            exec('ps -eaf |grep '.escapeshellarg($pid).' |grep -v grep', $output);
            foreach ($output as $line) {
                if (preg_match('#.*app/Console/cake.php -working .*/app sudo_server (-d|--daemon)#', $line)) {
                    return true;
                }
            }
        }
        // The file should not exist at this point, if it exists the SudoServer crashed and we need to cleanup
        if (file_exists($this->pidFile)) {
            unlink($this->pidFile);
        }

        return false;
    }

    private function _getPid()
    {
        $return = [];
        if (file_exists($this->pidFile)) {
            $pids = file($this->pidFile);
            if (sizeof($pids) > 1) {
                $this->out('<warning>More than one pid in my pid file!</warning>');
            }
            foreach ($pids as $pid) {
                $return[] = trim($pid);
            }
        }

        return $return;
    }

    public function stop($exit = true)
    {
        if (!$this->status()) {
            $this->out("<info>Notice: SudoServer isn't running!</info>");
            if ($exit) {
                exit(0);
            }
        }


        try {
            $this->_systemsettings = $this->Systemsetting->findAsArray();
        } catch (Exception $e) {
            //Mysql server issue
            //Set set default values to _systemsettings to to stop the sudo_server
            debug($e->getMessage());
            $this->_systemsettings = [];
            $this->_systemsettings['SUDO_SERVER']['SUDO_SERVER.SOCKET'] = '/usr/share/openitcockpit/app/run/';
            $this->_systemsettings['SUDO_SERVER']['SUDO_SERVER.SOCKET_NAME'] = 'sudo.sock';

        }

        foreach ($this->_getPid() as $pid) {
            posix_kill($pid, SIGTERM);
            pcntl_waitpid($pid, $status);
        }

        if (file_exists($this->pidFile)) {
            unlink($this->pidFile);
        }

        //$this->sudoWorkerSocket = $this->createSocket();
        //$this->stdout->styles('red', ['text' => 'red']);
        //$this->out('<red>Tell my worker child to kill itself</red>');
        //$this->sendToWorkerSocket([
        //	'task'  => 'exit',
        //	'payload'  => 'exit',
        //	'requestor'  => 'exit'
        //]);

        $this->stdout->styles('green', ['text' => 'green']);
        $this->out('<green>SudoServer terminated, astalavista baby...</green>');
        $this->deleteSocket();
        if ($exit) {
            exit(0);
        }
    }

    public function restart()
    {
        if ($this->stop(false)) {
            sleep(1);
            $this->daemonizing();
        }
    }

    /*
     * Pacemaker likes this function, we dont know why :)
     */
    public function probe()
    {
        echo "restart\n";
        exit(0);
    }

    private function _systemCheck()
    {
        if (!function_exists('pcntl_fork')) {
            $this->out('<error>Error: PHP function "pcntl_fork()" not found or is disabled for security reasons. Please check your php.ini</error>');
            exit(3);
        }

        if (!function_exists('exec')) {
            $this->out('<error>Error: PHP function "exec()" not found or is disabled for security reasons. Please check your php.ini</error>');
            exit(3);
        }

        if (!function_exists('pcntl_waitpid')) {
            $this->out('<error>Error: PHP function "pcntl_waitpid()" not found or is disabled for security reasons. Please check your php.ini</error>');
            exit(3);
        }

        if (!function_exists('posix_kill')) {
            $this->out('<error>Error: PHP function "posix_kill()" not found or is disabled for security reasons. Please check your php.ini</error>');
            exit(3);
        }

    }

    private function _bootstrap()
    {

        $this->_systemsettings = $this->Systemsetting->findAsArray();

        $this->socket = $this->createSocket();
        $this->bindSocket();


        //Child handling
        //$this->stdout->styles('blue', ['text' => 'blue']);
        //$this->stdout->styles('green', ['text' => 'green']);
        //$this->out('<blue>Fork new sudo_server worker process</blue>');
        //$childWorker = pcntl_fork();
        //if(!$childWorker){
        //	$this->out('<green>Hello I am the sudo_server worker process my pid is '.getmypid().'</green>');
        //	//I am the sudo server worker child
        //	$this->SudoWorker->work();
        //	exit(0);
        //}
        //
        ////Im the parrent sudo server process
        ////Seelp one second thet your child can create its unix socket!
        //sleep(1);
        //$this->sudoWorkerSocket = $this->createSocket();
        //pcntl_signal(SIGCHLD, array($this, 'sigchld_handler'));


        $SudoInterface = new SudoMessageInterface($this);
        $loop = React\EventLoop\Factory::create();
        $loop->addPeriodicTimer(0.01, [$SudoInterface, 'eventLoop']);

        $webSock = new React\Socket\Server($loop);
        $webSock->listen(8081, '0.0.0.0');

        $webServer = new Ratchet\Server\IoServer(
            new Ratchet\Http\HttpServer(
                new Ratchet\WebSocket\WsServer(
                    $SudoInterface
                )
            ),
            $webSock
        );
        try {
            $loop->run();
        } catch (Exception $e) {
            debug($e);
        }
    }

    public function createSocket()
    {
        return socket_create(AF_UNIX, SOCK_DGRAM, 0);
    }

    public function bindSocket()
    {
        if (!is_dir($this->_systemsettings['SUDO_SERVER']['SUDO_SERVER.SOCKET'])) {
            mkdir($this->_systemsettings['SUDO_SERVER']['SUDO_SERVER.SOCKET']);
        }

        $this->setFolderPermissions();

        $this->deleteSocket();

        socket_bind($this->socket, $this->_systemsettings['SUDO_SERVER']['SUDO_SERVER.SOCKET'].$this->_systemsettings['SUDO_SERVER']['SUDO_SERVER.SOCKET_NAME']);
        if (file_exists($this->_systemsettings['SUDO_SERVER']['SUDO_SERVER.SOCKET'].$this->_systemsettings['SUDO_SERVER']['SUDO_SERVER.SOCKET_NAME'])) {
            $this->setFilePermissions();

            return true;
        } else {
            return false;
        }
    }

    function deleteSocket()
    {
        if (file_exists($this->_systemsettings['SUDO_SERVER']['SUDO_SERVER.SOCKET'].$this->_systemsettings['SUDO_SERVER']['SUDO_SERVER.SOCKET_NAME'])) {
            unlink($this->_systemsettings['SUDO_SERVER']['SUDO_SERVER.SOCKET'].$this->_systemsettings['SUDO_SERVER']['SUDO_SERVER.SOCKET_NAME']);
        }
    }

    public function setFolderPermissions()
    {
        chown($this->_systemsettings['SUDO_SERVER']['SUDO_SERVER.SOCKET'], $this->_systemsettings['WEBSERVER']['WEBSERVER.USER']);
        chgrp($this->_systemsettings['SUDO_SERVER']['SUDO_SERVER.SOCKET'], $this->_systemsettings['MONITORING']['MONITORING.GROUP']);
        chmod($this->_systemsettings['SUDO_SERVER']['SUDO_SERVER.SOCKET'], $this->_systemsettings['SUDO_SERVER']['SUDO_SERVER.FOLDERPERMISSIONS']);
    }

    public function setFilePermissions()
    {
        chown($this->_systemsettings['SUDO_SERVER']['SUDO_SERVER.SOCKET'].$this->_systemsettings['SUDO_SERVER']['SUDO_SERVER.SOCKET_NAME'], $this->_systemsettings['WEBSERVER']['WEBSERVER.USER']);
        chgrp($this->_systemsettings['SUDO_SERVER']['SUDO_SERVER.SOCKET'].$this->_systemsettings['SUDO_SERVER']['SUDO_SERVER.SOCKET_NAME'], $this->_systemsettings['MONITORING']['MONITORING.GROUP']);
        chmod($this->_systemsettings['SUDO_SERVER']['SUDO_SERVER.SOCKET'].$this->_systemsettings['SUDO_SERVER']['SUDO_SERVER.SOCKET_NAME'], $this->_systemsettings['SUDO_SERVER']['SUDO_SERVER.SOCKETPERMISSIONS']);
    }

    public function sendToWorkerSocket($data = [])
    {
        /*$data = [
            'task' => $task,
            //'sourceTask' => $task,
            'payload' => $payload,
            'key' => $this->_systemsettings['SUDO_SERVER']['SUDO_SERVER.API_KEY'],
            'requestor' => $this->requestor,
        ];*/
        $data = json_encode($data);
        if (!socket_sendto($this->sudoWorkerSocket, $data, strlen($data), 0, $this->_systemsettings['SUDO_SERVER']['SUDO_SERVER.SOCKET'].$this->_systemsettings['SUDO_SERVER']['SUDO_SERVER.WORKERSOCKET_NAME'])) {
            $this->out(__('Could not connect to UNIX socket ').$this->_systemsettings['SUDO_SERVER']['SUDO_SERVER.SOCKET'].$this->_systemsettings['SUDO_SERVER']['SUDO_SERVER.WORKERSOCKET_NAME']);
        }
    }

    public function sendToResponseSocket($data = [])
    {
        $socket = socket_create(AF_UNIX, SOCK_DGRAM, 0);
        $data = json_encode($data);
        if (!socket_sendto($socket, $data, strlen($data), 0, $this->_systemsettings['SUDO_SERVER']['SUDO_SERVER.SOCKET'].$this->_systemsettings['SUDO_SERVER']['SUDO_SERVER.RESPONSESOCKET_NAME'])) {
            $this->out(__('Could not connect to UNIX socket ').$this->_systemsettings['SUDO_SERVER']['SUDO_SERVER.SOCKET'].$this->_systemsettings['SUDO_SERVER']['SUDO_SERVER.RESPONSESOCKET_NAME']);
        }
        unset($socket);
    }

    function sigchld_handler($signal)
    {
        //Get the dead child pid and clean up the zombie process
        $dead_child_pid = pcntl_wait($status, WNOHANG);
        // Dieser Child muss neu erstellt werden!
        if ($dead_child_pid > 0) {
            $this->out('my worker child died. I miss it already :( !');
        }
        pcntl_signal(SIGCHLD, [$this, 'sigchld_handler']);
    }

}
