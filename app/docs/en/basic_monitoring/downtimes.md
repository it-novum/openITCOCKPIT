[//]: # (Links)
[host]: /downtimes/host (Host downtimes)
[service]: /downtimes/service (Service downtimes)
[recurring]: /systemdowntimes (Recurring downtimes)
[configure]: #configure (Configure your automap)
[add host]: /systemdowntimes/addHostdowntime (Add a new host downtime)
[add host group]: /systemdowntimes/addHostgroupdowntime (Add a new host group downtime)
[add service]: /systemdowntimes/addServicedowntime (Add a new service downtime)

[//]: # (Pictures)

[//]: # (Content)

## What can I do with a downtime?

You can configure a downtime for hosts and services such as for a planned maintenance.
In the time of the maintenance the hosts or services are not tracked by your monitoring system.

## How can I filter downtimes?

You always can filter your downtimes by search terms by searching through the downtimes.

If your downtimes are not recurring, you filter them by a timespan automatically.
The timespan is set by default from today to one week ahead.
If you changed the timespan, click on
<a class="btn btn-xs btn-success toggle hidden-mobile"><i class="fa fa-check"></i> Apply</a>.

## How can I add a downtime?

Click on
<a class="btn btn-xs btn-success"><i class="fa fa-plus"></i> Create downtime</a>
in the upper right corner and then choose one of the following:

* **Create host downtime**
* **Create hostgroup downtime**
* **Create service downtime**

On the [host][add host], [host group][add hostgroup] or [service][add service] page that appears you can configure the downtime.
To learn more about the configuration click [here][configure].

Click on <a class="btn btn-xs btn-primary">Save</a> to add your new downtime.

Click on <a class="btn btn-xs btn-default">Cancel</a> if you want to discard your changes.

## How can I delete a downtime?

Click on
<a class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i> Delete</a>
in the same row as the downtime you want to delete.

A window will pop up asking if you really want to delete it,
confirm to delete.

## How do I configure a downtime? <span id="configure"></span>

##### Distinct host downtime options

**Host** - Choose the involved hosts.

**Maintenance period for** - Select how the downtime inherits.
You can choose the following options:

* **Individual host** - Downtime only for the hosts
* **Host including services** - Downtime for the hosts and their services
* **Host and dependent Hosts (triggered)** - Downtime for hosts and the host that depend on them
* **Host and dependent Hosts (non-triggered)**

##### Distinct host group downtime options

**Hostgroup** - Choose the involved host groups.

**Maintenance period for** - only for hosts and host groups
You can choose the following options:

* **Host only** - Downtime only for the hosts
* **Host including services** - Downtime for the hosts and their services

##### Distinct service downtime options

**Service** - Choose the involved services.

##### Downtime options

**Comment** - Choose a reasonable comment. (required)

Now you can choose if the downtime is a recurring event or one time only.

If its only occurring once you set up the timespan in that it occurs.

If it is a recurring downtime, check the recurring downtime switch to on.
Then you can choose on which weekdays, days of month and in what timespan it occurs.
