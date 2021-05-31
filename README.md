# openITCOCKPIT - next generation monitoring

The open source configuration interface for [Nagios](https://www.nagios.org/), [Naemon](http://www.naemon.org/) and [Prometheus](https://prometheus.io/)

<center>
<img src="https://openitcockpit.io/img/openitcockpit_logo_webseite_weisse_kacheln_nur_logo.svg" alt="openITCOCKPIT logo" width="auto" height="200">
</center>

[![Discord: ](https://img.shields.io/badge/Discord-Discord.svg?label=&logo=discord&logoColor=ffffff&color=7389D8&labelColor=6A7EC2)](https://discord.gg/G8KhxKuQ9G)
[![IRC: #openitcockpit on chat.freenode.net](https://img.shields.io/badge/%23openitcockpit-Libera.Chat-blue.svg)](https://web.libera.chat/#openitcockpit)


# What is openITCOCKPIT?
openITCOCKPIT is an Open Source system monitoring tool built for different monitoring engines like Nagios, Naemon and Prometheus.

So easy that everyone can use it: create your entire monitoring configuration with a few clicks due to our smart interface written in PHP

![openITCOCKPIT](screenshots/dashboard_v4.png?raw=true "openITCOCKPIT")

# Demo
Play around with our [Demo](https://demo.openitcockpit.io/) system. Its equipped with the majority of modules that you will get with the community license

Credentials:
````
Username(Email): demo@openitcockpit.io
Password: demo123
````

# Build status
openITCOCKPIT 4.x 
| Distribution | Stable                                                                                                      | Nightly                                                                                                      |
|--------------|-------------------------------------------------------------------------------------------------------------|--------------------------------------------------------------------------------------------------------------|
| Focal       | ![status](https://drone.openitcockpit.io/buildStatus/icon?job=openitcockpit-packages%2Fstable&style=flat-square)  | ![status](https://drone.openitcockpit.io/buildStatus/icon?job=openitcockpit-packages%2Fnightly&style=flat-square)  |
| Bionic       | ![status](https://drone.openitcockpit.io/buildStatus/icon?job=openitcockpit-packages%2Fstable&style=flat-square)  | ![status](https://drone.openitcockpit.io/buildStatus/icon?job=openitcockpit-packages%2Fnightly&style=flat-square)  |
| Buster      | ![status](https://drone.openitcockpit.io/buildStatus/icon?job=openitcockpit-packages%2Fstable&style=flat-square) | ![status](https://drone.openitcockpit.io/buildStatus/icon?job=openitcockpit-packages%2Fnightly&style=flat-square) |

openITCOCKPIT 3.x 
| Distribution | Stable                                                                                                      | Nightly                                                                                                      |
|--------------|-------------------------------------------------------------------------------------------------------------|--------------------------------------------------------------------------------------------------------------|
| Bionic       | ![status](https://jenkins.it-novum.com/buildStatus/icon?job=bionic-openitcockpit-stable&style=flat-square)  | ![status](https://jenkins.it-novum.com/buildStatus/icon?job=bionic-openitcockpit-nightly&style=flat-square)  |
| Xenial       | ![status](https://jenkins.it-novum.com/buildStatus/icon?job=xenial-openitcockpit-stable&style=flat-square)  | ![status](https://jenkins.it-novum.com/buildStatus/icon?job=xenial-openitcockpit-nightly&style=flat-square)  |
| Stretch      | ![status](https://jenkins.it-novum.com/buildStatus/icon?job=stretch-openitcockpit-stable&style=flat-square) | ![status](https://jenkins.it-novum.com/buildStatus/icon?job=stretch-openitcockpit-nightly&style=flat-square) |

# System requirements
* Ubuntu Linux 64 bit (20.04 LTS "focal" and 18.04 LTS "bionic"), Debian Linux 64 bit (10 "buster")
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
openITCOCKPIT runs on Ubuntu and Debian Linux systems and is available for download/installation via a apt repository.

To install openITCOCKPIT on your system, please follow the official documentation: https://openitcockpit.io/download_server/

## Raspberry Pi and arm64
openITCOCKPIT is 100% compatible to arm64. More information can be found on the project website: [https://openitcockpit.io/download_server/](https://openitcockpit.io/download_server/)

# Register openitcockpit community version:

You can register your openITCOCKPIT installation to get access to free community modules.
Login to the webinterface of openITCOCKPIT and navigate to System -> Registration,
enter the community license key `0dc0d951-e34e-43d0-a5a5-a690738e6a49` and click Register.
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

![openITCOCKPIT](screenshots/timeline.png?raw=true "Timeline")

![openITCOCKPIT](screenshots/mapmodule.png?raw=true "Maps")

![openITCOCKPIT](screenshots/event_correlation.png?raw=true "Event correlation")

![openITCOCKPIT](screenshots/downtime_report.png?raw=true "Downtime report")

![openITCOCKPIT](screenshots/current_state_report.png?raw=true "Current state report")


# Developers welcome
openITCOCKPIT's development is publicly available in GitHub. Everybody is welcome to join :-)

- [Setup an openITCOCKPIT development system](https://docs.it-novum.com/display/ODE/Creating+a+openITCOCKPIT+Development+System)
- [Create your own translation](https://docs.it-novum.com/display/ODE/Create+your+own+openITCOCKPIT+translation)
- [Creating a new openITCOCKPIT module](https://docs.it-novum.com/display/ODE/Creating+a+new+openITCOCKPIT+Module)
- [Creating a new check plugin](https://docs.it-novum.com/display/ODE/Creating+a+new+openITCOCKPIT+check+plugin)

# Need help or support?
* Official [Discord Server](https://discord.gg/G8KhxKuQ9G)
* Join [#openitcockpit](https://web.libera.chat/#openitcockpit) on Libera Chat
* [it-novum GmbH](https://it-services.it-novum.com/support-2/) provides commercial support

# Security
Please send security vulnerabilities found in openITCOCKPIT or software that is used by openITCOCKPIT to: `security@openitcockpit.io`.

All disclosed vulnerabilities are available here: [https://openitcockpit.io/security/](https://openitcockpit.io/security/)

# License
```
Copyright (C) 2015-2020  it-novum GmbH


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
