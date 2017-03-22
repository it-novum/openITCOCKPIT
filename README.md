<img src="https://mgmt.it-novum.com/oitc2.svg" alt="openITCOCKPIT logo" width="auto" height="100">

[![Chat on Matrix](https://img.shields.io/badge/style-matrix-blue.svg?style=flat&label=chat)](https://riot.im/app/#/room/#openitcockpit:matrix.org)
[![IRC: #openitcockpit on chat.freenode.net](https://img.shields.io/badge/%23openitcockpit-freenode-blue.svg)](https://kiwiirc.com/client/chat.freenode.net/#openitcockpit)

# What is openITCOCKPIT?
openITCOCKPIT is an Open Source system monitoring tool built for different monitoring engines like [Nagios](https://www.nagios.org/) or [Naemon](http://www.naemon.org/).

So easy that everyone can use it: create your entire monitoring configuration with a few clicks due to our smart interface written in PHP

![openITCOCKPIT](https://mgmt.it-novum.com/oitc.png "openITCOCKPIT")

# System requirements
* Ubuntu Linux (14.04 LTS "trusty" and 16.04 LTS "xenial"), Debian Linux 8 "jessie"
* 2 CPU cores (x86-64)
* 2 GB RAM
* 15 GB space

### Production system sizing
Unfortunately there is no golden rule for the right sizing of a monitoring system. This depends on the amount of hosts and services you like to monitor.

Please keep in mind that a monitoring system usually will create more I/O than your KVM farm!

A rough guide:
* 32 GB RAM
* 16 CPU Cores
* 500GB space

# Installation
openITCOCKPIT runs on Ubuntu and Debian Linux systems and is available for download/installation via apt repositories.

To install openITCOCKPIT on your system, just run the following commands.

If [phpMyAdmin](https://www.phpmyadmin.net/) asks you for your web server **leave the selection blank** and continue with **Ok**.

openITCOCKPIT uses Nginx as webserver and will generate a configuration for phpMyAdmin automatically for you.

**openITCOCKPIT + Naemon (recommended)**
````
apt-get install apt-transport-https
apt-key adv --recv --keyserver hkp://keyserver.ubuntu.com 1148DA8E
````
Ubuntu 14.04 - Trusty
````
echo 'deb https://packages.openitcockpit.com/repositories/trusty trusty main' > /etc/apt/sources.list.d/openitcockpit.list
````
Ubuntu 16.04 - Xenial
````
echo 'deb https://packages.openitcockpit.com/repositories/xenial xenial main' > /etc/apt/sources.list.d/openitcockpit.list
````
Debian 8 - Jessie
````
echo 'deb https://packages.openitcockpit.com/repositories/jessie jessie main' > /etc/apt/sources.list.d/openitcockpit.list
````
````
apt-get update
apt-get install openitcockpit{,-common,-naemon,-statusengine-naemon,-npcd,-message}
/usr/share/openitcockpit/app/SETUP.sh
````
**openITCOCKPIT Nagios 4:**

````
apt-get install apt-transport-https
apt-key adv --recv --keyserver hkp://keyserver.ubuntu.com 1148DA8E
````
Ubuntu 14.04 - Trusty
````
echo 'deb https://packages.openitcockpit.com/repositories/trusty trusty main' > /etc/apt/sources.list.d/openitcockpit.list
````
Ubuntu 16.04 - Xenial
````
echo 'deb https://packages.openitcockpit.com/repositories/xenial xenial main' > /etc/apt/sources.list.d/openitcockpit.list
````
Debian 8 - Jessie
````
echo 'deb https://packages.openitcockpit.com/repositories/jessie jessie main' > /etc/apt/sources.list.d/openitcockpit.list
````
````
apt-get update
apt-get install openitcockpit{,-common,-nagios,-ndoutils,-npcd,-message}
/usr/share/openitcockpit/app/SETUP.sh
````

**Register openitcockpit community version:**

Login to the webinterface of openITCOCKPIT and go to Administration -> Registration, enter the community license key 
0dc0d951-e34e-43d0-a5a5-a690738e6a49 and click Register. 
After successful registration you can install the free community modules at Administration -> Package Manager

# Features
* Easy to use web interface
* Template based configuration that will make your life easier
* MySQL based
* REST API
* Inbuilt package manager everyone can provide Add-ons for extending the interface
* HA cluster ready
* Two-factor authentication
* LDAP authentication
* Multitenancy
* Object permissions
* [Distributed Monitoring](http://www.it-novum.com/blog/distributed-monitoring-mit-openitcockpit-phpnsta/)
* [Mod-Gearman](http://mod-gearman.org/)
* [Statusengine](http://statusengine.org/)
* And much more to discover...

# Vagrant box [Repository](https://github.com/it-novum/vagrantboxes)
If you like to try openITCOCKPIT you can install our Vagrant box
* Install [Vagrant](https://www.vagrantup.com/downloads.html) and [VirtualBox](https://www.virtualbox.org/wiki/Downloads) on your system
````
apt-get install vagrant virtualbox
````
* Download the [Vagrantfile](https://raw.githubusercontent.com/it-novum/vagrantboxes/master/openITCOCKPIT_V3/Vagrantfile)
````
mkdir openITCOCKPIT_V3
cd openITCOCKPIT_V3
wget https://raw.githubusercontent.com/it-novum/vagrantboxes/master/openITCOCKPIT_V3/Vagrantfile
````
* Run the Vagrant box and follow the instructions
````
vagrant up
````

# Developers welcome
openITCOCKPIT's development is publicly available in GitHub. Everybody is welcome to join :-)

### Vagrant box (nightly)
Use [this Vagrantfile](https://raw.githubusercontent.com/it-novum/vagrantboxes/master/openITCOCKPIT_V3-nightly/Vagrantfile) to install the latest nightly build

# Need help or support?
* Join [#openitcockpit](http://webchat.freenode.net/?channels=openitcockpit) on freenode.net
* [it-novum GmbH](http://www.it-novum.com/en/support-openitcockpit-en.html) provides commercial support

# License
Copyright (C) 2015-2017  it-novum GmbH


openITCOCKPIT is dual licensed

1)
This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, version 3 of the License.


This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.


You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.

2)
If you purchased an openITCOCKPIT Enterprise Edition you can use this file
under the terms of the openITCOCKPIT Enterprise Edition licence agreement.
Licence agreement and licence key will be shipped with the order
confirmation.
