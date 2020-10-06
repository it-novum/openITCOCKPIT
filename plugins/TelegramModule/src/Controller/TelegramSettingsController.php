<?php

declare(strict_types=1);

namespace TelegramModule\Controller;

use App\Model\Table\SystemsettingsTable;
use Cake\ORM\TableRegistry;
use TelegramModule\Lib\TelegramActions;
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

        if ($telegramSettings->get('external_webhook_domain') == "") {
            /** @var SystemsettingsTable $SystemsettingsTable */
            $SystemsettingsTable = TableRegistry::getTableLocator()->get('Systemsettings');
            $result = $SystemsettingsTable->getSystemsettingByKey('SYSTEM.ADDRESS');
            $telegramSettings->set('external_webhook_domain', sprintf('https://%s', $result->get('value')));
        }

        if ($this->request->is('get')) {
            $this->set('telegramSettings', $telegramSettings);
            $this->viewBuilder()->setOption('serialize', [
                'telegramSettings'
            ]);
            return;
        }

        if ($this->request->is('post')) {
            $entity = $TelegramSettingsTable->getTelegramSettingsEntity();
            $originalTwoWaySetting = $entity->get('two_way');
            $entity = $TelegramSettingsTable->patchEntity($entity, $this->request->getData(null, []));

            if (($originalTwoWaySetting || $originalTwoWaySetting == 1) && (!$entity->get('two_way') || $entity->get('two_way') == 0) && $entity->get('token') != "") {
                //disable Telegram bot webhook
                $TelegramActions = new TelegramActions($entity->get('token'));
                $TelegramActions->disableWebhook();
            } else if (($entity->get('two_way') || $entity->get('two_way') == 1) && $entity->get('token') != "" && $entity->get('external_webhook_domain') != "" && $entity->get('webhook_api_key') != "") {
                //enable/update Telegram bot webhook
                $TelegramActions = new TelegramActions($entity->get('token'));
                $webhookUrl = sprintf('%s/telegram_module/telegram_webhook/notify.json?apikey=%s', $entity->get('external_webhook_domain'), $entity->get('webhook_api_key'));
                $result = $TelegramActions->enableWebhook($webhookUrl);

                if (!$result || $result === "") {
                    $entity->set('two_way', false);
                }
            }

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
