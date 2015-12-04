## Query all existing locations:

<div class="input-group">
	<span class="input-group-addon bg-color-green txt-color-white">GET</span>
	<input type="text" class="form-control" readonly="readonly" value="/locations.json">
</div>
<br />
<div class="panel panel-primary">
	<div class="panel-heading">
		<h3 class="panel-title">Response: application/json</h3>
	</div>
	<div class="panel-body">
		<pre>
{
    "all_locations": [
        {
            "Location": {
                "id": "5",
                "uuid": "200ade0a-896e-4a40-adef-c65a05280905",
                "container_id": "29",
                "description": "",
                "latitude": null,
                "longitude": null,
                "timezone": "Europe\/Amsterdam",
                "created": "2015-12-04 09:55:30",
                "modified": "2015-12-04 09:55:30"
            },
            "Container": {
                "id": "29",
                "containertype_id": "3",
                "name": "Amsterdam",
                "parent_id": "1",
                "lft": "38",
                "rght": "39"
            }
        },
        {
            "Location": {
                "id": "4",
                "uuid": "b02ded8b-27b9-4062-a680-4fb9178dbda1",
                "container_id": "27",
                "description": "",
                "latitude": null,
                "longitude": null,
                "timezone": "Europe\/Berlin",
                "created": "2015-12-03 10:36:47",
                "modified": "2015-12-04 09:55:00"
            },
            "Container": {
                "id": "27",
                "containertype_id": "3",
                "name": "Frankfurt",
                "parent_id": "4",
                "lft": "19",
                "rght": "20"
            }
        },
        {
            "Location": {
                "id": "3",
                "uuid": "64c6f310-a0a5-4e9b-bf9d-49fdb9ce9621",
                "container_id": "26",
                "description": "",
                "latitude": null,
                "longitude": null,
                "timezone": "Europe\/Berlin",
                "created": "2015-12-03 10:35:56",
                "modified": "2015-12-04 09:55:15"
            },
            "Container": {
                "id": "26",
                "containertype_id": "3",
                "name": "Fulda",
                "parent_id": "1",
                "lft": "36",
                "rght": "37"
            }
        }
    ]
}
		</pre>
	</div>
</div>

## Query a location by id:
<div class="input-group">
	<span class="input-group-addon bg-color-green txt-color-white">GET</span>
	<input type="text" class="form-control" readonly="readonly" value="/locations/5.json">
</div>
<br />
<div class="panel panel-primary">
	<div class="panel-heading">
		<h3 class="panel-title">Response: application/json</h3>
	</div>
	<div class="panel-body">
		<pre>
{
    "location": {
        "Location": {
            "id": "5",
            "uuid": "200ade0a-896e-4a40-adef-c65a05280905",
            "container_id": "29",
            "description": "",
            "latitude": null,
            "longitude": null,
            "timezone": "Europe\/Amsterdam",
            "created": "2015-12-04 09:55:30",
            "modified": "2015-12-04 09:55:30"
        },
        "Container": {
            "id": "29",
            "containertype_id": "3",
            "name": "Amsterdam",
            "parent_id": "1",
            "lft": "38",
            "rght": "39"
        }
    }
}
		</pre>
	</div>
</div>

