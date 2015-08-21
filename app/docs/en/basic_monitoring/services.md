[//]: # (Links)
[Service]: /services "Service"
[configure]: #configure "Configure your service"
[adding]: /services/add (add a new contact)
[service states]: #states (add a new contact)
[not monitored]: /service/notMonitored (Listing of not monitored services)
[monitored]: /service/index (Listing of monitored services)
[disabled]: /service/disabled (Listing of disabled services)
[refresh]: /exports/index (Refresh your configuration)
[notifications]: /documentations/wiki/basic-monitoring/notifications/en (Notification documentation)
[check_MK]: /documentations/wiki/discovery/mk_checks/en (Check_MK documentation)
[downtime]: /documentations/wiki/basic-monitoring/downtimes/en (Downtime documentation)
[extended host overview]: /documentations/wiki/basic-monitoring/hosts/en#extended (Extended host overview)
[graph move]: /documentations/wiki/basic-monitoring/graphgenerator/en#move-around (Graph movement)
[service template]: /documentations/wiki/basic-monitoring/servicetemplates/en (Service templates documentation)

[//]: # (Pictures)

[//]: # (Content)

## What is a service?

A service is command with which you retrieve informations from a host.

## Tab overview

On the top of your service list you will find four tabs.
Each tab contains a listing of your services in a special status.

In the
**<i class="fa fa-stethoscope"></i> Monitored**
tab you find all the [currently monitored services][monitored].

In the
**<i class="fa fa-user-md"></i> Not monitored**
tab you find all the services that are not written in you Nagios configuration and therefor [not monitored].
To change the not monitored status into monitored you just need to [refresh your monitoring configuration][refresh].

In the
**<i class="fa fa-power-off"></i> Disabled**
tab you find all the services that are [currently disabled][disabled] from the monitoring.

## How can I get the listing as PDF?

Scroll down and click on
<a class="btn btn-default btn-xs">More</a>
or
<a class="btn btn-default btn-xs"><span class="caret"></span></a>
and then on
<a class="btn btn-default btn-xs"><i class="fa fa-file-pdf-o"></i> List as PDF</a>.

Your server creates a PDF from the listing which your browser opens or downloads instantly.

## How can I reset the check time for multiple services?

Select a few services in the listing, then scroll down and click on
<a class="btn btn-default btn-xs">More</a>
or
<a class="btn btn-default btn-xs"><span class="caret"></span></a>
and then on
<a class="btn btn-default btn-xs"><i class="fa fa-refresh"></i> Reset check time</a>.

Confirm the pop up to reset the check times of the selected services.

## How can I temporary disable notification for multiple services?

Select a few services in the listing, then scroll down and click on
<a class="btn btn-default btn-xs">More</a>
or
<a class="btn btn-default btn-xs"><span class="caret"></span></a>
and then on
<a class="btn btn-default btn-xs"><i class="fa fa-envelope-o"></i> Disable notification</a>.

Confirm the pop up to temporary disable the notifications for the selected services.

## How can I temporary enable notification for multiple services?

Select a few services in the listing, then scroll down and click on
<a class="btn btn-default btn-xs">More</a>
or
<a class="btn btn-default btn-xs"><span class="caret"></span></a>
and then on
<a class="btn btn-default btn-xs"><i class="fa fa-envelope"></i> Enable notification</a>.

Confirm the pop up to temporary enable the notifications for the selected services.

## How to filter a service?

You search for a special service? Just click on
<a class="btn btn-xs btn-primary"><i class="fa fa-search"></i> Search</a>.
Here you can search a service by its
host name, service name or output and
filter which state it has - ok, warning, critical, unknown.

Click on <a class="btn btn-xs btn-primary">Filter</a> to filter the current listing.

Click on <a class="btn btn-default btn-xs ">Reset filter</a> to remove the current filter.

## How can I edit a service?

To edit a service either click on
<a class="btn btn-default btn-xs">&nbsp;<i class="fa fa-cog"></i>&nbsp;</a>
or on
<a class="btn btn-default btn-xs"><span class="caret"></span></a>
and then on
<a class="btn btn-default btn-xs"><i class="fa fa-cog"></i> Edit</a>
to go to the edit view.

To learn more about the configuration click [here][configure].

Click on <a class="btn btn-xs btn-primary">Save</a> to save your existing service.

Click on <a class="btn btn-xs btn-default">Cancel</a> if you want to discard your changes.

## How can I get an overview over the services of a host?

In the row of the host name click on
<i class="fa fa-list" title="Go to Service list"></i>
at the right,
to go to the service list of the host.

## How can I create a new service?

Click on
<a class="btn btn-xs btn-success"><i class="fa fa-plus"></i> New</a>
in the upper right corner.

On the [page][adding] that appears you can configure your user.
To learn more about the configuration click [here][configure].

Click on <a class="btn btn-xs btn-primary">Save</a> to create your new service.

Click on <a class="btn btn-xs btn-default">Cancel</a> if you want to discard your changes.

## How can I delete a service?

To delete a service either click on
<a class="btn btn-default btn-xs"><span class="caret"></span></a>
and then on
<a class="btn btn-xs btn-default txt-color-red"><i class="fa fa-trash-o"></i> Delete</a>
or go to the edit view and click in the upper right corner on
<a class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i> Delete</a>.

At deletion a window will pop up asking if you really want to delete it,
confirm to delete.

## How can I go to the extended host overview?

Click on the host name to go to the [extended host overview].

## Extended service overview <span id="extended"></span>

When you click on a service name in your service list,
you will forward to extended service overview.
The extended service overview
gives you overall informations about the service.

#### Button overview

* <a class="btn btn-default btn-xs"><i class="fa fa-refresh"></i></a> Refresh the service page.

* <a class="btn btn-default btn-xs"><i class="fa fa-envelope "></i></a> See the [notifications] of the service in a specific timespan.

* <a class="btn btn-default btn-xs"><i class="fa fa-check-square-o"></i></a> Get an overview of the check history in a specific timespan.

* <a class="btn btn-default btn-xs"><i class="fa fa-history"></i></a> Get an overview of the state history in a specific timespan.

* <a class="btn btn-default btn-xs"><i class="fa fa-user"></i></a> A forward to the overview of your acknowledgments in a specific timespan.

* <a class="btn btn-default btn-xs"><i class="fa fa-cog"></i></a> Edit the host.

* <a class="btn btn-default btn-xs"><i class="fa fa-cogs"></i></a> Edit the service.

* <a class="btn btn-default btn-xs">More</a> Contains the four following options:

 * <a class="btn btn-default btn-xs"><i class="fa fa-area-chart"></i> Grapher</a> Get an overview by looking at graphs in different timespans (4 hours, 25 hours, 7 days, 1 month, 1 year).

 * <a class="btn btn-default btn-xs"><i class="fa fa-qrcode"></i> Scan code</a> Creates a QR-code containing the link to this page.

#### <i class="fa fa-info"></i> Status information
General informations about the service status.

Status information displays the following informations:
* **Last state change** - Time and date of the last state change.
* **Last check** - Time, date and hard or soft state.
* **Current state** - Ok, warning, recovery, unknown or critical
* **Flap detection** - On or off
* **Check attempt** - Current_check_number / maximum_number_of_check_attempts
* **Command name** - Name of the check command.
* **Command line** - Display the command line that Nagios executed.
* **Next check in** - Date
* **Output** - Last check output from Nagios.
* **Preformance data** - Output of the ram usage at execution.
* **Long output** - Such as errors.

#### <i class="fa fa-hdd-o"></i> Device information
Informations about the device

Device information displays the following informations:
* **Current check attempt** - Current check number
* **Maximum attempts per check** - Maximum number of check attempts
* **Check interval** - Chosen period in which the check command can execute.
* **Check interval in case of error** - Chosen period in which the check command can execute.
* **UUID** - Displays the UUID (Universally Unique Identifier) of the service.
* **Description** - The service description.
* **Notes** - The service notes.

#### <i class="fa fa-envelope-o"></i> Notification information
Informations how the service notifies.

Notification information displays the following informations:
* **Notification period** - Chosen notification schedule.
* **Notification interval** - Chosen period in which a notification is sent.
* **Notification occurs in teh following cases** - Lists the chosen notifications options of the service.
* **The following contacts are notified** - Show which contacts to notify and which contacts inherited from the template.

#### <i class="fa fa-desktop"></i> Service commands
Control commands of the service.

**<i class="fa fa-refresh"></i> Reset check time** - Force an instant check.

**<i class="fa fa-download"></i> Passive transfer of check result** - Trigger passive check with a defined result.

**<i class="fa fa-clock-o"></i> Set planned maintenance times** - Set a period how long the service stays in maintenance mode.

**<i class="fa fa-adjust"></i> Enables/disables flap detection for a particular service** - This option is only temporary. It does not affect your configuration. This is an external command and only saved in the memory of your monitoring engine.

**<i class="fa fa-envelope-o"></i> Enables/disables notifications** - This option is only temporary. It does not affect your configuration. This is an external command and only saved in the memory of your monitoring engine.

**<i class="fa fa-envelope"></i> Send custom service notification**

#### Services graphs

At the bottom of the page you see a service graph from your current service.
To read more about the movement in the graph, click [here][graph move].

## What states can a service have? <span id="states"></span>

+ Ok - Service executed successfully.
+ Warning - Service returns with warnings.
* Recovery - Service returns ok-state again.
+ Unknown - Service returns nothing.
+ Critical - Service returns with errors.
* Flapping - The status of the service changed rapidly.
* Downtime - In a downtime notifications are not sent out by the service.


## How is a service configured? <span id="configure"></span>

#### Basic configuration

**Host** - Bind the service to the chosen host. (required)

**Service templates** - Choose a [service template] to set up your service with the template defaults. (required)

**Name** - Choose a reasonable name.

**Description** - Describe your service.

**Service groups** - Choose the service groups the service is in.

**Notes** - Notes for the service.

**Service URL** - Here you can type in the service URL.

**Priority** - Request handling priority. A higher priority equals a quicker result.

**Notification period** - Specify the notification schedule.

**Notification interval** - Specify the time before sending the next notification. (required)

**Notification options** - Choose here the states to get notified.

Tick **Enable graph** to automatically create a graph from the collected service data.

Tick **Enable active checks** if the master system and not a satellite, checks the service.

**Contact** - Choose the contacts who get notified about the service status.

**Contact groups** - Choose groups which contact get notified about the service status.

#### Expert settings

**Check command** - The check command executes at a service check.
	Do not forget to set the right parameters for the command, if any.

**Check period** - Choose the period in which the check command can execute.

**Check interval** - The interval the check command executes. (required)

**Retry interval** - Set the interval of a recheck to verify the non-up state. (required)

**Tags** - You can tag your host such as "Router", "> 20 TFlops" or "Ubuntu LTS 14.04".

**Flap detection** - If you want to detect rapid state changes switch the detection on.
	Below you can switch on the flapping detection for **ok**, **warning**, **unknown** and **critical**.

Tick **status volatile** to create a volatile service.
A volatile service always notifies its contacts
and executes the event handler
if the service is not ok after a check.

Tick **freshness checks enabled** to automatically execute the service check from the master system again after the **freshness threshold** time has run out.

**Event handler** - The event handler command executes on each state change.

If a command has **arguments**, you can use macros.
Click on
<a class="btn btn-xs btn-success"><i class="fa fa-plus"></i> Add new macro</a>
and the name of the macro and a text field appear where you can set your macro.
At execution the macro content replaces **$_service´name´$** in the command arguments.
If you want to delete an argument,
click on
<a class="btn btn-default btn-xs txt-color-red"><i class="fa fa-trash-o"></i></a>.

