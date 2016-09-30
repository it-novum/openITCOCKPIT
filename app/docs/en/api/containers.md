## Query all existing containers:

<div class="input-group">
	<span class="input-group-addon bg-color-green txt-color-white">GET</span>
	<input type="text" class="form-control" readonly="readonly" value="/containers.json">
</div>
<br />
<div class="panel panel-primary">
	<div class="panel-heading">
		<h3 class="panel-title">Response: application/json</h3>
	</div>
	<div class="panel-body">
		<pre>
{
    "all_container": [
        {
            "Container": {
                "id": "1",
                "containertype_id": "1",
                "name": "root",
                "parent_id": null,
                "lft": "1",
                "rght": "38"
            }
        },
        {
            "Container": {
                "id": "2",
                "containertype_id": "9",
                "name": "Demo service template group",
                "parent_id": "1",
                "lft": "2",
                "rght": "3"
            }
        },
        {
            "Container": {
                "id": "3",
                "containertype_id": "7",
                "name": "Demo hostgroup",
                "parent_id": "1",
                "lft": "4",
                "rght": "5"
            }
        },
        {
            "Container": {
                "id": "4",
                "containertype_id": "2",
                "name": "it-novum",
                "parent_id": "1",
                "lft": "6",
                "rght": "21"
            }
        },
        {
            "Container": {
                "id": "5",
                "containertype_id": "2",
                "name": "Test tenant",
                "parent_id": "1",
                "lft": "22",
                "rght": "25"
            }
        },
        {
            "Container": {
                "id": "6",
                "containertype_id": "2",
                "name": "Demo Tenant",
                "parent_id": "1",
                "lft": "26",
                "rght": "31"
            }
        },
        {
            "Container": {
                "id": "7",
                "containertype_id": "6",
                "name": "info",
                "parent_id": "1",
                "lft": "32",
                "rght": "33"
            }
        },
        {
            "Container": {
                "id": "8",
                "containertype_id": "6",
                "name": "Demo contactgroup",
                "parent_id": "4",
                "lft": "7",
                "rght": "8"
            }
        },
        {
            "Container": {
                "id": "9",
                "containertype_id": "6",
                "name": "Demo contactgroup 2",
                "parent_id": "4",
                "lft": "9",
                "rght": "10"
            }
        },
        {
            "Container": {
                "id": "15",
                "containertype_id": "7",
                "name": "Hostgroup_2",
                "parent_id": "4",
                "lft": "11",
                "rght": "12"
            }
        },
        {
            "Container": {
                "id": "17",
                "containertype_id": "7",
                "name": "Demo Hostgroup 2",
                "parent_id": "5",
                "lft": "23",
                "rght": "24"
            }
        },
        {
            "Container": {
                "id": "19",
                "containertype_id": "7",
                "name": "all_hosts",
                "parent_id": "6",
                "lft": "27",
                "rght": "28"
            }
        },
        {
            "Container": {
                "id": "20",
                "containertype_id": "7",
                "name": "all_it-novum",
                "parent_id": "4",
                "lft": "13",
                "rght": "14"
            }
        },
        {
            "Container": {
                "id": "21",
                "containertype_id": "7",
                "name": "KVM Users",
                "parent_id": "4",
                "lft": "15",
                "rght": "16"
            }
        },
        {
            "Container": {
                "id": "23",
                "containertype_id": "7",
                "name": "KVM Admins",
                "parent_id": "6",
                "lft": "29",
                "rght": "30"
            }
        },
        {
            "Container": {
                "id": "24",
                "containertype_id": "7",
                "name": "openStack Admins",
                "parent_id": "4",
                "lft": "17",
                "rght": "18"
            }
        },
        {
            "Container": {
                "id": "25",
                "containertype_id": "8",
                "name": "Demo servicegroups",
                "parent_id": "1",
                "lft": "34",
                "rght": "35"
            }
        },
        {
            "Container": {
                "id": "26",
                "containertype_id": "3",
                "name": "Fulda",
                "parent_id": "1",
                "lft": "36",
                "rght": "37"
            }
        },
        {
            "Container": {
                "id": "27",
                "containertype_id": "3",
                "name": "Frankfurt",
                "parent_id": "4",
                "lft": "19",
                "rght": "20"
            }
        }
    ]
}
		</pre>
	</div>
</div>

containertype_id:

- 1 Root container (global objects)
- 2 Tenant
- 3 Location
- 5 Node
- 6 Contactgroup
- 7 Hostgroup
- 8 Servicegroup
- 9 Service template group

## Query all containers as tree:
<div class="input-group">
	<span class="input-group-addon bg-color-green txt-color-white">GET</span>
	<input type="text" class="form-control" readonly="readonly" value="/containers/nest.json">
</div>
<br />
<div class="panel panel-primary">
	<div class="panel-heading">
		<h3 class="panel-title">Response: application/json</h3>
	</div>
	<div class="panel-body">
		<pre>
{
    "all_container": [
        {
            "Container": {
                "id": "1",
                "containertype_id": "1",
                "name": "root",
                "parent_id": null,
                "lft": "1",
                "rght": "38"
            },
            "children": [
                {
                    "Container": {
                        "id": "2",
                        "containertype_id": "9",
                        "name": "Demo service template group",
                        "parent_id": "1",
                        "lft": "2",
                        "rght": "3"
                    },
                    "children": [

                    ]
                },
                {
                    "Container": {
                        "id": "3",
                        "containertype_id": "7",
                        "name": "Demo hostgroup",
                        "parent_id": "1",
                        "lft": "4",
                        "rght": "5"
                    },
                    "children": [

                    ]
                },
                {
                    "Container": {
                        "id": "4",
                        "containertype_id": "2",
                        "name": "it-novum",
                        "parent_id": "1",
                        "lft": "6",
                        "rght": "21"
                    },
                    "children": [
                        {
                            "Container": {
                                "id": "8",
                                "containertype_id": "6",
                                "name": "Demo contactgroup",
                                "parent_id": "4",
                                "lft": "7",
                                "rght": "8"
                            },
                            "children": [

                            ]
                        },
                        {
                            "Container": {
                                "id": "9",
                                "containertype_id": "6",
                                "name": "Demo contactgroup 2",
                                "parent_id": "4",
                                "lft": "9",
                                "rght": "10"
                            },
                            "children": [

                            ]
                        },
                        {
                            "Container": {
                                "id": "15",
                                "containertype_id": "7",
                                "name": "Hostgroup_2",
                                "parent_id": "4",
                                "lft": "11",
                                "rght": "12"
                            },
                            "children": [

                            ]
                        },
                        {
                            "Container": {
                                "id": "20",
                                "containertype_id": "7",
                                "name": "all_it-novum",
                                "parent_id": "4",
                                "lft": "13",
                                "rght": "14"
                            },
                            "children": [

                            ]
                        },
                        {
                            "Container": {
                                "id": "21",
                                "containertype_id": "7",
                                "name": "KVM Users",
                                "parent_id": "4",
                                "lft": "15",
                                "rght": "16"
                            },
                            "children": [

                            ]
                        },
                        {
                            "Container": {
                                "id": "24",
                                "containertype_id": "7",
                                "name": "openStack Admins",
                                "parent_id": "4",
                                "lft": "17",
                                "rght": "18"
                            },
                            "children": [

                            ]
                        },
                        {
                            "Container": {
                                "id": "27",
                                "containertype_id": "3",
                                "name": "Frankfurt",
                                "parent_id": "4",
                                "lft": "19",
                                "rght": "20"
                            },
                            "children": [

                            ]
                        }
                    ]
                },
                {
                    "Container": {
                        "id": "5",
                        "containertype_id": "2",
                        "name": "Test tenant",
                        "parent_id": "1",
                        "lft": "22",
                        "rght": "25"
                    },
                    "children": [
                        {
                            "Container": {
                                "id": "17",
                                "containertype_id": "7",
                                "name": "Demo Hostgroup 2",
                                "parent_id": "5",
                                "lft": "23",
                                "rght": "24"
                            },
                            "children": [

                            ]
                        }
                    ]
                },
                {
                    "Container": {
                        "id": "6",
                        "containertype_id": "2",
                        "name": "Demo Tenant",
                        "parent_id": "1",
                        "lft": "26",
                        "rght": "31"
                    },
                    "children": [
                        {
                            "Container": {
                                "id": "19",
                                "containertype_id": "7",
                                "name": "all_hosts",
                                "parent_id": "6",
                                "lft": "27",
                                "rght": "28"
                            },
                            "children": [

                            ]
                        },
                        {
                            "Container": {
                                "id": "23",
                                "containertype_id": "7",
                                "name": "KVM Admins",
                                "parent_id": "6",
                                "lft": "29",
                                "rght": "30"
                            },
                            "children": [

                            ]
                        }
                    ]
                },
                {
                    "Container": {
                        "id": "7",
                        "containertype_id": "6",
                        "name": "info",
                        "parent_id": "1",
                        "lft": "32",
                        "rght": "33"
                    },
                    "children": [

                    ]
                },
                {
                    "Container": {
                        "id": "25",
                        "containertype_id": "8",
                        "name": "Demo servicegroups",
                        "parent_id": "1",
                        "lft": "34",
                        "rght": "35"
                    },
                    "children": [

                    ]
                },
                {
                    "Container": {
                        "id": "26",
                        "containertype_id": "3",
                        "name": "Fulda",
                        "parent_id": "1",
                        "lft": "36",
                        "rght": "37"
                    },
                    "children": [

                    ]
                }
            ]
        }
    ]
}
		</pre>
	</div>
</div>

## Query a container by id:
<div class="input-group">
	<span class="input-group-addon bg-color-green txt-color-white">GET</span>
	<input type="text" class="form-control" readonly="readonly" value="/containers/3.json">
</div>
<br />
<div class="panel panel-primary">
	<div class="panel-heading">
		<h3 class="panel-title">Response: application/json</h3>
	</div>
	<div class="panel-body">
		<pre>
{
    "container": {
        "Container": {
            "id": "3",
            "containertype_id": "7",
            "name": "Demo hostgroup",
            "parent_id": "1",
            "lft": "4",
            "rght": "5"
        },
        "Tenant": [

        ],
        "Contact": [
            {
                "id": "3",
                "uuid": "cde8502b-8f80-4d6a-9914-2ca88100b8e9",
                "name": "Demo contact",
                "description": "This is a API demo contact",
                "email": "demo@example.com",
                "phone": "",
                "host_timeperiod_id": "4",
                "service_timeperiod_id": "4",
                "host_notifications_enabled": "1",
                "service_notifications_enabled": "1",
                "notify_service_recovery": "1",
                "notify_service_warning": "1",
                "notify_service_unknown": "1",
                "notify_service_critical": "1",
                "notify_service_flapping": "1",
                "notify_service_downtime": "0",
                "notify_host_recovery": "1",
                "notify_host_down": "1",
                "notify_host_unreachable": "1",
                "notify_host_flapping": "1",
                "notify_host_downtime": "0"
            }
        ],
        "Contactgroup": [
            {
                "id": "3",
                "uuid": "a0e08f28-709a-4d3d-b179-33bee44f5398",
                "container_id": "9",
                "description": "API demo contactgroup"
            }
        ],
        "Location": [

        ],
        "Devicegroup": [

        ],
        "Hosttemplate": [

        ],
        "Hostgroup": [
            {
                "id": "1",
                "uuid": "aff8f698-3e16-48a1-8442-418ac164129c",
                "container_id": "3",
                "description": "",
                "hostgroup_url": ""
            }
        ],
        "Servicegroup": [

        ],
        "Calendar": [

        ],
        "ContainerUserMembership": [

        ]
    }
}
		</pre>
	</div>
</div>

