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

/**
 * Class DashboardsController
 * @property DashboardTab $DashboardTab
 * @property Widget $Widget
 */
class DashboardsController extends AppController {

    //Most calls are API calls or modal html requests
    //Blank is the best default for Dashboards...
    public $layout = 'blank';

    public $uses = [
        'Widget',
        'DashboardTab'
    ];

    public function index() {
        $this->layout = 'angularjs';
        if (!$this->isAngularJsRequest()) {
            //Only ship template
            return;
        }

        $User = new \itnovum\openITCOCKPIT\Core\ValueObjects\User($this->Auth);

        //Check if a tab exists for the given user
        if ($this->DashboardTab->hasUserATab($User->getId()) === false) {
            $this->DashboardTab->createNewTab($User->getId());
        }
        $tabs = $this->DashboardTab->getAllTabsByUserId($User->getId());

        $widgets = $this->Widget->getAvailableWidgets();

        $this->set('tabs', $tabs);
        $this->set('widgets', $widgets);
        $this->set('_serialize', ['tabs', 'widgets']);
    }

    public function getWidgetsForTab($tabId) {
        if (!$this->isAngularJsRequest()) {
            throw new MethodNotAllowedException();
        }

        if (!$this->DashboardTab->exists($tabId)) {
            throw new NotFoundException(sprintf('Tab width id %s not found', $tabId));
        }

        $User = new \itnovum\openITCOCKPIT\Core\ValueObjects\User($this->Auth);
        $widgets = $this->DashboardTab->getWidgetsForTabByUserIdAndTabId($User->getId(), $tabId);
        $this->set('widgets', $widgets);
        $this->set('_serialize', ['widgets']);
    }

    public function dynamicDirective() {
        $directiveName = $this->request->query('directive');
        if (strlen($directiveName) < 2) {
            throw new RuntimeException('Wrong AngularJS directive name?');
        }
        $this->set('directiveName', $directiveName);
    }

    public function saveGrid() {
        if (!$this->isAngularJsRequest() || !$this->request->is('post')) {
            throw new MethodNotAllowedException();
        }

        if ($this->Widget->saveAll($this->request->data)) {
            $this->set('message', __('Successfully saved'));
            $this->set('_serialize', ['message']);
            return;
        }
        $this->serializeErrorMessageFromModel('Widget');
    }


    /***** Basic Widgets *****/
    public function welcomeWidget() {
        if (!$this->isApiRequest()) {
            //Only ship HTML template
            return;
        }
    }


}
