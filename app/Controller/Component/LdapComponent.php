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

use adLDAP\adLDAP;

class LdapComponent extends Component
{

    public $components = ['Session'];


    public function __construct(ComponentCollection $collection, $settings = [])
    {
        parent::__construct($collection, $settings);

        //Load external lib
        require_once APP.'Vendor'.DS.'adLDAP'.DS.'lib'.DS.'adLDAP'.DS.'adLDAP.php';

        //Load Systemsettings
        $this->Systemsetting = ClassRegistry::init('Systemsetting');
        $this->_systemsettings = $this->Systemsetting->findAsArraySection('FRONTEND');

        if ($this->_systemsettings['FRONTEND']['FRONTEND.AUTH_METHOD'] == 'ldap') {
            //Only connect, if LDAP auth is enabled
            $this->connect();
        }
        //debug($this->adldap->user()->all());
        //debug($this->adldap->group()->all());
    }


    public function connect()
    {

        $useTls = false;

        if ($this->_systemsettings['FRONTEND']['FRONTEND.LDAP.USE_TLS'] == 1) {
            $useTls = true;
        }

        try {
            $this->adldap = new adLDAP([
                'base_dn'            => $this->_systemsettings['FRONTEND']['FRONTEND.LDAP.BASEDN'],
                'domain_controllers' => [$this->_systemsettings['FRONTEND']['FRONTEND.LDAP.ADDRESS']],
                'ad_port'            => $this->_systemsettings['FRONTEND']['FRONTEND.LDAP.PORT'],
                'account_suffix'     => $this->_systemsettings['FRONTEND']['FRONTEND.LDAP.SUFFIX'],
                'admin_username'     => $this->_systemsettings['FRONTEND']['FRONTEND.LDAP.USERNAME'],
                'admin_password'     => $this->_systemsettings['FRONTEND']['FRONTEND.LDAP.PASSWORD'],
                'use_tls'            => $useTls,
            ]);
        } catch (Exception $e) {
            $this->Session->setFlash($e->getMessage(), 'default', [
                'class' => 'alert alert-danger',
            ], 'flash');
        }
    }

    public function login($username, $password)
    {
        if (!is_resource($this->adldap)) {
            $this->connect();
        }

        return $this->adldap->authenticate($username, $password);
    }

    public function userInfo($username)
    {
        $fields = ['samaccountname', 'mail', 'memberof', 'department', 'displayname', 'telephonenumber', 'primarygroupid', 'objectsid', 'sn', 'givenname'];
        $ldapResult = $this->adldap->user()->info($username, $fields);
        //pr($ldapResult); //use pr() becasue cakes debug() will not work on ldap results!!!
        $return = [];
        foreach ($fields as $fieldName) {
            if (isset($ldapResult[0][$fieldName][0])) {
                $return[$fieldName] = $ldapResult[0][$fieldName][0];
            }
        }

        if (isset($return['objectsid'])) {
            //Objectid destroys cakes debug() and i guess we dont need this
            unset($return['objectsid']);
        }

        return $return;
    }

    public function userExists($username)
    {
        $result = $this->userInfo($username);
        if (!empty($result)) {
            return true;
        }

        return false;
    }

    public function findUser($username)
    {
        $result = $this->userInfo($username);

        return $result;
    }

    public function findAllUser()
    {
        return $this->adldap->user()->all();
    }
}
