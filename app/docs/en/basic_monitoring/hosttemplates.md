[//]: # (Links)
[host templates]: /hosttemplates (Host templates)
[configure]: #configure (Configure your host template)
[configuration of a host]: /documentations/wiki/basic-monitoring/hosts/en#configure (Configure your host)
[adding]: /hosttemplates/add (add a new host template)

[//]: # (Pictures)

[//]: # (Content)

## What is a host template?
Use [host templates] for easier host creation,
but there is another advantage which you can see
in the host configuration.
If an option is equal to the one from the template
on the right you see
<a class="btn-xs"><i class="fa fa-chain fa-chain-default txt-color-green"></i></a>,
but if they are not equal you see
<a class="btn-xs"><i class="fa fa-chain-broken fa-chain-non-default txt-color-red"></i></a>.
With one click on the broken chain you can reset the value to that from the template.

## How do I find out which host uses a specific template?

Click on
<a class="btn btn-xs btn-default"><span class="caret"></span></a>
and then on
<a class="btn btn-default btn-xs"><i class="fa fa-reply-all fa-flip-horizontal"></i> Used by</a>
to find out which host is using this template.

## How can I create a new host template?

Click on
<a class="btn btn-xs btn-success"><i class="fa fa-plus"></i> New</a>
in the upper right corner.

On the [page][adding] that appears you can configure your hosttemplate.
To learn more about the configuration click [here][configure].

Click on <a class="btn btn-xs btn-primary">Save</a> to create your new template.

Click on <a class="btn btn-xs btn-default">Cancel</a> if you want to discard your changes.

## How can I edit a host template?

You can edit the host template either by clicking on
<a class="btn btn-default btn-xs">&nbsp;<i class="fa fa-cog"></i>&nbsp;</a>
or by clicking on
<a class="btn btn-xs btn-default"><span class="caret"></span></a>
and then on
<a class="btn btn-default btn-xs"><i class="fa fa-cog"></i> Edit</a>.

If you are editing a template, a page will appear where you can edit it.
To learn more about the configuration click [here][configure].

Click on <a class="btn btn-xs btn-primary">Save</a> to create your new hosttemplate.

Click on <a class="btn btn-xs btn-default">Cancel</a> if you want to discard your changes.

## How can I delete a template?

You can either
quickly delete by clicking on
<a class="btn btn-xs btn-default"><span class="caret"></span></a>
and then on
<a class="btn btn-default btn-xs"><i class="fa fa-trash-o"></i> Delete</a>
or jump to edit page of the template you want to delete.

On the edit page you can delete the template by clicking on
<a class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i> Delete</a>.

At deletion a window will pop up asking if you really want to delete it,
confirm to delete.

## How are host templates configured? <span id="configure"></span>
Host templates are nearly configured the same way as the [configuration of a host].
You just do not have the host specific fields.
Host specific fields are hosttemplate, address, host group, parent hosts and instance.
Instead of a hostname you have a templatename.
