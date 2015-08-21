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

class SocketTestShell extends AppShell {
	
	public $uses = ['Systemsetting'];
	
	public function main() {
		
		$this->_systemsettings = $this->Systemsetting->findAsArray();
		$this->socket = $this->createSocket();
		$this->send(['task' => 'test', 'payload' => '123 abc test']);
		
	}
	
	public function createSocket(){
		return socket_create(AF_UNIX, SOCK_DGRAM, 0);
	}
	
	public function send($data){
		$data = json_encode($data);
		if(!socket_sendto($this->socket, $data, strlen($data), 0, $this->_systemsettings['SUDO_SERVER']['SUDO_SERVER.SOCKET'].$this->_systemsettings['SUDO_SERVER']['SUDO_SERVER.SOCKET_NAME'])){
			$this->out('Could not connect to UNIX socket '.$this->_systemsettings['SUDO_SERVER']['SUDO_SERVER.SOCKET'].$this->_systemsettings['SUDO_SERVER']['SUDO_SERVER.SOCKET_NAME']);
		};
	}
}