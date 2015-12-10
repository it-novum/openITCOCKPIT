## Query all existing satelittes:

<div class="input-group">
	<span class="input-group-addon bg-color-green txt-color-white">GET</span>
	<input type="text" class="form-control" readonly="readonly" value="/distribute_module/satellites.json">
</div>
<br />
<div class="panel panel-primary">
	<div class="panel-heading">
		<h3 class="panel-title">Response: application/json</h3>
	</div>
	<div class="panel-body">
		<pre>
{
    "all_satelittes": [
        {
            "Satellite": {
                "id": "1",
                "name": "Demo SAT-System",
                "address": "192.168.0.10",
                "container_id": "1",
                "created": "2015-12-09 12:10:32",
                "modified": "2015-12-09 12:10:32",
                "container": "root"
            }
        }
    ]
}
		</pre>
	</div>
</div>

command types:

- 1 check command
- 2 host check
- 3 notification command
- 4 event handler

## Query all existing host checks commands:

<div class="input-group">
	<span class="input-group-addon bg-color-green txt-color-white">GET</span>
	<input type="text" class="form-control" readonly="readonly" value="/commands/hostchecks.json">
</div>
<br />
<div class="panel panel-primary">
	<div class="panel-heading">
		<h3 class="panel-title">Response: application/json</h3>
	</div>
	<div class="panel-body">
		<pre>
{
    "all_commands": [
        {
            "Command": {
                "id": "36",
                "name": "check-host-alive-smtp",
                "command_line": "$USER1$\/check_tcp -H $HOSTADDRESS$ -p 25 -w 3 -c 5",
                "command_type": "2",
                "human_args": null,
                "uuid": "e8008385-957d-4379-9ac0-579d60b35f65",
                "description": null
            }
        },
        {
            "Command": {
                "id": "31",
                "name": "check-host-alive",
                "command_line": "$USER1$\/check_icmp -H $HOSTADDRESS$ -w 3000.0,80% -c 5000.0,100% -p 1",
                "command_type": "2",
                "human_args": null,
                "uuid": "d8c30a28-acd3-4b67-9ede-ae3af1d0dec6",
                "description": null
            }
        }
    ]
}
		</pre>
	</div>
</div>

## Query all existing notification commands:

<div class="input-group">
	<span class="input-group-addon bg-color-green txt-color-white">GET</span>
	<input type="text" class="form-control" readonly="readonly" value="/commands/notifications.json">
</div>
<br />
<div class="panel panel-primary">
	<div class="panel-heading">
		<h3 class="panel-title">Response: application/json</h3>
	</div>
	<div class="panel-body">
		<pre>
{
    "all_commands": [
        {
            "Command": {
                "id": "1",
                "name": "host-notify-by-cake",
                "command_line": "\/usr\/share\/openitcockpit\/app\/Console\/cake nagios_notification -q --type Host --notificationtype $NOTIFICATIONTYPE$ --hostname \"$HOSTNAME$\" --hoststate \"$HOSTSTATE$\" --hostaddress \"$HOSTADDRESS$\" --hostoutput \"$HOSTOUTPUT$\" --contactmail \"$CONTACTEMAIL$\" --contactalias \"$CONTACTALIAS$\"",
                "command_type": "3",
                "human_args": null,
                "uuid": "a13ff7f1-0642-4a11-be05-9931ca98da10",
                "description": "Send a host notification as mail"
            }
        },
        {
            "Command": {
                "id": "2",
                "name": "service-notify-by-cake",
                "command_line": "\/usr\/share\/openitcockpit\/app\/Console\/cake nagios_notification -q --type Service --notificationtype $NOTIFICATIONTYPE$ --hostname \"$HOSTNAME$\" --hoststate \"$HOSTSTATE$\" --hostaddress \"$HOSTADDRESS$\" --hostoutput \"$HOSTOUTPUT$\" --contactmail \"$CONTACTEMAIL$\" --contactalias \"$CONTACTALIAS$\" --servicedesc \"$SERVICEDESC$\" --servicestate \"$SERVICESTATE$\" --serviceoutput \"$SERVICEOUTPUT$\"",
                "command_type": "3",
                "human_args": null,
                "uuid": "a517bbb6-f299-4b57-9865-a4e0b70597e4",
                "description": "Send a service notificationa as mail"
            }
        }
    ]
}
		</pre>
	</div>
</div>

## Query all existing event handler commands:

<div class="input-group">
	<span class="input-group-addon bg-color-green txt-color-white">GET</span>
	<input type="text" class="form-control" readonly="readonly" value="/commands/handler.json">
</div>
<br />
<div class="panel panel-primary">
	<div class="panel-heading">
		<h3 class="panel-title">Response: application/json</h3>
	</div>
	<div class="panel-body">
		<pre>
{
    "all_commands": [
        {
            "Command": {
                "id": "100",
                "name": "test handle",
                "command_line": "echo 1 >> /tmp/handler_test",
                "command_type": "4",
                "human_args": null,
                "uuid": "cf154b32-6a1d-4546-a337-260daa735ea9",
                "description": null
            }
        }
    ]
}
		</pre>
	</div>
</div>

## Query a command by id:
<div class="input-group">
	<span class="input-group-addon bg-color-green txt-color-white">GET</span>
	<input type="text" class="form-control" readonly="readonly" value="/commands/17.json">
</div>
<br />
<div class="panel panel-primary">
	<div class="panel-heading">
		<h3 class="panel-title">Response: application/json</h3>
	</div>
	<div class="panel-body">
		<pre>
{
    "command": {
        "Command": {
            "id": "17",
            "name": "check_local_disk",
            "command_line": "$USER1$\/check_disk -w $ARG1$ -c $ARG2$ -p $ARG3$",
            "command_type": "1",
            "human_args": null,
            "uuid": "74a59dd0-2eff-4f41-9bcd-3e8786f34f04",
            "description": "This plugin checks the amount of used disk space on a mounted file system\r\nand generates an alert if free space is less than one of the threshold values"
        },
        "Commandargument": [
            {
                "id": "23",
                "command_id": "17",
                "name": "$ARG1$",
                "human_name": "Warning (%)",
                "created": "2015-01-15 23:36:34",
                "modified": "2015-01-15 23:36:34"
            },
            {
                "id": "24",
                "command_id": "17",
                "name": "$ARG2$",
                "human_name": "Critical (%)",
                "created": "2015-01-15 23:36:34",
                "modified": "2015-01-15 23:36:34"
            },
            {
                "id": "25",
                "command_id": "17",
                "name": "$ARG3$",
                "human_name": "Moint point",
                "created": "2015-01-15 23:36:34",
                "modified": "2015-01-15 23:36:34"
            }
        ]
    }
}
		</pre>
	</div>
</div>

## Create a new command:
<div class="input-group">
	<span class="input-group-addon bg-color-blue txt-color-white">POST</span>
	<input type="text" class="form-control" readonly="readonly" value="/commands.json">
</div>
<br />
<div class="panel panel-success">
	<div class="panel-heading">
		<h3 class="panel-title">PHP request code:</h3>
	</div>
	<div class="panel-body">
		<pre>
$data = array(
	'Command' => array(
		'name' => 'Example Command with arguments',
		'command_line' => '$USER1$/check_ping -H $HOSTADDRESS$ -w $ARG1$ -c $ARG2$ -p 5',
		'command_type' => 1
	),
	'Commandargument' => array(
		array(
			'name' => '$ARG1$',
			'human_name' => 'Warning'
		),
		array(
			'name' => '$ARG2$',
			'human_name' => 'Critical'
		),
	)
);
$url  = $RestApi->getUrl();
$url .= '/commands.json';
$response = $RestApi->httpSocket->post($url, $data, array('redirect' => true));
$response = $RestApi->parseResponse($response);
		</pre>
	</div>
</div>

<div class="panel panel-primary">
	<div class="panel-heading">
		<h3 class="panel-title">Response:</h3>
	</div>
	<div class="panel-body">
		<pre>
Array (
    [status_code] => 200
    [reason_phrase] => OK
    [content_type] => application/json; charset=UTF-8
    [result] => Array (
        [id] => 121
        [command_arguments] => Array(
            [0] => Array (
                [Commandargument] => Array (
                    [name] => $ARG1$
                    [human_name] => Warning
                    [command_id] => 121
                    [modified] => 2015-12-01 12:24:04
                    [created] => 2015-12-01 12:24:04
                    [id] => 163
                 )
            )
            [1] => Array (
                [Commandargument] => Array (
                    [name] => $ARG2$
                    [human_name] => Critical
                    [command_id] => 121
                    [modified] => 2015-12-01 12:24:04
                    [created] => 2015-12-01 12:24:04
                    [id] => 164
                )
            )
       )
    )
)
		</pre>
	</div>
</div>
You created successfully the command with the ID 121.

## Edit existing command by ID:
<div class="input-group">
	<span class="input-group-addon bg-color-blueDark txt-color-white">PUT</span>
	<input type="text" class="form-control" readonly="readonly" value="/commands/121.json">
</div>
<br />
<div class="panel panel-success">
	<div class="panel-heading">
		<h3 class="panel-title">PHP request code:</h3>
	</div>
	<div class="panel-body">
		<pre>
$commandId = 121;
$data = array(
    'Command' => array(
        'id' => $commandId,
        'name' => 'Rename my command to a new name',
        'command_line' => '$USER1$/check_ping -H $HOSTADDRESS$ -w $ARG1$ -c $ARG2$ -p 5',
        'command_type' => 1
    ),
    'Commandargument' => array(
        array(
            'id' => 163,
            'command_id' => $commandId,
            'name' => '$ARG1$',
            'human_name' => 'Warning'
        ),
        array(
            'id' => 164,
            'command_id' => $commandId,
            'name' => '$ARG2$',
            'human_name' => 'Critical'
        ),
    )
);
$url  = $RestApi->getUrl();
$url .= '/commands/'.$commandId.'.json';
$response = $RestApi->httpSocket->put($url, $data, array('redirect' => true));
$response = $RestApi->parseResponse($response);
		</pre>
	</div>
</div>

## Delete existing command by ID:
<div class="input-group">
	<span class="input-group-addon bg-color-red txt-color-white">DELETE</span>
	<input type="text" class="form-control" readonly="readonly" value="/commands/121.json">
</div>
<br />
<div class="panel panel-success">
	<div class="panel-heading">
		<h3 class="panel-title">PHP request code:</h3>
	</div>
	<div class="panel-body">
		<pre>
$data = array(
    'Command' => array(
        'id' => 121,
    )
);
$url  = $RestApi->getUrl();
$url .= '/commands/'.$commandId.'.json';
$response = $RestApi->httpSocket->delete($url, $data, array('redirect' => true));
$response = $RestApi->parseResponse($response);
		</pre>
	</div>
</div>

