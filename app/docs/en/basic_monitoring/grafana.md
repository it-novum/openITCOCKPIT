[//]: # (Links)
[configuration]: /grafana_module/grafana_configuration "Grafana configuration"
[hosts browser]: /hosts "Hosts overview"
[add user dashboard]: /grafana_module/grafana_userdashboards/add "Add Grafana user dashboard"

[//]: # (Pictures)
[Grafana hostgroups]: /img/docs/basic_monitoring/grafana/hostgroup_selectbox.png (Grafana configuration hostgroup selectbox)
[Grafana tab]: /img/docs/basic_monitoring/grafana/grafana_tab_box.png (Grafana tab box in host view)
[Create Grafana user dashboard]: /img/docs/basic_monitoring/grafana/create_grafana_user_dashboard.png (Create Grafana user dashboard)
[Configure Grafana user dashboard]: /img/docs/basic_monitoring/grafana/configure_grafana_user_dashboard.png (Configure Grafana user dashboard)
[Grafana User Dashboard]: /img/docs/basic_monitoring/grafana/grafana_user_dashboard.png (Grafana User Dashboard)

[//]: # (Content)

## What can I do with grafana?

You can generate graphs with data from different services.

## How do I configure grafana?

To configure our grafana module open the [grafana configuration][configuration].

Enter your:

* Grafana URL
* Grafana API Key
* Grafana Prefix

You can get them from your account on grafana.net or an own grafana server.

We recommend using https for a secure network connection.

To bring hosts into grafana, add hosts to a hostgroup and add these in the [grafana configuration][configuration].

![Grafana hostgroups]

## Where can I view my graphs?

To open a hosts graph collection open the host in [hosts browser] and open the grafana tab in the host overview.
Here you find all saved graphs from the services of the host.

![Grafana tab]

## What can I do with grafana user dashboards?

You can also generate graphs with data from different services.

The difference between the auto generated grafana dashboards is the possibility to order custom services by your own.

## How do I configure a grafana user dashboard?

First, [add a grafana user dashboard][add user dashboard].

Choose a container and set a name for your own grafana dashboard.

![Create Grafana user dashboard]

Then you can add rows and metrics in there.

Use the panel options to set a custom panel title or a panel unit.

![Configure Grafana user dashboard]

If you are ready, click <a class="btn btn-primary btn-xs"><i class="fa fa-refresh"></i> Synchronize with Grafana</a> and your dashboard will be created.

![Grafana User Dashboard]
