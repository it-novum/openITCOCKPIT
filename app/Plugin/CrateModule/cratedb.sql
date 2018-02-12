CREATE TABLE IF NOT EXISTS openitcockpit_hosts (
  id int,
  uuid string,
  name string,
  address string,
  container_id int,
  active_checks_enabled int,
  hosttemplate_id int,
  tags string,
  satellite_id int,
  container_ids ARRAY(INT),
  disabled boolean
  ) CLUSTERED INTO 4 shards with (number_of_replicas = '1-all');

CREATE TABLE IF NOT EXISTS openitcockpit_contacts (
  id int,
  uuid string,
  name string
  ) CLUSTERED INTO 4 shards with (number_of_replicas = '1-all');

CREATE TABLE IF NOT EXISTS openitcockpit_commands (
  id int,
  uuid string,
  name string
  ) CLUSTERED INTO 4 shards with (number_of_replicas = '1-all');

CREATE TABLE IF NOT EXISTS openitcockpit_services (
  id int,
  host_id int,
  servicetemplate_id int,
  uuid string,
  name string,
  name_from_template boolean,
  active_checks_enabled int,
  active_checks_enabled_from_template boolean,
  tags string,
  tags_from_template boolean,
  disabled boolean
  ) CLUSTERED INTO 4 shards with (number_of_replicas = '1-all');
