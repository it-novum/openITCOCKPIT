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

use App\Controller\InstantreportsController;
use App\Model\Entity\Instantreport;
use App\Model\Table\InstantreportsTable;
use App\Model\Table\SystemsettingsTable;
use Cake\Console\Arguments;
use Cake\Console\Command;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;
use Cake\Mailer\Mailer;
use Cake\ORM\Query;
use Cake\ORM\TableRegistry;
use itnovum\openITCOCKPIT\Core\Interfaces\CronjobInterface;
use itnovum\openITCOCKPIT\Core\Views\Logo;

/**
 * Class InstantReportCommand
 * @package App\Command
 */
class InstantReportCommand extends Command implements CronjobInterface {

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
        $io->setStyle('green', ['text' => 'green', 'blink' => false]);
        $io->setStyle('red', ['text' => 'red', 'blink' => false]);

        $io->out('Sending Instant Reports...');
        /** @var SystemsettingsTable $SystemsettingsTable */
        $SystemsettingsTable = TableRegistry::getTableLocator()->get('Systemsettings');
        $systemsettings = $SystemsettingsTable->findAsArraySection('MONITORING');


        /** @var InstantreportsTable $InstantreportsTable */
        $InstantreportsTable = TableRegistry::getTableLocator()->get('Instantreports');
        $instanreports = $InstantreportsTable->find()
            ->contain('Users', function (Query $q) {
                return $q->select(['Users.email']);
            })
            ->where([
                'Instantreports.send_email' => 1
            ]);
        /** @var  $SystemsettingsTable SystemsettingsTable */
        $SystemsettingsTable = TableRegistry::getTableLocator()->get('Systemsettings');
        $_systemsettings = $SystemsettingsTable->findAsArray();

        $Logo = new Logo();
        $Mailer = new Mailer();
        $Mailer->setFrom($_systemsettings['MONITORING']['MONITORING.FROM_ADDRESS'], $_systemsettings['MONITORING']['MONITORING.FROM_NAME']);

        $toSend = false;
        $sendIntervals = [
            __('Never'),
            __('Daily'),
            __('Weekly'),
            __('Monthly'),
            __('Yearly')
        ];
        /** @var  $instantreport Instantreport */
        foreach ($instanreports as $instantreport) {
            try {
                $hasToBeSend = $instantreport->hasToBeSend();
                if ($hasToBeSend === false) {
                    continue;
                }
                $emailsToSend = [];
                foreach ($instantreport->get('users') as $user) {
                    $emailsToSend[] = $user->get('email');
                }
                if (empty($emailsToSend)) {
                    continue;
                }
                //debug($emailsToSend);
                $InstantreportsController = new InstantreportsController();
                $fromDate = date('d.m.Y', $instantreport->reportStartTime());
                $toDate = date('d.m.Y', $instantreport->reportEndTime());


                $Mailer->addTo(implode(';', $emailsToSend));
                $subject = $sendIntervals[$instantreport->get('send_interval')] . ' Instant Report "' . $instantreport->get('name').'"';
                $Mailer->setSubject($subject);
                $Mailer->setEmailFormat('both');
                $Mailer->setAttachments([
                    'logo.png' => [
                        'file'      => $Logo->getSmallLogoDiskPath(),
                        'mimetype'  => 'image/png',
                        'contentId' => '100'
                    ]
                ]);
                /**
                 * $attachmentArray[preg_replace('[^0-9a-zA-Z_\s]', '_', $mInstantReport['Instantreport']['name']) . '.pdf'] = [
                 * 'file'     => $InstantreportsController->cronPdfName,
                 * 'mimetype' => 'application/pdf'
                 * ];
                 */
                //$Mailer->deliver();



                //$pdfReportCronPdfName = APP . 'tmp/InstantReport_' . $instantreport->get('id') . '.pdf';

                //debug($hasToBeSend);
                /*

                App::uses('CakePdf', 'CakePdf.Pdf');
                App::import('Controller', 'Instantreports');
                $InstantreportsController = new InstantreportsController();
                $InstantreportsController->cronFromDate = $this->Instantreport->reportStartTime($mInstantReport['Instantreport']['send_interval']);
                $InstantreportsController->cronToDate = $this->Instantreport->reportEndTime($mInstantReport['Instantreport']['send_interval']);
                $InstantreportsController->cronPdfName = APP . 'tmp/InstantReport_' . $mInstantReport['Instantreport']['id'] . '.pdf';
                $InstantreportsController->generate($mInstantReport['Instantreport']['id']);
                $attachmentArray[preg_replace('[^0-9a-zA-Z_\s]', '_', $mInstantReport['Instantreport']['name']) . '.pdf'] = [
                    'file'     => $InstantreportsController->cronPdfName,
                    'mimetype' => 'application/pdf'
                ];
                $sendIntervals = $this->Instantreport->getSendIntervals();
                $subject = $sendIntervals[$mInstantReport['Instantreport']['send_interval']] . ' Instant Report ' . $mInstantReport['Instantreport']['name'];
                $Email = new CakeEmail();
                $Email->config('default');
                $Email->from([$this->_systemsettings['MONITORING']['MONITORING.FROM_ADDRESS'] => $this->_systemsettings['MONITORING']['MONITORING.FROM_NAME']]);
                $Email->to($emailsToSend);
                $Email->subject($subject);
                $Email->attachments($attachmentArray);
                $toSend = true;

                if ($Email->send('Attached you find the automatically generated Instant Report!')) {
                    $this->out('Report "' . $mInstantReport['Instantreport']['id'] . '" sent to mail address "' . implode(', ', $emailsToSend) . '"', false);
                    $this->Instantreport->id = $mInstantReport['Instantreport']['id'];
                    $this->Instantreport->saveField('last_send_date', date('Y-m-d H:i:s'));
                    $io->success('    Ok');
                } else {
                    $this->out('ERROR sending report  "' . $mInstantReport['Instantreport']['id'] . '" to mail address "' . implode(', ', $emailsToSend) . '" !', false);
                    $io->out('<red>    Error</red>');
                }
                */
                //$instantreport->set('last_send_date', date('Y-m-d H:i:s'));
                //$InstantreportsTable->save($instantreport);

                $io->success('Report "' . $instantreport->get('id') . '" sent to mail address "' . implode(', ', $emailsToSend) . '"');

            } catch (\Exception $ex) {
                $io->out('<red> '. __('An error occured while sending test mail: ') . $ex->getMessage().'</red>');
            }
        }
        if (!$toSend) {
            $io->success('No emails to send');
        }
        $this->cleanUp();
        $io->hr();
    }


    /**
     * @param $str
     * @return bool
     */
    private function cleanUp() {
        $savePath = APP . '/webroot/img/charts/';
        if (!is_dir($savePath)) {
            return false;
        }

        $files = [];
        foreach (new \DirectoryIterator($savePath) as $fileInfo) {
            if (!$fileInfo->isDot()) {
                $files[] = $fileInfo->getRealPath();
            }
        }
        if (empty($files)) {
            return true;
        }

        $Filesystem = new \Symfony\Component\Filesystem\Filesystem();
        $Filesystem->remove($files);
    }
}
