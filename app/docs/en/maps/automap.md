[adding]: /automaps/add (add a new automap)
[configure]: #configure "Configure your automaps"

## What are automaps in openITCOCKPIT v3?

You can use regular expressions for hosts and services to create a map of the matching objects with their current status.

## How can I create a new automap?

Click on
<a class="btn btn-xs btn-success"><i class="fa fa-plus"></i> New</a>
in the upper right corner.

On the [page][adding] that appears you can configure your automap.
To learn more about the configuration click [here][configure].

Click on <a class="btn btn-xs btn-primary">Save</a> to create your new map.

Click on <a class="btn btn-xs btn-default">Cancel</a> if you want to discard your changes.

## How can I edit an automap?

To edit an automap click on <i class="fa fa-gear fa-lg txt-color-teal"></i>&nbsp;
to go to the edit view.

To learn more about the configuration click [here][configure].

Click on <a class="btn btn-xs btn-primary">Save</a> to save your existing map.

Click on <a class="btn btn-xs btn-default">Cancel</a> if you want to discard your changes.

## How can I delete a map?

To delete an automap go to the edit view and click in the upper right corner on
<a class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i> Delete</a>.

At deletion a window will pop up asking if you really want to delete it,
confirm to delete.

## How can I view my automap?

To view your automap, simply click on the name of the automap you want to view from the list of all automaps.

## How is a map configured? <span id="configure"></span>

**Container** - Choose the container you want to use. <span style="color:red;">(required)</span>

**Recursive container lookup** - Choose wether or not to include all hosts and services from child containers

**Name** - Choose a reasonable name. <span style="color:red;">(required)</span>

**Description** - Choose a reasonable description.

**Host RegEx** - Enter a regular expression which is used to find matching hosts. <span style="color:red;">(required)</span>

**Service RegEx** - Enter a regular expression which is used to find matching services. <span style="color:red;">(required)</span>

**Options**
* <i class="fa fa-square txt-color-greenLight"></i>&nbsp;Show OK (Show hosts which are UP / Show services which have the status OK)
* <i class="fa fa-square txt-color-orange"></i>&nbsp;Show Warning (Show services which have the status WARNING)
* <i class="fa fa-square txt-color-redLight"></i>&nbsp;Show Critcal (Show hosts which are DOWN / Show services which have the status CRITICAL)
* <i class="fa fa-square txt-color-blueDark"></i>&nbsp;Show Unknown (Show hosts which are UNREACHABLE / Show services which have the status UNKNOWN)
* <i class="fa fa-power-off"></i>&nbsp;Show Downtime (Show hosts and services which are currently in a downtime)
* <i class="fa fa-user"></i>&nbsp;Show Acknowledged (Show hosts and services which states are acknowledged)
* <i class="fa fa-tag"></i>&nbsp;Show Label (Show the name of hosts and services next to the status icon)
* <i class="fa fa-sitemap"></i>&nbsp;Group by host (Should services be displayed grouped by host)
* Icon size (select the size of the icons on the automap)

