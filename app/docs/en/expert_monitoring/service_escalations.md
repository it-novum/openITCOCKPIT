[//]: # (Links)
[Service escalations]: /serviceescalations "Service Escalations"
[add escalation]: /serviceescalations/add "add a new service escalation"
[configure]: #configure "Configure your service escalations"

[//]: # (Pictures)
[add]: /img/docs/expert_monitoring/service_escalations/add.gif
[delete]: /img/docs/expert_monitoring/service_escalations/delete.gif

[//]: # (Content)

## What are service escalations?

[Service escalations] are optional follow-up notifications,
that generates if a state change is not revoked in time,
such as if a service is not reactivated with in 30 minutes.

## When do services escalate?

Service notifications escalating if
one escalation definition
matches the current notification
sent by a service.
If no definition will match,
the service will not escalate.

## How can I create a new service escalation?

Click on
<a class="btn btn-xs btn-success"><i class="fa fa-plus"></i> New</a>
in the upper right corner.

On the [page][add escalation] that appears you can configure your service escalation.
To learn more about the configuration click [here][configure].

Click on <a class="btn btn-xs btn-primary">Save</a> to create your new service escalation.

Click on <a class="btn btn-xs btn-default">Cancel</a> if you want to discard your changes.

![add]

## How can I edit a service escalation?

Click on
<i class="fa fa-gear fa-lg txt-color-teal list-edit"></i>
in the same row as the service escalation you want to edit.

On the page that appears you can reconfigure your service escalation.
To learn more about the configuration click [here][configure].

Click on <a class="btn btn-xs btn-primary">Save</a> to create your new service escalation.

Click on <a class="btn btn-xs btn-default">Cancel</a> if you want to discard your changes.

## How can I delete a service escalation?

Click on
<i class="fa fa-gear fa-lg txt-color-teal list-edit"></i>
in the same row as the service escalation you want to delete.

On the page that appears, where you can also reconfigure your service escalation,
you can delete it by clicking on
<a class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i> Delete</a>.
A window will pop up asking if you really want to delete it,
confirm to delete.

![delete]

## How are service escalations configured? <span id="configure"></span>

First you have to choose a container,
which will grant you access to
services, service groups, timeperiods, contacts and contact groups.

Then you can include and exclude **services** and **service groups**.
Choose at least one service for a successful escalation.
You can also type the beginning of a service name to find it more quickly.

After that, you can define **when** the services will escalate.

**First escalation notice** sets the beginning of the escalation
determined by the number of notifications
sent by the service in a matching state.

**Last escalation notice** sets the end of the escalation
determined by the number of notifications
sent by the service in a matching state.
If you do not want any limit
to the number of escalation notifications sent,
use zero as value.

**Notification interval** specifies the interval
in which notifications are sent.
Set it to zero
to limit the number of notifications
to one.

**Timeperiod** specify the notification schedule.

Then you can choose
which **contacts** and **contact groups**
should receive the escalation notifications.
Choose at least one contact and one contact group for a successful escalation.
You can also type the beginning of a contact or contact group to find it more quickly.

Now you are nearly finished,
you only have to enable the different criteria
by switching them on or off.

Under **Serviceescalations options** your set these service states:

* Recovery

* Warning

* Critical

* Unknown
