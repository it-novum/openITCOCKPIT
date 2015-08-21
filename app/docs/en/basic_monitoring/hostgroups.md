[//]: # (Links)
[Host groups]: /hostgroups "host groups"
[configure]: #configure "Configure your host groups"
[adding]: /hostgroups/add (add a new host group)
[downtimes]: /documentations/wiki/basic-monitoring/downtimes/en#configure (Configure your downtimes)

[//]: # (Pictures)

[//]: # (Content)

## What are host groups?

[Host groups] contain a group of hosts.
You can assign all hosts to an action by only choosing the group.

## How can I create a new host group in default and extended view?

Click on
<a class="btn btn-xs btn-success"><i class="fa fa-plus"></i> New</a>
in the upper right corner.

On the [page][adding] that appears you can configure your hostgroup.
To learn more about the configuration click [here][configure].

Click on <a class="btn btn-xs btn-primary">Save</a> to create your new host group.

Click on <a class="btn btn-xs btn-default">Cancel</a> if you want to discard your changes.

## How do I use the extended overview?

First click on
<a class="btn btn-default btn-xs"><i class="fa fa-plus-square"></i> Extended overview</a>
to open the extended overview.
The extended overview will show you all hosts and status of all used services by the hosts in your chosen host group.
The extended view will also summarize the states 'up', 'down' and 'unreachable'.
You can also search for a host and search how long it has been since the last status change and search for the last or next host check.

With a click on
<a class="btn btn-default btn-xs">&nbsp;<i class="fa fa-cog"></i>&nbsp;</a>
you can see options you can choose to manage your host group.

You can choose

* <a class="btn btn-default btn-xs"><i class="fa fa-refresh"></i> Reset check time</a>
	to reset the check time for the hosts of the group or the hosts and their services.

* <a class="btn btn-default btn-xs"><i class="fa fa-clock-o"></i> Set planned maintenance times</a>
	will let you create a downtime for your host group.
	If you want to know more about how to configure a downtime, click [here][downtimes].

* <a class="btn btn-default btn-xs"><i class="fa fa-user"></i> Acknowledge host status</a>
	for the host group only or for the host group and their services.
	If you make your acknowledgment sticky,
	it is still acknowledged after another negative state change has occurred.
	You can leave a comment to make your work more transparent.

* <a class="btn btn-default btn-xs"><i class="fa fa-envelope-o"></i> Disable notification</a> and <a class="btn btn-default btn-xs"><i class="fa fa-envelope"></i> Enable notifications</a>
	for the host group only or for the host group and their services.
	The option is temporary saved in memory and does not affect your configuration.

## How can I edit a host group in default view?

Click on
<i class="fa fa-gear fa-lg txt-color-teal list-edit"></i>
in the same row as the host group you want to edit.

On the page that appears you can reconfigure your host group.
To learn more about the configuration click [here][configure].

Click on <a class="btn btn-xs btn-primary">Save</a> to create your new host group.

Click on <a class="btn btn-xs btn-default">Cancel</a> if you want to discard your changes.

## How can I edit a host group in extended view?

You can either click on
<a class="btn btn-default btn-xs">&nbsp;<i class="fa fa-cog"></i>&nbsp;</a>
or you can click
<a class="btn btn-default btn-xs"><span class="caret"></span></a>
and then on
<a class="btn btn-default btn-xs"><i class="fa fa-cog"></i> Edit</a>
to go to the edit view.

## How can I delete a host group?

You have to go into the edit view of your host group,
where you can delete it by clicking on
<a class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i> Delete</a>.

A window will pop up asking if you really want to delete it,
confirm to delete.

## How do I configure a host group? <span id="configure"></span>

First you have to choose a container,
which will grant you access to
the available hosts.

**Host group name** - Choose a reasonable name. (required)

**Description** - Describe the group.

**Hosts** - Choose the hosts to group. (required)
