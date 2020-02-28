-- Database schema for Statusengine 3
-- Source: https://github.com/statusengine/worker/blob/master/lib/mysql.sql
-- This file only contains tables that got partitioned

DROP TABLE IF EXISTS `statusengine_logentries`;

CREATE TABLE IF NOT EXISTS `statusengine_logentries` (
  `entry_time`    BIGINT(13) NOT NULL,
  `logentry_type` INT(11)      DEFAULT '0',
  `logentry_data` VARCHAR(255) DEFAULT NULL,
  `node_name`     VARCHAR(255) DEFAULT NULL,
  KEY `logentries` (`entry_time`, `logentry_data`, `node_name`),
  KEY `logentry_data_time` (`logentry_data`, `entry_time`)
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8
  PARTITION BY RANGE ( entry_time DIV 86400) (
  PARTITION p_max VALUES LESS THAN ( MAXVALUE )
  );

--

DROP TABLE IF EXISTS `statusengine_host_statehistory`;

CREATE TABLE `statusengine_host_statehistory` (
  `hostname`              VARCHAR(255),
  `state_time`            BIGINT(13) NOT NULL,
  `state_change`          TINYINT(1)          DEFAULT 0,
  `state`                 TINYINT(2) UNSIGNED DEFAULT 0,
  `is_hardstate`          TINYINT(1) UNSIGNED DEFAULT 0,
  `current_check_attempt` TINYINT(3) UNSIGNED DEFAULT 0,
  `max_check_attempts`    TINYINT(3) UNSIGNED DEFAULT 0,
  `last_state`            TINYINT(2) UNSIGNED DEFAULT 0,
  `last_hard_state`       TINYINT(2) UNSIGNED DEFAULT 0,
  `output`                VARCHAR(1024),
  `long_output`           VARCHAR(8192),
  KEY `hostname_time` (`hostname`, `state_time`)
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8
  COLLATE = utf8_general_ci
  PARTITION BY RANGE ( state_time DIV 86400 ) (
  PARTITION p_max VALUES LESS THAN ( MAXVALUE )
  );

--

DROP TABLE IF EXISTS `statusengine_service_statehistory`;

CREATE TABLE `statusengine_service_statehistory` (
  `hostname`              VARCHAR(255),
  `service_description`   VARCHAR(255),
  `state_time`            BIGINT(13) NOT NULL,
  `state_change`          TINYINT(1)          DEFAULT 0,
  `state`                 TINYINT(2) UNSIGNED DEFAULT 0,
  `is_hardstate`          TINYINT(1) UNSIGNED DEFAULT 0,
  `current_check_attempt` TINYINT(3) UNSIGNED DEFAULT 0,
  `max_check_attempts`    TINYINT(3) UNSIGNED DEFAULT 0,
  `last_state`            TINYINT(2) UNSIGNED DEFAULT 0,
  `last_hard_state`       TINYINT(2) UNSIGNED DEFAULT 0,
  `output`                VARCHAR(1024),
  `long_output`           VARCHAR(8192),
  KEY `host_servicename_time` (`hostname`, `service_description`, `state_time`),
  KEY `servicename_time` (`service_description`, `state_time`)

)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8
  COLLATE = utf8_general_ci
  PARTITION BY RANGE ( state_time DIV 86400 ) (
  PARTITION p_max VALUES LESS THAN ( MAXVALUE )
  );

--

DROP TABLE IF EXISTS `statusengine_hostchecks`;

CREATE TABLE `statusengine_hostchecks` (
  `hostname`              VARCHAR(255),
  `state`                 TINYINT(2) UNSIGNED DEFAULT 0,
  `is_hardstate`          TINYINT(1) UNSIGNED DEFAULT 0,
  `start_time`            BIGINT(13) NOT NULL,
  `end_time`              BIGINT(13) NOT NULL,
  `output`                VARCHAR(1024),
  `timeout`               TINYINT(3) UNSIGNED DEFAULT 0,
  `early_timeout`         TINYINT(1) UNSIGNED DEFAULT 0,
  `latency`               FLOAT               DEFAULT 0,
  `execution_time`        FLOAT               DEFAULT 0,
  `perfdata`              VARCHAR(2048),
  `command`               VARCHAR(1024),
  `current_check_attempt` TINYINT(3) UNSIGNED DEFAULT 0,
  `max_check_attempts`    TINYINT(3) UNSIGNED DEFAULT 0,
  `long_output`           VARCHAR(8192),
  KEY `times` (`start_time`, `end_time`),
  KEY `hostname` (`hostname`, `start_time`)
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8
  COLLATE = utf8_general_ci
  PARTITION BY RANGE ( start_time DIV 86400 ) (
  PARTITION p_max VALUES LESS THAN ( MAXVALUE )
  );

--

DROP TABLE IF EXISTS `statusengine_servicechecks`;

CREATE TABLE `statusengine_servicechecks` (
  `hostname`              VARCHAR(255),
  `service_description`   VARCHAR(255),
  `state`                 TINYINT(2) UNSIGNED DEFAULT 0,
  `is_hardstate`          TINYINT(1) UNSIGNED DEFAULT 0,
  `start_time`            BIGINT(13) NOT NULL,
  `end_time`              BIGINT(13) NOT NULL,
  `output`                VARCHAR(1024),
  `timeout`               TINYINT(3) UNSIGNED DEFAULT 0,
  `early_timeout`         TINYINT(1) UNSIGNED DEFAULT 0,
  `latency`               FLOAT               DEFAULT 0,
  `execution_time`        FLOAT               DEFAULT 0,
  `perfdata`              VARCHAR(2048),
  `command`               VARCHAR(1024),
  `current_check_attempt` TINYINT(3) UNSIGNED DEFAULT 0,
  `max_check_attempts`    TINYINT(3) UNSIGNED DEFAULT 0,
  `long_output`           VARCHAR(8192),
  KEY `servicename` (`hostname`, `service_description`, `start_time`)

)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8
  COLLATE = utf8_general_ci
  PARTITION BY RANGE ( start_time DIV 86400 ) (
  PARTITION p_max VALUES LESS THAN ( MAXVALUE )
  );

--

DROP TABLE IF EXISTS `statusengine_host_notifications`;

CREATE TABLE `statusengine_host_notifications` (
  `hostname`     VARCHAR(255),
  `contact_name` VARCHAR(1024),
  `command_name` VARCHAR(1024),
  `command_args` VARCHAR(1024),
  `state`        TINYINT(2) UNSIGNED DEFAULT 0,
  `start_time`   BIGINT(13) NOT NULL,
  `end_time`     BIGINT(13) NOT NULL,
  `reason_type`  TINYINT(3) UNSIGNED DEFAULT 0,
  `output`       VARCHAR(1024),
  `ack_author`   VARCHAR(255),
  `ack_data`     VARCHAR(1024),
  KEY `hostname` (`hostname`),
  KEY `start_time` (`start_time`)

)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8
  COLLATE = utf8_general_ci
  PARTITION BY RANGE ( start_time DIV 86400 ) (
  PARTITION p_max VALUES LESS THAN ( MAXVALUE )
  );

--

DROP TABLE IF EXISTS `statusengine_service_notifications`;

CREATE TABLE `statusengine_service_notifications` (
  `hostname`            VARCHAR(255),
  `service_description` VARCHAR(255),
  `contact_name`        VARCHAR(1024),
  `command_name`        VARCHAR(1024),
  `command_args`        VARCHAR(1024),
  `state`               TINYINT(2) UNSIGNED DEFAULT 0,
  `start_time`          BIGINT(13) NOT NULL,
  `end_time`            BIGINT(13) NOT NULL,
  `reason_type`         TINYINT(3) UNSIGNED DEFAULT 0,
  `output`              VARCHAR(1024),
  `ack_author`          VARCHAR(255),
  `ack_data`            VARCHAR(1024),
  KEY `servicename` (`hostname`, `service_description`),
  KEY `start_time` (`start_time`)

)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8
  COLLATE = utf8_general_ci
  PARTITION BY RANGE ( start_time DIV 86400 ) (
  PARTITION p_max VALUES LESS THAN ( MAXVALUE )
  );
