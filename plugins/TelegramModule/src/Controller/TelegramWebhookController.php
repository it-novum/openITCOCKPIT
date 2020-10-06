<?php

declare(strict_types=1);

namespace TelegramModule\Controller;

use TelegramModule\Lib\TelegramActions;

/**
 * Class TelegramWebhookController
 * @package TelegramModule\Controller
 */
class TelegramWebhookController extends AppController {

    public function notify() {
        $this->set('successful', false);
        $TelegramActions = new TelegramActions();

        if ($TelegramActions->isTwoWayWebhookEnabled()) {
            $update = $TelegramActions->parseUpdate($this->request->getData());

            if ($update !== false) {
                $TelegramActions->processUpdate($update);
                $this->set('successful', true);
            }
        }

        $this->viewBuilder()->setOption('serialize', [
            'successful'
        ]);
    }
}
