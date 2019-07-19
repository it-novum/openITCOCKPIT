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
                        "scheduledDowntimeDepth": "0",
                        "lastCheck": "on 1\/1\/70",
                        "nextCheck": "on 1\/1\/70",
                        "activeChecksEnabled": null,
                        "lastHardStateChange": "7M 1D 23h 21m 22s",
                        "last_state_change": "49Y 6M 18D 9h 33m 22s",
                        "output": "OK - 127.0.0.1: rta 0,034ms, lost 0%",
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
                                "scheduledDowntimeDepth": 0,
                                "lastCheck": "on 13\/6\/19",
                                "nextCheck": "in 23 minutes",
                                "activeChecksEnabled": "1",
                                "lastHardStateChange": "1M 25D 18h 35m 31s",
                                "last_state_change": "1M 25D 18h 35m 31s",
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
                                }
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
                                "scheduledDowntimeDepth": 0,
                                "lastCheck": "1 minute ago",
                                "nextCheck": "in 3 minutes",
                                "activeChecksEnabled": "1",
                                "lastHardStateChange": "7M 1D 18h 15m 35s",
                                "last_state_change": "7M 1D 18h 15m 35s",
                                "processPerformanceData": null,
                                "state_type": "1",
                                "acknowledgement_type": 0,
                                "flap_detection_enabled": null,
                                "notifications_enabled": null,
                                "current_check_attempt": "1",
                                "output": "OK - load average: 0.32, 0.16, 0.11",
                                "long_output": null,
                                "perfdata": "load1=0.320;7.000;10.000;0; load5=0.160;6.000;7.000;0; load15=0.110;5.000;6.000;0;",
                                "latency": null,
                                "max_check_attempts": 3,
                                "isHardstate": true,
                                "isInMonitoring": true,
                                "humanState": "ok",
                                "perfdataArray": {
                                    "load1": {
                                        "current": "0.320",
                                        "unit": null,
                                        "warning": "7.000",
                                        "critical": "10.000",
                                        "min": "0",
                                        "max": null
                                    },
                                    "load5": {
                                        "current": "0.160",
                                        "unit": null,
                                        "warning": "6.000",
                                        "critical": "7.000",
                                        "min": "0",
                                        "max": null
                                    },
                                    "load15": {
                                        "current": "0.110",
                                        "unit": null,
                                        "warning": "5.000",
                                        "critical": "6.000",
                                        "min": "0",
                                        "max": null
                                    }
                                }
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
                                "currentState": 1,
                                "lastHardState": null,
                                "isFlapping": false,
                                "problemHasBeenAcknowledged": false,
                                "scheduledDowntimeDepth": 0,
                                "lastCheck": "2 seconds ago",
                                "nextCheck": "in 4 minutes",
                                "activeChecksEnabled": "1",
                                "lastHardStateChange": "10D 20h 36m 49s",
                                "last_state_change": "10D 20h 38m 49s",
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
                                "humanState": "warning",
                                "perfdataArray": {
                                    "users": {
                                        "current": "4",
                                        "unit": null,
                                        "warning": "3",
                                        "critical": "7",
                                        "min": "0",
                                        "max": null
                                    }
                                }
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
                                "scheduledDowntimeDepth": 0,
                                "lastCheck": "3 minutes ago",
                                "nextCheck": "in 1 minute",
                                "activeChecksEnabled": "1",
                                "lastHardStateChange": "7M 1D 23h 21m 0s",
                                "last_state_change": "7M 1D 23h 21m 0s",
                                "processPerformanceData": null,
                                "state_type": "1",
                                "acknowledgement_type": 0,
                                "flap_detection_enabled": null,
                                "notifications_enabled": null,
                                "current_check_attempt": "1",
                                "output": "PING OK - Packet loss = 0%, RTA = 0.06 ms",
                                "long_output": null,
                                "perfdata": "rta=0.056000ms;100.000000;500.000000;0.000000 pl=0%;20;60;0",
                                "latency": null,
                                "max_check_attempts": 3,
                                "isHardstate": true,
                                "isInMonitoring": true,
                                "humanState": "ok",
                                "perfdataArray": {
                                    "rta": {
                                        "current": "0.056000",
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
                                }
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
                        "lastHardStateChange": "3M 3D 1h 28m 25s",
                        "last_state_change": "49Y 6M 18D 9h 33m 22s",
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
                                "lastCheck": "4 minutes ago",
                                "nextCheck": "in 11 seconds",
                                "activeChecksEnabled": "1",
                                "lastHardStateChange": "1M 4D 0h 10m 21s",
                                "last_state_change": "1M 4D 0h 10m 21s",
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
                                "perfdataArray": []
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
                        "lastHardStateChange": "4M 28D 18h 45m 53s",
                        "last_state_change": "49Y 6M 18D 9h 33m 22s",
                        "output": "OK - 127.0.0.1: rta 0,087ms, lost 0%",
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
                                "lastCheck": "44 seconds ago",
                                "nextCheck": "in 4 minutes",
                                "activeChecksEnabled": "1",
                                "lastHardStateChange": "1M 5D 0h 58m 55s",
                                "last_state_change": "1M 5D 0h 58m 55s",
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
                                }
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
                        "lastHardStateChange": "2M 13D 19h 39m 2s",
                        "last_state_change": "49Y 6M 18D 9h 33m 22s",
                        "output": "OK - 172.16.2.2: rta 0,254ms, lost 0%",
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
                                "lastCheck": "58 seconds ago",
                                "nextCheck": "in 2 seconds",
                                "activeChecksEnabled": "1",
                                "lastHardStateChange": "2M 13D 19h 39m 2s",
                                "last_state_change": "2M 13D 19h 39m 2s",
                                "processPerformanceData": null,
                                "state_type": "1",
                                "acknowledgement_type": 0,
                                "flap_detection_enabled": null,
                                "notifications_enabled": null,
                                "current_check_attempt": "1",
                                "output": "OK - Agent version 1.2.2p1, execution time 0.1 sec",
                                "long_output": null,
                                "perfdata": "execution_time=0.105 user_time=0.050 system_time=0.000 children_user_time=0.000 children_system_time=0.000",
                                "latency": null,
                                "max_check_attempts": 1,
                                "isHardstate": true,
                                "isInMonitoring": true,
                                "humanState": "ok",
                                "perfdataArray": {
                                    "execution_time": {
                                        "current": "0.105",
                                        "unit": null,
                                        "warning": null,
                                        "critical": null,
                                        "min": null,
                                        "max": null
                                    },
                                    "user_time": {
                                        "current": "0.050",
                                        "unit": null,
                                        "warning": null,
                                        "critical": null,
                                        "min": null,
                                        "max": null
                                    },
                                    "system_time": {
                                        "current": "0.000",
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
                                }
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
                                "lastCheck": "58 seconds ago",
                                "nextCheck": "in 2 seconds",
                                "activeChecksEnabled": "0",
                                "lastHardStateChange": "4M 29D 1h 21m 38s",
                                "last_state_change": "4M 29D 1h 21m 38s",
                                "processPerformanceData": null,
                                "state_type": "1",
                                "acknowledgement_type": 0,
                                "flap_detection_enabled": null,
                                "notifications_enabled": null,
                                "current_check_attempt": "1",
                                "output": "OK - 15min load 0.80 at 4 CPUs",
                                "long_output": null,
                                "perfdata": "load1=0.69;;;0;4 load5=0.91;;;0;4 load15=0.8;;;0;4",
                                "latency": null,
                                "max_check_attempts": 1,
                                "isHardstate": true,
                                "isInMonitoring": true,
                                "humanState": "ok",
                                "perfdataArray": {
                                    "load1": {
                                        "current": "0.69",
                                        "unit": null,
                                        "warning": null,
                                        "critical": null,
                                        "min": "0",
                                        "max": "4"
                                    },
                                    "load5": {
                                        "current": "0.91",
                                        "unit": null,
                                        "warning": null,
                                        "critical": null,
                                        "min": "0",
                                        "max": "4"
                                    },
                                    "load15": {
                                        "current": "0.8",
                                        "unit": null,
                                        "warning": null,
                                        "critical": null,
                                        "min": "0",
                                        "max": "4"
                                    }
                                }
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
                                "lastCheck": "58 seconds ago",
                                "nextCheck": "in 8 seconds",
                                "activeChecksEnabled": "0",
                                "lastHardStateChange": "4M 29D 1h 21m 38s",
                                "last_state_change": "4M 29D 1h 21m 38s",
                                "processPerformanceData": null,
                                "state_type": "1",
                                "acknowledgement_type": 0,
                                "flap_detection_enabled": null,
                                "notifications_enabled": null,
                                "current_check_attempt": "1",
                                "output": "OK - user: 3.9%, system: 10.6%, wait: 0.2%, total: 14.7%",
                                "long_output": null,
                                "perfdata": "user=3.851;;;; system=10.634;;;; wait=0.247;;;;",
                                "latency": null,
                                "max_check_attempts": 1,
                                "isHardstate": true,
                                "isInMonitoring": true,
                                "humanState": "ok",
                                "perfdataArray": {
                                    "user": {
                                        "current": "3.851",
                                        "unit": null,
                                        "warning": null,
                                        "critical": null,
                                        "min": null,
                                        "max": null
                                    },
                                    "system": {
                                        "current": "10.634",
                                        "unit": null,
                                        "warning": null,
                                        "critical": null,
                                        "min": null,
                                        "max": null
                                    },
                                    "wait": {
                                        "current": "0.247",
                                        "unit": null,
                                        "warning": null,
                                        "critical": null,
                                        "min": null,
                                        "max": null
                                    }
                                }
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
                                "lastCheck": "58 seconds ago",
                                "nextCheck": "in 2 seconds",
                                "activeChecksEnabled": "0",
                                "lastHardStateChange": "12D 5h 46m 26s",
                                "last_state_change": "12D 5h 46m 26s",
                                "processPerformanceData": null,
                                "state_type": "1",
                                "acknowledgement_type": 0,
                                "flap_detection_enabled": null,
                                "notifications_enabled": null,
                                "current_check_attempt": "1",
                                "output": "OK - 452.27 B\/sec read, 137.82 kB\/sec write, IOs: 15.77\/sec",
                                "long_output": null,
                                "perfdata": "read=452.266667;10;20;; write=141124.266667;10;40;; read.avg=600.83258;;;; write.avg=157523.631429;;;;",
                                "latency": null,
                                "max_check_attempts": 1,
                                "isHardstate": true,
                                "isInMonitoring": true,
                                "humanState": "ok",
                                "perfdataArray": {
                                    "read": {
                                        "current": "452.266667",
                                        "unit": null,
                                        "warning": "10",
                                        "critical": "20",
                                        "min": null,
                                        "max": null
                                    },
                                    "write": {
                                        "current": "141124.266667",
                                        "unit": null,
                                        "warning": "10",
                                        "critical": "40",
                                        "min": null,
                                        "max": null
                                    },
                                    "read.avg": {
                                        "current": "600.83258",
                                        "unit": null,
                                        "warning": null,
                                        "critical": null,
                                        "min": null,
                                        "max": null
                                    },
                                    "write.avg": {
                                        "current": "157523.631429",
                                        "unit": null,
                                        "warning": null,
                                        "critical": null,
                                        "min": null,
                                        "max": null
                                    }
                                }
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
                                "lastCheck": "58 seconds ago",
                                "nextCheck": "in 19 seconds",
                                "activeChecksEnabled": "0",
                                "lastHardStateChange": "4M 29D 1h 21m 38s",
                                "last_state_change": "4M 29D 1h 21m 38s",
                                "processPerformanceData": null,
                                "state_type": "1",
                                "acknowledgement_type": 0,
                                "flap_detection_enabled": null,
                                "notifications_enabled": null,
                                "current_check_attempt": "1",
                                "output": "OK - 49.2% used (9.03 of 18.33 GB), (levels at 80.00\/90.00%), trend: -30.22 MB \/ 24 hours",
                                "long_output": null,
                                "perfdata": "\/=9244.72265625MB;15018.165625;16895.436328;0;18772.707031 growth=168.750728;;;; trend=-30.220869;;;0;782.196126",
                                "latency": null,
                                "max_check_attempts": 1,
                                "isHardstate": true,
                                "isInMonitoring": true,
                                "humanState": "ok",
                                "perfdataArray": {
                                    "\/": {
                                        "current": "9244.72265625",
                                        "unit": "MB",
                                        "warning": "15018.165625",
                                        "critical": "16895.436328",
                                        "min": "0",
                                        "max": "18772.707031"
                                    },
                                    "growth": {
                                        "current": "168.750728",
                                        "unit": null,
                                        "warning": null,
                                        "critical": null,
                                        "min": null,
                                        "max": null
                                    },
                                    "trend": {
                                        "current": "-30.220869",
                                        "unit": null,
                                        "warning": null,
                                        "critical": null,
                                        "min": "0",
                                        "max": "782.196126"
                                    }
                                }
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
                                "lastCheck": "58 seconds ago",
                                "nextCheck": "in 19 seconds",
                                "activeChecksEnabled": "0",
                                "lastHardStateChange": "4M 29D 1h 21m 38s",
                                "last_state_change": "4M 29D 1h 21m 38s",
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
                                }
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
                                "lastCheck": "58 seconds ago",
                                "nextCheck": "in 2 seconds",
                                "activeChecksEnabled": "0",
                                "lastHardStateChange": "4M 29D 1h 21m 38s",
                                "last_state_change": "4M 29D 1h 21m 38s",
                                "processPerformanceData": null,
                                "state_type": "1",
                                "acknowledgement_type": 0,
                                "flap_detection_enabled": null,
                                "notifications_enabled": null,
                                "current_check_attempt": "1",
                                "output": "OK - 43.0% used (188.39 of 437.79 GB), (levels at 80.00\/90.00%), trend: +954.82 kB \/ 24 hours",
                                "long_output": null,
                                "perfdata": "\/vm\/ds01=192913.175781MB;358634.4;403463.7;0;448293 growth=0;;;; trend=0.932437;;;0;18678.875",
                                "latency": null,
                                "max_check_attempts": 1,
                                "isHardstate": true,
                                "isInMonitoring": true,
                                "humanState": "ok",
                                "perfdataArray": {
                                    "\/vm\/ds01": {
                                        "current": "192913.175781",
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
                                        "current": "0.932437",
                                        "unit": null,
                                        "warning": null,
                                        "critical": null,
                                        "min": "0",
                                        "max": "18678.875"
                                    }
                                }
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
                                "lastCheck": "58 seconds ago",
                                "nextCheck": "in 20 seconds",
                                "activeChecksEnabled": "0",
                                "lastHardStateChange": "4M 29D 1h 20m 38s",
                                "last_state_change": "4M 29D 1h 20m 38s",
                                "processPerformanceData": null,
                                "state_type": "1",
                                "acknowledgement_type": 0,
                                "flap_detection_enabled": null,
                                "notifications_enabled": null,
                                "current_check_attempt": "1",
                                "output": "OK - [vlan19] (up) 1 GBit\/s, in: 0.77 B\/s, out: 0.00 B\/s",
                                "long_output": null,
                                "perfdata": "in=0.76667;62500000;100000000;0;125000000 inucast=0.016667;;;; innucast=0;;;; indisc=0;;;; inerr=0;0.01;0.1;; out=0;62500000;100000000;0;125000000 outucast=0;;;; outnucast=0;;;; outdisc=0;;;; outerr=0;0.01;0.1;; outqlen=0;;;0;",
                                "latency": null,
                                "max_check_attempts": 1,
                                "isHardstate": true,
                                "isInMonitoring": true,
                                "humanState": "ok",
                                "perfdataArray": {
                                    "in": {
                                        "current": "0.76667",
                                        "unit": null,
                                        "warning": "62500000",
                                        "critical": "100000000",
                                        "min": "0",
                                        "max": "125000000"
                                    },
                                    "inucast": {
                                        "current": "0.016667",
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
                                }
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
                                "lastCheck": "58 seconds ago",
                                "nextCheck": "in 2 seconds",
                                "activeChecksEnabled": "0",
                                "lastHardStateChange": "4M 29D 1h 20m 38s",
                                "last_state_change": "4M 29D 1h 20m 38s",
                                "processPerformanceData": null,
                                "state_type": "1",
                                "acknowledgement_type": 0,
                                "flap_detection_enabled": null,
                                "notifications_enabled": null,
                                "current_check_attempt": "1",
                                "output": "OK - [eth0] (up) 1 GBit\/s, in: 171.82 kB\/s, out: 191.71 kB\/s",
                                "long_output": null,
                                "perfdata": "in=175940.809163;62500000;100000000;0;125000000 inucast=937.42009;;;; innucast=1.533339;;;; indisc=0;;;; inerr=0;0.01;0.1;; out=196308.716876;62500000;100000000;0;125000000 outucast=860.186475;;;; outnucast=0;;;; outdisc=0;;;; outerr=0;0.01;0.1;; outqlen=0;;;0;",
                                "latency": null,
                                "max_check_attempts": 1,
                                "isHardstate": true,
                                "isInMonitoring": true,
                                "humanState": "ok",
                                "perfdataArray": {
                                    "in": {
                                        "current": "175940.809163",
                                        "unit": null,
                                        "warning": "62500000",
                                        "critical": "100000000",
                                        "min": "0",
                                        "max": "125000000"
                                    },
                                    "inucast": {
                                        "current": "937.42009",
                                        "unit": null,
                                        "warning": null,
                                        "critical": null,
                                        "min": null,
                                        "max": null
                                    },
                                    "innucast": {
                                        "current": "1.533339",
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
                                        "current": "196308.716876",
                                        "unit": null,
                                        "warning": "62500000",
                                        "critical": "100000000",
                                        "min": "0",
                                        "max": "125000000"
                                    },
                                    "outucast": {
                                        "current": "860.186475",
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
                                }
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
                                "lastCheck": "58 seconds ago",
                                "nextCheck": "in 39 seconds",
                                "activeChecksEnabled": "0",
                                "lastHardStateChange": "4M 29D 1h 20m 38s",
                                "last_state_change": "4M 29D 1h 20m 38s",
                                "processPerformanceData": null,
                                "state_type": "1",
                                "acknowledgement_type": 0,
                                "flap_detection_enabled": null,
                                "notifications_enabled": null,
                                "current_check_attempt": "1",
                                "output": "OK - [eth1] (up) 1 GBit\/s, in: 60.07 B\/s, out: 20.13 B\/s",
                                "long_output": null,
                                "perfdata": "in=60.06685;62500000;100000000;0;125000000 inucast=1.366671;;;; innucast=0.533335;;;; indisc=0;;;; inerr=0;0.01;0.1;; out=20.133395;62500000;100000000;0;125000000 outucast=0.1;;;; outnucast=0;;;; outdisc=0;;;; outerr=0;0.01;0.1;; outqlen=0;;;0;",
                                "latency": null,
                                "max_check_attempts": 1,
                                "isHardstate": true,
                                "isInMonitoring": true,
                                "humanState": "ok",
                                "perfdataArray": {
                                    "in": {
                                        "current": "60.06685",
                                        "unit": null,
                                        "warning": "62500000",
                                        "critical": "100000000",
                                        "min": "0",
                                        "max": "125000000"
                                    },
                                    "inucast": {
                                        "current": "1.366671",
                                        "unit": null,
                                        "warning": null,
                                        "critical": null,
                                        "min": null,
                                        "max": null
                                    },
                                    "innucast": {
                                        "current": "0.533335",
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
                                        "current": "20.133395",
                                        "unit": null,
                                        "warning": "62500000",
                                        "critical": "100000000",
                                        "min": "0",
                                        "max": "125000000"
                                    },
                                    "outucast": {
                                        "current": "0.1",
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
                                }
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
                                "lastCheck": "58 seconds ago",
                                "nextCheck": "in 2 seconds",
                                "activeChecksEnabled": "0",
                                "lastHardStateChange": "4M 29D 1h 20m 38s",
                                "last_state_change": "4M 29D 1h 20m 38s",
                                "processPerformanceData": null,
                                "state_type": "1",
                                "acknowledgement_type": 0,
                                "flap_detection_enabled": null,
                                "notifications_enabled": null,
                                "current_check_attempt": "1",
                                "output": "OK - 10870\/s",
                                "long_output": null,
                                "perfdata": "ctxt=10870.016667;;;;",
                                "latency": null,
                                "max_check_attempts": 1,
                                "isHardstate": true,
                                "isInMonitoring": true,
                                "humanState": "ok",
                                "perfdataArray": {
                                    "ctxt": {
                                        "current": "10870.016667",
                                        "unit": null,
                                        "warning": null,
                                        "critical": null,
                                        "min": null,
                                        "max": null
                                    }
                                }
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
                                "lastCheck": "58 seconds ago",
                                "nextCheck": "in 31 seconds",
                                "activeChecksEnabled": "0",
                                "lastHardStateChange": "4M 29D 1h 20m 38s",
                                "last_state_change": "4M 29D 1h 20m 38s",
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
                                }
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
                                "lastCheck": "58 seconds ago",
                                "nextCheck": "in 2 seconds",
                                "activeChecksEnabled": "0",
                                "lastHardStateChange": "4M 29D 1h 20m 38s",
                                "last_state_change": "4M 29D 1h 20m 38s",
                                "processPerformanceData": null,
                                "state_type": "1",
                                "acknowledgement_type": 0,
                                "flap_detection_enabled": null,
                                "notifications_enabled": null,
                                "current_check_attempt": "1",
                                "output": "OK - 418\/s",
                                "long_output": null,
                                "perfdata": "processes=418.5;;;;",
                                "latency": null,
                                "max_check_attempts": 1,
                                "isHardstate": true,
                                "isInMonitoring": true,
                                "humanState": "ok",
                                "perfdataArray": {
                                    "processes": {
                                        "current": "418.5",
                                        "unit": null,
                                        "warning": null,
                                        "critical": null,
                                        "min": null,
                                        "max": null
                                    }
                                }
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
                                "lastCheck": "58 seconds ago",
                                "nextCheck": "in 36 seconds",
                                "activeChecksEnabled": "0",
                                "lastHardStateChange": "4M 29D 1h 21m 38s",
                                "last_state_change": "4M 29D 1h 21m 38s",
                                "processPerformanceData": null,
                                "state_type": "1",
                                "acknowledgement_type": 0,
                                "flap_detection_enabled": null,
                                "notifications_enabled": null,
                                "current_check_attempt": "1",
                                "output": "OK - 5.24 GB used (5.21 RAM + 0.01 SWAP + 0.02 Pagetables, this is 26.7% of 19.61 RAM (8.00 total SWAP)), 4.6 mapped, 5.0 committed, 0.0 shared",
                                "long_output": null,
                                "perfdata": "ramused=5334MB;;;0;20081.6875 swapused=11MB;;;0;8191 memused=5364.5625MB;30122;40163;0;28273.683594 mapped=4729MB;;;; committed_as=5149MB;;;; pagetables=18.92578125MB;;;; shared=0MB;;;;",
                                "latency": null,
                                "max_check_attempts": 1,
                                "isHardstate": true,
                                "isInMonitoring": true,
                                "humanState": "ok",
                                "perfdataArray": {
                                    "ramused": {
                                        "current": "5334",
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
                                        "current": "5364.5625",
                                        "unit": "MB",
                                        "warning": "30122",
                                        "critical": "40163",
                                        "min": "0",
                                        "max": "28273.683594"
                                    },
                                    "mapped": {
                                        "current": "4729",
                                        "unit": "MB",
                                        "warning": null,
                                        "critical": null,
                                        "min": null,
                                        "max": null
                                    },
                                    "committed_as": {
                                        "current": "5149",
                                        "unit": "MB",
                                        "warning": null,
                                        "critical": null,
                                        "min": null,
                                        "max": null
                                    },
                                    "pagetables": {
                                        "current": "18.92578125",
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
                                }
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
                                "lastCheck": "58 seconds ago",
                                "nextCheck": "in 19 seconds",
                                "activeChecksEnabled": "0",
                                "lastHardStateChange": "4M 29D 1h 21m 38s",
                                "last_state_change": "4M 29D 1h 21m 38s",
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
                                "perfdataArray": []
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
                                "lastCheck": "58 seconds ago",
                                "nextCheck": "in 23 seconds",
                                "activeChecksEnabled": "0",
                                "lastHardStateChange": "4M 29D 1h 21m 38s",
                                "last_state_change": "4M 29D 1h 21m 38s",
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
                                "perfdataArray": []
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
                                "lastCheck": "58 seconds ago",
                                "nextCheck": "in 2 seconds",
                                "activeChecksEnabled": "0",
                                "lastHardStateChange": "4M 29D 1h 21m 38s",
                                "last_state_change": "4M 29D 1h 21m 38s",
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
                                "perfdataArray": []
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
                                "lastCheck": "58 seconds ago",
                                "nextCheck": "in 19 seconds",
                                "activeChecksEnabled": "0",
                                "lastHardStateChange": "4M 29D 1h 21m 38s",
                                "last_state_change": "4M 29D 1h 21m 38s",
                                "processPerformanceData": null,
                                "state_type": "1",
                                "acknowledgement_type": 0,
                                "flap_detection_enabled": null,
                                "notifications_enabled": null,
                                "current_check_attempt": "1",
                                "output": "CRIT - critical offset sys.peer - stratum 4, offset 11.27 ms, jitter 23.98 ms, last reached 798 secs ago (synchronized on srvdc02.master.)",
                                "long_output": null,
                                "perfdata": "offset=11.269;5;10;0; jitter=23.975;5;10;0;",
                                "latency": null,
                                "max_check_attempts": 1,
                                "isHardstate": true,
                                "isInMonitoring": true,
                                "humanState": "critical",
                                "perfdataArray": {
                                    "offset": {
                                        "current": "11.269",
                                        "unit": null,
                                        "warning": "5",
                                        "critical": "10",
                                        "min": "0",
                                        "max": null
                                    },
                                    "jitter": {
                                        "current": "23.975",
                                        "unit": null,
                                        "warning": "5",
                                        "critical": "10",
                                        "min": "0",
                                        "max": null
                                    }
                                }
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
                                "lastCheck": "58 seconds ago",
                                "nextCheck": "in 19 seconds",
                                "activeChecksEnabled": "0",
                                "lastHardStateChange": "4M 29D 1h 21m 38s",
                                "last_state_change": "4M 29D 1h 21m 38s",
                                "processPerformanceData": null,
                                "state_type": "1",
                                "acknowledgement_type": 0,
                                "flap_detection_enabled": null,
                                "notifications_enabled": null,
                                "current_check_attempt": "1",
                                "output": "OK - 360 threads",
                                "long_output": null,
                                "perfdata": "threads=360;2000;4000;0;",
                                "latency": null,
                                "max_check_attempts": 1,
                                "isHardstate": true,
                                "isInMonitoring": true,
                                "humanState": "ok",
                                "perfdataArray": {
                                    "threads": {
                                        "current": "360",
                                        "unit": null,
                                        "warning": "2000",
                                        "critical": "4000",
                                        "min": "0",
                                        "max": null
                                    }
                                }
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
                                "lastCheck": "58 seconds ago",
                                "nextCheck": "in 8 seconds",
                                "activeChecksEnabled": "0",
                                "lastHardStateChange": "4M 29D 1h 21m 38s",
                                "last_state_change": "4M 29D 1h 21m 38s",
                                "processPerformanceData": null,
                                "state_type": "1",
                                "acknowledgement_type": 0,
                                "flap_detection_enabled": null,
                                "notifications_enabled": null,
                                "current_check_attempt": "1",
                                "output": "OK - ESTABLISHED: 2, TIME_WAIT: 3",
                                "long_output": null,
                                "perfdata": "ESTABLISHED=2;100;200;; SYN_SENT=0;;;; SYN_RECV=0;;;; LAST_ACK=0;;;; CLOSE_WAIT=0;;;; TIME_WAIT=3;170;200;; CLOSED=0;;;; CLOSING=0;;;; FIN_WAIT1=0;;;; FIN_WAIT2=0;;;; BOUND=0;;;;",
                                "latency": null,
                                "max_check_attempts": 1,
                                "isHardstate": true,
                                "isInMonitoring": true,
                                "humanState": "ok",
                                "perfdataArray": {
                                    "ESTABLISHED": {
                                        "current": "2",
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
                                }
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
                                "lastCheck": "58 seconds ago",
                                "nextCheck": "in 19 seconds",
                                "activeChecksEnabled": "0",
                                "lastHardStateChange": "4M 29D 1h 21m 38s",
                                "last_state_change": "4M 29D 1h 21m 38s",
                                "processPerformanceData": null,
                                "state_type": "1",
                                "acknowledgement_type": 0,
                                "flap_detection_enabled": null,
                                "notifications_enabled": null,
                                "current_check_attempt": "1",
                                "output": "OK - up since Mon Jun 16 09:05:00 2014 (1859d 02:27:23)",
                                "long_output": null,
                                "perfdata": "uptime=160626443.9;;;;",
                                "latency": null,
                                "max_check_attempts": 1,
                                "isHardstate": true,
                                "isInMonitoring": true,
                                "humanState": "ok",
                                "perfdataArray": {
                                    "uptime": {
                                        "current": "160626443.9",
                                        "unit": null,
                                        "warning": null,
                                        "critical": null,
                                        "min": null,
                                        "max": null
                                    }
                                }
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
                        "scheduledDowntimeDepth": "0",
                        "lastCheck": "on 1\/1\/70",
                        "nextCheck": "on 1\/1\/70",
                        "activeChecksEnabled": null,
                        "lastHardStateChange": "4M 28D 20h 25m 54s",
                        "last_state_change": "49Y 6M 18D 9h 33m 22s",
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
                        "humanState": "up"
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
                                "scheduledDowntimeDepth": 0,
                                "lastCheck": "50 seconds ago",
                                "nextCheck": "in 3 minutes",
                                "activeChecksEnabled": "0",
                                "lastHardStateChange": "10D 20h 20m 10s",
                                "last_state_change": "10D 20h 35m 10s",
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
                                }
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
                console.log($scope.services)

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

        $scope.createBackgroundForPerfdataMeter =  function(attributes){
            var background = 'none';

            if(!(attributes.min && attributes.current_value && attributes.warning && attributes.critical && attributes.min && attributes.max)){
                return background;
            }
            var linearGradientArray = ['to right'];
            var start = (attributes.min.value != "") ? attributes.min.value : 0;
            var end = (attributes.max.value != "") ? attributes.max.value : (attributes.critical.value != "") ? attributes.critical.value : 0;
            var currentValue = Number(attributes.current_value.value);
            var warningValue = Number(attributes.warning.value);
            var criticalValue = Number(attributes.critical.value);

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
                    linearGradientArray.push('#ffffff ' + curValPosInPercent + '%');
                }
            }
            return 'linear-gradient(' + linearGradientArray.join(', ') + ')';
        }
    });
