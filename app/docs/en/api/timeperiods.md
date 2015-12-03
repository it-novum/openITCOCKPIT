## Query all existing timeperiods:

<div class="input-group">
	<span class="input-group-addon bg-color-green txt-color-white">GET</span>
	<input type="text" class="form-control" readonly="readonly" value="/timeperiods/index.json">
</div>
<br />
<div class="panel panel-primary">
	<div class="panel-heading">
		<h3 class="panel-title">Response: application/json</h3>
	</div>
	<div class="panel-body">
		<pre>
{
    "all_timeperiods": [
        {
            "Timeperiod": {
                "id": "1",
                "uuid": "41012866-6114-4853-9caf-6ffd19954e50",
                "container_id": "1",
                "name": "24x7",
                "description": "24x7",
                "created": "2015-01-05 15:11:46",
                "modified": "2015-01-05 15:11:46"
            }
        },
        {
            "Timeperiod": {
                "id": "2",
                "uuid": "c5251a5e-37f1-4841-b0bd-f801ee8969d4",
                "container_id": "1",
                "name": "none",
                "description": "none",
                "created": "2015-01-05 15:11:56",
                "modified": "2015-01-05 15:11:56"
            }
        },
    ]
}
		</pre>
	</div>
</div>

## Query a timeperiod by id:
<div class="input-group">
	<span class="input-group-addon bg-color-green txt-color-white">GET</span>
	<input type="text" class="form-control" readonly="readonly" value="/timeperiods/1.json">
</div>
<br />
<div class="panel panel-primary">
	<div class="panel-heading">
		<h3 class="panel-title">Response: application/json</h3>
	</div>
	<div class="panel-body">
		<pre>
{
    "timeperiod": {
        "Timeperiod": {
            "id": "1",
            "uuid": "41012866-6114-4853-9caf-6ffd19954e50",
            "container_id": "1",
            "name": "24x7",
            "description": "24x7",
            "created": "2015-01-05 15:11:46",
            "modified": "2015-01-05 15:11:46"
        },
        "Timerange": [
            {
                "id": "1",
                "timeperiod_id": "1",
                "day": "1",
                "start": "00:00",
                "end": "24:00"
            },
            {
                "id": "2",
                "timeperiod_id": "1",
                "day": "2",
                "start": "00:00",
                "end": "24:00"
            },
            {
                "id": "3",
                "timeperiod_id": "1",
                "day": "3",
                "start": "00:00",
                "end": "24:00"
            },
            {
                "id": "4",
                "timeperiod_id": "1",
                "day": "4",
                "start": "00:00",
                "end": "24:00"
            },
            {
                "id": "5",
                "timeperiod_id": "1",
                "day": "5",
                "start": "00:00",
                "end": "24:00"
            },
            {
                "id": "6",
                "timeperiod_id": "1",
                "day": "6",
                "start": "00:00",
                "end": "24:00"
            },
            {
                "id": "7",
                "timeperiod_id": "1",
                "day": "7",
                "start": "00:00",
                "end": "24:00"
            }
        ]
    }
}
		</pre>
	</div>
</div>

Days:

- 1 Monday
- 2 Tuesday
- 3 Wednesday
- 4 Thursday
- 5 Friday
- 6 Saturday
- 7 Sunday