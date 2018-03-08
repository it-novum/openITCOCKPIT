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

use \itnovum\openITCOCKPIT\Core\Interfaces\CronjobInterface;

class InstantReportTask extends AppShell implements CronjobInterface
{
    public $uses = [
        'Instantreport',
        'Systemsetting'
    ];
    public $_systemsettings;

    public function execute($quiet = false){
        App::uses('Folder', 'Utility');
        App::uses('CakeEmail','Network/Email');
        $this->_systemsettings = $this->Systemsetting->findAsArraySection('MONITORING');
        $this->params['quiet'] = $quiet;
        $this->stdout->styles('green', ['text' => 'green']);
        $this->stdout->styles('red', ['text' => 'red']);
        $this->out('Sending Instant Reports...');
        $allInstantReports = $this->Instantreport->find('all', [
            'recursive' => -1,
            'conditions' => [
                'Instantreport.send_email' => '1',
            ],
            'contain'    => [
                'User.email'
            ],
        ]);
        $toSend = false;
        foreach($allInstantReports as $mInstantReport){
            $hasToBeSend = $this->Instantreport->hasToBeSend($mInstantReport['Instantreport']['last_send_date'], $mInstantReport['Instantreport']['send_interval']);
            if($hasToBeSend === false) continue;
            if(empty($mInstantReport['User'])) continue;
            $emailsToSend = [];
            foreach ($mInstantReport['User'] as $userToSend){
                $emailsToSend[] = $userToSend['email'];
            }
            if(empty($emailsToSend)) continue;
            App::uses('CakePdf', 'CakePdf.Pdf');
            App::import('Controller', 'Instantreports');
            $InstantreportsController = new InstantreportsController();
            $InstantreportsController->cronFromDate = $this->Instantreport->reportStartTime($mInstantReport['Instantreport']['send_interval']);
            $InstantreportsController->cronToDate = $this->Instantreport->reportEndTime($mInstantReport['Instantreport']['send_interval']);
            $InstantreportsController->cronPdfName = APP.'tmp/InstantReport_'.$mInstantReport['Instantreport']['id'].'.pdf';
            $InstantreportsController->generate($mInstantReport['Instantreport']['id']);
            $attachmentArray[preg_replace('[^0-9a-zA-Z_\s]', '_', $mInstantReport['Instantreport']['name']).'.pdf'] = [
                'file'     => $InstantreportsController->cronPdfName,
                'mimetype' => 'application/pdf'
            ];
            $sendIntervals = $this->Instantreport->getSendIntervals();
            $subject = $sendIntervals[$mInstantReport['Instantreport']['send_interval']].' Instant Report '.$mInstantReport['Instantreport']['name'];
            $Email = new CakeEmail();
            $Email->config('default');
            $Email->from([$this->_systemsettings['MONITORING']['MONITORING.FROM_ADDRESS'] => $this->_systemsettings['MONITORING']['MONITORING.FROM_NAME']]);
            $Email->to($emailsToSend);
            $Email->subject($subject);
            $Email->attachments($attachmentArray);
            $toSend = true;

            if ($Email->send('Attached you find the automatically generated Instant Report!')) {
                $this->out('Report "'.$mInstantReport['Instantreport']['id'].'" sent to mail address "'.implode(', ', $emailsToSend).'"', false);
                $this->Instantreport->id = $mInstantReport['Instantreport']['id'];
                $this->Instantreport->saveField('last_send_date', date('Y-m-d H:i:s'));
                $this->out('<green>   Ok</green>');
            } else {
                $this->out('ERROR sending report  "'.$mInstantReport['Instantreport']['id'].'" to mail address "'.implode(', ', $emailsToSend).'" !', false);
                $this->out('<red>   Error</red>');
            }
        }
        if(!$toSend){
            $this->out('<green>No emails to send</green>');
        }
        $this->hr();
    }
}
