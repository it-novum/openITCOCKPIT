[//]: # (Links)
[page]: /mk_module/mkchecks (MK checks listing)
[configure]: #configure (Configure your MK check)
[adding]: /mk_module/mkchecks/add (add a new MK check)

[//]: # (Pictures)

[//]: # (Content)

## What can I do with a check_MK check?

A check_MK check is a check where you only interact with the host once.
Check_mk filters the output and sends it as a passive check to nagios.
It reduces load on the host interacting only once with it.

## How can I add a new MK check?

Click on
<a class="btn btn-xs btn-success"><i class="fa fa-plus"></i> New</a>
in the upper right corner.

On the [page][adding] that appears you can configure your MK check.
To learn more about the configuration click [here][configure].

Click on <a class="btn btn-xs btn-primary">Save</a> to create your new check.

Click on <a class="btn btn-xs btn-default">Cancel</a> if you want to discard your changes.

## How can I edit an MK check?

You can edit the MK check by clicking on
<a class="btn btn-default btn-xs">&nbsp;<i class="fa fa-cog fa-lg txt-color-teal"></i></a>.

If you are editing a check, a page will appear where you can edit it.
To learn more about the configuration click [here][configure].

Click on <a class="btn btn-xs btn-primary">Save</a> to save your changes.

Click on <a class="btn btn-xs btn-default">Cancel</a> if you want to discard your changes.

## How can I delete a check?

First you jump to the edit page of the check you want to delete.

On the edit page you can delete the check by clicking on
<a class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i> Delete</a>.

At deletion a window will pop up asking if you really want to delete it,
confirm to delete.

## How do I configure a MK check? <span id="configure"></span>

**Check name** - Choose a reasonable name. (required)
**Service template** - Choose the service for the MK check. (required)

