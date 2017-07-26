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

App::import('Vendor', 'Imap/Imap');

use \itnovum\openITCOCKPIT\Core\Interfaces\CronjobInterface;
class AcknowledgePerMailTask extends AppShell implements CronjobInterface
{
    public $uses = ['NagiosModule.Externalcommand', 'Systemsetting'];
    public $_systemsettings;

    public function execute($quiet = false){

        $this->params['quiet'] = $quiet;
        $this->stdout->styles('green', ['text' => 'green']);
        $this->stdout->styles('red', ['text' => 'red']);
        $this->out('Checking inbox mails for acknowledgment...    ', false);

        $ackHostsAndServices = $this->ackHostsAndServices();

        $this->out($ackHostsAndServices['success']);
        foreach($ackHostsAndServices['messages'] as $message){
            $this->out($message);
        }
        $this->hr();
    }

    /**
     * @return string all unseen messages from inbox
     */
    private function ackHostsAndServices(){
        $this->_systemsettings = $this->Systemsetting->findAsArraySection('MONITORING');
        if(empty($this->_systemsettings['MONITORING']['MONITORING.ACK_RECEIVER_SERVER']) ||
            empty($this->_systemsettings['MONITORING']['MONITORING.ACK_RECEIVER_ADDRESS']) ||
            empty($this->_systemsettings['MONITORING']['MONITORING.ACK_RECEIVER_PASSWORD'])){
            return ['success' => '<red>Error</red>', 'messages' => ['Some of ACK_ values were not provided in system settings']];
        }
        $serverParts = explode('/', $this->_systemsettings['MONITORING']['MONITORING.ACK_RECEIVER_SERVER']);
        if(count($serverParts) < 2){
            return ['success' => '<red>Error</red>', 'messages' => ['ACK_RECEIVER_SERVER has wrong format']];
        }
        $serverAndPort = explode(':', $serverParts[0]);
        if(count($serverAndPort) < 2){
            return ['success' => '<red>Error</red>', 'messages' => ['ACK_RECEIVER_SERVER has wrong format. Either connection URL is wrong or port was not provided.']];
        }

        $serverURL = trim($serverAndPort[0]);
        $serverPort = trim($serverAndPort[1]);
        $serverProtocol = trim($serverParts[1]);
        $serverSSL = in_array('ssl', $serverParts);
        $serverNoValidateCert = in_array('novalidate-cert', $serverParts);

        if($serverProtocol != 'imap'){
            return ['success' => '<red>Error</red>', 'messages' => ['Only IMAP protocol is supported.']];
        }

        $mailbox = new \JJG\Imap($serverURL, $this->_systemsettings['MONITORING']['MONITORING.ACK_RECEIVER_ADDRESS'], $this->_systemsettings['MONITORING']['MONITORING.ACK_RECEIVER_PASSWORD'], $serverPort, $serverProtocol, $serverSSL, $serverNoValidateCert, 'INBOX');
        if(!empty($mailbox->error)){
            return ['success' => '<red>Error</red>', 'messages' => [$mailbox->error]];
        }
        $myEmails = $mailbox->searchForEmails();
        $acks = [];
        $success = '<green>Ok</green>';
        $acknowledged = 0;
        if($myEmails !== false) {
            $this->out('Received ' . (count($myEmails)) . ' email(s)...   ');
            foreach ($myEmails as $myEmailId) {
                $messArr = $mailbox->getMessage($myEmailId);
                $this->out('Parsing email from '.((isset($messArr['sender']) && !empty($messArr['sender']))?$messArr['sender']:$messArr['from']));
                $parsedValues = $this->parseAckInformation($messArr['body']);
                $mailbox->deleteMessage($myEmailId);
                if (empty($parsedValues)) continue;
                $acknowledged++;
                if (empty($parsedValues['ACK_SERVICEUUID']) && !empty($parsedValues['ACK_HOSTUUID'])) {
                    $this->Externalcommand->setHostAck([
                        'hostUuid' => $parsedValues['ACK_HOSTUUID'],
                        'author' => isset($messArr['sender']) && !empty($messArr['sender']) ? $messArr['sender'] : (isset($messArr['from']) && !empty($messArr['from']) ? $messArr['from'] : 'Unknown author'),
                        'comment' => __('Acknowledged per mail'),
                        'sticky' => 1,
                        'type' => 'hostOnly'
                    ]);
                    $this->out('Host ' . $parsedValues['ACK_HOSTUUID'] . ' <green>acknowledged</green>');
                } elseif (!empty($parsedValues['ACK_SERVICEUUID']) && !empty($parsedValues['ACK_HOSTUUID'])) {
                    $this->Externalcommand->setServiceAck([
                        'hostUuid' => $parsedValues['ACK_HOSTUUID'],
                        'serviceUuid' => $parsedValues['ACK_SERVICEUUID'],
                        'author' => isset($messArr['sender']) && !empty($messArr['sender']) ? $messArr['sender'] : (isset($messArr['from']) && !empty($messArr['from']) ? $messArr['from'] : 'Unknown author'),
                        'comment' => __('Acknowledged per mail'),
                        'sticky' => 1
                    ]);
                    $this->out('Service ' . $parsedValues['ACK_SERVICEUUID'] . ' <green>acknowledged</green>');
                }
            }
        }

        if($acknowledged == 0){
            $acks = ['No hosts and services were acknowledged'];
        }
        $mailbox->disconnect();
        return ['success' => $success, 'messages' => $acks];
    }

    private function getStringBetween($string, $start, $end){
        $string = ' ' . $string;
        $ini = strpos($string, $start);
        if ($ini == 0) return '';
        $ini += strlen($start);
        $len = strpos($string, $end, $ini) - $ini;
        return substr($string, $ini, $len);
    }

    private function parseAckInformation($ackString){
        $myString = $this->getStringBetween($ackString, '--- BEGIN ACK INFORMATION ---', '--- END ACK INFORMATION ---');
        if(empty($myString)){
            $myString = $this->getStringBetween($ackString, '--- BEGIN ACK2 INFORMATION ---', '--- END ACK2 INFORMATION ---');
        }
        if(empty($myString)) return [];

        $firstExplode = explode(':', $myString);
        $currentIndex = trim($firstExplode[0]);
        $returnArr = [];
        for($i = 1; $i<count($firstExplode); $i++){
            $nextExplose = explode('ACK_', $firstExplode[$i]);
            $returnArr[$currentIndex] = trim($nextExplose[0]);
            $currentIndex = isset($nextExplose[1]) ? ('ACK_'.trim($nextExplose[1])) : null;
        }
        if(!isset($returnArr['ACK_HOSTUUID']) || empty($returnArr['ACK_HOSTUUID']) || !isset($returnArr['ACK_SERVICEUUID'])) {
            return [];
        }

        return $returnArr;
    }

}