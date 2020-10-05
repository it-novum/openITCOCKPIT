<?php

namespace TelegramModule\Lib;


use App\Model\Table\HostsTable;
use App\Model\Table\ProxiesTable;
use App\Model\Table\ServicesTable;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\ORM\TableRegistry;
use itnovum\openITCOCKPIT\Core\System\Gearman;
use itnovum\openITCOCKPIT\Exceptions\HostNotFoundException;
use itnovum\openITCOCKPIT\Exceptions\ServiceNotFoundException;
use TelegramBot\Api\BotApi;
use TelegramBot\Api\Types\Update;
use TelegramModule\Model\Table\TelegramChatsTable;
use TelegramModule\Model\Table\TelegramSettingsTable;

class TelegramActions {

    /** @var TelegramChatsTable */
    private $TelegramChatsTable;

    /** @var TelegramSettingsTable */
    private $TelegramSettingsTable;

    private $telegramSettings = [];
    private $proxySettings = [];
    private $updateOffset = 0;
    private $token = '';

    /**
     * @var BotApi
     */
    private $bot = BotApi::class;

    public function __construct(string $tokenOverwrite = null) {
        $this->TelegramChatsTable = TableRegistry::getTableLocator()->get('TelegramModule.TelegramChats');
        $this->TelegramSettingsTable = TableRegistry::getTableLocator()->get('TelegramModule.TelegramSettings');
        $this->telegramSettings = $this->TelegramSettingsTable->getTelegramSettings();

        /** @var ProxiesTable $ProxiesTable */
        $ProxiesTable = TableRegistry::getTableLocator()->get('Proxies');
        $this->proxySettings = $ProxiesTable->getSettings();

        $this->token = $tokenOverwrite != null ? $tokenOverwrite : $this->telegramSettings->get('token');
        if (!$this->token || $this->token == '') {
            echo __d('oitc_console', 'No telegram bot token configured!') . PHP_EOL;
            return 1;
        }

        $this->bot = new BotApi($this->token);

        if ($this->telegramSettings->get('use_proxy') && $this->proxySettings['enabled'] == 1) {
            $this->bot->setProxy(sprintf('%s:%s', $this->proxySettings['ipaddress'], $this->proxySettings['port']));
        }

        if ($this->telegramSettings->get('last_update_id') > 0) {
            $this->updateOffset = $this->telegramSettings->get('last_update_id');
        }
    }

    /**
     * @return bool
     */
    public function isTwoWayWebhookEnabled() {
        return $this->telegramSettings->get('two_way') == 1;
    }

    /**
     * @param string $completeWebhookUrl
     * @return string
     * @throws \TelegramBot\Api\Exception
     */
    public function enableWebhook(string $completeWebhookUrl) {
        return $this->bot->setWebhook($completeWebhookUrl);
    }

    public function disableWebhook() {
        $this->bot->deleteWebhook();
    }

    /**
     * @return array|Update[]
     */
    public function getUpdates() {
        try {
            return $this->bot->getUpdates($this->getUpdateOffset() + 1);
        } catch (\TelegramBot\Api\Exception | \TelegramBot\Api\InvalidArgumentException $exception) {
            return [];
        }
    }

    /**
     * @param array $updateArray
     * @return bool|Update
     */
    public function parseUpdate(array $updateArray) {
        $update = new Update();
        $update = $update::fromResponse($updateArray);

        return $update;
    }

    /**
     * @param $updates
     * @throws HostNotFoundException
     * @throws ServiceNotFoundException
     * @throws \TelegramBot\Api\Exception
     * @throws \TelegramBot\Api\InvalidArgumentException
     */
    public function processUpdates($updates) {
        foreach ($updates as $update) {
            $this->processUpdate($update);
        }

        if (sizeof($updates) > 0) {
            if ($this->telegramSettings->get('token') == '') {
                $this->telegramSettings->set('token', $this->token);
            }
            $this->TelegramSettingsTable->patchEntity($this->telegramSettings, ['last_update_id' => $this->getUpdateOffset()]);
            $this->TelegramSettingsTable->save($this->telegramSettings);
            if ($this->telegramSettings->hasErrors()) {
                print_r($this->telegramSettings->getErrors());
            }
        }
    }

    /**
     * @param Update $update
     * @return bool
     * @throws \TelegramBot\Api\Exception
     * @throws \TelegramBot\Api\InvalidArgumentException
     */
    private function isChatAuthorized(Update $update) {
        if ($this->TelegramChatsTable->existsByChatId($update->getMessage()->getChat()->getId())) {
            return true;
        }

        $this->bot->sendMessage(
            $update->getMessage()->getChat()->getId(),
            sprintf($this->getText('welcome'), $update->getMessage()->getFrom()->getFirstName(), $update->getMessage()->getFrom()->getLastName()),
            "Markdown"
        );
        $this->bot->sendMessage(
            $update->getMessage()->getChat()->getId(),
            sprintf($this->getText('auth'), $update->getMessage()->getFrom()->getFirstName(), $update->getMessage()->getFrom()->getLastName()),
            "Markdown"
        );
        return false;
    }

    /**
     * @param Update $update
     * @throws HostNotFoundException
     * @throws ServiceNotFoundException
     * @throws \TelegramBot\Api\Exception
     * @throws \TelegramBot\Api\InvalidArgumentException
     */
    public function processUpdate(Update $update) {
        if ($this->updateOffset < $update->getUpdateId()) {
            $this->updateOffset = $update->getUpdateId();
        }

        //print_r($update);
        if ($update->getMessage()) {
            switch (trim($update->getMessage()->getText())) {
                case '/auth':
                    $this->bot->sendMessage(
                        $update->getMessage()->getChat()->getId(),
                        sprintf($this->getText('auth'), $update->getMessage()->getFrom()->getFirstName(), $update->getMessage()->getFrom()->getLastName()),
                        "Markdown"
                    );
                    break;

                case '/start':
                    if ($this->isChatAuthorized($update)) {
                        $TelegramChat = $this->TelegramChatsTable->getByChatId($update->getMessage()->getChat()->getId());
                        $this->TelegramChatsTable->patchEntity($TelegramChat, ['enabled' => true]);

                        $this->TelegramChatsTable->save($TelegramChat);

                        $this->bot->sendMessage(
                            $update->getMessage()->getChat()->getId(),
                            sprintf($this->getText('successfully_enabled'), $update->getMessage()->getFrom()->getFirstName(), $update->getMessage()->getFrom()->getLastName()),
                            "Markdown"
                        );
                    }

                    break;

                case '/stop':
                    if ($this->isChatAuthorized($update)) {
                        $TelegramChat = $this->TelegramChatsTable->getByChatId($update->getMessage()->getChat()->getId());
                        $this->TelegramChatsTable->patchEntity($TelegramChat, ['enabled' => false]);
                        $this->TelegramChatsTable->save($TelegramChat);

                        $this->bot->sendMessage(
                            $update->getMessage()->getChat()->getId(),
                            sprintf($this->getText('successfully_disabled'), $update->getMessage()->getFrom()->getFirstName(), $update->getMessage()->getFrom()->getLastName()),
                            "Markdown"
                        );
                    }
                    break;

                case '/help':
                    $this->bot->sendMessage(
                        $update->getMessage()->getChat()->getId(),
                        sprintf($this->getText('help'), $update->getMessage()->getFrom()->getFirstName(), $update->getMessage()->getFrom()->getLastName()),
                        "Markdown"
                    );
                    break;

                case '/delete':
                    if ($this->isChatAuthorized($update)) {
                        $TelegramChat = $this->TelegramChatsTable->getByChatId($update->getMessage()->getChat()->getId());
                        $this->TelegramChatsTable->delete($TelegramChat);

                        $this->bot->sendMessage(
                            $update->getMessage()->getChat()->getId(),
                            sprintf($this->getText('deleted_successfully'), $update->getMessage()->getFrom()->getFirstName(), $update->getMessage()->getFrom()->getLastName()),
                            "Markdown"
                        );
                    }
                    break;
            }

            if (str_starts_with(trim($update->getMessage()->getText()), '/auth ')) {
                $providedAuthKey = str_replace('/auth ', '', trim($update->getMessage()->getText()));
                if ($providedAuthKey === $this->telegramSettings->get('access_key')) {
                    if (!$this->TelegramChatsTable->existsByChatId($update->getMessage()->getChat()->getId())) {
                        $TelegramChat = $this->TelegramChatsTable->newEntity([
                            'chat_id'               => $update->getMessage()->getChat()->getId(),
                            'enabled'               => false,
                            'started_from_username' => $update->getMessage()->getFrom()->getUsername()
                        ]);
                        $this->TelegramChatsTable->save($TelegramChat);

                        $this->bot->sendMessage(
                            $update->getMessage()->getChat()->getId(),
                            sprintf($this->getText('auth_successful'), $update->getMessage()->getFrom()->getFirstName(), $update->getMessage()->getFrom()->getLastName()),
                            "Markdown"
                        );
                        $this->bot->sendMessage(
                            $update->getMessage()->getChat()->getId(),
                            sprintf($this->getText('help'), $update->getMessage()->getFrom()->getFirstName(), $update->getMessage()->getFrom()->getLastName()),
                            "Markdown"
                        );
                    }
                } else {
                    $this->bot->sendMessage(
                        $update->getMessage()->getChat()->getId(),
                        sprintf($this->getText('auth_unsuccessful'), $update->getMessage()->getFrom()->getFirstName(), $update->getMessage()->getFrom()->getLastName()),
                        "Markdown"
                    );
                }
            }
        }

        if ($update->getCallbackQuery()) {
            $callbackData = $update->getCallbackQuery()->getData();

            if (str_starts_with($callbackData, 'ack_host_')) {
                $full_ack_user_name = sprintf("%s %s", $update->getCallbackQuery()->getFrom()->getFirstName(), $update->getCallbackQuery()->getFrom()->getLastName());
                if ($this->acknowledgeHost(str_replace('ack_host_', '', $callbackData), $full_ack_user_name)) {
                    $this->bot->sendMessage(
                        $update->getCallbackQuery()->getMessage()->getChat()->getId(),
                        sprintf(__d('oitc_console', 'Successfully acknowledged by %s %s.'), $update->getCallbackQuery()->getMessage()->getChat()->getFirstName(), $update->getCallbackQuery()->getMessage()->getChat()->getLastName()),
                        "Markdown",
                        false,
                        $update->getCallbackQuery()->getMessage()->getMessageId()
                    );
                }
            } else if (str_starts_with($callbackData, 'ack_service_')) {
                $full_ack_user_name = sprintf("%s %s", $update->getCallbackQuery()->getFrom()->getFirstName(), $update->getCallbackQuery()->getFrom()->getLastName());
                if ($this->acknowledgeService(str_replace('ack_service_', '', $callbackData), $full_ack_user_name)) {
                    $this->bot->sendMessage(
                        $update->getCallbackQuery()->getMessage()->getChat()->getId(),
                        sprintf(__d('oitc_console', 'Successfully acknowledged by %s %s.'), $update->getCallbackQuery()->getMessage()->getChat()->getFirstName(), $update->getCallbackQuery()->getMessage()->getChat()->getLastName()),
                        "Markdown",
                        false,
                        $update->getCallbackQuery()->getMessage()->getMessageId()
                    );
                }
            }
        }
    }

    /**
     * @return int|mixed|null
     */
    public function getUpdateOffset() {
        return $this->updateOffset;
    }

    /**
     * @param string $hostUuid
     * @param string $author
     * @return bool
     * @throws HostNotFoundException
     */
    private function acknowledgeHost(string $hostUuid, string $author) {
        /** @var HostsTable $HostsTable */
        $HostsTable = TableRegistry::getTableLocator()->get('Hosts');

        //Check if the host exists
        try {
            $host = $HostsTable->getHostByUuid($hostUuid);
        } catch (RecordNotFoundException $e) {
            throw new HostNotFoundException('No host with given uuid found.');
        }

        $GearmanClient = new Gearman();

        $GearmanClient->sendBackground('cmd_external_command', [
            'command'     => 'ACKNOWLEDGE_HOST_PROBLEM',
            'parameters'  => [
                'hostUuid'   => $hostUuid,
                'sticky'     => 1,
                'notify'     => 0, // do not enable
                'persistent' => 1,
                'author'     => $author,
                'comment'    => __('Issue got acknowledged by {0} via Telegram.', $author),
            ],
            'satelliteId' => null
        ]);

        return true;
    }

    /**
     * @param string $serviceUuid
     * @param string $author
     * @return bool
     * @throws ServiceNotFoundException
     */
    private function acknowledgeService(string $serviceUuid, string $author) {
        /** @var HostsTable $HostsTable */
        $HostsTable = TableRegistry::getTableLocator()->get('Hosts');
        /** @var ServicesTable $ServicesTable */
        $ServicesTable = TableRegistry::getTableLocator()->get('Services');

        //Check if the service exists
        try {
            $service = $ServicesTable->getServiceByUuid($serviceUuid);
            $hostUuid = $HostsTable->getHostUuidById($service->get('host_id'));
        } catch (RecordNotFoundException $e) {
            throw new ServiceNotFoundException('No service with given uuid found.');
        }

        $GearmanClient = new Gearman();

        $GearmanClient->sendBackground('cmd_external_command', [
            'command'     => 'ACKNOWLEDGE_SVC_PROBLEM',
            'parameters'  => [
                'hostUuid'    => $hostUuid,
                'serviceUuid' => $serviceUuid,
                'sticky'      => 1,
                'notify'      => 0, // do not enable
                'persistent'  => 1,
                'author'      => $author,
                'comment'     => __('Issue got acknowledged by {0} via Telegram.', $author),
            ],
            'satelliteId' => null
        ]);

        return true;
    }

    /**
     * @param string $key
     * @return string
     */
    public function getText(string $key) {
        switch ($key) {
            case 'welcome':
                return __d('oitc_console', "Nice to see you %s %s");
            case 'successfully_enabled':
                return __d('oitc_console', "You have successfully enabled openITCOCKPIT notifications in this chat.");
            case 'successfully_disabled':
                return __d('oitc_console', "You have successfully disabled openITCOCKPIT notifications in this chat.");
            case 'auth':
                return __d('oitc_console', "If you want to enable openITCOCKPIT notifications in this chat, you have to authorize yourself with the (in openITCOCKPIT) configured API access key.
Use `/auth xxx` to authorize yourself. Replace xxx with the right API access key.");
            case 'auth_successful':
                return __d('oitc_console', 'The authorization was successful. You are now able to use this bot :)');
            case 'auth_unsuccessful':
                return __d('oitc_console', 'Unfortunately the authorization was unsuccessful.');
            case 'deleted_successfully':
                return __d('oitc_console', 'Connection successfully deleted. To use this bot again, you will need to re-authorize it.');
            case 'delay':
                $message = "
_Note: Interactions with this bot are only processed every minute due to the missing webhook configuration. As a result, there may be slight delays in executing commands._";
                if ($this->isTwoWayWebhookEnabled()) {
                    return '';
                }
                return $message;
            case 'help':
                return __d('oitc_console', "Here are some instructions and commands for using this bot.

*Bot control commands*:

`/auth xxx` authorizes yourself to activate the bot usage
`/start` enables openITCOCKPIT notifications
`/stop` disables openITCOCKPIT notifications
`/help` shows this help text again
`/delete` deletes this bot connection in openITCOCKPIT" . $this->getText('delay'));
        }
    }
}
