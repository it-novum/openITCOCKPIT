<?php
// Copyright (C) <2015-present>  <it-novum GmbH>
//
// This file is licensed under the terms of the openITCOCKPIT Enterprise Edition license agreement.
// The license agreement and license key were sent with the order confirmation.

declare(strict_types=1);

namespace MSTeamsModule\Controller;

use App\Controller\AppController as BaseController;
use Cake\ORM\TableRegistry;
use MSTeamsModule\Model\Table\MsteamsSettingsTable;

class MSTeamsSettingsController extends BaseController {
    public function index(): void {

        if (!$this->isAngularJsRequest()) {
            //Only ship html template
            return;
        }

        /** @var MsteamsSettingsTable $MsteamsSettingsTable */
        $MsteamsSettingsTable = TableRegistry::getTableLocator()->get('MSTeamsModule.MsteamsSettings');
        $teamsSettings = $MsteamsSettingsTable->getTeamsSettings();

        if ($this->request->is('get')) {
            $this->set('teamsSettings', $teamsSettings);
            $this->viewBuilder()->setOption('serialize', [
                'teamsSettings'
            ]);
            return;
        }

        if ($this->request->is('post')) {
            $entity = $MsteamsSettingsTable->getTeamsSettingsEntity();
            $entity = $MsteamsSettingsTable->patchEntity($entity, $this->request->getData(null, []));

            $MsteamsSettingsTable->save($entity);
            if ($entity->hasErrors()) {
                $this->response = $this->response->withStatus(400);
                $this->set('error', $entity->getErrors());
                $this->viewBuilder()->setOption('serialize', ['error']);
                return;
            }

            $this->set('teamsSettings', $entity);
            $this->viewBuilder()->setOption('serialize', [
                'teamsSettings'
            ]);
        }
    }
}
