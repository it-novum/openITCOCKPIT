-- Database schema for Statusengine 3
-- Source: https://github.com/statusengine/worker/blob/master/lib/mysql.sql
-- This file only contains tables that got partitioned

DROP TABLE IF EXISTS `statusengine_logentries`;

CREATE TABLE `statusengine_logentries`
(
    `id`            bigint(20) unsigned NOT NULL AUTO_INCREMENT,
    `entry_time`    bigint(20)          NOT NULL,
    `logentry_type` int(11)                              DEFAULT '0',
    `logentry_data` varchar(2048) COLLATE utf8mb4_general_ci DEFAULT NULL,
    `node_name`     varchar(255)  COLLATE utf8mb4_general_ci DEFAULT NULL,
    PRIMARY KEY (`id`, `entry_time`),
    KEY `logentries_se` (`entry_time`, `node_name`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_general_ci
    PARTITION BY RANGE ( entry_time DIV 86400) (
        PARTITION p_max VALUES LESS THAN ( MAXVALUE )
        );

--

DROP TABLE IF EXISTS `statusengine_host_statehistory`;

CREATE TABLE `statusengine_host_statehistory`
(
    `hostname`              varchar(255)     NOT NULL,
    `state_time`            bigint(20)       NOT NULL,
    `state_time_usec`       int(10) unsigned NOT NULL DEFAULT '0',
    `state_change`          tinyint(1)                DEFAULT '0',
    `state`                 smallint(2)               DEFAULT '0',
    `is_hardstate`          tinyint(1)                DEFAULT '0',
    `current_check_attempt` smallint(3)               DEFAULT '0',
    `max_check_attempts`    smallint(3)               DEFAULT '0',
    `last_state`            smallint(2)               DEFAULT '0',
    `last_hard_state`       smallint(2)               DEFAULT '0',
    `output`                varchar(1024)             DEFAULT NULL,
    `long_output`           varchar(8192)             DEFAULT NULL,
    PRIMARY KEY (`hostname`, `state_time`, `state_time_usec`),
    KEY `hostname_time` (`hostname`, `state_time`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_general_ci
    PARTITION BY RANGE ( state_time DIV 86400 ) (
        PARTITION p_max VALUES LESS THAN ( MAXVALUE )
        );

--

DROP TABLE IF EXISTS `statusengine_service_statehistory`;

CREATE TABLE `statusengine_service_statehistory`
(
    `service_description`   varchar(255)     NOT NULL,
    `state_time`            bigint(20)       NOT NULL,
    `state_time_usec`       int(10) unsigned NOT NULL DEFAULT '0',
    `hostname`              varchar(255)              DEFAULT NULL,
    `state_change`          tinyint(1)                DEFAULT '0',
    `state`                 smallint(2)               DEFAULT '0',
    `is_hardstate`          tinyint(1)                DEFAULT '0',
    `current_check_attempt` smallint(3)               DEFAULT '0',
    `max_check_attempts`    smallint(3)               DEFAULT '0',
    `last_state`            smallint(2)               DEFAULT '0',
    `last_hard_state`       smallint(2)               DEFAULT '0',
    `output`                varchar(1024)             DEFAULT NULL,
    `long_output`           varchar(8192)             DEFAULT NULL,
    PRIMARY KEY (`service_description`, `state_time`, `state_time_usec`),
    KEY `host_servicename_time` (`hostname`, `service_description`, `state_time`),
    KEY `servicename_time` (`service_description`, `state_time`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_general_ci
    PARTITION BY RANGE ( state_time DIV 86400 ) (
        PARTITION p_max VALUES LESS THAN ( MAXVALUE )
        );

--

DROP TABLE IF EXISTS `statusengine_hostchecks`;

CREATE TABLE `statusengine_hostchecks`
(
    `hostname`              varchar(255)     NOT NULL,
    `start_time`            bigint(20)       NOT NULL,
    `start_time_usec`       int(10) unsigned NOT NULL DEFAULT '0',
    `state`                 smallint(2)               DEFAULT '0',
    `is_hardstate`          tinyint(1)                DEFAULT '0',
    `end_time`              bigint(20)       NOT NULL,
    `output`                varchar(1024)             DEFAULT NULL,
    `timeout`               smallint(3)               DEFAULT '0',
    `early_timeout`         tinyint(1)                DEFAULT '0',
    `latency`               double                    DEFAULT '0',
    `execution_time`        double                    DEFAULT '0',
    `perfdata`              varchar(2048)             DEFAULT NULL,
    `command`               varchar(1024)             DEFAULT NULL,
    `current_check_attempt` smallint(3)               DEFAULT '0',
    `max_check_attempts`    smallint(3)               DEFAULT '0',
    `long_output`           varchar(8192)             DEFAULT NULL,
    PRIMARY KEY (`hostname`, `start_time`, `start_time_usec`),
    KEY `hostname` (`hostname`, `start_time`),
    KEY `times` (`start_time`, `end_time`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_general_ci
    PARTITION BY RANGE ( start_time DIV 86400 ) (
        PARTITION p_max VALUES LESS THAN ( MAXVALUE )
        );

--

DROP TABLE IF EXISTS `statusengine_servicechecks`;

CREATE TABLE `statusengine_servicechecks`
(
    `service_description`   varchar(255)     NOT NULL,
    `start_time`            bigint(20)       NOT NULL,
    `start_time_usec`       int(10) unsigned NOT NULL DEFAULT '0',
    `hostname`              varchar(255)              DEFAULT NULL,
    `state`                 smallint(2)               DEFAULT '0',
    `is_hardstate`          tinyint(1)                DEFAULT '0',
    `end_time`              bigint(20)       NOT NULL,
    `output`                varchar(1024)             DEFAULT NULL,
    `timeout`               smallint(3)               DEFAULT '0',
    `early_timeout`         tinyint(1)                DEFAULT '0',
    `latency`               double                    DEFAULT '0',
    `execution_time`        double                    DEFAULT '0',
    `perfdata`              varchar(2048)             DEFAULT NULL,
    `command`               varchar(1024)             DEFAULT NULL,
    `current_check_attempt` smallint(3)               DEFAULT '0',
    `max_check_attempts`    smallint(3)               DEFAULT '0',
    `long_output`           varchar(8192)             DEFAULT NULL,
    PRIMARY KEY (`service_description`, `start_time`, `start_time_usec`),
    KEY `servicename` (`hostname`, `service_description`, `start_time`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_general_ci
    PARTITION BY RANGE ( start_time DIV 86400 ) (
        PARTITION p_max VALUES LESS THAN ( MAXVALUE )
        );

--

DROP TABLE IF EXISTS `statusengine_host_notifications`;

CREATE TABLE `statusengine_host_notifications`
(
    `hostname`        varchar(255)     NOT NULL,
    `start_time`      bigint(20)       NOT NULL,
    `start_time_usec` int(10) unsigned NOT NULL DEFAULT '0',
    `contact_name`    varchar(1024)             DEFAULT NULL,
    `command_name`    varchar(1024)             DEFAULT NULL,
    `command_args`    varchar(1024)             DEFAULT NULL,
    `state`           smallint(2)               DEFAULT '0',
    `end_time`        bigint(20)       NOT NULL,
    `reason_type`     smallint(3)               DEFAULT '0',
    `output`          varchar(1024)             DEFAULT NULL,
    `ack_author`      varchar(255)              DEFAULT NULL,
    `ack_data`        varchar(1024)             DEFAULT NULL,
    PRIMARY KEY (`hostname`, `start_time`, `start_time_usec`),
    KEY `hostname` (`hostname`),
    KEY `start_time` (`start_time`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_general_ci
    PARTITION BY RANGE ( start_time DIV 86400 ) (
        PARTITION p_max VALUES LESS THAN ( MAXVALUE )
        );

--

DROP TABLE IF EXISTS `statusengine_service_notifications`;

CREATE TABLE `statusengine_service_notifications`
(
    `service_description` varchar(255)     NOT NULL,
    `start_time`          bigint(20)       NOT NULL,
    `start_time_usec`     int(10) unsigned NOT NULL DEFAULT '0',
    `hostname`            varchar(255)              DEFAULT NULL,
    `contact_name`        varchar(1024)             DEFAULT NULL,
    `command_name`        varchar(1024)             DEFAULT NULL,
    `command_args`        varchar(1024)             DEFAULT NULL,
    `state`               smallint(2)               DEFAULT '0',
    `end_time`            bigint(20)       NOT NULL,
    `reason_type`         smallint(3)               DEFAULT '0',
    `output`              varchar(1024)             DEFAULT NULL,
    `ack_author`          varchar(255)              DEFAULT NULL,
    `ack_data`            varchar(1024)             DEFAULT NULL,
    PRIMARY KEY (`service_description`, `start_time`, `start_time_usec`),
    KEY `start_time` (`start_time`),
    KEY `servicename` (`hostname`, `service_description`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_general_ci
    PARTITION BY RANGE ( start_time DIV 86400 ) (
        PARTITION p_max VALUES LESS THAN ( MAXVALUE )
        );

