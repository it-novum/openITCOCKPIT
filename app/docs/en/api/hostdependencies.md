## Query all existing hostdependencies:

<div class="input-group">
	<span class="input-group-addon bg-color-green txt-color-white">GET</span>
	<input type="text" class="form-control" readonly="readonly" value="/hostdependencies.json">
</div>
<br />
<div class="panel panel-primary">
	<div class="panel-heading">
		<h3 class="panel-title">Response: application/json</h3>
	</div>
	<div class="panel-body">
		<pre>
{
    "all_hostdependencies": [
        {
            "Hostdependency": {
                "id": "1",
                "uuid": "9fff08b9-f756-4bde-a621-97078a1824c2",
                "container_id": "1",
                "inherits_parent": "0",
                "timeperiod_id": "1",
                "execution_fail_on_up": "1",
                "execution_fail_on_down": "1",
                "execution_fail_on_unreachable": "0",
                "execution_fail_on_pending": "0",
                "execution_none": "0",
                "notification_fail_on_up": "1",
                "notification_fail_on_down": "0",
                "notification_fail_on_unreachable": "0",
                "notification_fail_on_pending": "0",
                "notification_none": "0",
                "created": "2015-12-07 15:00:56",
                "modified": "2015-12-07 15:02:18"
            },
            "Timeperiod": {
                "name": "24x7"
            },
            "HostdependencyHostMembership": [
                {
                    "id": "3",
                    "host_id": "1",
                    "hostdependency_id": "1",
                    "dependent": "0",
                    "Host": {
                        "name": "localhost"
                    }
                },
                {
                    "id": "4",
                    "host_id": "2",
                    "hostdependency_id": "1",
                    "dependent": "1",
                    "Host": {
                        "name": "srvoitcvbox01.master.dns"
                    }
                }
            ],
            "HostdependencyHostgroupMembership": [

            ]
        }
    ]
}
		</pre>
	</div>
</div>

## Query a hostdependency by id:
<div class="input-group">
	<span class="input-group-addon bg-color-green txt-color-white">GET</span>
	<input type="text" class="form-control" readonly="readonly" value="/hostdependencies/1.json">
</div>
<br />
<div class="panel panel-primary">
	<div class="panel-heading">
		<h3 class="panel-title">Response: application/json</h3>
	</div>
	<div class="panel-body">
		<pre>
{
    "hostdependency": {
        "Hostdependency": {
            "id": "1",
            "uuid": "9fff08b9-f756-4bde-a621-97078a1824c2",
            "container_id": "1",
            "inherits_parent": "0",
            "timeperiod_id": "1",
            "execution_fail_on_up": "1",
            "execution_fail_on_down": "1",
            "execution_fail_on_unreachable": "0",
            "execution_fail_on_pending": "0",
            "execution_none": "0",
            "notification_fail_on_up": "1",
            "notification_fail_on_down": "0",
            "notification_fail_on_unreachable": "0",
            "notification_fail_on_pending": "0",
            "notification_none": "0",
            "created": "2015-12-07 15:00:56",
            "modified": "2015-12-07 15:02:18"
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
        "HostdependencyHostMembership": [
            {
                "id": "3",
                "host_id": "1",
                "hostdependency_id": "1",
                "dependent": "0"
            },
            {
                "id": "4",
                "host_id": "2",
                "hostdependency_id": "1",
                "dependent": "1"
            }
        ],
        "HostdependencyHostgroupMembership": [

        ]
    }
}
		</pre>
	</div>
</div>

