############
# Statusengine Worker Configuration
############

# Every node in the cluster needs a name
# IT IS REQUIRED THAT THE NAME IS UNIQUE IN THE CLUSTER!
# The name is required to route external commands
# And to know which node executed a check
# So please change the default value, if you have more than one nodes!!
node_name: openITCOCKPIT

############
# DATA SOURCE CONFIGURATION
############

# Determine if your Statusengine Broker Model exports all data to a
# gearman-job-server or not.
# Warning: Do not enable use_gearman and use_rabbitmq at the same time.
use_gearman: 1

# Configuration of your gearman-job-server
# The Statusengine Broker Module exports all data as json encoded objects
# to the a gearman-job-server
gearman:
  address: 127.0.0.1
  port: 4730
  timeout: 1000

# Determine if your Statusengine Broker Model exports all data to
# RabbitMQ or not.
# Warning: Do not enable use_rabbitmq and use_gearman at the same time.
#
# NOTICE: RabbitMQ Support is for the new Statusengine Event Broker
# which is in development at the moment.
# See: https://github.com/statusengine/broker
use_rabbitmq: 0

# NOTICE: RabbitMQ Support is for the new Statusengine Event Broker
# which is in development at the moment.
# See: https://github.com/statusengine/broker
rabbitmq:
  host: 127.0.0.1
  port: 5672
  user: statusengine
  password: statusengine
  vhost: /
  exchange: statusengine
  durable_exchange: 0
  durable_queues: 0


############
# LIVE DATA CONFIGURATION
############

# If Statusengine should save status data to Redis
# NOTICE: Redis is always required, to calculate internal statistics!
# So this switch will only determine, if Statusengine will save monitoring status results to redis as well
use_redis: 0

# Configuration of your redis server
redis:
  address: 127.0.0.1
  port: 6379
  db: 0

############
# HISTORY DATA CONFIGURATION
############

# If this is 1, Statusengine will save the current host and service status also in your historical storage backend
# For example in MySQL or CrateDB
store_live_data_in_archive_backend: 1

# If Statusengine should save historical data to MySQL
# WARNING: Do not set use_mysql and use_crate to 1 at the same time!
use_mysql: 1

# Configuration of your MySQL server
mysql:
  host: {{mysql_host}}
  port: 3306
  username: {{mysql_user}}
  password: {{mysql_password}}
  database: {{mysql_database}}
  encoding: utf8mb4

# If Statusengine should save historical data to CrateDB
# WARNING: Do not set use_crate and use_mysql to 1 at the same time!
use_crate: 0

# Configuration of your CrateDB Cluster
# This is an array of cluster nodes.
#
# It is recommended to you a load balancer in front of your CrateDB cluster!
# So you will have a single ip address where Statusengine is going to connect to
crate:
nodes:
  - 127.0.0.1:4200
#    - 192.168.56.101:4200
#    - 192.168.56.102:4200

# Performance settings
# How many records get inserted in one statement
# This value effects: CrateDB, MySQL and Elasticsearch
# Recommendation for MySQL: 100
# Recommendation for CrateDB: 10000
number_of_bulk_records: {{number_of_bulk_records}}

# Timeout in seconds Statusengine will wait that number_of_bulk_records is reached until a flush get forced
# This value effects: CrateDB, MySQL and Elasticsearch
max_bulk_delay: {{max_bulk_delay}}

# Number of worker processes for service status records
# Target: Redis
number_servicestatus_worker: {{number_servicestatus_worker}}

# Number of worker processes for host status records
# Target: Redis
number_hoststatus_worker: {{number_hoststatus_worker}}

# Number of worker processes for logentry records
# Target: MySQL|CrateDB
number_logentry_worker: 1

# Number of worker processes for host and service
# state change records
# Target: MySQL|CrateDB
number_statechange_worker: 1

# Number of worker processes for host check results
# Target: MySQL|CrateDB
number_hostcheck_worker: {{number_hostcheck_worker}}

# Number of worker processes for service check results
# Target: MySQL|CrateDB
number_servicecheck_worker: {{number_servicecheck_worker}}

# Number of worker other queues like notifications, downtimes and acknowledgements
# Target: MySQL|CrateDB
number_misc_worker: 1

############
# PERFDATA DATA CONFIGURATION
############

# If statusengine should process performance data or not
# 1 = yes
# 0 = no
process_perfdata: 1

# Number of worker processes for service check results
# Target: You selected this at 'perfdata_backend' option
number_perfdata_worker: {{number_perfdata_worker}}

# Uncomment to enable
# You can enable as much backends as you want
perfdata_backend:
  - graphite
# - crate
# - mysql
# - elasticsearch

############
# GRAPHITE CONFIGURATION
############

# Every record in Graphite will be prefixed with the given key
# so multiple systems are able to read/write to the same system
graphite_prefix: {{graphite_prefix}}

# Set the ip address or hostname for your Graphite system
# Statusengine Worker use the TCP plaintext protocol to store data
graphite_address: {{graphite_address}}

# Port where your Graphite server is listening to
graphite_port: {{graphite_port}}

# Every characters in the key which not match the given regex
# will be replace with an underscore _
graphite_illegal_characters: /[^a-zA-Z^0-9\-\.]/

############
# ELASTICSEARCH CONFIGURATION
############

# Statusengine will create an index template to store performance data to
# Elasticsearch.
# The template is hardcoded and will be managed by Statusengine
# automatically. How ever, you can still change
# important settings.
# If you change any template settings, you need to do this
# BEFORE THE FIRST start of Statusengine Worker,
# or you need to delete/edit the old template manually via Elasticsearch API
elasticsearch_template:
  name: statusengine-metric
  number_of_shards: 2
  number_of_replicas: 0
  refresh_interval: 15s
  codec: best_compression
  enable_all: 0
  enable_source: 1

# Index that will be used to store data in Elasticsearch
elasticsearch_index: statusengine-metric-

# The value of elasticsearch_pattern will be added to the end of your
# defiend elasticsearch_index. It is recommended to terminate
# your elasticsearch_index with an dash, like the example
# index: statusengine-metric-
#
# Available patterns:
# - none     => All data in one index, this will also disable deletion of old records!
# - daily    => statusengine-metric-YYYY.MM.DD
# - weekly   => statusengine-metric-GGGG.WW
# - monthly  => statusengine-metric-YYYY.MM
elasticsearch_pattern: daily

# Set the ip address or hostname for your Elasticsearch system or cluster
# Statusengine will use the HTTP API
elasticsearch_address: 127.0.0.1

# Port where your Elasticsearch server is listening to
elasticsearch_port: 9200

############
# COMMAND ROUTER CONFIGURATION
############
check_for_commands: 1

# Interval to check for new commands in seconds
# Every check will fire a SQL query, to choose wisely
command_check_interval: 15

# External command file where Statusengine will pass external commands to.
# If you are using Nagios you MUST USE the nagios.cmd!!
# If you are not sure, of what you are doing, use the .cmd file :)
external_command_file: /opt/openitc/nagios/var/rw/nagios.cmd

# Path to Naemon query handler.
# This is where Statusengine will pass external commands to the monitoring backend
# NOTICE! At the moment only Naemon supports to pass external commands through the query handler!
# If you are using Nagios, you need to use the nagios.cmd (External Command File)
# See: https://github.com/NagiosEnterprises/nagioscore/issues/364
query_handler: /opt/openitc/nagios/var/rw/nagios.qh

# Pass external commands to the external commands file.
# Naemon and Nagios
submit_method: cmd

# Pass external commands to the query handler
# Naemon only at the moment, but i but i recommend to use this, with Naemon
#submit_method: qh

############
# SYSLOG CONFIGURATION
############

# If Statusengine Worker should write log messages to your syslog
# Enabled=1, disabled=0
syslog_enabled: 1

# The tag or ident of Statusengine Worker in your syslog
syslog_tag: statusengine-worker

############
# ARCHIVE AGE CONFIGURATION
############

# NOTICE:
# The Statusengine Database cleanup cronjob should only run at one node of your cluster
# You can run the cron on as many nodes as you want, but this will increase the load of the system.
# If you want to run the cronjob on more than one node, you should set different times for scheduling the cron
# For example at 01:00AM on node1 and at 01:00PM on node2 or so
# Cronjob usage:
# bin/Console.php cleanup -q (will run the cronjob without any output, perfect for crontab)
#
# bin/Console.php cleanup (will run the cronjob with output, perfect to check whats going on)
#
# In this section you can define, how long which data should be stored in the database
# Every value is in DAYs!
# Set 0 to disable automatic cleanup of a particular table

# Settings for Host related records
# How long should every executed check for a host be stored
age_hostchecks: 0

# How long should acknowledgement data of a host be stored
age_host_acknowledgements: 0

# How long should host notifications be stored
age_host_notifications: 0

# How long should host state change records be stored
age_host_statehistory: 0

# How long should downtime data of a host be stored
age_host_downtimes: 0

# Settings for Service related records
# How long should every executed check for a service be stored
age_servicechecks: 0

# How long should acknowledgement data of a service be stored
age_service_acknowledgements: 0

# How long should service notifications be stored
age_service_notifications: 0

# How long should service state change records be stored
age_service_statehistory: 0

# How long should downtime data of a service be stored
age_service_downtimes: 0

# Misc records
# How long should log entries records be stored
age_logentries: 0

# How long should unprocessed task in Statusengine's task queue be stored
age_tasks: 1

# For some perfdata backends, Statusengine is able to cleanup the database:
# - CrateDB
# - MySQL
# - Elasticsearch
#    If you use Elasticsearch, don't set this value to less that your pattern is
#    e.g.: daily => 2, weekly => 8, monthly => 32
#    If your pattern is set to none, deletion of old records is disabled!
#
# Other backends to this by them self, so the age_perfdata value has no effect:
# - Graphite
age_perfdata: 0

############
# ENVIRONMENT CONFIGURATION
############

# Sometimes creepy proxies are get in the way and than we can't connect to the database backend
# or what every the proxy thinks to know about your connection
# Enable (1) this option to clear proxy environment variables (For Statusengine only)
# Disable (0) and Statusengine will use the proxy out of your environment
disable_http_proxy: 1
