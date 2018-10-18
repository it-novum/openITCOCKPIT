[//]: # (Links)
[configuration]: /grafana_module/grafana_configuration "Grafana configuration"
[hosts browser]: https://dev-ttriebensky.oitc.itn/hosts "Hosts overview"

[//]: # (Pictures)
[Grafana hostgroups]: /img/docs/basic_monitoring/grafana/hostgroup_selectbox.png (Grafana configuration hostgroup selectbox)
[Grafana tab]: /img/docs/basic_monitoring/grafana/grafana_tab_box.png (Grafana tab box in host view)

[//]: # (Content)

## What can I do with grafana?

You can generate graphs with data from diffrent services.

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
