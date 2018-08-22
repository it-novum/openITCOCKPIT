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

namespace itnovum\openITCOCKPIT\Core\System\Health;


class LsbRelease {

    /**
     * @var null|string
     */
    private $vendor = null;

    /**
     * @var null|string
     */
    private $version = null;

    /**
     * @var null|string
     */
    private $codename = null;

    public function __construct() {

        if(file_exists('/etc/lsb-release')){
            foreach(file('/etc/lsb-release') as $line){
                $line = trim($line);
                $res = explode('DISTRIB_ID=', $line);
                if(isset($res[1])){
                    $this->vendor = $res[1];
                }

                $res = explode('DISTRIB_RELEASE=', $line);
                if(isset($res[1])){
                    $this->version = $res[1];
                }

                $res = explode('DISTRIB_CODENAME=', $line);
                if(isset($res[1])){
                    $this->codename = $res[1];
                }
            }
        }
    }

    /**
     * @return null|string
     */
    public function getVendor() {
        return $this->vendor;
    }

    /**
     * @return null|string
     */
    public function getVersion() {
        return $this->version;
    }

    /**
     * @return null|string
     */
    public function getCodename() {
        return $this->codename;
    }

}
