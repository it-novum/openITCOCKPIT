<?php
// Copyright (C) <2017>  <it-novum GmbH>
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

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;

class RightsShell extends AppShell {

    public function main(){
        $directories = [
            '/usr/share/openitcockpit/',
            '/usr/share/openitcockpit-modules/',
            '/usr/share/openITCOCKPIT-modules/',
        ];
        $this->setRights($directories);
    }

    private function setRights($dirs = []){
        try{
            $user = 'www-data';
            $group = $user;

            $fs = new Filesystem();
            foreach ($dirs as $dir){
                if($fs->exists($dir)){
                    $this->out('<info>set user permissions for '.$dir.'</info>');
                    $fs->chown($dir, $user, true);
                    $fs->chgrp($dir, $group, true);
                    $this->out('<success>done!</success>');
                }

            }
        }catch(IOExceptionInterface $e){
            $this->out('<error>an error occurred at '.$e->getPath().' </error>');
        }
    }
}