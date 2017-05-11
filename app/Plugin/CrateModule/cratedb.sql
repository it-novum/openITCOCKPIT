CREATE TABLE IF NOT EXISTS openitcockpit_hosts (
  id int,
  uuid string,
  name string,
  address string,
  container_ids array(int)
  ) CLUSTERED INTO 4 shards with (number_of_replicas = '1-all');