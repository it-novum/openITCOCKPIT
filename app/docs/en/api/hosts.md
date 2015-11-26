## Query all existing hosts:

<div class="input-group">
	<span class="input-group-addon bg-color-green txt-color-white">GET</span>
	<input type="text" class="form-control" readonly="readonly" value="/hosts.json">
</div>
<br />
<div class="panel panel-primary">
	<div class="panel-heading">
		<h3 class="panel-title">Response: application/json</h3>
	</div>
	<div class="panel-body">
		<pre>
{
    "all_hosts": [
        {
            "Host": {
                "id": "1",
                "uuid": "c36b8048-93ce-4385-ac19-ab5c90574b77",
                "name": "localhost",
                "description": null,
                "active_checks_enabled": null,
                "address": "127.0.0.1",
                "satellite_id": "0"
            },
            "Hoststatus": {
                "current_state": "0",
                "last_check": "2015-11-26 13:55:12",
                "next_check": "2015-11-26 15:55:12",
                "last_hard_state_change": "2015-08-27 00:04:48",
                "output": "OK - 127.0.0.1: rta 0,050ms, lost 0%",
                "scheduled_downtime_depth": "0",
                "active_checks_enabled": "1",
                "state_type": "1",
                "problem_has_been_acknowledged": "0",
                "is_flapping": "0"
            },
            "Hosttemplate": {
                "id": "1",
                "uuid": "efbee68c-cf48-4b78-83f5-c856c56177f0",
                "name": "default host",
                "description": "default host",
                "active_checks_enabled": "1"
            },
            "Container": [
                {
                    "id": "1",
                    "containertype_id": "1",
                    "name": "root",
                    "parent_id": null,
                    "lft": "1",
                    "rght": "42",
                    "HostsToContainer": {
                        "id": "1",
                        "host_id": "1",
                        "container_id": "1"
                    }
                }
            ]
        },
        {
            "Host": {
                "id": "2",
                "uuid": "2d24110b-0457-4a0e-a4c0-cb37d171a986",
                "name": "srvkvm01.local.lan",
                "description": null,
                "active_checks_enabled": null,
                "address": "192.168.1.1",
                "satellite_id": "0"
            },
            "Hoststatus": {
                "current_state": "0",
                "last_check": "2015-11-26 15:31:32",
                "next_check": "2015-11-26 17:31:32",
                "last_hard_state_change": "2015-08-27 00:32:37",
                "output": "OK - 192.168.1.1: rta 0,542ms, lost 0%",
                "scheduled_downtime_depth": "0",
                "active_checks_enabled": "1",
                "state_type": "1",
                "problem_has_been_acknowledged": "0",
                "is_flapping": "0"
            },
            "Hosttemplate": {
                "id": "1",
                "uuid": "efbee68c-cf48-4b78-83f5-c856c56177f0",
                "name": "default host",
                "description": "default host",
                "active_checks_enabled": "1"
            },
            "Container": [
                {
                    "id": "1",
                    "containertype_id": "1",
                    "name": "root",
                    "parent_id": null,
                    "lft": "1",
                    "rght": "42",
                    "HostsToContainer": {
                        "id": "2",
                        "host_id": "2",
                        "container_id": "1"
                    }
                }
            ]
        }
    ]
}
		</pre>
	</div>
</div>

## Query a host by id:
<div class="input-group">
	<span class="input-group-addon bg-color-green txt-color-white">GET</span>
	<input type="text" class="form-control" readonly="readonly" value="/hosts/1.json">
</div>
<br />
<div class="panel panel-primary">
	<div class="panel-heading">
		<h3 class="panel-title">Response: application/json</h3>
	</div>
	<div class="panel-body">
		<pre>
{
    "host": {
        "Host": {
            "id": "1",
            "uuid": "c36b8048-93ce-4385-ac19-ab5c90574b77",
            "container_id": "1",
            "name": "localhost",
            "description": null,
            "hosttemplate_id": "1",
            "address": "127.0.0.1",
            "command_id": null,
            "eventhandler_command_id": null,
            "timeperiod_id": null,
            "check_interval": null,
            "retry_interval": null,
            "max_check_attempts": null,
            "first_notification_delay": null,
            "notification_interval": null,
            "notify_on_down": null,
            "notify_on_unreachable": null,
            "notify_on_recovery": null,
            "notify_on_flapping": null,
            "notify_on_downtime": null,
            "flap_detection_enabled": null,
            "flap_detection_on_up": null,
            "flap_detection_on_down": null,
            "flap_detection_on_unreachable": null,
            "low_flap_threshold": null,
            "high_flap_threshold": null,
            "process_performance_data": null,
            "freshness_checks_enabled": null,
            "freshness_threshold": null,
            "passive_checks_enabled": null,
            "event_handler_enabled": null,
            "active_checks_enabled": null,
            "retain_status_information": null,
            "retain_nonstatus_information": null,
            "notifications_enabled": null,
            "notes": null,
            "priority": null,
            "check_period_id": null,
            "notify_period_id": null,
            "tags": null,
            "own_contacts": "0",
            "own_contactgroups": "0",
            "own_customvariables": "0",
            "host_url": "",
            "satellite_id": "0",
            "host_type": "1",
            "disabled": "0",
            "created": "2015-01-15 19:26:32",
            "modified": "2015-01-15 19:26:32"
        },
        "Container": {
            "id": "1",
            "containertype_id": "1",
            "name": "root",
            "parent_id": null,
            "lft": "1",
            "rght": "42",
            "0": {
                "id": "1",
                "containertype_id": "1",
                "name": "root",
                "parent_id": null,
                "lft": "1",
                "rght": "42",
                "HostsToContainer": {
                    "id": "1",
                    "host_id": "1",
                    "container_id": "1"
                }
            }
        },
        "CheckPeriod": {
            "id": null,
            "uuid": null,
            "container_id": null,
            "name": null,
            "description": null,
            "created": null,
            "modified": null
        },
        "NotifyPeriod": {
            "id": null,
            "uuid": null,
            "container_id": null,
            "name": null,
            "description": null,
            "created": null,
            "modified": null
        },
        "CheckCommand": {
            "id": null,
            "name": null,
            "command_line": null,
            "command_type": null,
            "human_args": null,
            "uuid": null,
            "description": null
        },
        "Hosttemplate": {
            "id": "1",
            "uuid": "efbee68c-cf48-4b78-83f5-c856c56177f0",
            "name": "default host",
            "description": "default host",
            "hosttemplatetype_id": "1",
            "command_id": "4",
            "check_command_args": "",
            "eventhandler_command_id": "0",
            "timeperiod_id": "0",
            "check_interval": "7200",
            "retry_interval": "60",
            "max_check_attempts": "3",
            "first_notification_delay": "0",
            "notification_interval": "7200",
            "notify_on_down": "0",
            "notify_on_unreachable": "1",
            "notify_on_recovery": "1",
            "notify_on_flapping": "0",
            "notify_on_downtime": "0",
            "flap_detection_enabled": "0",
            "flap_detection_on_up": "0",
            "flap_detection_on_down": "0",
            "flap_detection_on_unreachable": "0",
            "low_flap_threshold": "0",
            "high_flap_threshold": "0",
            "process_performance_data": "0",
            "freshness_checks_enabled": "0",
            "freshness_threshold": "0",
            "passive_checks_enabled": "0",
            "event_handler_enabled": "0",
            "active_checks_enabled": "1",
            "retain_status_information": "0",
            "retain_nonstatus_information": "0",
            "notifications_enabled": "0",
            "notes": "",
            "priority": "1",
            "check_period_id": "1",
            "notify_period_id": "1",
            "tags": "",
            "container_id": "1",
            "host_url": "",
            "created": "2015-01-05 15:22:21",
            "modified": "2015-01-05 15:22:21"
        },
        "HostescalationHostMembership": [

        ],
        "HostdependencyHostMembership": [

        ],
        "Service": [
            {
                "id": "1",
                "uuid": "74fd8f59-1348-4e16-85f0-4a5c57c7dd62",
                "servicetemplate_id": "1",
                "host_id": "1",
                "name": null,
                "description": null,
                "command_id": null,
                "check_command_args": "",
                "eventhandler_command_id": null,
                "notify_period_id": null,
                "check_period_id": null,
                "check_interval": null,
                "retry_interval": null,
                "max_check_attempts": null,
                "first_notification_delay": null,
                "notification_interval": null,
                "notify_on_warning": null,
                "notify_on_unknown": null,
                "notify_on_critical": null,
                "notify_on_recovery": null,
                "notify_on_flapping": null,
                "notify_on_downtime": null,
                "is_volatile": null,
                "flap_detection_enabled": null,
                "flap_detection_on_ok": null,
                "flap_detection_on_warning": null,
                "flap_detection_on_unknown": null,
                "flap_detection_on_critical": null,
                "low_flap_threshold": null,
                "high_flap_threshold": null,
                "process_performance_data": null,
                "freshness_checks_enabled": null,
                "freshness_threshold": null,
                "passive_checks_enabled": null,
                "event_handler_enabled": null,
                "active_checks_enabled": null,
                "notifications_enabled": null,
                "notes": null,
                "priority": null,
                "tags": null,
                "own_contacts": "0",
                "own_contactgroups": "0",
                "own_customvariables": "0",
                "service_url": null,
                "service_type": "1",
                "disabled": "0",
                "created": "2015-01-15 19:26:46",
                "modified": "2015-01-15 19:26:46"
            },
            {
                "id": "2",
                "uuid": "74f14950-a58f-4f18-b6c3-5cfa9dffef4e",
                "servicetemplate_id": "8",
                "host_id": "1",
                "name": null,
                "description": null,
                "command_id": null,
                "check_command_args": "",
                "eventhandler_command_id": null,
                "notify_period_id": null,
                "check_period_id": null,
                "check_interval": null,
                "retry_interval": null,
                "max_check_attempts": null,
                "first_notification_delay": null,
                "notification_interval": null,
                "notify_on_warning": null,
                "notify_on_unknown": null,
                "notify_on_critical": null,
                "notify_on_recovery": null,
                "notify_on_flapping": null,
                "notify_on_downtime": null,
                "is_volatile": null,
                "flap_detection_enabled": null,
                "flap_detection_on_ok": null,
                "flap_detection_on_warning": null,
                "flap_detection_on_unknown": null,
                "flap_detection_on_critical": null,
                "low_flap_threshold": null,
                "high_flap_threshold": null,
                "process_performance_data": null,
                "freshness_checks_enabled": null,
                "freshness_threshold": null,
                "passive_checks_enabled": null,
                "event_handler_enabled": null,
                "active_checks_enabled": null,
                "notifications_enabled": null,
                "notes": null,
                "priority": null,
                "tags": null,
                "own_contacts": "0",
                "own_contactgroups": "0",
                "own_customvariables": "0",
                "service_url": null,
                "service_type": "1",
                "disabled": "0",
                "created": "2015-01-16 00:46:39",
                "modified": "2015-01-16 00:46:39"
            },
            {
                "id": "3",
                "uuid": "1c045407-5502-4468-aabc-7781f6cf3dec",
                "servicetemplate_id": "9",
                "host_id": "1",
                "name": null,
                "description": null,
                "command_id": null,
                "check_command_args": "",
                "eventhandler_command_id": null,
                "notify_period_id": null,
                "check_period_id": null,
                "check_interval": null,
                "retry_interval": null,
                "max_check_attempts": null,
                "first_notification_delay": null,
                "notification_interval": null,
                "notify_on_warning": null,
                "notify_on_unknown": null,
                "notify_on_critical": null,
                "notify_on_recovery": null,
                "notify_on_flapping": null,
                "notify_on_downtime": null,
                "is_volatile": null,
                "flap_detection_enabled": null,
                "flap_detection_on_ok": null,
                "flap_detection_on_warning": null,
                "flap_detection_on_unknown": null,
                "flap_detection_on_critical": null,
                "low_flap_threshold": null,
                "high_flap_threshold": null,
                "process_performance_data": null,
                "freshness_checks_enabled": null,
                "freshness_threshold": null,
                "passive_checks_enabled": null,
                "event_handler_enabled": null,
                "active_checks_enabled": null,
                "notifications_enabled": null,
                "notes": null,
                "priority": null,
                "tags": null,
                "own_contacts": "0",
                "own_contactgroups": "0",
                "own_customvariables": "0",
                "service_url": null,
                "service_type": "1",
                "disabled": "0",
                "created": "2015-01-16 00:46:52",
                "modified": "2015-01-16 00:46:52"
            },
            {
                "id": "4",
                "uuid": "7391f1aa-5e2e-447a-8a9b-b23357b9cd2a",
                "servicetemplate_id": "13",
                "host_id": "1",
                "name": null,
                "description": null,
                "command_id": null,
                "check_command_args": "",
                "eventhandler_command_id": null,
                "notify_period_id": null,
                "check_period_id": null,
                "check_interval": null,
                "retry_interval": null,
                "max_check_attempts": null,
                "first_notification_delay": null,
                "notification_interval": null,
                "notify_on_warning": null,
                "notify_on_unknown": null,
                "notify_on_critical": null,
                "notify_on_recovery": null,
                "notify_on_flapping": null,
                "notify_on_downtime": null,
                "is_volatile": null,
                "flap_detection_enabled": null,
                "flap_detection_on_ok": null,
                "flap_detection_on_warning": null,
                "flap_detection_on_unknown": null,
                "flap_detection_on_critical": null,
                "low_flap_threshold": null,
                "high_flap_threshold": null,
                "process_performance_data": null,
                "freshness_checks_enabled": null,
                "freshness_threshold": null,
                "passive_checks_enabled": null,
                "event_handler_enabled": null,
                "active_checks_enabled": null,
                "notifications_enabled": null,
                "notes": null,
                "priority": null,
                "tags": null,
                "own_contacts": "0",
                "own_contactgroups": "0",
                "own_customvariables": "0",
                "service_url": null,
                "service_type": "1",
                "disabled": "0",
                "created": "2015-01-16 00:47:06",
                "modified": "2015-01-16 00:47:06"
            },
            {
                "id": "83",
                "uuid": "d2ae5fde-5116-4157-ba41-f1a6e77c1165",
                "servicetemplate_id": "78",
                "host_id": "1",
                "name": null,
                "description": null,
                "command_id": null,
                "check_command_args": "",
                "eventhandler_command_id": null,
                "notify_period_id": null,
                "check_period_id": null,
                "check_interval": null,
                "retry_interval": null,
                "max_check_attempts": null,
                "first_notification_delay": null,
                "notification_interval": null,
                "notify_on_warning": null,
                "notify_on_unknown": null,
                "notify_on_critical": null,
                "notify_on_recovery": null,
                "notify_on_flapping": null,
                "notify_on_downtime": null,
                "is_volatile": null,
                "flap_detection_enabled": null,
                "flap_detection_on_ok": null,
                "flap_detection_on_warning": null,
                "flap_detection_on_unknown": null,
                "flap_detection_on_critical": null,
                "low_flap_threshold": null,
                "high_flap_threshold": null,
                "process_performance_data": null,
                "freshness_checks_enabled": null,
                "freshness_threshold": null,
                "passive_checks_enabled": null,
                "event_handler_enabled": null,
                "active_checks_enabled": null,
                "notifications_enabled": null,
                "notes": null,
                "priority": null,
                "tags": null,
                "own_contacts": "0",
                "own_contactgroups": "0",
                "own_customvariables": "0",
                "service_url": null,
                "service_type": "1",
                "disabled": "0",
                "created": "2015-11-13 14:30:59",
                "modified": "2015-11-13 14:30:59"
            }
        ],
        "Customvariable": [

        ],
        "Hostcommandargumentvalue": [

        ],
        "Contactgroup": [

        ],
        "Contact": [

        ],
        "Parenthost": [

        ],
        "Hostgroup": [

        ],
        "Hoststatus": {
            "hoststatus_id": "27",
            "instance_id": "1",
            "host_object_id": "37",
            "status_update_time": "2015-11-26 15:55:12",
            "output": "OK - 127.0.0.1: rta 0,084ms, lost 0%",
            "long_output": null,
            "perfdata": "rta=0,084ms;3000,000;5000,000;0; pl=0%;80;100;; rtmax=0,084ms;;;; rtmin=0,084ms;;;;",
            "current_state": "0",
            "has_been_checked": "1",
            "should_be_scheduled": "1",
            "current_check_attempt": "1",
            "max_check_attempts": "3",
            "last_check": "2015-11-26 15:55:12",
            "next_check": "2015-11-26 17:55:12",
            "check_type": "0",
            "last_state_change": "2015-08-27 00:04:48",
            "last_hard_state_change": "2015-08-27 00:04:48",
            "last_hard_state": "0",
            "last_time_up": "2015-11-26 15:55:12",
            "last_time_down": "2015-08-26 00:04:43",
            "last_time_unreachable": "2015-08-25 23:49:25",
            "state_type": "1",
            "last_notification": "1970-01-01 01:00:00",
            "next_notification": "1970-01-01 01:00:00",
            "no_more_notifications": "0",
            "notifications_enabled": "1",
            "problem_has_been_acknowledged": "0",
            "acknowledgement_type": "0",
            "current_notification_number": "0",
            "passive_checks_enabled": "1",
            "active_checks_enabled": "1",
            "event_handler_enabled": "1",
            "flap_detection_enabled": "0",
            "is_flapping": "0",
            "percent_state_change": "0",
            "latency": "0.000269",
            "execution_time": "0.002941",
            "scheduled_downtime_depth": "0",
            "failure_prediction_enabled": "0",
            "process_performance_data": "0",
            "obsess_over_host": "1",
            "modified_host_attributes": "0",
            "event_handler": null,
            "check_command": "5a538ebc-03de-4ce6-8e32-665b841abde3!3000.0,80%!5000.0,100%",
            "normal_check_interval": "7200",
            "retry_check_interval": "60",
            "check_timeperiod_object_id": "30"
        },
        "Objects": {
            "object_id": "37",
            "instance_id": "1",
            "objecttype_id": "1",
            "name1": "c36b8048-93ce-4385-ac19-ab5c90574b77",
            "name2": null,
            "is_active": "1"
        }
    }
}
		</pre>
	</div>
</div>

## Query all hosts that are not monitored:
<div class="input-group">
	<span class="input-group-addon bg-color-green txt-color-white">GET</span>
	<input type="text" class="form-control" readonly="readonly" value="/hosts/notMonitored.json">
</div>
<br />
<div class="panel panel-primary">
	<div class="panel-heading">
		<h3 class="panel-title">Response: application/json</h3>
	</div>
	<div class="panel-body">
		<pre>
{
    "all_hosts": [
        {
            "Host": {
                "id": "6",
                "uuid": "44acf8ac-77f1-470f-b64e-a93b86ba1463",
                "name": "Just a randome host",
                "description": null,
                "active_checks_enabled": null,
                "address": "127.0.0.2",
                "satellite_id": "0"
            },
            "Hosttemplate": {
                "id": "1",
                "uuid": "efbee68c-cf48-4b78-83f5-c856c56177f0",
                "name": "default host",
                "description": "default host",
                "active_checks_enabled": "1"
            },
            "Container": [
                {
                    "id": "1",
                    "containertype_id": "1",
                    "name": "root",
                    "parent_id": null,
                    "lft": "1",
                    "rght": "42",
                    "HostsToContainer": {
                        "id": "9",
                        "host_id": "6",
                        "container_id": "1"
                    }
                }
            ]
        }
    ]
}
		</pre>
	</div>
</div>

## Query all disabled hosts:
<div class="input-group">
	<span class="input-group-addon bg-color-green txt-color-white">GET</span>
	<input type="text" class="form-control" readonly="readonly" value="/hosts/disabled.json">
</div>
<br />
<div class="panel panel-primary">
	<div class="panel-heading">
		<h3 class="panel-title">Response: application/json</h3>
	</div>
	<div class="panel-body">
		<pre>
{
    "disabledHosts": [
        {
            "Host": {
                "id": "5",
                "uuid": "d5b44947-b9a9-4565-b70b-1fd804cf291c",
                "name": "srvkvm02.local.lan",
                "address": "192.168.1.2",
                "satellite_id": "0"
            },
            "Hosttemplate": {
                "name": "default host",
                "id": "1"
            }
        }
    ]
}
		</pre>
	</div>
</div>


## Query all deleted hosts:
<div class="input-group">
	<span class="input-group-addon bg-color-green txt-color-white">GET</span>
	<input type="text" class="form-control" readonly="readonly" value="/deleted_hosts/index.json">
</div>
<br />
<div class="panel panel-primary">
	<div class="panel-heading">
		<h3 class="panel-title">Response: application/json</h3>
	</div>
	<div class="panel-body">
		<pre>
{
    "deletedHosts": [
        {
            "DeletedHost": {
                "id": "1",
                "uuid": "5283bd45-2b3d-41c2-8bca-e4cee75e3372",
                "hosttemplate_id": "2",
                "host_id": "3",
                "name": "srvkvm03.local.lan",
                "description": "KVM03",
                "deleted_perfdata": "1",
                "created": "2015-08-27 15:20:18",
                "modified": "2015-08-27 15:20:18"
            }
        },
        {
            "DeletedHost": {
                "id": "2",
                "uuid": "f11de8ed-8065-49aa-9d27-9f247abe22b2",
                "hosttemplate_id": "4",
                "host_id": "18",
                "name": "srvkvm04.local.lan",
                "description": "KVM04",
                "deleted_perfdata": "1",
                "created": "2015-10-22 11:46:22",
                "modified": "2015-10-22 11:46:22"
            }
        },
    ]
}
		</pre>
	</div>
</div>
