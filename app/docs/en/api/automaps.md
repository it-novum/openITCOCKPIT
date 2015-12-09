## Query all automaps:

<div class="input-group">
	<span class="input-group-addon bg-color-green txt-color-white">GET</span>
	<input type="text" class="form-control" readonly="readonly" value="/automaps.json">
</div>
<br />
<div class="panel panel-primary">
	<div class="panel-heading">
		<h3 class="panel-title">Response: application/json</h3>
	</div>
	<div class="panel-body">
		<pre>
{
    "all_automaps": [
        {
            "Automap": {
                "id": "1",
                "name": "All",
                "container_id": "1",
                "description": "All Services of all hosts",
                "host_regex": ".+",
                "service_regex": ".+",
                "show_ok": true,
                "show_warning": true,
                "show_critical": true,
                "show_unknown": true,
                "show_acknowledged": true,
                "show_downtime": true,
                "show_label": false,
                "font_size": "4",
                "created": "2015-12-09 11:44:34",
                "modified": "2015-12-09 11:47:00"
            }
        }
    ]
}
		</pre>
	</div>
</div>

## Query a automap by id:

<div class="input-group">
	<span class="input-group-addon bg-color-green txt-color-white">GET</span>
	<input type="text" class="form-control" readonly="readonly" value="/automaps/view/1.json">
</div>
<br />
<div class="panel panel-primary">
	<div class="panel-heading">
		<h3 class="panel-title">Response: application/json</h3>
	</div>
	<div class="panel-body">
		<pre>
{
    "automap": {
        "Automap": {
            "id": "1",
            "name": "All",
            "container_id": "1",
            "description": "All Services of all hosts",
            "host_regex": ".+",
            "service_regex": ".+",
            "show_ok": true,
            "show_warning": true,
            "show_critical": true,
            "show_unknown": true,
            "show_acknowledged": true,
            "show_downtime": true,
            "show_label": false,
            "font_size": "4",
            "created": "2015-12-09 11:44:34",
            "modified": "2015-12-09 11:47:00"
        }
    },
    "hosts": {
        "2": "srvkvm01.local.lan",
        "6": "srvkvm03.local.lan",
        "8": "srvkvm04.local.lan",
        "1": "localhost"
    },
    "services": [
        {
            "Service": {
                "id": "1",
                "uuid": "74fd8f59-1348-4e16-85f0-4a5c57c7dd62",
                "name": null,
                "host_id": "1"
            },
            "Servicetemplate": {
                "name": "Ping"
            },
            "ServiceObject": {
                "object_id": "41"
            },
            "Servicestatus": {
                "current_state": "0",
                "problem_has_been_acknowledged": "0",
                "scheduled_downtime_depth": "0"
            },
            "Host": {
                "id": "1",
                "name": "localhost"
            }
        },
        {
            "Service": {
                "id": "2",
                "uuid": "74f14950-a58f-4f18-b6c3-5cfa9dffef4e",
                "name": null,
                "host_id": "1"
            },
            "Servicetemplate": {
                "name": "CHECK_LOCAL_DISK"
            },
            "ServiceObject": {
                "object_id": "40"
            },
            "Servicestatus": {
                "current_state": "0",
                "problem_has_been_acknowledged": "0",
                "scheduled_downtime_depth": "0"
            },
            "Host": {
                "id": "1",
                "name": "localhost"
            }
        },
        {
            "Service": {
                "id": "3",
                "uuid": "1c045407-5502-4468-aabc-7781f6cf3dec",
                "name": null,
                "host_id": "1"
            },
            "Servicetemplate": {
                "name": "CHECK_LOCAL_LOAD"
            },
            "ServiceObject": {
                "object_id": "38"
            },
            "Servicestatus": {
                "current_state": "0",
                "problem_has_been_acknowledged": "0",
                "scheduled_downtime_depth": "0"
            },
            "Host": {
                "id": "1",
                "name": "localhost"
            }
        },
        {
            "Service": {
                "id": "4",
                "uuid": "7391f1aa-5e2e-447a-8a9b-b23357b9cd2a",
                "name": null,
                "host_id": "1"
            },
            "Servicetemplate": {
                "name": "CHECK_LOCAL_USERS"
            },
            "ServiceObject": {
                "object_id": "39"
            },
            "Servicestatus": {
                "current_state": "0",
                "problem_has_been_acknowledged": "0",
                "scheduled_downtime_depth": "0"
            },
            "Host": {
                "id": "1",
                "name": "localhost"
            }
        },
        {
            "Service": {
                "id": "83",
                "uuid": "d2ae5fde-5116-4157-ba41-f1a6e77c1165",
                "name": null,
                "host_id": "1"
            },
            "Servicetemplate": {
                "name": "check_user"
            },
            "ServiceObject": {
                "object_id": "171"
            },
            "Servicestatus": {
                "current_state": "0",
                "problem_has_been_acknowledged": "0",
                "scheduled_downtime_depth": "0"
            },
            "Host": {
                "id": "1",
                "name": "localhost"
            }
        },
        {
            "Service": {
                "id": "5",
                "uuid": "90f7f0cb-89f1-46c8-9ce1-daf1146c5dd5",
                "name": null,
                "host_id": "2"
            },
            "Servicetemplate": {
                "name": "Ping"
            },
            "ServiceObject": {
                "object_id": "43"
            },
            "Servicestatus": {
                "current_state": "0",
                "problem_has_been_acknowledged": "0",
                "scheduled_downtime_depth": "0"
            },
            "Host": {
                "id": "2",
                "name": "srvkvm01.local.lan"
            }
        },
        {
            "Service": {
                "id": "84",
                "uuid": "313dcf88-c48d-4c5d-85bf-341838fc8d59",
                "name": null,
                "host_id": "6"
            },
            "Servicetemplate": {
                "name": "Ping"
            },
            "ServiceObject": {
                "object_id": "174"
            },
            "Servicestatus": {
                "current_state": "0",
                "problem_has_been_acknowledged": "0",
                "scheduled_downtime_depth": "0"
            },
            "Host": {
                "id": "6",
                "name": "srvkvm03.local.lan"
            }
        },
        {
            "Service": {
                "id": "86",
                "uuid": "260d0add-b118-4e2c-a189-ff70eabead4e",
                "name": null,
                "host_id": "8"
            },
            "Servicetemplate": {
                "name": "Ping"
            },
            "ServiceObject": {
                "object_id": "179"
            },
            "Servicestatus": {
                "current_state": "0",
                "problem_has_been_acknowledged": "0",
                "scheduled_downtime_depth": "0"
            },
            "Host": {
                "id": "8",
                "name": "srvkvm04.local.lan"
            }
        },
        {
            "Service": {
                "id": "87",
                "uuid": "38aa65b1-76e9-43a0-ba40-69dde29c57d6",
                "name": null,
                "host_id": "8"
            },
            "Servicetemplate": {
                "name": "CHECK_LOCAL_DISK"
            },
            "ServiceObject": {
                "object_id": "180"
            },
            "Servicestatus": {
                "current_state": "0",
                "problem_has_been_acknowledged": "0",
                "scheduled_downtime_depth": "0"
            },
            "Host": {
                "id": "8",
                "name": "srvkvm04.local.lan"
            }
        },
        {
            "Service": {
                "id": "88",
                "uuid": "63078fc2-647b-4159-a851-caf3adb0b38f",
                "name": null,
                "host_id": "8"
            },
            "Servicetemplate": {
                "name": "CHECK_LOCAL_LOAD"
            },
            "ServiceObject": {
                "object_id": "182"
            },
            "Servicestatus": {
                "current_state": "0",
                "problem_has_been_acknowledged": "0",
                "scheduled_downtime_depth": "0"
            },
            "Host": {
                "id": "8",
                "name": "srvkvm04.local.lan"
            }
        },
        {
            "Service": {
                "id": "89",
                "uuid": "556cd6a7-31ba-4307-9a75-930c66ea5feb",
                "name": null,
                "host_id": "8"
            },
            "Servicetemplate": {
                "name": "CHECK_LOCAL_USERS"
            },
            "ServiceObject": {
                "object_id": "181"
            },
            "Servicestatus": {
                "current_state": "0",
                "problem_has_been_acknowledged": "0",
                "scheduled_downtime_depth": "0"
            },
            "Host": {
                "id": "8",
                "name": "srvkvm04.local.lan"
            }
        }
    ]
}
		</pre>
	</div>
</div>

