[//]: # (Links)
[site]: /autoreport_module/autoreports "View your auto reports"
[configure]: #configure "Configure your autoreports"
[adding]: /autoreport_module/autoreports/add (Add a new auto report)

[//]: # (Pictures)

[//]: # (Content)

## What are autoreports?

An autoreport automatically reports a report in a user defined way in a defined time to the defined user.

## How can I add a new auto report?

Click on
<a class="btn btn-xs btn-success"><i class="fa fa-plus"></i> Create local auto report</a>
in the upper right corner.

On the [page][adding] that appears you can configure your auto report.
To learn more about the configuration click [here][configure].

Click on <a class="btn btn-xs btn-primary">Save</a> to create your new auto report.

Click on <a class="btn btn-xs btn-default">Cancel</a> if you want to discard your changes.

## How can I edit an auto report?

To edit an auto report either click on
<a class="btn btn-default btn-xs">&nbsp;<i class="fa fa-cog"></i>&nbsp;</a>
or on
<a class="btn btn-default btn-xs"><span class="caret"></span></a>
and then on
<a class="btn btn-default btn-xs"><i class="fa fa-cog"></i> Edit</a>
to go to the edit view.

To learn more about the configuration click [here][configure].

Click on <a class="btn btn-xs btn-primary">Save</a> to save your existing auto report.

Click on <a class="btn btn-xs btn-default">Cancel</a> if you want to discard your changes.

## How can I delete an auto report?

To delete an auto report click on
<a class="btn btn-default btn-xs"><span class="caret"></span></a>
and then on
<a class="btn btn-default btn-xs txt-color-red"><i class="fa fa-trash-o"></i> Delete</a>.

## How can I create a report from an existing auto report?

To create an auto report click on
<a class="btn btn-default btn-xs"><span class="caret"></span></a>
and then on
<a class="btn btn-default btn-xs"><i class="fa fa-file-text-o"></i> Create report</a>.

On the new page that appears you can choose your format and a time span.

To crate the report, click on
<a class="btn btn-xs btn-primary">Create</a>.

## How is an auto report configured? <span id="configure"></span>

The configuration divides in three parts, **report configuration**, **host and services configuration** and **report details**.

To go to the next part click on
<a class="btn btn-xs btn-success">Next<i class="fa fa-arrow-right"></i></a>.
Click on
<a class="btn btn-xs btn-success"><i class="fa fa-arrow-left">Prev</i></a>
to got to the previous part.

##### Report Configuration

**Tenant** - Choose which tenant the calendar affects. (required)
**Report name** - Choose a reasonable name. (required)
**Report description** - Describe the auto report.

**Set start** if switched on your auto report requires a **start date**.

Choose the **timeperiod** and the time span trough **evaluation period** in which your report is valid.

You choose the generation interval through **send interval**.

There are also options to not generate an auto report,
you must specify at least one of the two options.

To not generate a scheduled auto report,
if hosts and services are to long available
check the box next to **min. allowed availability**
then enter your criteria in percent or minutes.

To not generate a scheduled auto report,
if hosts and services have not a critical number of outages,
check the box next to **max. number of outages**
then enter your maximum outage criteria.

**Graph** - Here you can choose if the graph displays time in percent or in hours.

**Reflection state** - Select which states show up in your graph,
only the hard or the hard and soft states.

**Consider downtimes** - Switch this option on to consider them.

**Users** - Select the users that receive this auto report. (required)

##### Host and services configuration

**Hosts** - Choose hosts and their name and services appear in the listing below.

In the listing below hosts have a dark blue background color
and services have a light blue background color.

In the second row of the listing you can set a value for all hosts and services in the listing.

You can also manually set the options for each service or host.

##### Report details

The report detail page shows you all the options that you set for your auto report.
