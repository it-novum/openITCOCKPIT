[//]: # (Links)

[//]: # (Pictures)

[//]: # (Content)

## The module require

* NRPEModul
* LinuxBasicMonitoringModul

## The module includes:

### User Defined Macros

* Alfresco User Frontend
* Alfresco Password Frontend
* Alfresco User JMX
* Alfresco Password JMX

### Commands

* check_alfresco_http
* check_alfresco_http_auth
* check_alfresco_https
* check_alfresco_https_auth	  
* check_nrpe_alfresco	  

### Service Templates

* ALFRESCO_CONNECTION_POOL
* ALFRESCO_CONTENT_SERVICES
* ALFRESCO_CONTENT_STORE_FREE
* ALFRESCO_HEAP_USAGE
* ALFRESCO_LICENCE_DAY
* ALFRESCO_NUMBER_DOCUMENTS
* ALFRESCO_NUMBER_DOCUMENTS_ARCHIVE
* ALFRESCO_REPO_CONNECTED_USER
* ALFRESCO_REPO_SESSION
* ALFRESCO_TASK_INSTANCE
* ALFRESCO_THREAD_COUNT
* ALFRESCO_TOTAL_USER
* ALFRESCO_WORKFLOW_INSTANCE
* ALFRESCO_URI_ALFRESCO
* ALFRESCO_URI_AOS
* ALFRESCO_URI_SHARE
* ALFRESCO_URI_WEBDAV
* ALFRESCO_URI_ALFRESCO_SSL
* ALFRESCO_URI_AOS_SSL
* ALFRESCO_URI_SHARE_SSL
* ALFRESCO_URI_WEBDAV_SSL

### Service Template Grps.

* Alfresco Application Monitoring
* Alfresco Frontend Monitoring
* Alfresco Frontend Monitoring with SSL

## Todo Master

On the master system, alfresco jmx/fontend user and password must be stored in the appropriate User Defined Macro.

## Todo alfresco Server

The package openitcockpit-alfresco-client musst be installed on the system to be monitored.
```shell
apt-get install apt-transport-https
apt-key adv --recv --keyserver hkp://keyserver.ubuntu.com 1148DA8E
echo 'deb https://packages.openitcockpit.com/repositories/xenial xenial main' > /etc/apt/sources.list.d/openitcockpit.list
apt-get install openitcockpit-alfresco-client
```
After the install nrpe has to be restarted:

```shell
systemctl restart nagios-nrpe-server.service
```
