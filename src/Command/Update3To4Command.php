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
use Cake\ORM\TableRegistry;
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

        $this->migrateHostNotifications();
        $this->migrateServiceNotifications();
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
        $hostNotificationsCount = $this->getHostNotificationsMigrationQuery(0, true);
        $numberOfSelects = ceil($hostNotificationsCount / $this->limit);

        if ($hostNotificationsCount == 0) {
            return;
        }

        $this->io->out('Migrating host notifications');
        $ProgressBar = new Manager(0, $numberOfSelects);

        $offset = 0;

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
                $params[] = $record['start_time']->getTimestamp();
                $params[] = $record['start_time']->getTimestamp();
                $params[] = $record['end_time']->getTimestamp();
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
    }

    public function migrateServiceNotifications() {
        $serviceNotificationsCount = $this->getServiceNotificationsMigrationQuery(0, true);
        $numberOfSelects = ceil($serviceNotificationsCount / $this->limit);

        if ($serviceNotificationsCount == 0) {
            return;
        }

        $this->io->out('Migrating service notifications');
        $ProgressBar = new Manager(0, $numberOfSelects);

        $offset = 0;

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
                $params[] = $record['start_time']->getTimestamp();
                $params[] = $record['start_time']->getTimestamp();
                $params[] = $record['end_time']->getTimestamp();
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
        return $query;
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
        return $query->toArray();
    }
}
