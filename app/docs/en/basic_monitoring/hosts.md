[//]: # (Links)
[Host]: /hosts "Host"
[configure]: #configure "Configure your host"
[adding]: /hosts/add (add a new contact)
[host states]: #states (add a new contact)
[not monitored]: /host/notMonitored (Listing of not monitored hosts)
[monitored]: /host/index (Listing of monitored hosts)
[disabled]: /host/disabled (Listing of disabled hosts)
[deleted]: /deleted_hosts/index (Listing of deleted hosts)
[refresh]: /exports/index (Refresh your configuration)
[notifications]: /documentations/wiki/basic-monitoring/notifications/en (Notification documentation)
[check_MK]: /documentations/wiki/discovery/mk_checks/en (Check_MK documentation)
[downtime]: /documentations/wiki/basic-monitoring/downtimes/en (Downtime documentation)
[extended service view]: /documentations/wiki/basic-monitoring/servicservicen#extended (Extended service view)
[host template]: /documentations/wiki/basic-monitoring/hosttemplates/en (Host templates documentation)

[//]: # (Pictures)

[//]: # (Content)

## What is a host?

A host is a device connected to a network.

## Tab overview

On the top of your host list you will find four tabs.
Each tab contains a listing of your hosts in a special status.

In the
**<i class="fa fa-stethoscope"></i> Monitored**
tab you find all the [currently monitored hosts][monitored].

In the
**<i class="fa fa-user-md"></i> Not monitored**
tab you find all the hosts that are not written in you Nagios configuration and therefor [not monitored].
To change the not monitored status into monitored you just need to [refresh your monitoring configuration][refresh].

In the
**<i class="fa fa-power-off"></i> Disabled**
tab you find all the hosts that are [currently disabled][disabled] from the monitoring.

In the
**<i class="fa fa-trash-o"></i> Deleted**
tab you find all the [deleted hosts][deleted].

## What can I customize the columns of the listing?

To customize your listing by enable or disable a column you just click on
<i class="fa fa-lg fa-table"></i>
and then on the desired column you want to enable or disable.

Available columns:
* **Edit** - Contains the edit buttons. (<i class="fa fa-gear"></i>)
* **Acknowledgment** - Contains acknowledgment. (<i class="fa fa-user"></i>)
* **In downtime** - Contains current downtime. (<i class="fa fa-power-off"></i>)
* **Graph** - Contains link to the service list if one of your services has a graph. (<i class="fa fa-area-chart"></i>)
* **Passive** - Contains a P if it is a passively checked host. (**P**)
* **Host name** - Name of the host. You can sort the host names ascending or descending.
* **IP-Address** - IP of the host. You can sort the IP addresses ascending or descending.
* **State since** - Time since last state change. You can sort the time since the last state change ascending or descending.
* **Last check** - Date and time of the last check. You can sort the date and time of last check ascending or descending.
* **Output** - Last check output. You can sort the output alphabetically ascending or descending.
* **Instance** - Who checks this host. You can sort the instances alphabetically ascending or descending.

## How can I group hosts?

Select a few hosts in the listing, then scroll down and click on
<a class="btn btn-default btn-xs">More</a>
or
<a class="btn btn-default btn-xs"><span class="caret"></span></a>
and then on
<a class="btn btn-default btn-xs"><i class="fa fa-sitemap"></i> Add to host group</a>.

On the page that appears you can choose to append the hosts to an existing group or create a new one.

To append the hosts leave the **Create host group** switch off and choose a group below to append the hosts.

To create a new group, tick the **Create host group** switch on and choose a container and a group name to create a new host group.

Click on <a class="btn btn-xs btn-primary">Save</a> to append the hosts to an existing or new group.

Click on <a class="btn btn-xs btn-default">Cancel</a> if you want to discard your changes.

## How can I edit more then a host at a time?

You can edit multiple hosts but not in the detail if you only edit one host.

Select a few hosts in the listing, then scroll down and click on
<a class="btn btn-default btn-xs">More</a>
or
<a class="btn btn-default btn-xs"><span class="caret"></span></a>
and then on
<a class="btn btn-default btn-xs"><i class="fa fa-cog"></i> Edit details</a>.

On the page that appears you can edit the following:

* Description - Replaces the current description.
* Contacts - Replaces the current contacts or append new contacts.
* Contact groups - Replaces the current contact groups or append new contact groups.
* Host URL - Replaces the current host URL.
* Tags - Replaces the current tags or append new tags.

To edit anything you have to check the **Edit $option** box.
To append contacts, contact groups or tags to the current settings, check **Keep existing**.

Click on <a class="btn btn-xs btn-primary">Save</a> to save the new settings to the hosts.

Click on <a class="btn btn-xs btn-default">Cancel</a> if you want to discard your changes.

## How can I disable multiple hosts?

Select a few hosts in the listing, then scroll down and click on
<a class="btn btn-default btn-xs">More</a>
or
<a class="btn btn-default btn-xs"><span class="caret"></span></a>
and then on
<a class="btn btn-default btn-xs"><i class="fa fa-plug"></i> Disable</a>.

Then a pop up dialog asks you if you really want to disable the hosts,
confirm to disable them.

## How can I get the listing as PDF?

Scroll down and click on
<a class="btn btn-default btn-xs">More</a>
or
<a class="btn btn-default btn-xs"><span class="caret"></span></a>
and then on
<a class="btn btn-default btn-xs"><i class="fa fa-file-pdf-o"></i> List as PDF</a>.

Your server creates a PDF from the listing which your browser opens or downloads instantly.

## How can I reset the check time for multiple hosts?

Select a few hosts in the listing, then scroll down and click on
<a class="btn btn-default btn-xs">More</a>
or
<a class="btn btn-default btn-xs"><span class="caret"></span></a>
and then on
<a class="btn btn-default btn-xs"><i class="fa fa-refresh"></i> Reset check time</a>.

Then a pop up dialog asks you if you want to reset the check time for the hosts only or their services too.

Click on <a class="btn btn-xs btn-success">Send</a> to reset the check time.

Click on <a class="btn btn-xs btn-default">Cancel</a> if you want to discard your changes.

## How can I set a downtime for multiple hosts?

Select a few hosts in the listing, then scroll down and click on
<a class="btn btn-default btn-xs">More</a>
or
<a class="btn btn-default btn-xs"><span class="caret"></span></a>
and then on
<a class="btn btn-default btn-xs"><i class="fa fa-clock-o"></i> Set planned maintenance times</a>

To learn more about the downtime configuration, click [here][downtime].

## How can I acknowledge the host states of multiple hosts?

Select a few hosts in the listing, then scroll down and click on
<a class="btn btn-default btn-xs">More</a>
or
<a class="btn btn-default btn-xs"><span class="caret"></span></a>
and then on
<a class="btn btn-default btn-xs"><i class="fa fa-user"></i> Acknowledge host status</a>.

Then a pop up dialog asks you if you want to acknowledge the host status for the hosts only or their services too.
If you make your acknowledgment sticky,
it is still acknowledged after another negative state change has occurred.
You can leave a comment to make your work more transparent.

Click on <a class="btn btn-xs btn-success">Send</a> to acknowledge the chosen.

Click on <a class="btn btn-xs btn-default">Cancel</a> if you want to discard your changes.

## How can I temporary disable notification for multiple hosts?

Select a few hosts in the listing, then scroll down and click on
<a class="btn btn-default btn-xs">More</a>
or
<a class="btn btn-default btn-xs"><span class="caret"></span></a>
and then on
<a class="btn btn-default btn-xs"><i class="fa fa-envelope-o"></i> Disable notification</a>.

Then a pop up dialog asks you if you want to temporary disable the notifications for the hosts only or their services too.

Click on <a class="btn btn-xs btn-success">Send</a> to temporary disable the notifications for the chosen.

Click on <a class="btn btn-xs btn-default">Cancel</a> if you want to discard your changes.

## How can I temporary enable notification for multiple hosts?

Select a few hosts in the listing, then scroll down and click on
<a class="btn btn-default btn-xs">More</a>
or
<a class="btn btn-default btn-xs"><span class="caret"></span></a>
and then on
<a class="btn btn-default btn-xs"><i class="fa fa-envelope"></i> Enable notification</a>.

Then a pop up dialog asks you if you want to temporary enable the notifications for the hosts only or their services too.

Click on <a class="btn btn-xs btn-success">Send</a> to temporary enable the notifications for the chosen.

Click on <a class="btn btn-xs btn-default">Cancel</a> if you want to discard your changes.

## How to filter a host?

You search for a special host? Just click on
<a class="btn btn-xs btn-primary"><i class="fa fa-search"></i> Search</a>.
Here you can search a host by its
name, IP or output and
filter which state it has - up, down or unreachable.

Click on <a class="btn btn-xs btn-primary">Filter</a> to filter the current listing.

Click on <a class="btn btn-default btn-xs ">Reset filter</a> to remove the current filter.

## How can I edit a host?

To edit a host either click on
<a class="btn btn-default btn-xs">&nbsp;<i class="fa fa-cog"></i>&nbsp;</a>
or on
<a class="btn btn-default btn-xs"><span class="caret"></span></a>
and then on
<a class="btn btn-default btn-xs"><i class="fa fa-cog"></i> Edit</a>
to go to the edit view.

To learn more about the configuration click [here][configure].

Click on <a class="btn btn-xs btn-primary">Save</a> to save your existing host.

Click on <a class="btn btn-xs btn-default">Cancel</a> if you want to discard your changes.

## How can I get an overview over the services of a host?

Click on
<a class="btn btn-default btn-xs"><span class="caret"></span></a>
and then on
<a class="btn btn-default btn-xs"><i class="fa fa-list"></i> Service list</a>
to go to the service list of the host.

## How can I create a new host?

Click on
<a class="btn btn-xs btn-success"><i class="fa fa-plus"></i> New</a>
in the upper right corner.

On the [page][adding] that appears you can configure your user.
To learn more about the configuration click [here][configure].

Click on <a class="btn btn-xs btn-primary">Save</a> to create your new host.

Click on <a class="btn btn-xs btn-default">Cancel</a> if you want to discard your changes.

## How can I delete a host?

To delete a host either click on
<a class="btn btn-default btn-xs"><span class="caret"></span></a>
and then on
<a class="btn btn-xs btn-default txt-color-red"><i class="fa fa-trash-o"></i> Delete</a>
or go to the edit view and click in the upper right corner on
<a class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i> Delete</a>.

At deletion a window will pop up asking if you really want to delete it,
confirm to delete.

## Extended host overview <span id="extended"></span>

When you click on a hostname in your host list,
you will forward to extended host overview.
The extended host overview
gives you overall informations about the host.

#### Button overview

* <a class="btn btn-default btn-xs"><i class="fa fa-refresh"></i></a> Refresh the host page.

* <a class="btn btn-default btn-xs"><i class="fa fa-book"></i></a> Write a documentation about the host.

* <a class="btn btn-default btn-xs"><i class="fa fa-envelope "></i></a> See the [notifications] of the host in a specific timespan.

* <a class="btn btn-default btn-xs"><i class="fa fa-check-square-o"></i></a> Get an overview of the check history in a specific timespan.

* <a class="btn btn-default btn-xs"><i class="fa fa-history"></i></a> Get an overview of the state history in a specific timespan.

* <a class="btn btn-default btn-xs"><i class="fa fa-user"></i></a> A forward to the overview of your acknowledgments in a specific timespan.

* <a class="btn btn-default btn-xs"><i class="fa fa-cog"></i></a> Edit the settings of your host.

* <a class="btn btn-default btn-xs">More</a> Contains the four following options:

 * <a class="btn btn-default btn-xs"><i class="fa fa-list"></i> Service list</a> Opes the service list of the host.

 * <a class="btn btn-default btn-xs"><i class="fa fa-qrcode"></i> Scan code</a> Creates a QR-code containing the link to this page.

 * <a class="btn btn-default btn-xs"><i class="fa fa-wifi"></i> Ping</a> Pings the host in a pop-up terminal.

 * <a class="btn btn-default btn-xs">Check_MK discovery</a> Makes a [check_MK] scan via remote or SNMP discovery.

#### <i class="fa fa-info"></i> Status information
General informations about the host status.

Status information displays the following informations:
* **Host name** - The name of the host.
* **Available since** - Last downtime
* **Last check** - Time, date and hard or soft state
* **Current state** - Up, down or unreachable
* **Flap detection** - On or off
* **Check attempt** - Current_check_number / maximum_number_of_check_attempts
* **Command name** - Name of the check command.
* **Command line** - Display the command line that Nagios executed.
* **Next check in** - Date
* **Output** - Last check output from Nagios.
* **Long output** - Such as errors.

#### <i class="fa fa-hdd-o"></i> Device information
Informations about the device

Device information displays the following informations:
* **Check period** - Chosen period in which the check command can execute.
* **UUID** - Displays the UUID (Universally Unique Identifier) of the host.
* **IP address** of your host.
* **Description** - The host description.

#### <i class="fa fa-envelope-o"></i> Notification information
Informations how the host notifies.

Notification information displays the following informations:
* **Notification period** - Chosen notification schedule.
* **Notification interval** - Chosen period in which a notification is sent.
* **Notification occurs in teh following cases** - Lists the chosen notifications options of the host.
* **The following contacts are notified** - Show which contacts to notify and which contacts inherited from the template.

#### <i class="fa fa-desktop"></i> Host commands
Control commands of the host.

**<i class="fa fa-refresh"></i> Reset check time** - Force an instant check.

**<i class="fa fa-download"></i> Passive transfer of check result** - Trigger passive check with a defined result.

**<i class="fa fa-clock-o"></i> Set planned maintenance times** - Set a period how long the host stays in maintenance mode.

**<i class="fa fa-adjust"></i> Enables/disables flap detection for a particular host** - This option is only temporary. It does not affect your configuration. This is an external command and only saved in the memory of your monitoring engine.

**<i class="fa fa-envelope-o"></i> Enables/disables notifications** - This option is only temporary. It does not affect your configuration. This is an external command and only saved in the memory of your monitoring engine.

**<i class="fa fa-envelope"></i> Send custom host notification**

#### Services

At the bottom of the page you see a listing of all the services from the current host.
Here you can search for a service.
If you hover over
<i class="fa fa-area-chart"></i>,
a pop up graph appears.
Click on the name of the service to go to its [extended service view].

## What states can a host have? <span id="states"></span>

* Up - A host is reachable.
* Down - Connection timeout.
* Recovery - A host gets reachable again.
* Unreachable - No connection to host.
* Flapping - The status of the host changed rapidly.
* Downtime - In a downtime notifications are not sent out by the host.

## How is a host configured? <span id="configure"></span>

#### Basic configuration

**Container** - Grand access from the chosen container to the host. (required)

**Host templates** - Choose a [host template] to set up your host with the template defaults. (required)

**Host name** - Defines the name of your host.
	Enter the IP of your host to automatically detect its host name.

**Description** - Describe your host.

**Address** - Enter the IP of your host or its FQDN. (required)

**Host groups** - Choose the host groups the host is in.

**Parent hosts** - Choose parents of your host.
	The host will be unreachable without a host check if all parents are unreachable.

**Notes** - Notes for the host.

**Host URL** - Here you can type in the host URL.

**Priority** - Request handling priority. A higher priority equals a quicker result.

**Tags** - You can tag your host such as "Router", "> 20 TFlops" or "Ubuntu LTS 14.04".

**Instance** - Choose here between your master system or one of your satellites.

**Notification period** - Specify the notification schedule.

**Notification interval** - Specify the time before sending the next notification. (required)

**Notification options** - Choose here the states to get notified.

Tick **Enable active checks** if the master system and not a satellite, checks the host.

**Contact** - Choose the contacts who get notified about the host status.

**Contact groups** - Choose groups which contact get notified about the host status.

#### Expert settings

**Check command** - The check command executes at a host check.
	Do not forget to set the right parameters for the command, if any.

**Check period** - Choose the period in which the check command can execute.

**Max. number of check attempts** - If a non-up-state occurs on a check,
	Nagios attempts to check the host for an up state
	until it reaches the maximum number of check attempts.
	If no up state has occurred,
	Nagios notifies the contacts about the problem.

**Check interval** - The interval the check command executes. (required)

**Retry interval** - Set the interval of a recheck to verify the non-up state. (required)

**Flap detection** - If you want to detect rapid state changes switch the detection on.
	Below you can switch on the flapping detection for **up**, **down** and **unreachable**.

If the check command has **arguments**, you can use macros.
Click on
<a class="btn btn-xs btn-success"><i class="fa fa-plus"></i> Add new macro</a>
and the name of the macro and a text field appear where you can set your macro.
At execution the macro content replaces **$_HOST´name´$** in the command arguments.
If you want to delete an argument,
click on
<a class="btn btn-default btn-xs txt-color-red"><i class="fa fa-trash-o"></i></a>.

