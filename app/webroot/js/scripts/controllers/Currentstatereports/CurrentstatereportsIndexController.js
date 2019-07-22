angular.module('openITCOCKPIT')
    .controller('CurrentstatereportsIndexController', function($rootScope, $scope, $http, $timeout, NotyService, QueryStringService){

        $scope.init = true;
        $scope.errors = null;

        $scope.post = {
            Currentstatereport: {
                Service: [],
                report_format: '1'
            }
        };

        $scope.filter = {
            Servicestatus: {
                current_state: {
                    ok: true,
                    warning: true,
                    critical: true,
                    unknown: true
                },
                acknowledged: QueryStringService.getValue('has_been_acknowledged', false) === '1',
                not_acknowledged: QueryStringService.getValue('has_not_been_acknowledged', false) === '1',
                in_downtime: QueryStringService.getValue('in_downtime', false) === '1',
                not_in_downtime: QueryStringService.getValue('not_in_downtime', false) === '1',
                passive: QueryStringService.getValue('passive', false) === '1',
                active: QueryStringService.getValue('active', false) === '1'
            }
        };

//        $scope.servicestatus = {};

        $scope.servicestatus = {
            "1": {
                "Host": {
                    "id": 1,
                    "uuid": "c36b8048-93ce-4385-ac19-ab5c90574b77",
                    "hostname": "default host",
                    "address": "127.0.0.1",
                    "description": null,
                    "active_checks_enabled": null,
                    "satelliteId": null,
                    "containerId": null,
                    "containerIds": null,
                    "tags": null,
                    "allow_edit": true,
                    "disabled": false,
                    "is_satellite_host": false
                },
                "Hoststatus": {
                    "currentState": 0,
                    "isFlapping": false,
                    "problemHasBeenAcknowledged": false,
                    "scheduledDowntimeDepth": "1",
                    "lastCheck": "on 1\/1\/70",
                    "nextCheck": "on 1\/1\/70",
                    "activeChecksEnabled": null,
                    "lastHardStateChange": "7M 4D 23h 47m 9s",
                    "last_state_change": "49Y 6M 21D 9h 59m 9s",
                    "output": "OK - 127.0.0.1: rta 0,039ms, lost 0%",
                    "long_output": null,
                    "acknowledgement_type": null,
                    "state_type": "1",
                    "flap_detection_enabled": null,
                    "notifications_enabled": null,
                    "current_check_attempt": "1",
                    "max_check_attempts": "3",
                    "latency": null,
                    "isHardstate": true,
                    "isInMonitoring": true,
                    "humanState": "up"
                },
                "Services": {
                    "2": {
                        "Service": {
                            "id": 2,
                            "uuid": "74f14950-a58f-4f18-b6c3-5cfa9dffef4e",
                            "servicename": "CHECK_LOCAL_DISK",
                            "hostname": "default host",
                            "description": null,
                            "active_checks_enabled": true,
                            "tags": null,
                            "host_id": 1,
                            "allow_edit": true,
                            "disabled": false
                        },
                        "Servicestatus": {
                            "currentState": 0,
                            "lastHardState": null,
                            "isFlapping": false,
                            "problemHasBeenAcknowledged": false,
                            "scheduledDowntimeDepth": 1,
                            "lastCheck": "on 13\/6\/19",
                            "nextCheck": "in 27 minutes",
                            "activeChecksEnabled": "1",
                            "lastHardStateChange": "2M 0D 19h 1m 18s",
                            "last_state_change": "2M 0D 19h 1m 18s",
                            "processPerformanceData": null,
                            "state_type": "1",
                            "acknowledgement_type": 0,
                            "flap_detection_enabled": null,
                            "notifications_enabled": null,
                            "current_check_attempt": "1",
                            "output": "DISK OK - free space: \/ 3082 MB (22% inode=71%):",
                            "long_output": null,
                            "perfdata": "\/=10464MB;11433;12862;0;14292",
                            "latency": null,
                            "max_check_attempts": 2,
                            "isHardstate": true,
                            "isInMonitoring": true,
                            "humanState": "ok",
                            "perfdataArray": {
                                "\/": {
                                    "current": "10464",
                                    "unit": "MB",
                                    "warning": "11433",
                                    "critical": "12862",
                                    "min": "0",
                                    "max": "14292"
                                }
                            },
                            "perfdataArrayCounter": 1
                        }
                    },
                    "3": {
                        "Service": {
                            "id": 3,
                            "uuid": "1c045407-5502-4468-aabc-7781f6cf3dec",
                            "servicename": "CHECK_LOCAL_LOAD",
                            "hostname": "default host",
                            "description": null,
                            "active_checks_enabled": true,
                            "tags": null,
                            "host_id": 1,
                            "allow_edit": true,
                            "disabled": false
                        },
                        "Servicestatus": {
                            "currentState": 0,
                            "lastHardState": null,
                            "isFlapping": false,
                            "problemHasBeenAcknowledged": false,
                            "scheduledDowntimeDepth": 1,
                            "lastCheck": "2 minutes ago",
                            "nextCheck": "in 3 minutes",
                            "activeChecksEnabled": "1",
                            "lastHardStateChange": "7M 4D 18h 41m 22s",
                            "last_state_change": "7M 4D 18h 41m 22s",
                            "processPerformanceData": null,
                            "state_type": "1",
                            "acknowledgement_type": 0,
                            "flap_detection_enabled": null,
                            "notifications_enabled": null,
                            "current_check_attempt": "1",
                            "output": "OK - load average: 0.20, 0.13, 0.10",
                            "long_output": null,
                            "perfdata": "load1=0.200;7.000;10.000;0; load5=0.130;6.000;7.000;0; load15=0.100;5.000;6.000;0;",
                            "latency": null,
                            "max_check_attempts": 3,
                            "isHardstate": true,
                            "isInMonitoring": true,
                            "humanState": "ok",
                            "perfdataArray": {
                                "load1": {
                                    "current": "0.200",
                                    "unit": null,
                                    "warning": "7.000",
                                    "critical": "10.000",
                                    "min": "0",
                                    "max": null
                                },
                                "load5": {
                                    "current": "0.130",
                                    "unit": null,
                                    "warning": "6.000",
                                    "critical": "7.000",
                                    "min": "0",
                                    "max": null
                                },
                                "load15": {
                                    "current": "0.100",
                                    "unit": null,
                                    "warning": "5.000",
                                    "critical": "6.000",
                                    "min": "0",
                                    "max": null
                                }
                            },
                            "perfdataArrayCounter": 3
                        }
                    },
                    "4": {
                        "Service": {
                            "id": 4,
                            "uuid": "7391f1aa-5e2e-447a-8a9b-b23357b9cd2a",
                            "servicename": "CHECK_LOCAL_USERS",
                            "hostname": "default host",
                            "description": null,
                            "active_checks_enabled": true,
                            "tags": null,
                            "host_id": 1,
                            "allow_edit": true,
                            "disabled": false
                        },
                        "Servicestatus": {
                            "currentState": 3,
                            "lastHardState": null,
                            "isFlapping": false,
                            "problemHasBeenAcknowledged": false,
                            "scheduledDowntimeDepth": 1,
                            "lastCheck": "49 seconds ago",
                            "nextCheck": "in 4 minutes",
                            "activeChecksEnabled": "1",
                            "lastHardStateChange": "13D 21h 2m 36s",
                            "last_state_change": "13D 21h 4m 36s",
                            "processPerformanceData": null,
                            "state_type": "1",
                            "acknowledgement_type": 0,
                            "flap_detection_enabled": null,
                            "notifications_enabled": null,
                            "current_check_attempt": "3",
                            "output": "USERS WARNING - 4 users currently logged in",
                            "long_output": null,
                            "perfdata": "users=4;3;7;0",
                            "latency": null,
                            "max_check_attempts": 3,
                            "isHardstate": true,
                            "isInMonitoring": true,
                            "humanState": "unknown",
                            "perfdataArray": {
                                "users": {
                                    "current": "4",
                                    "unit": null,
                                    "warning": "3",
                                    "critical": "7",
                                    "min": "0",
                                    "max": null
                                }
                            },
                            "perfdataArrayCounter": 1
                        }
                    },
                    "1": {
                        "Service": {
                            "id": 1,
                            "uuid": "74fd8f59-1348-4e16-85f0-4a5c57c7dd62",
                            "servicename": "Ping",
                            "hostname": "default host",
                            "description": null,
                            "active_checks_enabled": true,
                            "tags": null,
                            "host_id": 1,
                            "allow_edit": true,
                            "disabled": false
                        },
                        "Servicestatus": {
                            "currentState": 0,
                            "lastHardState": null,
                            "isFlapping": false,
                            "problemHasBeenAcknowledged": false,
                            "scheduledDowntimeDepth": 1,
                            "lastCheck": "3 minutes ago",
                            "nextCheck": "in 1 minute",
                            "activeChecksEnabled": "1",
                            "lastHardStateChange": "7M 4D 23h 46m 47s",
                            "last_state_change": "7M 4D 23h 46m 47s",
                            "processPerformanceData": null,
                            "state_type": "1",
                            "acknowledgement_type": 0,
                            "flap_detection_enabled": null,
                            "notifications_enabled": null,
                            "current_check_attempt": "1",
                            "output": "PING OK - Packet loss = 0%, RTA = 0.05 ms",
                            "long_output": null,
                            "perfdata": "rta=0.054000ms;100.000000;500.000000;0.000000 pl=0%;20;60;0",
                            "latency": null,
                            "max_check_attempts": 3,
                            "isHardstate": true,
                            "isInMonitoring": true,
                            "humanState": "ok",
                            "perfdataArray": {
                                "rta": {
                                    "current": "0.054000",
                                    "unit": "ms",
                                    "warning": "100.000000",
                                    "critical": "500.000000",
                                    "min": "0.000000",
                                    "max": null
                                },
                                "pl": {
                                    "current": "0",
                                    "unit": "%",
                                    "warning": "20",
                                    "critical": "60",
                                    "min": "0",
                                    "max": null
                                }
                            },
                            "perfdataArrayCounter": 2
                        }
                    }
                }
            },
            "10": {
                "Host": {
                    "id": 10,
                    "uuid": "9801fe9e-ebc1-4017-b41c-e66bced919e5",
                    "hostname": "host name du lauch",
                    "address": "host name du lauch",
                    "description": null,
                    "active_checks_enabled": null,
                    "satelliteId": null,
                    "containerId": null,
                    "containerIds": null,
                    "tags": null,
                    "allow_edit": true,
                    "disabled": false,
                    "is_satellite_host": false
                },
                "Hoststatus": {
                    "currentState": 1,
                    "isFlapping": false,
                    "problemHasBeenAcknowledged": false,
                    "scheduledDowntimeDepth": "0",
                    "lastCheck": "on 1\/1\/70",
                    "nextCheck": "on 1\/1\/70",
                    "activeChecksEnabled": null,
                    "lastHardStateChange": "3M 6D 1h 54m 12s",
                    "last_state_change": "49Y 6M 21D 9h 59m 9s",
                    "output": "check_icmp: Failed to resolve host",
                    "long_output": null,
                    "acknowledgement_type": null,
                    "state_type": "1",
                    "flap_detection_enabled": null,
                    "notifications_enabled": null,
                    "current_check_attempt": "1",
                    "max_check_attempts": "3",
                    "latency": null,
                    "isHardstate": true,
                    "isInMonitoring": true,
                    "humanState": "down"
                },
                "Services": {
                    "31": {
                        "Service": {
                            "id": 31,
                            "uuid": "d6491ff2-b6f1-4d60-97a4-8859b80384c9",
                            "servicename": "CHECK_HTTP",
                            "hostname": "host name du lauch",
                            "description": null,
                            "active_checks_enabled": true,
                            "tags": null,
                            "host_id": 10,
                            "allow_edit": true,
                            "disabled": false
                        },
                        "Servicestatus": {
                            "currentState": 2,
                            "lastHardState": null,
                            "isFlapping": false,
                            "problemHasBeenAcknowledged": false,
                            "scheduledDowntimeDepth": 0,
                            "lastCheck": "36 seconds ago",
                            "nextCheck": "in 4 minutes",
                            "activeChecksEnabled": "1",
                            "lastHardStateChange": "1M 7D 0h 36m 8s",
                            "last_state_change": "1M 7D 0h 36m 8s",
                            "processPerformanceData": null,
                            "state_type": "1",
                            "acknowledgement_type": 0,
                            "flap_detection_enabled": null,
                            "notifications_enabled": null,
                            "current_check_attempt": "3",
                            "output": "Tempor\u00e4rer Fehler bei der Namensaufl\u00f6sung",
                            "long_output": null,
                            "perfdata": "",
                            "latency": null,
                            "max_check_attempts": 3,
                            "isHardstate": true,
                            "isInMonitoring": true,
                            "humanState": "critical",
                            "perfdataArray": [],
                            "perfdataArrayCounter": 0
                        }
                    }
                }
            },
            "6": {
                "Host": {
                    "id": 6,
                    "uuid": "d8ad9c23-ff4b-4720-91ec-f701b5adb668",
                    "hostname": "localhost",
                    "address": "127.0.0.1",
                    "description": "default host OITC Container",
                    "active_checks_enabled": null,
                    "satelliteId": null,
                    "containerId": null,
                    "containerIds": null,
                    "tags": null,
                    "allow_edit": true,
                    "disabled": false,
                    "is_satellite_host": false
                },
                "Hoststatus": {
                    "currentState": 0,
                    "isFlapping": false,
                    "problemHasBeenAcknowledged": false,
                    "scheduledDowntimeDepth": "0",
                    "lastCheck": "on 1\/1\/70",
                    "nextCheck": "on 1\/1\/70",
                    "activeChecksEnabled": null,
                    "lastHardStateChange": "5M 0D 19h 11m 40s",
                    "last_state_change": "49Y 6M 21D 9h 59m 9s",
                    "output": "OK - 127.0.0.1: rta 0,061ms, lost 0%",
                    "long_output": null,
                    "acknowledgement_type": null,
                    "state_type": "1",
                    "flap_detection_enabled": null,
                    "notifications_enabled": null,
                    "current_check_attempt": "1",
                    "max_check_attempts": "3",
                    "latency": null,
                    "isHardstate": true,
                    "isInMonitoring": true,
                    "humanState": "up"
                },
                "Services": {
                    "28": {
                        "Service": {
                            "id": 28,
                            "uuid": "cc5f8110-2e63-4be9-a5fd-ec556673e16a",
                            "servicename": "Ping SE Test",
                            "hostname": "localhost",
                            "description": null,
                            "active_checks_enabled": true,
                            "tags": null,
                            "host_id": 6,
                            "allow_edit": true,
                            "disabled": false
                        },
                        "Servicestatus": {
                            "currentState": 0,
                            "lastHardState": null,
                            "isFlapping": false,
                            "problemHasBeenAcknowledged": false,
                            "scheduledDowntimeDepth": 0,
                            "lastCheck": "1 minute ago",
                            "nextCheck": "in 3 minutes",
                            "activeChecksEnabled": "1",
                            "lastHardStateChange": "1M 8D 1h 24m 42s",
                            "last_state_change": "1M 8D 1h 24m 42s",
                            "processPerformanceData": null,
                            "state_type": "1",
                            "acknowledgement_type": 0,
                            "flap_detection_enabled": null,
                            "notifications_enabled": null,
                            "current_check_attempt": "1",
                            "output": "PING OK - Packet loss = 0%, RTA = 0.05 ms",
                            "long_output": null,
                            "perfdata": "rta=0.055000ms;100.000000;500.000000;0.000000 pl=0%;20;60;0",
                            "latency": null,
                            "max_check_attempts": 3,
                            "isHardstate": true,
                            "isInMonitoring": true,
                            "humanState": "ok",
                            "perfdataArray": {
                                "rta": {
                                    "current": "0.055000",
                                    "unit": "ms",
                                    "warning": "100.000000",
                                    "critical": "500.000000",
                                    "min": "0.000000",
                                    "max": null
                                },
                                "pl": {
                                    "current": "0",
                                    "unit": "%",
                                    "warning": "20",
                                    "critical": "60",
                                    "min": "0",
                                    "max": null
                                }
                            },
                            "perfdataArrayCounter": 2
                        }
                    }
                }
            },
            "3": {
                "Host": {
                    "id": 3,
                    "uuid": "19d7cbda-a53d-481d-a255-037e32c74bd1",
                    "hostname": "srvoitcvbox01.master.dns",
                    "address": "172.16.2.2",
                    "description": "openITCOCKPIT VirtualBox Server",
                    "active_checks_enabled": null,
                    "satelliteId": null,
                    "containerId": null,
                    "containerIds": null,
                    "tags": null,
                    "allow_edit": true,
                    "disabled": false,
                    "is_satellite_host": false
                },
                "Hoststatus": {
                    "currentState": 0,
                    "isFlapping": false,
                    "problemHasBeenAcknowledged": false,
                    "scheduledDowntimeDepth": "0",
                    "lastCheck": "on 1\/1\/70",
                    "nextCheck": "on 1\/1\/70",
                    "activeChecksEnabled": null,
                    "lastHardStateChange": "2M 16D 20h 4m 49s",
                    "last_state_change": "49Y 6M 21D 9h 59m 9s",
                    "output": "OK - 172.16.2.2: rta 0,303ms, lost 0%",
                    "long_output": null,
                    "acknowledgement_type": null,
                    "state_type": "1",
                    "flap_detection_enabled": null,
                    "notifications_enabled": null,
                    "current_check_attempt": "1",
                    "max_check_attempts": "3",
                    "latency": null,
                    "isHardstate": true,
                    "isInMonitoring": true,
                    "humanState": "up"
                },
                "Services": {
                    "27": {
                        "Service": {
                            "id": 27,
                            "uuid": "ca16b3c7-07f0-44fc-a321-2a6f7e904485",
                            "servicename": "CHECK_MK_ACTIVE",
                            "hostname": "srvoitcvbox01.master.dns",
                            "description": null,
                            "active_checks_enabled": true,
                            "tags": null,
                            "host_id": 3,
                            "allow_edit": true,
                            "disabled": false
                        },
                        "Servicestatus": {
                            "currentState": 0,
                            "lastHardState": null,
                            "isFlapping": false,
                            "problemHasBeenAcknowledged": false,
                            "scheduledDowntimeDepth": 0,
                            "lastCheck": "45 seconds ago",
                            "nextCheck": "in 15 seconds",
                            "activeChecksEnabled": "1",
                            "lastHardStateChange": "2M 16D 20h 4m 49s",
                            "last_state_change": "2M 16D 20h 4m 49s",
                            "processPerformanceData": null,
                            "state_type": "1",
                            "acknowledgement_type": 0,
                            "flap_detection_enabled": null,
                            "notifications_enabled": null,
                            "current_check_attempt": "1",
                            "output": "OK - Agent version 1.2.2p1, execution time 0.1 sec",
                            "long_output": null,
                            "perfdata": "execution_time=0.115 user_time=0.030 system_time=0.020 children_user_time=0.000 children_system_time=0.000",
                            "latency": null,
                            "max_check_attempts": 1,
                            "isHardstate": true,
                            "isInMonitoring": true,
                            "humanState": "ok",
                            "perfdataArray": {
                                "execution_time": {
                                    "current": "0.115",
                                    "unit": null,
                                    "warning": null,
                                    "critical": null,
                                    "min": null,
                                    "max": null
                                },
                                "user_time": {
                                    "current": "0.030",
                                    "unit": null,
                                    "warning": null,
                                    "critical": null,
                                    "min": null,
                                    "max": null
                                },
                                "system_time": {
                                    "current": "0.020",
                                    "unit": null,
                                    "warning": null,
                                    "critical": null,
                                    "min": null,
                                    "max": null
                                },
                                "children_user_time": {
                                    "current": "0.000",
                                    "unit": null,
                                    "warning": null,
                                    "critical": null,
                                    "min": null,
                                    "max": null
                                },
                                "children_system_time": {
                                    "current": "0.000",
                                    "unit": null,
                                    "warning": null,
                                    "critical": null,
                                    "min": null,
                                    "max": null
                                }
                            },
                            "perfdataArrayCounter": 5
                        }
                    },
                    "7": {
                        "Service": {
                            "id": 7,
                            "uuid": "2831d34c-a915-457d-8f37-eeb39a1a584c",
                            "servicename": "CPU load",
                            "hostname": "srvoitcvbox01.master.dns",
                            "description": null,
                            "active_checks_enabled": false,
                            "tags": null,
                            "host_id": 3,
                            "allow_edit": true,
                            "disabled": false
                        },
                        "Servicestatus": {
                            "currentState": 0,
                            "lastHardState": null,
                            "isFlapping": false,
                            "problemHasBeenAcknowledged": false,
                            "scheduledDowntimeDepth": 0,
                            "lastCheck": "45 seconds ago",
                            "nextCheck": "in 15 seconds",
                            "activeChecksEnabled": "0",
                            "lastHardStateChange": "5M 1D 1h 47m 25s",
                            "last_state_change": "5M 1D 1h 47m 25s",
                            "processPerformanceData": null,
                            "state_type": "1",
                            "acknowledgement_type": 0,
                            "flap_detection_enabled": null,
                            "notifications_enabled": null,
                            "current_check_attempt": "1",
                            "output": "OK - 15min load 0.77 at 4 CPUs",
                            "long_output": null,
                            "perfdata": "load1=0.87;;;0;4 load5=0.8;;;0;4 load15=0.77;;;0;4",
                            "latency": null,
                            "max_check_attempts": 1,
                            "isHardstate": true,
                            "isInMonitoring": true,
                            "humanState": "ok",
                            "perfdataArray": {
                                "load1": {
                                    "current": "0.87",
                                    "unit": null,
                                    "warning": null,
                                    "critical": null,
                                    "min": "0",
                                    "max": "4"
                                },
                                "load5": {
                                    "current": "0.8",
                                    "unit": null,
                                    "warning": null,
                                    "critical": null,
                                    "min": "0",
                                    "max": "4"
                                },
                                "load15": {
                                    "current": "0.77",
                                    "unit": null,
                                    "warning": null,
                                    "critical": null,
                                    "min": "0",
                                    "max": "4"
                                }
                            },
                            "perfdataArrayCounter": 3
                        }
                    },
                    "8": {
                        "Service": {
                            "id": 8,
                            "uuid": "d5c4c676-8736-4aef-88a8-bcc248783727",
                            "servicename": "CPU utilization",
                            "hostname": "srvoitcvbox01.master.dns",
                            "description": null,
                            "active_checks_enabled": false,
                            "tags": null,
                            "host_id": 3,
                            "allow_edit": true,
                            "disabled": false
                        },
                        "Servicestatus": {
                            "currentState": 0,
                            "lastHardState": null,
                            "isFlapping": false,
                            "problemHasBeenAcknowledged": false,
                            "scheduledDowntimeDepth": 0,
                            "lastCheck": "45 seconds ago",
                            "nextCheck": "in 23 seconds",
                            "activeChecksEnabled": "0",
                            "lastHardStateChange": "5M 1D 1h 47m 25s",
                            "last_state_change": "5M 1D 1h 47m 25s",
                            "processPerformanceData": null,
                            "state_type": "1",
                            "acknowledgement_type": 0,
                            "flap_detection_enabled": null,
                            "notifications_enabled": null,
                            "current_check_attempt": "1",
                            "output": "OK - user: 4.2%, system: 11.2%, wait: 0.2%, total: 15.6%",
                            "long_output": null,
                            "perfdata": "user=4.218;;;; system=11.198;;;; wait=0.229;;;;",
                            "latency": null,
                            "max_check_attempts": 1,
                            "isHardstate": true,
                            "isInMonitoring": true,
                            "humanState": "ok",
                            "perfdataArray": {
                                "user": {
                                    "current": "4.218",
                                    "unit": null,
                                    "warning": null,
                                    "critical": null,
                                    "min": null,
                                    "max": null
                                },
                                "system": {
                                    "current": "11.198",
                                    "unit": null,
                                    "warning": null,
                                    "critical": null,
                                    "min": null,
                                    "max": null
                                },
                                "wait": {
                                    "current": "0.229",
                                    "unit": null,
                                    "warning": null,
                                    "critical": null,
                                    "min": null,
                                    "max": null
                                }
                            },
                            "perfdataArrayCounter": 3
                        }
                    },
                    "9": {
                        "Service": {
                            "id": 9,
                            "uuid": "344b5ddc-e35b-4830-b9f7-1f62bbb9414f",
                            "servicename": "Disk IO SUMMARY",
                            "hostname": "srvoitcvbox01.master.dns",
                            "description": null,
                            "active_checks_enabled": false,
                            "tags": null,
                            "host_id": 3,
                            "allow_edit": true,
                            "disabled": false
                        },
                        "Servicestatus": {
                            "currentState": 0,
                            "lastHardState": null,
                            "isFlapping": false,
                            "problemHasBeenAcknowledged": false,
                            "scheduledDowntimeDepth": 0,
                            "lastCheck": "45 seconds ago",
                            "nextCheck": "in 16 seconds",
                            "activeChecksEnabled": "0",
                            "lastHardStateChange": "15D 6h 12m 13s",
                            "last_state_change": "15D 6h 12m 13s",
                            "processPerformanceData": null,
                            "state_type": "1",
                            "acknowledgement_type": 0,
                            "flap_detection_enabled": null,
                            "notifications_enabled": null,
                            "current_check_attempt": "1",
                            "output": "OK - 921.60 B\/sec read, 144.00 kB\/sec write, IOs: 14.10\/sec",
                            "long_output": null,
                            "perfdata": "read=921.6;10;20;; write=147456;10;40;; read.avg=653.491902;;;; write.avg=156262.086762;;;;",
                            "latency": null,
                            "max_check_attempts": 1,
                            "isHardstate": true,
                            "isInMonitoring": true,
                            "humanState": "ok",
                            "perfdataArray": {
                                "read": {
                                    "current": "921.6",
                                    "unit": null,
                                    "warning": "10",
                                    "critical": "20",
                                    "min": null,
                                    "max": null
                                },
                                "write": {
                                    "current": "147456",
                                    "unit": null,
                                    "warning": "10",
                                    "critical": "40",
                                    "min": null,
                                    "max": null
                                },
                                "read.avg": {
                                    "current": "653.491902",
                                    "unit": null,
                                    "warning": null,
                                    "critical": null,
                                    "min": null,
                                    "max": null
                                },
                                "write.avg": {
                                    "current": "156262.086762",
                                    "unit": null,
                                    "warning": null,
                                    "critical": null,
                                    "min": null,
                                    "max": null
                                }
                            },
                            "perfdataArrayCounter": 4
                        }
                    },
                    "24": {
                        "Service": {
                            "id": 24,
                            "uuid": "4f15c664-7797-4de8-84f5-6cac3904f2ad",
                            "servicename": "fs_\/",
                            "hostname": "srvoitcvbox01.master.dns",
                            "description": null,
                            "active_checks_enabled": false,
                            "tags": null,
                            "host_id": 3,
                            "allow_edit": true,
                            "disabled": false
                        },
                        "Servicestatus": {
                            "currentState": 0,
                            "lastHardState": null,
                            "isFlapping": false,
                            "problemHasBeenAcknowledged": false,
                            "scheduledDowntimeDepth": 0,
                            "lastCheck": "45 seconds ago",
                            "nextCheck": "in 33 seconds",
                            "activeChecksEnabled": "0",
                            "lastHardStateChange": "5M 1D 1h 47m 25s",
                            "last_state_change": "5M 1D 1h 47m 25s",
                            "processPerformanceData": null,
                            "state_type": "1",
                            "acknowledgement_type": 0,
                            "flap_detection_enabled": null,
                            "notifications_enabled": null,
                            "current_check_attempt": "1",
                            "output": "OK - 49.2% used (9.02 of 18.33 GB), (levels at 80.00\/90.00%), trend: -43.75 MB \/ 24 hours",
                            "long_output": null,
                            "perfdata": "\/=9236.76953125MB;15018.165625;16895.436328;0;18772.707031 growth=151.853748;;;; trend=-43.754243;;;0;782.196126",
                            "latency": null,
                            "max_check_attempts": 1,
                            "isHardstate": true,
                            "isInMonitoring": true,
                            "humanState": "ok",
                            "perfdataArray": {
                                "\/": {
                                    "current": "9236.76953125",
                                    "unit": "MB",
                                    "warning": "15018.165625",
                                    "critical": "16895.436328",
                                    "min": "0",
                                    "max": "18772.707031"
                                },
                                "growth": {
                                    "current": "151.853748",
                                    "unit": null,
                                    "warning": null,
                                    "critical": null,
                                    "min": null,
                                    "max": null
                                },
                                "trend": {
                                    "current": "-43.754243",
                                    "unit": null,
                                    "warning": null,
                                    "critical": null,
                                    "min": "0",
                                    "max": "782.196126"
                                }
                            },
                            "perfdataArrayCounter": 3
                        }
                    },
                    "25": {
                        "Service": {
                            "id": 25,
                            "uuid": "f953d6c0-2645-4de7-9f77-7c7ce376df08",
                            "servicename": "fs_\/boot",
                            "hostname": "srvoitcvbox01.master.dns",
                            "description": null,
                            "active_checks_enabled": false,
                            "tags": null,
                            "host_id": 3,
                            "allow_edit": true,
                            "disabled": false
                        },
                        "Servicestatus": {
                            "currentState": 0,
                            "lastHardState": null,
                            "isFlapping": false,
                            "problemHasBeenAcknowledged": false,
                            "scheduledDowntimeDepth": 0,
                            "lastCheck": "45 seconds ago",
                            "nextCheck": "in 33 seconds",
                            "activeChecksEnabled": "0",
                            "lastHardStateChange": "5M 1D 1h 47m 25s",
                            "last_state_change": "5M 1D 1h 47m 25s",
                            "processPerformanceData": null,
                            "state_type": "1",
                            "acknowledgement_type": 0,
                            "flap_detection_enabled": null,
                            "notifications_enabled": null,
                            "current_check_attempt": "1",
                            "output": "OK - 62.2% used (114.25 of 183.80 MB), (levels at 80.00\/90.00%), trend: 0.00 B \/ 24 hours",
                            "long_output": null,
                            "perfdata": "\/boot=114.248046875MB;147.039844;165.419824;0;183.799805 growth=0;;;; trend=0;;;0;7.658325",
                            "latency": null,
                            "max_check_attempts": 1,
                            "isHardstate": true,
                            "isInMonitoring": true,
                            "humanState": "ok",
                            "perfdataArray": {
                                "\/boot": {
                                    "current": "114.248046875",
                                    "unit": "MB",
                                    "warning": "147.039844",
                                    "critical": "165.419824",
                                    "min": "0",
                                    "max": "183.799805"
                                },
                                "growth": {
                                    "current": "0",
                                    "unit": null,
                                    "warning": null,
                                    "critical": null,
                                    "min": null,
                                    "max": null
                                },
                                "trend": {
                                    "current": "0",
                                    "unit": null,
                                    "warning": null,
                                    "critical": null,
                                    "min": "0",
                                    "max": "7.658325"
                                }
                            },
                            "perfdataArrayCounter": 3
                        }
                    },
                    "26": {
                        "Service": {
                            "id": 26,
                            "uuid": "73fc070c-9727-4938-9be3-b8f5e68a99a6",
                            "servicename": "fs_\/vm\/ds01",
                            "hostname": "srvoitcvbox01.master.dns",
                            "description": null,
                            "active_checks_enabled": false,
                            "tags": null,
                            "host_id": 3,
                            "allow_edit": true,
                            "disabled": false
                        },
                        "Servicestatus": {
                            "currentState": 0,
                            "lastHardState": null,
                            "isFlapping": false,
                            "problemHasBeenAcknowledged": false,
                            "scheduledDowntimeDepth": 0,
                            "lastCheck": "45 seconds ago",
                            "nextCheck": "in 16 seconds",
                            "activeChecksEnabled": "0",
                            "lastHardStateChange": "5M 1D 1h 47m 25s",
                            "last_state_change": "5M 1D 1h 47m 25s",
                            "processPerformanceData": null,
                            "state_type": "1",
                            "acknowledgement_type": 0,
                            "flap_detection_enabled": null,
                            "notifications_enabled": null,
                            "current_check_attempt": "1",
                            "output": "OK - 43.0% used (188.42 of 437.79 GB), (levels at 80.00\/90.00%), trend: +10.34 MB \/ 24 hours",
                            "long_output": null,
                            "perfdata": "\/vm\/ds01=192947.175781MB;358634.4;403463.7;0;448293 growth=0;;;; trend=10.340869;;;0;18678.875",
                            "latency": null,
                            "max_check_attempts": 1,
                            "isHardstate": true,
                            "isInMonitoring": true,
                            "humanState": "ok",
                            "perfdataArray": {
                                "\/vm\/ds01": {
                                    "current": "192947.175781",
                                    "unit": "MB",
                                    "warning": "358634.4",
                                    "critical": "403463.7",
                                    "min": "0",
                                    "max": "448293"
                                },
                                "growth": {
                                    "current": "0",
                                    "unit": null,
                                    "warning": null,
                                    "critical": null,
                                    "min": null,
                                    "max": null
                                },
                                "trend": {
                                    "current": "10.340869",
                                    "unit": null,
                                    "warning": null,
                                    "critical": null,
                                    "min": "0",
                                    "max": "18678.875"
                                }
                            },
                            "perfdataArrayCounter": 3
                        }
                    },
                    "10": {
                        "Service": {
                            "id": 10,
                            "uuid": "e825cb02-d44d-4fe1-ba3d-f361c35dcb13",
                            "servicename": "Interface 1",
                            "hostname": "srvoitcvbox01.master.dns",
                            "description": null,
                            "active_checks_enabled": false,
                            "tags": null,
                            "host_id": 3,
                            "allow_edit": true,
                            "disabled": false
                        },
                        "Servicestatus": {
                            "currentState": 0,
                            "lastHardState": null,
                            "isFlapping": false,
                            "problemHasBeenAcknowledged": false,
                            "scheduledDowntimeDepth": 0,
                            "lastCheck": "45 seconds ago",
                            "nextCheck": "in 34 seconds",
                            "activeChecksEnabled": "0",
                            "lastHardStateChange": "5M 1D 1h 46m 25s",
                            "last_state_change": "5M 1D 1h 46m 25s",
                            "processPerformanceData": null,
                            "state_type": "1",
                            "acknowledgement_type": 0,
                            "flap_detection_enabled": null,
                            "notifications_enabled": null,
                            "current_check_attempt": "1",
                            "output": "OK - [vlan19] (up) 1 GBit\/s, in: 0.00 B\/s, out: 0.00 B\/s",
                            "long_output": null,
                            "perfdata": "in=0;62500000;100000000;0;125000000 inucast=0;;;; innucast=0;;;; indisc=0;;;; inerr=0;0.01;0.1;; out=0;62500000;100000000;0;125000000 outucast=0;;;; outnucast=0;;;; outdisc=0;;;; outerr=0;0.01;0.1;; outqlen=0;;;0;",
                            "latency": null,
                            "max_check_attempts": 1,
                            "isHardstate": true,
                            "isInMonitoring": true,
                            "humanState": "ok",
                            "perfdataArray": {
                                "in": {
                                    "current": "0",
                                    "unit": null,
                                    "warning": "62500000",
                                    "critical": "100000000",
                                    "min": "0",
                                    "max": "125000000"
                                },
                                "inucast": {
                                    "current": "0",
                                    "unit": null,
                                    "warning": null,
                                    "critical": null,
                                    "min": null,
                                    "max": null
                                },
                                "innucast": {
                                    "current": "0",
                                    "unit": null,
                                    "warning": null,
                                    "critical": null,
                                    "min": null,
                                    "max": null
                                },
                                "indisc": {
                                    "current": "0",
                                    "unit": null,
                                    "warning": null,
                                    "critical": null,
                                    "min": null,
                                    "max": null
                                },
                                "inerr": {
                                    "current": "0",
                                    "unit": null,
                                    "warning": "0.01",
                                    "critical": "0.1",
                                    "min": null,
                                    "max": null
                                },
                                "out": {
                                    "current": "0",
                                    "unit": null,
                                    "warning": "62500000",
                                    "critical": "100000000",
                                    "min": "0",
                                    "max": "125000000"
                                },
                                "outucast": {
                                    "current": "0",
                                    "unit": null,
                                    "warning": null,
                                    "critical": null,
                                    "min": null,
                                    "max": null
                                },
                                "outnucast": {
                                    "current": "0",
                                    "unit": null,
                                    "warning": null,
                                    "critical": null,
                                    "min": null,
                                    "max": null
                                },
                                "outdisc": {
                                    "current": "0",
                                    "unit": null,
                                    "warning": null,
                                    "critical": null,
                                    "min": null,
                                    "max": null
                                },
                                "outerr": {
                                    "current": "0",
                                    "unit": null,
                                    "warning": "0.01",
                                    "critical": "0.1",
                                    "min": null,
                                    "max": null
                                },
                                "outqlen": {
                                    "current": "0",
                                    "unit": null,
                                    "warning": null,
                                    "critical": null,
                                    "min": "0",
                                    "max": null
                                }
                            },
                            "perfdataArrayCounter": 11
                        }
                    },
                    "11": {
                        "Service": {
                            "id": 11,
                            "uuid": "bcfabf3c-ed86-48aa-a0f3-2c41a66e282a",
                            "servicename": "Interface 3",
                            "hostname": "srvoitcvbox01.master.dns",
                            "description": null,
                            "active_checks_enabled": false,
                            "tags": null,
                            "host_id": 3,
                            "allow_edit": true,
                            "disabled": false
                        },
                        "Servicestatus": {
                            "currentState": 0,
                            "lastHardState": null,
                            "isFlapping": false,
                            "problemHasBeenAcknowledged": false,
                            "scheduledDowntimeDepth": 0,
                            "lastCheck": "45 seconds ago",
                            "nextCheck": "in 15 seconds",
                            "activeChecksEnabled": "0",
                            "lastHardStateChange": "5M 1D 1h 46m 25s",
                            "last_state_change": "5M 1D 1h 46m 25s",
                            "processPerformanceData": null,
                            "state_type": "1",
                            "acknowledgement_type": 0,
                            "flap_detection_enabled": null,
                            "notifications_enabled": null,
                            "current_check_attempt": "1",
                            "output": "OK - [eth0] (up) 1 GBit\/s, in: 173.63 kB\/s, out: 195.95 kB\/s",
                            "long_output": null,
                            "perfdata": "in=177792.89141;62500000;100000000;0;125000000 inucast=953.599288;;;; innucast=1.533118;;;; indisc=0;;;; inerr=0;0.01;0.1;; out=200655.894278;62500000;100000000;0;125000000 outucast=877.326676;;;; outnucast=0;;;; outdisc=0;;;; outerr=0;0.01;0.1;; outqlen=0;;;0;",
                            "latency": null,
                            "max_check_attempts": 1,
                            "isHardstate": true,
                            "isInMonitoring": true,
                            "humanState": "ok",
                            "perfdataArray": {
                                "in": {
                                    "current": "177792.89141",
                                    "unit": null,
                                    "warning": "62500000",
                                    "critical": "100000000",
                                    "min": "0",
                                    "max": "125000000"
                                },
                                "inucast": {
                                    "current": "953.599288",
                                    "unit": null,
                                    "warning": null,
                                    "critical": null,
                                    "min": null,
                                    "max": null
                                },
                                "innucast": {
                                    "current": "1.533118",
                                    "unit": null,
                                    "warning": null,
                                    "critical": null,
                                    "min": null,
                                    "max": null
                                },
                                "indisc": {
                                    "current": "0",
                                    "unit": null,
                                    "warning": null,
                                    "critical": null,
                                    "min": null,
                                    "max": null
                                },
                                "inerr": {
                                    "current": "0",
                                    "unit": null,
                                    "warning": "0.01",
                                    "critical": "0.1",
                                    "min": null,
                                    "max": null
                                },
                                "out": {
                                    "current": "200655.894278",
                                    "unit": null,
                                    "warning": "62500000",
                                    "critical": "100000000",
                                    "min": "0",
                                    "max": "125000000"
                                },
                                "outucast": {
                                    "current": "877.326676",
                                    "unit": null,
                                    "warning": null,
                                    "critical": null,
                                    "min": null,
                                    "max": null
                                },
                                "outnucast": {
                                    "current": "0",
                                    "unit": null,
                                    "warning": null,
                                    "critical": null,
                                    "min": null,
                                    "max": null
                                },
                                "outdisc": {
                                    "current": "0",
                                    "unit": null,
                                    "warning": null,
                                    "critical": null,
                                    "min": null,
                                    "max": null
                                },
                                "outerr": {
                                    "current": "0",
                                    "unit": null,
                                    "warning": "0.01",
                                    "critical": "0.1",
                                    "min": null,
                                    "max": null
                                },
                                "outqlen": {
                                    "current": "0",
                                    "unit": null,
                                    "warning": null,
                                    "critical": null,
                                    "min": "0",
                                    "max": null
                                }
                            },
                            "perfdataArrayCounter": 11
                        }
                    },
                    "12": {
                        "Service": {
                            "id": 12,
                            "uuid": "ce3d9b15-5105-46fb-93ff-ce35d256a905",
                            "servicename": "Interface 4",
                            "hostname": "srvoitcvbox01.master.dns",
                            "description": null,
                            "active_checks_enabled": false,
                            "tags": null,
                            "host_id": 3,
                            "allow_edit": true,
                            "disabled": false
                        },
                        "Servicestatus": {
                            "currentState": 0,
                            "lastHardState": null,
                            "isFlapping": false,
                            "problemHasBeenAcknowledged": false,
                            "scheduledDowntimeDepth": 0,
                            "lastCheck": "45 seconds ago",
                            "nextCheck": "in 53 seconds",
                            "activeChecksEnabled": "0",
                            "lastHardStateChange": "5M 1D 1h 46m 25s",
                            "last_state_change": "5M 1D 1h 46m 25s",
                            "processPerformanceData": null,
                            "state_type": "1",
                            "acknowledgement_type": 0,
                            "flap_detection_enabled": null,
                            "notifications_enabled": null,
                            "current_check_attempt": "1",
                            "output": "OK - [eth1] (up) 1 GBit\/s, in: 41.93 B\/s, out: 19.50 B\/s",
                            "long_output": null,
                            "perfdata": "in=41.927519;62500000;100000000;0;125000000 inucast=1.083183;;;; innucast=0.533259;;;; indisc=0;;;; inerr=0;0.01;0.1;; out=19.497296;62500000;100000000;0;125000000 outucast=0.049993;;;; outnucast=0;;;; outdisc=0;;;; outerr=0;0.01;0.1;; outqlen=0;;;0;",
                            "latency": null,
                            "max_check_attempts": 1,
                            "isHardstate": true,
                            "isInMonitoring": true,
                            "humanState": "ok",
                            "perfdataArray": {
                                "in": {
                                    "current": "41.927519",
                                    "unit": null,
                                    "warning": "62500000",
                                    "critical": "100000000",
                                    "min": "0",
                                    "max": "125000000"
                                },
                                "inucast": {
                                    "current": "1.083183",
                                    "unit": null,
                                    "warning": null,
                                    "critical": null,
                                    "min": null,
                                    "max": null
                                },
                                "innucast": {
                                    "current": "0.533259",
                                    "unit": null,
                                    "warning": null,
                                    "critical": null,
                                    "min": null,
                                    "max": null
                                },
                                "indisc": {
                                    "current": "0",
                                    "unit": null,
                                    "warning": null,
                                    "critical": null,
                                    "min": null,
                                    "max": null
                                },
                                "inerr": {
                                    "current": "0",
                                    "unit": null,
                                    "warning": "0.01",
                                    "critical": "0.1",
                                    "min": null,
                                    "max": null
                                },
                                "out": {
                                    "current": "19.497296",
                                    "unit": null,
                                    "warning": "62500000",
                                    "critical": "100000000",
                                    "min": "0",
                                    "max": "125000000"
                                },
                                "outucast": {
                                    "current": "0.049993",
                                    "unit": null,
                                    "warning": null,
                                    "critical": null,
                                    "min": null,
                                    "max": null
                                },
                                "outnucast": {
                                    "current": "0",
                                    "unit": null,
                                    "warning": null,
                                    "critical": null,
                                    "min": null,
                                    "max": null
                                },
                                "outdisc": {
                                    "current": "0",
                                    "unit": null,
                                    "warning": null,
                                    "critical": null,
                                    "min": null,
                                    "max": null
                                },
                                "outerr": {
                                    "current": "0",
                                    "unit": null,
                                    "warning": "0.01",
                                    "critical": "0.1",
                                    "min": null,
                                    "max": null
                                },
                                "outqlen": {
                                    "current": "0",
                                    "unit": null,
                                    "warning": null,
                                    "critical": null,
                                    "min": "0",
                                    "max": null
                                }
                            },
                            "perfdataArrayCounter": 11
                        }
                    },
                    "13": {
                        "Service": {
                            "id": 13,
                            "uuid": "22888362-4a9a-4a55-9e14-5fa98f4d9028",
                            "servicename": "Kernel Context Switches",
                            "hostname": "srvoitcvbox01.master.dns",
                            "description": null,
                            "active_checks_enabled": false,
                            "tags": null,
                            "host_id": 3,
                            "allow_edit": true,
                            "disabled": false
                        },
                        "Servicestatus": {
                            "currentState": 0,
                            "lastHardState": null,
                            "isFlapping": false,
                            "problemHasBeenAcknowledged": false,
                            "scheduledDowntimeDepth": 0,
                            "lastCheck": "45 seconds ago",
                            "nextCheck": "in 16 seconds",
                            "activeChecksEnabled": "0",
                            "lastHardStateChange": "5M 1D 1h 46m 25s",
                            "last_state_change": "5M 1D 1h 46m 25s",
                            "processPerformanceData": null,
                            "state_type": "1",
                            "acknowledgement_type": 0,
                            "flap_detection_enabled": null,
                            "notifications_enabled": null,
                            "current_check_attempt": "1",
                            "output": "OK - 10389\/s",
                            "long_output": null,
                            "perfdata": "ctxt=10389.166667;;;;",
                            "latency": null,
                            "max_check_attempts": 1,
                            "isHardstate": true,
                            "isInMonitoring": true,
                            "humanState": "ok",
                            "perfdataArray": {
                                "ctxt": {
                                    "current": "10389.166667",
                                    "unit": null,
                                    "warning": null,
                                    "critical": null,
                                    "min": null,
                                    "max": null
                                }
                            },
                            "perfdataArrayCounter": 1
                        }
                    },
                    "14": {
                        "Service": {
                            "id": 14,
                            "uuid": "d54d0df1-58a9-4ef1-bf70-b03ccda07ea9",
                            "servicename": "Kernel Major Page Faults",
                            "hostname": "srvoitcvbox01.master.dns",
                            "description": null,
                            "active_checks_enabled": false,
                            "tags": null,
                            "host_id": 3,
                            "allow_edit": true,
                            "disabled": false
                        },
                        "Servicestatus": {
                            "currentState": 0,
                            "lastHardState": null,
                            "isFlapping": false,
                            "problemHasBeenAcknowledged": false,
                            "scheduledDowntimeDepth": 0,
                            "lastCheck": "45 seconds ago",
                            "nextCheck": "in 45 seconds",
                            "activeChecksEnabled": "0",
                            "lastHardStateChange": "5M 1D 1h 46m 25s",
                            "last_state_change": "5M 1D 1h 46m 25s",
                            "processPerformanceData": null,
                            "state_type": "1",
                            "acknowledgement_type": 0,
                            "flap_detection_enabled": null,
                            "notifications_enabled": null,
                            "current_check_attempt": "1",
                            "output": "OK - 0\/s",
                            "long_output": null,
                            "perfdata": "pgmajfault=0;;;;",
                            "latency": null,
                            "max_check_attempts": 1,
                            "isHardstate": true,
                            "isInMonitoring": true,
                            "humanState": "ok",
                            "perfdataArray": {
                                "pgmajfault": {
                                    "current": "0",
                                    "unit": null,
                                    "warning": null,
                                    "critical": null,
                                    "min": null,
                                    "max": null
                                }
                            },
                            "perfdataArrayCounter": 1
                        }
                    },
                    "15": {
                        "Service": {
                            "id": 15,
                            "uuid": "2ef47a4e-c924-4b96-b21d-4d111dca9518",
                            "servicename": "Kernel Process Creations",
                            "hostname": "srvoitcvbox01.master.dns",
                            "description": null,
                            "active_checks_enabled": false,
                            "tags": null,
                            "host_id": 3,
                            "allow_edit": true,
                            "disabled": false
                        },
                        "Servicestatus": {
                            "currentState": 0,
                            "lastHardState": null,
                            "isFlapping": false,
                            "problemHasBeenAcknowledged": false,
                            "scheduledDowntimeDepth": 0,
                            "lastCheck": "45 seconds ago",
                            "nextCheck": "in 15 seconds",
                            "activeChecksEnabled": "0",
                            "lastHardStateChange": "5M 1D 1h 46m 25s",
                            "last_state_change": "5M 1D 1h 46m 25s",
                            "processPerformanceData": null,
                            "state_type": "1",
                            "acknowledgement_type": 0,
                            "flap_detection_enabled": null,
                            "notifications_enabled": null,
                            "current_check_attempt": "1",
                            "output": "OK - 421\/s",
                            "long_output": null,
                            "perfdata": "processes=421.166667;;;;",
                            "latency": null,
                            "max_check_attempts": 1,
                            "isHardstate": true,
                            "isInMonitoring": true,
                            "humanState": "ok",
                            "perfdataArray": {
                                "processes": {
                                    "current": "421.166667",
                                    "unit": null,
                                    "warning": null,
                                    "critical": null,
                                    "min": null,
                                    "max": null
                                }
                            },
                            "perfdataArrayCounter": 1
                        }
                    },
                    "16": {
                        "Service": {
                            "id": 16,
                            "uuid": "8a5fcacf-e8d0-4edd-a27b-4e9cc206bb66",
                            "servicename": "Memory used",
                            "hostname": "srvoitcvbox01.master.dns",
                            "description": null,
                            "active_checks_enabled": false,
                            "tags": null,
                            "host_id": 3,
                            "allow_edit": true,
                            "disabled": false
                        },
                        "Servicestatus": {
                            "currentState": 0,
                            "lastHardState": null,
                            "isFlapping": false,
                            "problemHasBeenAcknowledged": false,
                            "scheduledDowntimeDepth": 0,
                            "lastCheck": "45 seconds ago",
                            "nextCheck": "in 50 seconds",
                            "activeChecksEnabled": "0",
                            "lastHardStateChange": "5M 1D 1h 47m 25s",
                            "last_state_change": "5M 1D 1h 47m 25s",
                            "processPerformanceData": null,
                            "state_type": "1",
                            "acknowledgement_type": 0,
                            "flap_detection_enabled": null,
                            "notifications_enabled": null,
                            "current_check_attempt": "1",
                            "output": "OK - 5.24 GB used (5.21 RAM + 0.01 SWAP + 0.02 Pagetables, this is 26.7% of 19.61 RAM (8.00 total SWAP)), 4.6 mapped, 5.0 committed, 0.0 shared",
                            "long_output": null,
                            "perfdata": "ramused=5337MB;;;0;20081.6875 swapused=11MB;;;0;8191 memused=5368.22265625MB;30122;40163;0;28273.683594 mapped=4739MB;;;; committed_as=5152MB;;;; pagetables=18.81640625MB;;;; shared=0MB;;;;",
                            "latency": null,
                            "max_check_attempts": 1,
                            "isHardstate": true,
                            "isInMonitoring": true,
                            "humanState": "ok",
                            "perfdataArray": {
                                "ramused": {
                                    "current": "5337",
                                    "unit": "MB",
                                    "warning": null,
                                    "critical": null,
                                    "min": "0",
                                    "max": "20081.6875"
                                },
                                "swapused": {
                                    "current": "11",
                                    "unit": "MB",
                                    "warning": null,
                                    "critical": null,
                                    "min": "0",
                                    "max": "8191"
                                },
                                "memused": {
                                    "current": "5368.22265625",
                                    "unit": "MB",
                                    "warning": "30122",
                                    "critical": "40163",
                                    "min": "0",
                                    "max": "28273.683594"
                                },
                                "mapped": {
                                    "current": "4739",
                                    "unit": "MB",
                                    "warning": null,
                                    "critical": null,
                                    "min": null,
                                    "max": null
                                },
                                "committed_as": {
                                    "current": "5152",
                                    "unit": "MB",
                                    "warning": null,
                                    "critical": null,
                                    "min": null,
                                    "max": null
                                },
                                "pagetables": {
                                    "current": "18.81640625",
                                    "unit": "MB",
                                    "warning": null,
                                    "critical": null,
                                    "min": null,
                                    "max": null
                                },
                                "shared": {
                                    "current": "0",
                                    "unit": "MB",
                                    "warning": null,
                                    "critical": null,
                                    "min": null,
                                    "max": null
                                }
                            },
                            "perfdataArrayCounter": 7
                        }
                    },
                    "17": {
                        "Service": {
                            "id": 17,
                            "uuid": "83aa157b-dd3e-45ff-9194-baa985609261",
                            "servicename": "Mount options of \/",
                            "hostname": "srvoitcvbox01.master.dns",
                            "description": null,
                            "active_checks_enabled": false,
                            "tags": null,
                            "host_id": 3,
                            "allow_edit": true,
                            "disabled": false
                        },
                        "Servicestatus": {
                            "currentState": 1,
                            "lastHardState": null,
                            "isFlapping": false,
                            "problemHasBeenAcknowledged": false,
                            "scheduledDowntimeDepth": 0,
                            "lastCheck": "45 seconds ago",
                            "nextCheck": "in 33 seconds",
                            "activeChecksEnabled": "0",
                            "lastHardStateChange": "5M 1D 1h 47m 25s",
                            "last_state_change": "5M 1D 1h 47m 25s",
                            "processPerformanceData": null,
                            "state_type": "1",
                            "acknowledgement_type": 0,
                            "flap_detection_enabled": null,
                            "notifications_enabled": null,
                            "current_check_attempt": "1",
                            "output": "WARN - exceeding: relatime,errors=remount-ro,data=ordered",
                            "long_output": null,
                            "perfdata": "",
                            "latency": null,
                            "max_check_attempts": 1,
                            "isHardstate": true,
                            "isInMonitoring": true,
                            "humanState": "warning",
                            "perfdataArray": [],
                            "perfdataArrayCounter": 0
                        }
                    },
                    "18": {
                        "Service": {
                            "id": 18,
                            "uuid": "c6a827c6-d428-4bac-87eb-f2c1c4704fed",
                            "servicename": "Mount options of \/boot",
                            "hostname": "srvoitcvbox01.master.dns",
                            "description": null,
                            "active_checks_enabled": false,
                            "tags": null,
                            "host_id": 3,
                            "allow_edit": true,
                            "disabled": false
                        },
                        "Servicestatus": {
                            "currentState": 1,
                            "lastHardState": null,
                            "isFlapping": false,
                            "problemHasBeenAcknowledged": false,
                            "scheduledDowntimeDepth": 0,
                            "lastCheck": "45 seconds ago",
                            "nextCheck": "in 37 seconds",
                            "activeChecksEnabled": "0",
                            "lastHardStateChange": "5M 1D 1h 47m 25s",
                            "last_state_change": "5M 1D 1h 47m 25s",
                            "processPerformanceData": null,
                            "state_type": "1",
                            "acknowledgement_type": 0,
                            "flap_detection_enabled": null,
                            "notifications_enabled": null,
                            "current_check_attempt": "1",
                            "output": "WARN - exceeding: relatime,data=ordered",
                            "long_output": null,
                            "perfdata": "",
                            "latency": null,
                            "max_check_attempts": 1,
                            "isHardstate": true,
                            "isInMonitoring": true,
                            "humanState": "warning",
                            "perfdataArray": [],
                            "perfdataArrayCounter": 0
                        }
                    },
                    "19": {
                        "Service": {
                            "id": 19,
                            "uuid": "bae2ee57-3a85-4b15-aec2-03e73f1a79e2",
                            "servicename": "Mount options of \/vm\/ds01",
                            "hostname": "srvoitcvbox01.master.dns",
                            "description": null,
                            "active_checks_enabled": false,
                            "tags": null,
                            "host_id": 3,
                            "allow_edit": true,
                            "disabled": false
                        },
                        "Servicestatus": {
                            "currentState": 1,
                            "lastHardState": null,
                            "isFlapping": false,
                            "problemHasBeenAcknowledged": false,
                            "scheduledDowntimeDepth": 0,
                            "lastCheck": "45 seconds ago",
                            "nextCheck": "in 16 seconds",
                            "activeChecksEnabled": "0",
                            "lastHardStateChange": "5M 1D 1h 47m 25s",
                            "last_state_change": "5M 1D 1h 47m 25s",
                            "processPerformanceData": null,
                            "state_type": "1",
                            "acknowledgement_type": 0,
                            "flap_detection_enabled": null,
                            "notifications_enabled": null,
                            "current_check_attempt": "1",
                            "output": "WARN - exceeding: relatime,attr2,noquota",
                            "long_output": null,
                            "perfdata": "",
                            "latency": null,
                            "max_check_attempts": 1,
                            "isHardstate": true,
                            "isInMonitoring": true,
                            "humanState": "warning",
                            "perfdataArray": [],
                            "perfdataArrayCounter": 0
                        }
                    },
                    "20": {
                        "Service": {
                            "id": 20,
                            "uuid": "22056abc-0f57-4ff1-a2bb-0079f2b56a75",
                            "servicename": "NTP Time",
                            "hostname": "srvoitcvbox01.master.dns",
                            "description": null,
                            "active_checks_enabled": false,
                            "tags": null,
                            "host_id": 3,
                            "allow_edit": true,
                            "disabled": false
                        },
                        "Servicestatus": {
                            "currentState": 2,
                            "lastHardState": null,
                            "isFlapping": false,
                            "problemHasBeenAcknowledged": false,
                            "scheduledDowntimeDepth": 0,
                            "lastCheck": "45 seconds ago",
                            "nextCheck": "in 33 seconds",
                            "activeChecksEnabled": "0",
                            "lastHardStateChange": "5M 1D 1h 47m 25s",
                            "last_state_change": "5M 1D 1h 47m 25s",
                            "processPerformanceData": null,
                            "state_type": "1",
                            "acknowledgement_type": 0,
                            "flap_detection_enabled": null,
                            "notifications_enabled": null,
                            "current_check_attempt": "1",
                            "output": "CRIT - critical offset sys.peer - stratum 4, offset 35.69 ms, jitter 30.07 ms, last reached 49 secs ago (synchronized on srvdc02.master.)",
                            "long_output": null,
                            "perfdata": "offset=35.693;5;10;0; jitter=30.067;5;10;0;",
                            "latency": null,
                            "max_check_attempts": 1,
                            "isHardstate": true,
                            "isInMonitoring": true,
                            "humanState": "critical",
                            "perfdataArray": {
                                "offset": {
                                    "current": "35.693",
                                    "unit": null,
                                    "warning": "5",
                                    "critical": "10",
                                    "min": "0",
                                    "max": null
                                },
                                "jitter": {
                                    "current": "30.067",
                                    "unit": null,
                                    "warning": "5",
                                    "critical": "10",
                                    "min": "0",
                                    "max": null
                                }
                            },
                            "perfdataArrayCounter": 2
                        }
                    },
                    "21": {
                        "Service": {
                            "id": 21,
                            "uuid": "a119fbd5-b062-4e81-b474-4e07e91f09cc",
                            "servicename": "Number of threads",
                            "hostname": "srvoitcvbox01.master.dns",
                            "description": null,
                            "active_checks_enabled": false,
                            "tags": null,
                            "host_id": 3,
                            "allow_edit": true,
                            "disabled": false
                        },
                        "Servicestatus": {
                            "currentState": 0,
                            "lastHardState": null,
                            "isFlapping": false,
                            "problemHasBeenAcknowledged": false,
                            "scheduledDowntimeDepth": 0,
                            "lastCheck": "45 seconds ago",
                            "nextCheck": "in 33 seconds",
                            "activeChecksEnabled": "0",
                            "lastHardStateChange": "5M 1D 1h 47m 25s",
                            "last_state_change": "5M 1D 1h 47m 25s",
                            "processPerformanceData": null,
                            "state_type": "1",
                            "acknowledgement_type": 0,
                            "flap_detection_enabled": null,
                            "notifications_enabled": null,
                            "current_check_attempt": "1",
                            "output": "OK - 366 threads",
                            "long_output": null,
                            "perfdata": "threads=366;2000;4000;0;",
                            "latency": null,
                            "max_check_attempts": 1,
                            "isHardstate": true,
                            "isInMonitoring": true,
                            "humanState": "ok",
                            "perfdataArray": {
                                "threads": {
                                    "current": "366",
                                    "unit": null,
                                    "warning": "2000",
                                    "critical": "4000",
                                    "min": "0",
                                    "max": null
                                }
                            },
                            "perfdataArrayCounter": 1
                        }
                    },
                    "22": {
                        "Service": {
                            "id": 22,
                            "uuid": "58b8285b-e290-4c2d-9ee4-6db8205d9cfd",
                            "servicename": "TCP Connections",
                            "hostname": "srvoitcvbox01.master.dns",
                            "description": null,
                            "active_checks_enabled": false,
                            "tags": null,
                            "host_id": 3,
                            "allow_edit": true,
                            "disabled": false
                        },
                        "Servicestatus": {
                            "currentState": 0,
                            "lastHardState": null,
                            "isFlapping": false,
                            "problemHasBeenAcknowledged": false,
                            "scheduledDowntimeDepth": 0,
                            "lastCheck": "45 seconds ago",
                            "nextCheck": "in 23 seconds",
                            "activeChecksEnabled": "0",
                            "lastHardStateChange": "5M 1D 1h 47m 25s",
                            "last_state_change": "5M 1D 1h 47m 25s",
                            "processPerformanceData": null,
                            "state_type": "1",
                            "acknowledgement_type": 0,
                            "flap_detection_enabled": null,
                            "notifications_enabled": null,
                            "current_check_attempt": "1",
                            "output": "OK - ESTABLISHED: 4, TIME_WAIT: 3",
                            "long_output": null,
                            "perfdata": "ESTABLISHED=4;100;200;; SYN_SENT=0;;;; SYN_RECV=0;;;; LAST_ACK=0;;;; CLOSE_WAIT=0;;;; TIME_WAIT=3;170;200;; CLOSED=0;;;; CLOSING=0;;;; FIN_WAIT1=0;;;; FIN_WAIT2=0;;;; BOUND=0;;;;",
                            "latency": null,
                            "max_check_attempts": 1,
                            "isHardstate": true,
                            "isInMonitoring": true,
                            "humanState": "ok",
                            "perfdataArray": {
                                "ESTABLISHED": {
                                    "current": "4",
                                    "unit": null,
                                    "warning": "100",
                                    "critical": "200",
                                    "min": null,
                                    "max": null
                                },
                                "SYN_SENT": {
                                    "current": "0",
                                    "unit": null,
                                    "warning": null,
                                    "critical": null,
                                    "min": null,
                                    "max": null
                                },
                                "SYN_RECV": {
                                    "current": "0",
                                    "unit": null,
                                    "warning": null,
                                    "critical": null,
                                    "min": null,
                                    "max": null
                                },
                                "LAST_ACK": {
                                    "current": "0",
                                    "unit": null,
                                    "warning": null,
                                    "critical": null,
                                    "min": null,
                                    "max": null
                                },
                                "CLOSE_WAIT": {
                                    "current": "0",
                                    "unit": null,
                                    "warning": null,
                                    "critical": null,
                                    "min": null,
                                    "max": null
                                },
                                "TIME_WAIT": {
                                    "current": "3",
                                    "unit": null,
                                    "warning": "170",
                                    "critical": "200",
                                    "min": null,
                                    "max": null
                                },
                                "CLOSED": {
                                    "current": "0",
                                    "unit": null,
                                    "warning": null,
                                    "critical": null,
                                    "min": null,
                                    "max": null
                                },
                                "CLOSING": {
                                    "current": "0",
                                    "unit": null,
                                    "warning": null,
                                    "critical": null,
                                    "min": null,
                                    "max": null
                                },
                                "FIN_WAIT1": {
                                    "current": "0",
                                    "unit": null,
                                    "warning": null,
                                    "critical": null,
                                    "min": null,
                                    "max": null
                                },
                                "FIN_WAIT2": {
                                    "current": "0",
                                    "unit": null,
                                    "warning": null,
                                    "critical": null,
                                    "min": null,
                                    "max": null
                                },
                                "BOUND": {
                                    "current": "0",
                                    "unit": null,
                                    "warning": null,
                                    "critical": null,
                                    "min": null,
                                    "max": null
                                }
                            },
                            "perfdataArrayCounter": 11
                        }
                    },
                    "23": {
                        "Service": {
                            "id": 23,
                            "uuid": "393aecea-0261-4498-b0e5-2641b6677926",
                            "servicename": "Uptime",
                            "hostname": "srvoitcvbox01.master.dns",
                            "description": null,
                            "active_checks_enabled": false,
                            "tags": null,
                            "host_id": 3,
                            "allow_edit": true,
                            "disabled": false
                        },
                        "Servicestatus": {
                            "currentState": 0,
                            "lastHardState": null,
                            "isFlapping": false,
                            "problemHasBeenAcknowledged": false,
                            "scheduledDowntimeDepth": 0,
                            "lastCheck": "45 seconds ago",
                            "nextCheck": "in 33 seconds",
                            "activeChecksEnabled": "0",
                            "lastHardStateChange": "5M 1D 1h 47m 25s",
                            "last_state_change": "5M 1D 1h 47m 25s",
                            "processPerformanceData": null,
                            "state_type": "1",
                            "acknowledgement_type": 0,
                            "flap_detection_enabled": null,
                            "notifications_enabled": null,
                            "current_check_attempt": "1",
                            "output": "OK - up since Mon Jun 16 09:04:57 2014 (1862d 02:53:27)",
                            "long_output": null,
                            "perfdata": "uptime=160887207.19;;;;",
                            "latency": null,
                            "max_check_attempts": 1,
                            "isHardstate": true,
                            "isInMonitoring": true,
                            "humanState": "ok",
                            "perfdataArray": {
                                "uptime": {
                                    "current": "160887207.19",
                                    "unit": null,
                                    "warning": null,
                                    "critical": null,
                                    "min": null,
                                    "max": null
                                }
                            },
                            "perfdataArrayCounter": 1
                        }
                    }
                }
            },
            "2": {
                "Host": {
                    "id": 2,
                    "uuid": "0592d268-cf71-473d-982f-b1d0e19ed27b",
                    "hostname": "test host check",
                    "address": "127.1.2.3",
                    "description": "test host check",
                    "active_checks_enabled": null,
                    "satelliteId": null,
                    "containerId": null,
                    "containerIds": null,
                    "tags": null,
                    "allow_edit": true,
                    "disabled": false,
                    "is_satellite_host": false
                },
                "Hoststatus": {
                    "currentState": 0,
                    "isFlapping": false,
                    "problemHasBeenAcknowledged": false,
                    "scheduledDowntimeDepth": "1",
                    "lastCheck": "on 1\/1\/70",
                    "nextCheck": "on 1\/1\/70",
                    "activeChecksEnabled": null,
                    "lastHardStateChange": "5M 0D 20h 51m 41s",
                    "last_state_change": "49Y 6M 21D 9h 59m 9s",
                    "output": "EVC Check",
                    "long_output": null,
                    "acknowledgement_type": null,
                    "state_type": "1",
                    "flap_detection_enabled": null,
                    "notifications_enabled": null,
                    "current_check_attempt": "1",
                    "max_check_attempts": "5",
                    "latency": null,
                    "isHardstate": true,
                    "isInMonitoring": true,
                    "humanState": "unreachable"
                },
                "Services": {
                    "5": {
                        "Service": {
                            "id": 5,
                            "uuid": "b62123b1-c5e2-430f-ae12-dfc70b8bd3ca",
                            "servicename": "ebene1",
                            "hostname": "test host check",
                            "description": null,
                            "active_checks_enabled": false,
                            "tags": null,
                            "host_id": 2,
                            "allow_edit": true,
                            "disabled": false
                        },
                        "Servicestatus": {
                            "currentState": 1,
                            "lastHardState": null,
                            "isFlapping": false,
                            "problemHasBeenAcknowledged": false,
                            "scheduledDowntimeDepth": 1,
                            "lastCheck": "4 minutes ago",
                            "nextCheck": "in 3 minutes",
                            "activeChecksEnabled": "0",
                            "lastHardStateChange": "13D 20h 45m 57s",
                            "last_state_change": "13D 21h 0m 57s",
                            "processPerformanceData": null,
                            "state_type": "1",
                            "acknowledgement_type": 0,
                            "flap_detection_enabled": null,
                            "notifications_enabled": null,
                            "current_check_attempt": "4",
                            "output": "AND - 2 services (2 from 3 services are OK)",
                            "long_output": null,
                            "perfdata": "state=1;1;2;0;3",
                            "latency": null,
                            "max_check_attempts": 4,
                            "isHardstate": true,
                            "isInMonitoring": true,
                            "humanState": "warning",
                            "perfdataArray": {
                                "state": {
                                    "current": "1",
                                    "unit": null,
                                    "warning": "1",
                                    "critical": "2",
                                    "min": "0",
                                    "max": "3"
                                }
                            },
                            "perfdataArrayCounter": 1
                        }
                    }
                }
            }
        };

        $scope.createCurrentStateReport = function(){
            var hasBeenAcknowledged = '';
            var inDowntime = '';
            if($scope.filter.Servicestatus.acknowledged ^ $scope.filter.Servicestatus.not_acknowledged){
                hasBeenAcknowledged = $scope.filter.Servicestatus.acknowledged === true;
            }
            if($scope.filter.Servicestatus.in_downtime ^ $scope.filter.Servicestatus.not_in_downtime){
                inDowntime = $scope.filter.Servicestatus.in_downtime === true;
            }

            var passive = '';
            if($scope.filter.Servicestatus.passive ^ $scope.filter.Servicestatus.active){
                passive = !$scope.filter.Servicestatus.passive;
            }

            var params = {
                'angular': true,
                'filter[Servicestatus.current_state][]': $rootScope.currentStateForApi($scope.filter.Servicestatus.current_state),
                'filter[Services.id][]': $scope.post.Currentstatereport.Service,
                'filter[Servicestatus.problem_has_been_acknowledged]': hasBeenAcknowledged,
                'filter[Servicestatus.scheduled_downtime_depth]': inDowntime,
                'filter[Servicestatus.active_checks_enabled]': passive
            };

            $http.get("/currentstatereports/index.json", {
                params: params
            }).then(function(result){
                $scope.servicestatus = result.data.all_services;
                NotyService.genericSuccess();

                if($scope.post.Currentstatereport.report_format === 'pdf'){
                    window.location = '/currentstatereports/createPdfReport.pdf';
                }

                if($scope.post.Currentstatereport.report_format === 'html'){
                    window.location = '/currentstatereports/createHtmlReport';
                }

                $scope.errors = null;
            }, function errorCallback(result){
                NotyService.genericError();
                if(result.data.hasOwnProperty('error')){
                    $scope.errors = result.data.error;
                }
            });

        };

        $scope.loadServices = function(searchString){
            $http.get("/services/loadServicesByString.json", {
                params: {
                    'angular': true,
                    'filter[Host.name]': searchString,
                    'filter[Service.servicename]': searchString,
                    'selected[]': $scope.post.Currentstatereport.Service
                }
            }).then(function(result){
                $scope.services = result.data.services;
            });

        };

        $scope.loadServices();

        $scope.createBackgroundForPerfdataMeter = function(attributes){
            var background = {
                'background': 'none'
            };

            if(!(attributes.min && attributes.current && attributes.warning && attributes.critical && attributes.min && attributes.max)){
                return background;
            }
            var linearGradientArray = ['to right'];
            var start = (attributes.min !== null) ? attributes.min : 0;
            var end = (attributes.max !== null) ? attributes.max : (attributes.critical != null) ? attributes.critical : 0;
            var currentValue = Number(attributes.current);
            var warningValue = Number(attributes.warning);
            var criticalValue = Number(attributes.critical);

            //if warning value < critical value, inverse
            if(!isNaN(warningValue) && !isNaN(criticalValue) && warningValue < criticalValue){
                var curValPosInPercent = currentValue / (end - start) * 100;
                curValPosInPercent = (curValPosInPercent > 100) ? 100 : curValPosInPercent;
                if((!isNaN(warningValue) && currentValue >= warningValue) &&
                    (!isNaN(criticalValue) && currentValue < criticalValue)
                ){
                    //if current state > warning and current state < critical
                    linearGradientArray.push(
                        '#5CB85C 0%',
                        '#F0AD4E ' + curValPosInPercent + '%'
                    );
                }else if((!isNaN(warningValue) && currentValue > warningValue) &&
                    (!isNaN(criticalValue) && currentValue >= criticalValue)
                ){
                    //if current state > warning and current state > critical
                    linearGradientArray.push(
                        '#5CB85C 0%',
                        '#F0AD4E ' + (warningValue / (end - start) * 100) + '%',
                        '#D9534F ' + curValPosInPercent + '%'
                    );
                }else if(currentValue < warningValue){
                    linearGradientArray.push('#5CB85C ' + curValPosInPercent + '%');
                }
                //set white color for gradient for empty area
                if(curValPosInPercent > 0 && curValPosInPercent < 100){
                    linearGradientArray.push('transparent ' + curValPosInPercent + '%');
                }
            }
            return {
                'background': 'linear-gradient(' + linearGradientArray.join(', ') + ')'
            };
        }
    });
