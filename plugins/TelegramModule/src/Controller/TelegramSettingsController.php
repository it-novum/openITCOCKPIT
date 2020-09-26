<?php

declare(strict_types=1);

namespace TelegramModule\Controller;

use Cake\ORM\TableRegistry;
use TelegramModule\Model\Table\TelegramSettingsTable;

/**
 * TelegramSettings Controller
 *
 * @method \TelegramModule\Model\Entity\TelegramSetting[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class TelegramSettingsController extends AppController {

    public function index() {

        if (!$this->isAngularJsRequest()) {
            //Only ship html template
            return;
        }

        /** @var TelegramSettingsTable $TelegramSettingsTable */
        $TelegramSettingsTable = TableRegistry::getTableLocator()->get('TelegramModule.TelegramSettings');
        $telegramSettings = $TelegramSettingsTable->getTelegramSettings();

        if ($this->request->is('get')) {
            $this->set('telegramSettings', $telegramSettings);
            $this->viewBuilder()->setOption('serialize', [
                'telegramSettings'
            ]);
            return;
        }

        if ($this->request->is('post')) {
            $entity = $TelegramSettingsTable->getTelegramSettingsEntity();
            $entity = $TelegramSettingsTable->patchEntity($entity, $this->request->getData(null, []));

            $TelegramSettingsTable->save($entity);
            if ($entity->hasErrors()) {
                $this->response = $this->response->withStatus(400);
                $this->set('error', $entity->getErrors());
                $this->viewBuilder()->setOption('serialize', ['error']);
                return;
            }

            $this->set('telegramSettings', $entity);
            $this->viewBuilder()->setOption('serialize', [
                'telegramSettings'
            ]);
        }
    }
}
