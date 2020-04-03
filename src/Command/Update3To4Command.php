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

use App\Lib\Interfaces\NotificationHostsTableInterface;
use App\Model\Table\SystemsettingsTable;
use App\Model\Table\UsersTable;
use Cake\Console\Arguments;
use Cake\Command\Command;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;
use Cake\Database\Connection;
use Cake\Datasource\ConnectionManager;
use Cake\Mailer\Mailer;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;
use itnovum\openITCOCKPIT\Core\Views\Logo;
use itnovum\openITCOCKPIT\SetupShell\MailConfigurator;
use itnovum\openITCOCKPIT\SetupShell\MailConfigValue;
use itnovum\openITCOCKPIT\SetupShell\MailConfigValueInt;
use ProgressBar\Manager;

/**
 * Update3To4 command.
 */
class Update3To4Command extends Command {

    /**
     * @var int
     */
    private $limit = 200;

    /**
     * @var ConsoleIo
     */
    private $io;

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
        $parser->addOption('email-config', [
            'help'    => __('Migrate openITCOCKPIT V3 E-Mail configuration'),
            'boolean' => true,
            'default' => false
        ]);

        $parser->addOption('activate-users', [
            'help'    => __('Enable users from the file /root/.openitcockpit_active_user_migration.json'),
            'boolean' => true,
            'default' => false
        ]);

        $parser->addOption('reset-all-passwords', [
            'help'    => __('Send a new random password to all local users per mail.'),
            'boolean' => true,
            'default' => false
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
        $this->io = $io;

        if ($args->getOption('email-config') === true) {
            $this->migrateEmailConfiguration();
        }

        if ($args->getOption('activate-users') === true) {
            $io->out('Active users...    ', 0);
            $this->setActiveUsersFromJson();
            $io->success('done');
        }

        if ($args->getOption('reset-all-passwords') === true) {
            $io->out('Reset users passwords...    ');
            $this->resetAllUserPasswords();
            $io->success('done');
        }

        //Set timezone to UTC to migrate between mysql DATETIME and php timestamp strtotime and so on...
        date_default_timezone_set('UTC');
        //$this->migrateHostNotifications();
        //$this->migrateServiceNotifications();
        //$this->migrateHostStatehistory();
        //$this->migrateServiceStatehistory();
        $this->migrateHostAcknowledgements();
        $this->migrateServiceAcknowledgements();
    }

    public function migrateEmailConfiguration() {
        if (!file_exists('/etc/openitcockpit/app/Config/email.php')) {
            $this->io->error('File /etc/openitcockpit/app/Config/email.php not found.');
            return;
        }

        require_once '/etc/openitcockpit/app/Config/email.php';
        $OldEmailConfig = new \EmailConfig();


        $mailHost = new MailConfigValue($OldEmailConfig->default['host']);
        $mailPort = new MailConfigValueInt((int)$OldEmailConfig->default['port']);
        $mailUsername = new MailConfigValue($OldEmailConfig->default['username']);
        $mailPassword = new MailConfigValue($OldEmailConfig->default['password']);
        $mailConfigurator = new MailConfigurator(
            $mailHost, $mailPort, $mailUsername, $mailPassword
        );

        $file = fopen(CONFIG . 'email.php', 'w+');
        fwrite($file, $mailConfigurator->getConfig());
        fclose($file);
        $this->io->success(__('Mail configuration "{0}" saved successfully.', CONFIG . 'email.php'));
    }

    public function setActiveUsersFromJson() {
        $filename = '/root/.openitcockpit_active_user_migration.json';
        if (!file_exists($filename)) {
            return false;
        }

        $users = json_decode(file_get_contents($filename), true);

        /** @var UsersTable $UsersTable */
        $UsersTable = TableRegistry::getTableLocator()->get('Users');

        foreach ($users as $user) {
            if ($UsersTable->existsById($user['id'])) {
                $userEntity = $UsersTable->get($user['id']);
                $userEntity->set('is_active', 1);
                $UsersTable->save($userEntity);
            }
        }

        //Remove json file
        unlink($filename);
    }

    public function resetAllUserPasswords() {
        /** @var UsersTable $UsersTable */
        $UsersTable = TableRegistry::getTableLocator()->get('Users');
        /** @var SystemsettingsTable $SystemsettingsTable */
        $SystemsettingsTable = TableRegistry::getTableLocator()->get('Systemsettings');

        $systemsettings = $SystemsettingsTable->findAsArray();

        $users = $UsersTable->find()
            ->where(['password !=' => ''])
            ->disableHydration()
            ->all();

        $users = $users->toArray();

        foreach ($users as $userArray) {
            $user = $UsersTable->get($userArray['id']);
            $newPassword = $UsersTable->generatePassword();

            $user->set('password', $newPassword);

            $Logo = new Logo();

            $Mailer = new Mailer();
            $Mailer->setFrom($systemsettings['MONITORING']['MONITORING.FROM_ADDRESS'], $systemsettings['MONITORING']['MONITORING.FROM_NAME']);
            $Mailer->addTo($user->get('email'));
            $Mailer->setSubject(__('Your ') . $systemsettings['FRONTEND']['FRONTEND.SYSTEMNAME'] . __(' got reset!'));
            $Mailer->setEmailFormat('text');
            $Mailer->setAttachments([
                'logo.png' => [
                    'file'      => $Logo->getSmallLogoDiskPath(),
                    'mimetype'  => 'image/png',
                    'contentId' => '100'
                ]
            ]);
            $Mailer->viewBuilder()
                ->setTemplate('reset_password')
                ->setVar('systemname', $systemsettings['FRONTEND']['FRONTEND.SYSTEMNAME'])
                ->setVar('newPassword', $newPassword);

            $user->set('password', $newPassword);

            $UsersTable->save($user);
            if ($user->hasErrors()) {
                $this->io->error('Could not reset password for user: ' . $user->email);
                continue;
            }

            //$Mailer->deliver();
            $this->io->success(__('New password was send to {0}', $user->email));
        }
    }


    public function migrateHostNotifications() {
        $this->migrateParitions('nagios_notifications', 'statusengine_host_notifications');

        $hostNotificationsCount = $this->getHostNotificationsMigrationQuery(0, true);
        $numberOfSelects = ceil($hostNotificationsCount / $this->limit);

        if ($hostNotificationsCount == 0) {
            return;
        }

        $this->io->out('Migrating host notifications');
        $ProgressBar = new Manager(0, $numberOfSelects);

        $query = "
      INSERT IGNORE INTO statusengine_host_notifications
      (hostname, contact_name, command_name, state, start_time, start_time_usec, end_time, reason_type, output )
      VALUES%s";

        $baseValues = '(?,?,?,?,?,?,?,?,?)';
        for ($i = 0; $i < $numberOfSelects; $i++) {
            $offset = $this->limit * $i;

            $values = [];
            $params = [];
            foreach ($this->getHostNotificationsMigrationQuery($offset) as $record) {
                $values[] = $baseValues;
                $params[] = $record['Hosts']['uuid'];
                $params[] = $record['Contacts']['uuid'];
                $params[] = $record['Commands']['uuid'];
                $params[] = $record['state'];
                $params[] = strtotime($record['start_time']);
                $params[] = $this->getMicrotime();
                $params[] = strtotime($record['end_time']);
                $params[] = $record['notification_reason'];
                $params[] = $record['output'];
            }

            $sql = sprintf($query, implode(',', $values));
            $connection = ConnectionManager::get('default');

            $statement = $connection->execute(
                $sql,
                $params
            );

            $ProgressBar->update(($i + 1));
        }
        $this->io->out('');
    }

    public function migrateServiceNotifications() {
        $this->migrateParitions('nagios_notifications', 'statusengine_service_notifications');


        $serviceNotificationsCount = $this->getServiceNotificationsMigrationQuery(0, true);
        $numberOfSelects = ceil($serviceNotificationsCount / $this->limit);

        if ($serviceNotificationsCount == 0) {
            return;
        }

        $this->io->out('Migrating service notifications');
        $ProgressBar = new Manager(0, $numberOfSelects);

        $query = "
      INSERT IGNORE INTO statusengine_service_notifications
      (hostname, service_description, contact_name, command_name, state, start_time, start_time_usec, end_time, reason_type, output )
      VALUES%s";

        $baseValues = '(?,?,?,?,?,?,?,?,?,?)';
        for ($i = 0; $i < $numberOfSelects; $i++) {
            $offset = $this->limit * $i;

            $values = [];
            $params = [];
            foreach ($this->getServiceNotificationsMigrationQuery($offset) as $record) {
                $values[] = $baseValues;
                $params[] = $record['Hosts']['uuid'];
                $params[] = $record['Services']['uuid'];
                $params[] = $record['Contacts']['uuid'];
                $params[] = $record['Commands']['uuid'];
                $params[] = $record['state'];
                $params[] = strtotime($record['start_time']);
                $params[] = $this->getMicrotime();
                $params[] = strtotime($record['end_time']);
                $params[] = $record['notification_reason'];
                $params[] = $record['output'];
            }

            $sql = sprintf($query, implode(',', $values));
            $connection = ConnectionManager::get('default');

            $statement = $connection->execute(
                $sql,
                $params
            );

            $ProgressBar->update(($i + 1));
        }
        $this->io->out('');
    }

    public function migrateHostStatehistory() {
        $this->migrateParitions('nagios_statehistory', 'statusengine_host_statehistory');


        $hostStatehistoryCount = $this->getHostStatehistoryMigrationQuery(0, true);
        $numberOfSelects = ceil($hostStatehistoryCount / $this->limit);

        if ($hostStatehistoryCount == 0) {
            return;
        }

        $this->io->out('Migrating host statehistory');
        $ProgressBar = new Manager(0, $numberOfSelects);

        $query = "
        INSERT IGNORE INTO statusengine_host_statehistory
        (hostname, state_time, state_time_usec, state, state_change, is_hardstate, current_check_attempt, max_check_attempts, last_state, last_hard_state, output, long_output)
        VALUES%s";

        $baseValues = '(?,?,?,?,?,?,?,?,?,?,?,?)';
        for ($i = 0; $i < $numberOfSelects; $i++) {
            $offset = $this->limit * $i;

            $values = [];
            $params = [];
            foreach ($this->getHostStatehistoryMigrationQuery($offset) as $record) {
                $values[] = $baseValues;

                $params[] = $record['Objects']['name1'];
                $params[] = strtotime($record['state_time']);
                $params[] = $this->getMicrotime();
                $params[] = $record['state'];
                $params[] = $record['state_change'];
                $params[] = $record['state_type'];
                $params[] = $record['current_check_attempt'];
                $params[] = $record['max_check_attempts'];
                $params[] = $record['last_state'];
                $params[] = $record['last_hard_state'];
                $params[] = $record['output'];
                $params[] = $record['long_output'];
            }

            $sql = sprintf($query, implode(',', $values));
            $connection = ConnectionManager::get('default');

            $statement = $connection->execute(
                $sql,
                $params
            );

            $ProgressBar->update(($i + 1));
        }
        $this->io->out('');
    }

    public function migrateServiceStatehistory() {
        $this->migrateParitions('nagios_statehistory', 'statusengine_service_statehistory');


        $serviceStatehistoryCount = $this->getServiceStatehistoryMigrationQuery(0, true);
        $numberOfSelects = ceil($serviceStatehistoryCount / $this->limit);

        if ($serviceStatehistoryCount == 0) {
            return;
        }

        $this->io->out('Migrating service statehistory');
        $ProgressBar = new Manager(0, $numberOfSelects);

        $query = "
        INSERT IGNORE INTO statusengine_service_statehistory
        (hostname, service_description, state_time, state_time_usec, state, state_change, is_hardstate, current_check_attempt, max_check_attempts, last_state, last_hard_state, output, long_output)
        VALUES%s";

        $baseValues = '(?,?,?,?,?,?,?,?,?,?,?,?,?)';
        for ($i = 0; $i < $numberOfSelects; $i++) {
            $offset = $this->limit * $i;

            $values = [];
            $params = [];
            foreach ($this->getServiceStatehistoryMigrationQuery($offset) as $record) {
                $values[] = $baseValues;

                $params[] = $record['Objects']['name1'];
                $params[] = $record['Objects']['name2'];
                $params[] = strtotime($record['state_time']);
                $params[] = $this->getMicrotime();
                $params[] = $record['state'];
                $params[] = $record['state_change'];
                $params[] = $record['state_type'];
                $params[] = $record['current_check_attempt'];
                $params[] = $record['max_check_attempts'];
                $params[] = $record['last_state'];
                $params[] = $record['last_hard_state'];
                $params[] = $record['output'];
                $params[] = $record['long_output'];
            }

            $sql = sprintf($query, implode(',', $values));
            $connection = ConnectionManager::get('default');

            $statement = $connection->execute(
                $sql,
                $params
            );

            $ProgressBar->update(($i + 1));
        }
        $this->io->out('');
    }

    public function migrateHostAcknowledgements() {
        $hostAcknowledgementsCount = $this->getHostAcknowledgementsMigrationQuery(0, true);
        $numberOfSelects = ceil($hostAcknowledgementsCount / $this->limit);

        if ($hostAcknowledgementsCount == 0) {
            return;
        }

        $this->io->out('Migrating host acknowledgements');
        $ProgressBar = new Manager(0, $numberOfSelects);

        $query = "
        INSERT IGNORE INTO statusengine_host_acknowledgements
        (hostname, state, author_name, comment_data, entry_time, entry_time_usec, acknowledgement_type, is_sticky, persistent_comment, notify_contacts)
        VALUES%s;";

        $baseValues = '(?,?,?,?,?,?,?,?,?,?)';
        for ($i = 0; $i < $numberOfSelects; $i++) {
            $offset = $this->limit * $i;

            $values = [];
            $params = [];
            foreach ($this->getHostAcknowledgementsMigrationQuery($offset) as $record) {
                $values[] = $baseValues;

                $params[] = $record['Objects']['name1'];
                $params[] = $record['state'];
                $params[] = $record['author_name'];
                $params[] = $record['comment_data'];
                $params[] = strtotime($record['entry_time']);
                $params[] = $this->getMicrotime();
                $params[] = $record['acknowledgement_type'];
                $params[] = $record['is_sticky'];
                $params[] = $record['persistent_comment'];
                $params[] = $record['notify_contacts'];
            }

            $sql = sprintf($query, implode(',', $values));
            $connection = ConnectionManager::get('default');

            $statement = $connection->execute(
                $sql,
                $params
            );

            $ProgressBar->update(($i + 1));
        }
        $this->io->out('');
    }

    public function migrateServiceAcknowledgements() {
        $serviceAcknowledgementsCount = $this->getServiceAcknowledgementsMigrationQuery(0, true);
        $numberOfSelects = ceil($serviceAcknowledgementsCount / $this->limit);

        if ($serviceAcknowledgementsCount == 0) {
            return;
        }

        $this->io->out('Migrating service acknowledgements');
        $ProgressBar = new Manager(0, $numberOfSelects);

        $query = "
        INSERT INTO statusengine_service_acknowledgements
        (hostname, service_description, state, author_name, comment_data, entry_time, entry_time_usec, acknowledgement_type, is_sticky, persistent_comment, notify_contacts)
        VALUES%s;";

        $baseValues = '(?,?,?,?,?,?,?,?,?,?,?)';
        for ($i = 0; $i < $numberOfSelects; $i++) {
            $offset = $this->limit * $i;

            $values = [];
            $params = [];
            foreach ($this->getServiceAcknowledgementsMigrationQuery($offset) as $record) {
                $values[] = $baseValues;

                $params[] = $record['Objects']['name1'];
                $params[] = $record['Objects']['name2'];
                $params[] = $record['state'];
                $params[] = $record['author_name'];
                $params[] = $record['comment_data'];
                $params[] = strtotime($record['entry_time']);
                $params[] = $this->getMicrotime();
                $params[] = $record['acknowledgement_type'];
                $params[] = $record['is_sticky'];
                $params[] = $record['persistent_comment'];
                $params[] = $record['notify_contacts'];
            }

            $sql = sprintf($query, implode(',', $values));
            $connection = ConnectionManager::get('default');

            $statement = $connection->execute(
                $sql,
                $params
            );

            $ProgressBar->update(($i + 1));
        }
        $this->io->out('');
    }

    /**
     * @param int $offset
     * @return mixed
     */
    private function getHostNotificationsMigrationQuery(int $offset = 0, bool $asCount = false) {
        /** @var \Statusengine2Module\Model\Table\NotificationHostsTable $NotificationHostsTable */
        $NotificationHostsTable = TableRegistry::getTableLocator()->get('Statusengine2Module.NotificationHosts');

        $query = $NotificationHostsTable->find()
            ->select([
                'NotificationHosts.object_id',
                'NotificationHosts.notification_type',
                'NotificationHosts.start_time',
                'NotificationHosts.end_time',
                'NotificationHosts.state',
                'NotificationHosts.output',
                'NotificationHosts.notification_reason',


                'Hosts.id',
                'Hosts.uuid',
                'Hosts.name',

                'Contactnotifications.notification_id',
                'Contactnotifications.contact_object_id',
                'Contactnotifications.start_time',

                'Contacts.id',
                'Contacts.uuid',
                'Contacts.name',

                'Commands.id',
                'Commands.uuid',
                'Commands.name',
            ])
            ->innerJoin(
                ['Objects' => 'nagios_objects'],
                ['Objects.object_id = NotificationHosts.object_id']
            )
            ->innerJoin(
                ['Hosts' => 'hosts'],
                ['Objects.name1 = Hosts.uuid']
            )
            ->innerJoin(
                ['Contactnotifications' => 'nagios_contactnotifications'],
                ['NotificationHosts.notification_id = Contactnotifications.notification_id']
            )
            ->innerJoin(
                ['ContactObjects' => 'nagios_objects'],
                ['Contactnotifications.contact_object_id = ContactObjects.object_id']
            )
            ->innerJoin(
                ['Contacts' => 'contacts'],
                ['ContactObjects.name1 = Contacts.uuid']
            )
            ->innerJoin(
                ['Contactnotificationmethods' => 'nagios_contactnotificationmethods'],
                ['Contactnotificationmethods.contactnotification_id = Contactnotifications.contactnotification_id']
            )
            ->innerJoin(
                ['CommandObjects' => 'nagios_objects'],
                ['Contactnotificationmethods.command_object_id = CommandObjects.object_id']
            )
            ->innerJoin(
                ['Commands' => 'commands'],
                ['CommandObjects.name1 = Commands.uuid']
            )
            ->where([
                'NotificationHosts.notification_type'   => 0,
                'NotificationHosts.contacts_notified >' => 0
            ])
            ->group(['Contactnotifications.contactnotification_id']);

        if ($asCount === true) {
            return $query->count();
        }

        $query->offset($offset);
        $query->limit($this->limit);
        $query->disableHydration();
        $query->disableResultsCasting();
        return $query->toArray();
    }

    private function getServiceNotificationsMigrationQuery(int $offset = 0, bool $asCount = false) {
        /** @var \Statusengine2Module\Model\Table\NotificationServicesTable $NotificationServicesTable */
        $NotificationServicesTable = TableRegistry::getTableLocator()->get('Statusengine2Module.NotificationServices');

        $query = $NotificationServicesTable->find();
        $query->select([
            'NotificationServices.object_id',
            'NotificationServices.notification_type',
            'NotificationServices.start_time',
            'NotificationServices.end_time',
            'NotificationServices.state',
            'NotificationServices.output',
            'NotificationServices.notification_reason',

            'Hosts.id',
            'Hosts.uuid',
            'Hosts.name',

            'Services.id',
            'Services.uuid',
            'Services.name',

            'Servicetemplates.id',
            'Servicetemplates.uuid',
            'Servicetemplates.name',

            'Contactnotifications.notification_id',
            'Contactnotifications.contact_object_id',
            'Contactnotifications.start_time',

            'Contacts.id',
            'Contacts.uuid',
            'Contacts.name',

            'Commands.id',
            'Commands.uuid',
            'Commands.name',
        ])
            ->innerJoin(
                ['Objects' => 'nagios_objects'],
                ['Objects.object_id = NotificationServices.object_id']
            )
            ->innerJoin(
                ['Hosts' => 'hosts'],
                ['Objects.name1 = Hosts.uuid']
            )
            ->innerJoin(
                ['Services' => 'services'],
                ['Objects.name2 = Services.uuid']
            )
            ->innerJoin(
                ['Servicetemplates' => 'servicetemplates'],
                ['Services.servicetemplate_id = Servicetemplates.id']
            )
            ->innerJoin(
                ['Contactnotifications' => 'nagios_contactnotifications'],
                ['NotificationServices.notification_id = Contactnotifications.notification_id']
            )
            ->innerJoin(
                ['ContactObjects' => 'nagios_objects'],
                ['Contactnotifications.contact_object_id = ContactObjects.object_id']
            )
            ->innerJoin(
                ['Contacts' => 'contacts'],
                ['ContactObjects.name1 = Contacts.uuid']
            )
            ->innerJoin(
                ['Contactnotificationmethods' => 'nagios_contactnotificationmethods'],
                ['Contactnotificationmethods.contactnotification_id = Contactnotifications.contactnotification_id']
            )
            ->innerJoin(
                ['CommandObjects' => 'nagios_objects'],
                ['Contactnotificationmethods.command_object_id = CommandObjects.object_id']
            )
            ->innerJoin(
                ['Commands' => 'commands'],
                ['CommandObjects.name1 = Commands.uuid']
            )
            ->where([
                'NotificationServices.notification_type'   => 1,
                'NotificationServices.contacts_notified >' => 0
            ])
            ->group(['Contactnotifications.contactnotification_id']);

        if ($asCount === true) {
            return $query->count();
        }

        $query->offset($offset);
        $query->limit($this->limit);
        $query->disableHydration();
        $query->disableResultsCasting();
        return $query->toArray();
    }

    private function getHostStatehistoryMigrationQuery(int $offset = 0, bool $asCount = false) {
        /** @var \Statusengine2Module\Model\Table\StatehistoryHostsTable $StatehistoryHostsTable */
        $StatehistoryHostsTable = TableRegistry::getTableLocator()->get('Statusengine2Module.StatehistoryHosts');

        $query = $StatehistoryHostsTable->find();
        $query
            ->select([
                'StatehistoryHosts.state_time',
                'StatehistoryHosts.state',
                'StatehistoryHosts.state_change',
                'StatehistoryHosts.state_type',
                'StatehistoryHosts.current_check_attempt',
                'StatehistoryHosts.max_check_attempts',
                'StatehistoryHosts.last_state',
                'StatehistoryHosts.last_hard_state',
                'StatehistoryHosts.output',
                'StatehistoryHosts.long_output',

                'Objects.name1'
            ])
            ->innerJoin(
                ['Objects' => 'nagios_objects'],
                ['Objects.object_id = StatehistoryHosts.object_id']
            )
            ->where([
                'Objects.objecttype_id' => 1
            ]);

        if ($asCount === true) {
            return $query->count();
        }

        $query->offset($offset);
        $query->limit($this->limit);
        $query->disableHydration();
        $query->disableResultsCasting();
        return $query->toArray();
    }

    private function getServiceStatehistoryMigrationQuery(int $offset = 0, bool $asCount = false) {
        /** @var \Statusengine2Module\Model\Table\StatehistoryServicesTable $StatehistoryServicesTable */
        $StatehistoryServicesTable = TableRegistry::getTableLocator()->get('Statusengine2Module.StatehistoryServices');

        $query = $StatehistoryServicesTable->find();
        $query
            ->select([
                'StatehistoryServices.state_time',
                'StatehistoryServices.state',
                'StatehistoryServices.state_change',
                'StatehistoryServices.state_type',
                'StatehistoryServices.current_check_attempt',
                'StatehistoryServices.max_check_attempts',
                'StatehistoryServices.last_state',
                'StatehistoryServices.last_hard_state',
                'StatehistoryServices.output',
                'StatehistoryServices.long_output',

                'Objects.name1',
                'Objects.name2'
            ])
            ->innerJoin(
                ['Objects' => 'nagios_objects'],
                ['Objects.object_id = StatehistoryServices.object_id']
            )
            ->where([
                'Objects.objecttype_id' => 2
            ]);

        if ($asCount === true) {
            return $query->count();
        }

        $query->offset($offset);
        $query->limit($this->limit);
        $query->disableHydration();
        $query->disableResultsCasting();
        return $query->toArray();
    }

    private function getHostAcknowledgementsMigrationQuery(int $offset = 0, bool $asCount = false) {
        /** @var \Statusengine2Module\Model\Table\AcknowledgementHostsTable $AcknowledgementHostsTable */
        $AcknowledgementHostsTable = TableRegistry::getTableLocator()->get('Statusengine2Module.AcknowledgementHosts');


        $query = $AcknowledgementHostsTable->find();

        $query
            ->select([
                'AcknowledgementHosts.state',
                'AcknowledgementHosts.author_name',
                'AcknowledgementHosts.comment_data',
                'AcknowledgementHosts.entry_time',
                'AcknowledgementHosts.acknowledgement_type',
                'AcknowledgementHosts.is_sticky',
                'AcknowledgementHosts.persistent_comment',
                'AcknowledgementHosts.notify_contacts',

                'Objects.name1',
            ])
            ->innerJoin(
                ['Objects' => 'nagios_objects'],
                ['Objects.object_id = AcknowledgementHosts.object_id']
            )
            ->where([
                'Objects.objecttype_id' => 1
            ]);

        if ($asCount === true) {
            return $query->count();
        }

        $query->offset($offset);
        $query->limit($this->limit);
        $query->disableHydration();
        $query->disableResultsCasting();
        return $query->toArray();
    }

    private function getServiceAcknowledgementsMigrationQuery(int $offset = 0, bool $asCount = false) {
        /** @var \Statusengine2Module\Model\Table\AcknowledgementServicesTable $AcknowledgementServicesTable */
        $AcknowledgementServicesTable = TableRegistry::getTableLocator()->get('Statusengine2Module.AcknowledgementServices');


        $query = $AcknowledgementServicesTable->find();

        $query
            ->select([
                'AcknowledgementServices.state',
                'AcknowledgementServices.author_name',
                'AcknowledgementServices.comment_data',
                'AcknowledgementServices.entry_time',
                'AcknowledgementServices.acknowledgement_type',
                'AcknowledgementServices.is_sticky',
                'AcknowledgementServices.persistent_comment',
                'AcknowledgementServices.notify_contacts',

                'Objects.name1',
                'Objects.name2',
            ])
            ->innerJoin(
                ['Objects' => 'nagios_objects'],
                ['Objects.object_id = AcknowledgementServices.object_id']
            )
            ->where([
                'Objects.objecttype_id' => 2
            ]);

        if ($asCount === true) {
            return $query->count();
        }

        $query->offset($offset);
        $query->limit($this->limit);
        $query->disableHydration();
        $query->disableResultsCasting();
        return $query->toArray();
    }

    private function migrateParitions(string $sourceTable, string $targetTable) {
        $Connection = ConnectionManager::get('default');

        //Get existing partitions for this table out of MySQL's information_schema
        $query = $Connection->execute("
                SELECT *   
                FROM information_schema.partitions
                WHERE TABLE_SCHEMA = :databaseName
                AND TABLE_NAME = :tableName
                ORDER BY partitions.PARTITION_DESCRIPTION ASC", [
            'databaseName' => $Connection->config()['database'],
            'tableName'    => $sourceTable
        ]);
        $partitionsInNdoSchema = $query->fetchAll('assoc');

        $query = $Connection->execute("
                SELECT *   
                FROM information_schema.partitions
                WHERE TABLE_SCHEMA = :databaseName
                AND TABLE_NAME = :tableName
                ORDER BY partitions.PARTITION_DESCRIPTION ASC", [
            'databaseName' => $Connection->config()['database'],
            'tableName'    => $targetTable
        ]);
        $partitionsInStatusengineSchema = $query->fetchAll('assoc');
        $partitionsInStatusengineSchemaNames = Hash::extract($partitionsInStatusengineSchema, '{n}.PARTITION_NAME');

        foreach ($partitionsInNdoSchema as $ndoPartition) {
            if ($ndoPartition['PARTITION_NAME'] === 'p_max') {
                continue;
            }

            if (!in_array($ndoPartition['PARTITION_NAME'], $partitionsInStatusengineSchemaNames, true)) {

                //Get less than value
                $query = $Connection->execute("SELECT FROM_DAYS(" . $ndoPartition['PARTITION_DESCRIPTION'] . ") as less_than");
                $lessThan = $query->fetchAll('assoc');

                $lessThan = strtotime($lessThan[0]['less_than'] . ' 00:00:00');
                //debug($ndoPartition['PARTITION_NAME'] . ' => ' . date('d.m.o H:i:s', $lessThan) . ' => ' . intdiv($lessThan, 86400));
                $currentMysqlPartitionStartDate = intdiv($lessThan, 86400);

                $this->io->info(__('Create partition {0} in tablke {1}', $ndoPartition['PARTITION_NAME'], $targetTable), 0);
                $Connection->execute("ALTER TABLE " . $Connection->config()['database'] . "." . $targetTable . " REORGANIZE PARTITION p_max INTO (PARTITION " . $ndoPartition['PARTITION_NAME'] . " VALUES LESS THAN (" . $currentMysqlPartitionStartDate . "), PARTITION p_max values LESS THAN (MAXVALUE));");
                $this->io->success('   Ok');
            }
        }
    }

    /**
     * @return float
     */
    private function getMicrotime() {
        $microtime = explode(' ', microtime())[0];
        return (int)ltrim($microtime, '0.');
    }
}
