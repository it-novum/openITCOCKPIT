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

use App\Model\Entity\FilterBookmark;
use App\Model\Table\FilterBookmarksTable;
use Cake\Http\Exception\MethodNotAllowedException;
use Cake\Http\Exception\NotFoundException;
use Cake\Http\Exception\InternalErrorException;
use Cake\ORM\TableRegistry;
use itnovum\openITCOCKPIT\Core\UUID;
use itnovum\openITCOCKPIT\Core\ValueObjects\User;

/**
 * Class FilterBookmarksController
 * @package App\Controller
 */
class FilterBookmarksController extends AppController {

    public function index() {
        if (!$this->isApiRequest()) {
            throw new MethodNotAllowedException();
        }
        $filterType = $this->request->getQuery('type');
        if (!$filterType) {
            throw new NotFoundException('Missing type param');
        }
        /** @var User $user */
        $User = new User($this->getUser());
        /** @var FilterBookmarksTable $FilterBookmarksTable */
        $FilterBookmarksTable = TableRegistry::getTableLocator()->get('FilterBookmarks');
        $filterBookmarks = $FilterBookmarksTable->getFilterByUser($User->getId(), $filterType);
        $this->set('bookmarks', $filterBookmarks);
        $this->viewBuilder()->setOption('serialize', ['bookmarks']);
    }

    public function add() {
        if (!$this->isApiRequest()) {
            throw new MethodNotAllowedException();
        }
        $data = [];
        $data = $this->request->getData();
        $filterType = $this->request->getQuery('type');
        if (!$filterType) {
            throw new NotFoundException('Missing type param');
        }
        $data['filter'] = json_encode($data['filter']);

        if ($this->request->is('post')) {
            if(empty($data['name'])) {
                throw new NotFoundException(__('This field cannot be left empty'));
            }
        }
        /** @var User $user */
        $User = new User($this->getUser());
        /** @var FilterBookmarksTable $FilterBookmarksTable */
        $FilterBookmarksTable = TableRegistry::getTableLocator()->get('FilterBookmarks');
        //existing bookmark returns
        if(!empty($data['id']) && !empty($data['uuid']) && !empty($data['user_id']) && $data['user_id'] == $User->getId()){
            /** @var FilterBookmark $FilterBookmark */
            $FilterBookmark = $FilterBookmarksTable->get($data['id']);
            //if a existing bookmark with the same name, than update the existing bookmark
            if($FilterBookmark->get('name') == $data['name']) {
                $FilterBookmark = $FilterBookmarksTable->patchEntity($FilterBookmark, $data);
            }
            //if existing bookmark with new name, then create new bookmark (new id, new uuid) from existing bookmark
            else {
                unset($data['id']);
                $data['uuid'] = UUID::v4();
                $host = $this->request->getUri()->getHost();
                $scheme = $this->request->getUri()->getScheme();
                $data['url'] = sprintf("%s://%s", $scheme, $host);  //$scheme . '://'. $host;
                $FilterBookmark = $FilterBookmarksTable->newEntity($data);
            }
        }
        // create complete new bookmark
        else {
            $data['uuid'] = UUID::v4();
            $data['filter_entity'] = $filterType;
            $data['name'] = $this->request->getData('name');
            $data['user_id'] = $User->getId();
            $data['filter'] = json_encode($this->request->getData('filter'));
            $host = $this->request->getUri()->getHost();
            $scheme = $this->request->getUri()->getScheme();
            $data['url'] = sprintf("%s://%s", $scheme, $host);
            /** @var FilterBookmark $FilterBookmark */
            $FilterBookmark = $FilterBookmarksTable->newEntity($data);
        }
        //if bookmark should be default, look for and unset old default
        if(!empty($data['default'])) {
            $default = $FilterBookmarksTable->getDefaultFilterByUser($User->getId(), $filterType);
            if (!empty($default)) {
                $FilterBookmarksTable->patchEntity($default, ['default' => false]);
                $FilterBookmarksTable->save($default);
            }
        }
        $FilterBookmarksTable->save($FilterBookmark);
        if ($FilterBookmark->hasErrors()) {
            throw new InternalErrorException('Error at save');
        }
        $filterBookmarks = $FilterBookmarksTable->getFilterByUser($User->getId(), $filterType);
        $this->set('bookmarks', $filterBookmarks);
        $this->viewBuilder()->setOption('serialize', ['bookmarks']);
    }


    public function default() {
        if (!$this->isApiRequest()) {
            throw new MethodNotAllowedException();
        }
        $filterType = $this->request->getQuery('type');
        if (!$filterType) {
            throw new NotFoundException('Missing type param');
        }
        /** @var FilterBookmarksTable $FilterBookmarksTable */
        $FilterBookmarksTable = TableRegistry::getTableLocator()->get('FilterBookmarks');
        $filter = $this->request->getData('filter');
        if (!empty($filter)) {
            /** @var FilterBookmark $FilterBookmark */
            $FilterBookmark = $FilterBookmarksTable->getFilterByUuid($filter);
        } else {
            /** @var User $User */
            $User = new User($this->getUser());
            /** @var FilterBookmark $FilterBookmark */
            $FilterBookmark = $FilterBookmarksTable->getDefaultFilterByUser($User->getId(), $filterType);
        }
        $this->set('bookmark', $FilterBookmark);
        $this->viewBuilder()->setOption('serialize', ['bookmark']);
    }

    public function delete() {
        if (!$this->isApiRequest()) {
            throw new MethodNotAllowedException();
        }
        $filterType = $this->request->getQuery('type');
        if (!$filterType) {
            throw new NotFoundException('Missing type param');
        }
        /** @var User $User */
        $User = new User($this->getUser());
        $data = $this->request->getData();
        if (empty($data['id'])) {
            throw new NotFoundException('No id to delete');
        }
        /** @var FilterBookmarksTable $FilterBookmarksTable */
        $FilterBookmarksTable = TableRegistry::getTableLocator()->get('FilterBookmarks');
        $FilterBookmark = $FilterBookmarksTable->get($data['id']);
        $FilterBookmarksTable->delete($FilterBookmark);
        if ($FilterBookmark->hasErrors()) {
            throw new InternalErrorException('Error at delete');
        }
        $filterBookmarks = $FilterBookmarksTable->getFilterByUser($User->getId(), $filterType);
        $this->set('bookmarks', $filterBookmarks);
        $this->viewBuilder()->setOption('serialize', ['bookmarks']);
    }

}
