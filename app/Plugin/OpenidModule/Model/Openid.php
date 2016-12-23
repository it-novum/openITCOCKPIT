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

//App::import('Vendor', 'Openid.LightOpenID', ['file' => 'LightOpenID.php']); hat nicht funktioniert...
require_once(__DIR__.DS.'..'.DS.'Vendor'.DS.'LightOpenID.php');
class Openid extends OpenidModuleAppModel{
    public $hasMany = [
        'OpenidLogs' => [
            'className' => 'OpenidModule.OpenidLog',
            'dependent' => true,
        ]
    ];

    public $validate = [
        'my_domain' => [
            'notBlank' => [
                'rule'    => 'notBlank',
                'message' => 'This field cannot be left blank.',
                'required' => true
            ],
        ],
        'identity' => [
            'notBlank' => [
                'rule'    => 'notBlank',
                'message' => 'This field cannot be left blank.',
                'required' => true
            ],
        ],
        'client_secret' => [
            'notBlank' => [
                'rule'    => 'notBlank',
                'message' => 'This field cannot be left blank.',
                'required' => true
            ],
        ]
    ];

    public function getOpenIdConnects(){
        $allOpenIds = $this->find('all', ['conditions' => ['Openid.active' => 1]]);
        $lastOpenID = null;
        $showLoginPage = true; // if at least one OpenID Connect has show_login_page = 0, we ignore all other openID connects and redirect to SSO server
        foreach ($allOpenIds as $openId) {
            if($openId['Openid']['show_login_page'] === '0'){
                $showLoginPage = false;
                $lastOpenID = $openId['Openid'];
                break;
            }
        }
        if(!$showLoginPage){
            $myLightOpenID = new LightOpenID($lastOpenID['my_domain']);
            $myLightOpenID->identity = $lastOpenID['identity'];
            $myLightOpenID->required = ['contact/email'];
            $myLightOpenID->returnUrl = $this->getReturnUrl($lastOpenID['id']);
            $this->redirect($myLightOpenID->authUrl()); // Still a timeout problem, curl_excec has timeout problem
        }else{
            $openIDButtons = [];
            foreach ($allOpenIds as $openId) {
                $myLightOpenID = new LightOpenID($openId['Openid']['my_domain']);
                $myLightOpenID->identity = $openId['Openid']['identity'];
                $myLightOpenID->required = ['contact/email'];
                $myLightOpenID->returnUrl = $this->getReturnUrl($openId['Openid']['id']);
                $openIDButtons[] = ['text' => $openId['Openid']['button_text'], 'href' => $myLightOpenID->authUrl()];
            }
            return $openIDButtons;
        }
    }

    public function getOpenIDEmail($id = null){
        $myOpenId = $this->findById($id);
        if(empty($myOpenId)){
            return ['email' => null, 'message' => 'OpenID Connect was not found.'];
        }

        $myLightOpenID = new LightOpenID($myOpenId['Openid']['my_domain']);
        if(!$myLightOpenID->mode){
            return ['email' => null, 'message' => 'Please, go to the login page to log in.'];
        }

        if($myLightOpenID->mode === 'cancel'){
            return ['email' => null, 'message' => 'User canceled authentication.'];
        }

        if(!$myLightOpenID->validate()){
            return ['email' => null, 'message' => 'Cannot validate OpenID Connection.'];
        }

        $openIdAttr = $myLightOpenID->getAttributes();
        if (empty($openIdAttr['contact/email']) || !filter_var($openIdAttr['contact/email'], FILTER_VALIDATE_EMAIL)) {
            return ['email' => null, 'message' => 'Email address in OpenID Connect is either empty or not a valid email address.'];
        }

        return ['email' => $openIdAttr['contact/email'], 'message' => null];
    }

    public function getReturnUrl($id){
        return Router::url(['plugin' => 'openid_module', 'controller' => 'openid', 'action' => 'checkAndLogin', 'id' => $id], true );
    }

}
