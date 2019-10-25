<img src="https://repo.it-novum.com/oitc/oitc2.svg" alt="openITCOCKPIT logo" width="auto" height="100">

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
* Ubuntu Linux 64 bit (16.04 LTS "xenial" and 18.04 LTS "bionic"), Debian Linux 64 bit (9 "stretch")
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

Please execute all commands as user `root` or via `sudo`.

**openITCOCKPIT + Naemon (recommended)**
````
apt-get install apt-transport-https curl
curl https://packages.openitcockpit.com/072783CB.txt | apt-key add
````
If you can not import the key from the keyserver, use this:
````
echo "-----BEGIN PGP PUBLIC KEY BLOCK-----
Version: GnuPG v1

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=J3I+
-----END PGP PUBLIC KEY BLOCK-----
" | apt-key add -
````

Ubuntu 16.04 - Xenial
````
echo 'deb https://packages.openitcockpit.com/repositories/xenial xenial main' > /etc/apt/sources.list.d/openitcockpit.list
````
Ubuntu 18.04 - Bionic
````
echo 'deb https://packages.openitcockpit.com/repositories/bionic bionic main' > /etc/apt/sources.list.d/openitcockpit.list
````

Debian 9 - Stretch
````
echo 'deb https://packages.openitcockpit.com/repositories/stretch stretch main' > /etc/apt/sources.list.d/openitcockpit.list
````
````
apt-get update
apt-get install openitcockpit{,-common,-naemon,-statusengine-naemon,-message}
/usr/share/openitcockpit/app/SETUP.sh
````
**openITCOCKPIT Nagios 4:**

Please execute all commands as user `root` or via `sudo`. 
````
apt-get install apt-transport-https curl
curl https://packages.openitcockpit.com/072783CB.txt | apt-key add
````
If you can not import the key from the keyserver, use this:
````
echo "-----BEGIN PGP PUBLIC KEY BLOCK-----
Version: GnuPG v1

mQINBFhHx0UBEAC8WMQdvfHOVs89RUBjTnpIfPUu6/0WMdw4G7cv7smMtaodZmwlr/nfn3RsMYy/kiQ6/lcdSPoZc4HNGVtWWB6T5hCAnE4/Q9HK2WXc/wzFJZTsb3QPpxtQ8PsZI/pSyIh7C/yR3qAWw5YhVZKBUk7Qnyvsv85eEnFQJkQs6v/XmE/nbL6eWK8PId5b8TWLij8l2iwSdh/CqvQ8QJm89etxqFfCXsWcg1a7rEF3X/y+bFiE8SEjx6tT5e0rJSew9Ebfk2GgqWZTbpwg/69koHwvdmZJn2f5csHyn8vcCPiCHy1UteTt1cyqwQjBn9RFSSNfXZWQT87ykgjVCBZ+b90XbngGgSx9S/+y8U/lJ/0XcnWn2VqgRHyBk3ImfsXA5ACJRJofuZPtTFrmv4LnoS72MzVEQOmZYf2jA7jwCECufdP6XgZ+y8I7I8ccCUEX9KMJPeyFnq7TyLpcacmMt7HdiFbUnahBa6/YSh5n8oGw0jgtXF9UNgmJo8+ny57kO9mERxVrJ+1WIevlr/D+cBhXXRnMgxyv9MbdT7CpCKRIHcsYYVScncOrcfnc53qnQKr3dMlgUzmWk4JeCX8OL1lrAW4Qnt+Shm6x/yhgZd/3Rryj3Ma4fxYZRnEJh5s5DOrn77dqMrTI6iD5RPKpWjaNLIQF+COkEvJCfSoxml7JQwARAQABtDdPbGl2ZXIgTXVlbGxlciAoaXQtbm92dW0pIDxvbGl2ZXIubXVlbGxlckBpdC1ub3Z1bS5jb20+iQI4BBMBAgAiBQJYR8dFAhsDBgsJCAcDAgYVCAIJCgsEFgIDAQIeAQIXgAAKCRCMtCvvByeDy65BD/9cHT/w6sFf98xIuRIGMBgSGWc6I2+WzkZvxfGjWFjJzKSXty8/k9eGy5T9SelGExLXyURDHwQMJ9vcQtK8Lv4xByfhhh3ZJB7Np8qoy0gVUC2U6ubl6jMs2Xqs+i+qiLuRUD4JhbNw8QV4lKwJXKtEDzsqheQ+NTA7YMCVnKSfzwMreoPfy4iGiDOGMw9le5uqDx0iQT/NK/Uans/Zyg+qfXX9KzzwKFQKANBZ/9wf1iip5LhjnJdv4UAgoD3aVakTd3oyS3tq8nAjJcnC/F306BU6Xush8P4S4ngznCgLSKZs7t0dVcPNjnND5FAylr8/8SwZdmJmtRUPEng7eqvLT85K0s5KcFehHfbu+aGfMDOykwQ/RWfcTYFuHLzCRUjGWdISzWTdKMVsKeuy7KjbiR4V4YVFaRyijEn0nX+wKXixFTy9MjwuIsAJFmXgxLPN2/tXlG2r6yjzgcLQNpl+L5K7+l6TduUPHK12JOX6Co8A4SKVGBgF1M3IkGd5qIyXmT5EPqNogtpuzyyeGK0OYidZxHeqlnIrJe7Jlv+mWHYZoLPhxNBmgy62CdfDsDm1947azOTiKm5btH5pGk1iA2KZqHyXC6aIGPtzd+PjdqpinGxgCBKcIB7XPuFltwHMdknOlUrQwA4XJiESPGhhZHuv9WgAhghM/dM0uT6iaLkCDQRYR8dFARAAuqvxALFzqX34/94NbQAQ+r8IbrjAx/Wu0gMWkpnqNLDoNpxkPCJ7uriei8qfT0N7zYIVvD50OE087g4aE4DBdCouMQ/+a7+fB/I4Fyp+dwBf3tKydO/nGMjROGh+jooKLYAJ2AU5nUp+paQjBzjAgffyDjIdaQeMWAIdQZoUWNS58jemSjBVxsfXe5821gTxqHTZzMLSBWfPeLN4Gk1/sbumwMu7hGCb9f74anbNpnl0gpe2twDcinpJN5TOSA+N6FJCMu2qF2auL/eCsyhOo+H6EwyFrILyyxw5Q691Y9xhAU2291/52rTWx3G0jpkHvcTwuNOSdzM5ObELZF8UsD2dxOhdxFQp8YG2mJWb7JrYnvvVnQ44H1Qd8ZO2DtlZ+XaPSBFiym37Wr5swaagS7dCHSpRHNaoezKuD0BK5Fn8zMUMgv+thEhJgb2G/L7NGcOL1+ikvX2OxgiaImZpbzYBfyc33vb1/TwF+tunvbRkmtR5JltGQp9gWjWsNVo3XVP6y1Zu6lwy+r/1l4inQAzWJG10MCjz0pFbF9rE4y9aer0A2xgOlWewpxApSOVIzpjY3dXsdOcEGqO91oflwoys3dMUQCwCVYISkTqYZ2eiWhCwT5EHumQnqKq6YsHYL7b7gJusKxxLkWVrKIMIw4K3fGTy0J463lPucH/8zTUAEQEAAYkCHwQYAQIACQUCWEfHRQIbDAAKCRCMtCvvByeDy7EaD/41FJZAQunJqORkHNvz48lT4XOEqF8La0IsX1JMLRhEdyZoJXGPFtzcjv2XedQTIE0Wc+1rcuDSiPoTtBcIVsYCPc2QFQNK5Tyr6HeS4AxzynIhDOe/K8HIv7LsT/egkgzVXEpBxjDRBfIfDe9Z28XAnAl28OJIcBV2fqE1uxUFIfvfcBAy9CiLyjMEsbEHSMSNHAcLuzesF3KFMc3v80p3lfiJsaKHK0Kr+oGZa+AOwzH/J34tIVh/AOYeT935BKoG1IAk/PFy629vOsQKvXzskPnmugtsH60dIY5/eDxwgpbTHaDQ/8iidvyalgk51qUC9p2LKukfLQTW88uqINAYB518Xb2adYljfoZ1j4IxV2poyRvkpERXkn03UlcwH15DCQ28B0g7xruoparDQPZchW55FEIteYGSmd4HMiqKuIGJcmesQLyYSh855Amk7yteE1XTeP2u3UWYabQquvrjU/N2jYu9L8C8/bhFdShnJz5JXE3wnvgQtbIKbq+w+TGGXxpgi90K9ob+JcktxZkvLMOUIa+LwcTnGrf9pWtkvc8KtpNsmwltn1PR/Sriq28paImwWj6dmwH3SdJH34Of558rq00UzWIsFjVAi4+FSodQQtexiyxYA1ewbP5EmXy4fBXWD9EUpZqESoVuvCDjUqN10kj0CZfy9rApxdYGFLkCDQRYR8d6ARAA2hP/Y/isbqe2ICjB6BxDDggFPPN1Qd73Bamwg4Ccf0l4a0VvCALPTLURevUbl/qlI7TKb2R8MEvokCLdCgvMt9wwmI1/X3q+0kKSSLDR9Zu+4rxgOAZae55+1taQ2K/PsVkKSf4gql3Jx9Ne0rJ0HIfZ+XFM9jWC6Mpg8EHVD9Hq3lH1213mWwEot7znOIrn0oneMN8gm/EW1i3fZSk7ULA6+fEe4A1WPZVMBWKTCWDQClKddO7Y+2tYCgzde/+FL3J2tQIDl2IwWOLtAAMFJQTiJMhS0VPg+xgGjHtw/eUwqFZXHNuGi2XDf+BgertbAqIkYUQ3WRLcmJHvyDb9U+UcMyDZJbUBO7h2Kh0Z4pcErf3aYcuIMeOQoc09zoTgVaScA3Mtg7Zm/UoPVzV1DZoCLFBWf4O6SD/68IiMGIg1Rrow53l1+gSWVY/p6YCHcJLZ6sTcGu+oQ4LwnOqcYtI3enVgRASpe2gRy7dCbsVZ46DI3cFwBsnL7BHovMCgxeuBydQA7jx4GleVDPkgARn7S7ZQRNMEoCajvHSwqnax+OxrjR812Eto1WkkOioXbvANXTT5LEp3Ggz2PTtvWF3+nBEld1259epbRm+LhnTAHHRqvfiJPg7oSefY7tJ/vAlG3Hz0mvJAfdCQCVY1SfdQNFS6rTw3df6ccufibn8AEQEAAYkEPgQYAQIACQUCWEfHegIbAgIpCRCMtCvvByeDy8FdIAQZAQIABgUCWEfHegAKCRAQiJxFEUjajn0HEACvNzHRCLGi4vzmu0U3Rj6q+gfQmcSKj7S7nezmxl3+B6rvzxAkPCtryHMV5uiOU9R4bOZChvx+l0SKf5Re2OI1sarWvhiAP/pN0G6UqpdIQfboEs6o8acE7Cq/DEv6Bf3kZqC/cfDzKq9CjQDzU7QyNilfPVEgM1IwlgOr+GYVjRqEKx1rLUvngQLxS2lHeTj5zV81WZfRc8wlbhMbxSiG+FvixXFBZi/IVRqp7X5DyNyNo6BhShiQE+GUwGKnMgdpe9bdJuKbRoZVip1ZcewULl5nOjFEVACyLiDxgmGG7yw5BbKByfILzNlVXuPZnChg6Bfzt1caBXC9uUehb37jh9HG7lWe0SmmKOJzySOcw83+5+6L8vzT/HVBkBPZa612vqOTcFx4z9qIsJau1gIf79pk4zsynR8JbP8aHX0h077AxNs+uPIP0TRmf/kpQGyByUiU2QQ3ZWUO0qcV94W+SRhKk5X7EF8JPTdy7P6FdjyZroN7XnfCBngMll+D2DjvM5P1MuGvlfcyV0vP0WRvhOGfkW48JogRUtiORCyv5mt/jikqFRhPOzQoaA2PsnRKusOwwZVBMdPGGCoNyOBfnK3PI/r+iSlY1U1xwgb3AP/0zQLVsU4jFEWAzwQEqW4a/RqxgLwkSL0HnPjxkI5UIJlmRPAXDYdf2nIdx4MpH5oAEACRVABHoj0qrXkwHMy/FUVqMrieX+TuqAkyTTKbGfGpVwgj6/3NV35HEvUOWfGqZch6wpJ4pDT8z590NNCkNLugsxYuEroJFHJ7Y0XI7Og9e7mk7DtvcGK2MKKD5Gn4unTkr9D5kyV9X7kKwGlGxJrMGtoMkN9NFPJEKKv3JWLeuqwKLVWWB+A48afyVKiEXaKNUGRn7VrvyiwIAtnIObfNHFtyqZhj6LPgwYX3nh91lG0J7jap+NQDMiELIH0VWElfBB3sWUAPcWzfENcD/a43NKjCOd8Po3cMBpQGnq1Ih5u6iXICWe0POhywEVvEO40u9FGDg6XIAHsoVUkAgcIBV+BKDRwb8D07KY7dPIbcj360a9LxcZ9sK9oW0VGfHUBSegePKNpiTDGVMaKDGbhS3SjAipmdk+aNXO5etSnbFoz90nyzaPBFRULu9GTxne+/RFWXQHS5sIatd8Elmst/E9ex8Zs4JXnqZl2vzqFPA2Se0PekzS3Oi3/k3Dq9hjAclV9rZnOD5rSVq3B92tEDVRVq3lfUARgXfH3rH99ESDEVwuyNU7gfruE3Av6YranqEvJlFF5CUWSBdYSuzDpPAzDOnYYz9033BdACFi/iPdF4w0xcJ229MirHAAUIIyp9BQIEJSJqQxxdtlFkUeiH53RZ7eupN4/K2moVo81MKw===J3I+
-----END PGP PUBLIC KEY BLOCK-----
" | apt-key add -
````

Ubuntu 16.04 - Xenial
````
echo 'deb https://packages.openitcockpit.com/repositories/xenial xenial main' > /etc/apt/sources.list.d/openitcockpit.list
````
Ubuntu 18.04 - Bionic
````
echo 'deb https://packages.openitcockpit.com/repositories/bionic bionic main' > /etc/apt/sources.list.d/openitcockpit.list
````

Debian 9 - Stretch
````
echo 'deb https://packages.openitcockpit.com/repositories/stretch stretch main' > /etc/apt/sources.list.d/openitcockpit.list
````
````
apt-get update
apt-get install openitcockpit{,-common,-nagios,-statusengine-nagios,-message}
/usr/share/openitcockpit/app/SETUP.sh
````

# Register openitcockpit community version:
You can register your openITCOCKPIT installation to get an access to free community modules.

**That's how it works**

Login to the webinterface of openITCOCKPIT and navigate to Administration -> Registration, enter the community license key
`0dc0d951-e34e-43d0-a5a5-a690738e6a49` and click Register.
After successful registration you can install the free community modules at Administration -> Package Manager

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
