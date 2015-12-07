## Query all existing servicedependencies:

<div class="input-group">
	<span class="input-group-addon bg-color-green txt-color-white">GET</span>
	<input type="text" class="form-control" readonly="readonly" value="/servicedependencies.json">
</div>
<br />
<div class="panel panel-primary">
	<div class="panel-heading">
		<h3 class="panel-title">Response: application/json</h3>
	</div>
	<div class="panel-body">
		<pre>
{
    "all_servicedependencies": [
        {
            "Servicedependency": {
                "id": "1",
                "uuid": "07800a4f-b68a-485b-be81-f7b44f7a9b1e",
                "container_id": "1",
                "inherits_parent": "1",
                "timeperiod_id": "1",
                "execution_fail_on_ok": "1",
                "execution_fail_on_warning": "0",
                "execution_fail_on_unknown": "1",
                "execution_fail_on_critical": "0",
                "execution_fail_on_pending": "0",
                "execution_none": "0",
                "notification_fail_on_ok": "1",
                "notification_fail_on_warning": "1",
                "notification_fail_on_unknown": "0",
                "notification_fail_on_critical": "0",
                "notification_fail_on_pending": "0",
                "notification_none": "0",
                "created": "2015-12-07 15:42:19",
                "modified": "2015-12-07 15:42:19"
            },
            "Timeperiod": {
                "name": "24x7"
            },
            "ServicedependencyServiceMembership": [
                {
                    "id": "1",
                    "service_id": "1",
                    "servicedependency_id": "1",
                    "dependent": "0",
                    "Service": {
                        "name": null,
                        "host_id": "1",
                        "servicetemplate_id": "1",
                        "Host": {
                            "id": "1",
                            "name": "localhost"
                        },
                        "Servicetemplate": {
                            "name": "Ping"
                        }
                    }
                },
                {
                    "id": "2",
                    "service_id": "5",
                    "servicedependency_id": "1",
                    "dependent": "1",
                    "Service": {
                        "name": null,
                        "host_id": "2",
                        "servicetemplate_id": "1",
                        "Host": {
                            "id": "2",
                            "name": "srvoitcvbox01.master.dns"
                        },
                        "Servicetemplate": {
                            "name": "Ping"
                        }
                    }
                }
            ],
            "ServicedependencyServicegroupMembership": [

            ]
        }
    ]
}
		</pre>
	</div>
</div>

## Query a servicedependency by id:
<div class="input-group">
	<span class="input-group-addon bg-color-green txt-color-white">GET</span>
	<input type="text" class="form-control" readonly="readonly" value="/servicedependencies/1.json">
</div>
<br />
<div class="panel panel-primary">
	<div class="panel-heading">
		<h3 class="panel-title">Response: application/json</h3>
	</div>
	<div class="panel-body">
		<pre>
{
    "servicedependency": {
        "Servicedependency": {
            "id": "1",
            "uuid": "07800a4f-b68a-485b-be81-f7b44f7a9b1e",
            "container_id": "1",
            "inherits_parent": "1",
            "timeperiod_id": "1",
            "execution_fail_on_ok": "1",
            "execution_fail_on_warning": "0",
            "execution_fail_on_unknown": "1",
            "execution_fail_on_critical": "0",
            "execution_fail_on_pending": "0",
            "execution_none": "0",
            "notification_fail_on_ok": "1",
            "notification_fail_on_warning": "1",
            "notification_fail_on_unknown": "0",
            "notification_fail_on_critical": "0",
            "notification_fail_on_pending": "0",
            "notification_none": "0",
            "created": "2015-12-07 15:42:19",
            "modified": "2015-12-07 15:42:19"
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
        "Container": {
            "id": "1",
            "containertype_id": "1",
            "name": "root",
            "parent_id": null,
            "lft": "1",
            "rght": "46"
        },
        "ServicedependencyServiceMembership": [
            {
                "id": "1",
                "service_id": "1",
                "servicedependency_id": "1",
                "dependent": "0"
            },
            {
                "id": "2",
                "service_id": "5",
                "servicedependency_id": "1",
                "dependent": "1"
            }
        ],
        "ServicedependencyServicegroupMembership": [

        ]
    }
}
		</pre>
	</div>
</div>

