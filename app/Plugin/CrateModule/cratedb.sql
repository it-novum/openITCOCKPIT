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