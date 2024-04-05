<?php
// Copyright (C) <2024>  <it-novum GmbH>
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

namespace MSTeamsModule\Command;


use App\Model\Table\HostsTable;
use App\Model\Table\ProxiesTable;
use App\Model\Table\ServicesTable;
use Cake\Console\Arguments;
use Cake\Console\Command;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\ORM\TableRegistry;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;
use itnovum\openITCOCKPIT\Core\Views\Host;
use itnovum\openITCOCKPIT\Core\Views\Service;
use MSTeamsModule\Lib\Connector\MSTeamsConnector\MSTeamsSettings;
use MSTeamsModule\Lib\Notification\TeamsNotification;

/**
 * TeamsNotification command.
 */
class TeamsNotificationCommand extends Command {

    private const LEVEL_CRITICAL = 'CRITICAL';
    private const LEVEL_WARNING = 'WARNING';
    private const LEVEL_OK = 'OK';
    private const LEVEL_UNKNOWN = 'UNKNOWN';
    private const COLOR_CRITICAL = 'Attention';
    private const COLOR_WARNING = 'Warning';
    private const COLOR_OK = 'Good';
    private const COLOR_DEFAULT = 'Attention';
    private const SERVICE_LEVELS = [
        0 => self::LEVEL_OK,
        1 => self::LEVEL_WARNING,
        2 => self::LEVEL_CRITICAL,
        3 => self::LEVEL_UNKNOWN,
    ];
    private const COLOR_MAPPING = [
        self::LEVEL_OK       => self::COLOR_OK,
        self::LEVEL_WARNING  => self::COLOR_WARNING,
        self::LEVEL_CRITICAL => self::COLOR_CRITICAL,
        self::LEVEL_UNKNOWN  => self::COLOR_DEFAULT,
    ];
    /** @var string I am the UUID of the Host. */
    private $hostUuid;

    /** @var string I am the UUID of the Service. */
    private $serviceUuid;

    /** @var string */
    private $type = '';

    /** @var MSTeamsSettings */
    private $settings;

    /** @var int */
    private $state;


    /**
     * @return void
     * @throws \Exception
     */
    public function __construct() {
        parent::__construct();
        // Fetch Settings for MS Teams.
        $this->settings = MSTeamsSettings::fetch();
    }

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
            'type'             => ['help' => __d('oitc_console', 'Type of the notification. "host" | "service"')],
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
     * I will build the Notification.
     * @return TeamsNotification
     */
    private function buildNotification(): TeamsNotification {
        $notification = new TeamsNotification();
        $notification->level = self::SERVICE_LEVELS[$this->state] ?? self::LEVEL_UNKNOWN;
        $notification->color = self::COLOR_MAPPING[$notification->level] ?? self::COLOR_DEFAULT;
        $notification->hostId = $this->getHost()->getId();
        $notification->hostName = $this->getHost()->getHostname();
        $notification->output = $this->output;

        if ($this->type === 'service') {
            $notification->serviceName = $this->getService()->getServicename();
            $notification->serviceId = $this->getService()->getId();
        }

        return $notification;
    }


    /**
     * Based on the user's input hostUuid, I will return the correct Host.
     * @return Host
     */
    private function getHost(): Host {
        try {
            /** @var HostsTable $HostsTable */
            $HostsTable = TableRegistry::getTableLocator()->get('Hosts');
            $host = $HostsTable->getHostByUuid($this->hostUuid, false);
        } catch (RecordNotFoundException $e) {
            throw new \RuntimeException(sprintf('Host with uuid "%s" could not be found!', $this->hostUuid));
        }

        return new Host($host);
    }

    /**
     * Based on the user's input serviceUuid, I will return the correct Service.
     * @return Service
     */
    private function getService(): Service {
        try {
            /** @var ServicesTable $ServicesTable */
            $ServicesTable = TableRegistry::getTableLocator()->get('Services');
            $service = $ServicesTable->getServiceByUuid($this->serviceUuid, false);
        } catch (RecordNotFoundException $e) {
            throw new \RuntimeException(sprintf('Service with uuid "%s" could not be found!', $this->serviceUuid));
        }

        return new Service($service);
    }


    private function buildServiceMessage(TeamsNotification $notification): array {
        $actions = [];
        $indent = false;
        if ($this->state > 0) {
            $actions[] = $this->buildAckServiceButton();
            $indent = true;
        }
        $actions[] = $this->buildHostLink($notification, $indent);
        $actions[] = $this->buildServiceLink($notification, $indent);
        return [
            'type'        => 'message',
            'attachments' => [
                [
                    'contentType' => 'application/vnd.microsoft.card.adaptive',
                    'contentUrl'  => null,
                    'content'     => [
                        '$schema' => 'http://adaptivecards.io/schemas/adaptive-card.json',
                        'type'    => 'AdaptiveCard',
                        'version' => '1.6',
                        'body'    => [
                            [
                                'type'  => 'Container',
                                'items' => [
                                    [
                                        'type'   => 'TextBlock',
                                        'text'   => sprintf(
                                            '[%s]: Service "%s" on host %s"',
                                            $notification->level,
                                            $notification->serviceName,
                                            $notification->hostName
                                        ),
                                        'weight' => 'bolder',
                                        'size'   => 'medium',
                                        'color'  => $notification->color
                                    ],
                                ]
                            ],
                            [
                                'type'  => 'Container',
                                'items' => [
                                    [
                                        'type' => 'TextBlock',
                                        'text' => sprintf(
                                            'Service "%s" changed to status %s.',
                                            $notification->serviceName,
                                            $notification->level
                                        ),
                                        'wrap' => true
                                    ],
                                    [
                                        'type'  => 'FactSet',
                                        'facts' => [
                                            [
                                                'title' => 'Host:',
                                                'value' => $notification->hostName
                                            ],
                                            [
                                                'title' => 'Service:',
                                                'value' => $notification->serviceName
                                            ]
                                        ]
                                    ]
                                ]
                            ]
                        ],
                        'actions' => $actions
                    ]
                ]
            ]
        ];
    }

    private function buildHostMessage(TeamsNotification $notification): array {
        $actions = [];
        if ($this->state === 3) {
            $actions[] = $this->buildAckHostButton();
        }
        $actions[] = $this->buildHostLink($notification);
        return [
            'type'        => 'message',
            'attachments' => [
                [
                    'contentType' => 'application/vnd.microsoft.card.adaptive',
                    'contentUrl'  => null,
                    'content'     => [
                        '$schema' => 'http://adaptivecards.io/schemas/adaptive-card.json',
                        'type'    => 'AdaptiveCard',
                        'version' => '1.6',
                        'body'    => [
                            [
                                'type'  => 'Container',
                                'items' => [
                                    [
                                        'type'   => 'TextBlock',
                                        'text'   => sprintf(
                                            '[%s]: host "%s" (%s)',
                                            $notification->level,
                                            $notification->hostName,
                                            $notification->color
                                        ),
                                        'weight' => 'bolder',
                                        'size'   => 'medium',
                                        'color'  => $notification->color
                                    ],
                                ]
                            ],
                            [
                                'type'  => 'Container',
                                'items' => [
                                    [
                                        'type' => 'TextBlock',
                                        'text' => sprintf(
                                            'Host "%s" changed to status %s .',
                                            $notification->hostName,
                                            $notification->level
                                        ),
                                        'wrap' => true
                                    ],
                                    [
                                        'type'  => 'FactSet',
                                        'facts' => [
                                            [
                                                'title' => 'Output:',
                                                'value' => $notification->output
                                            ]
                                        ]
                                    ]
                                ]
                            ]
                        ],
                        'actions' => $actions
                    ]
                ]
            ]
        ];
    }

    /**
     * I will solely build the correct message type based on the user's arguments.
     * @param TeamsNotification $notification
     * @return array
     */
    private function buildMessage(TeamsNotification $notification): array {
        switch ($this->type) {
            case 'service':
                return $this->buildServiceMessage($notification);
            case 'host':
                return $this->buildHostMessage($notification);
            default:
                return [];
        }
    }

    /**
     * @param Arguments $args
     * @param ConsoleIo $io
     */
    public function execute(Arguments $args, ConsoleIo $io) {
        try {
            // Validate User input
            $this->validateOptions($args);

            // Build notification.
            $notification = $this->buildNotification();

            print_r($notification);

            // Build Data.
            $data = [
                'json'    => $this->buildMessage($notification),
                'headers' => [
                    'Accept' => 'application/json'
                ],
                'proxy'   => $this->getProxySettings()
            ];


            // Post Data.
            $guzzleclient = new Client();
            $guzzleclient->post($this->settings->url, $data);
        } catch (ClientException $a) {
            exit(1);
        } catch (GuzzleException $a) {
            // B
            exit(1);
        } catch (Exception $exception) {
            // A
            exit(1);
        }
        exit(0);
    }

    private function getProxySettings(): array {
        if (!$this->settings->useProxy) {
            return [
                'http'  => false,
                'https' => false
            ];
        }

        /** @var ProxiesTable $ProxiesTable */
        $ProxiesTable = TableRegistry::getTableLocator()->get('Proxies');
        $proxy = $ProxiesTable->getSettings();

        return [
            'http'  => sprintf('%s:%s', $proxy['ipaddress'], $proxy['port']),
            'https' => sprintf('%s:%s', $proxy['ipaddress'], $proxy['port'])
        ];
    }

    /**
     * @param Arguments $args
     * @throws \itnovum\openITCOCKPIT\ApiShell\Exceptions\MissingParameterExceptions
     */
    private function validateOptions(Arguments $args) {
        $this->useProxy = $args->getOption('proxy');

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

        if ($args->getOption('ackauthor') === '') {
            $this->ackAuthor = $args->getOption('ackauthor');
        }

        if ($args->getOption('ackcomment') === '') {
            $this->ackComment = $args->getOption('ackcomment');
        }

        $this->noEmoji = $args->getOption('no-emoji');
    }


    /*******************************
     *** AdaptiveCard.io buttons ***
     *******************************/

    /**
     * I will solely build the AdaptiveCard definition for the [Acknowledge] Button (Host).
     * @return array
     */
    private function buildAckHostButton(): array {
        return
            [
                'type'  => 'Action.OpenUrl',
                'title' => '✔️ Acknowledge',
                'url'   => $this->buildHostAcknowledgement($this->getHost()),
                'role'  => 'Button',
                'style' => 'positive',
                'mode'  => 'primary'
            ];
    }

    /**
     * I will solely build the AdaptiveCard definition for the [Acknowledge] Button (Service).
     * @return array
     */
    private function buildAckServiceButton(): array {
        return [
            'type'  => 'Action.OpenUrl',
            'title' => '✔️ Acknowledge',
            'url'   => $this->buildServiceAcknowledgement($this->getService()),
            'role'  => 'Button',
            'style' => 'positive',
            'mode'  => 'primary'
        ];
    }

    /**
     * I will solely generate the AdaptiveCard definition for the [Host] button.
     * @param TeamsNotification $notification
     * @param bool $indent
     * @return string[]
     */
    private function buildHostLink(TeamsNotification $notification, bool $indent): array {
        return
            [
                'type'  => 'Action.OpenUrl',
                'title' => '🖥️ View Host',
                'url'   => sprintf(
                    'https://%s/#!/hosts/browser/%s',
                    $this->settings->oitcUrl,
                    $notification->hostId
                ),
                'role'  => 'Button',
                'mode'  => $indent ? 'secondary' : 'primary'
            ];
    }

    /**
     * I will solely generate the AdaptiveCard definition for the [Service] button.
     * @param TeamsNotification $notification
     * @param bool $indent
     * @return string[]
     */
    private function buildServiceLink(TeamsNotification $notification, bool $indent): array {
        return
            [
                'type'  => 'Action.OpenUrl',
                'title' => '⚙ View Service',
                'url'   => sprintf(
                    'https://%s/#!/hosts/browser/%s',
                    $this->settings->oitcUrl,
                    $notification->serviceId
                ),
                'role'  => 'Button',
                'mode'  => $indent ? 'secondary' : 'primary'
            ];
    }

    /**********************
     *** URLs and links ***
     **********************/

    /**
     * I will solely generate the URL to the acknowledgement (Service)
     * @param Service $Service
     * @return string
     */
    private function buildServiceAcknowledgement(Service $Service): string {
        return sprintf(
            '%s/#!/services/browser/%s#acknowledge',
            $this->settings->oitcUrl,
            $Service->getId()
        );
    }

    /**
     * I will solely generate the URL to the acknowledgement (Host)
     * @param Host $Host
     * @return string
     */
    private function buildHostAcknowledgement(Host $Host): string {
        return sprintf(
            '%s/#!/hosts/browser/%s#acknowledge',
            $this->settings->oitcUrl,
            $Host->getId()
        );
    }

}
