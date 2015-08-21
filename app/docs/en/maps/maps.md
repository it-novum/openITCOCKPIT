[//]: # (Links)
[Maps]: /map_module/maps "Maps"
[adding]: /map_module/maps/add (add a new map)
[configure]: #configure "Configure your maps"
[map use]: #map-use "Customize your map"

[//]: # (Pictures)
[options collapsed]: /img/docs/maps/maps/buttonopt.png
[map options]: /img/docs/maps/maps/map-menu.png

[//]: # (Content)

## What are maps in openITCOCKPIT v3?

Use it to customize the visualization of host and services on a map.

## How can I create a new map?

Click on
<a class="btn btn-xs btn-success"><i class="fa fa-plus"></i> New</a>
in the upper right corner.

On the [page][adding] that appears you can configure your map.
To learn more about the configuration click [here][configure].

Click on <a class="btn btn-xs btn-primary">Save</a> to create your new map.

Click on <a class="btn btn-xs btn-default">Cancel</a> if you want to discard your changes.

## How can I edit a map?

To edit a map either click on
<a class="btn btn-default btn-xs">&nbsp;<i class="fa fa-cog"></i>&nbsp;</a>
or on
<a class="btn btn-default btn-xs"><span class="caret"></span></a>
and then on
<a class="btn btn-default btn-xs"><i class="fa fa-cog"></i> Edit</a>
to go to the edit view.

To learn more about the configuration click [here][configure].

Click on <a class="btn btn-xs btn-primary">Save</a> to save your existing map.

Click on <a class="btn btn-xs btn-default">Cancel</a> if you want to discard your changes.

## How can I delete a map?

To delete a map either click on
<a class="btn btn-default btn-xs"><span class="caret"></span></a>
and then on
<a class="btn btn-default btn-xs txt-color-red"><i class="fa fa-trash-o"></i> Delete</a>
or go to the edit view and click in the upper right corner on
<a class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i> Delete</a>.

At deletion a window will pop up asking if you really want to delete it,
confirm to delete.

## How can I view my map?

You click on
<a class="btn btn-default btn-xs"><span class="caret"></span></a>
and then on
<a class="btn btn-default btn-xs"><i class="fa fa-eye"></i> View</a>
or
<a class="btn btn-default btn-xs"><i class="glyphicon glyphicon-resize-full"></i> View in full screen</a>
to view your map in full screen.

## How is a map configured? <span id="configure"></span>

**Map name** - Choose a reasonable name. (required)

**Map title** - Choose a reasonable title. (required)

**Tenant** - Grand access from the chosen tenants.

## How to use the map editor? <span id="map-use"></span>

Click on
<a class="btn btn-default btn-xs"><span class="caret"></span></a>
and then on
<a class="btn btn-default btn-xs"><i class="fa fa-eye"></i> View</a>
to open the editor.

#### How to set a background?

You can choose a background at the floating menu on the right under the **Backgrounds** tab.
If nothing fits your purpose you can easily upload new backgrounds.
Just click on <a class="btn btn-xs btn-primary">Upload Background</a>.
A pop up dialog opens in which you can drop images,
which will upload instantly.
You also can click on the drop area
to open a file dialog to choose images from to upload.

#### Is there an auto hide option for the floating menu?

Yes, there is and it is easy to enable, just click on
<a class="btn btn-xs btn-primary">Options</a>
then a pop up dialog opens where you tick
**<i class="fa fa-bars"></i> Autohide Menu** on
and you are done.

#### Can I enable a grid or a ruler to outline objects?

Yes, there is and it is easy to enable, just click on
<a class="btn btn-xs btn-primary">Options</a>
then a pop up dialog opens where you tick
**<i class="glyphicon glyphicon-th"></i> Enable Grid** on.
Now can customize your grid in size, color, text scaling and if the grid is magnetic or not.

#### What can I add to the map?

You can add the following:
* Items - Do not reflect the host or service state.
* Gadgets - Reflect the host or service state.
* Misc. - Line or Static text

You can add them by dragging them out of the floating menu anywhere on the map.

A pop up appears where you can configure what you want to add.

Except of text under misc you have the following options:
First choose what the element represents a host, service, host group or service group.

If you the element represents a host, you have to choose the host.

If you the element represents a service, you have to choose the host and the service.

If you the element represents a host group, you have to choose the host group.

If you the element represents a service group, you have to choose the service group.

Beneath the chosen you see the current position of the element on your map, which you can change.

Items also have the extra option **iconset** so you can change the display item icon.

#### How can I edit an element?

Double click the element in the editor to open the pop up configuration.
Edit it the way you like, then click on
<a class="btn btn-xs btn-primary">Save Element</a> to save the element.
Click on <a class="btn btn-xs btn-default">Close</a> if you want to discard your changes.

#### How can I delete an element?
Double click the element in the editor to open the pop up configuration.
Click on <a class="btn btn-xs btn-danger">Delete</a> to delete the element.
