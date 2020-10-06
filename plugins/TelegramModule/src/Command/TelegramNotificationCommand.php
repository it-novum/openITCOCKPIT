<?php

declare(strict_types=1);

namespace TelegramModule\Command;

use App\Model\Table\HostsTable;
use App\Model\Table\ProxiesTable;
use App\Model\Table\ServicesTable;
use App\Model\Table\SystemsettingsTable;
use Cake\Console\Arguments;
use Cake\Console\Command;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\ORM\TableRegistry;
use itnovum\openITCOCKPIT\Core\Views\Host;
use itnovum\openITCOCKPIT\Core\Views\HoststatusIcon;
use itnovum\openITCOCKPIT\Core\Views\Service;
use itnovum\openITCOCKPIT\Core\Views\ServicestatusIcon;
use Spatie\Emoji\Emoji;
use TelegramBot\Api\BotApi;
use TelegramBot\Api\Types\Inline\InlineKeyboardMarkup;
use TelegramModule\Model\Table\TelegramChatsTable;
use TelegramModule\Model\Table\TelegramSettingsTable;

/**
 * TelegramModule command.
 */
class TelegramNotificationCommand extends Command {

    private $type = 'host';

    private $hostUuid = '';

    private $serviceUuid = '';

    /**
     * 0 = Up, 1 = Down, 2 = Unreachable
     * 0 = Ok, 1 = Warning, 2 = Critical, 3 = Unknown
     *
     * @var null|int
     */
    private $state = null;

    private $output = '';

    /**
     * PROBLEM", "RECOVERY", "ACKNOWLEDGEMENT", "FLAPPINGSTART", "FLAPPINGSTOP",
     * "FLAPPINGDISABLED", "DOWNTIMESTART", "DOWNTIMEEND", or "DOWNTIMECANCELLED"
     *
     * @var string
     */
    private $notificationtype = '';

    private $ackAuthor = '';

    private $ackComment = '';

    /**
     * @var array
     */
    private $hostColors = [
        0 => '#5cb85c', //Up
        1 => '#d9534f', //Down
        2 => '#adadad' //Unreachable
    ];

    /**
     * @var array
     */
    private $serviceColors = [
        0 => '#5cb85c', //Ok
        1 => '#f0ad4e', //Warning
        2 => '#d9534f', //Critical
        3 => '#adadad' //Unknown
    ];

    /**
     * @var string
     */
    private $baseUrl = '';

    /**
     * @var \TelegramModule\Model\Entity\TelegramSetting
     */
    private $telegramSettings;

    /**
     * @var bool
     */
    private $noEmoji = false;

    /**
     * @var BotApi
     */
    private $bot = BotApi::class;

    /**
     * @var \Cake\Datasource\ResultSetInterface
     */
    private $telegramChats;

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
            'type'             => ['short' => 't', 'help' => __d('oitc_console', 'Type of the notification host or service')],
            'notificationtype' => ['help' => __d('oitc_console', 'Notification type of monitoring engine => $NOTIFICATIONTYPE$ ')],
            'hostuuid'         => ['help' => __d('oitc_console', 'Host uuid you want to send a notification => $HOSTNAME$')],
            'serviceuuid'      => ['help' => __d('oitc_console', 'Service uuid you want to send a notification => $SERVICEDESC$')],
            'state'            => ['help' => __d('oitc_console', 'current host state => $HOSTSTATEID$/$SERVICESTATEID$')],
            'output'           => ['help' => __d('oitc_console', 'host output => $HOSTOUTPUT$/$SERVICEOUTPUT$')],
            'ackauthor'        => ['help' => __d('oitc_console', 'host acknowledgement author => $NOTIFICATIONAUTHOR$')],
            'ackcomment'       => ['help' => __d('oitc_console', 'host acknowledgement comment => $NOTIFICATIONCOMMENT$')],
            'no-emoji'         => ['help' => __d('oitc_console', 'Disable emojis in subject'), 'boolean' => true, 'default' => false]
        ]);

        return $parser;
    }

    /**
     * Implement this method with your command's logic.
     *
     * @param \Cake\Console\Arguments $args The command arguments.
     * @param \Cake\Console\ConsoleIo $io The console io
     * @return null|void|int The exit code or null for success
     * @throws \itnovum\openITCOCKPIT\ApiShell\Exceptions\MissingParameterExceptions
     */
    public function execute(Arguments $args, ConsoleIo $io) {
        $this->validateOptions($args);


        /** @var TelegramSettingsTable $TelegramSettingsTable */
        $TelegramSettingsTable = TableRegistry::getTableLocator()->get('TelegramModule.TelegramSettings');
        $this->telegramSettings = $TelegramSettingsTable->getTelegramSettings();

        /** @var TelegramChatsTable $TelegramChatsTable */
        $TelegramChatsTable = TableRegistry::getTableLocator()->get('TelegramModule.TelegramChats');
        $this->telegramChats = $TelegramChatsTable->getTelegramChats();

        /** @var SystemsettingsTable $SystemsettingsTable */
        $SystemsettingsTable = TableRegistry::getTableLocator()->get('Systemsettings');

        /** @var ProxiesTable $ProxiesTable */
        $ProxiesTable = TableRegistry::getTableLocator()->get('Proxies');
        $proxySettings = $ProxiesTable->getSettings();

        $result = $SystemsettingsTable->getSystemsettingByKey('SYSTEM.ADDRESS');
        $this->baseUrl = sprintf('https://%s', $result->get('value'));

        /** @var HostsTable $HostsTable */
        $HostsTable = TableRegistry::getTableLocator()->get('Hosts');

        try {
            $host = $HostsTable->getHostByUuid($this->hostUuid, false);
        } catch (RecordNotFoundException $e) {
            throw new \RuntimeException(sprintf('Host with uuid "%s" could not be found!', $this->hostUuid));
        }

        $this->bot = new BotApi($this->telegramSettings->get('token'));
        if ($this->telegramSettings->get('use_proxy') && $proxySettings['enabled'] == 1) {
            $this->bot->setProxy(sprintf('%s:%s', $proxySettings['ipaddress'], $proxySettings['port']));
        }

        $Host = new Host($host);

        if ($this->type === 'service') {
            /** @var ServicesTable $ServicesTable */
            $ServicesTable = TableRegistry::getTableLocator()->get('Services');

            try {
                $service = $ServicesTable->getServiceByUuid($this->serviceUuid, false);
            } catch (RecordNotFoundException $e) {
                throw new \RuntimeException(sprintf('Service with uuid "%s" could not be found!', $this->serviceUuid));
            }

            $Service = new Service($service);
            $this->sendServiceNotification($Host, $Service);
            exit(0);
        }


        $this->sendHostNotification($Host);
        exit(0);
    }

    private function sendHostNotification(Host $Host) {
        $HoststatusIcon = new HoststatusIcon($this->state);

        if ($this->isAcknowledgement()) {
            $title = sprintf(
                'Acknowledgement for [%s](%s/#!/hosts/browser/%s) (%s) by %s (Comment: %s)',
                $Host->getHostname(),
                $this->baseUrl,
                $Host->getUuid(),
                $HoststatusIcon->getHumanState(),
                $this->ackAuthor,
                $this->ackComment
            );

            if ($this->noEmoji === false) {
                $title = Emoji::speechBalloon() . ' ' . $title;
            }

            return $this->sendMessage($title);
        }

        if ($this->isDowntimeStart()) {
            $title = sprintf(
                'Downtime started for [%s](%s/#!/hosts/browser/%s)',
                $Host->getHostname(),
                $this->baseUrl,
                $Host->getUuid()
            );

            if ($this->noEmoji === false) {
                $title = Emoji::zzz() . ' ' . $title;
            }
            return $this->sendMessage($title);
        }

        if ($this->isDowntimeEnd()) {
            $title = sprintf(
                'Downtime end for [%s](%s/#!/hosts/browser/%s)',
                $Host->getHostname(),
                $this->baseUrl,
                $Host->getUuid()
            );

            if ($this->noEmoji === false) {
                $title = Emoji::eightSpokedAsterisk() . ' ' . $title;
            }

            return $this->sendMessage($title);
        }

        if ($this->isDowntimeCancelled()) {
            $title = sprintf(
                'Downtime cancelled for [%s](%s/#!/hosts/browser/%s)',
                $Host->getHostname(),
                $this->baseUrl,
                $Host->getUuid()
            );

            if ($this->noEmoji === false) {
                $title = Emoji::wastebasket() . ' ' . $title;
            }
            return $this->sendMessage($title);
        }

        if ($this->isFlappingStart()) {
            $title = sprintf(
                'Flapping started on [%s](%s/#!/hosts/browser/%s)',
                $Host->getHostname(),
                $this->baseUrl,
                $Host->getUuid()
            );

            return $this->sendMessage($title);
        }

        if ($this->isFlappingStop()) {
            $title = sprintf(
                'Flapping stopped on [%s](%s/#!/hosts/browser/%s)',
                $Host->getHostname(),
                $this->baseUrl,
                $Host->getUuid()
            );

            return $this->sendMessage($title);
        }

        if ($this->isFlappingDisabled()) {
            $title = sprintf(
                'Disabled flap detection for [%s](%s/#!/hosts/browser/%s)',
                $Host->getHostname(),
                $this->baseUrl,
                $Host->getUuid()
            );

            return $this->sendMessage($title);
        }

        //Default notification
        $title = sprintf(
            '%s: [%s](%s/#!/hosts/browser/%s) is %s!',
            $this->notificationtype,
            $Host->getHostname(),
            $this->baseUrl,
            $Host->getUuid(),
            $HoststatusIcon->getHumanState()
        );

        if ($this->noEmoji === false) {
            $title = $HoststatusIcon->getEmoji() . ' ' . $title;
        }

        $text = $title . "\n" . $this->output;

        $InlineKeyboardMarkup = new InlineKeyboardMarkup([]);
        if ($HoststatusIcon->getState() !== 0 && $this->telegramSettings->get('two_way')) {
            $InlineKeyboardMarkup->setInlineKeyboard([
                [
                    [
                        'text'          => __d('oitc_console', "Click to acknowledge this issue."),
                        'callback_data' => "ack_host_" . $Host->getUuid()
                    ]
                ]
            ]);
        }

        $this->sendMessage($text, $InlineKeyboardMarkup);
    }

    private function sendServiceNotification(Host $Host, Service $Service) {
        $ServicestatusIcon = new ServicestatusIcon($this->state);

        if ($this->isAcknowledgement()) {
            $title = sprintf(
                'Acknowledgement for service [%s](%s/#!/hosts/browser/%s)/[%s](%s/#!/services/browser/%s) (%s) by %s (Comment: %s)',
                $Host->getHostname(),
                $this->baseUrl,
                $Host->getUuid(),
                $Service->getServicename(),
                $this->baseUrl,
                $Service->getUuid(),
                $ServicestatusIcon->getHumanState(),
                $this->ackAuthor,
                $this->ackComment
            );

            if ($this->noEmoji === false) {
                $title = Emoji::speechBalloon() . ' ' . $title;
            }

            return $this->sendMessage($title);
        }

        if ($this->isDowntimeStart()) {
            $title = sprintf(
                'Downtime start for service [%s](%s/#!/hosts/browser/%s)/[%s](%s/#!/services/browser/%s)',
                $Host->getHostname(),
                $this->baseUrl,
                $Host->getUuid(),
                $Service->getServicename(),
                $this->baseUrl,
                $Service->getUuid()
            );

            if ($this->noEmoji === false) {
                $title = Emoji::zzz() . ' ' . $title;
            }

            return $this->sendMessage($title);
        }

        if ($this->isDowntimeEnd()) {
            $title = sprintf(
                'Downtime end for service [%s](%s/#!/hosts/browser/%s)/[%s](%s/#!/services/browser/%s)',
                $Host->getHostname(),
                $this->baseUrl,
                $Host->getUuid(),
                $Service->getServicename(),
                $this->baseUrl,
                $Service->getUuid()
            );

            if ($this->noEmoji === false) {
                $title = Emoji::eightSpokedAsterisk() . ' ' . $title;
            }

            return $this->sendMessage($title);
        }

        if ($this->isDowntimeCancelled()) {
            $title = sprintf(
                'Downtime cancelled for service [%s](%s/#!/hosts/browser/%s)/[%s](%s/#!/services/browser/%s)',
                $Host->getHostname(),
                $this->baseUrl,
                $Host->getUuid(),
                $Service->getServicename(),
                $this->baseUrl,
                $Service->getUuid()
            );

            if ($this->noEmoji === false) {
                $title = Emoji::wastebasket() . ' ' . $title;
            }

            return $this->sendMessage($title);
        }

        if ($this->isFlappingStart()) {
            $title = sprintf(
                'Flapping started on [%s](%s/#!/hosts/browser/%s)/[%s](%s/#!/services/browser/%s)',
                $Host->getHostname(),
                $this->baseUrl,
                $Host->getUuid(),
                $Service->getServicename(),
                $this->baseUrl,
                $Service->getUuid()
            );

            return $this->sendMessage($title);
        }

        if ($this->isFlappingStop()) {
            $title = sprintf(
                'Flapping stopped on [%s](%s/#!/hosts/browser/%s)/[%s](%s/#!/services/browser/%s)',
                $Host->getHostname(),
                $this->baseUrl,
                $Host->getUuid(),
                $Service->getServicename(),
                $this->baseUrl,
                $Service->getUuid()
            );

            return $this->sendMessage($title);
        }

        if ($this->isFlappingDisabled()) {
            $title = sprintf(
                'Disabled flap detection for [%s](%s/#!/hosts/browser/%s)/[%s](%s/#!/services/browser/%s)',
                $Host->getHostname(),
                $this->baseUrl,
                $Host->getUuid(),
                $Service->getServicename(),
                $this->baseUrl,
                $Service->getUuid()
            );

            return $this->sendMessage($title);
        }

        //Default notification
        $title = sprintf(
            '%s: [%s](%s/#!/services/browser/%s) on [%s](%s/#!/hosts/browser/%s) is %s!',
            $this->notificationtype,
            $Service->getServicename(),
            $this->baseUrl,
            $Service->getUuid(),
            $Host->getHostname(),
            $this->baseUrl,
            $Host->getUuid(),
            $ServicestatusIcon->getHumanState()
        );

        if ($this->noEmoji === false) {
            $title = $ServicestatusIcon->getEmoji() . ' ' . $title;
        }

        $text = $title . "\n" . $this->output;

        $InlineKeyboardMarkup = new InlineKeyboardMarkup([]);
        if ($ServicestatusIcon->getState() !== 0 && $this->telegramSettings->get('two_way')) {
            $InlineKeyboardMarkup->setInlineKeyboard([
                [
                    [
                        'text'          => __d('oitc_console', "Click to acknowledge this issue."),
                        'callback_data' => "ack_service_" . $Service->getUuid()
                    ]
                ]
            ]);
        }

        $this->sendMessage($text, $InlineKeyboardMarkup);
    }

    private function sendMessage($text, $InlineKeyboardMarkup = null) {
        if ($this->telegramChats->count() > 0) {
            $this->telegramChats->each(function ($Chat, $key) use ($text, $InlineKeyboardMarkup) {
                if (is_array($Chat)) {
                    if ($Chat['enabled']) {
                        $this->bot->sendMessage(
                            $Chat['chat_id'],
                            $text,
                            "Markdown",
                            false,
                            null,
                            $InlineKeyboardMarkup
                        );
                    }
                } else {
                    if ($Chat->get('enabled')) {
                        $this->bot->sendMessage(
                            $Chat->get('chat_id'),
                            $text,
                            "Markdown",
                            false,
                            null,
                            $InlineKeyboardMarkup
                        );
                    }
                }
            });
        }
    }

    private function isAcknowledgement() {
        return $this->notificationtype === 'ACKNOWLEDGEMENT';
    }

    private function isFlappingStart() {
        return $this->notificationtype === 'FLAPPINGSTART';
    }

    private function isFlappingStop() {
        return $this->notificationtype === 'FLAPPINGSTOP';
    }

    private function isFlappingDisabled() {
        return $this->notificationtype === 'FLAPPINGDISABLED';
    }

    private function isDowntimeStart() {
        return $this->notificationtype === 'DOWNTIMESTART';
    }

    private function isDowntimeEnd() {
        return $this->notificationtype === 'DOWNTIMEEND';
    }

    private function isDowntimeCancelled() {
        return $this->notificationtype === 'DOWNTIMECANCELLED';
    }

    /**
     * @param Arguments $args
     * @throws \itnovum\openITCOCKPIT\ApiShell\Exceptions\MissingParameterExceptions
     */
    private function validateOptions(Arguments $args) {
        if ($args->getOption('type') === '') {
            throw new \itnovum\openITCOCKPIT\ApiShell\Exceptions\MissingParameterExceptions(
                'Option --type is missing'
            );
        }

        $this->state = 2;
        $this->type = strtolower($args->getOption('type'));

        if ($args->getOption('notificationtype') === '') {
            throw new \itnovum\openITCOCKPIT\ApiShell\Exceptions\MissingParameterExceptions(
                'Option --notificationtype is missing'
            );
        }
        $this->notificationtype = $args->getOption('notificationtype');

        if ($args->getOption('hostuuid') === '') {
            throw new \itnovum\openITCOCKPIT\ApiShell\Exceptions\MissingParameterExceptions(
                'Option --hostuuid is missing'
            );
        }
        $this->hostUuid = $args->getOption('hostuuid');

        if ($this->type === 'service') {
            $this->state = 3;
            if ($args->getOption('serviceuuid') === '') {
                throw new \itnovum\openITCOCKPIT\ApiShell\Exceptions\MissingParameterExceptions(
                    'Option --serviceuuid is missing'
                );
            }
            $this->serviceUuid = $args->getOption('serviceuuid');
        }

        if ($args->getOption('state') !== '') {
            //Not all notifications have a state like ack or downtime messages.
            $this->state = (int)$args->getOption('state');
        }


        if ($args->getOption('output') === '') {
            throw new \itnovum\openITCOCKPIT\ApiShell\Exceptions\MissingParameterExceptions(
                'Option --output is missing'
            );
        }
        $this->output = $args->getOption('output');

        if ($args->getOption('ackauthor') !== '') {
            $this->ackAuthor = $args->getOption('ackauthor');
        }

        if ($args->getOption('ackcomment') !== '') {
            $this->ackComment = $args->getOption('ackcomment');
        }

        $this->noEmoji = $args->getOption('no-emoji');
    }
}
