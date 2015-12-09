## Query last 250 records from changelog:

<div class="input-group">
	<span class="input-group-addon bg-color-green txt-color-white">GET</span>
	<input type="text" class="form-control" readonly="readonly" value="/changelogs.json">
</div>
<br />
<div class="panel panel-primary">
	<div class="panel-heading">
		<h3 class="panel-title">Response: application/json</h3>
	</div>
	<div class="panel-body">
		<pre>
{
    "all_changes": [
        {
            "Changelog": {
                "id": "417",
                "model": "Service",
                "action": "edit",
                "object_id": "2",
                "objecttype_id": "2048",
                "user_id": "1",
                "data": "a:1:{i:0;a:1:{s:7:\"Contact\";a:3:{s:6:\"before\";a:1:{i:0;a:2:{s:2:\"id\";s:1:\"1\";s:4:\"name\";s:4:\"info\";}}s:5:\"after\";a:1:{i:0;a:2:{s:2:\"id\";i:8;s:4:\"name\";s:12:\"notify01_sms\";}}s:12:\"current_data\";a:1:{i:0;a:2:{s:2:\"id\";s:1:\"1\";s:4:\"name\";s:4:\"info\";}}}}}",
                "name": "localhost\/CHECK_LOCAL_DISK",
                "created": "2015-12-04 16:03:42"
            },
            "User": {
                "id": "1",
                "lastname": "it-novum",
                "firstname": "Admin"
            }
        },
        {
            "Changelog": {
                "id": "416",
                "model": "Host",
                "action": "edit",
                "object_id": "1",
                "objecttype_id": "256",
                "user_id": "1",
                "data": "a:1:{i:0;a:1:{s:7:\"Contact\";a:3:{s:6:\"before\";a:1:{i:0;a:2:{s:2:\"id\";s:1:\"1\";s:4:\"name\";s:4:\"info\";}}s:5:\"after\";a:1:{i:0;a:2:{s:2:\"id\";i:8;s:4:\"name\";s:12:\"notify01_sms\";}}s:12:\"current_data\";a:1:{i:0;a:2:{s:2:\"id\";s:1:\"1\";s:4:\"name\";s:4:\"info\";}}}}}",
                "name": "localhost",
                "created": "2015-12-04 16:03:24"
            },
            "User": {
                "id": "1",
                "lastname": "it-novum",
                "firstname": "Admin"
            }
        },
        {
            "Changelog": {
                "id": "415",
                "model": "Contact",
                "action": "add",
                "object_id": "8",
                "objecttype_id": "32",
                "user_id": "1",
                "data": "a:5:{i:0;a:1:{s:7:\"Contact\";a:1:{s:12:\"current_data\";a:13:{s:4:\"name\";s:12:\"notify01_sms\";s:5:\"phone\";s:15:\"004915146159034\";s:20:\"notify_host_recovery\";s:1:\"1\";s:16:\"notify_host_down\";s:1:\"1\";s:23:\"notify_host_unreachable\";s:1:\"0\";s:20:\"notify_host_flapping\";s:1:\"0\";s:20:\"notify_host_downtime\";s:1:\"0\";s:23:\"notify_service_recovery\";s:1:\"1\";s:22:\"notify_service_warning\";s:1:\"0\";s:22:\"notify_service_unknown\";s:1:\"0\";s:23:\"notify_service_critical\";s:1:\"1\";s:23:\"notify_service_flapping\";s:1:\"0\";s:23:\"notify_service_downtime\";s:1:\"0\";}}}i:1;a:1:{s:14:\"HostTimeperiod\";a:1:{s:12:\"current_data\";a:2:{s:2:\"id\";s:1:\"1\";s:4:\"name\";s:4:\"24x7\";}}}i:2;a:1:{s:17:\"ServiceTimeperiod\";a:1:{s:12:\"current_data\";a:2:{s:2:\"id\";s:1:\"1\";s:4:\"name\";s:4:\"24x7\";}}}i:3;a:1:{s:12:\"HostCommands\";a:1:{s:12:\"current_data\";a:1:{i:0;a:2:{s:2:\"id\";s:3:\"124\";s:4:\"name\";s:16:\"host-sms-by-cake\";}}}}i:4;a:1:{s:15:\"ServiceCommands\";a:1:{s:12:\"current_data\";a:1:{i:0;a:2:{s:2:\"id\";s:3:\"125\";s:4:\"name\";s:16:\"service-sms-cake\";}}}}}",
                "name": "notify01_sms",
                "created": "2015-12-04 16:03:10"
            },
            "User": {
                "id": "1",
                "lastname": "it-novum",
                "firstname": "Admin"
            }
        }
    ]
}
		</pre>
	</div>
</div>

