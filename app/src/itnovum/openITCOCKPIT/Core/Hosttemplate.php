<?php

namespace itnovum\openITCOCKPIT\Core;

class Hosttemplate {

    private $id;
    private $uuid;
    private $name;
    private $description;
    private $hosttemplatetype_id;
    private $command_id;
    private $check_command_args;
    private $eventhandler_command_id;
    private $timeperiod_id;
    private $check_interval;
    private $retry_interval;
    private $max_check_attempts;
    private $first_notification_delay;
    private $notification_interval;
    private $notify_on_down;
    private $notify_on_unreachable;
    private $notify_on_recovery;
    private $notify_on_flapping;
    private $notify_on_downtime;
    private $flap_detection_enabled;
    private $flap_detection_on_up;
    private $flap_detection_on_down;
    private $flap_detection_on_unreachable;
    private $low_flap_threshold;
    private $high_flap_threshold;
    private $process_performance_data;
    private $freshness_checks_enabled;
    private $freshness_threshold;
    private $passive_checks_enabled;
    private $event_handler_enabled;
    private $active_checks_enabled;
    private $retain_status_information;
    private $retain_nonstatus_information;
    private $notifications_enabled;
    private $notes;
    private $priority;
    private $check_period_id;
    private $notify_period_id;
    private $tags;
    private $container_id;
    private $host_url;
    private $created;
    private $modified;


    public function __construct($data) {
        if(isset($data['id'])){
            $this->id = $data['id'];
        }

        if(isset($data['uuid'])){
            $this->uuid = $data['uuid'];
        }

        if(isset($data['name'])){
            $this->name = $data['name'];
        }

        if(isset($data['description'])){
            $this->description = $data['description'];
        }

        if(isset($data['hosttemplatetype_id'])){
            $this->hosttemplatetype_id = $data['hosttemplatetype_id'];
        }

        if(isset($data['command_id'])){
            $this->command_id = $data['command_id'];
        }

        if(isset($data['check_command_args'])){
            $this->check_command_args = $data['check_command_args'];
        }

        if(isset($data['eventhandler_command_id'])){
            $this->eventhandler_command_id = $data['eventhandler_command_id'];
        }

        if(isset($data['timeperiod_id'])){
            $this->timeperiod_id = $data['timeperiod_id'];
        }

        if(isset($data['check_interval'])){
            $this->check_interval = $data['check_interval'];
        }

        if(isset($data['retry_interval'])){
            $this->retry_interval = $data['retry_interval'];
        }

        if(isset($data['max_check_attempts'])){
            $this->max_check_attempts = $data['max_check_attempts'];
        }

        if(isset($data['first_notification_delay'])){
            $this->first_notification_delay = $data['first_notification_delay'];
        }

        if(isset($data['notification_interval'])){
            $this->notification_interval = $data['notification_interval'];
        }

        if(isset($data['notify_on_down'])){
            $this->notify_on_down = $data['notify_on_down'];
        }

        if(isset($data['notify_on_unreachable'])){
            $this->notify_on_unreachable = $data['notify_on_unreachable'];
        }

        if(isset($data['notify_on_recovery'])){
            $this->notify_on_recovery = $data['notify_on_recovery'];
        }

        if(isset($data['notify_on_flapping'])){
            $this->notify_on_flapping = $data['notify_on_flapping'];
        }

        if(isset($data['notify_on_downtime'])){
            $this->notify_on_downtime = $data['notify_on_downtime'];
        }

        if(isset($data['notify_on_flapping'])){
            $this->notify_on_flapping = $data['notify_on_flapping'];
        }

        if(isset($data['flap_detection_enabled'])){
            $this->flap_detection_enabled = $data['flap_detection_enabled'];
        }

        if(isset($data['flap_detection_on_up'])){
            $this->flap_detection_on_up = $data['flap_detection_on_up'];
        }

        if(isset($data['flap_detection_on_down'])){
            $this->flap_detection_on_down = $data['flap_detection_on_down'];
        }

        if(isset($data['flap_detection_on_unreachable'])){
            $this->flap_detection_on_unreachable = $data['flap_detection_on_unreachable'];
        }

        if(isset($data['low_flap_threshold'])){
            $this->low_flap_threshold = $data['low_flap_threshold'];
        }

        if(isset($data['high_flap_threshold'])){
            $this->high_flap_threshold = $data['high_flap_threshold'];
        }

        if(isset($data['process_performance_data'])){
            $this->process_performance_data = $data['process_performance_data'];
        }

        if(isset($data['freshness_checks_enabled'])){
            $this->freshness_checks_enabled = $data['freshness_checks_enabled'];
        }

        if(isset($data['freshness_threshold'])){
            $this->freshness_threshold = $data['freshness_threshold'];
        }

        if(isset($data['passive_checks_enabled'])){
            $this->passive_checks_enabled = $data['passive_checks_enabled'];
        }

        if(isset($data['event_handler_enabled'])){
            $this->event_handler_enabled = $data['event_handler_enabled'];
        }

        if(isset($data['active_checks_enabled'])){
            $this->active_checks_enabled = $data['active_checks_enabled'];
        }

        if(isset($data['retain_status_information'])){
            $this->retain_status_information = $data['retain_status_information'];
        }

        if(isset($data['retain_nonstatus_information'])){
            $this->retain_nonstatus_information = $data['retain_nonstatus_information'];
        }

        if(isset($data['notifications_enabled'])){
            $this->notifications_enabled = $data['notifications_enabled'];
        }

        if(isset($data['active_checks_enabled'])){
            $this->active_checks_enabled = $data['active_checks_enabled'];
        }

        if(isset($data['notes'])){
            $this->notes = $data['notes'];
        }

        if(isset($data['priority'])){
            $this->priority = $data['priority'];
        }

        if(isset($data['check_period_id'])){
            $this->check_period_id = $data['check_period_id'];
        }

        if(isset($data['notify_period_id'])){
            $this->notify_period_id = $data['notify_period_id'];
        }

        if(isset($data['tags'])){
            $this->tags = $data['tags'];
        }

        if(isset($data['container_id'])){
            $this->container_id = $data['container_id'];
        }

        if(isset($data['host_url'])){
            $this->host_url = $data['host_url'];
        }

        if(isset($data['created'])){
            $this->created = $data['created'];
        }

        if(isset($data['modified'])){
            $this->modified = $data['modified'];
        }
    }

    public function castedValues(){
        $castedValues = [];
        $castedValues['id'] = (int)$this->id;
        $castedValues['uuid'] = $this->uuid;
        $castedValues['name'] = $this->name;
        $castedValues['description'] = $this->description;
        $castedValues['hosttemplatetype_id'] = (int)$this->hosttemplatetype_id;
        $castedValues['command_id'] = (int)$this->command_id;
        $castedValues['check_command_args'] = $this->check_command_args;
        $castedValues['eventhandler_command_id'] = (int)$this->eventhandler_command_id;
        $castedValues['timeperiod_id'] = (int)$this->timeperiod_id;
        $castedValues['check_interval'] = (int)$this->check_interval;
        $castedValues['retry_interval'] = (int)$this->retry_interval;
        $castedValues['max_check_attempts'] = (int)$this->max_check_attempts;
        $castedValues['first_notification_delay'] = $this->first_notification_delay;
        $castedValues['notification_interval'] = $this->notification_interval;
        $castedValues['notify_on_down'] = (int)$this->notify_on_down;
        $castedValues['notify_on_unreachable'] = (int)$this->notify_on_unreachable;
        $castedValues['notify_on_recovery'] = (int)$this->notify_on_recovery;
        $castedValues['notify_on_flapping'] = (int)$this->notify_on_flapping;
        $castedValues['notify_on_downtime'] = (int)$this->notify_on_downtime;
        $castedValues['flap_detection_enabled'] = (int)$this->flap_detection_enabled;
        $castedValues['flap_detection_on_up'] = (int)$this->flap_detection_on_up;
        $castedValues['flap_detection_on_down'] = (int)$this->flap_detection_on_down;
        $castedValues['flap_detection_on_unreachable'] = (int)$this->flap_detection_on_unreachable;
        $castedValues['low_flap_threshold'] = $this->low_flap_threshold;
        $castedValues['high_flap_threshold'] = $this->high_flap_threshold;
        $castedValues['process_performance_data'] = (int)$this->process_performance_data;
        $castedValues['freshness_checks_enabled'] = (int)$this->freshness_checks_enabled;
        $castedValues['freshness_threshold'] = (int)$this->freshness_threshold;
        $castedValues['passive_checks_enabled'] = (int)$this->passive_checks_enabled;
        $castedValues['event_handler_enabled'] = (int)$this->event_handler_enabled;
        $castedValues['active_checks_enabled'] = (int)$this->active_checks_enabled;
        $castedValues['retain_status_information'] = (int)$this->retain_status_information;
        $castedValues['retain_nonstatus_information'] = (int)$this->retain_nonstatus_information;
        $castedValues['notifications_enabled'] = (int)$this->notifications_enabled;
        $castedValues['notes'] = $this->notes;
        $castedValues['priority'] = (int)$this->priority;
        $castedValues['check_period_id'] = (int)$this->check_period_id;
        $castedValues['notify_period_id'] = (int)$this->notify_period_id;
        $castedValues['tags'] = $this->tags;
        $castedValues['container_id'] = (int)$this->container_id;
        $castedValues['host_url'] = $this->host_url;
        $castedValues['created'] = $this->created;
        $castedValues['modified'] = $this->modified;
        return ['Hosttemplate' => $castedValues];
    }


}