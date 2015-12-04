## Query all existing devicegroups:

<div class="input-group">
	<span class="input-group-addon bg-color-green txt-color-white">GET</span>
	<input type="text" class="form-control" readonly="readonly" value="/devicegroups.json">
</div>
<br />
<div class="panel panel-primary">
	<div class="panel-heading">
		<h3 class="panel-title">Response: application/json</h3>
	</div>
	<div class="panel-body">
		<pre>
{
    "all_devicegroups": [
        {
            "Devicegroup": {
                "id": "5",
                "container_id": "30",
                "description": "Server in Fulda",
                "created": "2015-12-04 10:16:19",
                "modified": "2015-12-04 10:16:19"
            },
            "Container": {
                "id": "30",
                "containertype_id": "4",
                "name": "Server",
                "parent_id": "26",
                "lft": "37",
                "rght": "38"
            }
        },
        {
            "Devicegroup": {
                "id": "6",
                "container_id": "31",
                "description": "",
                "created": "2015-12-04 10:16:30",
                "modified": "2015-12-04 10:16:30"
            },
            "Container": {
                "id": "31",
                "containertype_id": "4",
                "name": "Smartphones",
                "parent_id": "26",
                "lft": "39",
                "rght": "40"
            }
        },
        {
            "Devicegroup": {
                "id": "7",
                "container_id": "32",
                "description": "",
                "created": "2015-12-04 10:16:39",
                "modified": "2015-12-04 10:16:39"
            },
            "Container": {
                "id": "32",
                "containertype_id": "4",
                "name": "vServer",
                "parent_id": "29",
                "lft": "43",
                "rght": "44"
            }
        }
    ]
}
		</pre>
	</div>
</div>

## Query a devicegroup by id:
<div class="input-group">
	<span class="input-group-addon bg-color-green txt-color-white">GET</span>
	<input type="text" class="form-control" readonly="readonly" value="/devicegroups/5.json">
</div>
<br />
<div class="panel panel-primary">
	<div class="panel-heading">
		<h3 class="panel-title">Response: application/json</h3>
	</div>
	<div class="panel-body">
		<pre>
{
    "devicegroup": {
        "Devicegroup": {
            "id": "5",
            "container_id": "30",
            "description": "Server in Fulda",
            "created": "2015-12-04 10:16:19",
            "modified": "2015-12-04 10:16:19"
        },
        "Container": {
            "id": "30",
            "containertype_id": "4",
            "name": "Server",
            "parent_id": "26",
            "lft": "37",
            "rght": "38"
        }
    }
}
		</pre>
	</div>
</div>

