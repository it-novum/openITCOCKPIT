#!/usr/bin/php
<?php


$PreUpgradeScript = new PreUpgradeScript();
$PreUpgradeScript->execute();

class PreUpgradeScript {

    private $iniFile = '/etc/openitcockpit/mysql.cnf';

    /**
     * @var \PDO
     */
    private $pdo = null;

    const ACTIVE_USER = 1;

    public function execute() {
        if ($_SERVER['USER'] !== 'root') {
            $this->out('Error: This script needs to be executed as root user!');
            exit(1);
        }

        if (!file_exists($this->iniFile)) {
            $this->out('Error: MySQL configuration "' . $this->iniFile . '" not found');
            exit(1);
        }

        $this->getMysqlConnection();
        $this->saveActiveUserStatusToJsonFile();

        //$this->out('Stop all services');
        //$services = [
        //    'nagios.service',
        //    'statusengine.service',
        //    'nginx.service',
        //    'sudo_server.service',
        //    'oitc_cmd.service',
        //    'gearman_worker.service',
        //    'push_notification.service',
        //    'nodejs_server.service',
        //    'openitcockpit-graphing.service'
        //];
        //foreach ($services as $service) {
        //    exec('systemctl stop ' . $service);
        //}

        if(file_exists('/etc/cron.d/openitc')){
            unlink('/etc/cron.d/openitc');
        }

        $this->out('Pre upgrade tasks done.');

    }

    private function out($msg, $newline = true) {
        echo $msg;
        if ($newline) {
            echo PHP_EOL;
        }
    }

    /**
     * @return PDO
     */
    private function getMysqlConnection() {
        if ($this->pdo !== null) {
            return $this->pdo;
        }

        $config = parse_ini_file($this->iniFile, false,  INI_SCANNER_RAW);

        try {
            $pdo = new PDO(
                sprintf(
                    'mysql:host=%s;dbname=%s',
                    $config['host'],
                    $config['database']
                ),
                $config['user'],
                $config['password']
            );
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->pdo = $pdo;
            return $pdo;
        } catch (\Exception $e) {
            $this->out('Could not connect to MySQL Database');
            print_r($e->getMessage());
            exit(1);
        }
    }

    private function saveActiveUserStatusToJsonFile() {
        $pdo = $this->getMysqlConnection();
        $stm = $pdo->prepare("SELECT * FROM users WHERE status = ?");
        $stm->bindValue(1, self::ACTIVE_USER);

        $stm->execute();
        $result = $stm->fetchAll(PDO::FETCH_ASSOC);

        $filename = '/root/.openitcockpit_active_user_migration.json';
        $file = fopen($filename, 'w+');
        fwrite($file, json_encode($result, JSON_PRETTY_PRINT));
        fclose($file);

        $this->out('Users saved to: "' . $filename . '"');
    }

}
