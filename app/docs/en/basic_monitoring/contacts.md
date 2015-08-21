[//]: # (Links)
[Contacts]: /contacts "contacts"
[configure]: #configure "Configure your contacts"
[adding]: /contacts/add (add a new contact)
[host states]: /documentations/wiki/basic-monitoring/hosts/en#states (Host states)
[service states]: /documentations/wiki/basic-monitoring/services/en#states (Service states)

[//]: # (Pictures)

[//]: # (Content)

## What are contacts?

[Contacts] contain information whom to notify in case of a particular state of a host or service.

## How can I create a new contact?

Click on
<a class="btn btn-xs btn-success"><i class="fa fa-plus"></i> New</a>
in the upper right corner.

On the [page][adding] that appears you can configure your contact.
To learn more about the configuration click [here][configure].

Click on <a class="btn btn-xs btn-primary">Save</a> to create your new contact.

Click on <a class="btn btn-xs btn-default">Cancel</a> if you want to discard your changes.

## How can I edit a contact?

Click on
<i class="fa fa-gear fa-lg txt-color-teal list-edit"></i>
in the same row as the contact you want to edit.

On the page that appears you can reconfigure your contact.
To learn more about the configuration click [here][configure].

Click on <a class="btn btn-xs btn-primary">Save</a> to create your new contact.

Click on <a class="btn btn-xs btn-default">Cancel</a> if you want to discard your changes.

## How can I delete a contact?

Click on
<i class="fa fa-gear fa-lg txt-color-teal list-edit"></i>
in the same row as the contact you want to delete.

On the page that appears, where you can also reconfigure your contact,
you can delete it by clicking on
<a class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i> Delete</a>.
A window will pop up asking if you really want to delete it,
confirm to delete.

## How do I configure a contact? <span id="configure"></span>

First you have to choose a container,
which will grant you access to
timeperiods, hosts and services.

**Name** - Choose a reasonable name. (required)

**Description** - Describe the contact.

**Email** - This mail address will receive notifications via mail. (required)

**Phone** - This phone will receive notifications via sms. (required)

Now you define **when** the contact gets notified by one or more hosts and services.

Under the notification settings of host and service
you have to select a timeperiod and at least one command.

If you have notifications enabled,
you can switch different **notification options** on or off.

For information about the different host states [click here][host states].
For information about the different service states [click here][service states].

