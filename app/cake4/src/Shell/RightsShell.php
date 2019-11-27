<?php
// Copyright (C) <2017>  <it-novum GmbH>
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

namespace App\Shell;

use App\Model\Table\SystemsettingsTable;
use Cake\Console\ConsoleOptionParser;
use Cake\Console\Shell;
use Cake\ORM\TableRegistry;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Rights shell command.
 */
class RightsShell extends Shell {
    /**
     * Manage the available sub-commands along with their arguments and help
     *
     * @see http://book.cakephp.org/3.0/en/console-and-shells.html#configuring-options-and-generating-help
     *
     * @return \Cake\Console\ConsoleOptionParser
     */
    public function getOptionParser(): ConsoleOptionParser {
        $parser = parent::getOptionParser();

        return $parser;
    }

    /**
     * main() method.
     *
     * @return bool|int|null Success or error code.
     */
    public function main() {
        $directories = [
            '/usr/share/openitcockpit/',
            '/usr/share/openitcockpit-modules/',
            '/usr/share/openITCOCKPIT-modules/',
        ];
        $this->setRights($directories);
    }

    private function setRights($dirs = []) {
        try {
            /** @var SystemsettingsTable $SystemsettingsTable */
            $SystemsettingsTable = TableRegistry::getTableLocator()->get('Systemsettings');
            $systemsettings = $SystemsettingsTable->findAsArray();
            $user = $systemsettings['WEBSERVER']['WEBSERVER.USER'];
            $group = $systemsettings['WEBSERVER']['WEBSERVER.GROUP'];

            $fs = new Filesystem();
            foreach ($dirs as $dir) {
                if ($fs->exists($dir)) {
                    $this->out(sprintf(
                        '<info>Set filesystem permissions for user %s to %s...    </info>',
                        $user,
                        $dir
                    ), 0);
                    $fs->chown($dir, $user, true);
                    $fs->chgrp($dir, $group, true);
                    $this->out('<success>done!</success>');
                }

            }
        } catch (IOExceptionInterface $e) {
            $this->out('<error>An error occurred at ' . $e->getPath() . ' </error>');
        }
    }
}
