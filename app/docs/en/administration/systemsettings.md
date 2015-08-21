[//]: # (Links)
[settings]: /systemsettings "System settings"

[//]: # (Pictures)

[//]: # (Content)

## What can I set up in the system settings?

[Here][settings] you configure settings for your web server, sudo server, monitoring, system, front end, check_mk and archive.

## Which settings can I set?

You can set settings that belong to your web server, sudo server, monitoring, system, front end, check_mk and archive.

You can always get a hint if you hover over
<a class="btn-xs" data-original-title="Gives you a hint." data-placement="left" rel="tooltip" data-container="body"><i class="fa fa-info-circle fa-2x"></i></a>.

Click on <a class="btn btn-xs btn-primary">Save</a> to save your configuration.

Click on <a class="btn btn-xs btn-default">Cancel</a> if you want to discard your changes.

###### WEB SERVER
* **USER** - User name of the web server
* **GROUP** - User group of the web server

###### SUDO_SERVER
* **SOCKET** - Path where the sudo server will try to create its socket file
* **SOCKET_NAME** - Sudo servers socket name
* **SOCKETPERMISSIONS** - Permissions of the socket file
* **FOLDERPERMISSIONS** - Permissions of the socket folder
* **API_KEY** - API key for the sudo server socket API
* **WORKERSOCKET_NAME** - Sudo servers worker socket name
* **WORKERSOCKETPERMISSIONS** - Permissions of the worker socket file
* **RESPONSESOCKET_NAME** - Sudo servers worker socket name
* **RESPONSESOCKETPERMISSIONS** - Permissions of the worker socket file

###### MONITORING
* **USER** - The user of your monitoring system
* **GROUP** - The group of your monitoring system
* **FROM_ADDRESS** - Sender mail address for notifications
* **FROM_NAME** - The name we should display in your mail client
* **MESSAGE_HEADER** - The header in the plain text mail
* **CMD** - The command pipe for your monitoring system
* **HOST.INITSTATE** - Host initial state [ok, down, unknown]
* **SERVICE.INITSTATE** - Service initial state [ok, warning, unknown, critical]
* **RESTART** - Command to restart your monitoring software
* **RELOAD** - Command to reload your monitoring software
* **STOP** - Command to stop your monitoring software
* **START** - Command to start your monitoring software
* **CORECONFIG** - Path to monitoring core configuration file
* **STATUS_DAT** - Path to the status.dat of the monitoring system
* **FRESHNESS_THRESHOLD_ADDITION** - Value in seconds that get added to the service check interval for passive services, before the monitoring system will fire up the freshness check
* **AFTER_EXPORT** - A command that get executed on each export (Notice: this command runs as root, so be careful)

###### SYSTEM
* **ADDRESS** - The IP address or FQDN of the system

###### FRONT END
* **SYSTEMNAME** - The name of your system
* **MASTER_INSTANCE** - The name of your openITCOCKPIT main instance
* **AUTH_METHOD** - The authentication method that should be used for login
* **LDAP.ADDRESS** - The address or hostname of your LDAP server
* **LDAP.PORT** - The port where your LDAP server is listen to. Notice: If you want to use TLS the default port 389 should work as well! (SSL default Port is 636)
* **LDAP.BASEDN** - Your BASE DN
* **LDAP.USERNAME** - The user name that the system will use to connect to your LDAP server
* **LDAP.PASSWORD** - The password that the system will use to connect to your LDAP server
* **LDAP.SUFFIX** - The Suffix of your domain
* **LDAP.USE_TLS** - If PHP should upgrade the security of a plain connection to a TLS encrypted connection

###### CHECK_MK
* **BIN** - Path to check_mk binary
* **MATCH** - These are the services that should not be compressed by check_mk as regular expression
* **ETC** - Path to Check_MK configuration files
* **VAR** - Path to Check_MK variable files
* **ACTIVE_CHECK** - The name of the check_mk active check service template

###### ARCHIVE
* **AGE.SERVICECHECKS** - Time in weeks how long service check results will be stored
* **AGE.HOSTCHECKS** - Time in weeks how long host check results will be stored
* **AGE.STATEHISTORIES** - Time in weeks how long state change events will be stored
* **AGE.LOGENTRIES** - Time in weeks how long log entries will be stored
* **AGE.NOTIFICATIONS** - Time in weeks how long notifications will be stored
* **AGE.CONTACTNOTIFICATIONS** - Time in weeks how long contact notifications will be stored
* **AGE.CONTACTNOTIFICATIONMETHODS** - Time in weeks how long contact notification methods will be stored
