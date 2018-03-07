<?php
// The MIT License
//
// Copyright <2018> <Daniel Ziegler>
//
// Permission is hereby granted, free of charge, to any person obtaining a copy of this
// software and associated documentation files (the "Software"), to deal in the Software
// without restriction, including without limitation the rights touse, copy, modify,
// merge, publish, distribute, sublicense, and/or sell copies
// of the Software, and to permit persons to whom the Software is
// furnished to do so, subject to the following conditions:
//
// The above copyright notice and this permission notice shall be
// included in all copies or substantial portions of the Software.
//
// THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED,
// INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR
// A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT
// HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT,
// TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR
// THE USE OR OTHER DEALINGS IN THE SOFTWARE.


use itnovum\openITCOCKPIT\Core\Views\Host;
use itnovum\openITCOCKPIT\Core\Views\Service;
use itnovum\openITCOCKPIT\Core\Views\HoststatusIcon;
use itnovum\openITCOCKPIT\Core\Views\ServicestatusIcon;
use GuzzleHttp\Client;

/**
 * Class PushoverNotificationShell
 * @property \Host Host
 * @property \Service Service
 * @property Proxy Proxy
 */
class PushoverNotificationShell extends AppShell {

    public $uses = [
        'Host',
        'Service',
        'Proxy'
    ];

    /**
     * @var bool
     */
    private $useProxy = false;

    private $type = 'host';

    private $hostUuid = '';

    private $serviceUuid = '';

    //0 = Up, 1 = Down, 2 = Unreachable
    //0 = Ok, 1 = Warning, 2 = Critical, 3 = Unknown
    private $state = null;

    private $output = '';

    //PROBLEM", "RECOVERY", "ACKNOWLEDGEMENT", "FLAPPINGSTART", "FLAPPINGSTOP",
    //"FLAPPINGDISABLED", "DOWNTIMESTART", "DOWNTIMEEND", or "DOWNTIMECANCELLED"
    private $notificationtype = '';

    private $ackAuthor = false;

    private $ackComment = false;

    private $pushoverApiToken = '';

    private $pushoverUserToken = '';

    private $pushoverApiUrl = 'https://api.pushover.net/1/messages.json';

    public function main() {
        $this->validateOptions();

        $host = $this->Host->find('first', [
            'recursive'  => -1,
            'fields'     => [
                'Host.id',
                'Host.name',
                'Host.address'
            ],
            'conditions' => [
                'Host.uuid' => $this->hostUuid
            ]
        ]);

        if (empty($host)) {
            throw new RuntimeException(sprintf('Host with uuid "%s" could not be found!', $this->hostUuid));
        }

        $Host = new Host($host);

        if ($this->type === 'service') {
            $service = $this->Service->find('first', [
                'recursive'  => -1,
                'fields'     => [
                    'Service.id',
                    'Service.name',
                ],
                'contain'    => [
                    'Servicetemplate' => [
                        'fields' => [
                            'Servicetemplate.name'
                        ]
                    ]
                ],
                'conditions' => [
                    'Service.uuid' => $this->serviceUuid
                ]
            ]);

            if (empty($service)) {
                throw new RuntimeException(sprintf('Service with uuid "%s" could not be found!', $this->serviceUuid));
            }

            $Service = new Service($service);
            $this->sendServiceNotification($Host, $Service);
            exit(0);
        }


        $this->sendHostNotification($Host);
        exit(0);
    }


    public function getOptionParser() {
        $parser = parent::getOptionParser();
        $parser->addOptions([
            'type'                => ['short' => 't', 'help' => __d('oitc_console', 'Type of the notification host or service')],
            'notificationtype'    => ['help' => __d('oitc_console', 'Notification type of monitoring engine => $NOTIFICATIONTYPE$ ')],
            'hostuuid'            => ['help' => __d('oitc_console', 'Host uuid you want to send a notification => $HOSTNAME$')],
            'serviceuuid'         => ['help' => __d('oitc_console', 'Service uuid you want to send a notification => $SERVICEDESC$')],
            'state'               => ['help' => __d('oitc_console', 'current host state => $HOSTSTATEID$/$SERVICESTATEID$')],
            'output'              => ['help' => __d('oitc_console', 'host output => $HOSTOUTPUT$/$SERVICEOUTPUT$')],
            'ackauthor'           => ['help' => __d('oitc_console', 'host acknowledgement author => $HOSTACKAUTHOR$/$SERVICEACKAUTHOR$')],
            'ackcomment'          => ['help' => __d('oitc_console', 'host acknowledgement comment => $HOSTACKCOMMENT$/$SERVICEACKCOMMENT$')],
            'pushover-api-token'  => ['help' => __d('oitc_console', 'API Token of your Pushover Application => $_CONTACTPUSHOVERAPP$')],
            'pushover-user-token' => ['help' => __d('oitc_console', 'Your Pushover User Key => $_CONTACTPUSHOVERUSER$')],
            'proxy'               => ['help' => __d('oitc_console', 'If set, connection will be established using the proxy server defined in openITCOCKPIT interface. (1/0)')]
        ]);


        return $parser;
    }

    private function sendHostNotification(Host $Host) {
        $HoststatusIcon = new HoststatusIcon($this->state);

        if ($this->isAcknowledgement()) {
            $title = sprintf(
                'Acknowledgement for %s (%s)',
                $Host->getHostname(),
                $HoststatusIcon->getHumanState()
            );

            $message = sprintf('<b>%s:</b> %s', $this->ackAuthor, $this->ackComment);
            return $this->push($title, $message);
        }

        if ($this->isDowntimeStart()) {
            $title = sprintf(
                'Downtime start for %s',
                $Host->getHostname(),
                $HoststatusIcon->getHumanState()
            );

            $message = 'Host has entered a period of scheduled downtime';
            return $this->push($title, $message);
        }

        if ($this->isDowntimeEnd()) {
            $title = sprintf(
                'Downtime end for %s',
                $Host->getHostname(),
                $HoststatusIcon->getHumanState()
            );

            $message = 'Host has exited from a period of scheduled downtime';
            return $this->push($title, $message);
        }

        if ($this->isDowntimeCancelled()) {
            $title = sprintf(
                'Downtime cancelled for %s',
                $Host->getHostname(),
                $HoststatusIcon->getHumanState()
            );

            $message = 'Scheduled downtime for host has been cancelled';
            return $this->push($title, $message);
        }

        if ($this->isFlappingStart()) {
            $title = sprintf(
                'Flapping started on %s',
                $Host->getHostname(),
                $HoststatusIcon->getHumanState()
            );

            $message = 'Host appears to have started flapping';
            return $this->push($title, $message);
        }

        if ($this->isFlappingStop()) {
            $title = sprintf(
                'Flapping stopped on %s',
                $Host->getHostname(),
                $HoststatusIcon->getHumanState()
            );

            $message = 'Host appears to have stopped flapping';
            return $this->push($title, $message);
        }

        if ($this->isFlappingDisabled()) {
            $title = sprintf(
                'Disabled flap detection for %s',
                $Host->getHostname(),
                $HoststatusIcon->getHumanState()
            );

            $message = 'Flap detection has been disabled';
            return $this->push($title, $message);
        }

        //Default notification
        $title = sprintf(
            '%s: %s is %s!',
            $this->notificationtype,
            $Host->getHostname(),
            $HoststatusIcon->getHumanState()
        );

        $this->push($title, $this->output);
    }

    private function sendServiceNotification(Host $Host, Service $Service) {
        $ServicestatusIcon = new ServicestatusIcon($this->state);

        if ($this->isAcknowledgement()) {
            $title = sprintf(
                'Acknowledgement for %s/%s (%s)',
                $Host->getHostname(),
                $Service->getServicename(),
                $ServicestatusIcon->getHumanState()
            );

            $message = sprintf('<b>%s:</b> %s', $this->ackAuthor, $this->ackComment);
            return $this->push($title, $message);
        }

        if ($this->isDowntimeStart()) {
            $title = sprintf(
                'Downtime start for %s/%s',
                $Host->getHostname(),
                $Service->getServicename(),
                $ServicestatusIcon->getHumanState()
            );

            $message = 'Service has entered a period of scheduled downtime';
            return $this->push($title, $message);
        }

        if ($this->isDowntimeEnd()) {
            $title = sprintf(
                'Downtime end for %s/%s',
                $Host->getHostname(),
                $Service->getServicename(),
                $ServicestatusIcon->getHumanState()
            );

            $message = 'Service has exited from a period of scheduled downtime';
            return $this->push($title, $message);
        }

        if ($this->isDowntimeCancelled()) {
            $title = sprintf(
                'Downtime cancelled for %s/%s',
                $Host->getHostname(),
                $Service->getServicename(),
                $ServicestatusIcon->getHumanState()
            );

            $message = 'Scheduled downtime for service has been cancelled';
            return $this->push($title, $message);
        }

        if ($this->isFlappingStart()) {
            $title = sprintf(
                'Flapping started on %s/%s',
                $Host->getHostname(),
                $Service->getServicename(),
                $ServicestatusIcon->getHumanState()
            );

            $message = 'Service appears to have started flapping';
            return $this->push($title, $message);
        }

        if ($this->isFlappingStop()) {
            $title = sprintf(
                'Flapping stopped on %s/%s',
                $Host->getHostname(),
                $Service->getServicename(),
                $ServicestatusIcon->getHumanState()
            );

            $message = 'Service appears to have stopped flapping';
            return $this->push($title, $message);
        }

        if ($this->isFlappingDisabled()) {
            $title = sprintf(
                'Disabled flap detection for %s/%s',
                $Host->getHostname(),
                $Service->getServicename(),
                $ServicestatusIcon->getHumanState()
            );

            $message = 'Flap detection has been disabled';
            return $this->push($title, $message);
        }

        //Default notification
        $title = sprintf(
            '%s: %s on %s is %s!',
            $this->notificationtype,
            $Service->getServicename(),
            $Host->getHostname(),
            $ServicestatusIcon->getHumanState()
        );

        $this->push($title, $this->output);
    }

    private function push($title, $message) {
        $params = [
            'form_params' => [
                'token'   => $this->pushoverApiToken,
                'user'    => $this->pushoverUserToken,
                'html'    => 1,
                'title'   => $title,
                'message' => $message
            ],
            'proxy'       => [
                'http'  => false,
                'https' => false
            ]
        ];

        $params['proxy'] = [
            'http'  => false,
            'https' => false
        ];
        if ($this->useProxy) {
            $proxySettings = $this->Proxy->getSettings();
            if ($proxySettings['enabled']) {
                $params['proxy']['http'] = sprintf('%s:%s', $proxySettings['ipaddress'], $proxySettings['port']);
                $params['proxy']['https'] = $params['proxy']['http'];
            }
        }

        $client = new Client();

        $response = $client->request('POST', $this->pushoverApiUrl, $params);

        if ($response->getStatusCode() !== 200) {
            throw new RuntimeException($response->getBody()->getContents());
        }

        return true;

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

    private function validateOptions() {
        $this->useProxy = isset($this->params['proxy']);

        if (!isset($this->params['type'])) {
            throw new \itnovum\openITCOCKPIT\ApiShell\Exceptions\MissingParameterExceptions(
                'Option --type is missing'
            );
        }
        $this->type = strtolower($this->params['type']);

        if (!isset($this->params['notificationtype'])) {
            throw new \itnovum\openITCOCKPIT\ApiShell\Exceptions\MissingParameterExceptions(
                'Option --notificationtype is missing'
            );
        }
        $this->notificationtype = $this->params['notificationtype'];

        if (!isset($this->params['hostuuid'])) {
            throw new \itnovum\openITCOCKPIT\ApiShell\Exceptions\MissingParameterExceptions(
                'Option --hostuuid is missing'
            );
        }
        $this->hostUuid = $this->params['hostuuid'];

        if ($this->type === 'service') {
            if (!isset($this->params['serviceuuid'])) {
                throw new \itnovum\openITCOCKPIT\ApiShell\Exceptions\MissingParameterExceptions(
                    'Option --serviceuuid is missing'
                );
            }
            $this->serviceUuid = $this->params['serviceuuid'];
        }

        if (!isset($this->params['state'])) {
            throw new \itnovum\openITCOCKPIT\ApiShell\Exceptions\MissingParameterExceptions(
                'Option --state is missing'
            );
        }
        $this->state = (int)$this->params['state'];

        if (!isset($this->params['output'])) {
            throw new \itnovum\openITCOCKPIT\ApiShell\Exceptions\MissingParameterExceptions(
                'Option --output is missing'
            );
        }
        $this->output = $this->params['output'];

        if (isset($this->params['ackauthor'])) {
            $this->ackAuthor = $this->params['ackauthor'];
        }

        if (isset($this->params['ackcomment'])) {
            $this->ackComment = $this->params['ackcomment'];
        }

        if (!isset($this->params['pushover-api-token'])) {
            throw new \itnovum\openITCOCKPIT\ApiShell\Exceptions\MissingParameterExceptions(
                'Option --pushover-api-token is missing'
            );
        }
        $this->pushoverApiToken = $this->params['pushover-api-token'];

        if (!isset($this->params['pushover-user-token'])) {
            throw new \itnovum\openITCOCKPIT\ApiShell\Exceptions\MissingParameterExceptions(
                'Option --pushover-api-token is missing'
            );
        }
        $this->pushoverUserToken = $this->params['pushover-user-token'];
    }

    public function _welcome() {
        //Disable cake welcome message
        return;
    }


}
