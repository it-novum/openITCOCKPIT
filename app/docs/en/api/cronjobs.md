## Query all cronjobs:

<div class="input-group">
	<span class="input-group-addon bg-color-green txt-color-white">GET</span>
	<input type="text" class="form-control" readonly="readonly" value="/cronjobs.json">
</div>
<br />
<div class="panel panel-primary">
	<div class="panel-heading">
		<h3 class="panel-title">Response: application/json</h3>
	</div>
	<div class="panel-body">
		<pre>
{
    "cronjobs": [
        {
            "Cronjob": {
                "id": "1",
                "task": "CleanupTemp",
                "plugin": "Core",
                "interval": "10"
            },
            "Cronschedule": {
                "id": "1",
                "cronjob_id": "1",
                "is_running": "0",
                "start_time": "2015-12-09 10:55:02",
                "end_time": "2015-12-09 10:55:02"
            }
        },
        {
            "Cronjob": {
                "id": "2",
                "task": "DatabaseCleanup",
                "plugin": "Core",
                "interval": "1440"
            },
            "Cronschedule": {
                "id": "2",
                "cronjob_id": "2",
                "is_running": "0",
                "start_time": "2015-10-21 09:53:03",
                "end_time": "2015-10-21 09:53:03"
            }
        },
        {
            "Cronjob": {
                "id": "3",
                "task": "RecurringDowntimes",
                "plugin": "Core",
                "interval": "10"
            },
            "Cronschedule": {
                "id": "3",
                "cronjob_id": "3",
                "is_running": "0",
                "start_time": "2015-10-22 09:51:01",
                "end_time": "2015-10-22 09:51:01"
            }
        },
        {
            "Cronjob": {
                "id": "4",
                "task": "CpuLoad",
                "plugin": "Core",
                "interval": "15"
            },
            "Cronschedule": {
                "id": null,
                "cronjob_id": null,
                "is_running": null,
                "start_time": null,
                "end_time": null
            }
        }
    ]
}		</pre>
	</div>
</div>

