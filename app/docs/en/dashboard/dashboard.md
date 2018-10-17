[//]: # (Links)
[page]: / "Your dashboard"
[graphs]: /documentations/wiki/basic-monitoring/graphgenerator/en "Graph documentation"
[maps doc]: /documentations/wiki/maps/maps/en "Maps documentation"
[md]: /documentations/wiki/additional-help/markdown/en "Markdown Cheatsheet"

[//]: # (Pictures)
[Welcome]: /img/docs/dashboard/dashboards/welcome.png (Welcome)
[Parent outages]: /img/docs/dashboard/dashboards/parentoutages.png (Parent outages)
[Hosts pie chart]: /img/docs/dashboard/dashboards/hosts_pie_chart.png (Hosts pie chart)
[Hosts pie chart 180]: /img/docs/dashboard/dashboards/hosts_pie_chart_180.png (Hosts pie chart 180)
[Services pie chart]: /img/docs/dashboard/dashboards/services_pie_chart.png (Services pie chart)
[Services pie chart 180]: /img/docs/dashboard/dashboards/services_pie_chart_180.png (Services pie chart 180)
[Traffic light]: /img/docs/dashboard/dashboards/trafficlight.png (Traffic light)
[Tachometer]: /img/docs/dashboard/dashboards/tachometer.png (Tachometer)
[Notice]: /img/docs/dashboard/dashboards/notice.png (Notice)
[Notice edit]: /img/docs/dashboard/dashboards/notice_edit.png (Notice edit)
[Hosts in downtime]: /img/docs/dashboard/dashboards/host_downtimes.png (Hosts in downtime)
[Services in downtime]: /img/docs/dashboard/dashboards/service_downtimes.png (Services in downtime)
[Host status list]: /img/docs/dashboard/dashboards/host_status_list.png (Host status list)
[Host status overview]: /img/docs/dashboard/dashboards/host_status_overview.png (Host status overview)
[Host status overview down]: /img/docs/dashboard/dashboards/host_status_overview_down.png (Host status overview down)
[Service status list]: /img/docs/dashboard/dashboards/service_status_list.png (Service status list)
[Service status overview]: /img/docs/dashboard/dashboards/service_status_overview.png (Service status overview)
[Service status overview critical]: /img/docs/dashboard/dashboards/service_status_overview_critical.png (Service status overview critical)
[Event correlation]: /img/docs/dashboard/dashboards/event_correlation.png (Event correlation)
[Grafana]: /img/docs/dashboard/dashboards/grafana.png (Grafana)
[Map]: /img/docs/dashboard/dashboards/maps.png (Map)

[//]: # (Content)

## What is the Dashboard in openITCOCKPIT?

Every user has it's [own dashboard][page] that can be customized.
A dashboard has tabs and tabs contain widgets.
A widget is a small tool that
retrieves information from your monitoring system
to give you a visual feedback like graphs, charts, tables and more.
You can move, resize, rename and color all widgets.

## How to add new tab?
Click on
<a class="btn btn-xs btn-success"><i class="fa fa-plus"></i></a>
in the right corner of the tab bar.

A pop up dialog appears where you can set the name of the new tab.

Click on <a class="btn btn-xs btn-primary">Create new tab</a> to create your new tab.

Click on <a class="btn btn-xs btn-default">Close</a> if you want to discard your changes.

The new tab appears after the last tab.

## What is a shared tab?
A shared tab is a tab from another user that is shared across all users of your openITCOCKPIT implementation.

If a shared tab gets changed by the author, all users will get notified and asked if they want to update their tab.

## How to add a shared tab?
Click on
<a class="btn btn-xs btn-success"><i class="fa fa-plus"></i></a>
in the right corner of the tab bar.

A drop down appears where you choose a shared tab and click on
<a class="btn btn-xs btn-primary">Create from shared tab</a>.

The new tab appears after the last tab.

## How do I rename a tab?

Click on the current tab name.

A drop down menu appears.

Click on
<a class="btn btn-default btn-xs"><i class="fa fa-pencil-square-o"></i> Rename</a>,
a pop up appears where you can edit the current tab name.

Click on <a class="btn btn-xs btn-primary">Rename tab</a> to save the new tab name.

Click on <a class="btn btn-xs btn-default">Close</a> if you want to discard your changes.

## How do I share a tab?

Click on the current tab name.

A drop down menu appears.

Click on
<a class="btn btn-default btn-xs"><i class="fa fa-code-fork"></i> Start sharing</a>,
a pop up appears which you have to confirm to start the tab sharing.

## How do I stop the tab sharing?

Click on the current tab name.

A drop down menu appears.

Click on
<a class="btn btn-default btn-xs"><i class="fa fa-code-fork"></i> Stop sharing</a>,
a pop up appears which you have to confirm to stop the tab sharing.

## How do I delete a tab?

Click on the current tab name.

A drop down menu appears.

Click on
<a class="btn btn-default btn-xs txt-color-red"><i class="fa fa-trash-o"></i> Delete</a>
to delete the current tab without a request.

## How do I configure a tab rotation?
Click on
<a class="btn btn-xs btn-primary"><i class="fa fa-spinner"></i></a>
in the right corner of the tab bar.

A drop down appears where you choose the tab rotation interval time via a slider.

The interval will be saved instantly.

After you have chosen a new value, you can close the drop down.

Set it to zero to stop the rotation.

## How do I lock a tab?

Click on
<a class="btn btn-primary btn-xs"><i class="fa fa-unlock"></i></a>
to lock the current tab for editing.

Click on
<a class="btn btn-primary btn-xs"><i class="fa fa-lock"></i></a>
to unlock a locked tab.

## How do I view a tab in fullscreen mode?

Click on
<a class="btn btn-success btn-xs"><i class="fa fa-arrows-alt"></i></a>
to open the tab in fullscreen mode.

Press F11 or ESC to disable the fullscreen mode.

## How to add a Widget?
Click on
<a class="btn btn-xs btn-success">Add Widget <i class="fa fa-caret-down"></i></a>
in the upper right corner.

A drop down menu opens with all widgets that you can choose from
and an additional **Recreate default page** option.

This option recreates the default page on the current tab,
this means the default widgets replace your current widgets in your current tab.

## How can I rename a widget?

Click in the widget bar on
<a class="btn btn-xs btn-default"><i class="fa fa-pencil"></i></a>,
a pop up appears where you can rename your widget.

## How can I resize a widget?

Click and hold
<span class="btn-xs"><i class="fa fa-expand fa-rotate-90"></i></span>
on the bottom right.
Drag it into a direction to resize the widget.

## How can I move a widget?

Click and hold the widget bar.
Drag it into a direction to move the widget around.

You can not put the widget in the middle of nowhere,
because all widgets have a gravitational like pull towards the tab bar.

## How can I delete a widget?
Click in the widget bar on
<a class="btn btn-xs btn-default"><i class="fa fa-times"></i></a>,
to delete the widget instantly.

## How can I color a widgets bar?
Click in the widget bar on
<i class="fa fa-lg fa-square-o"></i>,
a pop up appears where you can choose a new color for the widget bar.

## What Widgets are available?

#### <i class="fa fa-comment"></i> Welcome

Shows you what timezone you configured and how many hosts and services are monitored.

![Welcome]

#### <i class="fa fa-exchange"></i> Parent outages

Displays if parent hosts are down or unreachable.

![Parent outages]

#### <i class="fa fa-pie-chart"></i> Hosts pie chart (180)

Display a pie chart of all host states.
You can click on the state color below the chart
to get a list of all objects in the particular state.

![Hosts pie chart]
![Hosts pie chart 180]

#### <i class="fa fa-pie-chart"></i> Services pie chart

Display a pie chart of all service states.
You can click on the state color below the chart
to get a list of all objects in the particular state.

![Services pie chart]
![Services pie chart 180]

#### <i class="fa fa-road"></i> Traffic light

Displays the current state of a service as a traffic light.

![Traffic light]

#### <i class="fa fa-tachometer"></i> Tachometer

Display the value of a service such as the local disk space.

<!--You have to define the following values:

* **Minimum** - Start value of the tachometer
* **Warning** - Begin of the warning range
* **Critical** - Begin of the critical range
* **Maximum** - End value of the tachometer-->

![Tachometer]

#### <i class="fa fa-pencil-square-o"></i> Notice

Make a notice formated via text, html or [mark down][md].

![Notice]
![Notice edit]

#### <i class="fa fa-power-off"></i> Hosts in downtime

Display if there is currently a host downtime.

![Hosts in downtime]

#### <i class="fa fa-power-off"></i> Services in downtime

Display if there is currently a service downtime.

![Services in downtime]

#### <i class="fa fa-list-alt"></i> Host status list

Shows you hosts with different states in a listing,
which automatically browses through the pages by the configured interval.

You can configure the following:
* **Pause scrolling** - Pause the scroll animation.
* **Scroll interval (secs)** - After the interval the widget will browse to the next page.
* **State colors** - Displays only the hosts with the ticked states.
* **Filters** - Filter the services with the available filters 'host name', 'service name' and 'service output'.

Your configuration changes will be saved instantly.

![Host status list]

#### <i class="fa fa-list-alt"></i> Host status overview

Shows the number of hosts in a given state

![Host status overview]
![Host status overview down]

#### <i class="fa fa-list-alt"></i> Service status list

Shows you services with different states in a listing,
which automatically browses through the pages after the configured interval.

You can configure the following:
* **Pause scrolling** - Pause the scroll animation.
* **Scroll interval (secs)** - After the interval the widget will browse to the next page.
* **States to show** - Displays only the services with the selected states.
* **Show acknowledged or services in downtime** - Choose if acknowledged services and/or services in downtime should be displayed.
* **Filters** - Filter the services with the available filters 'host name', 'service name' and 'service output'.

Your configuration changes will be saved instantly.

![Service status list]

#### <i class="fa fa-list-alt"></i> Service status overview

Shows the number of services in a given state

![Service status overview]
![Service status overview critical]

#### <i class="fa fa-line-chart"></i> Event correlation

Shows the current state of a given event correlation.

![Event correlation]

#### <i class="fa fa-line-chart"></i> Grafana

Shows an existing grafana dashboard.

![Grafana]

#### <i class="fa fa-globe"></i> Map

This widget displays the map you chose.
To learn more about maps, click [here][maps doc].

![Map]

