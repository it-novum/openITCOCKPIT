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


//class AcknowledgePerMailTask extends AppShell
//{
//
//    public function execute($quiet = false)
//    {
//        $this->params['quiet'] = $quiet;
//        $this->stdout->styles('green', ['text' => 'green']);
//        $this->stdout->styles('red', ['text' => 'red']);
//        $this->out('Checking inbox mails', false);
//
//        $availableMails = $this->getInboxMails();
//
//        $this->out('<green>   Ok</green>');
//        $this->hr();
//    }
//
//    /**
//     * @return string all unseen messages from inbox
//     */
//    public function getInboxMails()
//    {
        $hostname = '{imap.gmail.com:993/ssl}INBOX';
        $username = 'www.mail.ru89@gmail.com';
        $password = 'Eaytyflj!89';

        /* try to connect */
        $inbox = imap_open($hostname,$username,$password) or die('Cannot connect to Gmail: ' . imap_last_error());

        /* grab emails */
        $emails = imap_search($inbox,'ALL');
        var_dump($emails);
//    }
//}