## Query all existing contact groups:

<div class="input-group">
	<span class="input-group-addon bg-color-green txt-color-white">GET</span>
	<input type="text" class="form-control" readonly="readonly" value="/contactgroups.json">
</div>
<br />
<div class="panel panel-primary">
	<div class="panel-heading">
		<h3 class="panel-title">Response: application/json</h3>
	</div>
	<div class="panel-body">
		<pre>
{
    "all_contactgroups": [
        {
            "Contactgroup": {
                "id": "1",
                "uuid": "ae4d5cf1-e386-49cd-ac59-6d6193a5c5c0",
                "container_id": "7",
                "description": "Infoaccount"
            },
            "Container": {
                "id": "7",
                "containertype_id": "6",
                "name": "info",
                "parent_id": "1",
                "lft": "32",
                "rght": "33"
            },
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
                    "ContactsToContactgroup": {
                        "id": "1",
                        "contact_id": "6",
                        "contactgroup_id": "1"
                    }
                }
            ]
        }
    ]
}
		</pre>
	</div>
</div>

## Query a contact group by id:
<div class="input-group">
	<span class="input-group-addon bg-color-green txt-color-white">GET</span>
	<input type="text" class="form-control" readonly="readonly" value="/contactgroups/1.json">
</div>
<br />
<div class="panel panel-primary">
	<div class="panel-heading">
		<h3 class="panel-title">Response: application/json</h3>
	</div>
	<div class="panel-body">
		<pre>
{
    "contactgroup": {
        "Contactgroup": {
            "id": "1",
            "uuid": "ae4d5cf1-e386-49cd-ac59-6d6193a5c5c0",
            "container_id": "7",
            "description": "Infoaccount"
        },
        "Container": {
            "id": "7",
            "containertype_id": "6",
            "name": "info",
            "parent_id": "1",
            "lft": "32",
            "rght": "33"
        },
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
                "ContactsToContactgroup": {
                    "id": "1",
                    "contact_id": "6",
                    "contactgroup_id": "1"
                }
            }
        ]
    }
}
		</pre>
	</div>
</div>

