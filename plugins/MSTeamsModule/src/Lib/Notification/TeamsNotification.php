<?php declare(strict_types=1);

namespace MSTeamsModule\Lib\Notification;

final class TeamsNotification {
    /** @var string */
    public $level = '';
    /** @var int */
    public $hostId = -1;
    /** @var string */
    public $hostName = '';
    /** @var string */
    public $serviceName = '';
    /** @var int */
    public $serviceId = -1;
    /** @var string */
    public $color = '';
    /** @var string  */
    public $output = '';
}