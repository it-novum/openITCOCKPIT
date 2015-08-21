[//]: # (Links)
[Host escalations]: /hostescalations "Host Escalations"
[add escalation]: /hostescalations/add "add a new host escalations"
[configure]: #configure "Configure your host escalations"

[//]: # (Pictures)
[add]: /img/docs/expert_monitoring/host_escalations/add.gif
[delete]: /img/docs/expert_monitoring/host_escalations/delete.gif

[//]: # (Content)

## What are host escalations?

[Host escalations] are optional follow-up notifications,
that generates if a state change is not revoked in time,
such as if a host is not reactivated with in 30 minutes.

## When do hosts escalate?

Host notifications escalating if
one escalation definition
matches the current notification
sent by a host.
If no definition will match,
the host will not escalate.

## How can I create a new host escalation?

Click on
<a class="btn btn-xs btn-success"><i class="fa fa-plus"></i> New</a>
in the upper right corner.

On the [page][add escalation] that appears you can configure your host escalation.
To learn more about the configuration click [here][configure].

Click on <a class="btn btn-xs btn-primary">Save</a> to create your new host escalation.

Click on <a class="btn btn-xs btn-default">Cancel</a> if you want to discard your changes.

![add]

## How can I edit a host escalation?

Click on
<i class="fa fa-gear fa-lg txt-color-teal list-edit"></i>
in the same row as the host escalation you want to edit.

On the page that appears you can reconfigure your host escalation.
To learn more about the configuration click [here][configure].

Click on <a class="btn btn-xs btn-primary">Save</a> to create your new host escalation.

Click on <a class="btn btn-xs btn-default">Cancel</a> if you want to discard your changes.

## How can I delete a host escalation?

Click on
<i class="fa fa-gear fa-lg txt-color-teal list-edit"></i>
in the same row as the host escalation you want to delete.

On the page that appears, where you can also reconfigure your host escalation,
you can delete it by clicking on
<a class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i> Delete</a>.
A window will pop up asking if you really want to delete it,
confirm to delete.

![delete]

## How are host escalations configured? <span id="configure"></span>

First you have to choose a container,
which will grant you access to
hosts, host groups, timeperiods, contacts and contact groups.

Then you can include and exclude **hosts** and **host groups**.
Choose at least one host for a successful escalation.
You can also type the beginning of a host name to find it more quickly.

After that, you can define **when** the hosts will escalate.

**First escalation notice** sets the beginning of the escalation
determined by the number of notifications
sent by the host in a matching state.

**Last escalation notice** sets the end of the escalation
determined by the number of notifications
sent by the host in a matching state.
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

Under **Hostescalations options** you have the following options:

* Recovery - the host switched to up again

* Down - the host is down

* Unreachable - the host is either
  not reachable through a software or hardware issue
  or down
