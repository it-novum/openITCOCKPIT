-- Host Notifications
insert into statusengine_host_notifications (hostname, contact_name, command_name, command_args, state, start_time, end_time, reason_type, output, ack_author, ack_data) SELECT

Host.uuid,
Contact.uuid,
Command.uuid,
Contactnotificationmethod.command_args,
NotificationHost.state,
UNIX_TIMESTAMP(NotificationHost.start_time),
UNIX_TIMESTAMP(NotificationHost.end_time),
NotificationHost.notification_reason,
NotificationHost.output



 FROM `openitcockpit`.`nagios_notifications` AS `NotificationHost` INNER JOIN `openitcockpit`.`nagios_objects` AS `Objects` ON (`Objects`.`object_id` = `NotificationHost`.`object_id`) INNER JOIN `openitcockpit`.`hosts` AS `Host` ON (`Objects`.`name1` = `Host`.`uuid`) INNER JOIN `openitcockpit`.`nagios_contactnotifications` AS `Contactnotification` ON (`NotificationHost`.`notification_id` = `Contactnotification`.`notification_id`) INNER JOIN `openitcockpit`.`nagios_objects` AS `ContactObject` ON (`Contactnotification`.`contact_object_id` = `ContactObject`.`object_id`) INNER JOIN `openitcockpit`.`contacts` AS `Contact` ON (`ContactObject`.`name1` = `Contact`.`uuid`) INNER JOIN `openitcockpit`.`nagios_contactnotificationmethods` AS `Contactnotificationmethod` ON (`Contactnotificationmethod`.`contactnotification_id` = `Contactnotification`.`contactnotification_id`) INNER JOIN `openitcockpit`.`nagios_objects` AS `CommandObject` ON (`Contactnotificationmethod`.`command_object_id` = `CommandObject`.`object_id`) INNER JOIN `openitcockpit`.`commands` AS `Command` ON (`CommandObject`.`name1` = `Command`.`uuid`)


WHERE NotificationHost.contacts_notified > 0
and `NotificationHost`.`notification_type` = 0



-- Service Notifications
insert into statusengine_service_notifications (hostname, service_description, contact_name, command_name, command_args, state, start_time, end_time, reason_type, output) SELECT
Objects.name1,
Objects.name2,
Contact.uuid,
Command.uuid,
Contactnotificationmethod.command_args,
NotificationService.state,
UNIX_TIMESTAMP(NotificationService.start_time),
UNIX_TIMESTAMP(NotificationService.end_time),
NotificationService.notification_reason,
NotificationService.output



 FROM `openitcockpit`.`nagios_notifications` AS `NotificationService`
 INNER JOIN `openitcockpit`.`nagios_objects` AS `Objects` ON (`Objects`.`object_id` = `NotificationService`.`object_id`)

 INNER JOIN `openitcockpit`.`nagios_contactnotifications` AS `Contactnotification` ON (`NotificationService`.`notification_id` = `Contactnotification`.`notification_id`) INNER JOIN `openitcockpit`.`nagios_objects` AS `ContactObject` ON (`Contactnotification`.`contact_object_id` = `ContactObject`.`object_id`) INNER JOIN `openitcockpit`.`contacts` AS `Contact` ON (`ContactObject`.`name1` = `Contact`.`uuid`) INNER JOIN `openitcockpit`.`nagios_contactnotificationmethods` AS `Contactnotificationmethod` ON (`Contactnotificationmethod`.`contactnotification_id` = `Contactnotification`.`contactnotification_id`) INNER JOIN `openitcockpit`.`nagios_objects` AS `CommandObject` ON (`Contactnotificationmethod`.`command_object_id` = `CommandObject`.`object_id`) INNER JOIN `openitcockpit`.`commands` AS `Command` ON (`CommandObject`.`name1` = `Command`.`uuid`)


WHERE NotificationService.contacts_notified > 0
and `NotificationService`.`notification_type` = 1


-- Logentries

insert into statusengine_logentries (entry_time, logentry_type, logentry_data, node_name)
select
UNIX_TIMESTAMP(Logentry.entry_time),
Logentry.logentry_type,
Logentry.logentry_data,
'openITCOCKPIT'
FROM  `openitcockpit`.`nagios_logentries` AS `Logentry`;

-- Host State History
insert into statusengine_host_statehistory (hostname, state_time, state_change, state, is_hardstate, current_check_attempt, max_check_attempts, last_state, last_hard_state, output, long_output)
SELECT
  Objects.name1,
  UNIX_TIMESTAMP(Statehistory.state_time),
  Statehistory.state_change,
  Statehistory.state,
  Statehistory.state_type,
  Statehistory.current_check_attempt,
  Statehistory.max_check_attempts,
  Statehistory.last_state,
  Statehistory.last_hard_state,
  Statehistory.output,
  Statehistory.long_output
FROM
  `openitcockpit`.`nagios_statehistory` AS `Statehistory`
INNER JOIN `openitcockpit`.`nagios_objects` AS `Objects` ON (`Objects`.`object_id` = `Statehistory`.`object_id` AND Objects.objecttype_id = 1);

-- Service State History
insert into statusengine_service_statehistory (hostname, service_description, state_time, state_change, state, is_hardstate, current_check_attempt, max_check_attempts, last_state, last_hard_state, output, long_output)
SELECT
  Objects.name1,
  Objects.name2,
  UNIX_TIMESTAMP(Statehistory.state_time),
  Statehistory.state_change,
  Statehistory.state,
  Statehistory.state_type,
  Statehistory.current_check_attempt,
  Statehistory.max_check_attempts,
  Statehistory.last_state,
  Statehistory.last_hard_state,
  Statehistory.output,
  Statehistory.long_output
FROM
  `openitcockpit`.`nagios_statehistory` AS `Statehistory`
INNER JOIN `openitcockpit`.`nagios_objects` AS `Objects` ON (`Objects`.`object_id` = `Statehistory`.`object_id` AND Objects.objecttype_id = 2);

-- Host checks
insert into statusengine_hostchecks (hostname, state, is_hardstate, start_time, end_time, output, timeout, early_timeout, latency, execution_time, perfdata, command, current_check_attempt, max_check_attempts, long_output)
SELECT
  Objects.name1,
  Hostcheck.state,
  Hostcheck.state_type,
  UNIX_TIMESTAMP(Hostcheck.start_time),
  UNIX_TIMESTAMP(Hostcheck.end_time),
  Hostcheck.output,
  Hostcheck.timeout,
  Hostcheck.early_timeout,
  Hostcheck.latency,
  Hostcheck.execution_time,
  Hostcheck.perfdata,
  CommandObjects.name1,
  Hostcheck.current_check_attempt,
  Hostcheck.max_check_attempts,
  Hostcheck.long_output
FROM
  `openitcockpit`.`nagios_hostchecks` AS `Hostcheck`
INNER JOIN `openitcockpit`.`nagios_objects` AS `Objects` ON (`Objects`.`object_id` = `Hostcheck`.`host_object_id` AND Objects.objecttype_id = 1)
INNER JOIN `openitcockpit`.`nagios_objects` AS `CommandObjects` ON (`CommandObjects`.`object_id` = `Hostcheck`.`command_object_id` AND CommandObjects.objecttype_id = 12);

-- Service checks
insert into statusengine_servicechecks (hostname, service_description, state, is_hardstate, start_time, end_time, output, timeout, early_timeout, latency, execution_time, perfdata, command, current_check_attempt, max_check_attempts, long_output)
SELECT
  Objects.name1,
  Objects.name2,
  Servicecheck.state,
  Servicecheck.state_type,
  UNIX_TIMESTAMP(Servicecheck.start_time),
  UNIX_TIMESTAMP(Servicecheck.end_time),
  Servicecheck.output,
  Servicecheck.timeout,
  Servicecheck.early_timeout,
  Servicecheck.latency,
  Servicecheck.execution_time,
  Servicecheck.perfdata,
  CommandObjects.name1,
  Servicecheck.current_check_attempt,
  Servicecheck.max_check_attempts,
  Servicecheck.long_output
FROM
  `openitcockpit`.`nagios_servicechecks` AS `Servicecheck`
INNER JOIN `openitcockpit`.`nagios_objects` AS `Objects` ON (`Objects`.`object_id` = `Servicecheck`.`service_object_id` AND Objects.objecttype_id = 2)
INNER JOIN `openitcockpit`.`nagios_objects` AS `CommandObjects` ON (`CommandObjects`.`object_id` = `Servicecheck`.`command_object_id` AND CommandObjects.objecttype_id = 12);

-- Host Acks
insert into statusengine_host_acknowledgements (hostname, state, author_name, comment_data, entry_time, acknowledgement_type, is_sticky, persistent_comment, notify_contacts)
SELECT
  Objects.name1,
  Acknowledgement.state,
  Acknowledgement.author_name,
  Acknowledgement.comment_data,
  UNIX_TIMESTAMP(Acknowledgement.entry_time),
  Acknowledgement.acknowledgement_type,
  Acknowledgement.is_sticky,
  Acknowledgement.persistent_comment,
  Acknowledgement.notify_contacts
FROM
  `openitcockpit`.`nagios_acknowledgements` AS `Acknowledgement`
INNER JOIN `openitcockpit`.`nagios_objects` AS `Objects` ON (`Objects`.`object_id` = `Acknowledgement`.`object_id` AND Objects.objecttype_id = 1);

-- Service Acks
insert into statusengine_service_acknowledgements (hostname, service_description, state, author_name, comment_data, entry_time, acknowledgement_type, is_sticky, persistent_comment, notify_contacts)
SELECT
  Objects.name1,
  Objects.name2,
  Acknowledgement.state,
  Acknowledgement.author_name,
  Acknowledgement.comment_data,
  UNIX_TIMESTAMP(Acknowledgement.entry_time),
  Acknowledgement.acknowledgement_type,
  Acknowledgement.is_sticky,
  Acknowledgement.persistent_comment,
  Acknowledgement.notify_contacts
FROM
  `openitcockpit`.`nagios_acknowledgements` AS `Acknowledgement`
INNER JOIN `openitcockpit`.`nagios_objects` AS `Objects` ON (`Objects`.`object_id` = `Acknowledgement`.`object_id` AND Objects.objecttype_id = 2);

-- Host downtimes
insert into statusengine_host_downtimehistory (hostname, entry_time, author_name, comment_data, internal_downtime_id, triggered_by_id, is_fixed, duration, scheduled_start_time, scheduled_end_time, was_started, actual_start_time, actual_end_time, was_cancelled, node_name)
SELECT
  Objects.name1,
  UNIX_TIMESTAMP(DowntimeHost.entry_time),
  DowntimeHost.author_name,
  DowntimeHost.comment_data,
  DowntimeHost.internal_downtime_id,
  DowntimeHost.triggered_by_id,
  DowntimeHost.is_fixed,
  DowntimeHost.duration,
  UNIX_TIMESTAMP(DowntimeHost.scheduled_start_time),
  UNIX_TIMESTAMP(DowntimeHost.scheduled_end_time),
  DowntimeHost.was_started,
  UNIX_TIMESTAMP(DowntimeHost.actual_start_time),
  UNIX_TIMESTAMP(DowntimeHost.actual_end_time),
  DowntimeHost.was_cancelled,
  'openITCOCKPIT'
FROM
  `openitcockpit`.`nagios_downtimehistory` AS `DowntimeHost`
INNER JOIN `openitcockpit`.`nagios_objects` AS `Objects` ON (`Objects`.`object_id` = `DowntimeHost`.`object_id` AND Objects.objecttype_id = 1)
WHERE DowntimeHost.downtime_type = 2;

insert into statusengine_service_downtimehistory (hostname, service_description, entry_time, author_name, comment_data, internal_downtime_id, triggered_by_id, is_fixed, duration, scheduled_start_time, scheduled_end_time, was_started, actual_start_time, actual_end_time, was_cancelled, node_name)
SELECT
  Objects.name1,
  Objects.name2,
  UNIX_TIMESTAMP(DowntimeService.entry_time),
  DowntimeService.author_name,
  DowntimeService.comment_data,
  DowntimeService.internal_downtime_id,
  DowntimeService.triggered_by_id,
  DowntimeService.is_fixed,
  DowntimeService.duration,
  UNIX_TIMESTAMP(DowntimeService.scheduled_start_time),
  UNIX_TIMESTAMP(DowntimeService.scheduled_end_time),
  DowntimeService.was_started,
  UNIX_TIMESTAMP(DowntimeService.actual_start_time),
  UNIX_TIMESTAMP(DowntimeService.actual_end_time),
  DowntimeService.was_cancelled,
  'openITCOCKPIT'
FROM
  `openitcockpit`.`nagios_downtimehistory` AS `DowntimeService`
INNER JOIN `openitcockpit`.`nagios_objects` AS `Objects` ON (`Objects`.`object_id` = `DowntimeService`.`object_id` AND Objects.objecttype_id = 2)
WHERE DowntimeService.downtime_type = 1;