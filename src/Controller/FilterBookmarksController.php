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
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\MethodNotAllowedException;
use Cake\Http\Exception\NotFoundException;
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
        $plugin = $this->request->getQuery('plugin', null);
        $controller = $this->request->getQuery('controller', null);
        $action = $this->request->getQuery('action', null);
        if ($controller === null || $action === null) {
            throw new BadRequestException('Missing parameter');
        }

        $queryFilter = $this->request->getQuery('queryFilter', null);

        /** @var User $User */
        $User = new User($this->getUser());

        /** @var FilterBookmarksTable $FilterBookmarksTable */
        $FilterBookmarksTable = TableRegistry::getTableLocator()->get('FilterBookmarks');

        if ($queryFilter) {
            // User want's to load a specific filter by UUID - filter via URL param
            $bookmark = $FilterBookmarksTable->getFilterByUuid($queryFilter);

            if (!empty($bookmark)) {
                $bookmark = $bookmark->toArray();
                if ($bookmark['user_id'] !== $User->getId()) {
                    // This filter belongs to another user
                    unset($bookmark['user_id']);
                    unset($bookmark['uuid']);
                    $bookmark['via_sharing'] = true;
                }
            }
        }
        $this->set('bookmark', $bookmark ?? null);
        $filterBookmarks = $FilterBookmarksTable->getFilterByUser($User->getId(), $plugin, $controller, $action);
        $this->set('bookmarks', $filterBookmarks);
        $this->viewBuilder()->setOption('serialize', ['bookmarks', 'bookmark']);
    }

    public function add() {
        if (!$this->isApiRequest() || !$this->request->is('post')) {
            throw new MethodNotAllowedException();
        }

        /** @var FilterBookmarksTable $FilterBookmarksTable */
        $FilterBookmarksTable = TableRegistry::getTableLocator()->get('FilterBookmarks');

        $data = $this->request->getData(null, []);
        $data['uuid'] = UUID::v4();

        $bookmark = $FilterBookmarksTable->newEmptyEntity();
        $bookmark = $FilterBookmarksTable->patchEntity($bookmark, $data);

        $FilterBookmarksTable->save($bookmark);
        if ($bookmark->hasErrors()) {
            $this->response = $this->response->withStatus(400);
            $this->set('error', $bookmark->getErrors());
            $this->viewBuilder()->setOption('serialize', ['error']);
            return;
        }

        $this->set('bookmark', $bookmark);
        $this->viewBuilder()->setOption('serialize', ['bookmark']);
    }

    public function edit($id) {
        if (!$this->isApiRequest() || !$this->request->is('post')) {
            throw new MethodNotAllowedException();
        }

        /** @var FilterBookmarksTable $FilterBookmarksTable */
        $FilterBookmarksTable = TableRegistry::getTableLocator()->get('FilterBookmarks');

        $User = new User($this->getUser());

        try {
            $bookmark = $FilterBookmarksTable->getByIdAndUserId($id, $User->getId());
        } catch (RecordNotFoundException $e) {
            throw new NotFoundException();
        }

        $data = $this->request->getData(null, []);
        $bookmark->setAccess('id', false);
        $bookmark->setAccess('uuid', false);
        $bookmark->setAccess('user_id', false);

        $bookmark = $FilterBookmarksTable->patchEntity($bookmark, $data);

        $FilterBookmarksTable->save($bookmark);
        if ($bookmark->hasErrors()) {
            $this->response = $this->response->withStatus(400);
            $this->set('error', $bookmark->getErrors());
            $this->viewBuilder()->setOption('serialize', ['error']);
            return;
        }

        $this->set('bookmark', $bookmark);
        $this->viewBuilder()->setOption('serialize', ['bookmark']);
    }

    public function save() {
        if (!$this->isApiRequest() && !$this->request->is('post')) {
            throw new MethodNotAllowedException();
        }

        $data = $this->request->getData();
        $data['filter'] = json_encode($this->request->getData('filter', '{}'));

        /** @var User $user */
        $User = new User($this->getUser());

        /** @var FilterBookmarksTable $FilterBookmarksTable */
        $FilterBookmarksTable = TableRegistry::getTableLocator()->get('FilterBookmarks');

        // Do we have an id? If yes, try to update existing bookmark
        if (!empty($data['id'])) {
            try {
                // Update existing bookmark
                $bookmark = $FilterBookmarksTable->getByIdAndUserId($data['id'], $User->getId());
                $bookmark->setAccess('id', false);
                $bookmark->setAccess('uuid', false);
                $bookmark->setAccess('user_id', false);

                if (isset($data['name']) && $data['name'] !== $bookmark->name) {
                    // User has renamed this bookmark - so we create a complete new filter
                    unset($bookmark);
                }

            } catch (RecordNotFoundException $e) {
                // No bookmark found for given id and user_id - create a new one
            }
        }


        if (!isset($bookmark)) {
            // Create new bookmark
            unset($data['id']);
            $data['uuid'] = UUID::v4();
            $data['user_id'] = $User->getId();

            $bookmark = $FilterBookmarksTable->newEntity($data);
            $bookmark->setNew(true);
        }

        // Update existing bookmark or createa a new one
        $FilterBookmarksTable->patchEntity($bookmark, $data);

        $FilterBookmarksTable->save($bookmark);
        if ($bookmark->hasErrors()) {
            $this->response = $this->response->withStatus(400);
            $this->set('error', $bookmark->getErrors());
            $this->viewBuilder()->setOption('serialize', ['error']);
            return;
        }

        //if bookmark should be default, look for and unset old default
        if ($bookmark->default) {
            $FilterBookmarksTable->updateAll([
                'default' => false
            ], [
                'id !='      => $bookmark->id,
                'user_id'    => $User->getId(),
                'plugin'     => $data['plugin'],
                'controller' => $data['controller'],
                'action'     => $data['action']
            ]);
        }
        $bookmarks = $FilterBookmarksTable->getFilterByUser($User->getId(), $data['plugin'], $data['controller'], $data['action']);
        $this->set('bookmarks', $bookmarks);
        $this->set('bookmark', $bookmark);
        $this->viewBuilder()->setOption('serialize', ['bookmarks', 'bookmark']);
    }

    public function delete() {
        if (!$this->isApiRequest()) {
            throw new MethodNotAllowedException();
        }

        /** @var User $User */
        $User = new User($this->getUser());

        $data = $this->request->getData();
        if (empty($data['id'])) {
            throw new NotFoundException('No id given');
        }

        /** @var FilterBookmarksTable $FilterBookmarksTable */
        $FilterBookmarksTable = TableRegistry::getTableLocator()->get('FilterBookmarks');

        if (!$FilterBookmarksTable->existsById($data['id'])) {
            throw new NotFoundException();
        }

        /** @var FilterBookmark $bookmark */
        $bookmark = $FilterBookmarksTable->get($data['id']);

        if ($User->getId() != $bookmark->user_id) {
            throw new MethodNotAllowedException('Deletion not allowed, wrong User');
        }

        $FilterBookmarksTable->delete($bookmark);

        $filterBookmarks = $FilterBookmarksTable->getFilterByUser($User->getId(), $data['plugin'], $data['controller'], $data['action']);
        $this->set('bookmarks', $filterBookmarks);
        $this->viewBuilder()->setOption('serialize', ['bookmarks']);
    }

    public function directive() {
        // Only ship HTML template
    }

}
