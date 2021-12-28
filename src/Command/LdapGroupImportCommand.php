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

use App\Model\Entity\Ldapgroup;
use App\Model\Table\LdapgroupsTable;
use App\Model\Table\SystemsettingsTable;
use Cake\Console\Arguments;
use Cake\Console\Command;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;
use Cake\ORM\TableRegistry;
use itnovum\openITCOCKPIT\Core\Interfaces\CronjobInterface;
use itnovum\openITCOCKPIT\Ldap\LdapClient;

/**
 * LdapGroupImport command.
 */
class LdapGroupImportCommand extends Command implements CronjobInterface {

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
        /** @var SystemsettingsTable $SystemsettingsTable */
        $SystemsettingsTable = TableRegistry::getTableLocator()->get('Systemsettings');

        if ($SystemsettingsTable->isLdapAuth() === false) {
            // No LDAP no LDAP sync :)
            return;
        }

        $io->out('Scan for new LDAP groups. This will take a while...');

        /** @var LdapgroupsTable $LdapgroupsTable */
        $LdapgroupsTable = TableRegistry::getTableLocator()->get('Ldapgroups');

        $LdapClient = LdapClient::fromSystemsettings($SystemsettingsTable->findAsArraySection('FRONTEND'));

        $ldapGroupsFromDb = $LdapgroupsTable->getGroupsForSync();
        $ldapGroupsFromLdap = $LdapClient->getGroups();

        // Check which groups only exists in our database but not in LDAP anymore and needs to be removed
        $ldapGroupsFromLdapHash = [];
        foreach ($ldapGroupsFromLdap as $ldapGroup) {
            $ldapGroupsFromLdapHash[$ldapGroup['dn']] = $ldapGroup;
        }

        $removed = 0;
        $ldapGroupsToRemove = [];
        foreach ($ldapGroupsFromDb as $dn => $ldapGroupFromDb) {
            /** @var Ldapgroup $ldapGroup */
            if (!isset($ldapGroupsFromLdapHash[$ldapGroupFromDb->dn])) {
                // This group was removed from LDAP
                $ldapGroupsToRemove[] = $ldapGroupFromDb;
                $removed++;
            }
        }


        $LdapgroupsTable->deleteMany($ldapGroupsToRemove);
        foreach ($ldapGroupsToRemove as $groupToRemove) {
            $io->out(sprintf('Deleted LDAP group: <warning>%s</warning> from database', $groupToRemove->dn));
        }


        // Add new LDAP Groups to database
        $created = 0;
        foreach ($ldapGroupsFromLdap as $ldapGroup) {
            if (!isset($ldapGroupsFromDb[$ldapGroup['dn']])) {
                // This LDAP group does not exists in database
                $entity = $LdapgroupsTable->newEntity([
                    'cn'          => $ldapGroup['cn'],
                    'dn'          => $ldapGroup['dn'],
                    'description' => $ldapGroup['description']
                ]);

                if ($entity->hasErrors() === false) {
                    $LdapgroupsTable->save($entity);
                    $created++;
                    $io->out(sprintf('Created LDAP group: <success>%s</success>', $ldapGroup['dn']));
                }
            }
        }

        $io->out(sprintf('Imported %s groups, removed %s groups from database.', $created, $removed));

        $io->success('   Ok');
        $io->hr();
    }
}
