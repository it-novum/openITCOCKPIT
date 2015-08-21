[//]: # (Links)
[page]: /automaps (Automaps)
[configure]: #configure (Configure your automap)
[adding]: /autompas/add (Add a new automap)
[graphMove]: /documentations/wiki/basic-monitoring/graphgenerator/en#move-around (How can I move around in the graph?)
[serviceBrowser]: /documentations/wiki/basic-monitoring/services/en#browser (Browser usage)

[//]: # (Pictures)

[//]: # (Content)

## What is an automap?

You can quickly create a map of host and their services filtered by regular expressions.
With automap you can quickly see if anything works fine or not.

## How do I use automaps?

First you click on the name of the automap you want to view.
On the page that will appear you will see your automap.
All squares are hosts, if you click on them, a pop up window appears
to show you details of this host.

The pop up contains the following details about the host:

* **Host** name
* **Service** name
* **Current state**
* **State Type** - hard or soft
* **Last Check** time
* **State since** last change
* **Output** of the services
* **Prefdata** - Data that's preferred
* and often a graph to move around described [here][graphMove]

Click on
<a class="btn btn-primary btn-xs"><i class="fa fa-cog"></i> Browser</a>
in the pop up to view the service in the browser view described [here][serviceBrowser].

To close the pop up you have multiple ways to do it.
You can click outside the pop up,
click on
<a class="btn btn-default btn-xs">x</a>
in the upper right or click on
<a class="btn btn-default btn-xs">Close</a>
in the bottom right.

## How can I add a new automap?

Click on
<a class="btn btn-xs btn-success"><i class="fa fa-plus"></i> New</a>
in the upper right corner.

On the [page][adding] that appears you can configure your automap.
To learn more about the configuration click [here][configure].

Click on <a class="btn btn-xs btn-primary">Save</a> to create your new automap.

Click on <a class="btn btn-xs btn-default">Cancel</a> if you want to discard your changes.

## How can I edit an automap?

You can edit the automap by clicking on
<a class="btn btn-default btn-xs">&nbsp;<i class="fa fa-cog fa-lg txt-color-teal"></i></a>.

A page will appear where you can edit your automap.
To learn more about the configuration click [here][configure].

Click on <a class="btn btn-xs btn-primary">Save</a> to save your changes.

Click on <a class="btn btn-xs btn-default">Cancel</a> if you want to discard your changes.

## How can I delete an automap?

First you jump to the edit page of the automap you want to delete.

On the edit page you can delete the automap by clicking on
<a class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i> Delete</a>.

At deletion a window will pop up asking if you really want to delete it,
confirm to delete.

## How do I configure an automap? <span id="configure"></span>

First you have to choose a container,
which will grant you access to
the available hosts.

**Name** - Choose a reasonable name. (required)

**Description** - Describe the automap.

**Host Regex** - Filter your host by a regular expression. (required)

**Service Regex** - Filter services from the filtered hosts by a regular expression. (required)

Switch on and off which states your automap shows.

At the end you can set the icon size and if the icons have labels or not.
