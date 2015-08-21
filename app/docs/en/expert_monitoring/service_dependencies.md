[//]: # (Links)
[Service dependencies]: /servicedependencies "Service dependencies"
[add dependencie]: /servicedependencies/add "add a new service dependency"
[configure]: #configure "Configure your service dependencies"

[//]: # (Pictures)

[//]: # (Content)

## What are service dependencies?

[Service dependencies] are optional settings to associate multiple services.
You can use dependencies for complicated monitoring setups.

## What happens if a criteria matches?

If one or more dependent services matching
one or more execution failure states
the service checks time reduces.

If one or more dependent services matching
one or more notification failure states
the service is suppressing notifications.

## How can I create a new service dependency?

Click on
<a class="btn btn-xs btn-success"><i class="fa fa-plus"></i> New</a>
in the upper right corner.

On the [page][add dependencie] that appears you can configure your service dependency.
To learn more about the configuration click [here][configure].

Click on <a class="btn btn-xs btn-primary">Save</a> to create your new service dependency.

Click on <a class="btn btn-xs btn-default">Cancel</a> if you want to discard your changes.

## How can I edit a service dependency?

Click on
<i class="fa fa-gear fa-lg txt-color-teal list-edit"></i>
in the same row as the service dependency you want to edit.

On the page that appears you can reconfigure your service dependency.
To learn more about the configuration click [here][configure].

Click on <a class="btn btn-xs btn-primary">Save</a> to create your new service dependency.

Click on <a class="btn btn-xs btn-default">Cancel</a> if you want to discard your changes.

## How can I delete a service dependency?

Click on
<i class="fa fa-gear fa-lg txt-color-teal list-edit"></i>
in the same row as the service dependency you want to delete.

On the page that appears, where you can also reconfigure your service dependency,
you can delete it by clicking on
<a class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i> Delete</a>.
A window will pop up asking if you really want to delete it,
confirm to delete.

## How are service dependencies configured? <span id="configure"></span>

First you have to choose a container,
which will grant you access to
services, service groups and timeperiods.

Then you choose services and the services they depend on.
Choose at least one service and one depended service for a successful dependency.
You can also choose service groups and service groups your services depend on.

You can type the beginning of a service or service group name to find it more quickly.

**Timeperiod** specify how long the dependency will be valid.

Now you can switch different criteria on or off.

**Inherits parent** - Inherits dependency of the depended services to their children.
This means if any of the children will fail,
this dependency will also fail.

**Execution failure criteria** - Determines when the dependent service should **not** be actively checked.

Under **Execution failure criteria** you have the following options:

* Ok - the service is ok

* Warning - the service is in the warning state

* Critical - the service is critical

* Unknown - the service is unknown

* Pending - the service has not yet been checked

* Execution_none - the service will always be actively checked

**Notification failure criteria** - Determines when the dependent service should **not** send out notifications.

Under **Notification failure criteria** you have the following options:

* Ok - the service is ok

* Warning - the service is in the warning state

* Critical - the service is critical

* Unknown - the service is unknown

* Pending - the service has not yet been checked

* Execution_none - the service will always send notifications
