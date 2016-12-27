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

class SudoResponseSocketComponent extends Component
{
    public function initialize(Controller $controller)
    {
        $this->Controller = $controller;
        $this->Controller->loadModel('Systemsetting');
        $this->_systemsettings = $this->Controller->Systemsetting->findAsArray();
        $this->socket = null;
        $this->request_id = sha1(time().rand().rand());
    }


    public function createSocket()
    {
        return socket_create(AF_UNIX, SOCK_DGRAM, 0);
    }

    public function send($task = 'ping', $payload = [])
    {
        $data = [
            'task'       => $task,
            'payload'    => $payload,
            'key'        => $this->_systemsettings['SUDO_SERVER']['SUDO_SERVER.API_KEY'],
            'request_id' => $this->request_id,
        ];
        $data = json_encode($data);
        if (!socket_sendto($this->socket, $data, strlen($data), 0, $this->_systemsettings['SUDO_SERVER']['SUDO_SERVER.SOCKET'].$this->_systemsettings['SUDO_SERVER']['SUDO_SERVER.SOCKET_NAME'])) {
            $this->Controller->setFlash(__('Could not connect to UNIX socket ').$this->_systemsettings['SUDO_SERVER']['SUDO_SERVER.SOCKET'].$this->_systemsettings['SUDO_SERVER']['SUDO_SERVER.SOCKET_NAME']);
        };
    }

    public function read($len = 4096)
    {
        $buf = "";
        socket_recv($this->socket, $buf, $len, MSG_DONTWAIT);
        if ($buf !== null) {
            $data = json_decode($buf);
            if ($data->request_id == $this->request_id) {
                return $data;
            }
        }

        return null;
    }

    public function readAll()
    {
        $recived = [];
        while (true) {
            //Fetching sudo_server's output out of your unix socket
            $data = $this->read();
            if ($data !== null) {
                if ($data->type == 'end_transmission') {
                    //data transmission is finished
                    break;
                }
                $recived[] = $data->payload;
            }
        }
        //Close socket
        $this->deleteSocket();

        return $recived;
    }


    public function bindSocket()
    {
        $this->deleteSocket();

        if (!is_resource($this->socket)) {
            $this->socket = $this->createSocket();
        }

        socket_bind($this->socket, $this->_systemsettings['SUDO_SERVER']['SUDO_SERVER.SOCKET'].$this->_systemsettings['SUDO_SERVER']['SUDO_SERVER.RESPONSESOCKET_NAME']);
        if (file_exists($this->_systemsettings['SUDO_SERVER']['SUDO_SERVER.SOCKET'].$this->_systemsettings['SUDO_SERVER']['SUDO_SERVER.RESPONSESOCKET_NAME'])) {
            $this->setFilePermissions();

            return true;
        } else {
            return false;
        }
    }

    function deleteSocket()
    {
        $this->socket = null;
        if (file_exists($this->_systemsettings['SUDO_SERVER']['SUDO_SERVER.SOCKET'].$this->_systemsettings['SUDO_SERVER']['SUDO_SERVER.RESPONSESOCKET_NAME'])) {
            unlink($this->_systemsettings['SUDO_SERVER']['SUDO_SERVER.SOCKET'].$this->_systemsettings['SUDO_SERVER']['SUDO_SERVER.RESPONSESOCKET_NAME']);
        }
    }

    public function setFilePermissions()
    {
        chown($this->_systemsettings['SUDO_SERVER']['SUDO_SERVER.SOCKET'].$this->_systemsettings['SUDO_SERVER']['SUDO_SERVER.RESPONSESOCKET_NAME'], $this->_systemsettings['WEBSERVER']['WEBSERVER.USER']);
        chgrp($this->_systemsettings['SUDO_SERVER']['SUDO_SERVER.SOCKET'].$this->_systemsettings['SUDO_SERVER']['SUDO_SERVER.RESPONSESOCKET_NAME'], $this->_systemsettings['WEBSERVER']['WEBSERVER.GROUP']);
        chmod($this->_systemsettings['SUDO_SERVER']['SUDO_SERVER.SOCKET'].$this->_systemsettings['SUDO_SERVER']['SUDO_SERVER.RESPONSESOCKET_NAME'], $this->_systemsettings['SUDO_SERVER']['SUDO_SERVER.RESPONSESOCKETPERMISSIONS']);
    }

}