[//]: # (Links)
[service template groups]: /servicetemplategroups (Service template groups)
[configure]: #configure (Configure your service template group)
[adding]: /servicetemplategroups/add (add a new service template group)

[//]: # (Pictures)

[//]: # (Content)

## What is a service template group?

Use [service template groups] to append not present services to a host or a host group in an easy way.

## How can I create a new service template group?

Click on
<a class="btn btn-xs btn-success"><i class="fa fa-plus"></i> New</a>
in the upper right corner.

On the [page][adding] that appears you can configure your service template group.
To learn more about the configuration click [here][configure].

Click on <a class="btn btn-xs btn-primary">Save</a> to create your new template group.

Click on <a class="btn btn-xs btn-default">Cancel</a> if you want to discard your changes.

## How can I edit a service template group?

You can edit the service template group either by clicking on
<a class="btn btn-default btn-xs">&nbsp;<i class="fa fa-cog"></i>&nbsp;</a>
or by clicking on
<a class="btn btn-xs btn-default"><span class="caret"></span></a>
and then on
<a class="btn btn-default btn-xs"><i class="fa fa-cog"></i> Edit</a>.

If you are editing a template group, a page will appear where you can edit it.
To learn more about the configuration click [here][configure].

Click on <a class="btn btn-xs btn-primary">Save</a> to save your changes.

Click on <a class="btn btn-xs btn-default">Cancel</a> if you want to discard your changes.

## How can I delete a template group?

You can either quickly delete by clicking on
<a class="btn btn-xs btn-default"><span class="caret"></span></a>
and then on
<a class="btn btn-default btn-xs txt-color-red"><i class="fa fa-trash-o"></i> Delete</a>
or jump to edit page of the template group you want to delete.

On the edit page you can delete the template group by clicking on
<a class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i> Delete</a>.

At deletion a window will pop up asking if you really want to delete it,
confirm to delete.

## How can I append not present services to hosts or host groups?

Click on
<a class="btn btn-xs btn-default"><span class="caret"></span></a>
and then either on
<a class="btn btn-default btn-xs"><i class="fa fa-external-link"></i> Allocate host group</a>
or on
<a class="btn btn-default btn-xs"><i class="fa fa-external-link"></i> Allocate host</a>
to append not present services to the hosts of the host group or the chosen host.

On the page that appears you can either choose a host group or a host.

Beneath each host you see all the services you chose in your service template group configuration.
Each service has a check box in front of its name.
If checked, the service appends to the host on save otherwise the service not appends to the host.

By default checked check boxes mean that the host does not contain the service.

The services that hosts already contain are marked with
<i class="fa fa-info-circle text-info"></i>
at the end of the line. You can hover over the symbol to get the information that the service already exist on the host and if you tick the check box you will create duplicate service on the host.

To append the checked services to the host or the host group click on
<a class="btn btn-xs btn-primary">Save</a>.

If you have allocated a host group, you will be redirected to hosts else to the service list of your chosen host.

## How do I configure a service template group? <span id="configure"></span>

First you have to choose a container,
which will grant you access to
the available service templates.

**Service template group name** - Choose a reasonable name. (required)

**Service templates** - Choose the services templates your group contains. (required)

**Description** - Describe the service template group.

