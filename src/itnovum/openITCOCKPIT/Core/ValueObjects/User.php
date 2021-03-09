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

namespace itnovum\openITCOCKPIT\Core\ValueObjects;

use Authentication\IdentityInterface;
use itnovum\openITCOCKPIT\Core\Views\UserTime;

class User {
    /**
     * @var IdentityInterface
     */
    private $Identity;

    /**
     * @var bool
     */
    private $recursiveBrowser;

    /**
     * @var mixed|null
     */
    private $fullName;

    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $timezone;

    /**
     * @var string
     */
    private $dateformat;

    /**
     * @var string
     */
    private $email;

    /**
     * User constructor.
     * @param IdentityInterface $Identity
     */
    public function __construct(IdentityInterface $Identity) {
        $this->Identity = $Identity;

        $this->recursiveBrowser = (bool)$Identity->get('recursive_browser');
        $this->fullName = sprintf('%s %s', $Identity->get('firstname'), $Identity->get('lastname'));
        $this->id = (int)$Identity->get('id');
        $this->timezone = $Identity->get('timezone');
        $this->dateformat = $Identity->get('dateformat');
        $this->email = $Identity->get('email');
    }

    /**getUserTime
     * @return boolean
     */
    public function isRecursiveBrowserEnabled() {
        return $this->recursiveBrowser;
    }

    /**
     * @return mixed|null
     */
    public function getFullName() {
        return $this->fullName;
    }

    /**
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getTimezone() {
        return $this->timezone;
    }

    /**
     * @return string
     */
    public function getDateformat() {
        return $this->dateformat;
    }

    /**
     * @return UserTime
     */
    public function getUserTime() {
        return new UserTime($this->timezone, $this->dateformat);
    }

    /**
     * @return string
     */
    public function getEmail(){
        return $this->email;
    }


}
