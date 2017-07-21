[//]: # (Links)

[//]: # (Pictures)

[//]: # (Content)

## The module require

* NRPEModul
* LinuxBasicMonitoringModul

## The module includes:

### Service Templates

* POSTGRES_AUTOVACUUM
* POSTGRES_CHECKPOINTER
* POSTGRES_STATS
* POSTGRES_WAL
* POSTGRES_WRITER

### Service Template Grps.

*  Postgres Process Monitoring

## Todo client

On the client, the following lines must be added to the end of the /etc/nagios/nrpe.cfg.
```shell
command[postgres_checkpointer]=/usr/lib/nagios/plugins/check_procs -a 'postgres: checkpointer' -c 1:1
command[postgres_writer]=/usr/lib/nagios/plugins/check_procs -a 'postgres: writer' -c 1:1
command[postgres_autovacuum]=/usr/lib/nagios/plugins/check_procs -a 'postgres: autovacuum' -c 1:1
command[postgres_wal]=/usr/lib/nagios/plugins/check_procs -a 'postgres: wal' -c 1:1
command[postgres_stats]=/usr/lib/nagios/plugins/check_procs -a 'postgres: stats' -c 1:1
```

Then restart the nrpe deamon:
```shell
systemctl restart nagios-nrpe-server.service
```
