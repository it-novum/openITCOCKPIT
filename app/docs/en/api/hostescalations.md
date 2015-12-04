## Query all existing hostescalations:

<div class="input-group">
	<span class="input-group-addon bg-color-green txt-color-white">GET</span>
	<input type="text" class="form-control" readonly="readonly" value="/hostescalations.json">
</div>
<br />
<div class="panel panel-primary">
	<div class="panel-heading">
		<h3 class="panel-title">Response: application/json</h3>
	</div>
	<div class="panel-body">
		<pre>
{
    "all_hostescalations": [
        {
            "Hostescalation": {
                "id": "1",
                "uuid": "032d4e5c-96f3-4035-81ab-3bbb5527f221",
                "container_id": "1",
                "timeperiod_id": "1",
                "first_notification": "2",
                "last_notification": "15",
                "notification_interval": "120",
                "escalate_on_recovery": "1",
                "escalate_on_down": "1",
                "escalate_on_unreachable": "0",
                "created": "2015-12-04 11:29:18",
                "modified": "2015-12-04 11:29:18"
            },
            "Timeperiod": {
                "name": "24x7",
                "id": "1"
            },
            "HostescalationHostMembership": [
                {
                    "id": "1",
                    "host_id": "1",
                    "hostescalation_id": "1",
                    "excluded": "0",
                    "Host": {
                        "name": "localhost",
                        "id": "1"
                    }
                }
            ],
            "HostescalationHostgroupMembership": [

            ],
            "Contactgroup": [

            ],
            "Contact": [
                {
                    "name": "openitcockpitSupport",
                    "id": "6",
                    "ContactsToHostescalation": {
                        "id": "1",
                        "contact_id": "6",
                        "hostescalation_id": "1"
                    }
                }
            ]
        }
    ]
}
		</pre>
	</div>
</div>

## Query a hostescalation by id:
<div class="input-group">
	<span class="input-group-addon bg-color-green txt-color-white">GET</span>
	<input type="text" class="form-control" readonly="readonly" value="/hostescalations/1.json">
</div>
<br />
<div class="panel panel-primary">
	<div class="panel-heading">
		<h3 class="panel-title">Response: application/json</h3>
	</div>
	<div class="panel-body">
		<pre>
{
    "hostescalation": {
        "Hostescalation": {
            "id": "1",
            "uuid": "032d4e5c-96f3-4035-81ab-3bbb5527f221",
            "container_id": "1",
            "timeperiod_id": "1",
            "first_notification": "2",
            "last_notification": "15",
            "notification_interval": "120",
            "escalate_on_recovery": "1",
            "escalate_on_down": "1",
            "escalate_on_unreachable": "0",
            "created": "2015-12-04 11:29:18",
            "modified": "2015-12-04 11:29:18"
        },
        "Timeperiod": {
            "id": "1",
            "uuid": "41012866-6114-4853-9caf-6ffd19954e50",
            "container_id": "1",
            "name": "24x7",
            "description": "24x7",
            "created": "2015-01-05 15:11:46",
            "modified": "2015-01-05 15:11:46"
        },
        "HostescalationHostMembership": [
            {
                "id": "1",
                "host_id": "1",
                "hostescalation_id": "1",
                "excluded": "0",
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
                }
            }
        ],
        "HostescalationHostgroupMembership": [

        ],
        "Contactgroup": [

        ],
        "Contact": [
            {
                "id": "6",
                "uuid": "1513a066-e0c4-4265-829d-b8d3781601aa",
                "name": "openitcockpitSupport",
                "description": "openitcockpit Support",
                "email": "openitcockpit@support.it-novum.com",
                "phone": "00491",
                "host_timeperiod_id": "4",
                "service_timeperiod_id": "4",
                "host_notifications_enabled": "1",
                "service_notifications_enabled": "1",
                "notify_service_recovery": "1",
                "notify_service_warning": "1",
                "notify_service_unknown": "1",
                "notify_service_critical": "1",
                "notify_service_flapping": "1",
                "notify_service_downtime": "0",
                "notify_host_recovery": "1",
                "notify_host_down": "1",
                "notify_host_unreachable": "1",
                "notify_host_flapping": "1",
                "notify_host_downtime": "0",
                "ContactsToHostescalation": {
                    "id": "1",
                    "contact_id": "6",
                    "hostescalation_id": "1"
                }
            }
        ]
    }
}
		</pre>
	</div>
</div>

