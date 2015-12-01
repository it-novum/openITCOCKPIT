## Query all existing services:

<div class="input-group">
	<span class="input-group-addon bg-color-green txt-color-white">GET</span>
	<input type="text" class="form-control" readonly="readonly" value="/services.json">
</div>
<br />
<div class="panel panel-primary">
	<div class="panel-heading">
		<h3 class="panel-title">Response: application/json</h3>
	</div>
	<div class="panel-body">
		<pre>
{
    "all_services": [
        {
            "Service": {
                "id": "3",
                "uuid": "1c045407-5502-4468-aabc-7781f6cf3dec",
                "name": null,
                "description": null,
                "active_checks_enabled": null
            },
            "Servicestatus": {
                "current_state": "0",
                "last_check": "2015-12-01 14:51:27",
                "next_check": "2015-12-01 14:56:27",
                "last_hard_state_change": "2015-08-26 23:49:30",
                "output": "OK - load average: 0.27, 0.23, 0.24",
                "scheduled_downtime_depth": "0",
                "active_checks_enabled": "1",
                "state_type": "1",
                "problem_has_been_acknowledged": "0",
                "is_flapping": "0"
            },
            "Servicetemplate": {
                "id": "9",
                "uuid": "c673cc09-c46a-4916-8e42-85cb0e68f1e6",
                "name": "CHECK_LOCAL_LOAD",
                "description": "Checks the CPU load of the openITCOCKPIT Server",
                "active_checks_enabled": "1"
            },
            "Host": {
                "name": "localhost",
                "id": "1",
                "uuid": "c36b8048-93ce-4385-ac19-ab5c90574b77",
                "description": null,
                "address": "127.0.0.1"
            },
            "Hoststatus": {
                "current_state": "0"
            },
            "HostsToContainers": {
                "container_id": "1"
            }
        },
        {
            "Service": {
                "id": "1",
                "uuid": "74fd8f59-1348-4e16-85f0-4a5c57c7dd62",
                "name": null,
                "description": null,
                "active_checks_enabled": null
            },
            "Servicestatus": {
                "current_state": "0",
                "last_check": "2015-12-01 14:51:57",
                "next_check": "2015-12-01 14:56:57",
                "last_hard_state_change": "2015-08-26 23:49:30",
                "output": "PING OK - Packet loss = 0%, RTA = 0.05 ms",
                "scheduled_downtime_depth": "0",
                "active_checks_enabled": "1",
                "state_type": "1",
                "problem_has_been_acknowledged": "0",
                "is_flapping": "0"
            },
            "Servicetemplate": {
                "id": "1",
                "uuid": "3eb9db30-c9cf-4c25-9c69-0c3c01dc2256",
                "name": "Ping",
                "description": "Lan-Ping",
                "active_checks_enabled": "1"
            },
            "Host": {
                "name": "localhost",
                "id": "1",
                "uuid": "c36b8048-93ce-4385-ac19-ab5c90574b77",
                "description": null,
                "address": "127.0.0.1"
            },
            "Hoststatus": {
                "current_state": "0"
            },
            "HostsToContainers": {
                "container_id": "1"
            }
        }
    ]
}
		</pre>
	</div>
</div>

## Query a service by id:
<div class="input-group">
	<span class="input-group-addon bg-color-green txt-color-white">GET</span>
	<input type="text" class="form-control" readonly="readonly" value="/services/1.json">
</div>
<br />
<div class="panel panel-primary">
	<div class="panel-heading">
		<h3 class="panel-title">Response: application/json</h3>
	</div>
	<div class="panel-body">
		<pre>
{
    "service": {
        "Service": {
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
        "EventhandlerCommand": {
            "id": null,
            "name": null,
            "command_line": null,
            "command_type": null,
            "human_args": null,
            "uuid": null,
            "description": null
        },
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
        "Servicetemplate": {
            "id": "1",
            "uuid": "3eb9db30-c9cf-4c25-9c69-0c3c01dc2256",
            "name": "Ping",
            "container_id": "1",
            "servicetemplatetype_id": "1",
            "check_period_id": "1",
            "notify_period_id": "1",
            "description": "Lan-Ping",
            "command_id": "3",
            "check_command_args": "",
            "checkcommand_info": "",
            "eventhandler_command_id": "0",
            "timeperiod_id": "0",
            "check_interval": "300",
            "retry_interval": "60",
            "max_check_attempts": "3",
            "first_notification_delay": "0",
            "notification_interval": "7200",
            "notify_on_warning": "0",
            "notify_on_unknown": "0",
            "notify_on_critical": "1",
            "notify_on_recovery": true,
            "notify_on_flapping": "0",
            "notify_on_downtime": "0",
            "flap_detection_enabled": "0",
            "flap_detection_on_ok": "0",
            "flap_detection_on_warning": "0",
            "flap_detection_on_unknown": "0",
            "flap_detection_on_critical": false,
            "low_flap_threshold": "0",
            "high_flap_threshold": "0",
            "process_performance_data": "1",
            "freshness_checks_enabled": "0",
            "freshness_threshold": null,
            "passive_checks_enabled": "0",
            "event_handler_enabled": "0",
            "active_checks_enabled": "1",
            "retain_status_information": "0",
            "retain_nonstatus_information": "0",
            "notifications_enabled": "0",
            "notes": "",
            "priority": "1",
            "tags": "",
            "service_url": "",
            "is_volatile": false,
            "check_freshness": false,
            "created": "2015-01-05 15:20:14",
            "modified": "2015-01-15 23:46:17"
        },
        "Servicecommandargumentvalue": [

        ],
        "Serviceeventcommandargumentvalue": [

        ],
        "ServiceEscalationServiceMembership": [

        ],
        "ServicedependencyServiceMembership": [

        ],
        "Customvariable": [

        ],
        "Widget": [

        ],
        "Contactgroup": [

        ],
        "Contact": [

        ],
        "Servicegroup": [

        ],
        "Servicestatus": {
            "servicestatus_id": "3",
            "instance_id": "1",
            "service_object_id": "41",
            "status_update_time": "2015-12-01 15:12:01",
            "output": "PING OK - Packet loss = 0%, RTA = 0.08 ms",
            "long_output": null,
            "perfdata": "rta=0.077000ms;100.000000;500.000000;0.000000 pl=0%;20;60;0",
            "current_state": "0",
            "has_been_checked": "1",
            "should_be_scheduled": "1",
            "current_check_attempt": "1",
            "max_check_attempts": "3",
            "last_check": "2015-12-01 15:11:57",
            "next_check": "2015-12-01 15:16:57",
            "check_type": "0",
            "last_state_change": "2015-08-26 23:49:30",
            "last_hard_state_change": "2015-08-26 23:49:30",
            "last_hard_state": "0",
            "last_time_ok": "2015-12-01 15:11:57",
            "last_time_warning": "1970-01-01 01:00:00",
            "last_time_unknown": "2015-08-25 23:49:25",
            "last_time_critical": "1970-01-01 01:00:00",
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
            "latency": "0.000136",
            "execution_time": "4.00015",
            "scheduled_downtime_depth": "0",
            "failure_prediction_enabled": "0",
            "process_performance_data": "1",
            "obsess_over_service": "1",
            "modified_service_attributes": "0",
            "event_handler": null,
            "check_command": "cdd9ba25-a4d8-4261-a551-32164d4dde14!100.0,20%!500.0,60%",
            "normal_check_interval": "300",
            "retry_check_interval": "60",
            "check_timeperiod_object_id": "30"
        },
        "Objects": {
            "object_id": "41",
            "instance_id": "1",
            "objecttype_id": "2",
            "name1": "c36b8048-93ce-4385-ac19-ab5c90574b77",
            "name2": "74fd8f59-1348-4e16-85f0-4a5c57c7dd62",
            "is_active": "1"
        }
    }
}
		</pre>
	</div>
</div>

## Query all services that are not monitored:
<div class="input-group">
	<span class="input-group-addon bg-color-green txt-color-white">GET</span>
	<input type="text" class="form-control" readonly="readonly" value="/services/notMonitored.json">
</div>
<br />
<div class="panel panel-primary">
	<div class="panel-heading">
		<h3 class="panel-title">Response: application/json</h3>
	</div>
	<div class="panel-body">
		<pre>
{
    "all_services": [
        {
            "Service": {
                "id": "84",
                "uuid": "313dcf88-c48d-4c5d-85bf-341838fc8d59",
                "name": null,
                "description": null,
                "active_checks_enabled": null
            },
            "Servicetemplate": {
                "id": "1",
                "uuid": "3eb9db30-c9cf-4c25-9c69-0c3c01dc2256",
                "name": "Ping",
                "description": "Lan-Ping",
                "active_checks_enabled": "1"
            },
            "ServiceObject": {
                "object_id": null
            },
            "Host": {
                "name": "Just a randome host",
                "id": "6",
                "uuid": "44acf8ac-77f1-470f-b64e-a93b86ba1463",
                "description": null,
                "address": "127.0.0.1"
            }
        }
    ]
}
		</pre>
	</div>
</div>

## Query all disabled services:
<div class="input-group">
	<span class="input-group-addon bg-color-green txt-color-white">GET</span>
	<input type="text" class="form-control" readonly="readonly" value="/services/disabled.json">
</div>
<br />
<div class="panel panel-primary">
	<div class="panel-heading">
		<h3 class="panel-title">Response: application/json</h3>
	</div>
	<div class="panel-body">
		<pre>
{
    "all_services": [
        {
            "Service": {
                "id": "6",
                "uuid": "01faf2de-3a78-46ff-908a-1734fbf8d427",
                "name": null,
                "description": null,
                "active_checks_enabled": null
            },
            "Servicetemplate": {
                "id": "3",
                "uuid": "cd5f7e0a-3682-4734-806d-455f1b296888",
                "name": "CHECK_BY_SSH",
                "description": "Execute a command on a remote host",
                "active_checks_enabled": "1"
            },
            "HostObject": {
                "object_id": "45"
            },
            "Host": {
                "name": "srvkvm02.local.lan",
                "id": "5",
                "uuid": "d5b44947-b9a9-4565-b70b-1fd804cf291c",
                "description": null,
                "address": "192.168.1.2"
            },
            "Hoststatus": {
                "current_state": "0"
            }
        },
    ]
}
		</pre>
	</div>
</div>


## Query all services by host ID:
<div class="input-group">
	<span class="input-group-addon bg-color-green txt-color-white">GET</span>
	<input type="text" class="form-control" readonly="readonly" value="/services/serviceList/1.json">
</div>
<br />
<div class="panel panel-primary">
	<div class="panel-heading">
		<h3 class="panel-title">Response: application/json</h3>
	</div>
	<div class="panel-body">
		<pre>
{
    "all_services": [
        {
            "Service": {
                "id": "2",
                "uuid": "74f14950-a58f-4f18-b6c3-5cfa9dffef4e",
                "name": null,
                "description": null,
                "active_checks_enabled": null
            },
            "Servicestatus": {
                "current_state": "0",
                "last_check": "2015-12-01 15:18:36",
                "next_check": "2015-12-01 15:48:36",
                "last_hard_state_change": "2015-08-26 23:49:30",
                "output": "DISK OK - free space: \/ 1548 MB (23% inode=74%):",
                "scheduled_downtime_depth": "0",
                "active_checks_enabled": "1",
                "state_type": "1",
                "problem_has_been_acknowledged": "0",
                "is_flapping": "0"
            },
            "Servicetemplate": {
                "id": "8",
                "uuid": "354c1e0e-bd09-48e0-bbbe-eb98a4059454",
                "name": "CHECK_LOCAL_DISK",
                "description": "Checks a local disk of the openITCOCKPIT Server",
                "active_checks_enabled": "1"
            },
            "Host": {
                "name": "localhost",
                "id": "1",
                "uuid": "c36b8048-93ce-4385-ac19-ab5c90574b77",
                "description": null,
                "address": "127.0.0.1"
            },
            "Hoststatus": {
                "current_state": "0"
            }
        },
        {
            "Service": {
                "id": "3",
                "uuid": "1c045407-5502-4468-aabc-7781f6cf3dec",
                "name": null,
                "description": null,
                "active_checks_enabled": null
            },
            "Servicestatus": {
                "current_state": "0",
                "last_check": "2015-12-01 15:41:27",
                "next_check": "2015-12-01 15:46:27",
                "last_hard_state_change": "2015-08-26 23:49:30",
                "output": "OK - load average: 0.09, 0.17, 0.21",
                "scheduled_downtime_depth": "0",
                "active_checks_enabled": "1",
                "state_type": "1",
                "problem_has_been_acknowledged": "0",
                "is_flapping": "0"
            },
            "Servicetemplate": {
                "id": "9",
                "uuid": "c673cc09-c46a-4916-8e42-85cb0e68f1e6",
                "name": "CHECK_LOCAL_LOAD",
                "description": "Checks the CPU load of the openITCOCKPIT Server",
                "active_checks_enabled": "1"
            },
            "Host": {
                "name": "localhost",
                "id": "1",
                "uuid": "c36b8048-93ce-4385-ac19-ab5c90574b77",
                "description": null,
                "address": "127.0.0.1"
            },
            "Hoststatus": {
                "current_state": "0"
            }
        },
        {
            "Service": {
                "id": "4",
                "uuid": "7391f1aa-5e2e-447a-8a9b-b23357b9cd2a",
                "name": null,
                "description": null,
                "active_checks_enabled": null
            },
            "Servicestatus": {
                "current_state": "0",
                "last_check": "2015-12-01 15:42:27",
                "next_check": "2015-12-01 15:47:27",
                "last_hard_state_change": "2015-10-15 21:33:27",
                "output": "USERS OK - 1 users currently logged in",
                "scheduled_downtime_depth": "0",
                "active_checks_enabled": "1",
                "state_type": "1",
                "problem_has_been_acknowledged": "0",
                "is_flapping": "0"
            },
            "Servicetemplate": {
                "id": "13",
                "uuid": "5b0b27c4-6e70-454b-a778-b7b050910abb",
                "name": "CHECK_LOCAL_USERS",
                "description": "Checks how many users are logged in to the openITCOCKPIT server backend",
                "active_checks_enabled": "1"
            },
            "Host": {
                "name": "localhost",
                "id": "1",
                "uuid": "c36b8048-93ce-4385-ac19-ab5c90574b77",
                "description": null,
                "address": "127.0.0.1"
            },
            "Hoststatus": {
                "current_state": "0"
            }
        },
        {
            "Service": {
                "id": "83",
                "uuid": "d2ae5fde-5116-4157-ba41-f1a6e77c1165",
                "name": null,
                "description": null,
                "active_checks_enabled": null
            },
            "Servicestatus": {
                "current_state": "0",
                "last_check": "2015-12-01 15:41:36",
                "next_check": "2015-12-01 15:42:36",
                "last_hard_state_change": "2015-11-13 14:31:46",
                "output": "(No output returned from plugin)",
                "scheduled_downtime_depth": "0",
                "active_checks_enabled": "1",
                "state_type": "1",
                "problem_has_been_acknowledged": "0",
                "is_flapping": "0"
            },
            "Servicetemplate": {
                "id": "78",
                "uuid": "678f2adc-6f07-4c9d-b399-7397773f67f1",
                "name": "check_user",
                "description": "check_user",
                "active_checks_enabled": "1"
            },
            "Host": {
                "name": "localhost",
                "id": "1",
                "uuid": "c36b8048-93ce-4385-ac19-ab5c90574b77",
                "description": null,
                "address": "127.0.0.1"
            },
            "Hoststatus": {
                "current_state": "0"
            }
        },
        {
            "Service": {
                "id": "1",
                "uuid": "74fd8f59-1348-4e16-85f0-4a5c57c7dd62",
                "name": null,
                "description": null,
                "active_checks_enabled": null
            },
            "Servicestatus": {
                "current_state": "0",
                "last_check": "2015-12-01 15:41:57",
                "next_check": "2015-12-01 15:46:57",
                "last_hard_state_change": "2015-08-26 23:49:30",
                "output": "PING OK - Packet loss = 0%, RTA = 0.06 ms",
                "scheduled_downtime_depth": "0",
                "active_checks_enabled": "1",
                "state_type": "1",
                "problem_has_been_acknowledged": "0",
                "is_flapping": "0"
            },
            "Servicetemplate": {
                "id": "1",
                "uuid": "3eb9db30-c9cf-4c25-9c69-0c3c01dc2256",
                "name": "Ping",
                "description": "Lan-Ping",
                "active_checks_enabled": "1"
            },
            "Host": {
                "name": "localhost",
                "id": "1",
                "uuid": "c36b8048-93ce-4385-ac19-ab5c90574b77",
                "description": null,
                "address": "127.0.0.1"
            },
            "Hoststatus": {
                "current_state": "0"
            }
        }
    ]
}
		</pre>
	</div>
</div>
