## Query all existing hostescalations:

<div class="input-group">
	<span class="input-group-addon bg-color-green txt-color-white">GET</span>
	<input type="text" class="form-control" readonly="readonly" value="serviceescalations.json">
</div>
<br />
<div class="panel panel-primary">
	<div class="panel-heading">
		<h3 class="panel-title">Response: application/json</h3>
	</div>
	<div class="panel-body">
		<pre>
{
    "all_serviceescalations": [
        {
            "Serviceescalation": {
                "id": "1",
                "uuid": "9c82bae6-2dbf-40d6-8bbf-b850b95a7714",
                "container_id": "1",
                "timeperiod_id": "1",
                "first_notification": "1",
                "last_notification": "15",
                "notification_interval": "60",
                "escalate_on_recovery": "1",
                "escalate_on_warning": "1",
                "escalate_on_unknown": "0",
                "escalate_on_critical": "1",
                "created": "2015-12-04 11:35:25",
                "modified": "2015-12-04 11:35:25"
            },
            "Timeperiod": {
                "id": "1",
                "name": "24x7"
            },
            "ServiceescalationServiceMembership": [
                {
                    "id": "1",
                    "service_id": "1",
                    "serviceescalation_id": "1",
                    "excluded": "0",
                    "Service": {
                        "id": "1",
                        "name": null,
                        "host_id": "1",
                        "servicetemplate_id": "1",
                        "Host": {
                            "id": "1",
                            "name": "localhost"
                        },
                        "Servicetemplate": {
                            "name": "Ping"
                        }
                    }
                },
                {
                    "id": "2",
                    "service_id": "2",
                    "serviceescalation_id": "1",
                    "excluded": "0",
                    "Service": {
                        "id": "2",
                        "name": null,
                        "host_id": "1",
                        "servicetemplate_id": "8",
                        "Host": {
                            "id": "1",
                            "name": "localhost"
                        },
                        "Servicetemplate": {
                            "name": "CHECK_LOCAL_DISK"
                        }
                    }
                },
                {
                    "id": "3",
                    "service_id": "3",
                    "serviceescalation_id": "1",
                    "excluded": "0",
                    "Service": {
                        "id": "3",
                        "name": null,
                        "host_id": "1",
                        "servicetemplate_id": "9",
                        "Host": {
                            "id": "1",
                            "name": "localhost"
                        },
                        "Servicetemplate": {
                            "name": "CHECK_LOCAL_LOAD"
                        }
                    }
                },
                {
                    "id": "4",
                    "service_id": "4",
                    "serviceescalation_id": "1",
                    "excluded": "0",
                    "Service": {
                        "id": "4",
                        "name": null,
                        "host_id": "1",
                        "servicetemplate_id": "13",
                        "Host": {
                            "id": "1",
                            "name": "localhost"
                        },
                        "Servicetemplate": {
                            "name": "CHECK_LOCAL_USERS"
                        }
                    }
                },
                {
                    "id": "5",
                    "service_id": "83",
                    "serviceescalation_id": "1",
                    "excluded": "0",
                    "Service": {
                        "id": "83",
                        "name": null,
                        "host_id": "1",
                        "servicetemplate_id": "78",
                        "Host": {
                            "id": "1",
                            "name": "localhost"
                        },
                        "Servicetemplate": {
                            "name": "check_user"
                        }
                    }
                },
                {
                    "id": "6",
                    "service_id": "85",
                    "serviceescalation_id": "1",
                    "excluded": "0",
                    "Service": {
                        "id": "85",
                        "name": null,
                        "host_id": "1",
                        "servicetemplate_id": "79",
                        "Host": {
                            "id": "1",
                            "name": "localhost"
                        },
                        "Servicetemplate": {
                            "name": "check_interval"
                        }
                    }
                }
            ],
            "ServiceescalationServicegroupMembership": [

            ],
            "Contactgroup": [

            ],
            "Contact": [
                {
                    "name": "openitcockpitSupport",
                    "ContactsToServiceescalation": {
                        "id": "1",
                        "contact_id": "6",
                        "serviceescalation_id": "1"
                    }
                }
            ]
        }
    ]
}
		</pre>
	</div>
</div>

## Query a serviceescalation by id:
<div class="input-group">
	<span class="input-group-addon bg-color-green txt-color-white">GET</span>
	<input type="text" class="form-control" readonly="readonly" value="/serviceescalations/1.json">
</div>
<br />
<div class="panel panel-primary">
	<div class="panel-heading">
		<h3 class="panel-title">Response: application/json</h3>
	</div>
	<div class="panel-body">
		<pre>
{
    "serviceescalation": {
        "Serviceescalation": {
            "id": "1",
            "uuid": "9c82bae6-2dbf-40d6-8bbf-b850b95a7714",
            "container_id": "1",
            "timeperiod_id": "1",
            "first_notification": "1",
            "last_notification": "15",
            "notification_interval": "60",
            "escalate_on_recovery": "1",
            "escalate_on_warning": "1",
            "escalate_on_unknown": "0",
            "escalate_on_critical": "1",
            "created": "2015-12-04 11:35:25",
            "modified": "2015-12-04 11:35:25"
        },
        "Timeperiod": {
            "id": "1",
            "uuid": "41012866-6114-4853-9caf-6ffd19954e50",
            "container_id": "1",
            "name": "24x7",
            "description": "24x7",
            "created": "2015-01-05 15:11:46",
            "modified": "2015-01-05 15:11:46"
        },
        "ServiceescalationServiceMembership": [
            {
                "id": "1",
                "service_id": "1",
                "serviceescalation_id": "1",
                "excluded": "0",
                "Service": {
                    "id": "1",
                    "uuid": "74fd8f59-1348-4e16-85f0-4a5c57c7dd62",
                    "servicetemplate_id": "1",
                    "host_id": "1",
                    "name": null,
                    "description": null,
                    "command_id": null,
                    "check_command_args": "",
                    "eventhandler_command_id": null,
                    "notify_period_id": null,
                    "check_period_id": null,
                    "check_interval": null,
                    "retry_interval": null,
                    "max_check_attempts": null,
                    "first_notification_delay": null,
                    "notification_interval": null,
                    "notify_on_warning": null,
                    "notify_on_unknown": null,
                    "notify_on_critical": null,
                    "notify_on_recovery": null,
                    "notify_on_flapping": null,
                    "notify_on_downtime": null,
                    "is_volatile": null,
                    "flap_detection_enabled": null,
                    "flap_detection_on_ok": null,
                    "flap_detection_on_warning": null,
                    "flap_detection_on_unknown": null,
                    "flap_detection_on_critical": null,
                    "low_flap_threshold": null,
                    "high_flap_threshold": null,
                    "process_performance_data": null,
                    "freshness_checks_enabled": null,
                    "freshness_threshold": null,
                    "passive_checks_enabled": null,
                    "event_handler_enabled": null,
                    "active_checks_enabled": null,
                    "notifications_enabled": null,
                    "notes": null,
                    "priority": null,
                    "tags": null,
                    "own_contacts": "0",
                    "own_contactgroups": "0",
                    "own_customvariables": "0",
                    "service_url": null,
                    "service_type": "1",
                    "disabled": "0",
                    "created": "2015-01-15 19:26:46",
                    "modified": "2015-01-15 19:26:46",
                    "Host": {
                        "id": "1",
                        "name": "localhost"
                    },
                    "Servicetemplate": {
                        "id": "1",
                        "name": "Ping"
                    }
                }
            },
            {
                "id": "2",
                "service_id": "2",
                "serviceescalation_id": "1",
                "excluded": "0",
                "Service": {
                    "id": "2",
                    "uuid": "74f14950-a58f-4f18-b6c3-5cfa9dffef4e",
                    "servicetemplate_id": "8",
                    "host_id": "1",
                    "name": null,
                    "description": null,
                    "command_id": null,
                    "check_command_args": "",
                    "eventhandler_command_id": null,
                    "notify_period_id": null,
                    "check_period_id": null,
                    "check_interval": null,
                    "retry_interval": null,
                    "max_check_attempts": null,
                    "first_notification_delay": null,
                    "notification_interval": null,
                    "notify_on_warning": null,
                    "notify_on_unknown": null,
                    "notify_on_critical": null,
                    "notify_on_recovery": null,
                    "notify_on_flapping": null,
                    "notify_on_downtime": null,
                    "is_volatile": null,
                    "flap_detection_enabled": null,
                    "flap_detection_on_ok": null,
                    "flap_detection_on_warning": null,
                    "flap_detection_on_unknown": null,
                    "flap_detection_on_critical": null,
                    "low_flap_threshold": null,
                    "high_flap_threshold": null,
                    "process_performance_data": null,
                    "freshness_checks_enabled": null,
                    "freshness_threshold": null,
                    "passive_checks_enabled": null,
                    "event_handler_enabled": null,
                    "active_checks_enabled": null,
                    "notifications_enabled": null,
                    "notes": null,
                    "priority": null,
                    "tags": null,
                    "own_contacts": "0",
                    "own_contactgroups": "0",
                    "own_customvariables": "0",
                    "service_url": null,
                    "service_type": "1",
                    "disabled": "0",
                    "created": "2015-01-16 00:46:39",
                    "modified": "2015-01-16 00:46:39",
                    "Host": {
                        "id": "1",
                        "name": "localhost"
                    },
                    "Servicetemplate": {
                        "id": "8",
                        "name": "CHECK_LOCAL_DISK"
                    }
                }
            },
            {
                "id": "3",
                "service_id": "3",
                "serviceescalation_id": "1",
                "excluded": "0",
                "Service": {
                    "id": "3",
                    "uuid": "1c045407-5502-4468-aabc-7781f6cf3dec",
                    "servicetemplate_id": "9",
                    "host_id": "1",
                    "name": null,
                    "description": null,
                    "command_id": null,
                    "check_command_args": "",
                    "eventhandler_command_id": null,
                    "notify_period_id": null,
                    "check_period_id": null,
                    "check_interval": null,
                    "retry_interval": null,
                    "max_check_attempts": null,
                    "first_notification_delay": null,
                    "notification_interval": null,
                    "notify_on_warning": null,
                    "notify_on_unknown": null,
                    "notify_on_critical": null,
                    "notify_on_recovery": null,
                    "notify_on_flapping": null,
                    "notify_on_downtime": null,
                    "is_volatile": null,
                    "flap_detection_enabled": null,
                    "flap_detection_on_ok": null,
                    "flap_detection_on_warning": null,
                    "flap_detection_on_unknown": null,
                    "flap_detection_on_critical": null,
                    "low_flap_threshold": null,
                    "high_flap_threshold": null,
                    "process_performance_data": null,
                    "freshness_checks_enabled": null,
                    "freshness_threshold": null,
                    "passive_checks_enabled": null,
                    "event_handler_enabled": null,
                    "active_checks_enabled": null,
                    "notifications_enabled": null,
                    "notes": null,
                    "priority": null,
                    "tags": null,
                    "own_contacts": "0",
                    "own_contactgroups": "0",
                    "own_customvariables": "0",
                    "service_url": null,
                    "service_type": "1",
                    "disabled": "0",
                    "created": "2015-01-16 00:46:52",
                    "modified": "2015-01-16 00:46:52",
                    "Host": {
                        "id": "1",
                        "name": "localhost"
                    },
                    "Servicetemplate": {
                        "id": "9",
                        "name": "CHECK_LOCAL_LOAD"
                    }
                }
            },
            {
                "id": "4",
                "service_id": "4",
                "serviceescalation_id": "1",
                "excluded": "0",
                "Service": {
                    "id": "4",
                    "uuid": "7391f1aa-5e2e-447a-8a9b-b23357b9cd2a",
                    "servicetemplate_id": "13",
                    "host_id": "1",
                    "name": null,
                    "description": null,
                    "command_id": null,
                    "check_command_args": "",
                    "eventhandler_command_id": null,
                    "notify_period_id": null,
                    "check_period_id": null,
                    "check_interval": null,
                    "retry_interval": null,
                    "max_check_attempts": null,
                    "first_notification_delay": null,
                    "notification_interval": null,
                    "notify_on_warning": null,
                    "notify_on_unknown": null,
                    "notify_on_critical": null,
                    "notify_on_recovery": null,
                    "notify_on_flapping": null,
                    "notify_on_downtime": null,
                    "is_volatile": null,
                    "flap_detection_enabled": null,
                    "flap_detection_on_ok": null,
                    "flap_detection_on_warning": null,
                    "flap_detection_on_unknown": null,
                    "flap_detection_on_critical": null,
                    "low_flap_threshold": null,
                    "high_flap_threshold": null,
                    "process_performance_data": null,
                    "freshness_checks_enabled": null,
                    "freshness_threshold": null,
                    "passive_checks_enabled": null,
                    "event_handler_enabled": null,
                    "active_checks_enabled": null,
                    "notifications_enabled": null,
                    "notes": null,
                    "priority": null,
                    "tags": null,
                    "own_contacts": "0",
                    "own_contactgroups": "0",
                    "own_customvariables": "0",
                    "service_url": null,
                    "service_type": "1",
                    "disabled": "0",
                    "created": "2015-01-16 00:47:06",
                    "modified": "2015-01-16 00:47:06",
                    "Host": {
                        "id": "1",
                        "name": "localhost"
                    },
                    "Servicetemplate": {
                        "id": "13",
                        "name": "CHECK_LOCAL_USERS"
                    }
                }
            },
            {
                "id": "5",
                "service_id": "83",
                "serviceescalation_id": "1",
                "excluded": "0",
                "Service": {
                    "id": "83",
                    "uuid": "d2ae5fde-5116-4157-ba41-f1a6e77c1165",
                    "servicetemplate_id": "78",
                    "host_id": "1",
                    "name": null,
                    "description": null,
                    "command_id": null,
                    "check_command_args": "",
                    "eventhandler_command_id": null,
                    "notify_period_id": null,
                    "check_period_id": null,
                    "check_interval": null,
                    "retry_interval": null,
                    "max_check_attempts": null,
                    "first_notification_delay": null,
                    "notification_interval": null,
                    "notify_on_warning": null,
                    "notify_on_unknown": null,
                    "notify_on_critical": null,
                    "notify_on_recovery": null,
                    "notify_on_flapping": null,
                    "notify_on_downtime": null,
                    "is_volatile": null,
                    "flap_detection_enabled": null,
                    "flap_detection_on_ok": null,
                    "flap_detection_on_warning": null,
                    "flap_detection_on_unknown": null,
                    "flap_detection_on_critical": null,
                    "low_flap_threshold": null,
                    "high_flap_threshold": null,
                    "process_performance_data": null,
                    "freshness_checks_enabled": null,
                    "freshness_threshold": null,
                    "passive_checks_enabled": null,
                    "event_handler_enabled": null,
                    "active_checks_enabled": null,
                    "notifications_enabled": null,
                    "notes": null,
                    "priority": null,
                    "tags": null,
                    "own_contacts": "0",
                    "own_contactgroups": "0",
                    "own_customvariables": "0",
                    "service_url": null,
                    "service_type": "1",
                    "disabled": "0",
                    "created": "2015-11-13 14:30:59",
                    "modified": "2015-11-13 14:30:59",
                    "Host": {
                        "id": "1",
                        "name": "localhost"
                    },
                    "Servicetemplate": {
                        "id": "78",
                        "name": "check_user"
                    }
                }
            },
            {
                "id": "6",
                "service_id": "85",
                "serviceescalation_id": "1",
                "excluded": "0",
                "Service": {
                    "id": "85",
                    "uuid": "7f9a2bd0-e7b7-4dc1-a7c6-f3fa3bd9363a",
                    "servicetemplate_id": "79",
                    "host_id": "1",
                    "name": null,
                    "description": null,
                    "command_id": null,
                    "check_command_args": "",
                    "eventhandler_command_id": null,
                    "notify_period_id": null,
                    "check_period_id": null,
                    "check_interval": null,
                    "retry_interval": null,
                    "max_check_attempts": null,
                    "first_notification_delay": null,
                    "notification_interval": null,
                    "notify_on_warning": null,
                    "notify_on_unknown": null,
                    "notify_on_critical": null,
                    "notify_on_recovery": null,
                    "notify_on_flapping": null,
                    "notify_on_downtime": null,
                    "is_volatile": null,
                    "flap_detection_enabled": null,
                    "flap_detection_on_ok": null,
                    "flap_detection_on_warning": null,
                    "flap_detection_on_unknown": null,
                    "flap_detection_on_critical": null,
                    "low_flap_threshold": null,
                    "high_flap_threshold": null,
                    "process_performance_data": null,
                    "freshness_checks_enabled": null,
                    "freshness_threshold": null,
                    "passive_checks_enabled": null,
                    "event_handler_enabled": null,
                    "active_checks_enabled": null,
                    "notifications_enabled": null,
                    "notes": null,
                    "priority": null,
                    "tags": null,
                    "own_contacts": "0",
                    "own_contactgroups": "0",
                    "own_customvariables": "0",
                    "service_url": null,
                    "service_type": "1",
                    "disabled": "0",
                    "created": "2015-12-02 09:36:14",
                    "modified": "2015-12-02 09:36:14",
                    "Host": {
                        "id": "1",
                        "name": "localhost"
                    },
                    "Servicetemplate": {
                        "id": "79",
                        "name": "check_interval"
                    }
                }
            }
        ],
        "ServiceescalationServicegroupMembership": [

        ],
        "Contactgroup": [

        ],
        "Contact": [
            {
                "id": "6",
                "uuid": "1513a066-e0c4-4265-829d-b8d3781601aa",
                "name": "openitcockpitSupport",
                "description": "openitcockpit Support",
                "email": "openitcockpit@support.it-novum.com",
                "phone": "00491",
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
                "notify_host_downtime": "0",
                "ContactsToServiceescalation": {
                    "id": "1",
                    "contact_id": "6",
                    "serviceescalation_id": "1"
                }
            }
        ]
    }
}
		</pre>
	</div>
</div>

