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

declare(strict_types=1);

namespace App\Controller;

use App\Lib\Exceptions\MissingDbBackendException;
use App\Lib\openITCOCKPIT_AvailableVersion;
use App\Model\Table\ContactsTable;
use App\Model\Table\ContainersTable;
use App\Model\Table\DocumentationsTable;
use App\Model\Table\HostsTable;
use App\Model\Table\HosttemplatesTable;
use App\Model\Table\MessagesOtdTable;
use App\Model\Table\ServicesTable;
use App\Model\Table\ServicetemplatesTable;
use App\Model\Table\SystemsettingsTable;
use Cake\Cache\Cache;
use Cake\Core\Plugin;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\NotFoundException;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;
use DateTime;
use DateTimeZone;
use Exception;
use itnovum\openITCOCKPIT\Core\CustomMacroReplacer;
use itnovum\openITCOCKPIT\Core\HostMacroReplacer;
use itnovum\openITCOCKPIT\Core\Hoststatus;
use itnovum\openITCOCKPIT\Core\HoststatusFields;
use itnovum\openITCOCKPIT\Core\Menu\Menu;
use itnovum\openITCOCKPIT\Core\Merger\HostMergerForBrowser;
use itnovum\openITCOCKPIT\Core\Merger\ServiceMergerForBrowser;
use itnovum\openITCOCKPIT\Core\ServiceMacroReplacer;
use itnovum\openITCOCKPIT\Core\Servicestatus;
use itnovum\openITCOCKPIT\Core\ServicestatusFields;
use itnovum\openITCOCKPIT\Core\System\Gearman;
use itnovum\openITCOCKPIT\Core\ValueObjects\User;
use itnovum\openITCOCKPIT\Core\Views\HostAndServiceSummaryIcon;
use itnovum\openITCOCKPIT\Core\Views\Logo;
use itnovum\openITCOCKPIT\Core\Views\PieChart;
use itnovum\openITCOCKPIT\Core\Views\UserTime;
use itnovum\openITCOCKPIT\Monitoring\QueryHandler;
use Laminas\Diactoros\CallbackStream;
use RuntimeException;

/**
 * Class VueController
 * @package App\Controller
 */
class VueController extends AppController {

    public function getSystemInformation() {
        $systemname = $this->getSystemname();
        $permissions = $this->PERMISSIONS;

        $User = new User($this->getUser());
        $Logo = new Logo();

        $this->set('systemname', $systemname);
        $this->set('permissions', $permissions);
        $this->set('hasRootPrivileges', $this->hasRootPrivileges);
        $this->set('user', [
            'id'         => $User->getId(),
            'full_name'  => $User->getFullName(),
            'avatar_url' => $User->getUserAvatar(),
            'timezone'   => $User->getTimezone(),
            'dateformat' => $User->getDateformat(),
        ]);
        $this->set('logos', [
            'getHeaderLogoForHtml' => $Logo->getHeaderLogoForHtml()
        ]);

        $this->viewBuilder()->setOption('serialize', ['systemname', 'permissions', 'hasRootPrivileges', 'user', 'logos']);
    }
}
