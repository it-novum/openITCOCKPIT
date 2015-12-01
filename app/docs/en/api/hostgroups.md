## Query all existing servicetemplategroups:

<div class="input-group">
	<span class="input-group-addon bg-color-green txt-color-white">GET</span>
	<input type="text" class="form-control" readonly="readonly" value="/hostgroups.json">
</div>
<br />
<div class="panel panel-primary">
	<div class="panel-heading">
		<h3 class="panel-title">Response: application/json</h3>
	</div>
	<div class="panel-body">
		<pre>
{
    "all_hostgroups": [
        {
            "Hostgroup": {
                "id": "1",
                "uuid": "aff8f698-3e16-48a1-8442-418ac164129c",
                "container_id": "3",
                "description": "",
                "hostgroup_url": ""
            },
            "Container": {
                "id": "3",
                "containertype_id": "7",
                "name": "Demo hostgroup",
                "parent_id": "1",
                "lft": "4",
                "rght": "5"
            },
            "Host": [
                {
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
                    "modified": "2015-01-15 19:26:32",
                    "HostsToHostgroup": {
                        "id": "4",
                        "host_id": "1",
                        "hostgroup_id": "1"
                    }
                }
            ]
        }
    ]
}
		</pre>
	</div>
</div>

## Query a hostgroup by id:
<div class="input-group">
	<span class="input-group-addon bg-color-green txt-color-white">GET</span>
	<input type="text" class="form-control" readonly="readonly" value="/hostgroups/1.json">
</div>
<br />
<div class="panel panel-primary">
	<div class="panel-heading">
		<h3 class="panel-title">Response: application/json</h3>
	</div>
	<div class="panel-body">
		<pre>
{
    "hostgroup": {
        "Hostgroup": {
            "id": "1",
            "uuid": "aff8f698-3e16-48a1-8442-418ac164129c",
            "container_id": "3",
            "description": "",
            "hostgroup_url": ""
        },
        "Container": {
            "id": "3",
            "containertype_id": "7",
            "name": "Demo hostgroup",
            "parent_id": "1",
            "lft": "4",
            "rght": "5"
        },
        "Host": [
            {
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
                "modified": "2015-01-15 19:26:32",
                "HostsToHostgroup": {
                    "id": "4",
                    "host_id": "1",
                    "hostgroup_id": "1"
                }
            }
        ]
    }
}
		</pre>
	</div>
</div>

