<?php
// Copyright (C) <2015>  <it-novum GmbH>
//
// This file is dual licensed
//
// 1.
//  This program is free software: you can redistribute it and/or modify
//  it under the terms of the GNU General Public License as published by
//  the Free Software Foundation, version 3 of the License.
//
//  This program is distributed in the hope that it will be useful,
//  but WITHOUT ANY WARRANTY; without even the implied warranty of
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//  GNU General Public License for more details.
//
//  You should have received a copy of the GNU General Public License
//  along with this program.  If not, see <http://www.gnu.org/licenses/>.
//
// 2.
//  If you purchased an openITCOCKPIT Enterprise Edition you can use this file
//  under the terms of the openITCOCKPIT Enterprise Edition license agreement.
//  License agreement and license key will be shipped with the order
//  confirmation.

App::import('Vendor', 'ddeboer/imap/src/Server');

use Cake\ORM\TableRegistry;
use Ddeboer\Imap\Server;
use itnovum\openITCOCKPIT\Core\Interfaces\CronjobInterface;

class AcknowledgePerMailTask extends AppShell implements CronjobInterface {
    public $uses = ['NagiosModule.Externalcommand', 'Systemsetting'];
    public $_systemsettings;

    public function execute($quiet = false) {

        $this->params['quiet'] = $quiet;
        $this->stdout->styles('green', ['text' => 'green']);
        $this->stdout->styles('red', ['text' => 'red']);
        $this->out('Checking inbox mails for acknowledgment...    ');

        $ackHostsAndServices = $this->ackHostsAndServices();

        $this->out($ackHostsAndServices['success']);
        foreach ($ackHostsAndServices['messages'] as $message) {
            $this->out($message);
        }
        $this->hr();
    }

    /**
     * @return string all unseen messages from inbox
     */
    private function ackHostsAndServices() {
        /** @var $Systemsettings App\Model\Table\SystemsettingsTable */
        $Systemsettings = TableRegistry::getTableLocator()->get('Systemsettings');
        $this->_systemsettings = $Systemsettings->findAsArraySection('MONITORING');
        if (empty($this->_systemsettings['MONITORING']['MONITORING.ACK_RECEIVER_SERVER']) ||
            empty($this->_systemsettings['MONITORING']['MONITORING.ACK_RECEIVER_ADDRESS']) ||
            empty($this->_systemsettings['MONITORING']['MONITORING.ACK_RECEIVER_PASSWORD'])) {
            return ['success' => '<red>Error</red>', 'messages' => ['Some of ACK_ values were not provided in system settings']];
        }
        $serverParts = explode('/', $this->_systemsettings['MONITORING']['MONITORING.ACK_RECEIVER_SERVER']);
        if (count($serverParts) < 2) {
            return ['success' => '<red>Error</red>', 'messages' => ['ACK_RECEIVER_SERVER has wrong format']];
        }
        $serverAndPort = explode(':', $serverParts[0]);
        if (count($serverAndPort) < 2) {
            return ['success' => '<red>Error</red>', 'messages' => ['ACK_RECEIVER_SERVER has wrong format. Either connection URL is wrong or port was not provided.']];
        }

        $serverURL = trim($serverAndPort[0]);
        $serverPort = trim($serverAndPort[1]);
        $serverProtocol = trim($serverParts[1]);
        $serverSSL = in_array('ssl', $serverParts);
        $serverNoValidateCert = in_array('novalidate-cert', $serverParts);
        $serverOptions = '/' . $serverProtocol . ($serverSSL ? '/ssl' : '') . ($serverNoValidateCert ? '/novalidate-cert' : '');

        if ($serverProtocol != 'imap') {
            return ['success' => '<red>Error</red>', 'messages' => ['Only IMAP protocol is supported.']];
        }

        $server = new Server($serverURL, $serverPort, $serverOptions);
        $connection = $server->authenticate($this->_systemsettings['MONITORING']['MONITORING.ACK_RECEIVER_ADDRESS'], $this->_systemsettings['MONITORING']['MONITORING.ACK_RECEIVER_PASSWORD']);
        $mailbox = $connection->getMailbox('INBOX');
        $acks = [];
        $success = '<green>Ok</green>';
        $acknowledged = 0;
        openlog('Ack per mail ', LOG_CONS | LOG_NDELAY | LOG_PID, LOG_USER | LOG_PERROR);
        foreach ($mailbox->getMessages() as $message) {
            $this->out('Parsing email from ' . $message->getFrom()->getAddress());

            $body = $message->getBodyText();
            //Check if this is base64_encoded, but the header is not set to base64
            if ($this->isBase64($body)) {
                $body = base64_decode(str_replace(["\n", "\r\n", "\r"], '', $body), true);
            }
//debug($body);
            $parsedValues = $this->parseAckInformation($body);
            if (empty($parsedValues)){
                $message->delete();
                continue;
            }
            $author = empty($message->getFrom()->getName()) ? $message->getFrom()->getAddress() : $message->getFrom()->getName();
            $acknowledged++;
            if (empty($parsedValues['ACK_SERVICEUUID']) && !empty($parsedValues['ACK_HOSTUUID'])) {
                $hostAckArray = [
                    'hostUuid' => $parsedValues['ACK_HOSTUUID'],
                    'author'   => $author,
                    'comment'  => __('Acknowledged per mail'),
                    'sticky'   => 1,
                    'type'     => 'hostOnly'
                ];
                syslog(LOG_INFO, json_encode($hostAckArray));
                $this->Externalcommand->setHostAck($hostAckArray);
                $this->out('Host ' . $parsedValues['ACK_HOSTUUID'] . ' <green>acknowledged</green>');
            } else if (!empty($parsedValues['ACK_SERVICEUUID']) && !empty($parsedValues['ACK_HOSTUUID'])) {
                $serviceAckArray = [
                    'hostUuid'    => $parsedValues['ACK_HOSTUUID'],
                    'serviceUuid' => $parsedValues['ACK_SERVICEUUID'],
                    'author'      => $author,
                    'comment'     => __('Acknowledged per mail'),
                    'sticky'      => 1
                ];
                syslog(LOG_INFO, json_encode($serviceAckArray));
                $this->Externalcommand->setServiceAck($serviceAckArray);
                $this->out('Service ' . $parsedValues['ACK_SERVICEUUID'] . ' <green>acknowledged</green>');
            }
            $message->delete();
        }
        closelog();
        $mailbox->expunge();

        if ($acknowledged == 0) {
            $acks = ['No hosts and services were acknowledged'];
        }
        $connection->close();
        return ['success' => $success, 'messages' => $acks];
    }

    private function getStringBetween($string, $start, $end) {
        $str = str_replace(["\r", "\n", '>'], '', $string);
        $ini = mb_strpos($str, $start);
        if ($ini === false) return '';
        $ini += strlen($start);
        $len = mb_strpos($str, $end);
        if ($end === false) return '';
        return mb_substr($str, $ini, $len - $ini);
    }

    private function parseAckInformation($ackString) {
        $myString = $this->getStringBetween($ackString, '--- BEGIN ACK INFORMATION ---', '--- END ACK INFORMATION ---');
        if (empty($myString)) {
            $myString = $this->getStringBetween($ackString, '--- BEGIN ACK2 INFORMATION ---', '--- END ACK2 INFORMATION ---');
        }
        if (empty($myString)) return [];

        $firstExplode = explode(':', $myString);
        $currentIndex = trim($firstExplode[0]);
        $returnArr = [];
        for ($i = 1; $i < count($firstExplode); $i++) {
            $nextExplose = explode('ACK_', $firstExplode[$i]);
            $returnArr[$currentIndex] = trim($nextExplose[0]);
            $currentIndex = isset($nextExplose[1]) ? ('ACK_' . trim($nextExplose[1])) : null;
        }
        if (!isset($returnArr['ACK_HOSTUUID']) || empty($returnArr['ACK_HOSTUUID']) || !isset($returnArr['ACK_SERVICEUUID'])) {
            return [];
        }

        return $returnArr;
    }

    /**
     * @param $str
     * @return bool
     */
    private function isBase64($str) {
        $str = str_replace(["\n", "\r\n", "\r"], '', $str);
        if (base64_encode(base64_decode($str, true)) === $str) {
            return true;
        }
        return false;
    }

}
