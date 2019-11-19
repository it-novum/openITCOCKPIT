## Query all usergroups:

<div class="input-group">
	<span class="input-group-addon bg-color-green txt-color-white">GET</span>
	<input type="text" class="form-control" readonly="readonly" value="/usergroups.json">
</div>
<br />
<div class="panel panel-primary">
	<div class="panel-heading">
		<h3 class="panel-title">Response: application/json</h3>
	</div>
	<div class="panel-body">
		<pre>
{
    "all_usergroups": [
        {
            "Usergroup": {
                "id": "1",
                "name": "Administrator",
                "description": "Grant a user full administrator privileges",
                "created": "2015-08-19 14:57:42",
                "modified": "2015-12-09 11:31:32"
            }
        },
        {
            "Usergroup": {
                "id": "2",
                "name": "Viewer",
                "description": "Users of this group can only see objects but not edit or reschedule",
                "created": "2015-08-19 15:00:36",
                "modified": "2015-12-09 11:32:20"
            }
        }
    ]
}
		</pre>
	</div>
</div>

## Query a usergroup by id:

<div class="input-group">
	<span class="input-group-addon bg-color-green txt-color-white">GET</span>
	<input type="text" class="form-control" readonly="readonly" value="/usergroups/view/1.json">
</div>
<br />
<div class="panel panel-primary">
	<div class="panel-heading">
		<h3 class="panel-title">Response: application/json</h3>
	</div>
	<div class="panel-body">
		<pre>
{
    "usergroup": {
        "Usergroup": {
            "id": "1",
            "name": "Administrator",
            "description": "Grant a user full administrator privileges",
            "created": "2015-08-19 14:57:42",
            "modified": "2015-12-09 11:31:32"
        },
        "User": [
            {
                "id": "1",
                "usergroup_id": "1",
                "status": "1",
                "email": "admin@local.lan",
                "password": "dfe5b29513c31beff314d5bc613cdc9b97dfb317",
                "firstname": "Admin",
                "lastname": "user",
                "position": null,
                "company": null,
                "phone": null,
                "timezone": "Europe\/Berlin",
                "dateformat": "%H:%M:%S - %d.%m.%Y",
                "image": null,
                "onetimetoken": null,
                "samaccountname": null,
                "showstatsinmenu": false,
                "dashboard_tab_rotation": "570",
                "created": null,
                "modified": "2015-09-28 17:54:32",
                "full_name": "Admin user"
            },
            {
                "id": "7",
                "usergroup_id": "1",
                "status": "1",
                "email": "api@openitcockpit.org",
                "password": "4822c51316e1ef33457b987cf29de4d885cfa136",
                "firstname": "api",
                "lastname": "api",
                "position": "",
                "company": "",
                "phone": "",
                "timezone": "Europe\/Berlin",
                "dateformat": "%H:%M:%S - %d.%m.%Y",
                "image": null,
                "onetimetoken": null,
                "samaccountname": null,
                "showstatsinmenu": false,
                "dashboard_tab_rotation": "0",
                "created": "2015-11-27 15:01:44",
                "modified": "2015-11-27 15:01:44",
                "full_name": "api api"
            }
        ]
    }
}
		</pre>
	</div>
</div>

