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
  container_ids ARRAY(INT)
  ) CLUSTERED INTO 4 shards with (number_of_replicas = '1-all');

CREATE TABLE IF NOT EXISTS openitcockpit_contacts (
  contact_id int,
  command_id int,
  contact_uuid string,
  command_uuid string,
  contact_name string,
  command_name string,
  is_host_command boolean
  ) CLUSTERED INTO 4 shards with (number_of_replicas = '1-all');

