## Query all existing contacts:

<div class="input-group">
	<span class="input-group-addon bg-color-green txt-color-white">GET</span>
	<input type="text" class="form-control" readonly="readonly" value="/contacts.json">
</div>
<br />
<div class="panel panel-primary">
	<div class="panel-heading">
		<h3 class="panel-title">Response: application/json</h3>
	</div>
	<div class="panel-body">
		<pre>
{
    "all_contacts": [
        {
            "Contact": {
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
                "notify_host_downtime": "0"
            },
            "Container": [
                {
                    "id": "1",
                    "containertype_id": "1",
                    "name": "root",
                    "parent_id": null,
                    "lft": "1",
                    "rght": "38",
                    "ContactsToContainer": {
                        "id": "3",
                        "contact_id": "1",
                        "container_id": "1"
                    }
                }
            ],
            "allowEdit": true
        },
    ]
}
		</pre>
	</div>
</div>

## Query a contact by id:
<div class="input-group">
	<span class="input-group-addon bg-color-green txt-color-white">GET</span>
	<input type="text" class="form-control" readonly="readonly" value="/contacts/1.json">
</div>
<br />
<div class="panel panel-primary">
	<div class="panel-heading">
		<h3 class="panel-title">Response: application/json</h3>
	</div>
	<div class="panel-body">
		<pre>
{
    "contact": {
        "Contact": {
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
            "notify_host_downtime": "0"
        },
        "HostTimeperiod": {
            "id": "1",
            "uuid": "41012866-6114-4853-9caf-6ffd19954e50",
            "container_id": "1",
            "name": "24x7",
            "description": "24x7",
            "created": "2015-01-05 15:11:46",
            "modified": "2015-01-05 15:11:46"
        },
        "ServiceTimeperiod": {
            "id": "1",
            "uuid": "41012866-6114-4853-9caf-6ffd19954e50",
            "container_id": "1",
            "name": "24x7",
            "description": "24x7",
            "created": "2015-01-05 15:11:46",
            "modified": "2015-01-05 15:11:46"
        },
        "Container": [
            {
                "id": "1",
                "containertype_id": "1",
                "name": "root",
                "parent_id": null,
                "lft": "1",
                "rght": "38",
                "ContactsToContainer": {
                    "id": "3",
                    "contact_id": "1",
                    "container_id": "1"
                }
            }
        ],
        "HostCommands": [
            {
                "id": "1",
                "name": "host-notify-by-cake",
                "command_line": "\/usr\/share\/openitcockpit\/app\/Console\/cake nagios_notification -q --type Host --notificationtype $NOTIFICATIONTYPE$ --hostname \"$HOSTNAME$\" --hoststate \"$HOSTSTATE$\" --hostaddress \"$HOSTADDRESS$\" --hostoutput \"$HOSTOUTPUT$\" --contactmail \"$CONTACTEMAIL$\" --contactalias \"$CONTACTALIAS$\"",
                "command_type": "3",
                "human_args": null,
                "uuid": "a13ff7f1-0642-4a11-be05-9931ca98da10",
                "description": "Send a host notification as mail",
                "ContactsToHostcommand": {
                    "id": "3",
                    "contact_id": "1",
                    "command_id": "1"
                }
            }
        ],
        "ServiceCommands": [
            {
                "id": "2",
                "name": "service-notify-by-cake",
                "command_line": "\/usr\/share\/openitcockpit\/app\/Console\/cake nagios_notification -q --type Service --notificationtype $NOTIFICATIONTYPE$ --hostname \"$HOSTNAME$\" --hoststate \"$HOSTSTATE$\" --hostaddress \"$HOSTADDRESS$\" --hostoutput \"$HOSTOUTPUT$\" --contactmail \"$CONTACTEMAIL$\" --contactalias \"$CONTACTALIAS$\" --servicedesc \"$SERVICEDESC$\" --servicestate \"$SERVICESTATE$\" --serviceoutput \"$SERVICEOUTPUT$\"",
                "command_type": "3",
                "human_args": null,
                "uuid": "a517bbb6-f299-4b57-9865-a4e0b70597e4",
                "description": "Send a service notificationa s mail",
                "ContactsToServicecommand": {
                    "id": "3",
                    "contact_id": "1",
                    "command_id": "2"
                }
            }
        ],
        "Contactgroup": [
            {
                "id": "1",
                "uuid": "ae4d5cf1-e386-49cd-ac59-6d6193a5c5c0",
                "container_id": "7",
                "description": "Infoaccount",
                "ContactsToContainer": {
                    "id": "3",
                    "contact_id": "1",
                    "container_id": "1"
                }
            }
        ]
    }
}
		</pre>
	</div>
</div>

