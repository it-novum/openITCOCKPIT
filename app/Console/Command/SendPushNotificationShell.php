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


use Cake\ORM\TableRegistry;
use itnovum\openITCOCKPIT\Core\Views\Host;
use itnovum\openITCOCKPIT\Core\Views\HoststatusIcon;
use itnovum\openITCOCKPIT\Core\Views\Service;
use itnovum\openITCOCKPIT\Core\Views\ServicestatusIcon;

// Example command:
// oitc send_push_notification --type Host --notificationtype PROBLEM --hostuuid c36b8048-93ce-4385-ac19-ab5c90574b77 --state 1 --output "This host is down right now" --ackauthor "" --ackcomment "" --user-id 1

/**
 * Class PushoverNotificationShell
 * @property \Host Host
 * @property \Service Service
 * @property User User
 */
class SendPushNotificationShell extends AppShell {

    public $uses = [
        'Host',
        'Service',
        'User'
    ];

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

    /**
     * @var array
     */
    private $Config;

    /**
     * @var
     */
    private $userId;

    public function main() {
        $this->validateOptions();

        Configure::load('gearman');
        $this->Config = Configure::read('gearman');

        if ($this->userId === 0) {
            exit (0);
        }
        /** @var $Users App\Model\Table\UsersTable */
        $Users = TableRegistry::getTableLocator()->get('Users');

        if (!$Users->existsById($this->userId)) {
            throw new RuntimeException(sprintf('User with id "%s" could not be found!', $this->userId));
        }

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
            'type'             => ['short' => 't', 'help' => __d('oitc_console', 'Type of the notification host or service')],
            'notificationtype' => ['help' => __d('oitc_console', 'Notification type of monitoring engine => $NOTIFICATIONTYPE$ ')],
            'hostuuid'         => ['help' => __d('oitc_console', 'Host uuid you want to send a notification => $HOSTNAME$')],
            'serviceuuid'      => ['help' => __d('oitc_console', 'Service uuid you want to send a notification => $SERVICEDESC$')],
            'state'            => ['help' => __d('oitc_console', 'current host state => $HOSTSTATEID$/$SERVICESTATEID$')],
            'output'           => ['help' => __d('oitc_console', 'host output => $HOSTOUTPUT$/$SERVICEOUTPUT$')],
            'ackauthor'        => ['help' => __d('oitc_console', 'host acknowledgement author => $NOTIFICATIONAUTHOR$')],
            'ackcomment'       => ['help' => __d('oitc_console', 'host acknowledgement comment => $NOTIFICATIONCOMMENT$')],
            'user-id'          => ['help' => __d('oitc_console', 'openITCOCKPIT User Id')],
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

            $message = sprintf('%s: %s', $this->ackAuthor, $this->ackComment);
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

        $this->push($title, $this->output, $HoststatusIcon->getNotificationIcon());
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

            $message = sprintf('%s: %s', $this->ackAuthor, $this->ackComment);
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

        $this->push($title, $this->output, $ServicestatusIcon->getNotificationIcon());
    }

    private function push($title, $message, $icon = null) {
        $GearmanClient = new GearmanClient();
        $GearmanClient->addServer($this->Config['address'], $this->Config['port']);

        $GearmanClient->doBackground('oitc_push_notifications', json_encode([
            'timestamp'   => time(),
            'userId'      => $this->userId,
            'title'       => $title,
            'message'     => $message,
            'type'        => $this->type,
            'hostUuid'    => $this->hostUuid,
            'serviceUuid' => $this->serviceUuid,
            'icon'        => $icon
        ]));
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

        if (!isset($this->params['user-id'])) {
            throw new \itnovum\openITCOCKPIT\ApiShell\Exceptions\MissingParameterExceptions(
                'Option --user-id is missing'
            );
        }
        $this->userId = (int)$this->params['user-id'];
    }

    public function _welcome() {
        //Disable cake welcome message
        return;
    }


}
