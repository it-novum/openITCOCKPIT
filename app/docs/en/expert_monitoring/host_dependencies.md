[//]: # (Links)
[Host dependencies]: /hostdependencies "Host dependencies"
[add dependencie]: /hostdependencies/add "add a new host dependencies"
[configure]: #configure "Configure your host dependencies"

[//]: # (Pictures)

[//]: # (Content)

## What are host dependencies?

[Host dependencies] are optional settings to associate multiple hosts,
you can also associate hosts by a parent-child-association.
You can use dependencies for complicated monitoring setups.

## What happens if a criteria matches?

If one or more dependent hosts matching
one or more execution failure states
the host checks time reduces.

If one or more dependent hosts matching
one or more notification failure states
the host is suppressing notifications.

## How can I create a new host dependencies?

Click on
<a class="btn btn-xs btn-success"><i class="fa fa-plus"></i> New</a>
in the upper right corner.

On the [page][add dependencie] that appears you can configure your host dependency.
To learn more about the configuration click [here][configure].

Click on <a class="btn btn-xs btn-primary">Save</a> to create your new host dependency.

Click on <a class="btn btn-xs btn-default">Cancel</a> if you want to discard your changes.

## How can I edit a host dependencies?

Click on
<i class="fa fa-gear fa-lg txt-color-teal list-edit"></i>
in the same row as the host dependency you want to edit.

On the page that appears you can reconfigure your host dependency.
To learn more about the configuration click [here][configure].

Click on <a class="btn btn-xs btn-primary">Save</a> to create your new host dependency.

Click on <a class="btn btn-xs btn-default">Cancel</a> if you want to discard your changes.

## How can I delete a host dependencies?

Click on
<i class="fa fa-gear fa-lg txt-color-teal list-edit"></i>
in the same row as the host dependency you want to delete.

On the page that appears, where you can also reconfigure your host dependency,
you can delete it by clicking on
<a class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i> Delete</a>.
A window will pop up asking if you really want to delete it,
confirm to delete.

## How are host dependencies configured? <span id="configure"></span>

First you have to choose a container,
which will grant you access to
hosts, host groups and timeperiods.

Then you choose hosts and the hosts they depend on.
Choose at least one host and one depended host for a successful dependency.
You can also choose host groups and host groups your hosts depend on.

You can type the beginning of a host or host group name to find it more quickly.

**Timeperiod** specify how long the dependency will be valid.

Now you can switch different criteria on or off.

**Inherits parent** - Inherits dependency of the depended hosts to their children.
This means if any of the children will fail,
this dependency will also fail.

**Execution failure criteria** - Determines when the dependent host should **not** be actively checked.

Under **Execution failure criteria** you have the following options:

* Up - the host is up

* Down - the host is down

* Unreachable - the host is unreachable

* Pending - the host has not yet been checked

* None - the host will always be actively checked

**Notification failure criteria** - Determines when the dependent host should **not** send out notifications.

Under **Notification failure criteria** you have the following options:

* Up - the host is up

* Down - the host is down

* Unreachable - the host is unreachable

* Pending - the host has not yet been checked

* None - the host will always send notifications
