## Query all users:

<div class="input-group">
	<span class="input-group-addon bg-color-green txt-color-white">GET</span>
	<input type="text" class="form-control" readonly="readonly" value="/users.json">
</div>
<br />
<div class="panel panel-primary">
	<div class="panel-heading">
		<h3 class="panel-title">Response: application/json</h3>
	</div>
	<div class="panel-body">
		<pre>
{
    "all_users": [
        {
            "User": {
                "id": "1",
                "email": "admin@local.lan",
                "company": null,
                "status": "1",
                "samaccountname": null,
                "full_name": "Admin user"
            },
            "Usergroup": {
                "id": "1",
                "name": "Administrator"
            },
            "UsersToContainer": {
                "container_id": "1"
            }
        },
        {
            "User": {
                "id": "7",
                "email": "api@openitcockpit.org",
                "company": "",
                "status": "1",
                "samaccountname": null,
                "full_name": "api api"
            },
            "Usergroup": {
                "id": "1",
                "name": "Administrator"
            },
            "UsersToContainer": {
                "container_id": "1"
            }
        },
        {
            "User": {
                "id": "4",
                "email": "api@localhost.local",
                "company": "",
                "status": "0",
                "samaccountname": "api",
                "full_name": "api api"
            },
            "Usergroup": {
                "id": "1",
                "name": "Administrator"
            },
            "UsersToContainer": {
                "container_id": "4"
            }
        }
    ]
}
		</pre>
	</div>
</div>

## Query a user by id:

<div class="input-group">
	<span class="input-group-addon bg-color-green txt-color-white">GET</span>
	<input type="text" class="form-control" readonly="readonly" value="/users/1.json">
</div>
<br />
<div class="panel panel-primary">
	<div class="panel-heading">
		<h3 class="panel-title">Response: application/json</h3>
	</div>
	<div class="panel-body">
		<pre>
{
    "user": {
        "User": {
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
        "Usergroup": {
            "id": "1",
            "name": "Administrator",
            "description": "",
            "created": "2015-08-19 14:57:42",
            "modified": "2015-11-26 16:49:20"
        },
        "ContainerUserMembership": [
            {
                "id": "1",
                "user_id": "1",
                "container_id": "1",
                "permission_level": "2"
            }
        ]
    }
}
		</pre>
	</div>
</div>

