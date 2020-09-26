<?php

declare(strict_types=1);

namespace TelegramModule\Command;

use App\Model\Table\HostsTable;
use App\Model\Table\ProxiesTable;
use App\Model\Table\ServicesTable;
use Cake\Console\Arguments;
use Cake\Console\Command;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\ORM\TableRegistry;
use itnovum\openITCOCKPIT\Core\System\Gearman;
use itnovum\openITCOCKPIT\Exceptions\HostNotFoundException;
use itnovum\openITCOCKPIT\Exceptions\ServiceNotFoundException;
use TelegramBot\Api\BotApi;
use TelegramBot\Api\Exception;
use TelegramBot\Api\InvalidArgumentException;
use TelegramModule\Model\Table\TelegramChatsTable;
use TelegramModule\Model\Table\TelegramSettingsTable;

/**
 * TelegramModule command.
 */
class TelegramProcessUpdatesCommand extends Command {


    /**
     * @var string
     */
    private $token = "";

    /**
     * Hook method for defining this command's option parser.
     *
     * @see https://book.cakephp.org/3.0/en/console-and-shells/commands.html#defining-arguments-and-options
     *
     * @param \Cake\Console\ConsoleOptionParser $parser The parser to be defined
     * @return \Cake\Console\ConsoleOptionParser The built parser.
     */
    public function buildOptionParser(ConsoleOptionParser $parser): ConsoleOptionParser {
        $parser = parent::buildOptionParser($parser);

        $parser->addOptions([
            'api-token' => ['help' => __d('oitc_console', 'Overwrites the stored Telegram bot HTTP API token')],
        ]);

        return $parser;
    }

    /**
     * Implement this method with your command's logic.
     *
     * @param Arguments $args
     * @param ConsoleIo $io
     * @return int
     * @throws Exception
     * @throws InvalidArgumentException
     * @throws \itnovum\openITCOCKPIT\ApiShell\Exceptions\MissingParameterExceptions
     */
    public function execute(Arguments $args, ConsoleIo $io) {
        /** @var TelegramSettingsTable $TelegramSettingsTable */
        $TelegramSettingsTable = TableRegistry::getTableLocator()->get('TelegramModule.TelegramSettings');
        $telegramSettings = $TelegramSettingsTable->getTelegramSettings();

        /** @var TelegramChatsTable $TelegramChatsTable */
        $TelegramChatsTable = TableRegistry::getTableLocator()->get('TelegramModule.TelegramChats');

        /** @var ProxiesTable $ProxiesTable */
        $ProxiesTable = TableRegistry::getTableLocator()->get('Proxies');
        $proxySettings = $ProxiesTable->getSettings();

        $this->token = $telegramSettings->get('token');
        $this->validateOptions($args);

        if (!$this->token || $this->token == '') {
            echo __d('oitc_console', 'No telegram bot token configured!') . PHP_EOL;
            return 1;
        }

        $bot = new BotApi($this->token);

        if ($telegramSettings->get('use_proxy') && $proxySettings['enabled'] == 1) {
            $bot->setProxy(sprintf('%s:%s', $proxySettings['ipaddress'], $proxySettings['port']));
        }

        $updateOffset = 0;
        if ($telegramSettings->get('last_update_id') > 0) {
            $updateOffset = $telegramSettings->get('last_update_id');
        }

        $botWelcomeText = __d('oitc_console', "Nice to see you %s %s, you have successfully enabled openITCOCKPIT notifications in this chat.");
        $botHelpText = __d('oitc_console', "Here are some instructions and commands for using this bot.

        *Bot control commands*:

        `/start` enables openITCOCKPIT notifications
        `/stop` disables openITCOCKPIT notifications
        `/help` shows this help text again
        `/delete` deletes this bot connection in openITCOCKPIT

        _Note: Interactions with this bot are only processed every minute. Therefore, there may be slight delays when executing commands._
        ");

        $updates = $bot->getUpdates($updateOffset + 1);
        foreach ($updates as $update) {
            if ($updateOffset < $update->getUpdateId()) {
                $updateOffset = $update->getUpdateId();
            }

            //print_r($update);
            if ($update->getMessage()) {
                switch (trim($update->getMessage()->getText())) {
                    case '/start':
                        if ($TelegramChatsTable->existsByChatId($update->getMessage()->getChat()->getId())) {
                            $TelegramChat = $TelegramChatsTable->getByChatId($update->getMessage()->getChat()->getId());
                            $TelegramChatsTable->patchEntity($TelegramChat, ['enabled' => true]);
                        } else {
                            $TelegramChat = $TelegramChatsTable->newEntity([
                                'chat_id'               => $update->getMessage()->getChat()->getId(),
                                'enabled'               => true,
                                'started_from_username' => $update->getMessage()->getFrom()->getUsername()
                            ]);
                        }
                        $TelegramChatsTable->save($TelegramChat);

                        $bot->sendMessage(
                            $update->getMessage()->getChat()->getId(),
                            sprintf($botWelcomeText, $update->getMessage()->getFrom()->getFirstName(), $update->getMessage()->getFrom()->getLastName()),
                            "Markdown"
                        );
                        $bot->sendMessage(
                            $update->getMessage()->getChat()->getId(),
                            sprintf($botHelpText, $update->getMessage()->getFrom()->getFirstName(), $update->getMessage()->getFrom()->getLastName()),
                            "Markdown"
                        );
                        break;

                    case '/stop':
                        if ($TelegramChatsTable->existsByChatId($update->getMessage()->getChat()->getId())) {
                            $TelegramChat = $TelegramChatsTable->getByChatId($update->getMessage()->getChat()->getId());
                            $TelegramChatsTable->patchEntity($TelegramChat, ['enabled' => false]);
                            $TelegramChatsTable->save($TelegramChat);
                        }
                        break;

                    case '/help':
                        $bot->sendMessage(
                            $update->getMessage()->getChat()->getId(),
                            sprintf($botHelpText, $update->getMessage()->getFrom()->getFirstName(), $update->getMessage()->getFrom()->getLastName()),
                            "Markdown"
                        );
                        break;

                    case '/delete':
                        if ($TelegramChatsTable->existsByChatId($update->getMessage()->getChat()->getId())) {
                            $TelegramChat = $TelegramChatsTable->getByChatId($update->getMessage()->getChat()->getId());
                            $TelegramChatsTable->delete($TelegramChat);
                        }
                        break;
                }
            }

            if ($update->getCallbackQuery()) {
                $callbackData = $update->getCallbackQuery()->getData();

                if (str_starts_with($callbackData, 'ack_host_')) {
                    $full_ack_user_name = sprintf("%s %s", $update->getCallbackQuery()->getFrom()->getFirstName(), $update->getCallbackQuery()->getFrom()->getLastName());
                    if ($this->acknowledgeHost(str_replace('ack_host_', '', $callbackData), $full_ack_user_name)) {
                        $bot->sendMessage(
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
                        $bot->sendMessage(
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

        if (sizeof($updates) > 0) {
            if ($telegramSettings->get('token') == '') {
                $telegramSettings->set('token', $this->token);
            }
            $TelegramSettingsTable->patchEntity($telegramSettings, ['last_update_id' => $updateOffset]);
            $TelegramSettingsTable->save($telegramSettings);
            if ($telegramSettings->hasErrors()) {
                print_r($telegramSettings->getErrors());
            }
        }
    }

    /**
     * @param Arguments $args
     */
    private function validateOptions(Arguments $args) {
        if ($args->hasOption('api-token') && $args->getOption('api-token') != '') {
            $this->token = $args->getOption('api-token');
        }
    }

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
}
