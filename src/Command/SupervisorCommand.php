<?php
// Copyright (C) <2015-present>  <it-novum GmbH>
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

declare(strict_types=1);

namespace App\Command;

use App\itnovum\openITCOCKPIT\Supervisor\XMLRPCApi;
use Cake\Command\Command;
use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;

/**
 * Supervisor command.
 */
class SupervisorCommand extends Command {
    /**
     * Hook method for defining this command's option parser.
     *
     * @see https://book.cakephp.org/4/en/console-commands/commands.html#defining-arguments-and-options
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

        $SupervisorApi = new XMLRPCApi('supervisord', 'password', 'http://127.0.0.1:9001/RPC2');

        try {
            debug($SupervisorApi->getApiVersion());
            debug($SupervisorApi->getSupervisorVersion());
            debug($SupervisorApi->getIdentification());
            debug($SupervisorApi->getState());
            debug($SupervisorApi->getPID());
            debug($SupervisorApi->readLog());
            debug($SupervisorApi->listMethods());
            debug($SupervisorApi->methodHelp('supervisor.stopAllProcesses'));
            debug($SupervisorApi->methodSignature('supervisor.stopAllProcesses'));
            debug($SupervisorApi->getProcessInfo('sudo_server'));
            debug($SupervisorApi->getAllProcessInfo());
        }catch (\Exception $e){
            debug($e->getMessage());
        }

    }
}
