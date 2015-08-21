[//]: # (Links)
[Satellites]: /distribute_module/satellites "Satellites"
[configure]: #configure "Configure your satellites"
[adding]: /distribute_module/satellites/add (add a new satellite)

[//]: # (Pictures)

[//]: # (Content)

## What are satellites?

[Satellites] are server which check hosts and deliver the results as a passive check to nagios. This saves you time if the network you monitor contains over a hundred hosts.

## How can I add a new satellite?

Click on
<a class="btn btn-xs btn-success"><i class="fa fa-plus"></i> New</a>
in the upper right corner.

On the [page][adding] that appears you can configure your satellite.
To learn more about the configuration click [here][configure].

Click on <a class="btn btn-xs btn-primary">Save</a> to create your new satellite.

Click on <a class="btn btn-xs btn-default">Cancel</a> if you want to discard your changes.

## How can I edit a satellite?

Click on
<i class="fa fa-gear fa-lg txt-color-teal list-edit"></i>
in the same row as the satellite you want to edit.

On the page that appears you can reconfigure your satellite.
To learn more about the configuration click [here][configure].

Click on <a class="btn btn-xs btn-primary">Save</a> to create your new satellite.

Click on <a class="btn btn-xs btn-default">Cancel</a> if you want to discard your changes.

## How can I delete a satellite?

Click on
<i class="fa fa-gear fa-lg txt-color-teal list-edit"></i>
in the same row as the satellite you want to delete.

On the page that appears, where you can also reconfigure your satellite,
you can delete it by clicking on
<a class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i> Delete</a>.
A window will pop up asking if you really want to delete it,
confirm to delete.

## How do I configure a satellite? <span id="configure"></span>

First you chose a reasonable **instance name** for your satellite.

Then type in the **IP address** of your satellite.

Select a **container** to grand access from the chosen container to the satellite.
