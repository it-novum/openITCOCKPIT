[//]: # (Links)

[//]: # (Pictures)

[//]: # (Content)

## Linux Basic Monitoring Module

### The module includes:

#### Commands

..* check_nrpe
..* check_nrpe_args

#### Service Templates

..* LINUX_NRPE_DISK
..* LINUX_NRPE_LOAD
..* LINUX_NRPE_MEMORY
..* LINUX_NRPE_PROC
..* LINUX_NRPE_ZOBMIE

#### Service Template Grps.

..*  Linux Basic Monitoring NRPE

### ToDo on the linux client

The package openitcockpit-linux-basic-client musst be installed on the system ot be monitored. 
```shell
apt-get install apt-transport-https
apt-key adv --recv --keyserver hkp://keyserver.ubuntu.com 1148DA8E
echo 'deb http://apt.oitc.itn/repositories/xenial xenial main' > /etc/apt/sources.list.d/openitcockpit.list
apt-get install openitcockpit-linux-basic-client
```
After the install the file /etc/nagios/nrpe.cfg has to be adpated the following line:
```shell
...
allowed_hosts=127.0.0.1,<IP des Monitoring Sat/Master>
...

After the change nrpe has to be restarted:

```shell
systemctl restart nagios-nrpe-server.service
```
