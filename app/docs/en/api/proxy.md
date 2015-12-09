## Query proxy configuration:

<div class="input-group">
	<span class="input-group-addon bg-color-green txt-color-white">GET</span>
	<input type="text" class="form-control" readonly="readonly" value="/proxy.json">
</div>
<br />
<div class="panel panel-primary">
	<div class="panel-heading">
		<h3 class="panel-title">Response: application/json</h3>
	</div>
	<div class="panel-body">
		<pre>
{
    "proxy": [
        {
            "Proxy": {
                "id": "1",
                "ipaddress": "proxy.example.org",
                "port": "3128",
                "enabled": false
            }
        }
    ]
}
		</pre>
	</div>
</div>

