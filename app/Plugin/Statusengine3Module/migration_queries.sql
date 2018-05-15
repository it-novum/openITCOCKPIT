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




-- hostname, contact_name, command_name, command_args, state, start_time, end_time, reason_type, output, ack_author, ack_data



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


-- hostname, service_description, contact_name, command_name, command_args, state, start_time, end_time, reason_type, output, ack_author, ack_data


