## Query systemsettings:

<div class="input-group">
	<span class="input-group-addon bg-color-green txt-color-white">GET</span>
	<input type="text" class="form-control" readonly="readonly" value="/systemsettings.json">
</div>
<br />
<div class="panel panel-primary">
	<div class="panel-heading">
		<h3 class="panel-title">Response: application/json</h3>
	</div>
	<div class="panel-body">
		<pre>
{
    "all_systemsettings": {
        "WEBSERVER": [
            {
                "id": "4",
                "key": "WEBSERVER.USER",
                "value": "www-data",
                "info": "Username of the webserver",
                "section": "WEBSERVER",
                "created": "0000-00-00 00:00:00",
                "modified": "2015-11-12 08:03:06"
            },
            {
                "id": "5",
                "key": "WEBSERVER.GROUP",
                "value": "www-data",
                "info": "Usergroup of the webserver",
                "section": "WEBSERVER",
                "created": "0000-00-00 00:00:00",
                "modified": "2015-11-12 08:03:06"
            }
        ],
        "SUDO_SERVER": [
            {
                "id": "1",
                "key": "SUDO_SERVER.SOCKET",
                "value": "\/usr\/share\/openitcockpit\/app\/run\/",
                "info": "Path where the sudo server will try to create its socket file",
                "section": "SUDO_SERVER",
                "created": "0000-00-00 00:00:00",
                "modified": "2015-11-12 08:03:06"
            },
            {
                "id": "2",
                "key": "SUDO_SERVER.SOCKET_NAME",
                "value": "sudo.sock",
                "info": "Sudoservers socket name",
                "section": "SUDO_SERVER",
                "created": "0000-00-00 00:00:00",
                "modified": "2015-11-12 08:03:06"
            },
            {
                "id": "3",
                "key": "SUDO_SERVER.SOCKETPERMISSIONS",
                "value": "49588",
                "info": "Permissions of the socket file",
                "section": "SUDO_SERVER",
                "created": "0000-00-00 00:00:00",
                "modified": "2015-11-12 08:03:06"
            },
            {
                "id": "6",
                "key": "SUDO_SERVER.FOLDERPERMISSIONS",
                "value": "16877",
                "info": "Permissions of the socket folder",
                "section": "SUDO_SERVER",
                "created": "0000-00-00 00:00:00",
                "modified": "2015-11-12 08:03:06"
            },
            {
                "id": "20",
                "key": "SUDO_SERVER.API_KEY",
                "value": "9a9c262ab376af7370bede02b678db6c",
                "info": "API key for the sudoserver socket API",
                "section": "SUDO_SERVER",
                "created": "0000-00-00 00:00:00",
                "modified": "2015-11-12 08:03:06"
            },
            {
                "id": "36",
                "key": "SUDO_SERVER.WORKERSOCKET_NAME",
                "value": "worker.sock",
                "info": "Sudoservers worker socket name",
                "section": "SUDO_SERVER",
                "created": "0000-00-00 00:00:00",
                "modified": "2015-11-12 08:03:06"
            },
            {
                "id": "37",
                "key": "SUDO_SERVER.WORKERSOCKETPERMISSIONS",
                "value": "49588",
                "info": "Permissions of the worker socket file",
                "section": "SUDO_SERVER",
                "created": "0000-00-00 00:00:00",
                "modified": "2015-11-12 08:03:06"
            },
            {
                "id": "39",
                "key": "SUDO_SERVER.RESPONSESOCKET_NAME",
                "value": "response.sock",
                "info": "Sudoservers worker socket name",
                "section": "SUDO_SERVER",
                "created": "0000-00-00 00:00:00",
                "modified": "2015-11-12 08:03:06"
            },
            {
                "id": "40",
                "key": "SUDO_SERVER.RESPONSESOCKETPERMISSIONS",
                "value": "49588",
                "info": "Permissions of the worker socket file",
                "section": "SUDO_SERVER",
                "created": "0000-00-00 00:00:00",
                "modified": "2015-11-12 08:03:06"
            }
        ],
        "MONITORING": [
            {
                "id": "21",
                "key": "MONITORING.USER",
                "value": "nagios",
                "info": "The user of your monitoring system",
                "section": "MONITORING",
                "created": "0000-00-00 00:00:00",
                "modified": "2015-11-12 08:03:06"
            },
            {
                "id": "22",
                "key": "MONITORING.GROUP",
                "value": "nagios",
                "info": "The group of your monitoring system",
                "section": "MONITORING",
                "created": "0000-00-00 00:00:00",
                "modified": "2015-11-12 08:03:06"
            },
            {
                "id": "23",
                "key": "MONITORING.FROM_ADDRESS",
                "value": "openitcockpit@example.org",
                "info": "Sender mail address for notifications",
                "section": "MONITORING",
                "created": "0000-00-00 00:00:00",
                "modified": "2015-11-12 08:03:06"
            },
            {
                "id": "24",
                "key": "MONITORING.FROM_NAME",
                "value": "openITCOCKPIT Notification",
                "info": "The name we should display in your mail client",
                "section": "MONITORING",
                "created": "0000-00-00 00:00:00",
                "modified": "2015-11-12 08:03:06"
            },
            {
                "id": "25",
                "key": "MONITORING.MESSAGE_HEADER",
                "value": "**** openITCOCKPIT notification by it-novum GmbH ****",
                "info": "The header in the plain text mail",
                "section": "MONITORING",
                "created": "0000-00-00 00:00:00",
                "modified": "2015-11-12 08:03:06"
            },
            {
                "id": "26",
                "key": "MONITORING.CMD",
                "value": "\/opt\/openitc\/nagios\/var\/rw\/nagios.cmd",
                "info": "The command pipe for your monitoring system",
                "section": "MONITORING",
                "created": "0000-00-00 00:00:00",
                "modified": "2015-11-12 08:03:06"
            },
            {
                "id": "29",
                "key": "MONITORING.HOST.INITSTATE",
                "value": "u",
                "info": "Host initial state [o,d,u]",
                "section": "MONITORING",
                "created": "0000-00-00 00:00:00",
                "modified": "2015-11-12 08:03:06"
            },
            {
                "id": "30",
                "key": "MONITORING.SERVICE.INITSTATE",
                "value": "u",
                "info": "Service initial state [o,w,u,c]",
                "section": "MONITORING",
                "created": "0000-00-00 00:00:00",
                "modified": "2015-11-12 08:03:06"
            },
            {
                "id": "31",
                "key": "MONITORING.RESTART",
                "value": "service nagios restart",
                "info": "Command to restart your monitoring software",
                "section": "MONITORING",
                "created": "0000-00-00 00:00:00",
                "modified": "2015-11-12 08:03:06"
            },
            {
                "id": "32",
                "key": "MONITORING.RELOAD",
                "value": "service nagios reload",
                "info": "Command to reload your monitoring software",
                "section": "MONITORING",
                "created": "0000-00-00 00:00:00",
                "modified": "2015-11-12 08:03:06"
            },
            {
                "id": "33",
                "key": "MONITORING.STOP",
                "value": "service nagios stop",
                "info": "Command to stop your monitoring software",
                "section": "MONITORING",
                "created": "0000-00-00 00:00:00",
                "modified": "2015-11-12 08:03:06"
            },
            {
                "id": "34",
                "key": "MONITORING.START",
                "value": "service nagios start",
                "info": "Command to start your monitoring software",
                "section": "MONITORING",
                "created": "0000-00-00 00:00:00",
                "modified": "2015-11-12 08:03:06"
            },
            {
                "id": "41",
                "key": "MONITORING.CORECONFIG",
                "value": "\/etc\/openitcockpit\/nagios.cfg",
                "info": "Path to monitoring core configuration file",
                "section": "MONITORING",
                "created": "0000-00-00 00:00:00",
                "modified": "2015-11-12 08:03:06"
            },
            {
                "id": "60",
                "key": "MONITORING.STATUS_DAT",
                "value": "\/opt\/openitc\/nagios\/var\/status.dat",
                "info": "Path to the status.dat of the monitoring system",
                "section": "MONITORING",
                "created": "0000-00-00 00:00:00",
                "modified": "2015-11-12 08:03:06"
            },
            {
                "id": "65",
                "key": "MONITORING.FRESHNESS_THRESHOLD_ADDITION",
                "value": "300",
                "info": "Value in seconds that get added to the service check interval for passive services, before the monitoring system will fire up the freshness check",
                "section": "MONITORING",
                "created": "2014-12-23 11:45:31",
                "modified": "2015-11-12 08:03:06"
            },
            {
                "id": "66",
                "key": "MONITORING.AFTER_EXPORT",
                "value": "#echo 1",
                "info": "A command that get executed on each export (Notice: this command runs as root, so be careful)",
                "section": "MONITORING",
                "created": "2014-12-23 11:45:31",
                "modified": "2015-11-12 08:03:06"
            }
        ],
        "SYSTEM": [
            {
                "id": "28",
                "key": "SYSTEM.ADDRESS",
                "value": "192.168.0.1",
                "info": "The IP address or FQDN of the system",
                "section": "SYSTEM",
                "created": "0000-00-00 00:00:00",
                "modified": "2015-11-12 08:03:06"
            }
        ],
        "FRONTEND": [
            {
                "id": "35",
                "key": "FRONTEND.SYSTEMNAME",
                "value": "openITCOCKPIT",
                "info": "The name of your system",
                "section": "FRONTEND",
                "created": "0000-00-00 00:00:00",
                "modified": "2015-11-12 08:03:06"
            },
            {
                "id": "46",
                "key": "FRONTEND.MASTER_INSTANCE",
                "value": "Mastersystem",
                "info": "The name of your openITCOCKPIT main instance",
                "section": "FRONTEND",
                "created": "0000-00-00 00:00:00",
                "modified": "2015-11-12 08:03:06"
            },
            {
                "id": "47",
                "key": "FRONTEND.AUTH_METHOD",
                "value": "session",
                "info": "The authentication method that shoud be used for login",
                "section": "FRONTEND",
                "created": "0000-00-00 00:00:00",
                "modified": "2015-11-12 08:03:06"
            },
            {
                "id": "48",
                "key": "FRONTEND.LDAP.ADDRESS",
                "value": "ldap01.local.lan",
                "info": "The address or hostname of your LDAP server",
                "section": "FRONTEND",
                "created": "0000-00-00 00:00:00",
                "modified": "2015-11-12 08:03:06"
            },
            {
                "id": "49",
                "key": "FRONTEND.LDAP.PORT",
                "value": "389",
                "info": "The port where your LDAP server is listen to. Notice: If you want to use TLS the default port 389 shoud work as well! (SSL default Port is 636)",
                "section": "FRONTEND",
                "created": "0000-00-00 00:00:00",
                "modified": "2015-11-12 08:03:06"
            },
            {
                "id": "50",
                "key": "FRONTEND.LDAP.BASEDN",
                "value": "OU=Foo,OU=Bar,DC=example,DC=org",
                "info": "Your BASEDN",
                "section": "FRONTEND",
                "created": "0000-00-00 00:00:00",
                "modified": "2015-11-12 08:03:06"
            },
            {
                "id": "51",
                "key": "FRONTEND.LDAP.USERNAME",
                "value": "admin",
                "info": "The username that the system will use to connect to your LDAP server",
                "section": "FRONTEND",
                "created": "0000-00-00 00:00:00",
                "modified": "2015-11-12 08:03:06"
            },
            {
                "id": "52",
                "key": "FRONTEND.LDAP.PASSWORD",
                "value": "password123",
                "info": "The password that the system will use to connect to your LDAP server",
                "section": "FRONTEND",
                "created": "0000-00-00 00:00:00",
                "modified": "2015-11-12 08:03:06"
            },
            {
                "id": "53",
                "key": "FRONTEND.LDAP.SUFFIX",
                "value": "@example.org",
                "info": "The Suffix of your domain",
                "section": "FRONTEND",
                "created": "0000-00-00 00:00:00",
                "modified": "2015-11-12 08:03:06"
            },
            {
                "id": "54",
                "key": "FRONTEND.LDAP.USE_TLS",
                "value": "0",
                "info": "If PHP shoud upgrade the security of a plain connection to a TLS encrypted connection",
                "section": "FRONTEND",
                "created": "0000-00-00 00:00:00",
                "modified": "2015-11-12 08:03:06"
            },
            {
                "id": "68",
                "key": "FRONTEND.SSO.CLIENT_ID",
                "info": "Client id generated in SSO Server",
                "section": "FRONTEND",
                "created": "0000-00-00 00:00:00",
                "modified": "2015-11-12 08:03:06"
            },
            {
                "id": "69",
                "key": "FRONTEND.SSO.CLIENT_SECRET",
                "info": "Client secret generated in SSO Server",
                "section": "FRONTEND",
                "created": "0000-00-00 00:00:00",
                "modified": "2015-11-12 08:03:06"
            },
            {
                "id": "70",
                "key": "FRONTEND.SSO.AUTH_ENDPOINT",
                "info": "Authorization endpoint of SSO Server",
                "section": "FRONTEND",
                "created": "0000-00-00 00:00:00",
                "modified": "2015-11-12 08:03:06"
            },
            {
                "id": "71",
                "key": "FRONTEND.SSO.TOKEN_ENDPOINT",
                "info": "Token endpoint of SSO Server",
                "section": "FRONTEND",
                "created": "0000-00-00 00:00:00",
                "modified": "2015-11-12 08:03:06"
            },
            {
                "id": "72",
                "key": "FRONTEND.SSO.USER_ENDPOINT",
                "info": "User info endpoint of SSO Server",
                "section": "FRONTEND",
                "created": "0000-00-00 00:00:00",
                "modified": "2015-11-12 08:03:06"
            },
            {
                "id": "73",
                "key": "FRONTEND.SSO.NO_EMAIL_MESSAGE",
                "info": "The error message that appears when provided E-mail address was not found in openITCOCKPIT",
                "section": "FRONTEND",
                "created": "0000-00-00 00:00:00",
                "modified": "2015-11-12 08:03:06"
            },
            {
                "id": "74",
                "key": "FRONTEND.SSO.LOG_OFF_LINK",
                "info": "SSO Server log out link",
                "section": "FRONTEND",
                "created": "0000-00-00 00:00:00",
                "modified": "2015-11-12 08:03:06"
            },
            {
                "id": "75",
                "key": "FRONTEND.CERT.DEFAULT_USER_EMAIL",
                "info": "Default user E-mail address to be used if no E-mail address was found during the login with certificate",
                "section": "FRONTEND",
                "created": "0000-00-00 00:00:00",
                "modified": "2015-11-12 08:03:06"
            },
        ],
        "CHECK_MK": [
            {
                "id": "38",
                "key": "CHECK_MK.BIN",
                "value": "\/opt\/openitc\/nagios\/3rd\/check_mk\/bin\/check_mk",
                "info": "Path to check_mk binary",
                "section": "CHECK_MK",
                "created": "0000-00-00 00:00:00",
                "modified": "2015-11-12 08:03:06"
            },
            {
                "id": "42",
                "key": "CHECK_MK.MATCH",
                "value": "(perl|dsmc|java|ksh|VBoxHeadless)",
                "info": "These are the services that should not be compressed by check_mk as regular expression",
                "section": "CHECK_MK",
                "created": "0000-00-00 00:00:00",
                "modified": "2015-11-12 08:03:06"
            },
            {
                "id": "43",
                "key": "CHECK_MK.ETC",
                "value": "\/opt\/openitc\/nagios\/3rd\/check_mk\/etc\/",
                "info": "Path to Check_MK confi files",
                "section": "CHECK_MK",
                "created": "0000-00-00 00:00:00",
                "modified": "2015-11-12 08:03:06"
            },
            {
                "id": "44",
                "key": "CHECK_MK.VAR",
                "value": "\/opt\/openitc\/nagios\/3rd\/check_mk\/var\/",
                "info": "Path to Check_MK variable files",
                "section": "CHECK_MK",
                "created": "0000-00-00 00:00:00",
                "modified": "2015-11-12 08:03:06"
            },
            {
                "id": "45",
                "key": "CHECK_MK.ACTIVE_CHECK",
                "value": "CHECK_MK_ACTIVE",
                "info": "The name of the check_mk active check service template",
                "section": "CHECK_MK",
                "created": "0000-00-00 00:00:00",
                "modified": "2015-11-12 08:03:06"
            }
        ],
        "ARCHIVE": [
            {
                "id": "56",
                "key": "ARCHIVE.AGE.SERVICECHECKS",
                "value": "2",
                "info": "Time in weeks how long service check results will be stored",
                "section": "ARCHIVE",
                "created": "0000-00-00 00:00:00",
                "modified": "2015-11-12 08:03:06"
            },
            {
                "id": "57",
                "key": "ARCHIVE.AGE.HOSTCHECKS",
                "value": "2",
                "info": "Time in weeks how long host check results will be stored",
                "section": "ARCHIVE",
                "created": "0000-00-00 00:00:00",
                "modified": "2015-11-12 08:03:06"
            },
            {
                "id": "58",
                "key": "ARCHIVE.AGE.STATEHISTORIES",
                "value": "53",
                "info": "Time in weeks how long state change events will be stored",
                "section": "ARCHIVE",
                "created": "0000-00-00 00:00:00",
                "modified": "2015-11-12 08:03:06"
            },
            {
                "id": "59",
                "key": "ARCHIVE.AGE.LOGENTRIES",
                "value": "2",
                "info": "Time in weeks how long logentries will be stored",
                "section": "ARCHIVE",
                "created": "0000-00-00 00:00:00",
                "modified": "2015-11-12 08:03:06"
            },
            {
                "id": "61",
                "key": "ARCHIVE.AGE.NOTIFICATIONS",
                "value": "2",
                "info": "Time in weeks how long notifications will be stored (keep eq to CONTACTNOTIFICATIONS AND CONTACTNOTIFICATIONMETHODS)",
                "section": "ARCHIVE",
                "created": "2014-12-23 10:32:55",
                "modified": "2015-11-12 08:03:06"
            },
            {
                "id": "62",
                "key": "ARCHIVE.AGE.CONTACTNOTIFICATIONS",
                "value": "2",
                "info": "Time in weeks how long contactnotifications will be stored (keep eq to NOTIFICATIONS AND CONTACTNOTIFICATIONMETHODS)",
                "section": "ARCHIVE",
                "created": "0000-00-00 00:00:00",
                "modified": "2015-11-12 08:03:06"
            },
            {
                "id": "63",
                "key": "ARCHIVE.AGE.CONTACTNOTIFICATIONMETHODS",
                "value": "2",
                "info": "Time in weeks how long contactnotificationmethods will be stored (keep eq to NOTIFICATIONS AND CONTACTNOTIFICATIONS)",
                "section": "ARCHIVE",
                "created": "0000-00-00 00:00:00",
                "modified": "2015-11-12 08:03:06"
            }
        ],
        "TICKET_SYSTEM": [
            {
                "id": "67",
                "key": "TICKET_SYSTEM.URL",
                "value": "",
                "info": "Link to the ticket system",
                "section": "TICKET_SYSTEM",
                "created": "2016-06-13 00:00:00",
                "modified": "2016-06-13 00:00:01"
            }
        ],
    }
}
		</pre>
	</div>
</div>

