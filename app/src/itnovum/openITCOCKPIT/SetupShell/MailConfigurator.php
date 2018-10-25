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


namespace itnovum\openITCOCKPIT\SetupShell;


class MailConfigurator {
    /**
     * @var MailConfigValue
     */
    private $host;

    /**
     * @var MailConfigValueInt
     */
    private $port;

    /**
     * @var MailConfigValue
     */
    private $username;

    /**
     * @var MailConfigValue
     */
    private $password;

    /**
     * MailConfigurator constructor.
     *
     * @param MailConfigValue $host
     * @param MailConfigValueInt $port
     * @param MailConfigValue $username
     * @param MailConfigValue $password
     */
    public function __construct(MailConfigValue $host, MailConfigValueInt $port, MailConfigValue $username, MailConfigValue $password) {
        $this->host = $host;
        $this->port = $port;
        $this->username = $username;
        $this->password = $password;
    }

    /**
     * @return string
     */
    public function getConfig() {
        return sprintf(
            $this->getTemplate(),
            $this->host->getValueForConfig(),
            $this->port->getValueForConfig(),
            $this->username->getValueForConfig(),
            $this->password->getValueForConfig()
        );
    }

    /**
     * @return string
     */
    public function getTemplate() {
        $template = '<?php
class EmailConfig {
    public $default = array(
        "transport" => "Smtp",
        "host" => %s,
        "port" => %s,
        "username" => %s,
        "password" => %s,
    );

    public $fast = array(
        "from" => "you@localhost",
        "sender" => null,
        "to" => null,
        "cc" => null,
        "bcc" => null,
        "replyTo" => null,
        "readReceipt" => null,
        "returnPath" => null,
        "messageId" => true,
        "subject" => null,
        "message" => null,
        "headers" => null,
        "viewRender" => null,
        "template" => false,
        "layout" => false,
        "viewVars" => null,
        "attachments" => null,
        "emailFormat" => null,
        "transport" => "Smtp",
        "host" => "localhost",
        "port" => 25,
        "timeout" => 30,
        "username" => "user",
        "password" => "secret",
        "client" => null,
        "log" => true
    );
}
';

        return $template;
    }
}