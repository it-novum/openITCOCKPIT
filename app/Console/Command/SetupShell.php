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

use Cake\ORM\TableRegistry;
use itnovum\openITCOCKPIT\SetupShell\MailConfigurator;
use itnovum\openITCOCKPIT\SetupShell\MailConfigValue;
use itnovum\openITCOCKPIT\SetupShell\MailConfigValueInt;

class SetupShell extends AppShell {
    public $uses = ['Systemsetting', 'User', 'Cronjob', 'ContainerUserMembership'];

    public $load = true;

    public function main() {
        if ($this->load === true) {
            App::uses('Folder', 'Utility');
            App::import('Component', 'Auth');
            $this->Auth = @new AuthComponent(new ComponentCollection());
            $this->load = false;
        }


        $this->stdout->styles('green', ['text' => 'green']);
        $this->stdout->styles('blue', ['text' => 'blue']);
        $this->stdout->styles('red', ['text' => 'red']);

        $this->parser = $this->getOptionParser();
        $this->out('This shell helps you to setup your fresh installation of openITCOCKPIT.');
        $this->out('<red>NOTICE:</red>');
        $this->out('<red>Only run this shell, if you installed openITCOCKPIT right now and never logged in to the interface!</red>');
        $this->out('<red>If you continue, you will lose all your archive data!</red>');
        $this->hr();
        $this->out(__d('oitc_console', '[Y]es I want to continue the setup of openITCOCKPIT'));
        $this->out(__d('oitc_console', '[N]o and exit shell'));
        $this->stdout->styles('red_bold', ['text' => 'red', 'bold' => true]);


        $this->Userdata = ['User' => []];
        $this->Systemdata = ['Systemsetting' => []];
        $this->Mail = [];

        $menuSelection = strtoupper($this->in(__d('oitc_console', 'Are you sure to continue?'), ['Y', 'N']));
        switch ($menuSelection) {
            case 'Y':
                $this->setup();
                break;
            case 'N':
                exit(1);
            default:
                $this->out(__d('oitc_console', 'You have made an invalid selection. Please choose by entering Y or N.'));
        }

        $this->hr();
        $this->main();
    }

    public function setup() {
        if ($this->fetchUserdata()) {
            /** @var $Users App\Model\Table\UsersTable */
            $Users = TableRegistry::getTableLocator()->get('Users');

            $userEntity = $Users->newEntity($this->Userdata['User']);
            $Users->save($userEntity);
            if ($userEntity->hasErrors()) {
                $this->out('The following errors occured:');
                foreach ($userEntity->getErrors() as $validationError) {
                    $this->out("\t" . '<red>' . $validationError . '</red>');
                }
            }
            $this->out('<green>User created successfully</green>');
            if ($this->fetchSystemAddress()) {
                if ($this->Systemsetting->save($this->Systemdata)) {
                    $this->out('<green>Saved IP address successfully</green>');
                    if ($this->fetchMailconfig()) {
                        if ($this->Systemsetting->save($this->Systemdata)) {
                            //Return mail address saved successfully
                            $file = fopen(OLD_APP . 'Config' . DS . 'email.php', 'w+');
                            $mailHost = new MailConfigValue($this->Mail['host']);
                            $mailPort = new MailConfigValueInt((int)$this->Mail['port']);
                            $mailUsername = new MailConfigValue($this->Mail['username']);
                            $mailPassword = new MailConfigValue($this->Mail['password']);
                            $mailConfigurator = new MailConfigurator(
                                $mailHost, $mailPort, $mailUsername, $mailPassword
                            );
                            fwrite($file, $mailConfigurator->getConfig());
                            fclose($file);
                            $this->out('<green>Mail configuration saved successfully</green>');

                            //$this->createMysqlPartitions();
                            $this->createCronjobs();
                            $this->out('');
                            $this->hr();
                            $this->out('<green>You can now open the interface in your browser and login, have a nice day!</green>');
                            $this->hr();
                            exit(0);
                        }
                    }
                }
            }
        }
    }

    public function fetchUserdata() {
        $this->Userdata['User']['firstname'] = $this->askFirstname();
        $this->Userdata['User']['lastname'] = $this->askLastname();
        $this->Userdata['User']['email'] = $this->askEmail();
        $this->Userdata['User']['new_password'] = $this->askPassword();
        $this->Userdata['User']['confirm_new_password'] = $this->Userdata['User']['new_password'];
        $this->Userdata['User']['status'] = 1;
        $this->Userdata['User']['usergroup_id'] = 1;
        $this->Userdata['User']['Container'] = [0 => '1'];
        $this->Userdata['ContainerUserMembership'] = [
            [
                'container_id'     => 1,
                'permission_level' => 2,
            ],
        ];

        $this->out('You entered:');
        $this->out('First name:', false);
        $this->out('<blue>' . $this->Userdata['User']['firstname'] . '</blue>');
        $this->out('Last name:', false);
        $this->out('<blue>' . $this->Userdata['User']['lastname'] . '</blue>');
        $this->out('Email:', false);
        $this->out('<blue>' . $this->Userdata['User']['email'] . '</blue>');
        $this->out('Password:', false);
        $this->out('<blue>' . $this->Userdata['User']['new_password'] . '</blue>');

        if (!$this->askContinue('If you want to continue type Y or N if you want to change the data')) {
            $this->fetchUserdata();
        }

        return true;

    }

    public function askFirstname() {
        $input = $this->in(__d('oitc_console', 'Please enter your first name'));
        $input = trim($input);
        if (strlen($input) > 0) {
            return $input;
        }
        $this->askFirstname();
    }

    public function askLastname() {
        $input = $this->in(__d('oitc_console', 'Please enter your last name'));
        $input = trim($input);
        if (strlen($input) > 0) {
            return $input;
        }
        $this->askLastname();
    }

    public function askEmail() {
        $input = $this->in(__d('oitc_console', 'Please enter your email address. This will be the username for the login.'));
        $input = trim($input);
        if (!Validation::email($input)) {
            $this->askEmail();
        }

        return $input;
    }

    public function askPassword() {
        $this->out('<blue>The password must consist of 6 alphanumeric characters and must contain at least one digit</blue>');
        $pw1 = $this->in(__d('oitc_console', 'Please enter a password for the login.'));
        $pw1 = trim($pw1);
        if (strlen($pw1) >= 6 && strlen($pw1) <= 12) {
            $pw2 = $this->in(__d('oitc_console', 'Please confirm your password'));
            $pw2 = trim($pw2);
            if ($pw1 == $pw2) {
                return $pw1;
            }
        }
        $this->out('Password mismatch!');
        $this->askPassword();
    }

    public function askContinue($message) {
        $input = strtoupper($this->in(__d('oitc_console', $message), ['Y', 'N']));
        $input = trim($input);
        if ($input == 'Y') {
            return true;
        } else if ($input == 'N') {
            return false;
        } else {
            $this->askContinue();
        }
    }

    public function fetchSystemAddress() {
        $currentValue = $this->Systemsetting->findByKey('SYSTEM.ADDRESS');
        $currentValue['Systemsetting']['value'] = $this->askSystemIp();
        $this->Systemdata = $currentValue;

        return true;
    }

    public function askSystemIp() {
        $input = $this->in(__d('oitc_console', 'Please enter the FQDN or IP address of your openITCOCKPIT Server. If you do not know your IP address enter a random one and change it via the interface later.'));
        $input = trim($input);
        if (strlen($input) > 0) {
            return $input;
        }
        $this->askSystemIp();
    }

    public function fetchMailconfig() {
        $this->out('<blue>The installer will ask you now for your mail configuration</blue>');
        $this->out('<blue>This configuration is used by the interface and the monitoring software to send emails</blue>');
        $this->out('<blue>You don\'t need to install a local mailserver</blue>');
        $this->out('<blue>If you want to change this settings later </blue>', false);
        $this->out('<red>' . OLD_APP . 'Config' . DS . 'email.php </red>', false);
        $this->out('<blue> is the place you need to search for</blue>');

        $currentValue = $this->Systemsetting->findByKey('MONITORING.FROM_ADDRESS');
        $currentValue['Systemsetting']['value'] = $this->askFromAddress();
        $this->Systemdata = ['Systemsetting' => []];
        $this->Systemdata = $currentValue;

        $this->Mail['host'] = $this->askMailhost();
        $this->Mail['port'] = $this->askMailPort();
        $this->Mail['username'] = $this->askMailUser();
        $this->Mail['password'] = $this->askMailPassword();

        return true;
    }

    public function askFromAddress() {
        $this->out('<blue>openITCOCKPIT requires a valid mail address to send mails. (e.g. openitcockpit@example.org)</blue>');
        $input = $this->in(__d('oitc_console', 'Please enter your return mail address'));
        $input = trim($input);
        if (!Validation::email($input)) {
            $this->askFromAddress();
        }

        return $input;
    }

    public function askMailhost() {
        $this->out('<blue>If you want to use a local installed mail server (e.g.postfix) enter 127.0.0.1 as address and left username and password blank</blue>');
        $input = $this->in(__d('oitc_console', 'Please enter the address of your mail server (e.g. mail.example.org)'));
        $input = trim($input);
        if (strlen($input) > 0) {
            return $input;
        }
        $this->askMailhost();
    }

    public function askMailPort() {
        $this->out('<blue>I guess you want to enter 25 as port</blue>');
        $input = $this->in(__d('oitc_console', 'Please enter the port of your mail server'), null, 25);
        $input = trim($input);
        if (strlen($input) > 0 && is_numeric($input)) {
            return $input;
        }
        $this->askMailPort();
    }

    public function askMailUser() {
        $this->out('<blue>Your username may looks like </blue>', false);
        $this->out('<red>domain\jdoe</red> or <red>john.doe@example.org</red>');
        $input = $this->in(__d('oitc_console', 'Please enter your username, or leave it blank if you don\'t need a user'));
        $input = trim($input);

        return $input;
    }

    public function askMailPassword() {
        $input = $this->in(__d('oitc_console', 'Please enter your password, or leave it blank if you don\'t need a password'));
        $input = trim($input);

        return $input;
    }

    public function createMysqlPartitions() {
        $this->out('Create MySQL partitions', false);
        if (file_exists('/etc/openitcockpit/mysql.cnf')) {
            exec('mysql --defaults-extra-file=/etc/openitcockpit/mysql.cnf < ' . OLD_APP . 'partitions.sql', $out, $ret);
            if ($ret == 0) {
                $this->out('<green> ...OK</green>');

                return true;
            }
            $this->out('<red> ...ERROR</red>');

            return false;
        }
        $this->out('<red> MySQL configuration file /etc/openitcockpit/mysql.cnf does not exist</red>');

        return false;
    }


    public function createCronjobs() {
        $this->out('<blue>Checking for missing cronjobs</blue>');

        /** @var CronjobsTable $Cronjobs */
        $Cronjobs = TableRegistry::getTableLocator()->get('Cronjobs');

        //Check if load cronjob exists
        if (!$Cronjobs->checkForCronjob('CpuLoad', 'Core')) {
            //Cron does not exists, so we create it
            $cpuCron = $Cronjobs->newEntity([
                'task'     => 'CpuLoad',
                'plugin'   => 'Core',
                'interval' => 15,
                'enabled'  => 1
            ]);

            $Cronjobs->save($cpuCron);

            if ($cpuCron->hasErrors()) {
                $this->out($cpuCron->getErrors());
            }
        }

        //Check if load cronjob exists
        if (!$Cronjobs->checkForCronjob('CpuLoad', 'Core')) {
            //Cron does not exists, so we create it
            $versionCheckCron = $Cronjobs->newEntity([
                'task'     => 'VersionCheck',
                'plugin'   => 'Core',
                'interval' => 1440,
                'enabled'  => 1
            ]);

            $Cronjobs->save($versionCheckCron);

            if ($versionCheckCron->hasErrors()) {
                $this->out($versionCheckCron->getErrors());
            }
        }


        //Check if load cronjob exists
        if (!$Cronjobs->checkForCronjob('CpuLoad', 'Core')) {
            //Cron does not exists, so we create it
            $systemHealthCron = $Cronjobs->newEntity([
                'task'     => 'SystemHealth',
                'plugin'   => 'Core',
                'interval' => 1,
                'enabled'  => 1
            ]);

            $Cronjobs->save($systemHealthCron);

            if ($systemHealthCron->hasErrors()) {
                $this->out($systemHealthCron->getErrors());
            }
        }


    }
}
