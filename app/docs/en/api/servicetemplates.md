## Query all existing servicetemplates:

<div class="input-group">
	<span class="input-group-addon bg-color-green txt-color-white">GET</span>
	<input type="text" class="form-control" value="/servicetemplates.json">
</div>
<br />
<div class="panel panel-primary">
	<div class="panel-heading">
		<h3 class="panel-title">Response: application/json</h3>
	</div>
	<div class="panel-body">
		<pre>
{
    "all_servicetemplates": [
        {
            "Servicetemplate": {
                "id": "8",
                "uuid": "354c1e0e-bd09-48e0-bbbe-eb98a4059454",
                "name": "CHECK_LOCAL_DISK",
                "container_id": "1",
                "description": "Checks a local disk of the openITCOCKPIT Server"
            },
            "Container": {
                "id": "1",
                "containertype_id": "1",
                "name": "root",
                "parent_id": null,
                "lft": "1",
                "rght": "42"
            }
        },
        {
            "Servicetemplate": {
                "id": "1",
                "uuid": "3eb9db30-c9cf-4c25-9c69-0c3c01dc2256",
                "name": "Ping",
                "container_id": "1",
                "description": "Lan-Ping"
            },
            "Container": {
                "id": "1",
                "containertype_id": "1",
                "name": "root",
                "parent_id": null,
                "lft": "1",
                "rght": "42"
            }
        }
    ]
}
		</pre>
	</div>
</div>

## Query a servicetemplate by id:
<div class="input-group">
	<span class="input-group-addon bg-color-green txt-color-white">GET</span>
	<input type="text" class="form-control" value="/servicetemplates/1.json">
</div>
<br />
<div class="panel panel-primary">
	<div class="panel-heading">
		<h3 class="panel-title">Response: application/json</h3>
	</div>
	<div class="panel-body">
		<pre>
{
    "servicetemplate": {
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
        "Container": {
            "id": "1",
            "containertype_id": "1",
            "name": "root",
            "parent_id": null,
            "lft": "1",
            "rght": "42"
        },
        "CheckPeriod": {
            "id": "1",
            "uuid": "41012866-6114-4853-9caf-6ffd19954e50",
            "container_id": "1",
            "name": "24x7",
            "description": "24x7",
            "created": "2015-01-05 15:11:46",
            "modified": "2015-01-05 15:11:46"
        },
        "NotifyPeriod": {
            "id": "1",
            "uuid": "41012866-6114-4853-9caf-6ffd19954e50",
            "container_id": "1",
            "name": "24x7",
            "description": "24x7",
            "created": "2015-01-05 15:11:46",
            "modified": "2015-01-05 15:11:46"
        },
        "CheckCommand": {
            "id": "3",
            "name": "check_ping",
            "command_line": "$USER1$\/check_ping -H $HOSTADDRESS$ -w $ARG1$ -c $ARG2$ -p 5",
            "command_type": "1",
            "human_args": null,
            "uuid": "cdd9ba25-a4d8-4261-a551-32164d4dde14",
            "description": ""
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
        "Customvariable": [

        ],
        "Servicetemplatecommandargumentvalue": [
            {
                "id": "1",
                "commandargument_id": "1",
                "servicetemplate_id": "1",
                "value": "100.0,20%",
                "created": "2015-01-05 15:20:14",
                "modified": "2015-01-15 23:46:17"
            },
            {
                "id": "2",
                "commandargument_id": "2",
                "servicetemplate_id": "1",
                "value": "500.0,60%",
                "created": "2015-01-05 15:20:14",
                "modified": "2015-01-15 23:46:17"
            }
        ],
        "Servicetemplateeventcommandargumentvalue": [

        ],
        "Contactgroup": [

        ],
        "Contact": [
            {
                "id": "1",
                "uuid": "152aecaf-e981-4b0b-8e05-86972868547d",
                "name": "info",
                "description": "info contact",
                "email": "openitcockpit@localhost.local",
                "phone": "",
                "host_timeperiod_id": "1",
                "service_timeperiod_id": "1",
                "host_notifications_enabled": "0",
                "service_notifications_enabled": "0",
                "notify_service_recovery": "1",
                "notify_service_warning": "1",
                "notify_service_unknown": "1",
                "notify_service_critical": "1",
                "notify_service_flapping": "0",
                "notify_service_downtime": "0",
                "notify_host_recovery": "1",
                "notify_host_down": "1",
                "notify_host_unreachable": "1",
                "notify_host_flapping": "0",
                "notify_host_downtime": "0",
                "ContactsToServicetemplate": {
                    "id": "3",
                    "contact_id": "1",
                    "servicetemplate_id": "1"
                }
            }
        ]
    }
}
		</pre>
	</div>
</div>

## Query all services that are using the given servicetemplate:
<div class="input-group">
	<span class="input-group-addon bg-color-green txt-color-white">GET</span>
	<input type="text" class="form-control" value="/servicetemplates/usedBy/1.json">
</div>
<br />
<div class="panel panel-primary">
	<div class="panel-heading">
		<h3 class="panel-title">Response: application/json</h3>
	</div>
	<div class="panel-body">
		<pre>
{
    "all_services": {
        "1": {
            "1": {
                "Service": {
                    "id": "1",
                    "host_id": "1",
                    "name": null
                },
                "Servicetemplate": {
                    "id": "1",
                    "name": "Ping"
                }
            }
        },
        "6": {
            "84": {
                "Service": {
                    "id": "84",
                    "host_id": "6",
                    "name": null
                },
                "Servicetemplate": {
                    "id": "1",
                    "name": "Ping"
                }
            }
        },
        "2": {
            "5": {
                "Service": {
                    "id": "5",
                    "host_id": "2",
                    "name": null
                },
                "Servicetemplate": {
                    "id": "1",
                    "name": "Ping"
                }
            }
        }
    }
}
		</pre>
	</div>
</div>
