## Query all existing tenants:

<div class="input-group">
	<span class="input-group-addon bg-color-green txt-color-white">GET</span>
	<input type="text" class="form-control" readonly="readonly" value="/tenants.json">
</div>
<br />
<div class="panel panel-primary">
	<div class="panel-heading">
		<h3 class="panel-title">Response: application/json</h3>
	</div>
	<div class="panel-body">
		<pre>
{
    "all_tenants": [
        {
            "Tenant": {
                "id": "1",
                "container_id": "4",
                "description": "it-novum",
                "is_active": "1",
                "date": null,
                "number_users": "0",
                "max_users": "0",
                "number_hosts": "0",
                "max_hosts": "0",
                "number_services": "0",
                "max_services": "0",
                "firstname": "itnovum",
                "lastname": "Consultant",
                "street": "Edelzeller Str. 44",
                "zipcode": "36043",
                "city": "Fulda",
                "created": "2015-10-21 17:55:55",
                "modified": "2015-10-21 17:55:55",
                "name": "it-novum"
            },
            "Container": {
                "id": "4",
                "containertype_id": "2",
                "name": "it-novum",
                "parent_id": "1",
                "lft": "6",
                "rght": "21"
            }
        }
    ]
}
		</pre>
	</div>
</div>

## Query a tenant by id:
<div class="input-group">
	<span class="input-group-addon bg-color-green txt-color-white">GET</span>
	<input type="text" class="form-control" readonly="readonly" value="/tenants/1.json">
</div>
<br />
<div class="panel panel-primary">
	<div class="panel-heading">
		<h3 class="panel-title">Response: application/json</h3>
	</div>
	<div class="panel-body">
		<pre>
{
    "tenant": {
        "Tenant": {
            "id": "1",
            "container_id": "4",
            "description": "it-novum",
            "is_active": "1",
            "date": null,
            "number_users": "0",
            "max_users": "0",
            "number_hosts": "0",
            "max_hosts": "0",
            "number_services": "0",
            "max_services": "0",
            "firstname": "itnovum",
            "lastname": "Consultant",
            "street": "Edelzeller Str. 44",
            "zipcode": "36043",
            "city": "Fulda",
            "created": "2015-10-21 17:55:55",
            "modified": "2015-10-21 17:55:55"
        },
        "Container": {
            "id": "4",
            "containertype_id": "2",
            "name": "it-novum",
            "parent_id": "1",
            "lft": "6",
            "rght": "21"
        }
    }
}
		</pre>
	</div>
</div>

