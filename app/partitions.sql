DROP TABLE IF EXISTS `nagios_servicechecks`;

CREATE TABLE IF NOT EXISTS `nagios_servicechecks` (
  `servicecheck_id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `instance_id` smallint(6) NOT NULL DEFAULT '0',
  `service_object_id` int(11) NOT NULL DEFAULT '0',
  `check_type` smallint(6) NOT NULL DEFAULT '0',
  `current_check_attempt` smallint(6) NOT NULL DEFAULT '0',
  `max_check_attempts` smallint(6) NOT NULL DEFAULT '0',
  `state` smallint(6) NOT NULL DEFAULT '0',
  `state_type` smallint(6) NOT NULL DEFAULT '0',
  `start_time` datetime NOT NULL DEFAULT '1970-01-01 00:00:00',
  `start_time_usec` int(11) NOT NULL DEFAULT '0',
  `end_time` datetime NOT NULL DEFAULT '1970-01-01 00:00:00',
  `end_time_usec` int(11) NOT NULL DEFAULT '0',
  `command_object_id` int(11) NOT NULL DEFAULT '0',
  `command_args` varchar(255) COLLATE utf8_swedish_ci DEFAULT '',
  `command_line` varchar(255) COLLATE utf8_swedish_ci DEFAULT '',
  `timeout` smallint(6) NOT NULL DEFAULT '0',
  `early_timeout` smallint(6) NOT NULL DEFAULT '0',
  `execution_time` double NOT NULL DEFAULT '0',
  `latency` double NOT NULL DEFAULT '0',
  `return_code` smallint(6) NOT NULL DEFAULT '0',
  `output` varchar(1000) COLLATE utf8_swedish_ci,
  `long_output` text COLLATE utf8_swedish_ci,
  `perfdata` varchar(1000) COLLATE utf8_swedish_ci,
  PRIMARY KEY (`servicecheck_id`, `start_time`),
  KEY `start_time` (`start_time`),
  KEY `instance_id` (`instance_id`),
  KEY `service_object_id` (`service_object_id`),
  INDEX (start_time)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci COMMENT='Historical service checks'
PARTITION BY RANGE ( TO_DAYS(start_time) ) (
  PARTITION p_max VALUES LESS THAN (MAXVALUE)
);

-- mysql> show create table itnovum_workshop.nagios_servicechecks\G

DROP TABLE IF EXISTS `nagios_hostchecks`;

CREATE TABLE IF NOT EXISTS `nagios_hostchecks` (
  `hostcheck_id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `instance_id` smallint(6) NOT NULL DEFAULT '0',
  `host_object_id` int(11) NOT NULL DEFAULT '0',
  `check_type` smallint(6) NOT NULL DEFAULT '0',
  `is_raw_check` smallint(6) NOT NULL DEFAULT '0',
  `current_check_attempt` smallint(6) NOT NULL DEFAULT '0',
  `max_check_attempts` smallint(6) NOT NULL DEFAULT '0',
  `state` smallint(6) NOT NULL DEFAULT '0',
  `state_type` smallint(6) NOT NULL DEFAULT '0',
  `start_time` datetime NOT NULL DEFAULT '1970-01-01 00:00:00',
  `start_time_usec` int(11) NOT NULL DEFAULT '0',
  `end_time` datetime NOT NULL DEFAULT '1970-01-01 00:00:00',
  `end_time_usec` int(11) NOT NULL DEFAULT '0',
  `command_object_id` int(11) NOT NULL DEFAULT '0',
  `command_args` varchar(255) COLLATE utf8_swedish_ci DEFAULT '',
  `command_line` varchar(255) COLLATE utf8_swedish_ci DEFAULT '',
  `timeout` smallint(6) NOT NULL DEFAULT '0',
  `early_timeout` smallint(6) NOT NULL DEFAULT '0',
  `execution_time` double NOT NULL DEFAULT '0',
  `latency` double NOT NULL DEFAULT '0',
  `return_code` smallint(6) NOT NULL DEFAULT '0',
  `output` varchar(255) CHARACTER SET utf8 DEFAULT '',
  `long_output` text CHARACTER SET utf8,
  `perfdata` varchar(255) COLLATE utf8_swedish_ci DEFAULT '',
  PRIMARY KEY (`hostcheck_id`, `start_time`),
  KEY `start_time` (`start_time`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci COMMENT='Historical host checks'
PARTITION BY RANGE ( TO_DAYS(start_time) ) (
  PARTITION p_max VALUES LESS THAN (MAXVALUE)
);

-- mysql> show create table itnovum_workshop.nagios_hostchecks\G

DROP TABLE IF EXISTS `nagios_statehistory`;

CREATE TABLE IF NOT EXISTS `nagios_statehistory` (
  `statehistory_id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `instance_id` smallint(6) NOT NULL DEFAULT '0',
  `state_time` datetime NOT NULL DEFAULT '1970-01-01 00:00:00',
  `state_time_usec` int(11) NOT NULL DEFAULT '0',
  `object_id` int(11) NOT NULL DEFAULT '0',
  `state_change` smallint(6) NOT NULL DEFAULT '0',
  `state` smallint(6) NOT NULL DEFAULT '0',
  `state_type` smallint(6) NOT NULL DEFAULT '0',
  `current_check_attempt` smallint(6) NOT NULL DEFAULT '0',
  `max_check_attempts` smallint(6) NOT NULL DEFAULT '0',
  `last_state` smallint(6) NOT NULL DEFAULT '-1',
  `last_hard_state` smallint(6) NOT NULL DEFAULT '-1',
  `output` varchar(255) COLLATE utf8_swedish_ci DEFAULT '',
  `long_output` text COLLATE utf8_swedish_ci,
  PRIMARY KEY (`statehistory_id`, `state_time`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci COMMENT='Historical host and service state changes'
PARTITION BY RANGE ( TO_DAYS(state_time) ) (
  PARTITION p_max VALUES LESS THAN (MAXVALUE)
);

-- mysql> show create table itnovum_workshop.nagios_statehistory\G

DROP TABLE IF EXISTS `nagios_logentries`;

CREATE TABLE IF NOT EXISTS `nagios_logentries` (
  `logentry_id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `instance_id` int(11) NOT NULL DEFAULT '0',
  `logentry_time` datetime NOT NULL DEFAULT '1970-01-01 00:00:00',
  `entry_time` datetime NOT NULL DEFAULT '1970-01-01 00:00:00',
  `entry_time_usec` int(11) NOT NULL DEFAULT '0',
  `logentry_type` int(11) NOT NULL DEFAULT '0',
  `logentry_data` varchar(255) COLLATE utf8_swedish_ci NOT NULL DEFAULT '',
  `realtime_data` smallint(6) NOT NULL DEFAULT '0',
  `inferred_data_extracted` smallint(6) NOT NULL DEFAULT '0',
  PRIMARY KEY (`logentry_id`, `entry_time`),
  UNIQUE KEY `instance_id` (`instance_id`,`logentry_time`,`entry_time`,`entry_time_usec`),
  KEY `logentry_time` (`logentry_time`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci COMMENT='Historical record of log entries'
PARTITION BY RANGE ( TO_DAYS(entry_time) ) (
  PARTITION p_max VALUES LESS THAN (MAXVALUE)
);

-- mysql> show create table itnovum_workshop.nagios_logentries\G

DROP TABLE IF EXISTS `nagios_notifications`;

CREATE TABLE IF NOT EXISTS `nagios_notifications` (
  `notification_id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `instance_id` smallint(6) NOT NULL DEFAULT '0',
  `notification_type` smallint(6) NOT NULL DEFAULT '0',
  `notification_reason` smallint(6) NOT NULL DEFAULT '0',
  `object_id` int(11) NOT NULL DEFAULT '0',
  `start_time` datetime NOT NULL DEFAULT '1970-01-01 00:00:00',
  `start_time_usec` int(11) NOT NULL DEFAULT '0',
  `end_time` datetime NOT NULL DEFAULT '1970-01-01 00:00:00',
  `end_time_usec` int(11) NOT NULL DEFAULT '0',
  `state` smallint(6) NOT NULL DEFAULT '0',
  `output` varchar(255) COLLATE utf8_swedish_ci DEFAULT '',
  `long_output` text COLLATE utf8_swedish_ci,
  `escalated` smallint(6) NOT NULL DEFAULT '0',
  `contacts_notified` smallint(6) NOT NULL DEFAULT '0',
  PRIMARY KEY (`notification_id`, `start_time`),
  KEY `top10` (`object_id`,`start_time`,`contacts_notified`),
  KEY `start_time` (`start_time`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci COMMENT='Historical record of host and service notifications'
PARTITION BY RANGE ( TO_DAYS(start_time) ) (
  PARTITION p_max VALUES LESS THAN (MAXVALUE)
);

-- mysql> show create table itnovum_workshop.nagios_notifications\G

DROP TABLE IF EXISTS `nagios_contactnotifications`;

CREATE TABLE IF NOT EXISTS `nagios_contactnotifications` (
  `contactnotification_id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `instance_id` smallint(6) NOT NULL DEFAULT '0',
  `notification_id` BIGINT(20) UNSIGNED NOT NULL DEFAULT '0',
  `contact_object_id` int(11) NOT NULL DEFAULT '0',
  `start_time` datetime NOT NULL DEFAULT '1970-01-01 00:00:00',
  `start_time_usec` int(11) NOT NULL DEFAULT '0',
  `end_time` datetime NOT NULL DEFAULT '1970-01-01 00:00:00',
  `end_time_usec` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`contactnotification_id`, `start_time`),
  KEY `start_time` (`start_time`),
  KEY `contact_object_id` (`contact_object_id`),
  KEY `notification_id` (`notification_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci COMMENT='Historical record of contact notifications'
PARTITION BY RANGE ( TO_DAYS(start_time) ) (
  PARTITION p_max VALUES LESS THAN (MAXVALUE)
);

-- mysql> show create table itnovum_workshop.nagios_contactnotifications\G

DROP TABLE IF EXISTS `nagios_contactnotificationmethods`;

CREATE TABLE IF NOT EXISTS `nagios_contactnotificationmethods` (
  `contactnotificationmethod_id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `instance_id` smallint(6) NOT NULL DEFAULT '0',
  `contactnotification_id` BIGINT(20) UNSIGNED NOT NULL DEFAULT '0',
  `start_time` datetime NOT NULL DEFAULT '1970-01-01 00:00:00',
  `start_time_usec` int(11) NOT NULL DEFAULT '0',
  `end_time` datetime NOT NULL DEFAULT '1970-01-01 00:00:00',
  `end_time_usec` int(11) NOT NULL DEFAULT '0',
  `command_object_id` int(11) NOT NULL DEFAULT '0',
  `command_args` varchar(1000) COLLATE utf8_swedish_ci DEFAULT '',
  PRIMARY KEY (`contactnotificationmethod_id`, `start_time`),
  KEY `start_time` (`start_time`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci COMMENT='Historical record of contact notification methods'
PARTITION BY RANGE ( TO_DAYS(start_time) ) (
  PARTITION p_max VALUES LESS THAN (MAXVALUE)
);

-- mysql> show create table itnovum_workshop.nagios_contactnotificationmethods\G
