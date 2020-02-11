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

declare(strict_types=1);

namespace App\Controller;

use App\Model\Table\ChangelogsTable;
use App\Model\Table\SystemsettingsTable;
use Cake\ORM\TableRegistry;
use itnovum\openITCOCKPIT\Database\PaginateOMat;
use itnovum\openITCOCKPIT\Filter\ChangelogsFilter;

class ChangelogsController extends AppController {

    public function index() {
        if (!$this->isApiRequest()) {
            //Only ship HTML Template
            return;
        }

        /** @var SystemsettingsTable $SystemsettingsTable */
        $SystemsettingsTable = TableRegistry::getTableLocator()->get('Systemsettings');
        /** @var ChangelogsTable $ChangelogsTable */
        $ChangelogsTable = TableRegistry::getTableLocator()->get('Changelogs');

        $result = $SystemsettingsTable->getSystemsettingByKey('FRONTEND.HIDDEN_USER_IN_CHANGELOG');
        $includeUser = $result->get('value') === '0';

        $ChangelogsFilter = new ChangelogsFilter($this->request);
        $PaginateOMat = new PaginateOMat($this, $this->isScrollRequest(), $ChangelogsFilter->getPage());
        $MY_RIGHTS = $this->MY_RIGHTS;
        if ($this->hasRootPrivileges === true) {
            $MY_RIGHTS = [];
        }
        $all_changes = $ChangelogsTable->getChangelogIndex($ChangelogsFilter, $PaginateOMat, $MY_RIGHTS, $includeUser);

        $this->set('all_changes', $all_changes);
        $this->viewBuilder()->setOption('serialize', ['all_changes']);
    }
}
