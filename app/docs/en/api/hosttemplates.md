## Query all existing hosttemplates:

<div class="input-group">
	<span class="input-group-addon bg-color-green txt-color-white">GET</span>
	<input type="text" class="form-control" readonly="readonly" value="/hosttemplates.json">
</div>
<br />
<div class="panel panel-primary">
	<div class="panel-heading">
		<h3 class="panel-title">Response: application/json</h3>
	</div>
	<div class="panel-body">
		<pre>
{
    "all_hosttemplates": [
        {
            "Hosttemplate": {
                "id": "1",
                "uuid": "efbee68c-cf48-4b78-83f5-c856c56177f0",
                "name": "default host",
                "description": "default host",
                "container_id": "1"
            },
            "Container": {
                "id": "1",
                "parent_id": null
            }
        },
        {
            "Hosttemplate": {
                "id": "4",
                "uuid": "21633d6e-2db7-4efd-89ff-a008c3fbcd3e",
                "name": "Personal host template",
                "description": "Contains essential parts of your v2 host configuration.",
                "container_id": "1"
            },
            "Container": {
                "id": "1",
                "parent_id": null
            }
        }
    ]
}
		</pre>
	</div>
</div>

## Query a hosttemplate by id:
<div class="input-group">
	<span class="input-group-addon bg-color-green txt-color-white">GET</span>
	<input type="text" class="form-control" readonly="readonly" value="/hosttemplates/1.json">
</div>
<br />
<div class="panel panel-primary">
	<div class="panel-heading">
		<h3 class="panel-title">Response: application/json</h3>
	</div>
	<div class="panel-body">
		<pre>
{
    "hosttemplate": {
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
            "id": "4",
            "name": "check-host-alive",
            "command_line": "$USER1$\/check_icmp -H $HOSTADDRESS$ -w $ARG1$ -c $ARG2$ -p 1",
            "command_type": "2",
            "human_args": null,
            "uuid": "5a538ebc-03de-4ce6-8e32-665b841abde3",
            "description": ""
        },
        "Customvariable": [

        ],
        "Hosttemplatecommandargumentvalue": [
            {
                "id": "1",
                "commandargument_id": "3",
                "hosttemplate_id": "1",
                "value": "3000.0,80%",
                "created": "2015-01-05 15:22:21",
                "modified": "2015-01-05 15:22:21"
            },
            {
                "id": "2",
                "commandargument_id": "4",
                "hosttemplate_id": "1",
                "value": "5000.0,100%",
                "created": "2015-01-05 15:22:21",
                "modified": "2015-01-05 15:22:21"
            }
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
                "ContactsToHosttemplate": {
                    "id": "1",
                    "contact_id": "1",
                    "hosttemplate_id": "1"
                }
            }
        ]
    }
}
		</pre>
	</div>
</div>

## Query all hosts that are using the given hosttemplate:
<div class="input-group">
	<span class="input-group-addon bg-color-green txt-color-white">GET</span>
	<input type="text" class="form-control" readonly="readonly" value="/hosttemplates/usedBy/1.json">
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
                "address": "127.0.0.1"
            }
        },
        {
            "Host": {
                "id": "2",
                "uuid": "2d24110b-0457-4a0e-a4c0-cb37d171a986",
                "name": "srvkvm01.local.lan",
                "address": "192.168.1.1"
            }
        }
    ]
}
		</pre>
	</div>
</div>
