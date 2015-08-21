[//]: # (Links)
[external commands]: /nagios_module/cmd "External Commands"
[api key]: /systemsettings/index (Go to your system settings under SUDO_SERVER)
[hosts]: /hosts/index (Go to your hosts)
[services]: /services/index (Go to your services)

[//]: # (Pictures)

[//]: # (Content)

## What are external commands?

In some cases you need to send an external command
to the monitoring engine
for transmitting a passive service state or something similar.
The [interface][external command] will help you create your own external commands
by generating an example to explain of the supported command you want to use.
The example will easily show you the API-structure for usage.

## How do I use the examples?

You get two links to use the command externally from your application with the API call.
One link contains the default parameters if any,
the other contains no default parameters,
this means you have to replace them too.

In both links you have to replace the **API_KEY** and every parameter that is **$required**.

## Where do I find what is needed?

#### Directions to obtain your API_KEY

You can find your API_KEY under SUDO_SERVER in your [configuration][api key].

#### Directions to obtain the right host UUID

First go to your [hosts], where click on the name of your chosen host.
On the detailed host page you click the
<span class="btn btn-default btn-xs"><i class="fa fa-lg fa-hdd-o"></i> Device information</span>
tab.
In this tab you will find your hosts UUID.


#### Directions to obtain the right service UUID

First go to your [services], where click on the name of your chosen service.
On the detailed service page you click the
<span class="btn btn-default btn-xs"><i class="fa fa-lg fa-hdd-o"></i> Device information</span>
tab.
In this tab you will find your services UUID.
