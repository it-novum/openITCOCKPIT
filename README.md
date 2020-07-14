# openITCOCKPIT - next generation monitoring

This is the **BETA VERSION** of openITCOCKPIT 4. [Click here](https://github.com/it-novum/openITCOCKPIT/tree/3.x-master) to get to the latest stable release.

<center>
<img src="https://openitcockpit.io/img/openitcockpit_logo_webseite_weisse_kacheln_nur_logo.svg" alt="openITCOCKPIT logo" width="auto" height="200">
</center>


[![Chat on Matrix](https://img.shields.io/badge/style-matrix-blue.svg?style=flat&label=chat)](https://riot.im/app/#/room/#openitcockpit:matrix.org)
[![IRC: #openitcockpit on chat.freenode.net](https://img.shields.io/badge/%23openitcockpit-freenode-blue.svg)](https://kiwiirc.com/client/chat.freenode.net/#openitcockpit)


| Distribution | Stable                                                                                                      | Nightly                                                                                                      |
|--------------|-------------------------------------------------------------------------------------------------------------|--------------------------------------------------------------------------------------------------------------|
| Bionic       | ![status](https://jenkins.it-novum.com/buildStatus/icon?job=bionic-openitcockpit-stable&style=flat-square)  | ![status](https://jenkins.it-novum.com/buildStatus/icon?job=bionic-openitcockpit-nightly&style=flat-square)  |
| Xenial       | ![status](https://jenkins.it-novum.com/buildStatus/icon?job=xenial-openitcockpit-stable&style=flat-square)  | ![status](https://jenkins.it-novum.com/buildStatus/icon?job=xenial-openitcockpit-nightly&style=flat-square)  |
| Stretch      | ![status](https://jenkins.it-novum.com/buildStatus/icon?job=stretch-openitcockpit-stable&style=flat-square) | ![status](https://jenkins.it-novum.com/buildStatus/icon?job=stretch-openitcockpit-nightly&style=flat-square) |


# What is openITCOCKPIT?
openITCOCKPIT is an Open Source system monitoring tool built for different monitoring engines like [Nagios](https://www.nagios.org/) or [Naemon](http://www.naemon.org/).

So easy that everyone can use it: create your entire monitoring configuration with a few clicks due to our smart interface written in PHP

![openITCOCKPIT](screenshots/openITC_3.7.1.png?raw=true "openITCOCKPIT")

# Demo
Play around with our [Demo](https://demo.openitcockpit.io/) system. Its equipped with the majority of modules that you will get with the community license

Credentials:
````
Username(Email): demo@openitcockpit.io
Password: demo123
````

# System requirements
* Ubuntu Linux 64 bit (16.04 LTS "xenial" and 18.04 LTS "bionic"), Debian Linux 64 bit (10 "buster")
* PHP >= 7.2
* 2 CPU cores (x86-64)
* 2 GB RAM
* 15 GB space

### Production system sizing
Unfortunately there is no golden rule for the right sizing of a monitoring system. This depends on the amount of hosts and services you like to monitor.

Please keep in mind that a monitoring system usually will create more I/O than your KVM farm!

It's recommended to use SSD as main storage.

A rough guide:
* 32 GB RAM
* 16 CPU Cores
* 500 GB space

# Installation
openITCOCKPIT runs on Ubuntu and Debian Linux systems and is available for download/installation via apt repositories.

To install openITCOCKPIT on your system, just run the following commands.

If [phpMyAdmin](https://www.phpmyadmin.net/) asks you for your web server **leave the selection blank** and continue with **Ok**.

openITCOCKPIT uses Nginx as webserver and will generate a configuration for phpMyAdmin automatically for you.

Please execute all commands as user `root` or via `sudo`.

**openITCOCKPIT + Naemon (recommended)**
````
apt-get install apt-transport-https curl gnupg2 ca-certificates
curl https://packages.openitcockpit.io/repokey.txt | apt-key add -

echo "deb https://packages.openitcockpit.io/openitcockpit/$(lsb_release -sc)/unstable $(lsb_release -sc) main" > /etc/apt/sources.list.d/openitcockpit.list
apt-get update

apt-get install openitcockpit
````


# Register openitcockpit community version:

You can register your openITCOCKPIT installation to get access to free community modules.
Login to the webinterface of openITCOCKPIT and navigate to System -> Registration,
enter the community license key `e5aef99e-817b-0ff5-3f0e-140c1f342792` and click Register.
After successful registration you can install the free community modules at System tools -> Package Manager

# Main Features
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

# Screenshots

![openITCOCKPIT](screenshots/timeline_oitc.png?raw=true "Timeline")

![openITCOCKPIT](screenshots/map1.png?raw=true "Maps")

![openITCOCKPIT](screenshots/map2.png?raw=true "Maps")

![openITCOCKPIT](screenshots/event_correlation.png?raw=true "Event correlation")

![openITCOCKPIT](screenshots/downtime_report.png?raw=true "Downtime report")

![openITCOCKPIT](screenshots/current_state_report.png?raw=true "Current state report")


# Developers welcome
openITCOCKPIT's development is publicly available in GitHub. Everybody is welcome to join :-)

# Need help or support?
* Join [#openitcockpit](http://webchat.freenode.net/?channels=openitcockpit) on freenode.net
* [it-novum GmbH](https://it-novum.com/en/it-service-management/openitcockpit/openitcockpit-enterprise-subscription-license) provides commercial support

# Security
Please send security vulnerabilities found in openITCOCKPIT or software that is used by openITCOCKPIT to: `security@openitcockpit.io`.

All disclosed vulnerabilities are available here: [https://openitcockpit.io/security/](https://openitcockpit.io/security/)

# License
```
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
```
