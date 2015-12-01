## REST Client:
We provide a basic REST API client for openITCOCKPIT written in PHP.

All examples in this documentation are tested with our REST client.

You can find the client on GitHub as well: https://github.com/it-novum/openITCOCKPIT-apiclient


## Login:
<div class="panel panel-primary">
	<div class="panel-heading">
		<h3 class="panel-title">PHP code:</h3>
	</div>
	<div class="panel-body">
		<pre>
&lt;?php
require_once 'HttpSocket/HttpSocket.php';
require_once 'RestApi/RestApi.php';

define('HTTPS', true);
define('HTTP', false);

$RestApi = new RestApi(
	'172.16.13.151',          // host address
	'api@openitcockpit.org',  // username
	'123456789',              // password
	HTTPS,                    // protocol
	false                     // is this an LDAP user?
);
$RestApi->autoload();
$RestApi->login();
		</pre>
	</div>
</div>

## Send a test request:
<div class="panel panel-primary">
	<div class="panel-heading">
		<h3 class="panel-title">PHP code:</h3>
	</div>
	<div class="panel-body">
		<pre>
$result = $RestApi->Command->index();
print_r($result);
		</pre>
	</div>
</div>

