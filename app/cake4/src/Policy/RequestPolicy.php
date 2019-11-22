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

namespace App\Policy;


use Acl\Controller\Component\AclComponent;
use Acl\Model\Table\ArosTable;
use Authentication\Authenticator\UnauthenticatedException;
use Authorization\Policy\RequestPolicyInterface;
use Cake\Controller\ComponentRegistry;
use Cake\Controller\Exception\SecurityException;
use Cake\Http\ServerRequest;
use Cake\ORM\TableRegistry;

class RequestPolicy implements RequestPolicyInterface {

    /**
     * Method to check if the request can be accessed
     *
     * @param \Authorization\IdentityInterface|null Identity
     * @param \Cake\Http\ServerRequest $request Server Request
     * @return bool
     */
    public function canAccess($identity, ServerRequest $request) {
        $controller = $request->getParam('controller');
        $action = $request->getParam('action');
        $plugin = $request->getParam('plugin'); //not used

        if ($identity === null) {
            //User is not logged in!
            if (strtolower($controller) === 'users' && strtolower($action) === 'login') {
                return true;
            }

            throw new UnauthenticatedException();
        }

        $Collection = new ComponentRegistry();
        $Acl = new AclComponent($Collection);

        /** @var ArosTable $ArosTable */
        $ArosTable = TableRegistry::getTableLocator()->get('Acl.Aros');

        $usergroupId = $identity->get('usergroup_id');

        $usergroupHasAros = $ArosTable->exists([
            'Aros.foreign_key' => $usergroupId
        ]);

        if ($usergroupHasAros === false) {
            throw new SecurityException('No Aros defined for given usergroup_id!');
        }

        // Uncomment to disable ACL permission checks
        // return true;

        //debug($Acl->check(['Usergroups' => ['id' => $usergroupId]], "$controller/$action"));die();
        return $Acl->check(['Usergroups' => ['id' => $usergroupId]], "$controller/$action");
    }
}
