-- Database schema for Statusengine 3
-- Source: https://github.com/statusengine/worker/blob/master/lib/mysql.sql
-- This file only contains tables that got partitioned

DROP TABLE IF EXISTS `statusengine_logentries`;

CREATE TABLE `statusengine_logentries`
(
    `id`            bigint(20) unsigned NOT NULL AUTO_INCREMENT,
    `entry_time`    bigint(20)          NOT NULL,
    `logentry_type` int(11)                              DEFAULT '0',
    `logentry_data` varchar(255) COLLATE utf8_general_ci DEFAULT NULL,
    `node_name`     varchar(255) COLLATE utf8_general_ci DEFAULT NULL,
    PRIMARY KEY (`id`, `entry_time`),
    KEY `logentries` (`entry_time`, `logentry_data`, `node_name`),
    KEY `logentry_data_time` (`logentry_data`, `entry_time`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8
  COLLATE = utf8_general_ci
    PARTITION BY RANGE ( entry_time DIV 86400) (
        PARTITION p_max VALUES LESS THAN ( MAXVALUE )
        );

--

DROP TABLE IF EXISTS `statusengine_host_statehistory`;

CREATE TABLE `statusengine_host_statehistory`
(
    `hostname`              varchar(255) COLLATE utf8_general_ci NOT NULL,
    `state_time`            bigint(20)                           NOT NULL,
    `state_change`          tinyint(1)                            DEFAULT '0',
    `state`                 smallint(5) unsigned                  DEFAULT '0',
    `is_hardstate`          tinyint(1)                            DEFAULT '0',
    `current_check_attempt` smallint(5) unsigned                  DEFAULT '0',
    `max_check_attempts`    smallint(5) unsigned                  DEFAULT '0',
    `last_state`            smallint(5) unsigned                  DEFAULT '0',
    `last_hard_state`       smallint(5) unsigned                  DEFAULT '0',
    `output`                varchar(1024) COLLATE utf8_general_ci DEFAULT NULL,
    `long_output`           varchar(8192) COLLATE utf8_general_ci DEFAULT NULL,
    PRIMARY KEY (`hostname`, `state_time`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8
  COLLATE = utf8_general_ci
    PARTITION BY RANGE ( state_time DIV 86400 ) (
        PARTITION p_max VALUES LESS THAN ( MAXVALUE )
        );

--

DROP TABLE IF EXISTS `statusengine_service_statehistory`;

CREATE TABLE `statusengine_service_statehistory`
(
    `hostname`              varchar(255) COLLATE utf8_general_ci NOT NULL,
    `service_description`   varchar(255) COLLATE utf8_general_ci NOT NULL,
    `state_time`            bigint(20)                           NOT NULL,
    `state_change`          tinyint(1)                            DEFAULT '0',
    `state`                 smallint(5) unsigned                  DEFAULT '0',
    `is_hardstate`          tinyint(1)                            DEFAULT '0',
    `current_check_attempt` smallint(5) unsigned                  DEFAULT '0',
    `max_check_attempts`    smallint(5) unsigned                  DEFAULT '0',
    `last_state`            smallint(5) unsigned                  DEFAULT '0',
    `last_hard_state`       smallint(5) unsigned                  DEFAULT '0',
    `output`                varchar(1024) COLLATE utf8_general_ci DEFAULT NULL,
    `long_output`           varchar(8192) COLLATE utf8_general_ci DEFAULT NULL,
    PRIMARY KEY (`hostname`, `service_description`, `state_time`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8
  COLLATE = utf8_general_ci
    PARTITION BY RANGE ( state_time DIV 86400 ) (
        PARTITION p_max VALUES LESS THAN ( MAXVALUE )
        );

--

DROP TABLE IF EXISTS `statusengine_hostchecks`;

CREATE TABLE `statusengine_hostchecks`
(
    `hostname`              varchar(255) COLLATE utf8_general_ci NOT NULL,
    `start_time`            bigint(20)                           NOT NULL,
    `state`                 smallint(5) unsigned                  DEFAULT '0',
    `is_hardstate`          tinyint(1)                            DEFAULT '0',
    `end_time`              bigint(20)                           NOT NULL,
    `output`                varchar(1024) COLLATE utf8_general_ci DEFAULT NULL,
    `timeout`               smallint(5) unsigned                  DEFAULT '0',
    `early_timeout`         tinyint(1)                            DEFAULT '0',
    `latency`               double                                DEFAULT '0',
    `execution_time`        double                                DEFAULT '0',
    `perfdata`              varchar(1024) COLLATE utf8_general_ci DEFAULT NULL,
    `command`               varchar(1024) COLLATE utf8_general_ci DEFAULT NULL,
    `current_check_attempt` smallint(5) unsigned                  DEFAULT '0',
    `max_check_attempts`    smallint(5) unsigned                  DEFAULT '0',
    `long_output`           varchar(8192) COLLATE utf8_general_ci DEFAULT NULL,
    PRIMARY KEY (`hostname`, `start_time`),
    KEY `times` (`start_time`, `end_time`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8
  COLLATE = utf8_general_ci
    PARTITION BY RANGE ( start_time DIV 86400 ) (
        PARTITION p_max VALUES LESS THAN ( MAXVALUE )
        );

--

DROP TABLE IF EXISTS `statusengine_servicechecks`;

CREATE TABLE `statusengine_servicechecks`
(
    `hostname`              varchar(255) COLLATE utf8_general_ci NOT NULL,
    `service_description`   varchar(255) COLLATE utf8_general_ci NOT NULL,
    `start_time`            bigint(20)                           NOT NULL,
    `state`                 smallint(5) unsigned                  DEFAULT '0',
    `is_hardstate`          tinyint(1)                            DEFAULT '0',
    `end_time`              bigint(20)                           NOT NULL,
    `output`                varchar(1024) COLLATE utf8_general_ci DEFAULT NULL,
    `timeout`               smallint(5) unsigned                  DEFAULT '0',
    `early_timeout`         tinyint(1)                            DEFAULT '0',
    `latency`               double                                DEFAULT '0',
    `execution_time`        double                                DEFAULT '0',
    `perfdata`              varchar(1024) COLLATE utf8_general_ci DEFAULT NULL,
    `command`               varchar(1024) COLLATE utf8_general_ci DEFAULT NULL,
    `current_check_attempt` smallint(5) unsigned                  DEFAULT '0',
    `max_check_attempts`    smallint(5) unsigned                  DEFAULT '0',
    `long_output`           varchar(8192) COLLATE utf8_general_ci DEFAULT NULL,
    PRIMARY KEY (`hostname`, `service_description`, `start_time`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8
  COLLATE = utf8_general_ci
    PARTITION BY RANGE ( start_time DIV 86400 ) (
        PARTITION p_max VALUES LESS THAN ( MAXVALUE )
        );

--

DROP TABLE IF EXISTS `statusengine_host_notifications`;

CREATE TABLE `statusengine_host_notifications`
(
    `id`           bigint(20) unsigned NOT NULL AUTO_INCREMENT,
    `hostname`     varchar(255) COLLATE utf8_general_ci  DEFAULT NULL,
    `contact_name` varchar(1024) COLLATE utf8_general_ci DEFAULT NULL,
    `command_name` varchar(1024) COLLATE utf8_general_ci DEFAULT NULL,
    `command_args` varchar(1024) COLLATE utf8_general_ci DEFAULT NULL,
    `state`        smallint(5) unsigned                  DEFAULT '0',
    `start_time`   bigint(20)          NOT NULL,
    `end_time`     bigint(20)          NOT NULL,
    `reason_type`  smallint(5) unsigned                  DEFAULT '0',
    `output`       varchar(1024) COLLATE utf8_general_ci DEFAULT NULL,
    `ack_author`   varchar(255) COLLATE utf8_general_ci  DEFAULT NULL,
    `ack_data`     varchar(1024) COLLATE utf8_general_ci DEFAULT NULL,
    PRIMARY KEY (`id`, `start_time`),
    KEY `start_time` (`start_time`),
    KEY `hostname` (`hostname`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8
  COLLATE = utf8_general_ci
    PARTITION BY RANGE ( start_time DIV 86400 ) (
        PARTITION p_max VALUES LESS THAN ( MAXVALUE )
        );

--

DROP TABLE IF EXISTS `statusengine_service_notifications`;

CREATE TABLE `statusengine_service_notifications`
(
    `id`                  bigint(20) unsigned NOT NULL AUTO_INCREMENT,
    `hostname`            varchar(255) COLLATE utf8_general_ci  DEFAULT NULL,
    `service_description` varchar(255) COLLATE utf8_general_ci  DEFAULT NULL,
    `contact_name`        varchar(1024) COLLATE utf8_general_ci DEFAULT NULL,
    `command_name`        varchar(1024) COLLATE utf8_general_ci DEFAULT NULL,
    `command_args`        varchar(1024) COLLATE utf8_general_ci DEFAULT NULL,
    `state`               smallint(5) unsigned                  DEFAULT '0',
    `start_time`          bigint(20)          NOT NULL,
    `end_time`            bigint(20)          NOT NULL,
    `reason_type`         smallint(5) unsigned                  DEFAULT '0',
    `output`              varchar(1024) COLLATE utf8_general_ci DEFAULT NULL,
    `ack_author`          varchar(255) COLLATE utf8_general_ci  DEFAULT NULL,
    `ack_data`            varchar(1024) COLLATE utf8_general_ci DEFAULT NULL,
    PRIMARY KEY (`id`, `start_time`),
    KEY `servicename` (`hostname`, `service_description`),
    KEY `start_time` (`start_time`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8
  COLLATE = utf8_general_ci
    PARTITION BY RANGE ( start_time DIV 86400 ) (
        PARTITION p_max VALUES LESS THAN ( MAXVALUE )
        );
