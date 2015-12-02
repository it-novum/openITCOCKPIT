## Query all existing user defined macros:

<div class="input-group">
	<span class="input-group-addon bg-color-green txt-color-white">GET</span>
	<input type="text" class="form-control" readonly="readonly" value="/macros.json">
</div>
<br />
<div class="panel panel-primary">
	<div class="panel-heading">
		<h3 class="panel-title">Response: application/json</h3>
	</div>
	<div class="panel-body">
		<pre>
{
    "all_macros": [
        {
            "Macro": {
                "id": "1",
                "name": "$USER1$",
                "value": "\/opt\/openitc\/nagios\/libexec",
                "description": "Path to monitoring plugins",
                "created": "2015-01-05 15:17:23",
                "modified": "2015-11-13 14:27:24"
            }
        }
    ]
}
		</pre>
	</div>
</div>

