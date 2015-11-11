-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Erstellungszeit: 15. Jan 2015 um 19:20
-- Server Version: 5.5.41-0ubuntu0.14.04.1
-- PHP-Version: 5.5.9-1ubuntu4.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Datenbank: `openitcockpitmysql`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `calendars`
--

CREATE TABLE IF NOT EXISTS `calendars` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL DEFAULT '',
  `container_id` int(11) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQUE_NAME` (`container_id`,`name`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `calendar_holidays`
--

CREATE TABLE IF NOT EXISTS `calendar_holidays` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `calendar_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `date` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `changelogs`
--

CREATE TABLE IF NOT EXISTS `changelogs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `model` varchar(255) COLLATE utf8_swedish_ci NOT NULL,
  `action` varchar(255) COLLATE utf8_swedish_ci NOT NULL,
  `object_id` int(11) NOT NULL,
  `objecttype_id` int(11) DEFAULT NULL,
  `command_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `data` text COLLATE utf8_swedish_ci NOT NULL,
  `name` text COLLATE utf8_swedish_ci NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `created` (`created`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `commandarguments`
--

CREATE TABLE IF NOT EXISTS `commandarguments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `command_id` int(11) NOT NULL,
  `name` varchar(10) COLLATE utf8_swedish_ci NOT NULL,
  `human_name` varchar(255) COLLATE utf8_swedish_ci NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;

--
-- Daten für Tabelle `commandarguments`
--

INSERT INTO `commandarguments` (`id`, `command_id`, `name`, `human_name`, `created`, `modified`) VALUES
(1, 3, '$ARG1$', 'Warning', '2015-01-05 15:18:23', '2015-01-05 15:18:23'),
(2, 3, '$ARG2$', 'Critical', '2015-01-05 15:18:23', '2015-01-05 15:18:23'),
(3, 4, '$ARG1$', 'Warning', '2015-01-05 15:21:32', '2015-01-05 15:21:32'),
(4, 4, '$ARG2$', 'Critical', '2015-01-05 15:21:32', '2015-01-05 15:21:32'),
(5, 5, '$ARG1$', 'IP of DHCP server', '2015-01-15 23:16:23', '2015-01-15 23:16:23'),
(6, 5, '$ARG2$', 'IP we expect', '2015-01-15 23:16:23', '2015-01-15 23:16:23'),
(7, 5, '$ARG3$', 'Listening interface (i.e. eth0)', '2015-01-15 23:16:23', '2015-01-15 23:16:23'),
(8, 7, '$ARG1$', 'Warning (seconds)', '2015-01-15 23:18:48', '2015-01-15 23:52:08'),
(9, 7, '$ARG2$', 'Critical (seconds)', '2015-01-15 23:18:48', '2015-01-15 23:52:08'),
(10, 7, '$ARG3$', 'URL (default /)', '2015-01-15 23:18:48', '2015-01-15 23:52:08'),
(11, 8, '$ARG1$', 'Warning (seconds)', '2015-01-15 23:19:34', '2015-01-15 23:19:34'),
(12, 8, '$ARG2$', 'Critical (seconds)', '2015-01-15 23:19:34', '2015-01-15 23:19:34'),
(13, 8, '$ARG3$', 'URL (default /)', '2015-01-15 23:19:34', '2015-01-15 23:19:34'),
(14, 10, '$ARG1$', 'Port No', '2015-01-15 23:20:49', '2015-01-15 23:20:49'),
(15, 12, '$ARG1$', 'Warning (seconds)', '2015-01-15 23:22:04', '2015-01-15 23:22:04'),
(16, 12, '$ARG2$', 'Critical (seconds)', '2015-01-15 23:22:04', '2015-01-15 23:22:04'),
(17, 13, '$ARG1$', 'Warning (seconds)', '2015-01-15 23:23:10', '2015-01-15 23:23:10'),
(18, 13, '$ARG2$', 'Critical (seconds)', '2015-01-15 23:23:10', '2015-01-15 23:23:10'),
(19, 14, '$ARG1$', 'Warn offset (seconds)', '2015-01-15 23:32:59', '2015-01-15 23:32:59'),
(20, 14, '$ARG2$', 'Crit offset (seconds)', '2015-01-15 23:32:59', '2015-01-15 23:32:59'),
(21, 15, '$ARG1$', 'Warn offset (seconds)', '2015-01-15 23:34:21', '2015-01-15 23:34:21'),
(22, 15, '$ARG2$', 'Crit offset (seconds)', '2015-01-15 23:34:21', '2015-01-15 23:34:21'),
(23, 17, '$ARG1$', 'Warning (%)', '2015-01-15 23:36:34', '2015-01-15 23:36:34'),
(24, 17, '$ARG2$', 'Critical (%)', '2015-01-15 23:36:34', '2015-01-15 23:36:34'),
(25, 17, '$ARG3$', 'Moint point', '2015-01-15 23:36:34', '2015-01-15 23:36:34'),
(26, 18, '$ARG1$', 'Warning', '2015-01-15 23:37:22', '2015-01-15 23:37:22'),
(27, 18, '$ARG2$', 'Critical', '2015-01-15 23:37:22', '2015-01-15 23:37:22'),
(28, 19, '$ARG1$', 'Warn (5, 10, 15)', '2015-01-15 23:38:14', '2015-01-15 23:38:14'),
(29, 19, '$ARG2$', 'Crit (5, 10, 15)', '2015-01-15 23:38:14', '2015-01-15 23:38:14'),
(30, 20, '$ARG1$', 'Warning', '2015-01-15 23:38:52', '2015-01-15 23:38:52'),
(31, 20, '$ARG2$', 'Critical', '2015-01-15 23:38:52', '2015-01-15 23:38:52'),
(32, 21, '$ARG1$', 'Warning (Range)', '2015-01-15 23:40:29', '2015-01-16 00:01:49'),
(33, 21, '$ARG2$', 'Critical (Range)', '2015-01-15 23:40:29', '2015-01-16 00:01:49'),
(34, 21, '$ARG3$', 'Name', '2015-01-15 23:40:29', '2015-01-16 00:01:49'),
(35, 22, '$ARG1$', 'Warning (Range)', '2015-01-15 23:41:07', '2015-01-15 23:41:07'),
(36, 22, '$ARG2$', 'Critical (Range)', '2015-01-15 23:41:07', '2015-01-15 23:41:07'),
(37, 23, '$ARG1$', 'Username', '2015-01-15 23:42:43', '2015-01-15 23:42:43'),
(38, 23, '$ARG2$', 'Command', '2015-01-15 23:42:43', '2015-01-15 23:42:43'),
(39, 16, '$ARG1$', 'ARG1', '2015-01-16 00:17:45', '2015-01-16 00:17:45');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `commands`
--

CREATE TABLE IF NOT EXISTS `commands` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_swedish_ci DEFAULT NULL,
  `command_line` text COLLATE utf8_swedish_ci,
  `command_type` int(1) NOT NULL,
  `human_args` text COLLATE utf8_swedish_ci,
  `uuid` varchar(37) COLLATE utf8_swedish_ci NOT NULL DEFAULT '',
  `description` text COLLATE utf8_swedish_ci,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;

--
-- Daten für Tabelle `commands`
--

INSERT INTO `commands` (`id`, `name`, `command_line`, `command_type`, `human_args`, `uuid`, `description`) VALUES
(1, 'host-notify-by-cake', '/usr/share/openitcockpit/app/Console/cake nagios_notification -q --type Host --notificationtype $NOTIFICATIONTYPE$ --hostname "$HOSTNAME$" --hoststate "$HOSTSTATE$" --hostaddress "$HOSTADDRESS$" --hostoutput "$HOSTOUTPUT$" --contactmail "$CONTACTEMAIL$" --contactalias "$CONTACTALIAS$"', 3, NULL, 'a13ff7f1-0642-4a11-be05-9931ca98da10', 'Send a host notification as mail'),
(2, 'service-notify-by-cake', '/usr/share/openitcockpit/app/Console/cake nagios_notification -q --type Service --notificationtype $NOTIFICATIONTYPE$ --hostname "$HOSTNAME$" --hoststate "$HOSTSTATE$" --hostaddress "$HOSTADDRESS$" --hostoutput "$HOSTOUTPUT$" --contactmail "$CONTACTEMAIL$" --contactalias "$CONTACTALIAS$" --servicedesc "$SERVICEDESC$" --servicestate "$SERVICESTATE$" --serviceoutput "$SERVICEOUTPUT$"', 3, NULL, 'a517bbb6-f299-4b57-9865-a4e0b70597e4', 'Send a service notificationa s mail'),
(3, 'check_ping', '$USER1$/check_ping -H $HOSTADDRESS$ -w $ARG1$ -c $ARG2$ -p 5', 1, NULL, 'cdd9ba25-a4d8-4261-a551-32164d4dde14', ''),
(4, 'check-host-alive', '$USER1$/check_icmp -H $HOSTADDRESS$ -w $ARG1$ -c $ARG2$ -p 1', 2, NULL, '5a538ebc-03de-4ce6-8e32-665b841abde3', ''),
(5, 'check_dhcp', '$USER1$/check_dhcp -s $ARG1$ -r $ARG2$ -i $ARG3$', 1, NULL, '685d5fe1-8847-4df8-8b0e-57e9d8d8def1', 'This plugin tests the availability of DHCP servers on a network.'),
(6, 'check_ftp', '$USER1$/check_ftp -H $HOSTADDRESS$', 1, NULL, 'c63955f5-b971-4bc7-b8e4-eacf991f7a2c', 'This plugin tests FTP connections with the specified host (or unix socket).'),
(7, 'check_http', '$USER1$/check_http -H $HOSTADDRESS$ -w $ARG1$ -c $ARG2$ -u $ARG3$', 1, NULL, '9f83fd32-0718-459b-a99e-977e0282da2d', 'This plugin tests the HTTP service on the specified host. It can test\r\nnormal (http) and secure (https) servers, follow redirects, search for\r\nstrings and regular expressions, check connection times, and report on\r\ncertificate expiration times.'),
(8, 'check_https', '$USER1$/check_http -H $HOSTADDRESS$ -w $ARG1$ -c $ARG2$ -u $ARG3$ -S -p 443 -L', 1, NULL, 'fd4ee227-e672-42e2-a21f-d3a2076bb899', 'This plugin tests the HTTP service on the specified host. It can test\r\nnormal (http) and secure (https) servers, follow redirects, search for\r\nstrings and regular expressions, check connection times, and report on\r\ncertificate expiration times.'),
(9, 'check_telnet', '$USER1$/check_tcp -H $HOSTADDRESS$ -p 23', 1, NULL, 'db5f1e3c-7031-420e-9998-1520119fc28d', 'This plugin tests TCP connections with the specified host (or unix socket) on port 23 for telnet'),
(10, 'check_tcp', '$USER1$/check_tcp -H $HOSTADDRESS$ -p $ARG1$', 1, NULL, '9e659fe6-9f92-4af4-8f4d-905a2e2e6073', 'This plugin tests TCP connections with the specified host (or unix socket).'),
(11, 'check_ssh', '$USER1$/check_ssh $HOSTADDRESS$', 1, NULL, 'a62423e1-b6ca-4b55-8374-08c73d7b38f9', 'Try to connect to an SSH server at specified server and port'),
(12, 'check_smtp', '$USER1$/check_smtp -H $HOSTADDRESS$ -w $ARG1$ -c $ARG2$', 1, NULL, '72d2f4fc-7913-4a0e-8163-fe0810867318', 'This plugin will attempt to open an SMTP connection with the host.'),
(13, 'check_pop', '$USER1$/check_pop -H $HOSTADDRESS$ -w $ARG1$ -c $ARG2$', 1, NULL, 'a19098ba-ca85-4d84-8f11-aecf8f489571', 'This plugin tests POP connections with the specified host (or unix socket).'),
(14, 'check_ntp_time', '$USER1$/check_ntp_time -H $HOSTADDRESS$ -w $ARG1$ -c $ARG2$', 1, NULL, 'bd682bb8-9bd0-4272-aec3-2fa745b47fbc', 'This plugin checks the clock offset with the ntp server'),
(15, 'check_ntp_peer', '$USER1$/check_ntp_peer -H $HOSTADDRESS$ -w $ARG1$ -c $ARG2$', 1, NULL, '8dd4b139-6912-4ccb-af00-14025eedf2da', 'This plugin checks the selected ntp server'),
(16, 'check_none', '$USER1$/check_none $ARG1$', 1, NULL, '6ea1e4a4-996f-4673-9099-7c9235aca11b', 'This checks nothing'),
(17, 'check_local_disk', '$USER1$/check_disk -w $ARG1$ -c $ARG2$ -p $ARG3$', 1, NULL, '74a59dd0-2eff-4f41-9bcd-3e8786f34f04', 'This plugin checks the amount of used disk space on a mounted file system\r\nand generates an alert if free space is less than one of the threshold values'),
(18, 'check_local_users', '$USER1$/check_users -w $ARG1$ -c $ARG2$', 1, NULL, 'dd9f7422-1390-4b31-981b-c22bf4dfd00a', 'This plugin checks the number of users currently logged in on the local\r\nsystem and generates an error if the number exceeds the thresholds specified.'),
(19, 'check_local_load', '$USER1$/check_load -w $ARG1$ -c $ARG2$', 1, NULL, '84084403-5c21-4273-835b-d8ac770b4a9f', 'This plugin tests the current system load average.'),
(20, 'check_local_mailq', '$USER1$/check_mailq -w $ARG1$ -c $ARG2$', 1, NULL, '9da5cee2-0408-4d47-ab05-8b9ff33b6a9b', 'Checks the number of messages in the mail queue (supports multiple sendmail queues, qmail)\r\nFeedback/patches to support non-sendmail mailqueue welcome'),
(21, 'check_local_procs', '$USER1$/check_procs -w $ARG1$ -c $ARG2$ -a $ARG3$', 1, NULL, '2a8a0335-15fd-40d5-8f5a-40ee25c85a8a', 'Checks all processes and generates WARNING or CRITICAL states if the specified\r\nmetric is outside the required threshold ranges. The metric defaults to number\r\nof processes.  Search filters can be applied to limit the processes to check.'),
(22, 'check_local_procs_total', '$USER1$/check_procs -w $ARG1$ -c $ARG2$', 1, NULL, '62b42c52-3103-427e-bbb2-8ad4e5db6563', 'Checks all processes and generates WARNING or CRITICAL states if the specified\r\nmetric is outside the required threshold ranges. The metric defaults to number\r\nof processes.  Search filters can be applied to limit the processes to check.'),
(23, 'check_by_ssh', '$USER1$/check_by_ssh -H $HOSTADDRESS$ -l $ARG1$ -C $ARG2$', 1, NULL, 'fd52f68f-e0f9-4c69-9f69-bc1b2d97188a', 'This plugin uses SSH to execute commands on a remote host'),
(24, 'check_mk_active', 'python /opt/openitc/nagios/3rd/check_mk/var/precompiled/$HOSTNAME$.py', 1, NULL, 'e02cbbc7-aa72-42e2-b7c6-fb4fb7b6519b', 'Execute a check_mk python file, generated by check_mk and openITCOCKPIT');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `contactgroups`
--

CREATE TABLE IF NOT EXISTS `contactgroups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uuid` varchar(37) COLLATE utf8_swedish_ci NOT NULL,
  `container_id` int(11) NOT NULL,
  `description` varchar(255) COLLATE utf8_swedish_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uuid` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `contactgroups_to_hostescalations`
--

CREATE TABLE IF NOT EXISTS `contactgroups_to_hostescalations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `contactgroup_id` int(11) NOT NULL,
  `hostescalation_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `contactgroup_id` (`contactgroup_id`),
  KEY `hostescalation_id` (`hostescalation_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `contactgroups_to_hosts`
--

CREATE TABLE IF NOT EXISTS `contactgroups_to_hosts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `contactgroup_id` int(11) NOT NULL,
  `host_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `contactgroup_id` (`contactgroup_id`),
  KEY `host_id` (`host_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `contactgroups_to_hosttemplates`
--

CREATE TABLE IF NOT EXISTS `contactgroups_to_hosttemplates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `contactgroup_id` int(11) NOT NULL,
  `hosttemplate_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `contactgroup_id` (`contactgroup_id`),
  KEY `hosttemplate_id` (`hosttemplate_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `contactgroups_to_serviceescalations`
--

CREATE TABLE IF NOT EXISTS `contactgroups_to_serviceescalations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `contactgroup_id` int(11) NOT NULL,
  `serviceescalation_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `contactgroup_id` (`contactgroup_id`),
  KEY `serviceescalation_id` (`serviceescalation_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `contactgroups_to_services`
--

CREATE TABLE IF NOT EXISTS `contactgroups_to_services` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `contactgroup_id` int(11) NOT NULL,
  `service_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `contactgroup_id` (`contactgroup_id`),
  KEY `service_id` (`service_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `contactgroups_to_servicetemplates`
--

CREATE TABLE IF NOT EXISTS `contactgroups_to_servicetemplates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `contactgroup_id` int(11) NOT NULL,
  `servicetemplate_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `contactgroup_id` (`contactgroup_id`),
  KEY `servicetemplate_id` (`servicetemplate_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `contacts`
--

CREATE TABLE IF NOT EXISTS `contacts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uuid` varchar(37) COLLATE utf8_swedish_ci NOT NULL,
  `name` varchar(64) COLLATE utf8_swedish_ci NOT NULL,
  `description` varchar(255) COLLATE utf8_swedish_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_swedish_ci NOT NULL,
  `phone` varchar(64) COLLATE utf8_swedish_ci NOT NULL,
  `host_timeperiod_id` int(11) NOT NULL DEFAULT '0',
  `service_timeperiod_id` int(11) NOT NULL DEFAULT '0',
  `host_notifications_enabled` int(1) NOT NULL DEFAULT '0',
  `service_notifications_enabled` int(1) NOT NULL DEFAULT '0',
  `can_submit_commands` int(1) NOT NULL DEFAULT '0',
  `notify_service_recovery` int(1) NOT NULL DEFAULT '0',
  `notify_service_warning` int(1) NOT NULL DEFAULT '0',
  `notify_service_unknown` int(1) NOT NULL DEFAULT '0',
  `notify_service_critical` int(1) NOT NULL DEFAULT '0',
  `notify_service_flapping` int(1) NOT NULL DEFAULT '0',
  `notify_service_downtime` int(1) NOT NULL DEFAULT '0',
  `notify_host_recovery` int(1) NOT NULL DEFAULT '0',
  `notify_host_down` int(1) NOT NULL DEFAULT '0',
  `notify_host_unreachable` int(1) NOT NULL DEFAULT '0',
  `notify_host_flapping` int(1) NOT NULL DEFAULT '0',
  `notify_host_downtime` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uuid` (`uuid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;

--
-- Daten für Tabelle `contacts`
--

INSERT INTO `contacts` (`id`, `uuid`, `name`, `description`, `email`, `phone`, `host_timeperiod_id`, `service_timeperiod_id`, `host_notifications_enabled`, `service_notifications_enabled`, `can_submit_commands`, `notify_service_recovery`, `notify_service_warning`, `notify_service_unknown`, `notify_service_critical`, `notify_service_flapping`, `notify_service_downtime`, `notify_host_recovery`, `notify_host_down`, `notify_host_unreachable`, `notify_host_flapping`, `notify_host_downtime`) VALUES
(1, '152aecaf-e981-4b0b-8e05-86972868547d', 'info', 'info contact', 'openitcockpit@localhost.local', '', 1, 1, 1, 1, 0, 1, 1, 1, 1, 0, 0, 1, 1, 1, 0, 0);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `contacts_to_contactgroups`
--

CREATE TABLE IF NOT EXISTS `contacts_to_contactgroups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `contact_id` int(11) NOT NULL,
  `contactgroup_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `contact_id` (`contact_id`),
  KEY `contactgroup_id` (`contactgroup_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `contacts_to_containers`
--

CREATE TABLE IF NOT EXISTS `contacts_to_containers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `contact_id` int(11) NOT NULL,
  `container_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `contact_id` (`contact_id`),
  KEY `container_id` (`container_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;

--
-- Daten für Tabelle `contacts_to_containers`
--

INSERT INTO `contacts_to_containers` (`id`, `contact_id`, `container_id`) VALUES
(2, 1, 1);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `contacts_to_hostcommands`
--

CREATE TABLE IF NOT EXISTS `contacts_to_hostcommands` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `contact_id` int(11) NOT NULL,
  `command_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `contact_id` (`contact_id`),
  KEY `command_id` (`command_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;

--
-- Daten für Tabelle `contacts_to_hostcommands`
--

INSERT INTO `contacts_to_hostcommands` (`id`, `contact_id`, `command_id`) VALUES
(2, 1, 1);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `contacts_to_hostescalations`
--

CREATE TABLE IF NOT EXISTS `contacts_to_hostescalations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `contact_id` int(11) NOT NULL,
  `hostescalation_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `contact_id` (`contact_id`),
  KEY `hostescalation_id` (`hostescalation_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `contacts_to_hosts`
--

CREATE TABLE IF NOT EXISTS `contacts_to_hosts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `contact_id` int(11) NOT NULL,
  `host_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `contact_id` (`contact_id`),
  KEY `host_id` (`host_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `contacts_to_hosttemplates`
--

CREATE TABLE IF NOT EXISTS `contacts_to_hosttemplates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `contact_id` int(11) NOT NULL,
  `hosttemplate_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `contact_id` (`contact_id`),
  KEY `hosttemplate_id` (`hosttemplate_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;

--
-- Daten für Tabelle `contacts_to_hosttemplates`
--

INSERT INTO `contacts_to_hosttemplates` (`id`, `contact_id`, `hosttemplate_id`) VALUES
(1, 1, 1);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `contacts_to_servicecommands`
--

CREATE TABLE IF NOT EXISTS `contacts_to_servicecommands` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `contact_id` int(11) NOT NULL,
  `command_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `contact_id` (`contact_id`),
  KEY `command_id` (`command_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;

--
-- Daten für Tabelle `contacts_to_servicecommands`
--

INSERT INTO `contacts_to_servicecommands` (`id`, `contact_id`, `command_id`) VALUES
(2, 1, 2);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `contacts_to_serviceescalations`
--

CREATE TABLE IF NOT EXISTS `contacts_to_serviceescalations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `contact_id` int(11) NOT NULL,
  `serviceescalation_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `contact_id` (`contact_id`),
  KEY `serviceescalation_id` (`serviceescalation_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `contacts_to_services`
--

CREATE TABLE IF NOT EXISTS `contacts_to_services` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `contact_id` int(11) NOT NULL,
  `service_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `contact_id` (`contact_id`),
  KEY `service_id` (`service_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `contacts_to_servicetemplates`
--

CREATE TABLE IF NOT EXISTS `contacts_to_servicetemplates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `contact_id` int(11) NOT NULL,
  `servicetemplate_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `contact_id` (`contact_id`),
  KEY `servicetemplate_id` (`servicetemplate_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;

--
-- Daten für Tabelle `contacts_to_servicetemplates`
--

INSERT INTO `contacts_to_servicetemplates` (`id`, `contact_id`, `servicetemplate_id`) VALUES
(2, 1, 2),
(3, 1, 1),
(4, 1, 3),
(5, 1, 4),
(6, 1, 5),
(7, 1, 6),
(8, 1, 7),
(9, 1, 8),
(10, 1, 9),
(11, 1, 10),
(12, 1, 11),
(13, 1, 12),
(14, 1, 13),
(15, 1, 14),
(16, 1, 15),
(17, 1, 16),
(18, 1, 17),
(19, 1, 18),
(20, 1, 19);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `containers`
--

CREATE TABLE IF NOT EXISTS `containers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `containertype_id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_swedish_ci NOT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `lft` int(11) NOT NULL,
  `rght` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;

--
-- Daten für Tabelle `containers`
--

INSERT INTO `containers` (`id`, `containertype_id`, `name`, `parent_id`, `lft`, `rght`) VALUES
(1, 1, 'root', NULL, 1, 2);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `cronjobs`
--

CREATE TABLE IF NOT EXISTS `cronjobs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `task` varchar(255) COLLATE utf8_swedish_ci NOT NULL,
  `plugin` varchar(255) COLLATE utf8_swedish_ci NOT NULL DEFAULT 'Core',
  `interval` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;

--
-- Daten für Tabelle `cronjobs`
--

INSERT INTO `cronjobs` (`id`, `task`, `plugin`, `interval`) VALUES
(1, 'CleanupTemp', 'Core', 10),
(2, 'DatabaseCleanup', 'Core', 1440),
(3, 'RecurringDowntimes', 'Core', 10);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `cronschedules`
--

CREATE TABLE IF NOT EXISTS `cronschedules` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cronjob_id` int(11) DEFAULT NULL,
  `is_running` int(11) DEFAULT NULL,
  `start_time` datetime NOT NULL,
  `end_time` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;

--
-- Daten für Tabelle `cronschedules`
--

INSERT INTO `cronschedules` (`id`, `cronjob_id`, `is_running`, `start_time`, `end_time`) VALUES
(1, 1, 0, '2015-01-16 00:52:01', '2015-01-16 00:52:01'),
(2, 2, 0, '2015-01-16 00:43:01', '2015-01-16 00:43:02'),
(3, 3, 0, '2015-01-16 00:43:02', '2015-01-16 00:43:02');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `customvariables`
--

CREATE TABLE IF NOT EXISTS `customvariables` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `object_id` int(11) NOT NULL,
  `objecttype_id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_swedish_ci NOT NULL,
  `value` varchar(255) COLLATE utf8_swedish_ci NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `dashboards`
--

-- CREATE TABLE IF NOT EXISTS `dashboards` (
--  `id` int(11) NOT NULL AUTO_INCREMENT,
--  PRIMARY KEY (`id`)
-- ) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `deleted_hosts`
--

CREATE TABLE IF NOT EXISTS `deleted_hosts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uuid` varchar(37) COLLATE utf8_swedish_ci NOT NULL,
  `hosttemplate_id` int(11) NOT NULL,
  `host_id` int(11) NOT NULL,
  `name` varchar(64) COLLATE utf8_swedish_ci NOT NULL,
  `description` varchar(255) COLLATE utf8_swedish_ci DEFAULT NULL,
  `deleted_perfdata` int(1) DEFAULT '0',
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uuid` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `deleted_services`
--

CREATE TABLE IF NOT EXISTS `deleted_services` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uuid` varchar(37) COLLATE utf8_swedish_ci NOT NULL,
  `host_uuid` varchar(37) COLLATE utf8_swedish_ci NOT NULL,
  `servicetemplate_id` int(11) NOT NULL,
  `host_id` int(11) NOT NULL,
  `name` varchar(64) COLLATE utf8_swedish_ci NOT NULL,
  `description` varchar(255) COLLATE utf8_swedish_ci DEFAULT NULL,
  `deleted_perfdata` int(1) DEFAULT '0',
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uuid` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `documentations`
--

CREATE TABLE IF NOT EXISTS `documentations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uuid` varchar(37) COLLATE utf8_swedish_ci NOT NULL,
  `content` text COLLATE utf8_swedish_ci,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uuid` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `exports`
--

CREATE TABLE IF NOT EXISTS `exports` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `object_id` int(11) NOT NULL,
  `objecttype_id` int(11) NOT NULL,
  `action` varchar(255) COLLATE utf8_swedish_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `graphgen_tmpls`
--

CREATE TABLE IF NOT EXISTS `graphgen_tmpls` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL,
  `relative_time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `graphgen_tmpl_confs`
--

CREATE TABLE IF NOT EXISTS `graphgen_tmpl_confs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `graphgen_tmpl_id` int(11) NOT NULL,
  `service_id` int(11) NOT NULL,
  `data_sources` varchar(256) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `graph_collections`
--

CREATE TABLE IF NOT EXISTS `graph_collections` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQUE_NAME` (`id`,`name`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `graph_tmpl_to_graph_collection`
--

CREATE TABLE IF NOT EXISTS `graph_tmpl_to_graph_collection` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `graphgen_tmpl_id` int(11) NOT NULL,
  `graph_collection_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `hostcommandargumentvalues`
--

CREATE TABLE IF NOT EXISTS `hostcommandargumentvalues` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `commandargument_id` int(11) NOT NULL,
  `host_id` int(11) NOT NULL,
  `value` varchar(500) COLLATE utf8_swedish_ci NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `hostdependencies`
--

CREATE TABLE IF NOT EXISTS `hostdependencies` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uuid` varchar(37) COLLATE utf8_swedish_ci NOT NULL,
  `inherits_parent` int(1) NOT NULL DEFAULT '0',
  `timeperiod_id` int(11) DEFAULT NULL,
  `execution_fail_on_up` int(1) NOT NULL,
  `execution_fail_on_down` int(1) NOT NULL,
  `execution_fail_on_unreachable` int(1) NOT NULL,
  `execution_fail_on_pending` int(1) NOT NULL,
  `execution_none` int(1) NOT NULL,
  `notification_fail_on_up` int(1) NOT NULL,
  `notification_fail_on_down` int(1) NOT NULL,
  `notification_fail_on_unreachable` int(1) NOT NULL,
  `notification_fail_on_pending` int(1) NOT NULL,
  `notification_none` int(1) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `hostescalations`
--

CREATE TABLE IF NOT EXISTS `hostescalations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uuid` varchar(37) COLLATE utf8_swedish_ci NOT NULL,
  `timeperiod_id` int(11) NOT NULL,
  `first_notification` int(6) NOT NULL,
  `last_notification` int(6) NOT NULL,
  `notification_interval` int(6) NOT NULL,
  `escalate_on_recovery` int(1) NOT NULL,
  `escalate_on_down` int(1) NOT NULL,
  `escalate_on_unreachable` int(1) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `hostgroups`
--

CREATE TABLE IF NOT EXISTS `hostgroups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uuid` varchar(37) COLLATE utf8_swedish_ci NOT NULL,
  `container_id` int(6) NOT NULL DEFAULT '0',
  `description` varchar(255) COLLATE utf8_swedish_ci NOT NULL,
  `hostgroup_url` varchar(255) COLLATE utf8_swedish_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uuid` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `hostgroups_to_hostdependencies`
--

CREATE TABLE IF NOT EXISTS `hostgroups_to_hostdependencies` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `hostgroup_id` int(11) NOT NULL,
  `hostdependency_id` int(11) NOT NULL,
  `dependent` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `hostgroup_id` (`hostgroup_id`,`dependent`),
  KEY `hostdependency_id` (`hostdependency_id`,`dependent`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `hostgroups_to_hostescalations`
--

CREATE TABLE IF NOT EXISTS `hostgroups_to_hostescalations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `hostgroup_id` int(11) NOT NULL,
  `hostescalation_id` int(11) NOT NULL,
  `excluded` int(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `hostgroup_id` (`hostgroup_id`,`excluded`),
  KEY `hostescalation_id` (`hostescalation_id`,`excluded`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `hosts`
--

CREATE TABLE IF NOT EXISTS `hosts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uuid` varchar(37) COLLATE utf8_swedish_ci NOT NULL,
  `container_id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_swedish_ci NOT NULL,
  `description` varchar(255) COLLATE utf8_swedish_ci DEFAULT NULL,
  `hosttemplate_id` int(11) NOT NULL,
  `address` varchar(128) COLLATE utf8_swedish_ci NOT NULL,
  `command_id` int(11) DEFAULT NULL,
  `eventhandler_command_id` int(11) DEFAULT NULL,
  `timeperiod_id` int(11) DEFAULT NULL,
  `check_interval` int(5) DEFAULT NULL,
  `retry_interval` int(5) DEFAULT NULL,
  `max_check_attempts` int(3) DEFAULT NULL,
  `first_notification_delay` float DEFAULT NULL,
  `notification_interval` float DEFAULT NULL,
  `notify_on_down` int(1) DEFAULT NULL,
  `notify_on_unreachable` int(1) DEFAULT NULL,
  `notify_on_recovery` int(1) DEFAULT NULL,
  `notify_on_flapping` int(1) DEFAULT NULL,
  `notify_on_downtime` int(1) DEFAULT NULL,
  `flap_detection_enabled` int(1) DEFAULT NULL,
  `flap_detection_on_up` int(1) DEFAULT NULL,
  `flap_detection_on_down` int(1) DEFAULT NULL,
  `flap_detection_on_unreachable` int(1) DEFAULT NULL,
  `low_flap_threshold` float DEFAULT NULL,
  `high_flap_threshold` float DEFAULT NULL,
  `process_performance_data` int(6) DEFAULT NULL,
  `freshness_checks_enabled` int(6) DEFAULT NULL,
  `freshness_threshold` int(8) DEFAULT NULL,
  `passive_checks_enabled` int(1) DEFAULT NULL,
  `event_handler_enabled` int(1) DEFAULT NULL,
  `active_checks_enabled` int(1) DEFAULT NULL,
  `retain_status_information` int(6) DEFAULT NULL,
  `retain_nonstatus_information` int(6) DEFAULT NULL,
  `notifications_enabled` int(6) DEFAULT NULL,
  `notes` varchar(255) COLLATE utf8_swedish_ci DEFAULT NULL,
  `priority` int(2) DEFAULT NULL,
  `check_period_id` int(11) DEFAULT NULL,
  `notify_period_id` int(11) DEFAULT NULL,
  `tags` varchar(255) COLLATE utf8_swedish_ci DEFAULT NULL,
  `own_contacts` int(1) NOT NULL DEFAULT '0',
  `own_contactgroups` int(1) NOT NULL DEFAULT '0',
  `own_customvariables` int(1) NOT NULL DEFAULT '0',
  `host_url` varchar(255) COLLATE utf8_swedish_ci DEFAULT NULL,
  `satellite_id` int(11) DEFAULT '0',
  `host_type` int(11) NOT NULL DEFAULT '1',
  `disabled` int(1) DEFAULT '0',
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uuid` (`uuid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;

--
-- Daten für Tabelle `hosts`
--

INSERT INTO `hosts` (`id`, `uuid`, `container_id`, `name`, `description`, `hosttemplate_id`, `address`, `command_id`, `eventhandler_command_id`, `timeperiod_id`, `check_interval`, `retry_interval`, `max_check_attempts`, `first_notification_delay`, `notification_interval`, `notify_on_down`, `notify_on_unreachable`, `notify_on_recovery`, `notify_on_flapping`, `notify_on_downtime`, `flap_detection_enabled`, `flap_detection_on_up`, `flap_detection_on_down`, `flap_detection_on_unreachable`, `low_flap_threshold`, `high_flap_threshold`, `process_performance_data`, `freshness_checks_enabled`, `freshness_threshold`, `passive_checks_enabled`, `event_handler_enabled`, `active_checks_enabled`, `retain_status_information`, `retain_nonstatus_information`, `notifications_enabled`, `notes`, `priority`, `check_period_id`, `notify_period_id`, `tags`, `own_contacts`, `own_contactgroups`, `own_customvariables`, `host_url`, `satellite_id`, `host_type`, `disabled`, `created`, `modified`) VALUES
(1, 'c36b8048-93ce-4385-ac19-ab5c90574b77', 1, 'localhost', NULL, 1, '127.0.0.1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, '', 0, 1, 0, '2015-01-15 19:26:32', '2015-01-15 19:26:32');

-- --------------------------------------------------------


--
-- Tabellenstruktur für Tabelle `hosts_to_containers`
--

CREATE TABLE IF NOT EXISTS `hosts_to_containers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `host_id` int(11) NOT NULL,
  `container_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `host_id` (`host_id`),
  KEY `container_id` (`container_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;

--
-- Daten für Tabelle `hosts_to_containers`
--

INSERT INTO `hosts_to_containers` (`id`, `host_id`, `container_id`) VALUES
(1, 1, 1);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `hosts_to_hostdependencies`
--

CREATE TABLE IF NOT EXISTS `hosts_to_hostdependencies` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `host_id` int(11) NOT NULL,
  `hostdependency_id` int(11) NOT NULL,
  `dependent` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `host_id` (`host_id`,`dependent`),
  KEY `hostdependency_id` (`hostdependency_id`,`dependent`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `hosts_to_hostescalations`
--

CREATE TABLE IF NOT EXISTS `hosts_to_hostescalations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `host_id` int(11) NOT NULL,
  `hostescalation_id` int(11) NOT NULL,
  `excluded` int(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `host_id` (`host_id`,`excluded`),
  KEY `hostescalation_id` (`hostescalation_id`,`excluded`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `hosts_to_hostgroups`
--

CREATE TABLE IF NOT EXISTS `hosts_to_hostgroups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `host_id` int(11) NOT NULL,
  `hostgroup_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `host_id` (`host_id`),
  KEY `hostgroup_id` (`hostgroup_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `hosts_to_parenthosts`
--

CREATE TABLE IF NOT EXISTS `hosts_to_parenthosts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `host_id` int(11) NOT NULL,
  `parenthost_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `host_id` (`host_id`),
  KEY `parenthost_id` (`parenthost_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `hosttemplatecommandargumentvalues`
--

CREATE TABLE IF NOT EXISTS `hosttemplatecommandargumentvalues` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `commandargument_id` int(11) NOT NULL,
  `hosttemplate_id` int(11) NOT NULL,
  `value` varchar(500) COLLATE utf8_swedish_ci NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;

--
-- Daten für Tabelle `hosttemplatecommandargumentvalues`
--

INSERT INTO `hosttemplatecommandargumentvalues` (`id`, `commandargument_id`, `hosttemplate_id`, `value`, `created`, `modified`) VALUES
(1, 3, 1, '3000.0,80%', '2015-01-05 15:22:21', '2015-01-05 15:22:21'),
(2, 4, 1, '5000.0,100%', '2015-01-05 15:22:21', '2015-01-05 15:22:21');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `hosttemplates`
--

CREATE TABLE IF NOT EXISTS `hosttemplates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uuid` varchar(37) COLLATE utf8_swedish_ci NOT NULL,
  `name` varchar(255) COLLATE utf8_swedish_ci NOT NULL,
  `description` varchar(255) COLLATE utf8_swedish_ci NOT NULL,
  `command_id` int(11) NOT NULL DEFAULT '0',
  `check_command_args` varchar(1000) COLLATE utf8_swedish_ci NOT NULL,
  `eventhandler_command_id` int(11) NOT NULL DEFAULT '0',
  `timeperiod_id` int(11) NOT NULL,
  `check_interval` int(5) NOT NULL DEFAULT '1',
  `retry_interval` int(5) NOT NULL DEFAULT '3',
  `max_check_attempts` int(3) NOT NULL DEFAULT '1',
  `first_notification_delay` float NOT NULL DEFAULT '0',
  `notification_interval` float NOT NULL DEFAULT '0',
  `notify_on_down` int(1) NOT NULL DEFAULT '0',
  `notify_on_unreachable` int(1) NOT NULL DEFAULT '0',
  `notify_on_recovery` int(1) NOT NULL DEFAULT '0',
  `notify_on_flapping` int(1) NOT NULL DEFAULT '0',
  `notify_on_downtime` int(1) NOT NULL DEFAULT '0',
  `flap_detection_enabled` int(1) NOT NULL DEFAULT '0',
  `flap_detection_on_up` int(1) NOT NULL DEFAULT '0',
  `flap_detection_on_down` int(1) NOT NULL DEFAULT '0',
  `flap_detection_on_unreachable` int(1) NOT NULL DEFAULT '0',
  `low_flap_threshold` float NOT NULL DEFAULT '0',
  `high_flap_threshold` float NOT NULL DEFAULT '0',
  `process_performance_data` int(6) NOT NULL DEFAULT '0',
  `freshness_checks_enabled` int(6) NOT NULL DEFAULT '0',
  `freshness_threshold` int(8) DEFAULT '0',
  `passive_checks_enabled` int(1) NOT NULL DEFAULT '0',
  `event_handler_enabled` int(1) NOT NULL DEFAULT '0',
  `active_checks_enabled` int(1) NOT NULL DEFAULT '0',
  `retain_status_information` int(6) NOT NULL DEFAULT '0',
  `retain_nonstatus_information` int(6) NOT NULL DEFAULT '0',
  `notifications_enabled` int(6) NOT NULL DEFAULT '0',
  `notes` varchar(255) COLLATE utf8_swedish_ci NOT NULL,
  `priority` int(2) NOT NULL DEFAULT '1',
  `check_period_id` int(11) NOT NULL,
  `notify_period_id` int(11) NOT NULL,
  `tags` varchar(255) COLLATE utf8_swedish_ci NOT NULL,
  `container_id` int(11) NOT NULL,
  `host_url` varchar(255) COLLATE utf8_swedish_ci DEFAULT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uuid` (`uuid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;

--
-- Daten für Tabelle `hosttemplates`
--

INSERT INTO `hosttemplates` (`id`, `uuid`, `name`, `description`, `command_id`, `check_command_args`, `eventhandler_command_id`, `timeperiod_id`, `check_interval`, `retry_interval`, `max_check_attempts`, `first_notification_delay`, `notification_interval`, `notify_on_down`, `notify_on_unreachable`, `notify_on_recovery`, `notify_on_flapping`, `notify_on_downtime`, `flap_detection_enabled`, `flap_detection_on_up`, `flap_detection_on_down`, `flap_detection_on_unreachable`, `low_flap_threshold`, `high_flap_threshold`, `process_performance_data`, `freshness_checks_enabled`, `freshness_threshold`, `passive_checks_enabled`, `event_handler_enabled`, `active_checks_enabled`, `retain_status_information`, `retain_nonstatus_information`, `notifications_enabled`, `notes`, `priority`, `check_period_id`, `notify_period_id`, `tags`, `container_id`, `host_url`, `created`, `modified`) VALUES
(1, 'efbee68c-cf48-4b78-83f5-c856c56177f0', 'default host', 'default host', 4, '', 0, 0, 7200, 60, 3, 0, 7200, 0, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0, 0, '', 1, 1, 1, '', 1, '', '2015-01-05 15:22:21', '2015-01-05 15:22:21');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `locations`
--

CREATE TABLE IF NOT EXISTS `locations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uuid` varchar(37) COLLATE utf8_swedish_ci NOT NULL,
  `container_id` int(11) NOT NULL,
  `description` varchar(255) COLLATE utf8_swedish_ci NOT NULL,
  `latitude` float DEFAULT '0',
  `longitude` float DEFAULT '0',
  `timezone` varchar(255) COLLATE utf8_swedish_ci NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uuid` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `macros`
--

CREATE TABLE IF NOT EXISTS `macros` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(10) COLLATE utf8_swedish_ci NOT NULL,
  `value` varchar(255) COLLATE utf8_swedish_ci NOT NULL,
  `description` varchar(255) COLLATE utf8_swedish_ci NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;

--
-- Daten für Tabelle `macros`
--

INSERT INTO `macros` (`id`, `name`, `value`, `description`, `created`, `modified`) VALUES
(1, '$USER1$', '/opt/openitc/nagios/libexec', 'Path to monitoring plugins', '2015-01-05 15:17:23', '2015-01-05 15:17:23');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `nagios_acknowledgements`
--

CREATE TABLE IF NOT EXISTS `nagios_acknowledgements` (
  `acknowledgement_id` int(11) NOT NULL AUTO_INCREMENT,
  `instance_id` smallint(6) NOT NULL DEFAULT '0',
  `entry_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `entry_time_usec` int(11) NOT NULL DEFAULT '0',
  `acknowledgement_type` smallint(6) NOT NULL DEFAULT '0',
  `object_id` int(11) NOT NULL DEFAULT '0',
  `state` smallint(6) NOT NULL DEFAULT '0',
  `author_name` varchar(64) COLLATE utf8_swedish_ci NOT NULL DEFAULT '',
  `comment_data` varchar(255) COLLATE utf8_swedish_ci NOT NULL DEFAULT '',
  `is_sticky` smallint(6) NOT NULL DEFAULT '0',
  `persistent_comment` smallint(6) NOT NULL DEFAULT '0',
  `notify_contacts` smallint(6) NOT NULL DEFAULT '0',
  PRIMARY KEY (`acknowledgement_id`),
  KEY `entry_time` (`entry_time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci COMMENT='Current and historical host and service acknowledgements';

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `nagios_commands`
--

CREATE TABLE IF NOT EXISTS `nagios_commands` (
  `command_id` int(11) NOT NULL AUTO_INCREMENT,
  `instance_id` smallint(6) NOT NULL DEFAULT '0',
  `config_type` smallint(6) NOT NULL DEFAULT '0',
  `object_id` int(11) NOT NULL DEFAULT '0',
  `command_line` varchar(511) COLLATE utf8_swedish_ci NOT NULL,
  PRIMARY KEY (`command_id`),
  UNIQUE KEY `instance_id` (`instance_id`,`object_id`,`config_type`),
  KEY `object_id` (`object_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci COMMENT='Command definitions';

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `nagios_commenthistory`
--

CREATE TABLE IF NOT EXISTS `nagios_commenthistory` (
  `commenthistory_id` int(11) NOT NULL AUTO_INCREMENT,
  `instance_id` smallint(6) NOT NULL DEFAULT '0',
  `entry_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `entry_time_usec` int(11) NOT NULL DEFAULT '0',
  `comment_type` smallint(6) NOT NULL DEFAULT '0',
  `entry_type` smallint(6) NOT NULL DEFAULT '0',
  `object_id` int(11) NOT NULL DEFAULT '0',
  `comment_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `internal_comment_id` int(11) NOT NULL DEFAULT '0',
  `author_name` varchar(64) COLLATE utf8_swedish_ci NOT NULL DEFAULT '',
  `comment_data` varchar(255) COLLATE utf8_swedish_ci NOT NULL DEFAULT '',
  `is_persistent` smallint(6) NOT NULL DEFAULT '0',
  `comment_source` smallint(6) NOT NULL DEFAULT '0',
  `expires` smallint(6) NOT NULL DEFAULT '0',
  `expiration_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deletion_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deletion_time_usec` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`commenthistory_id`),
  UNIQUE KEY `instance_id` (`instance_id`,`comment_time`,`internal_comment_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci COMMENT='Historical host and service comments';

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `nagios_comments`
--

CREATE TABLE IF NOT EXISTS `nagios_comments` (
  `comment_id` int(11) NOT NULL AUTO_INCREMENT,
  `instance_id` smallint(6) NOT NULL DEFAULT '0',
  `entry_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `entry_time_usec` int(11) NOT NULL DEFAULT '0',
  `comment_type` smallint(6) NOT NULL DEFAULT '0',
  `entry_type` smallint(6) NOT NULL DEFAULT '0',
  `object_id` int(11) NOT NULL DEFAULT '0',
  `comment_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `internal_comment_id` int(11) NOT NULL DEFAULT '0',
  `author_name` varchar(64) COLLATE utf8_swedish_ci NOT NULL DEFAULT '',
  `comment_data` varchar(255) COLLATE utf8_swedish_ci NOT NULL DEFAULT '',
  `is_persistent` smallint(6) NOT NULL DEFAULT '0',
  `comment_source` smallint(6) NOT NULL DEFAULT '0',
  `expires` smallint(6) NOT NULL DEFAULT '0',
  `expiration_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`comment_id`),
  UNIQUE KEY `instance_id` (`instance_id`,`comment_time`,`internal_comment_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `nagios_configfiles`
--

CREATE TABLE IF NOT EXISTS `nagios_configfiles` (
  `configfile_id` int(11) NOT NULL AUTO_INCREMENT,
  `instance_id` smallint(6) NOT NULL DEFAULT '0',
  `configfile_type` smallint(6) NOT NULL DEFAULT '0',
  `configfile_path` varchar(255) COLLATE utf8_swedish_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`configfile_id`),
  UNIQUE KEY `instance_id` (`instance_id`,`configfile_type`,`configfile_path`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci COMMENT='Configuration files';

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `nagios_configfilevariables`
--

CREATE TABLE IF NOT EXISTS `nagios_configfilevariables` (
  `configfilevariable_id` int(11) NOT NULL AUTO_INCREMENT,
  `instance_id` smallint(6) NOT NULL DEFAULT '0',
  `configfile_id` int(11) NOT NULL DEFAULT '0',
  `varname` varchar(64) COLLATE utf8_swedish_ci NOT NULL DEFAULT '',
  `varvalue` varchar(255) COLLATE utf8_swedish_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`configfilevariable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci COMMENT='Configuration file variables';

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `nagios_conninfo`
--

CREATE TABLE IF NOT EXISTS `nagios_conninfo` (
  `conninfo_id` int(11) NOT NULL AUTO_INCREMENT,
  `instance_id` smallint(6) NOT NULL DEFAULT '0',
  `agent_name` varchar(32) COLLATE utf8_swedish_ci NOT NULL DEFAULT '',
  `agent_version` varchar(8) COLLATE utf8_swedish_ci NOT NULL DEFAULT '',
  `disposition` varchar(16) COLLATE utf8_swedish_ci NOT NULL DEFAULT '',
  `connect_source` varchar(16) COLLATE utf8_swedish_ci NOT NULL DEFAULT '',
  `connect_type` varchar(16) COLLATE utf8_swedish_ci NOT NULL DEFAULT '',
  `connect_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `disconnect_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `last_checkin_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `data_start_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `data_end_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `bytes_processed` int(11) NOT NULL DEFAULT '0',
  `lines_processed` int(11) NOT NULL DEFAULT '0',
  `entries_processed` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`conninfo_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci COMMENT='NDO2DB daemon connection information';

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `nagios_contactgroups`
--

CREATE TABLE IF NOT EXISTS `nagios_contactgroups` (
  `contactgroup_id` int(11) NOT NULL AUTO_INCREMENT,
  `instance_id` smallint(6) NOT NULL DEFAULT '0',
  `config_type` smallint(6) NOT NULL DEFAULT '0',
  `contactgroup_object_id` int(11) NOT NULL DEFAULT '0',
  `alias` varchar(255) COLLATE utf8_swedish_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`contactgroup_id`),
  UNIQUE KEY `instance_id` (`instance_id`,`config_type`,`contactgroup_object_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci COMMENT='Contactgroup definitions';

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `nagios_contactgroup_members`
--

CREATE TABLE IF NOT EXISTS `nagios_contactgroup_members` (
  `contactgroup_member_id` int(11) NOT NULL AUTO_INCREMENT,
  `instance_id` smallint(6) NOT NULL DEFAULT '0',
  `contactgroup_id` int(11) NOT NULL DEFAULT '0',
  `contact_object_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`contactgroup_member_id`),
  UNIQUE KEY `instance_id` (`contactgroup_id`,`contact_object_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci COMMENT='Contactgroup members';

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `nagios_contactnotificationmethods`
--

CREATE TABLE IF NOT EXISTS `nagios_contactnotificationmethods` (
  `contactnotificationmethod_id` int(11) NOT NULL AUTO_INCREMENT,
  `instance_id` smallint(6) NOT NULL DEFAULT '0',
  `contactnotification_id` int(11) NOT NULL DEFAULT '0',
  `start_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `start_time_usec` int(11) NOT NULL DEFAULT '0',
  `end_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `end_time_usec` int(11) NOT NULL DEFAULT '0',
  `command_object_id` int(11) NOT NULL DEFAULT '0',
  `command_args` varchar(1000) COLLATE utf8_swedish_ci DEFAULT '',
  PRIMARY KEY (`contactnotificationmethod_id`,`start_time`),
  KEY `start_time` (`start_time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci COMMENT='Historical record of contact notification methods';

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `nagios_contactnotifications`
--

CREATE TABLE IF NOT EXISTS `nagios_contactnotifications` (
  `contactnotification_id` int(11) NOT NULL AUTO_INCREMENT,
  `instance_id` smallint(6) NOT NULL DEFAULT '0',
  `notification_id` int(11) NOT NULL DEFAULT '0',
  `contact_object_id` int(11) NOT NULL DEFAULT '0',
  `start_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `start_time_usec` int(11) NOT NULL DEFAULT '0',
  `end_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `end_time_usec` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`contactnotification_id`,`start_time`),
  KEY `start_time` (`start_time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci COMMENT='Historical record of contact notifications';

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `nagios_contacts`
--

CREATE TABLE IF NOT EXISTS `nagios_contacts` (
  `contact_id` int(11) NOT NULL AUTO_INCREMENT,
  `instance_id` smallint(6) NOT NULL DEFAULT '0',
  `config_type` smallint(6) NOT NULL DEFAULT '0',
  `contact_object_id` int(11) NOT NULL DEFAULT '0',
  `alias` varchar(64) COLLATE utf8_swedish_ci NOT NULL DEFAULT '',
  `email_address` varchar(255) COLLATE utf8_swedish_ci DEFAULT '',
  `pager_address` varchar(64) COLLATE utf8_swedish_ci DEFAULT '',
  `host_timeperiod_object_id` int(11) NOT NULL DEFAULT '0',
  `service_timeperiod_object_id` int(11) NOT NULL DEFAULT '0',
  `host_notifications_enabled` smallint(6) NOT NULL DEFAULT '0',
  `service_notifications_enabled` smallint(6) NOT NULL DEFAULT '0',
  `can_submit_commands` smallint(6) NOT NULL DEFAULT '0',
  `notify_service_recovery` smallint(6) NOT NULL DEFAULT '0',
  `notify_service_warning` smallint(6) NOT NULL DEFAULT '0',
  `notify_service_unknown` smallint(6) NOT NULL DEFAULT '0',
  `notify_service_critical` smallint(6) NOT NULL DEFAULT '0',
  `notify_service_flapping` smallint(6) NOT NULL DEFAULT '0',
  `notify_service_downtime` smallint(6) NOT NULL DEFAULT '0',
  `notify_host_recovery` smallint(6) NOT NULL DEFAULT '0',
  `notify_host_down` smallint(6) NOT NULL DEFAULT '0',
  `notify_host_unreachable` smallint(6) NOT NULL DEFAULT '0',
  `notify_host_flapping` smallint(6) NOT NULL DEFAULT '0',
  `notify_host_downtime` smallint(6) NOT NULL DEFAULT '0',
  `minimum_importance` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`contact_id`),
  UNIQUE KEY `instance_id` (`instance_id`,`config_type`,`contact_object_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci COMMENT='Contact definitions';

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `nagios_contactstatus`
--

CREATE TABLE IF NOT EXISTS `nagios_contactstatus` (
  `contactstatus_id` int(11) NOT NULL AUTO_INCREMENT,
  `instance_id` smallint(6) NOT NULL DEFAULT '0',
  `contact_object_id` int(11) NOT NULL DEFAULT '0',
  `status_update_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `host_notifications_enabled` smallint(6) NOT NULL DEFAULT '0',
  `service_notifications_enabled` smallint(6) NOT NULL DEFAULT '0',
  `last_host_notification` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `last_service_notification` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_attributes` int(11) NOT NULL DEFAULT '0',
  `modified_host_attributes` int(11) NOT NULL DEFAULT '0',
  `modified_service_attributes` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`contactstatus_id`),
  UNIQUE KEY `contact_object_id` (`contact_object_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci COMMENT='Contact status';

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `nagios_contact_addresses`
--

CREATE TABLE IF NOT EXISTS `nagios_contact_addresses` (
  `contact_address_id` int(11) NOT NULL AUTO_INCREMENT,
  `instance_id` smallint(6) NOT NULL DEFAULT '0',
  `contact_id` int(11) NOT NULL DEFAULT '0',
  `address_number` smallint(6) NOT NULL DEFAULT '0',
  `address` varchar(255) COLLATE utf8_swedish_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`contact_address_id`),
  UNIQUE KEY `contact_id` (`contact_id`,`address_number`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci COMMENT='Contact addresses';

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `nagios_contact_notificationcommands`
--

CREATE TABLE IF NOT EXISTS `nagios_contact_notificationcommands` (
  `contact_notificationcommand_id` int(11) NOT NULL AUTO_INCREMENT,
  `instance_id` smallint(6) NOT NULL DEFAULT '0',
  `contact_id` int(11) NOT NULL DEFAULT '0',
  `notification_type` smallint(6) NOT NULL DEFAULT '0',
  `command_object_id` int(11) NOT NULL DEFAULT '0',
  `command_args` varchar(1000) COLLATE utf8_swedish_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`contact_notificationcommand_id`),
  UNIQUE KEY `contact_id` (`contact_id`,`notification_type`,`command_object_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci COMMENT='Contact host and service notification commands';

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `nagios_customvariables`
--

CREATE TABLE IF NOT EXISTS `nagios_customvariables` (
  `customvariable_id` int(11) NOT NULL AUTO_INCREMENT,
  `instance_id` smallint(6) NOT NULL DEFAULT '0',
  `object_id` int(11) NOT NULL DEFAULT '0',
  `config_type` smallint(6) NOT NULL DEFAULT '0',
  `has_been_modified` smallint(6) NOT NULL DEFAULT '0',
  `varname` varchar(255) COLLATE utf8_swedish_ci NOT NULL DEFAULT '',
  `varvalue` varchar(255) COLLATE utf8_swedish_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`customvariable_id`),
  UNIQUE KEY `object_id_2` (`object_id`,`config_type`,`varname`),
  KEY `varname` (`varname`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci COMMENT='Custom variables';

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `nagios_customvariablestatus`
--

CREATE TABLE IF NOT EXISTS `nagios_customvariablestatus` (
  `customvariablestatus_id` int(11) NOT NULL AUTO_INCREMENT,
  `instance_id` smallint(6) NOT NULL DEFAULT '0',
  `object_id` int(11) NOT NULL DEFAULT '0',
  `status_update_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `has_been_modified` smallint(6) NOT NULL DEFAULT '0',
  `varname` varchar(255) COLLATE utf8_swedish_ci NOT NULL DEFAULT '',
  `varvalue` varchar(255) COLLATE utf8_swedish_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`customvariablestatus_id`),
  UNIQUE KEY `object_id_2` (`object_id`,`varname`),
  KEY `varname` (`varname`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci COMMENT='Custom variable status information';

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `nagios_dbversion`
--

CREATE TABLE IF NOT EXISTS `nagios_dbversion` (
  `name` varchar(10) COLLATE utf8_swedish_ci NOT NULL DEFAULT '',
  `version` varchar(10) COLLATE utf8_swedish_ci NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `nagios_downtimehistory`
--

CREATE TABLE IF NOT EXISTS `nagios_downtimehistory` (
  `downtimehistory_id` int(11) NOT NULL AUTO_INCREMENT,
  `instance_id` smallint(6) NOT NULL DEFAULT '0',
  `downtime_type` smallint(6) NOT NULL DEFAULT '0',
  `object_id` int(11) NOT NULL DEFAULT '0',
  `entry_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `author_name` varchar(64) COLLATE utf8_swedish_ci NOT NULL DEFAULT '',
  `comment_data` varchar(255) COLLATE utf8_swedish_ci NOT NULL DEFAULT '',
  `internal_downtime_id` int(11) NOT NULL DEFAULT '0',
  `triggered_by_id` int(11) NOT NULL DEFAULT '0',
  `is_fixed` smallint(6) NOT NULL DEFAULT '0',
  `duration` int(11) NOT NULL DEFAULT '0',
  `scheduled_start_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `scheduled_end_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `was_started` smallint(6) NOT NULL DEFAULT '0',
  `actual_start_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `actual_start_time_usec` int(11) NOT NULL DEFAULT '0',
  `actual_end_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `actual_end_time_usec` int(11) NOT NULL DEFAULT '0',
  `was_cancelled` smallint(6) NOT NULL DEFAULT '0',
  PRIMARY KEY (`downtimehistory_id`),
  UNIQUE KEY `instance_id` (`instance_id`,`object_id`,`entry_time`,`internal_downtime_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci COMMENT='Historical scheduled host and service downtime';

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `nagios_eventhandlers`
--

CREATE TABLE IF NOT EXISTS `nagios_eventhandlers` (
  `eventhandler_id` int(11) NOT NULL AUTO_INCREMENT,
  `instance_id` smallint(6) NOT NULL DEFAULT '0',
  `eventhandler_type` smallint(6) NOT NULL DEFAULT '0',
  `object_id` int(11) NOT NULL DEFAULT '0',
  `state` smallint(6) NOT NULL DEFAULT '0',
  `state_type` smallint(6) NOT NULL DEFAULT '0',
  `start_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `start_time_usec` int(11) NOT NULL DEFAULT '0',
  `end_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `end_time_usec` int(11) NOT NULL DEFAULT '0',
  `command_object_id` int(11) NOT NULL DEFAULT '0',
  `command_args` varchar(1000) COLLATE utf8_swedish_ci NOT NULL DEFAULT '',
  `command_line` varchar(255) COLLATE utf8_swedish_ci NOT NULL DEFAULT '',
  `timeout` smallint(6) NOT NULL DEFAULT '0',
  `early_timeout` smallint(6) NOT NULL DEFAULT '0',
  `execution_time` double NOT NULL DEFAULT '0',
  `return_code` smallint(6) NOT NULL DEFAULT '0',
  `output` varchar(255) COLLATE utf8_swedish_ci NOT NULL DEFAULT '',
  `long_output` text COLLATE utf8_swedish_ci NOT NULL,
  PRIMARY KEY (`eventhandler_id`),
  UNIQUE KEY `instance_id` (`instance_id`,`object_id`,`start_time`,`start_time_usec`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci COMMENT='Historical host and service event handlers';

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `nagios_externalcommands`
--

CREATE TABLE IF NOT EXISTS `nagios_externalcommands` (
  `externalcommand_id` int(11) NOT NULL AUTO_INCREMENT,
  `instance_id` smallint(6) NOT NULL DEFAULT '0',
  `entry_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `command_type` smallint(6) NOT NULL DEFAULT '0',
  `command_name` varchar(128) COLLATE utf8_swedish_ci NOT NULL DEFAULT '',
  `command_args` varchar(1000) COLLATE utf8_swedish_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`externalcommand_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci COMMENT='Historical record of processed external commands';

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `nagios_flappinghistory`
--

CREATE TABLE IF NOT EXISTS `nagios_flappinghistory` (
  `flappinghistory_id` int(11) NOT NULL AUTO_INCREMENT,
  `instance_id` smallint(6) NOT NULL DEFAULT '0',
  `event_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `event_time_usec` int(11) NOT NULL DEFAULT '0',
  `event_type` smallint(6) NOT NULL DEFAULT '0',
  `reason_type` smallint(6) NOT NULL DEFAULT '0',
  `flapping_type` smallint(6) NOT NULL DEFAULT '0',
  `object_id` int(11) NOT NULL DEFAULT '0',
  `percent_state_change` double NOT NULL DEFAULT '0',
  `low_threshold` double NOT NULL DEFAULT '0',
  `high_threshold` double NOT NULL DEFAULT '0',
  `comment_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `internal_comment_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`flappinghistory_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci COMMENT='Current and historical record of host and service flapping';

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `nagios_hostchecks`
--

CREATE TABLE IF NOT EXISTS `nagios_hostchecks` (
  `hostcheck_id` int(11) NOT NULL AUTO_INCREMENT,
  `instance_id` smallint(6) NOT NULL DEFAULT '0',
  `host_object_id` int(11) NOT NULL DEFAULT '0',
  `check_type` smallint(6) NOT NULL DEFAULT '0',
  `is_raw_check` smallint(6) NOT NULL DEFAULT '0',
  `current_check_attempt` smallint(6) NOT NULL DEFAULT '0',
  `max_check_attempts` smallint(6) NOT NULL DEFAULT '0',
  `state` smallint(6) NOT NULL DEFAULT '0',
  `state_type` smallint(6) NOT NULL DEFAULT '0',
  `start_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `start_time_usec` int(11) NOT NULL DEFAULT '0',
  `end_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `end_time_usec` int(11) NOT NULL DEFAULT '0',
  `command_object_id` int(11) NOT NULL DEFAULT '0',
  `command_args` varchar(255) COLLATE utf8_swedish_ci NOT NULL DEFAULT '',
  `command_line` varchar(255) COLLATE utf8_swedish_ci NOT NULL DEFAULT '',
  `timeout` smallint(6) NOT NULL DEFAULT '0',
  `early_timeout` smallint(6) NOT NULL DEFAULT '0',
  `execution_time` double NOT NULL DEFAULT '0',
  `latency` double NOT NULL DEFAULT '0',
  `return_code` smallint(6) NOT NULL DEFAULT '0',
  `output` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `long_output` text CHARACTER SET utf8 NOT NULL,
  `perfdata` varchar(255) COLLATE utf8_swedish_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`hostcheck_id`,`start_time`),
  UNIQUE KEY `instance_id` (`instance_id`,`host_object_id`,`start_time`,`start_time_usec`),
  KEY `start_time` (`start_time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci COMMENT='Historical host checks';

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `nagios_hostdependencies`
--

CREATE TABLE IF NOT EXISTS `nagios_hostdependencies` (
  `hostdependency_id` int(11) NOT NULL AUTO_INCREMENT,
  `instance_id` smallint(6) NOT NULL DEFAULT '0',
  `config_type` smallint(6) NOT NULL DEFAULT '0',
  `host_object_id` int(11) NOT NULL DEFAULT '0',
  `dependent_host_object_id` int(11) NOT NULL DEFAULT '0',
  `dependency_type` smallint(6) NOT NULL DEFAULT '0',
  `inherits_parent` smallint(6) NOT NULL DEFAULT '0',
  `timeperiod_object_id` int(11) NOT NULL DEFAULT '0',
  `fail_on_up` smallint(6) NOT NULL DEFAULT '0',
  `fail_on_down` smallint(6) NOT NULL DEFAULT '0',
  `fail_on_unreachable` smallint(6) NOT NULL DEFAULT '0',
  PRIMARY KEY (`hostdependency_id`),
  UNIQUE KEY `instance_id` (`instance_id`,`config_type`,`host_object_id`,`dependent_host_object_id`,`dependency_type`,`inherits_parent`,`fail_on_up`,`fail_on_down`,`fail_on_unreachable`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci COMMENT='Host dependency definitions';

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `nagios_hostescalations`
--

CREATE TABLE IF NOT EXISTS `nagios_hostescalations` (
  `hostescalation_id` int(11) NOT NULL AUTO_INCREMENT,
  `instance_id` smallint(6) NOT NULL DEFAULT '0',
  `config_type` smallint(6) NOT NULL DEFAULT '0',
  `host_object_id` int(11) NOT NULL DEFAULT '0',
  `timeperiod_object_id` int(11) NOT NULL DEFAULT '0',
  `first_notification` smallint(6) NOT NULL DEFAULT '0',
  `last_notification` smallint(6) NOT NULL DEFAULT '0',
  `notification_interval` double NOT NULL DEFAULT '0',
  `escalate_on_recovery` smallint(6) NOT NULL DEFAULT '0',
  `escalate_on_down` smallint(6) NOT NULL DEFAULT '0',
  `escalate_on_unreachable` smallint(6) NOT NULL DEFAULT '0',
  PRIMARY KEY (`hostescalation_id`),
  UNIQUE KEY `instance_id` (`instance_id`,`config_type`,`host_object_id`,`timeperiod_object_id`,`first_notification`,`last_notification`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci COMMENT='Host escalation definitions';

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `nagios_hostescalation_contactgroups`
--

CREATE TABLE IF NOT EXISTS `nagios_hostescalation_contactgroups` (
  `hostescalation_contactgroup_id` int(11) NOT NULL AUTO_INCREMENT,
  `instance_id` smallint(6) NOT NULL DEFAULT '0',
  `hostescalation_id` int(11) NOT NULL DEFAULT '0',
  `contactgroup_object_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`hostescalation_contactgroup_id`),
  UNIQUE KEY `instance_id` (`hostescalation_id`,`contactgroup_object_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci COMMENT='Host escalation contact groups';

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `nagios_hostescalation_contacts`
--

CREATE TABLE IF NOT EXISTS `nagios_hostescalation_contacts` (
  `hostescalation_contact_id` int(11) NOT NULL AUTO_INCREMENT,
  `instance_id` smallint(6) NOT NULL DEFAULT '0',
  `hostescalation_id` int(11) NOT NULL DEFAULT '0',
  `contact_object_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`hostescalation_contact_id`),
  UNIQUE KEY `instance_id` (`instance_id`,`hostescalation_id`,`contact_object_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `nagios_hostgroups`
--

CREATE TABLE IF NOT EXISTS `nagios_hostgroups` (
  `hostgroup_id` int(11) NOT NULL AUTO_INCREMENT,
  `instance_id` smallint(6) NOT NULL DEFAULT '0',
  `config_type` smallint(6) NOT NULL DEFAULT '0',
  `hostgroup_object_id` int(11) NOT NULL DEFAULT '0',
  `alias` varchar(255) COLLATE utf8_swedish_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`hostgroup_id`),
  UNIQUE KEY `instance_id` (`instance_id`,`hostgroup_object_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci COMMENT='Hostgroup definitions';

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `nagios_hostgroup_members`
--

CREATE TABLE IF NOT EXISTS `nagios_hostgroup_members` (
  `hostgroup_member_id` int(11) NOT NULL AUTO_INCREMENT,
  `instance_id` smallint(6) NOT NULL DEFAULT '0',
  `hostgroup_id` int(11) NOT NULL DEFAULT '0',
  `host_object_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`hostgroup_member_id`),
  UNIQUE KEY `instance_id` (`hostgroup_id`,`host_object_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci COMMENT='Hostgroup members';

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `nagios_hosts`
--

CREATE TABLE IF NOT EXISTS `nagios_hosts` (
  `host_id` int(11) NOT NULL AUTO_INCREMENT,
  `instance_id` smallint(6) NOT NULL DEFAULT '0',
  `config_type` smallint(6) NOT NULL DEFAULT '0',
  `host_object_id` int(11) NOT NULL DEFAULT '0',
  `alias` varchar(64) COLLATE utf8_swedish_ci NOT NULL DEFAULT '',
  `display_name` varchar(64) COLLATE utf8_swedish_ci NOT NULL DEFAULT '',
  `address` varchar(128) COLLATE utf8_swedish_ci NOT NULL DEFAULT '',
  `check_command_object_id` int(11) NOT NULL DEFAULT '0',
  `check_command_args` varchar(1000) COLLATE utf8_swedish_ci NOT NULL DEFAULT '',
  `eventhandler_command_object_id` int(11) NOT NULL DEFAULT '0',
  `eventhandler_command_args` varchar(1000) COLLATE utf8_swedish_ci NOT NULL DEFAULT '',
  `notification_timeperiod_object_id` int(11) NOT NULL DEFAULT '0',
  `check_timeperiod_object_id` int(11) NOT NULL DEFAULT '0',
  `failure_prediction_options` varchar(64) COLLATE utf8_swedish_ci NOT NULL DEFAULT '',
  `check_interval` double NOT NULL DEFAULT '0',
  `retry_interval` double NOT NULL DEFAULT '0',
  `max_check_attempts` smallint(6) NOT NULL DEFAULT '0',
  `first_notification_delay` double NOT NULL DEFAULT '0',
  `notification_interval` double NOT NULL DEFAULT '0',
  `notify_on_down` smallint(6) NOT NULL DEFAULT '0',
  `notify_on_unreachable` smallint(6) NOT NULL DEFAULT '0',
  `notify_on_recovery` smallint(6) NOT NULL DEFAULT '0',
  `notify_on_flapping` smallint(6) NOT NULL DEFAULT '0',
  `notify_on_downtime` smallint(6) NOT NULL DEFAULT '0',
  `stalk_on_up` smallint(6) NOT NULL DEFAULT '0',
  `stalk_on_down` smallint(6) NOT NULL DEFAULT '0',
  `stalk_on_unreachable` smallint(6) NOT NULL DEFAULT '0',
  `flap_detection_enabled` smallint(6) NOT NULL DEFAULT '0',
  `flap_detection_on_up` smallint(6) NOT NULL DEFAULT '0',
  `flap_detection_on_down` smallint(6) NOT NULL DEFAULT '0',
  `flap_detection_on_unreachable` smallint(6) NOT NULL DEFAULT '0',
  `low_flap_threshold` double NOT NULL DEFAULT '0',
  `high_flap_threshold` double NOT NULL DEFAULT '0',
  `process_performance_data` smallint(6) NOT NULL DEFAULT '0',
  `freshness_checks_enabled` smallint(6) NOT NULL DEFAULT '0',
  `freshness_threshold` mediumint(8) NOT NULL DEFAULT '0',
  `passive_checks_enabled` smallint(6) NOT NULL DEFAULT '0',
  `event_handler_enabled` smallint(6) NOT NULL DEFAULT '0',
  `active_checks_enabled` smallint(6) NOT NULL DEFAULT '0',
  `retain_status_information` smallint(6) NOT NULL DEFAULT '0',
  `retain_nonstatus_information` smallint(6) NOT NULL DEFAULT '0',
  `notifications_enabled` smallint(6) NOT NULL DEFAULT '0',
  `obsess_over_host` smallint(6) NOT NULL DEFAULT '0',
  `failure_prediction_enabled` smallint(6) NOT NULL DEFAULT '0',
  `notes` varchar(255) COLLATE utf8_swedish_ci NOT NULL DEFAULT '',
  `notes_url` varchar(255) COLLATE utf8_swedish_ci NOT NULL DEFAULT '',
  `action_url` varchar(255) COLLATE utf8_swedish_ci NOT NULL DEFAULT '',
  `icon_image` varchar(255) COLLATE utf8_swedish_ci NOT NULL DEFAULT '',
  `icon_image_alt` varchar(255) COLLATE utf8_swedish_ci NOT NULL DEFAULT '',
  `vrml_image` varchar(255) COLLATE utf8_swedish_ci NOT NULL DEFAULT '',
  `statusmap_image` varchar(255) COLLATE utf8_swedish_ci NOT NULL DEFAULT '',
  `have_2d_coords` smallint(6) NOT NULL DEFAULT '0',
  `x_2d` smallint(6) NOT NULL DEFAULT '0',
  `y_2d` smallint(6) NOT NULL DEFAULT '0',
  `have_3d_coords` smallint(6) NOT NULL DEFAULT '0',
  `x_3d` double NOT NULL DEFAULT '0',
  `y_3d` double NOT NULL DEFAULT '0',
  `z_3d` double NOT NULL DEFAULT '0',
  `importance` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`host_id`),
  UNIQUE KEY `instance_id` (`instance_id`,`config_type`,`host_object_id`),
  KEY `host_object_id` (`host_object_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci COMMENT='Host definitions';

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `nagios_hoststatus`
--

CREATE TABLE IF NOT EXISTS `nagios_hoststatus` (
  `hoststatus_id` int(11) NOT NULL AUTO_INCREMENT,
  `instance_id` smallint(6) NOT NULL DEFAULT '0',
  `host_object_id` int(11) NOT NULL DEFAULT '0',
  `status_update_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `output` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `long_output` text CHARACTER SET utf8 NOT NULL,
  `perfdata` varchar(255) COLLATE utf8_swedish_ci NOT NULL DEFAULT '',
  `current_state` smallint(6) NOT NULL DEFAULT '0',
  `has_been_checked` smallint(6) NOT NULL DEFAULT '0',
  `should_be_scheduled` smallint(6) NOT NULL DEFAULT '0',
  `current_check_attempt` smallint(6) NOT NULL DEFAULT '0',
  `max_check_attempts` smallint(6) NOT NULL DEFAULT '0',
  `last_check` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `next_check` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `check_type` smallint(6) NOT NULL DEFAULT '0',
  `last_state_change` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `last_hard_state_change` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `last_hard_state` smallint(6) NOT NULL DEFAULT '0',
  `last_time_up` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `last_time_down` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `last_time_unreachable` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `state_type` smallint(6) NOT NULL DEFAULT '0',
  `last_notification` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `next_notification` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `no_more_notifications` smallint(6) NOT NULL DEFAULT '0',
  `notifications_enabled` smallint(6) NOT NULL DEFAULT '0',
  `problem_has_been_acknowledged` smallint(6) NOT NULL DEFAULT '0',
  `acknowledgement_type` smallint(6) NOT NULL DEFAULT '0',
  `current_notification_number` smallint(6) NOT NULL DEFAULT '0',
  `passive_checks_enabled` smallint(6) NOT NULL DEFAULT '0',
  `active_checks_enabled` smallint(6) NOT NULL DEFAULT '0',
  `event_handler_enabled` smallint(6) NOT NULL DEFAULT '0',
  `flap_detection_enabled` smallint(6) NOT NULL DEFAULT '0',
  `is_flapping` smallint(6) NOT NULL DEFAULT '0',
  `percent_state_change` double NOT NULL DEFAULT '0',
  `latency` double NOT NULL DEFAULT '0',
  `execution_time` double NOT NULL DEFAULT '0',
  `scheduled_downtime_depth` smallint(6) NOT NULL DEFAULT '0',
  `failure_prediction_enabled` smallint(6) NOT NULL DEFAULT '0',
  `process_performance_data` smallint(6) NOT NULL DEFAULT '0',
  `obsess_over_host` smallint(6) NOT NULL DEFAULT '0',
  `modified_host_attributes` int(11) NOT NULL DEFAULT '0',
  `event_handler` varchar(255) COLLATE utf8_swedish_ci NOT NULL DEFAULT '',
  `check_command` varchar(255) COLLATE utf8_swedish_ci NOT NULL DEFAULT '',
  `normal_check_interval` double NOT NULL DEFAULT '0',
  `retry_check_interval` double NOT NULL DEFAULT '0',
  `check_timeperiod_object_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`hoststatus_id`),
  UNIQUE KEY `object_id` (`host_object_id`),
  KEY `instance_id` (`instance_id`),
  KEY `status_update_time` (`status_update_time`),
  KEY `current_state` (`current_state`),
  KEY `check_type` (`check_type`),
  KEY `state_type` (`state_type`),
  KEY `last_state_change` (`last_state_change`),
  KEY `notifications_enabled` (`notifications_enabled`),
  KEY `problem_has_been_acknowledged` (`problem_has_been_acknowledged`),
  KEY `active_checks_enabled` (`active_checks_enabled`),
  KEY `passive_checks_enabled` (`passive_checks_enabled`),
  KEY `event_handler_enabled` (`event_handler_enabled`),
  KEY `flap_detection_enabled` (`flap_detection_enabled`),
  KEY `is_flapping` (`is_flapping`),
  KEY `percent_state_change` (`percent_state_change`),
  KEY `latency` (`latency`),
  KEY `execution_time` (`execution_time`),
  KEY `scheduled_downtime_depth` (`scheduled_downtime_depth`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci COMMENT='Current host status information';

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `nagios_host_contactgroups`
--

CREATE TABLE IF NOT EXISTS `nagios_host_contactgroups` (
  `host_contactgroup_id` int(11) NOT NULL AUTO_INCREMENT,
  `instance_id` smallint(6) NOT NULL DEFAULT '0',
  `host_id` int(11) NOT NULL DEFAULT '0',
  `contactgroup_object_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`host_contactgroup_id`),
  UNIQUE KEY `instance_id` (`host_id`,`contactgroup_object_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci COMMENT='Host contact groups';

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `nagios_host_contacts`
--

CREATE TABLE IF NOT EXISTS `nagios_host_contacts` (
  `host_contact_id` int(11) NOT NULL AUTO_INCREMENT,
  `instance_id` smallint(6) NOT NULL DEFAULT '0',
  `host_id` int(11) NOT NULL DEFAULT '0',
  `contact_object_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`host_contact_id`),
  UNIQUE KEY `instance_id` (`instance_id`,`host_id`,`contact_object_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `nagios_host_parenthosts`
--

CREATE TABLE IF NOT EXISTS `nagios_host_parenthosts` (
  `host_parenthost_id` int(11) NOT NULL AUTO_INCREMENT,
  `instance_id` smallint(6) NOT NULL DEFAULT '0',
  `host_id` int(11) NOT NULL DEFAULT '0',
  `parent_host_object_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`host_parenthost_id`),
  UNIQUE KEY `instance_id` (`host_id`,`parent_host_object_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci COMMENT='Parent hosts';

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `nagios_instances`
--

CREATE TABLE IF NOT EXISTS `nagios_instances` (
  `instance_id` smallint(6) NOT NULL AUTO_INCREMENT,
  `instance_name` varchar(64) COLLATE utf8_swedish_ci NOT NULL DEFAULT '',
  `instance_description` varchar(128) COLLATE utf8_swedish_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`instance_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci COMMENT='Location names of various Nagios installations';

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `nagios_logentries`
--

CREATE TABLE IF NOT EXISTS `nagios_logentries` (
  `logentry_id` int(11) NOT NULL AUTO_INCREMENT,
  `instance_id` int(11) NOT NULL DEFAULT '0',
  `logentry_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `entry_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `entry_time_usec` int(11) NOT NULL DEFAULT '0',
  `logentry_type` int(11) NOT NULL DEFAULT '0',
  `logentry_data` varchar(255) COLLATE utf8_swedish_ci NOT NULL DEFAULT '',
  `realtime_data` smallint(6) NOT NULL DEFAULT '0',
  `inferred_data_extracted` smallint(6) NOT NULL DEFAULT '0',
  PRIMARY KEY (`logentry_id`,`entry_time`),
  UNIQUE KEY `instance_id` (`instance_id`,`logentry_time`,`entry_time`,`entry_time_usec`),
  KEY `logentry_time` (`logentry_time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci COMMENT='Historical record of log entries';

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `nagios_notifications`
--

CREATE TABLE IF NOT EXISTS `nagios_notifications` (
  `notification_id` int(11) NOT NULL AUTO_INCREMENT,
  `instance_id` smallint(6) NOT NULL DEFAULT '0',
  `notification_type` smallint(6) NOT NULL DEFAULT '0',
  `notification_reason` smallint(6) NOT NULL DEFAULT '0',
  `object_id` int(11) NOT NULL DEFAULT '0',
  `start_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `start_time_usec` int(11) NOT NULL DEFAULT '0',
  `end_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `end_time_usec` int(11) NOT NULL DEFAULT '0',
  `state` smallint(6) NOT NULL DEFAULT '0',
  `output` varchar(255) COLLATE utf8_swedish_ci NOT NULL DEFAULT '',
  `long_output` text COLLATE utf8_swedish_ci NOT NULL,
  `escalated` smallint(6) NOT NULL DEFAULT '0',
  `contacts_notified` smallint(6) NOT NULL DEFAULT '0',
  PRIMARY KEY (`notification_id`,`start_time`),
  UNIQUE KEY `instance_id` (`instance_id`,`object_id`,`start_time`,`start_time_usec`),
  KEY `top10` (`object_id`,`start_time`,`contacts_notified`),
  KEY `start_time` (`start_time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci COMMENT='Historical record of host and service notifications';

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `nagios_objects`
--

CREATE TABLE IF NOT EXISTS `nagios_objects` (
  `object_id` int(11) NOT NULL AUTO_INCREMENT,
  `instance_id` smallint(6) NOT NULL DEFAULT '0',
  `objecttype_id` smallint(6) NOT NULL DEFAULT '0',
  `name1` varchar(128) COLLATE utf8_swedish_ci NOT NULL DEFAULT '',
  `name2` varchar(128) COLLATE utf8_swedish_ci DEFAULT NULL,
  `is_active` smallint(6) NOT NULL DEFAULT '0',
  PRIMARY KEY (`object_id`),
  KEY `objecttype_id` (`objecttype_id`,`name1`,`name2`),
  KEY `achmet` (`name1`,`name2`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci COMMENT='Current and historical objects of all kinds';

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `nagios_processevents`
--

CREATE TABLE IF NOT EXISTS `nagios_processevents` (
  `processevent_id` int(11) NOT NULL AUTO_INCREMENT,
  `instance_id` smallint(6) NOT NULL DEFAULT '0',
  `event_type` smallint(6) NOT NULL DEFAULT '0',
  `event_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `event_time_usec` int(11) NOT NULL DEFAULT '0',
  `process_id` int(11) NOT NULL DEFAULT '0',
  `program_name` varchar(16) COLLATE utf8_swedish_ci NOT NULL DEFAULT '',
  `program_version` varchar(20) COLLATE utf8_swedish_ci NOT NULL DEFAULT '',
  `program_date` varchar(10) COLLATE utf8_swedish_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`processevent_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci COMMENT='Historical Nagios process events';

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `nagios_programstatus`
--

CREATE TABLE IF NOT EXISTS `nagios_programstatus` (
  `programstatus_id` int(11) NOT NULL AUTO_INCREMENT,
  `instance_id` smallint(6) NOT NULL DEFAULT '0',
  `status_update_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `program_start_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `program_end_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `is_currently_running` smallint(6) NOT NULL DEFAULT '0',
  `process_id` int(11) NOT NULL DEFAULT '0',
  `daemon_mode` smallint(6) NOT NULL DEFAULT '0',
  `last_command_check` datetime DEFAULT '0000-00-00 00:00:00',
  `last_log_rotation` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `notifications_enabled` smallint(6) NOT NULL DEFAULT '0',
  `active_service_checks_enabled` smallint(6) NOT NULL DEFAULT '0',
  `passive_service_checks_enabled` smallint(6) NOT NULL DEFAULT '0',
  `active_host_checks_enabled` smallint(6) NOT NULL DEFAULT '0',
  `passive_host_checks_enabled` smallint(6) NOT NULL DEFAULT '0',
  `event_handlers_enabled` smallint(6) NOT NULL DEFAULT '0',
  `flap_detection_enabled` smallint(6) NOT NULL DEFAULT '0',
  `failure_prediction_enabled` smallint(6) NOT NULL DEFAULT '0',
  `process_performance_data` smallint(6) NOT NULL DEFAULT '0',
  `obsess_over_hosts` smallint(6) NOT NULL DEFAULT '0',
  `obsess_over_services` smallint(6) NOT NULL DEFAULT '0',
  `modified_host_attributes` int(11) NOT NULL DEFAULT '0',
  `modified_service_attributes` int(11) NOT NULL DEFAULT '0',
  `global_host_event_handler` varchar(255) COLLATE utf8_swedish_ci NOT NULL DEFAULT '',
  `global_service_event_handler` varchar(255) COLLATE utf8_swedish_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`programstatus_id`),
  UNIQUE KEY `instance_id` (`instance_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci COMMENT='Current program status information';

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `nagios_runtimevariables`
--

CREATE TABLE IF NOT EXISTS `nagios_runtimevariables` (
  `runtimevariable_id` int(11) NOT NULL AUTO_INCREMENT,
  `instance_id` smallint(6) NOT NULL DEFAULT '0',
  `varname` varchar(64) COLLATE utf8_swedish_ci NOT NULL DEFAULT '',
  `varvalue` varchar(255) COLLATE utf8_swedish_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`runtimevariable_id`),
  UNIQUE KEY `instance_id` (`instance_id`,`varname`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci COMMENT='Runtime variables from the Nagios daemon';

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `nagios_scheduleddowntime`
--

CREATE TABLE IF NOT EXISTS `nagios_scheduleddowntime` (
  `scheduleddowntime_id` int(11) NOT NULL AUTO_INCREMENT,
  `instance_id` smallint(6) NOT NULL DEFAULT '0',
  `downtime_type` smallint(6) NOT NULL DEFAULT '0',
  `object_id` int(11) NOT NULL DEFAULT '0',
  `entry_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `author_name` varchar(64) COLLATE utf8_swedish_ci NOT NULL DEFAULT '',
  `comment_data` varchar(255) COLLATE utf8_swedish_ci NOT NULL DEFAULT '',
  `internal_downtime_id` int(11) NOT NULL DEFAULT '0',
  `triggered_by_id` int(11) NOT NULL DEFAULT '0',
  `is_fixed` smallint(6) NOT NULL DEFAULT '0',
  `duration` smallint(6) NOT NULL DEFAULT '0',
  `scheduled_start_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `scheduled_end_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `was_started` smallint(6) NOT NULL DEFAULT '0',
  `actual_start_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `actual_start_time_usec` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`scheduleddowntime_id`),
  UNIQUE KEY `instance_id` (`instance_id`,`object_id`,`entry_time`,`internal_downtime_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci COMMENT='Current scheduled host and service downtime';

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `nagios_servicechecks`
--

CREATE TABLE IF NOT EXISTS `nagios_servicechecks` (
  `servicecheck_id` int(11) NOT NULL AUTO_INCREMENT,
  `instance_id` smallint(6) NOT NULL DEFAULT '0',
  `service_object_id` int(11) NOT NULL DEFAULT '0',
  `check_type` smallint(6) NOT NULL DEFAULT '0',
  `current_check_attempt` smallint(6) NOT NULL DEFAULT '0',
  `max_check_attempts` smallint(6) NOT NULL DEFAULT '0',
  `state` smallint(6) NOT NULL DEFAULT '0',
  `state_type` smallint(6) NOT NULL DEFAULT '0',
  `start_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `start_time_usec` int(11) NOT NULL DEFAULT '0',
  `end_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `end_time_usec` int(11) NOT NULL DEFAULT '0',
  `command_object_id` int(11) NOT NULL DEFAULT '0',
  `command_args` varchar(255) COLLATE utf8_swedish_ci NOT NULL DEFAULT '',
  `command_line` varchar(255) COLLATE utf8_swedish_ci NOT NULL DEFAULT '',
  `timeout` smallint(6) NOT NULL DEFAULT '0',
  `early_timeout` smallint(6) NOT NULL DEFAULT '0',
  `execution_time` double NOT NULL DEFAULT '0',
  `latency` double NOT NULL DEFAULT '0',
  `return_code` smallint(6) NOT NULL DEFAULT '0',
  `output` varchar(1000) COLLATE utf8_swedish_ci NOT NULL,
  `long_output` text COLLATE utf8_swedish_ci NOT NULL,
  `perfdata` varchar(1000) COLLATE utf8_swedish_ci NOT NULL,
  PRIMARY KEY (`servicecheck_id`,`start_time`),
  KEY `start_time` (`start_time`),
  KEY `instance_id` (`instance_id`),
  KEY `service_object_id` (`service_object_id`),
  KEY `start_time_2` (`start_time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci COMMENT='Historical service checks';

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `nagios_servicedependencies`
--

CREATE TABLE IF NOT EXISTS `nagios_servicedependencies` (
  `servicedependency_id` int(11) NOT NULL AUTO_INCREMENT,
  `instance_id` smallint(6) NOT NULL DEFAULT '0',
  `config_type` smallint(6) NOT NULL DEFAULT '0',
  `service_object_id` int(11) NOT NULL DEFAULT '0',
  `dependent_service_object_id` int(11) NOT NULL DEFAULT '0',
  `dependency_type` smallint(6) NOT NULL DEFAULT '0',
  `inherits_parent` smallint(6) NOT NULL DEFAULT '0',
  `timeperiod_object_id` int(11) NOT NULL DEFAULT '0',
  `fail_on_ok` smallint(6) NOT NULL DEFAULT '0',
  `fail_on_warning` smallint(6) NOT NULL DEFAULT '0',
  `fail_on_unknown` smallint(6) NOT NULL DEFAULT '0',
  `fail_on_critical` smallint(6) NOT NULL DEFAULT '0',
  PRIMARY KEY (`servicedependency_id`),
  UNIQUE KEY `instance_id` (`instance_id`,`config_type`,`service_object_id`,`dependent_service_object_id`,`dependency_type`,`inherits_parent`,`fail_on_ok`,`fail_on_warning`,`fail_on_unknown`,`fail_on_critical`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci COMMENT='Service dependency definitions';

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `nagios_serviceescalations`
--

CREATE TABLE IF NOT EXISTS `nagios_serviceescalations` (
  `serviceescalation_id` int(11) NOT NULL AUTO_INCREMENT,
  `instance_id` smallint(6) NOT NULL DEFAULT '0',
  `config_type` smallint(6) NOT NULL DEFAULT '0',
  `service_object_id` int(11) NOT NULL DEFAULT '0',
  `timeperiod_object_id` int(11) NOT NULL DEFAULT '0',
  `first_notification` smallint(6) NOT NULL DEFAULT '0',
  `last_notification` smallint(6) NOT NULL DEFAULT '0',
  `notification_interval` double NOT NULL DEFAULT '0',
  `escalate_on_recovery` smallint(6) NOT NULL DEFAULT '0',
  `escalate_on_warning` smallint(6) NOT NULL DEFAULT '0',
  `escalate_on_unknown` smallint(6) NOT NULL DEFAULT '0',
  `escalate_on_critical` smallint(6) NOT NULL DEFAULT '0',
  PRIMARY KEY (`serviceescalation_id`),
  UNIQUE KEY `instance_id` (`instance_id`,`config_type`,`service_object_id`,`timeperiod_object_id`,`first_notification`,`last_notification`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci COMMENT='Service escalation definitions';

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `nagios_serviceescalation_contactgroups`
--

CREATE TABLE IF NOT EXISTS `nagios_serviceescalation_contactgroups` (
  `serviceescalation_contactgroup_id` int(11) NOT NULL AUTO_INCREMENT,
  `instance_id` smallint(6) NOT NULL DEFAULT '0',
  `serviceescalation_id` int(11) NOT NULL DEFAULT '0',
  `contactgroup_object_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`serviceescalation_contactgroup_id`),
  UNIQUE KEY `instance_id` (`serviceescalation_id`,`contactgroup_object_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci COMMENT='Service escalation contact groups';

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `nagios_serviceescalation_contacts`
--

CREATE TABLE IF NOT EXISTS `nagios_serviceescalation_contacts` (
  `serviceescalation_contact_id` int(11) NOT NULL AUTO_INCREMENT,
  `instance_id` smallint(6) NOT NULL DEFAULT '0',
  `serviceescalation_id` int(11) NOT NULL DEFAULT '0',
  `contact_object_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`serviceescalation_contact_id`),
  UNIQUE KEY `instance_id` (`instance_id`,`serviceescalation_id`,`contact_object_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `nagios_servicegroups`
--

CREATE TABLE IF NOT EXISTS `nagios_servicegroups` (
  `servicegroup_id` int(11) NOT NULL AUTO_INCREMENT,
  `instance_id` smallint(6) NOT NULL DEFAULT '0',
  `config_type` smallint(6) NOT NULL DEFAULT '0',
  `servicegroup_object_id` int(11) NOT NULL DEFAULT '0',
  `alias` varchar(255) COLLATE utf8_swedish_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`servicegroup_id`),
  UNIQUE KEY `instance_id` (`instance_id`,`config_type`,`servicegroup_object_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci COMMENT='Servicegroup definitions';

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `nagios_servicegroup_members`
--

CREATE TABLE IF NOT EXISTS `nagios_servicegroup_members` (
  `servicegroup_member_id` int(11) NOT NULL AUTO_INCREMENT,
  `instance_id` smallint(6) NOT NULL DEFAULT '0',
  `servicegroup_id` int(11) NOT NULL DEFAULT '0',
  `service_object_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`servicegroup_member_id`),
  UNIQUE KEY `instance_id` (`servicegroup_id`,`service_object_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci COMMENT='Servicegroup members';

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `nagios_services`
--

CREATE TABLE IF NOT EXISTS `nagios_services` (
  `service_id` int(11) NOT NULL AUTO_INCREMENT,
  `instance_id` smallint(6) NOT NULL DEFAULT '0',
  `config_type` smallint(6) NOT NULL DEFAULT '0',
  `host_object_id` int(11) NOT NULL DEFAULT '0',
  `service_object_id` int(11) NOT NULL DEFAULT '0',
  `display_name` varchar(64) COLLATE utf8_swedish_ci NOT NULL DEFAULT '',
  `check_command_object_id` int(11) NOT NULL DEFAULT '0',
  `check_command_args` varchar(1000) COLLATE utf8_swedish_ci NOT NULL DEFAULT '',
  `eventhandler_command_object_id` int(11) NOT NULL DEFAULT '0',
  `eventhandler_command_args` varchar(1000) COLLATE utf8_swedish_ci NOT NULL DEFAULT '',
  `notification_timeperiod_object_id` int(11) NOT NULL DEFAULT '0',
  `check_timeperiod_object_id` int(11) NOT NULL DEFAULT '0',
  `failure_prediction_options` varchar(64) COLLATE utf8_swedish_ci NOT NULL DEFAULT '',
  `check_interval` double NOT NULL DEFAULT '0',
  `retry_interval` double NOT NULL DEFAULT '0',
  `max_check_attempts` smallint(6) NOT NULL DEFAULT '0',
  `first_notification_delay` double NOT NULL DEFAULT '0',
  `notification_interval` double NOT NULL DEFAULT '0',
  `notify_on_warning` smallint(6) NOT NULL DEFAULT '0',
  `notify_on_unknown` smallint(6) NOT NULL DEFAULT '0',
  `notify_on_critical` smallint(6) NOT NULL DEFAULT '0',
  `notify_on_recovery` smallint(6) NOT NULL DEFAULT '0',
  `notify_on_flapping` smallint(6) NOT NULL DEFAULT '0',
  `notify_on_downtime` smallint(6) NOT NULL DEFAULT '0',
  `stalk_on_ok` smallint(6) NOT NULL DEFAULT '0',
  `stalk_on_warning` smallint(6) NOT NULL DEFAULT '0',
  `stalk_on_unknown` smallint(6) NOT NULL DEFAULT '0',
  `stalk_on_critical` smallint(6) NOT NULL DEFAULT '0',
  `is_volatile` smallint(6) NOT NULL DEFAULT '0',
  `flap_detection_enabled` smallint(6) NOT NULL DEFAULT '0',
  `flap_detection_on_ok` smallint(6) NOT NULL DEFAULT '0',
  `flap_detection_on_warning` smallint(6) NOT NULL DEFAULT '0',
  `flap_detection_on_unknown` smallint(6) NOT NULL DEFAULT '0',
  `flap_detection_on_critical` smallint(6) NOT NULL DEFAULT '0',
  `low_flap_threshold` double NOT NULL DEFAULT '0',
  `high_flap_threshold` double NOT NULL DEFAULT '0',
  `process_performance_data` smallint(6) NOT NULL DEFAULT '0',
  `freshness_checks_enabled` mediumint(8) NOT NULL DEFAULT '0',
  `freshness_threshold` smallint(6) NOT NULL DEFAULT '0',
  `passive_checks_enabled` smallint(6) NOT NULL DEFAULT '0',
  `event_handler_enabled` smallint(6) NOT NULL DEFAULT '0',
  `active_checks_enabled` smallint(6) NOT NULL DEFAULT '0',
  `retain_status_information` smallint(6) NOT NULL DEFAULT '0',
  `retain_nonstatus_information` smallint(6) NOT NULL DEFAULT '0',
  `notifications_enabled` smallint(6) NOT NULL DEFAULT '0',
  `obsess_over_service` smallint(6) NOT NULL DEFAULT '0',
  `failure_prediction_enabled` smallint(6) NOT NULL DEFAULT '0',
  `notes` varchar(255) COLLATE utf8_swedish_ci NOT NULL DEFAULT '',
  `notes_url` varchar(255) COLLATE utf8_swedish_ci NOT NULL DEFAULT '',
  `action_url` varchar(255) COLLATE utf8_swedish_ci NOT NULL DEFAULT '',
  `icon_image` varchar(255) COLLATE utf8_swedish_ci NOT NULL DEFAULT '',
  `icon_image_alt` varchar(255) COLLATE utf8_swedish_ci NOT NULL DEFAULT '',
  `importance` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`service_id`),
  UNIQUE KEY `instance_id` (`instance_id`,`config_type`,`service_object_id`),
  KEY `service_object_id` (`service_object_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci COMMENT='Service definitions';

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `nagios_servicestatus`
--

CREATE TABLE IF NOT EXISTS `nagios_servicestatus` (
  `servicestatus_id` int(11) NOT NULL AUTO_INCREMENT,
  `instance_id` smallint(6) NOT NULL DEFAULT '0',
  `service_object_id` int(11) NOT NULL DEFAULT '0',
  `status_update_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `output` varchar(512) COLLATE utf8_swedish_ci NOT NULL DEFAULT '',
  `long_output` text COLLATE utf8_swedish_ci NOT NULL,
  `perfdata` varchar(255) COLLATE utf8_swedish_ci NOT NULL DEFAULT '',
  `current_state` smallint(6) NOT NULL DEFAULT '0',
  `has_been_checked` smallint(6) NOT NULL DEFAULT '0',
  `should_be_scheduled` smallint(6) NOT NULL DEFAULT '0',
  `current_check_attempt` smallint(6) NOT NULL DEFAULT '0',
  `max_check_attempts` smallint(6) NOT NULL DEFAULT '0',
  `last_check` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `next_check` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `check_type` smallint(6) NOT NULL DEFAULT '0',
  `last_state_change` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `last_hard_state_change` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `last_hard_state` smallint(6) NOT NULL DEFAULT '0',
  `last_time_ok` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `last_time_warning` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `last_time_unknown` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `last_time_critical` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `state_type` smallint(6) NOT NULL DEFAULT '0',
  `last_notification` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `next_notification` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `no_more_notifications` smallint(6) NOT NULL DEFAULT '0',
  `notifications_enabled` smallint(6) NOT NULL DEFAULT '0',
  `problem_has_been_acknowledged` smallint(6) NOT NULL DEFAULT '0',
  `acknowledgement_type` smallint(6) NOT NULL DEFAULT '0',
  `current_notification_number` smallint(6) NOT NULL DEFAULT '0',
  `passive_checks_enabled` smallint(6) NOT NULL DEFAULT '0',
  `active_checks_enabled` smallint(6) NOT NULL DEFAULT '0',
  `event_handler_enabled` smallint(6) NOT NULL DEFAULT '0',
  `flap_detection_enabled` smallint(6) NOT NULL DEFAULT '0',
  `is_flapping` smallint(6) NOT NULL DEFAULT '0',
  `percent_state_change` double NOT NULL DEFAULT '0',
  `latency` double NOT NULL DEFAULT '0',
  `execution_time` double NOT NULL DEFAULT '0',
  `scheduled_downtime_depth` smallint(6) NOT NULL DEFAULT '0',
  `failure_prediction_enabled` smallint(6) NOT NULL DEFAULT '0',
  `process_performance_data` smallint(6) NOT NULL DEFAULT '0',
  `obsess_over_service` smallint(6) NOT NULL DEFAULT '0',
  `modified_service_attributes` int(11) NOT NULL DEFAULT '0',
  `event_handler` varchar(255) COLLATE utf8_swedish_ci NOT NULL DEFAULT '',
  `check_command` varchar(255) COLLATE utf8_swedish_ci NOT NULL DEFAULT '',
  `normal_check_interval` double NOT NULL DEFAULT '0',
  `retry_check_interval` double NOT NULL DEFAULT '0',
  `check_timeperiod_object_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`servicestatus_id`),
  UNIQUE KEY `object_id` (`service_object_id`),
  KEY `instance_id` (`instance_id`),
  KEY `status_update_time` (`status_update_time`),
  KEY `current_state` (`current_state`),
  KEY `check_type` (`check_type`),
  KEY `state_type` (`state_type`),
  KEY `last_state_change` (`last_state_change`),
  KEY `notifications_enabled` (`notifications_enabled`),
  KEY `problem_has_been_acknowledged` (`problem_has_been_acknowledged`),
  KEY `active_checks_enabled` (`active_checks_enabled`),
  KEY `passive_checks_enabled` (`passive_checks_enabled`),
  KEY `event_handler_enabled` (`event_handler_enabled`),
  KEY `flap_detection_enabled` (`flap_detection_enabled`),
  KEY `is_flapping` (`is_flapping`),
  KEY `percent_state_change` (`percent_state_change`),
  KEY `latency` (`latency`),
  KEY `execution_time` (`execution_time`),
  KEY `scheduled_downtime_depth` (`scheduled_downtime_depth`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci COMMENT='Current service status information';

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `nagios_service_contactgroups`
--

CREATE TABLE IF NOT EXISTS `nagios_service_contactgroups` (
  `service_contactgroup_id` int(11) NOT NULL AUTO_INCREMENT,
  `instance_id` smallint(6) NOT NULL DEFAULT '0',
  `service_id` int(11) NOT NULL DEFAULT '0',
  `contactgroup_object_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`service_contactgroup_id`),
  UNIQUE KEY `instance_id` (`service_id`,`contactgroup_object_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci COMMENT='Service contact groups';

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `nagios_service_contacts`
--

CREATE TABLE IF NOT EXISTS `nagios_service_contacts` (
  `service_contact_id` int(11) NOT NULL AUTO_INCREMENT,
  `instance_id` smallint(6) NOT NULL DEFAULT '0',
  `service_id` int(11) NOT NULL DEFAULT '0',
  `contact_object_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`service_contact_id`),
  UNIQUE KEY `instance_id` (`instance_id`,`service_id`,`contact_object_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `nagios_service_parentservices`
--

CREATE TABLE IF NOT EXISTS `nagios_service_parentservices` (
  `service_parentservice_id` int(11) NOT NULL AUTO_INCREMENT,
  `instance_id` smallint(6) NOT NULL DEFAULT '0',
  `service_id` int(11) NOT NULL DEFAULT '0',
  `parent_service_object_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`service_parentservice_id`),
  UNIQUE KEY `instance_id` (`service_id`,`parent_service_object_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci COMMENT='Parent services';

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `nagios_statehistory`
--

CREATE TABLE IF NOT EXISTS `nagios_statehistory` (
  `statehistory_id` int(11) NOT NULL AUTO_INCREMENT,
  `instance_id` smallint(6) NOT NULL DEFAULT '0',
  `state_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `state_time_usec` int(11) NOT NULL DEFAULT '0',
  `object_id` int(11) NOT NULL DEFAULT '0',
  `state_change` smallint(6) NOT NULL DEFAULT '0',
  `state` smallint(6) NOT NULL DEFAULT '0',
  `state_type` smallint(6) NOT NULL DEFAULT '0',
  `current_check_attempt` smallint(6) NOT NULL DEFAULT '0',
  `max_check_attempts` smallint(6) NOT NULL DEFAULT '0',
  `last_state` smallint(6) NOT NULL DEFAULT '-1',
  `last_hard_state` smallint(6) NOT NULL DEFAULT '-1',
  `output` varchar(255) COLLATE utf8_swedish_ci NOT NULL DEFAULT '',
  `long_output` text COLLATE utf8_swedish_ci NOT NULL,
  PRIMARY KEY (`statehistory_id`,`state_time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci COMMENT='Historical host and service state changes';

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `nagios_systemcommands`
--

CREATE TABLE IF NOT EXISTS `nagios_systemcommands` (
  `systemcommand_id` int(11) NOT NULL AUTO_INCREMENT,
  `instance_id` smallint(6) NOT NULL DEFAULT '0',
  `start_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `start_time_usec` int(11) NOT NULL DEFAULT '0',
  `end_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `end_time_usec` int(11) NOT NULL DEFAULT '0',
  `command_line` varchar(255) COLLATE utf8_swedish_ci NOT NULL DEFAULT '',
  `timeout` smallint(6) NOT NULL DEFAULT '0',
  `early_timeout` smallint(6) NOT NULL DEFAULT '0',
  `execution_time` double NOT NULL DEFAULT '0',
  `return_code` smallint(6) NOT NULL DEFAULT '0',
  `output` varchar(255) COLLATE utf8_swedish_ci NOT NULL DEFAULT '',
  `long_output` text COLLATE utf8_swedish_ci NOT NULL,
  PRIMARY KEY (`systemcommand_id`),
  KEY `instance_id` (`instance_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci COMMENT='Historical system commands that are executed';

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `nagios_timedeventqueue`
--

CREATE TABLE IF NOT EXISTS `nagios_timedeventqueue` (
  `timedeventqueue_id` int(11) NOT NULL AUTO_INCREMENT,
  `instance_id` smallint(6) NOT NULL DEFAULT '0',
  `event_type` smallint(6) NOT NULL DEFAULT '0',
  `queued_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `queued_time_usec` int(11) NOT NULL DEFAULT '0',
  `scheduled_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `recurring_event` smallint(6) NOT NULL DEFAULT '0',
  `object_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`timedeventqueue_id`),
  KEY `instance_id` (`instance_id`),
  KEY `event_type` (`event_type`),
  KEY `scheduled_time` (`scheduled_time`),
  KEY `object_id` (`object_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci COMMENT='Current Nagios event queue';

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `nagios_timedevents`
--

CREATE TABLE IF NOT EXISTS `nagios_timedevents` (
  `timedevent_id` int(11) NOT NULL AUTO_INCREMENT,
  `instance_id` smallint(6) NOT NULL DEFAULT '0',
  `event_type` smallint(6) NOT NULL DEFAULT '0',
  `queued_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `queued_time_usec` int(11) NOT NULL DEFAULT '0',
  `event_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `event_time_usec` int(11) NOT NULL DEFAULT '0',
  `scheduled_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `recurring_event` smallint(6) NOT NULL DEFAULT '0',
  `object_id` int(11) NOT NULL DEFAULT '0',
  `deletion_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deletion_time_usec` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`timedevent_id`),
  KEY `instance_id` (`instance_id`),
  KEY `event_type` (`event_type`),
  KEY `scheduled_time` (`scheduled_time`),
  KEY `object_id` (`object_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci COMMENT='Historical events from the Nagios event queue';

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `nagios_timeperiods`
--

CREATE TABLE IF NOT EXISTS `nagios_timeperiods` (
  `timeperiod_id` int(11) NOT NULL AUTO_INCREMENT,
  `instance_id` smallint(6) NOT NULL DEFAULT '0',
  `config_type` smallint(6) NOT NULL DEFAULT '0',
  `timeperiod_object_id` int(11) NOT NULL DEFAULT '0',
  `alias` varchar(255) COLLATE utf8_swedish_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`timeperiod_id`),
  UNIQUE KEY `instance_id` (`instance_id`,`config_type`,`timeperiod_object_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci COMMENT='Timeperiod definitions';

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `nagios_timeperiod_timeranges`
--

CREATE TABLE IF NOT EXISTS `nagios_timeperiod_timeranges` (
  `timeperiod_timerange_id` int(11) NOT NULL AUTO_INCREMENT,
  `instance_id` smallint(6) NOT NULL DEFAULT '0',
  `timeperiod_id` int(11) NOT NULL DEFAULT '0',
  `day` smallint(6) NOT NULL DEFAULT '0',
  `start_sec` int(11) NOT NULL DEFAULT '0',
  `end_sec` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`timeperiod_timerange_id`),
  UNIQUE KEY `instance_id` (`timeperiod_id`,`day`,`start_sec`,`end_sec`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci COMMENT='Timeperiod definitions';

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `proxies`
--

CREATE TABLE IF NOT EXISTS `proxies` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ipaddress` varchar(255) COLLATE utf8_swedish_ci NOT NULL,
  `port` int(5) NOT NULL,
  `enabled` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `registers`
--

CREATE TABLE IF NOT EXISTS `registers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `license` varchar(37) COLLATE utf8_swedish_ci NOT NULL,
  `accepted` tinyint(1) NOT NULL DEFAULT '0',
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;

-- --------------------------------------------------------


--
-- Tabellenstruktur für Tabelle `servicecommandargumentvalues`
--

CREATE TABLE IF NOT EXISTS `servicecommandargumentvalues` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `commandargument_id` int(11) NOT NULL,
  `service_id` int(11) NOT NULL,
  `value` varchar(500) COLLATE utf8_swedish_ci NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `servicedependencies`
--

CREATE TABLE IF NOT EXISTS `servicedependencies` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uuid` varchar(37) COLLATE utf8_swedish_ci NOT NULL,
  `inherits_parent` int(1) NOT NULL DEFAULT '0',
  `timeperiod_id` int(11) DEFAULT NULL,
  `execution_fail_on_ok` int(1) NOT NULL,
  `execution_fail_on_warning` int(1) NOT NULL,
  `execution_fail_on_unknown` int(1) NOT NULL,
  `execution_fail_on_critical` int(1) NOT NULL,
  `execution_fail_on_pending` int(1) NOT NULL,
  `execution_none` int(1) NOT NULL,
  `notification_fail_on_ok` int(1) NOT NULL,
  `notification_on_warning` int(1) NOT NULL,
  `notification_fail_on_unknown` int(1) NOT NULL,
  `notification_fail_on_critical` int(1) NOT NULL,
  `notification_fail_on_pending` int(1) NOT NULL,
  `notification_none` int(1) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `serviceescalations`
--

CREATE TABLE IF NOT EXISTS `serviceescalations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uuid` varchar(37) COLLATE utf8_swedish_ci NOT NULL,
  `timeperiod_id` int(11) NOT NULL,
  `first_notification` int(6) NOT NULL,
  `last_notification` int(6) NOT NULL,
  `notification_interval` int(6) NOT NULL,
  `escalate_on_recovery` int(1) NOT NULL,
  `escalate_on_warning` int(1) NOT NULL,
  `escalate_on_unknown` int(1) NOT NULL,
  `escalate_on_critical` int(1) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `servicegroups`
--

CREATE TABLE IF NOT EXISTS `servicegroups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uuid` varchar(37) COLLATE utf8_swedish_ci NOT NULL,
  `container_id` int(6) NOT NULL DEFAULT '0',
  `description` varchar(255) COLLATE utf8_swedish_ci NOT NULL,
  `servicegroup_url` varchar(255) COLLATE utf8_swedish_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uuid` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `servicegroups_to_servicedependencies`
--

CREATE TABLE IF NOT EXISTS `servicegroups_to_servicedependencies` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `servicegroup_id` int(11) NOT NULL,
  `servicedependency_id` int(11) NOT NULL,
  `dependent` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `servicegroup_id` (`servicegroup_id`,`dependent`),
  KEY `servicedependency_id` (`servicedependency_id`,`dependent`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `servicegroups_to_serviceescalations`
--

CREATE TABLE IF NOT EXISTS `servicegroups_to_serviceescalations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `servicegroup_id` int(11) NOT NULL,
  `serviceescalation_id` int(11) NOT NULL,
  `excluded` int(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `servicegroup_id` (`servicegroup_id`,`excluded`),
  KEY `serviceescalation_id` (`serviceescalation_id`,`excluded`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `services`
--

CREATE TABLE IF NOT EXISTS `services` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uuid` varchar(37) COLLATE utf8_swedish_ci NOT NULL,
  `servicetemplate_id` int(11) NOT NULL,
  `host_id` int(11) NOT NULL,
  `name` varchar(1500) COLLATE utf8_swedish_ci DEFAULT NULL,
  `description` varchar(255) COLLATE utf8_swedish_ci DEFAULT NULL,
  `command_id` int(11) DEFAULT NULL,
  `check_command_args` varchar(1000) COLLATE utf8_swedish_ci NOT NULL,
  `eventhandler_command_id` int(11) DEFAULT NULL,
  `notify_period_id` int(11) DEFAULT NULL,
  `check_period_id` int(11) DEFAULT NULL,
  `check_interval` float DEFAULT NULL,
  `retry_interval` float DEFAULT NULL,
  `max_check_attempts` int(6) DEFAULT NULL,
  `first_notification_delay` float DEFAULT NULL,
  `notification_interval` float DEFAULT NULL,
  `notify_on_warning` int(1) DEFAULT NULL,
  `notify_on_unknown` int(1) DEFAULT NULL,
  `notify_on_critical` int(1) DEFAULT NULL,
  `notify_on_recovery` int(1) DEFAULT NULL,
  `notify_on_flapping` int(1) DEFAULT NULL,
  `notify_on_downtime` int(1) DEFAULT NULL,
  `is_volatile` int(1) DEFAULT NULL,
  `flap_detection_enabled` int(1) DEFAULT NULL,
  `flap_detection_on_ok` int(1) DEFAULT NULL,
  `flap_detection_on_warning` int(1) DEFAULT NULL,
  `flap_detection_on_unknown` int(1) DEFAULT NULL,
  `flap_detection_on_critical` int(1) DEFAULT NULL,
  `low_flap_threshold` float DEFAULT NULL,
  `high_flap_threshold` float DEFAULT NULL,
  `process_performance_data` int(6) DEFAULT NULL,
  `freshness_checks_enabled` int(8) DEFAULT NULL,
  `freshness_threshold` int(6) DEFAULT NULL,
  `passive_checks_enabled` int(6) DEFAULT NULL,
  `event_handler_enabled` int(6) DEFAULT NULL,
  `active_checks_enabled` int(6) DEFAULT NULL,
  `notifications_enabled` int(6) DEFAULT NULL,
  `notes` varchar(255) COLLATE utf8_swedish_ci DEFAULT NULL,
  `priority` int(2) DEFAULT NULL,
  `tags` varchar(255) COLLATE utf8_swedish_ci DEFAULT NULL,
  `own_contacts` int(1) DEFAULT NULL,
  `own_contactgroups` int(1) DEFAULT NULL,
  `own_customvariables` int(1) DEFAULT NULL,
  `service_url` varchar(255) COLLATE utf8_swedish_ci DEFAULT NULL,
  `service_type` int(11) NOT NULL DEFAULT '1',
  `disabled` int(1) DEFAULT '0',
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uuid` (`uuid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;

--
-- Daten für Tabelle `services`
--

INSERT INTO `services` (`id`, `uuid`, `servicetemplate_id`, `host_id`, `name`, `description`, `command_id`, `check_command_args`, `eventhandler_command_id`, `notify_period_id`, `check_period_id`, `check_interval`, `retry_interval`, `max_check_attempts`, `first_notification_delay`, `notification_interval`, `notify_on_warning`, `notify_on_unknown`, `notify_on_critical`, `notify_on_recovery`, `notify_on_flapping`, `notify_on_downtime`, `is_volatile`, `flap_detection_enabled`, `flap_detection_on_ok`, `flap_detection_on_warning`, `flap_detection_on_unknown`, `flap_detection_on_critical`, `low_flap_threshold`, `high_flap_threshold`, `process_performance_data`, `freshness_checks_enabled`, `freshness_threshold`, `passive_checks_enabled`, `event_handler_enabled`, `active_checks_enabled`, `notifications_enabled`, `notes`, `priority`, `tags`, `own_contacts`, `own_contactgroups`, `own_customvariables`, `service_url`, `service_type`, `disabled`, `created`, `modified`) VALUES
(1, '74fd8f59-1348-4e16-85f0-4a5c57c7dd62', 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, NULL, 1, 0, '2015-01-15 19:26:46', '2015-01-15 19:26:46'),
(2, '74f14950-a58f-4f18-b6c3-5cfa9dffef4e', 8, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, NULL, 1, 0, '2015-01-16 00:46:39', '2015-01-16 00:46:39'),
(3, '1c045407-5502-4468-aabc-7781f6cf3dec', 9, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, NULL, 1, 0, '2015-01-16 00:46:52', '2015-01-16 00:46:52'),
(4, '7391f1aa-5e2e-447a-8a9b-b23357b9cd2a', 13, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, NULL, 1, 0, '2015-01-16 00:47:06', '2015-01-16 00:47:06');

-- --------------------------------------------------------


--
-- Tabellenstruktur für Tabelle `services_to_containers`
--

CREATE TABLE IF NOT EXISTS `services_to_containers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `service_id` int(11) NOT NULL,
  `container_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `services_to_servicedependencies`
--

CREATE TABLE IF NOT EXISTS `services_to_servicedependencies` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `service_id` int(11) NOT NULL,
  `servicedependency_id` int(11) NOT NULL,
  `dependent` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `service_id` (`service_id`,`dependent`),
  KEY `servicedependency_id` (`servicedependency_id`,`dependent`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `services_to_serviceescalations`
--

CREATE TABLE IF NOT EXISTS `services_to_serviceescalations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `service_id` int(11) NOT NULL,
  `serviceescalation_id` int(11) NOT NULL,
  `excluded` int(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `service_id` (`service_id`,`excluded`),
  KEY `serviceescalation_id` (`serviceescalation_id`,`excluded`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `services_to_servicegroups`
--

CREATE TABLE IF NOT EXISTS `services_to_servicegroups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `service_id` int(11) NOT NULL,
  `servicegroup_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `service_id` (`service_id`),
  KEY `servicegroup_id` (`servicegroup_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `servicetemplatecommandargumentvalues`
--

CREATE TABLE IF NOT EXISTS `servicetemplatecommandargumentvalues` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `commandargument_id` int(11) NOT NULL,
  `servicetemplate_id` int(11) NOT NULL,
  `value` varchar(500) COLLATE utf8_swedish_ci NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;

--
-- Daten für Tabelle `servicetemplatecommandargumentvalues`
--

INSERT INTO `servicetemplatecommandargumentvalues` (`id`, `commandargument_id`, `servicetemplate_id`, `value`, `created`, `modified`) VALUES
(1, 1, 1, '100.0,20%', '2015-01-05 15:20:14', '2015-01-15 23:46:17'),
(2, 2, 1, '500.0,60%', '2015-01-05 15:20:14', '2015-01-15 23:46:17'),
(3, 37, 3, '', '2015-01-15 23:47:34', '2015-01-15 23:47:34'),
(4, 38, 3, '', '2015-01-15 23:47:34', '2015-01-15 23:47:34'),
(5, 8, 6, '1', '2015-01-15 23:51:53', '2015-01-15 23:51:53'),
(6, 9, 6, '2', '2015-01-15 23:51:53', '2015-01-15 23:51:53'),
(7, 10, 6, '/', '2015-01-15 23:51:53', '2015-01-15 23:51:53'),
(8, 8, 7, '1', '2015-01-15 23:52:49', '2015-01-15 23:52:49'),
(9, 9, 7, '2', '2015-01-15 23:52:49', '2015-01-15 23:52:49'),
(10, 10, 7, '/', '2015-01-15 23:52:49', '2015-01-15 23:52:49'),
(11, 23, 8, '80', '2015-01-15 23:55:07', '2015-01-15 23:55:07'),
(12, 24, 8, '90', '2015-01-15 23:55:07', '2015-01-15 23:55:07'),
(13, 25, 8, '/', '2015-01-15 23:55:07', '2015-01-15 23:55:07'),
(14, 28, 9, '7.0,6.0,5.0', '2015-01-15 23:56:25', '2015-01-15 23:56:25'),
(15, 29, 9, '10.0,7.0,6.0', '2015-01-15 23:56:25', '2015-01-15 23:56:25'),
(16, 30, 10, '25', '2015-01-15 23:57:39', '2015-01-15 23:57:39'),
(17, 31, 10, '50', '2015-01-15 23:57:39', '2015-01-15 23:57:39'),
(18, 32, 11, '1:10', '2015-01-16 00:01:26', '2015-01-16 00:01:26'),
(19, 33, 11, '1:20', '2015-01-16 00:01:26', '2015-01-16 00:01:26'),
(20, 34, 11, '/usr/sbin/nginx', '2015-01-16 00:01:26', '2015-01-16 00:01:26'),
(21, 35, 12, '', '2015-01-16 00:03:20', '2015-01-16 00:03:20'),
(22, 36, 12, '', '2015-01-16 00:03:20', '2015-01-16 00:03:20'),
(23, 26, 13, '3', '2015-01-16 00:04:24', '2015-01-16 00:04:24'),
(24, 27, 13, '7', '2015-01-16 00:04:24', '2015-01-16 00:04:24'),
(25, 14, 14, '', '2015-01-16 00:05:27', '2015-01-16 00:05:27'),
(26, 15, 16, '2', '2015-01-16 00:07:52', '2015-01-16 00:07:52'),
(27, 16, 16, '5', '2015-01-16 00:07:52', '2015-01-16 00:07:52'),
(28, 21, 17, '500', '2015-01-16 00:10:54', '2015-01-16 00:10:54'),
(29, 22, 17, '1000', '2015-01-16 00:10:54', '2015-01-16 00:10:54'),
(30, 19, 18, '500', '2015-01-16 00:11:57', '2015-01-16 00:11:57'),
(31, 20, 18, '1000', '2015-01-16 00:11:57', '2015-01-16 00:11:57'),
(32, 39, 20, '(150.0 ,200.0)', '2015-01-16 00:18:00', '2015-01-16 00:18:00'),
(33, 39, 21, '(100, 120, 50.0, 30.0)', '2015-01-16 00:18:47', '2015-01-16 00:18:47'),
(34, 39, 22, '[''rw'']', '2015-01-16 00:19:32', '2015-01-16 00:19:32'),
(35, 39, 23, '(0.01, 0.1)', '2015-01-16 00:20:21', '2015-01-16 00:20:21'),
(36, 39, 24, 'True', '2015-01-16 00:20:59', '2015-01-16 00:20:59'),
(37, 39, 25, '(''1000Mb/s'', ''Full'', ''off'')', '2015-01-16 00:21:33', '2015-01-16 00:21:33'),
(38, 39, 26, '[80,90]', '2015-01-16 00:22:20', '2015-01-16 00:22:20'),
(39, 39, 27, '(3, 5.0, 10.0)', '2015-01-16 00:22:54', '2015-01-16 00:22:54'),
(40, 39, 28, '(25,50)', '2015-01-16 00:23:36', '2015-01-16 00:23:36'),
(41, 39, 29, '(_PROC_,1,1,98,99)', '2015-01-16 00:24:07', '2015-01-15 18:54:04'),
(42, 39, 30, '', '2015-01-16 00:24:53', '2015-01-16 00:24:53'),
(43, 39, 31, '', '2015-01-16 00:25:29', '2015-01-16 00:25:29'),
(44, 39, 32, '(90,95)', '2015-01-16 00:26:04', '2015-01-16 00:26:04'),
(45, 39, 33, '{ ''read'': (10, 20), ''write'': (10,40), ''average'': 15 }', '2015-01-16 00:26:36', '2015-01-15 18:56:40'),
(46, 39, 35, '(2000, 4000)', '2015-01-16 00:28:15', '2015-01-16 00:28:15'),
(47, 39, 36, '(80, 90)', '2015-01-16 00:29:04', '2015-01-16 00:29:04'),
(48, 39, 37, '{ ''read'': (10, 20), ''write'': (10,40), ''average'': 15 }', '2015-01-16 00:29:36', '2015-01-15 18:57:51'),
(49, 39, 38, '(None,None)', '2015-01-16 00:30:17', '2015-01-16 00:37:35'),
(50, 39, 39, '(101,102)', '2015-01-16 00:30:50', '2015-01-16 00:30:50'),
(51, 39, 40, '', '2015-01-16 00:31:22', '2015-01-16 00:31:22'),
(52, 39, 34, '(4,8)', '2015-01-16 00:39:09', '2015-01-16 00:39:09');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `servicetemplategroups`
--

CREATE TABLE IF NOT EXISTS `servicetemplategroups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uuid` varchar(37) COLLATE utf8_swedish_ci NOT NULL,
  `container_id` int(11) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uuid` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `servicetemplates`
--

CREATE TABLE IF NOT EXISTS `servicetemplates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uuid` varchar(37) COLLATE utf8_swedish_ci NOT NULL,
  `name` varchar(255) COLLATE utf8_swedish_ci NOT NULL,
  `container_id` int(11) DEFAULT NULL,
  `servicetemplatetype_id` int(11) NOT NULL DEFAULT '1',
  `check_period_id` int(11) DEFAULT NULL,
  `notify_period_id` int(11) DEFAULT NULL,
  `description` varchar(255) COLLATE utf8_swedish_ci NOT NULL,
  `command_id` int(11) NOT NULL DEFAULT '0',
  `check_command_args` varchar(1000) COLLATE utf8_swedish_ci NOT NULL,
  `checkcommand_info` varchar(255) COLLATE utf8_swedish_ci NOT NULL,
  `eventhandler_command_id` int(11) NOT NULL DEFAULT '0',
  `timeperiod_id` int(11) NOT NULL,
  `check_interval` int(5) NOT NULL DEFAULT '1',
  `retry_interval` int(5) NOT NULL DEFAULT '3',
  `max_check_attempts` int(3) NOT NULL DEFAULT '1',
  `first_notification_delay` float NOT NULL DEFAULT '0',
  `notification_interval` float NOT NULL DEFAULT '0',
  `notify_on_warning` int(1) NOT NULL DEFAULT '0',
  `notify_on_unknown` int(1) NOT NULL DEFAULT '0',
  `notify_on_critical` int(1) NOT NULL DEFAULT '0',
  `notify_on_recovery` tinyint(1) NOT NULL DEFAULT '0',
  `notify_on_flapping` int(1) NOT NULL DEFAULT '0',
  `notify_on_downtime` int(1) NOT NULL DEFAULT '0',
  `flap_detection_enabled` int(1) NOT NULL DEFAULT '0',
  `flap_detection_on_ok` int(1) NOT NULL DEFAULT '0',
  `flap_detection_on_warning` int(1) NOT NULL DEFAULT '0',
  `flap_detection_on_unknown` int(1) NOT NULL DEFAULT '0',
  `flap_detection_on_critical` tinyint(1) NOT NULL DEFAULT '0',
  `low_flap_threshold` float NOT NULL DEFAULT '0',
  `high_flap_threshold` float NOT NULL DEFAULT '0',
  `process_performance_data` int(6) NOT NULL DEFAULT '0',
  `freshness_checks_enabled` int(6) NOT NULL DEFAULT '0',
  `freshness_threshold` int(8) DEFAULT '0',
  `passive_checks_enabled` int(1) NOT NULL DEFAULT '0',
  `event_handler_enabled` int(1) NOT NULL DEFAULT '0',
  `active_checks_enabled` int(1) NOT NULL DEFAULT '0',
  `retain_status_information` int(6) NOT NULL DEFAULT '0',
  `retain_nonstatus_information` int(6) NOT NULL DEFAULT '0',
  `notifications_enabled` int(6) NOT NULL DEFAULT '0',
  `notes` varchar(255) COLLATE utf8_swedish_ci NOT NULL,
  `priority` int(2) DEFAULT NULL,
  `tags` varchar(1500) COLLATE utf8_swedish_ci DEFAULT NULL,
  `service_url` varchar(255) COLLATE utf8_swedish_ci DEFAULT NULL,
  `is_volatile` tinyint(1) NOT NULL DEFAULT '0',
  `check_freshness` tinyint(1) NOT NULL DEFAULT '0',
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uuid` (`uuid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;

--
-- Daten für Tabelle `servicetemplates`
--

INSERT INTO `servicetemplates` (`id`, `uuid`, `name`, `container_id`, `servicetemplatetype_id`, `check_period_id`, `notify_period_id`, `description`, `command_id`, `check_command_args`, `checkcommand_info`, `eventhandler_command_id`, `timeperiod_id`, `check_interval`, `retry_interval`, `max_check_attempts`, `first_notification_delay`, `notification_interval`, `notify_on_warning`, `notify_on_unknown`, `notify_on_critical`, `notify_on_recovery`, `notify_on_flapping`, `notify_on_downtime`, `flap_detection_enabled`, `flap_detection_on_ok`, `flap_detection_on_warning`, `flap_detection_on_unknown`, `flap_detection_on_critical`, `low_flap_threshold`, `high_flap_threshold`, `process_performance_data`, `freshness_checks_enabled`, `freshness_threshold`, `passive_checks_enabled`, `event_handler_enabled`, `active_checks_enabled`, `retain_status_information`, `retain_nonstatus_information`, `notifications_enabled`, `notes`, `priority`, `tags`, `service_url`, `is_volatile`, `check_freshness`, `created`, `modified`) VALUES
(1, '3eb9db30-c9cf-4c25-9c69-0c3c01dc2256', 'Ping', 1, 1, 1, 1, 'Lan-Ping', 3, '', '', 0, 0, 300, 60, 3, 0, 7200, 0, 0, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, NULL, 0, 0, 1, 0, 0, 0, '', 1, '', '', 0, 0, '2015-01-05 15:20:14', '2015-01-15 23:46:17'),
(2, 'fd250454-4065-4218-8e8e-4b0325eddf13', 'CHECK_SSH', 1, 1, 1, 1, 'SSH', 11, '', '', 0, 0, 300, 60, 3, 0, 7200, 0, 0, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, 0, 0, 1, 0, 0, 0, '', 1, '', '', 0, 0, '2015-01-15 23:44:39', '2015-01-15 23:45:45'),
(3, 'cd5f7e0a-3682-4734-806d-455f1b296888', 'CHECK_BY_SSH', 1, 1, 1, 1, 'Execute a command on a remote host', 23, '', '', 0, 0, 900, 300, 3, 0, 7200, 0, 0, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, NULL, 0, 0, 1, 0, 0, 0, '', 1, '', NULL, 0, 0, '2015-01-15 23:47:34', '2015-01-15 23:47:34'),
(4, 'eec01955-9aee-4763-b205-f3021480eee5', 'CHECK_DHCP', 1, 1, 1, 0, 'Check if DHCP server returns the right IP address', 5, '', '', 0, 0, 300, 90, 3, 0, 0, 0, 0, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, NULL, 0, 0, 1, 0, 0, 0, '', 1, '', NULL, 0, 0, '2015-01-15 23:49:21', '2015-01-15 23:49:21'),
(5, '632cbb02-4046-4406-8bcc-4cfe0befbc0c', 'CHECK_FTP', 1, 1, 1, 1, 'Test a FTP connection for given host', 6, '', '', 0, 0, 300, 90, 3, 0, 7200, 0, 0, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, NULL, 0, 0, 1, 0, 0, 0, '', 1, '', NULL, 0, 0, '2015-01-15 23:50:41', '2015-01-15 23:50:41'),
(6, 'b987e6c0-135d-4034-987a-18c65b111fae', 'CHECK_HTTP', 1, 1, 1, 0, 'Send request to an HTTP server', 7, '', '', 0, 0, 300, 90, 3, 0, 0, 0, 0, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, NULL, 0, 0, 1, 0, 0, 0, '', 1, '', NULL, 0, 0, '2015-01-15 23:51:53', '2015-01-15 23:51:53'),
(7, 'b12e5fcd-eccc-43f9-8c84-921329e56c92', 'CHECK_HTTPS', 1, 1, 1, 1, 'Send request to an HTTPS server', 7, '', '', 0, 0, 300, 90, 3, 0, 7200, 0, 0, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, NULL, 0, 0, 1, 0, 0, 0, '', 1, '', NULL, 0, 0, '2015-01-15 23:52:49', '2015-01-15 23:52:49'),
(8, '354c1e0e-bd09-48e0-bbbe-eb98a4059454', 'CHECK_LOCAL_DISK', 1, 1, 1, 1, 'Checks a local disk of the openITCOCKPIT Server', 17, '', '', 0, 0, 1800, 300, 2, 0, 7200, 0, 0, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, NULL, 0, 0, 1, 0, 0, 0, '', 1, '', NULL, 0, 0, '2015-01-15 23:55:07', '2015-01-15 23:55:07'),
(9, 'c673cc09-c46a-4916-8e42-85cb0e68f1e6', 'CHECK_LOCAL_LOAD', 1, 1, 1, 1, 'Checks the CPU load of the openITCOCKPIT Server', 19, '', '', 0, 0, 300, 90, 3, 0, 7200, 0, 0, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, NULL, 0, 0, 1, 0, 0, 0, '', 1, '', NULL, 0, 0, '2015-01-15 23:56:25', '2015-01-15 23:56:25'),
(10, '90aefb61-c818-4bd6-818c-c9e6174b3e13', 'CHECK_LOCAL_MAILQ', 1, 1, 1, 1, 'Checks the mailq of the openITCOCKPIT Server', 20, '', '', 0, 0, 900, 300, 2, 0, 7200, 0, 0, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, NULL, 0, 0, 1, 0, 0, 0, '', 1, '', NULL, 0, 0, '2015-01-15 23:57:39', '2015-01-15 23:57:39'),
(11, '91bc9075-64b2-4054-a39d-c2981cd6bd01', 'CHECK_LOCAL_PROC', 1, 1, 1, 1, 'Check if a process is running on openITCOCKPIT server', 21, '', '', 0, 0, 300, 120, 3, 0, 7200, 0, 0, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, NULL, 0, 0, 1, 0, 0, 0, '', 1, '', NULL, 0, 0, '2015-01-16 00:01:26', '2015-01-16 00:01:26'),
(12, 'b7e5c7a7-ed33-465f-ab8e-45059cb679fe', 'CHECK_LOCAL_PROCS_TOTAL', 1, 1, 1, 1, 'Checks how many processes are running on openITCOCKPIT Server', 22, '', '', 0, 0, 300, 90, 3, 0, 7200, 0, 0, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, NULL, 0, 0, 1, 0, 0, 0, '', 1, '', NULL, 0, 0, '2015-01-16 00:03:20', '2015-01-16 00:03:20'),
(13, '5b0b27c4-6e70-454b-a778-b7b050910abb', 'CHECK_LOCAL_USERS', 1, 1, 1, 1, 'Checks how many users are logged in to the openITCOCKPIT server backend', 18, '', '', 0, 0, 300, 60, 3, 0, 7200, 0, 0, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, NULL, 0, 0, 1, 0, 0, 0, '', 1, '', NULL, 0, 0, '2015-01-16 00:04:24', '2015-01-16 00:04:24'),
(14, 'a4773ab9-ed5f-4395-b0c7-bb98bb975b06', 'CHECK_TCP', 1, 1, 1, 1, 'Checks a TCP port', 10, '', '', 0, 0, 300, 90, 3, 0, 7200, 0, 0, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, NULL, 0, 0, 1, 0, 0, 0, '', 1, '', NULL, 0, 0, '2015-01-16 00:05:27', '2015-01-16 00:05:27'),
(15, '70ef8cb7-b253-4b81-9515-335e0ade1d8f', 'CHECK_TELNET', 1, 1, 1, 1, 'Check if a telnet server is running on target host', 9, '', '', 0, 0, 300, 60, 3, 0, 7200, 0, 0, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, NULL, 0, 0, 1, 0, 0, 0, '', 1, '', NULL, 0, 0, '2015-01-16 00:06:18', '2015-01-16 00:06:18'),
(16, '52ac5010-4014-41c2-ba3a-74bc765e442d', 'CHECK_SMTP', 1, 1, 1, 1, 'Check if a SNTP server is running on target host', 12, '', '', 0, 0, 300, 90, 3, 0, 7200, 0, 0, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, NULL, 0, 0, 1, 0, 0, 0, '', 1, '', NULL, 0, 0, '2015-01-16 00:07:52', '2015-01-16 00:07:52'),
(17, 'b80db4b7-3148-4c39-9337-d89d367b9669', 'CHECK_NTP_PEER', 1, 1, 1, 1, 'Check the NTP offset if target host', 15, '', '', 0, 0, 300, 90, 3, 0, 7200, 0, 0, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, NULL, 0, 0, 1, 0, 0, 0, '', 1, '', NULL, 0, 0, '2015-01-16 00:10:54', '2015-01-16 00:10:54'),
(18, '9babe8f0-9012-47a8-8580-f82246365dd8', 'CHECK_NTP_TIME', 1, 1, 1, 1, 'Check the NTP offset if target host', 14, '', '', 0, 0, 300, 90, 3, 0, 7200, 0, 0, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, NULL, 0, 0, 1, 0, 0, 0, '', 1, '', NULL, 0, 0, '2015-01-16 00:11:57', '2015-01-16 00:11:57'),
(19, '3634eec0-a006-46ff-88cb-5355517232eb', 'CHECK_MK_ACTIVE', 1, 8, 1, 1, 'Execute the active check_mk data colector', 24, '', '', 0, 0, 60, 60, 1, 0, 7200, 0, 0, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, NULL, 0, 0, 1, 0, 0, 0, '', 1, '', NULL, 0, 0, '2015-01-16 00:14:45', '2015-01-16 00:14:45'),
(20, '331cb374-df79-4b72-9c51-2edb7fbb1e79', 'CHECK_MK_MEM_USED', 1, 8, 1, 1, 'Memory', 16, '', '', 0, 0, 60, 60, 1, 0, 7200, 0, 0, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, NULL, 0, 0, 0, 0, 0, 0, '', 1, '', '', 0, 0, '2015-01-16 00:17:22', '2015-01-16 00:18:00'),
(21, '5bd24dbf-6b88-4736-8860-a546d281de77', 'CHECK_MK_MEM_VMALLOC', 1, 8, 1, 1, 'Memory-vmalloc', 16, '', '', 0, 0, 60, 60, 1, 0, 7200, 0, 0, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, NULL, 0, 0, 0, 0, 0, 0, '', 1, '', NULL, 0, 0, '2015-01-16 00:18:47', '2015-01-16 00:18:47'),
(22, '3bafc389-c87e-43fb-86d3-e91471492e9b', 'CHECK_MK_MOUNTS', 1, 8, 1, 1, 'Mounts', 16, '', '', 0, 0, 60, 60, 1, 0, 7200, 0, 0, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, NULL, 0, 0, 0, 0, 0, 0, '', 1, '', NULL, 0, 0, '2015-01-16 00:19:32', '2015-01-16 00:19:32'),
(23, '8a7bd583-9764-43c6-9989-4ffa927b19ab', 'CHECK_MK_NETCTR_COMBINED', 1, 8, 1, 1, 'NetCTR-Combined', 16, '', '', 0, 0, 60, 60, 1, 0, 7200, 0, 0, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, NULL, 0, 0, 0, 0, 0, 0, '', 1, '', NULL, 0, 0, '2015-01-16 00:20:21', '2015-01-16 00:20:21'),
(24, '3b388dc5-cc3c-4348-a2fa-0de4c3d6605a', 'CHECK_MK_NETIF_LINK', 1, 8, 1, 1, 'NetIF-Link', 16, '', '', 0, 0, 60, 60, 1, 0, 7200, 0, 0, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, NULL, 0, 0, 0, 0, 0, 0, '', 1, '', NULL, 0, 0, '2015-01-16 00:20:59', '2015-01-16 00:20:59'),
(25, '633a9e82-a9a0-4097-8107-43d5c0ce3709', 'CHECK_MK_NETIF_PARAMS', 1, 8, 1, 1, 'NetIF-Params', 16, '', '', 0, 0, 60, 60, 1, 0, 7200, 0, 0, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, NULL, 0, 0, 0, 0, 0, 0, '', 1, '', NULL, 0, 0, '2015-01-16 00:21:33', '2015-01-16 00:21:33'),
(26, '40824c10-ef1d-4625-9a5d-519ca641e403', 'CHECK_MK_NFSMOUNTS', 1, 8, 1, 1, 'NFSMount', 16, '', '', 0, 0, 60, 60, 1, 0, 7200, 0, 0, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, NULL, 0, 0, 0, 0, 0, 0, '', 1, '', NULL, 0, 0, '2015-01-16 00:22:20', '2015-01-16 00:22:20'),
(27, 'eccfc508-68a3-483e-95b7-8b57e532ae81', 'CHECK_MK_NTP_TIME', 1, 8, 1, 1, 'NTP-Time', 16, '', '', 0, 0, 60, 60, 1, 0, 7200, 0, 0, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, NULL, 0, 0, 0, 0, 0, 0, '', 1, '', NULL, 0, 0, '2015-01-16 00:22:54', '2015-01-16 00:22:54'),
(28, '4c234f5b-111d-413c-9181-1dc2bbaf0441', 'CHECK_MK_POSTFIX_MAILQ', 1, 8, 1, 1, 'Postfix-Mailq', 16, '', '', 0, 0, 60, 60, 1, 0, 7200, 0, 0, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, NULL, 0, 0, 0, 0, 0, 0, '', 1, '', NULL, 0, 0, '2015-01-16 00:23:36', '2015-01-16 00:23:36'),
(29, 'a6a9cc08-225e-4066-8c6d-ba4f45b26e07', 'CHECK_MK_PS', 1, 8, 1, 1, 'proc_', 16, '', '', 0, 0, 60, 60, 1, 0, 7200, 0, 0, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, NULL, 0, 0, 0, 0, 0, 0, '', 1, '', '', 0, 0, '2015-01-16 00:24:07', '2015-01-15 18:54:04'),
(30, '59ef450a-5240-46dd-a441-953371abef95', 'CHECK_MK_SERVICE', 1, 8, 1, 1, 'service_', 16, '', '', 0, 0, 60, 60, 5, 0, 7200, 0, 0, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, NULL, 0, 0, 0, 0, 0, 0, '', 1, '', NULL, 0, 0, '2015-01-16 00:24:53', '2015-01-16 00:24:53'),
(31, 'f9728a8d-be9b-4879-ae5e-e5f2377e4c56', 'CHECK_MK_UPTIME', 1, 8, 1, 1, 'Uptime', 16, '', '', 0, 0, 60, 60, 1, 0, 7200, 0, 0, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, NULL, 0, 0, 0, 0, 0, 0, '', 1, '', NULL, 0, 0, '2015-01-16 00:25:29', '2015-01-16 00:25:29'),
(32, '6ad6a6a8-0a1e-4c94-add5-eacdef149356', 'CHECK_MK_WINPERF_CPUUSAGE', 1, 8, 1, 1, 'CPU-Usage', 16, '', '', 0, 0, 60, 60, 1, 0, 7200, 0, 0, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, NULL, 0, 0, 0, 0, 0, 0, '', 1, '', NULL, 0, 0, '2015-01-16 00:26:04', '2015-01-16 00:26:04'),
(33, '0afbf35e-ac90-407a-bd4b-0e6ed6450e5b', 'CHECK_MK_WINPERF_DISKSTAT', 1, 8, 1, 1, 'Disk-Stat', 16, '', '', 0, 0, 60, 60, 1, 0, 7200, 0, 0, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, NULL, 0, 0, 0, 0, 0, 0, '', 1, '', '', 0, 0, '2015-01-16 00:26:36', '2015-01-15 18:56:40'),
(34, 'bcca054b-f9e3-4d77-8010-b950acc7651a', 'CHECK_MK_CPU_LOADS', 1, 8, 1, 1, 'CPU-Load', 16, '', '', 0, 0, 60, 60, 1, 0, 7200, 0, 0, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, NULL, 0, 0, 0, 0, 0, 0, '', 1, '', '', 0, 0, '2015-01-16 00:27:39', '2015-01-16 00:39:09'),
(35, 'e2376f24-36d3-48ea-a5ac-e972d6b81a85', 'CHECK_MK_CPU_THREADS', 1, 8, 1, 1, 'CPU-threads', 16, '', '', 0, 0, 60, 60, 1, 0, 7200, 0, 0, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, NULL, 0, 0, 0, 0, 0, 0, '', 1, '', NULL, 0, 0, '2015-01-16 00:28:15', '2015-01-16 00:28:15'),
(36, '77bce4e3-4535-49f0-ba96-b5879e0023e6', 'CHECK_MK_DF', 1, 8, 1, 1, 'df_', 16, '', '', 0, 0, 60, 60, 1, 0, 7200, 0, 0, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, NULL, 0, 0, 0, 0, 0, 0, '', 1, '', NULL, 0, 0, '2015-01-16 00:29:04', '2015-01-16 00:29:04'),
(37, 'e819a5a5-ad93-4c50-971a-132abccb26d2', 'CHECK_MK_DISKSTAT', 1, 8, 1, 1, 'Disk-Stat', 16, '', '', 0, 0, 60, 60, 1, 0, 7200, 0, 0, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, NULL, 0, 0, 0, 0, 0, 0, '', 1, '', '', 0, 0, '2015-01-16 00:29:36', '2015-01-15 18:57:51'),
(38, '5e6721e7-34b6-492b-b5f7-83396e7766c7', 'CHECK_MK_KERNEL', 1, 8, 1, 1, 'Kernel', 16, '', '', 0, 0, 60, 60, 1, 0, 7200, 0, 0, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, NULL, 0, 0, 0, 0, 0, 0, '', 1, '', '', 0, 0, '2015-01-16 00:30:17', '2015-01-16 00:37:35'),
(39, '50650487-c48f-4af0-810a-887a4c1ff447', 'CHECK_MK_KERNEL_UTIL', 1, 8, 1, 1, 'Kernel-Util', 16, '', '', 0, 0, 60, 60, 1, 0, 7200, 0, 0, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, NULL, 0, 0, 0, 0, 0, 0, '', 1, '', NULL, 0, 0, '2015-01-16 00:30:50', '2015-01-16 00:30:50'),
(40, '2ecf4324-437c-4fd5-85fe-b8fd07be1a96', 'CHECK_MK_LOGWATCH', 1, 8, 1, 1, 'LogWatch', 16, '', '', 0, 0, 60, 60, 1, 0, 7200, 0, 0, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, NULL, 0, 0, 0, 0, 0, 0, '', 1, '', NULL, 0, 0, '2015-01-16 00:31:22', '2015-01-16 00:31:22'),
(41, '0fb9cee0-b3be-4ac1-8917-3fc5d2984d76', 'CHECK_MK_WINPERF_PROCESSOR_UTIL', 1, 8, 1, 1, 'Processor Util', 16, '', '', 0, 0, 60, 60, 1, 0, 7200, 0, 0, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, NULL, 0, 0, 0, 0, 0, 0, '', 1, '', NULL, 0, 0, '2015-01-15 18:56:02', '2015-01-15 18:56:02'),
(42, 'c6e07b02-bd67-4109-822e-6d23f9f157d6', 'CHECK_MK_ORACLE_TABLESPACES', 1, 8, 1, 1, 'Oracle Tablespace', 16, '', '', 0, 0, 60, 60, 1, 0, 7200, 0, 0, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, NULL, 0, 0, 0, 0, 0, 0, '', 1, '', NULL, 0, 0, '2015-01-15 18:59:28', '2015-01-15 18:59:28'),
(43, '02e0eaaf-c4bb-4d36-a3e8-0ca809bc3665', 'CHECK_MK_MEM_WIN', 1, 8, 1, 1, 'Windows Memory', 16, '', '', 0, 0, 60, 60, 1, 0, 7200, 0, 0, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, NULL, 0, 0, 0, 0, 0, 0, '', 1, '', NULL, 0, 0, '2015-01-15 19:00:18', '2015-01-15 19:00:18'),
(44, 'ca552795-e698-4821-ac78-f22f31653d25', 'CHECK_MK_WINPERF_IF', 1, 8, 1, 1, 'Winperf Interface', 16, '', '', 0, 0, 60, 60, 1, 0, 7200, 0, 0, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, NULL, 0, 0, 0, 0, 0, 0, '', 1, '', NULL, 0, 0, '2015-01-15 19:01:14', '2015-01-15 19:01:14'),
(45, '92dd4fc1-06e5-441c-8342-89cd64b9e13e', 'CHECK_MK_SYSTEMTIME', 1, 8, 1, 1, 'Systemtime', 16, '', '', 0, 0, 60, 60, 1, 0, 7200, 0, 0, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, NULL, 0, 0, 0, 0, 0, 0, '', 1, '', NULL, 0, 0, '2015-01-15 19:01:59', '2015-01-15 19:01:59'),
(46, 'f43bebf4-ad50-4477-aa4f-968ac204829c', 'CHECK_MK_SERVICES_SUMMARY', 1, 8, 1, 1, 'Service summary', 16, '', '', 0, 0, 60, 60, 1, 0, 7200, 0, 0, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, NULL, 0, 0, 0, 0, 0, 0, '', 1, '', NULL, 0, 0, '2015-01-15 19:02:52', '2015-01-15 19:02:52'),
(47, 'e753c9ec-60e5-41c8-a4fd-a3567094c392', 'CHECK_MK_LNX_IF', 1, 8, 1, 1, 'LNX Interface', 16, '', '', 0, 0, 60, 60, 1, 0, 7200, 0, 0, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, NULL, 0, 0, 0, 0, 0, 0, '', 1, '', NULL, 0, 0, '2015-01-15 19:03:40', '2015-01-15 19:03:40'),
(48, 'f54f914b-0215-4681-bbb1-173ba1b46d06', 'CHECK_MK_TCP_CONN_STATS', 1, 8, 1, 1, 'TCP connection stats', 16, '', '', 0, 0, 60, 60, 1, 0, 7200, 0, 0, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, NULL, 0, 0, 0, 0, 0, 0, '', 1, '', NULL, 0, 0, '2015-01-15 19:04:31', '2015-01-15 19:04:31');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `servicetemplates_to_servicetemplategroups`
--

CREATE TABLE IF NOT EXISTS `servicetemplates_to_servicetemplategroups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `servicetemplate_id` int(11) NOT NULL,
  `servicetemplategroup_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `servicetemplategroup_id` (`servicetemplategroup_id`),
  KEY `servicetemplate_id` (`servicetemplate_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `systemdowntimes`
--

CREATE TABLE IF NOT EXISTS `systemdowntimes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `objecttype_id` int(11) DEFAULT NULL,
  `object_id` int(11) DEFAULT NULL,
  `downtimetype_id` int(11) DEFAULT '0',
  `weekdays` varchar(255) COLLATE utf8_swedish_ci DEFAULT NULL,
  `day_of_month` varchar(255) COLLATE utf8_swedish_ci DEFAULT NULL,
  `from_time` text COLLATE utf8_swedish_ci NOT NULL,
  `to_time` text COLLATE utf8_swedish_ci NOT NULL,
  `comment` varchar(255) COLLATE utf8_swedish_ci DEFAULT NULL,
  `author` varchar(255) COLLATE utf8_swedish_ci DEFAULT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `systemfailures`
--

CREATE TABLE IF NOT EXISTS `systemfailures` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `start_time` datetime NOT NULL,
  `end_time` datetime NOT NULL,
  `comment` varchar(255) COLLATE utf8_swedish_ci NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `systemsettings`
--

CREATE TABLE IF NOT EXISTS `systemsettings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `key` varchar(255) COLLATE utf8_swedish_ci NOT NULL,
  `value` varchar(255) COLLATE utf8_swedish_ci NOT NULL,
  `info` varchar(1500) COLLATE utf8_swedish_ci NOT NULL,
  `section` varchar(255) COLLATE utf8_swedish_ci NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;

--
-- Daten für Tabelle `systemsettings`
--

INSERT INTO `systemsettings` (`id`, `key`, `value`, `info`, `section`, `created`, `modified`) VALUES
(1, 'SUDO_SERVER.SOCKET', '/usr/share/openitcockpit/app/run/', 'Path where the sudo server will try to create its socket file', 'SUDO_SERVER', '0000-00-00 00:00:00', '2015-01-16 00:41:41'),
(2, 'SUDO_SERVER.SOCKET_NAME', 'sudo.sock', 'Sudoservers socket name', 'SUDO_SERVER', '0000-00-00 00:00:00', '2015-01-16 00:41:41'),
(3, 'SUDO_SERVER.SOCKETPERMISSIONS', '49588', 'Permissions of the socket file', 'SUDO_SERVER', '0000-00-00 00:00:00', '2015-01-16 00:41:41'),
(4, 'WEBSERVER.USER', 'www-data', 'Username of the webserver', 'WEBSERVER', '0000-00-00 00:00:00', '2015-01-16 00:41:41'),
(5, 'WEBSERVER.GROUP', 'www-data', 'Usergroup of the webserver', 'WEBSERVER', '0000-00-00 00:00:00', '2015-01-16 00:41:41'),
(6, 'SUDO_SERVER.FOLDERPERMISSIONS', '16877', 'Permissions of the socket folder', 'SUDO_SERVER', '0000-00-00 00:00:00', '2015-01-16 00:41:41'),
(20, 'SUDO_SERVER.API_KEY', '1fea123e07f730f76e661bced33a94152378611e', 'API key for the sudoserver socket API', 'SUDO_SERVER', '0000-00-00 00:00:00', '2015-01-16 00:41:41'),
(21, 'MONITORING.USER', 'nagios', 'The user of your monitoring system', 'MONITORING', '0000-00-00 00:00:00', '2015-01-16 00:41:41'),
(22, 'MONITORING.GROUP', 'nagios', 'The group of your monitoring system', 'MONITORING', '0000-00-00 00:00:00', '2015-01-16 00:41:41'),
(23, 'MONITORING.FROM_ADDRESS', 'openitcockpit@example.org', 'Sender mail address for notifications', 'MONITORING', '0000-00-00 00:00:00', '2015-01-16 00:41:41'),
(24, 'MONITORING.FROM_NAME', 'openITCOCKPIT Notification', 'The name we should display in your mail client', 'MONITORING', '0000-00-00 00:00:00', '2015-01-16 00:41:41'),
(25, 'MONITORING.MESSAGE_HEADER', '**** openITCOCKPIT notification by it-novum GmbH ****', 'The header in the plain text mail', 'MONITORING', '0000-00-00 00:00:00', '2015-01-16 00:41:41'),
(26, 'MONITORING.CMD', '/opt/openitc/nagios/var/rw/nagios.cmd', 'The command pipe for your monitoring system', 'MONITORING', '0000-00-00 00:00:00', '2015-01-16 00:41:41'),
(27, 'CRONJOB.RECURRING_DOWNTIME', '10', 'Time in minutes the cron will check for recurring downtimes', 'CRONJOB', '0000-00-00 00:00:00', '2015-01-02 10:59:39'),
(28, 'SYSTEM.ADDRESS', '192.168.0.1', 'The IP address or FQDN of the system', 'SYSTEM', '0000-00-00 00:00:00', '2015-01-16 00:41:41'),
(29, 'MONITORING.HOST.INITSTATE', 'u', 'Host initial state [o,d,u]', 'MONITORING', '0000-00-00 00:00:00', '2015-01-16 00:41:41'),
(30, 'MONITORING.SERVICE.INITSTATE', 'u', 'Service initial state [o,w,u,c]', 'MONITORING', '0000-00-00 00:00:00', '2015-01-16 00:41:41'),
(31, 'MONITORING.RESTART', 'service nagios restart', 'Command to restart your monitoring software', 'MONITORING', '0000-00-00 00:00:00', '2015-01-16 00:41:41'),
(32, 'MONITORING.RELOAD', 'service nagios reload', 'Command to reload your monitoring software', 'MONITORING', '0000-00-00 00:00:00', '2015-01-16 00:41:41'),
(33, 'MONITORING.STOP', 'service nagios stop', 'Command to stop your monitoring software', 'MONITORING', '0000-00-00 00:00:00', '2015-01-16 00:41:41'),
(34, 'MONITORING.START', 'service nagios start', 'Command to start your monitoring software', 'MONITORING', '0000-00-00 00:00:00', '2015-01-16 00:41:41'),
(35, 'FRONTEND.SYSTEMNAME', 'openITCOCKPIT', 'The name of your system', 'FRONTEND', '0000-00-00 00:00:00', '2015-01-16 00:41:41'),
(36, 'SUDO_SERVER.WORKERSOCKET_NAME', 'worker.sock', 'Sudoservers worker socket name', 'SUDO_SERVER', '0000-00-00 00:00:00', '2015-01-16 00:41:41'),
(37, 'SUDO_SERVER.WORKERSOCKETPERMISSIONS', '49588', 'Permissions of the worker socket file', 'SUDO_SERVER', '0000-00-00 00:00:00', '2015-01-16 00:41:41'),
(38, 'CHECK_MK.BIN', '/opt/openitc/nagios/3rd/check_mk/bin/check_mk', 'Path to check_mk binary', 'CHECK_MK', '0000-00-00 00:00:00', '2015-01-16 00:41:41'),
(39, 'SUDO_SERVER.RESPONSESOCKET_NAME', 'response.sock', 'Sudoservers worker socket name', 'SUDO_SERVER', '0000-00-00 00:00:00', '2015-01-16 00:41:41'),
(40, 'SUDO_SERVER.RESPONSESOCKETPERMISSIONS', '49588', 'Permissions of the worker socket file', 'SUDO_SERVER', '0000-00-00 00:00:00', '2015-01-16 00:41:41'),
(41, 'MONITORING.CORECONFIG', '/etc/openitcockpit/nagios.cfg', 'Path to monitoring core configuration file', 'MONITORING', '0000-00-00 00:00:00', '2015-01-16 00:41:41'),
(42, 'CHECK_MK.MATCH', '(perl|dsmc|java|ksh|VBoxHeadless)', 'These are the services that should not be compressed by check_mk as regular expression', 'CHECK_MK', '0000-00-00 00:00:00', '2015-01-16 00:41:41'),
(43, 'CHECK_MK.ETC', '/opt/openitc/nagios/3rd/check_mk/etc/', 'Path to Check_MK confi files', 'CHECK_MK', '0000-00-00 00:00:00', '2015-01-16 00:41:41'),
(44, 'CHECK_MK.VAR', '/opt/openitc/nagios/3rd/check_mk/var/', 'Path to Check_MK variable files', 'CHECK_MK', '0000-00-00 00:00:00', '2015-01-16 00:41:41'),
(45, 'CHECK_MK.ACTIVE_CHECK', 'CHECK_MK_ACTIVE', 'The name of the check_mk active check service template', 'CHECK_MK', '0000-00-00 00:00:00', '2015-01-16 00:41:41'),
(46, 'FRONTEND.MASTER_INSTANCE', 'Mastersystem', 'The name of your openITCOCKPIT main instance', 'FRONTEND', '0000-00-00 00:00:00', '2015-01-16 00:41:41'),
(47, 'FRONTEND.AUTH_METHOD', 'session', 'The authentication method that shoud be used for login', 'FRONTEND', '0000-00-00 00:00:00', '2015-01-16 00:41:41'),
(48, 'FRONTEND.LDAP.ADDRESS', '192.168.1.10', 'The address or hostname of your LDAP server', 'FRONTEND', '0000-00-00 00:00:00', '2015-01-15 19:20:23'),
(49, 'FRONTEND.LDAP.PORT', '389', 'The port where your LDAP server is listen to. Notice: If you want to use TLS the default port 389 shoud work as well! (SSL default Port is 636)', 'FRONTEND', '0000-00-00 00:00:00', '2015-01-15 19:20:23'),
(50, 'FRONTEND.LDAP.BASEDN', 'DC=example,DC=org', 'Your BASEDN', 'FRONTEND', '0000-00-00 00:00:00', '2015-01-15 19:20:23'),
(51, 'FRONTEND.LDAP.USERNAME', 'administrator', 'The username that the system will use to connect to your LDAP server', 'FRONTEND', '0000-00-00 00:00:00', '2015-01-15 19:20:23'),
(52, 'FRONTEND.LDAP.PASSWORD', 'Testing123!', 'The password that the system will use to connect to your LDAP server', 'FRONTEND', '0000-00-00 00:00:00', '2015-01-15 19:20:23'),
(53, 'FRONTEND.LDAP.SUFFIX', '@example.org', 'The Suffix of your domain', 'FRONTEND', '0000-00-00 00:00:00', '2015-01-15 19:20:23'),
(54, 'FRONTEND.LDAP.USE_TLS', '1', 'If PHP shoud upgrade the security of a plain connection to a TLS encrypted connection', 'FRONTEND', '0000-00-00 00:00:00', '2015-01-16 00:41:41'),
(55, 'CRONJOB.CLEANUP_DATABASE', '1440', 'Time in minutes the cron will check for partitions in database and drop old partitions', 'CRONJOB', '0000-00-00 00:00:00', '2015-01-02 10:59:39'),
(56, 'ARCHIVE.AGE.SERVICECHECKS', '2', 'Time in weeks how long service check results will be stored', 'ARCHIVE', '0000-00-00 00:00:00', '2015-01-16 00:41:41'),
(57, 'ARCHIVE.AGE.HOSTCHECKS', '2', 'Time in weeks how long host check results will be stored', 'ARCHIVE', '0000-00-00 00:00:00', '2015-01-16 00:41:41'),
(58, 'ARCHIVE.AGE.STATEHISTORIES', '53', 'Time in weeks how long state change events will be stored', 'ARCHIVE', '0000-00-00 00:00:00', '2015-01-16 00:41:41'),
(59, 'ARCHIVE.AGE.LOGENTRIES', '2', 'Time in weeks how long logentries will be stored', 'ARCHIVE', '0000-00-00 00:00:00', '2015-01-16 00:41:41'),
(60, 'MONITORING.STATUS_DAT', '/opt/openitc/nagios/var/status.dat', 'Path to the status.dat of the monitoring system', 'MONITORING', '0000-00-00 00:00:00', '2015-01-16 00:41:41'),
(61, 'ARCHIVE.AGE.NOTIFICATIONS', '2', 'Time in weeks how long notifications will be stored (keep eq to CONTACTNOTIFICATIONS AND CONTACTNOTIFICATIONMETHODS)', 'ARCHIVE', '2014-12-23 10:32:55', '2015-01-16 00:41:41'),
(62, 'ARCHIVE.AGE.CONTACTNOTIFICATIONS', '2', 'Time in weeks how long contactnotifications will be stored (keep eq to NOTIFICATIONS AND CONTACTNOTIFICATIONMETHODS)', 'ARCHIVE', '0000-00-00 00:00:00', '2015-01-16 00:41:41'),
(63, 'ARCHIVE.AGE.CONTACTNOTIFICATIONMETHODS', '2', 'Time in weeks how long contactnotificationmethods will be stored (keep eq to NOTIFICATIONS AND CONTACTNOTIFICATIONS)', 'ARCHIVE', '0000-00-00 00:00:00', '2015-01-16 00:41:41'),
(64, 'CRONJOB.CLENUP_TEMPFILES', '10', 'Deletes tmp files', 'CRONJOB', '2014-12-23 11:45:31', '2015-01-02 10:59:39'),
(65, 'MONITORING.FRESHNESS_THRESHOLD_ADDITION', '300', 'Value in seconds that get added to the service check interval for passive services, before the monitoring system will fire up the freshness check', 'MONITORING', '2014-12-23 11:45:31', '2015-01-02 10:59:39'),
(66, 'MONITORING.AFTER_EXPORT', '#echo 1', 'A command that get executed on each export (Notice: this command runs as root, so be careful)', 'MONITORING', '2014-12-23 11:45:31', '2015-01-02 10:59:39');


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `tenants`
--

CREATE TABLE IF NOT EXISTS `tenants` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `container_id` int(11) NOT NULL,
  `description` varchar(255) COLLATE utf8_swedish_ci DEFAULT NULL,
  `is_active` int(1) NOT NULL,
  `number_users` int(6) NOT NULL,
  `max_users` int(6) NOT NULL,
  `number_hosts` int(6) NOT NULL,
  `max_hosts` int(6) NOT NULL,
  `number_services` int(6) NOT NULL,
  `max_services` int(6) NOT NULL,
  `firstname` varchar(255) COLLATE utf8_swedish_ci DEFAULT NULL,
  `lastname` varchar(255) COLLATE utf8_swedish_ci DEFAULT NULL,
  `street` varchar(255) COLLATE utf8_swedish_ci DEFAULT NULL,
  `zipcode` int(6) DEFAULT NULL,
  `city` varchar(255) COLLATE utf8_swedish_ci DEFAULT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;

-- --------------------------------------------------------


--
-- Tabellenstruktur für Tabelle `timeperiods`
--

CREATE TABLE IF NOT EXISTS `timeperiods` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uuid` varchar(37) COLLATE utf8_swedish_ci NOT NULL,
  `container_id` int(6) NOT NULL,
  `name` varchar(255) COLLATE utf8_swedish_ci NOT NULL,
  `description` varchar(255) COLLATE utf8_swedish_ci DEFAULT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uuid` (`uuid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;

--
-- Daten für Tabelle `timeperiods`
--

INSERT INTO `timeperiods` (`id`, `uuid`, `container_id`, `name`, `description`, `created`, `modified`) VALUES
(1, '41012866-6114-4853-9caf-6ffd19954e50', 1, '24x7', '24x7', '2015-01-05 15:11:46', '2015-01-05 15:11:46'),
(2, 'c5251a5e-37f1-4841-b0bd-f801ee8969d4', 1, 'none', 'none', '2015-01-05 15:11:56', '2015-01-05 15:11:56');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `timeperiod_timeranges`
--

CREATE TABLE IF NOT EXISTS `timeperiod_timeranges` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `timeperiod_id` int(11) NOT NULL DEFAULT '0',
  `day` int(6) NOT NULL DEFAULT '0',
  `start` varchar(5) COLLATE utf8_swedish_ci NOT NULL,
  `end` varchar(5) COLLATE utf8_swedish_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;

--
-- Daten für Tabelle `timeperiod_timeranges`
--

INSERT INTO `timeperiod_timeranges` (`id`, `timeperiod_id`, `day`, `start`, `end`) VALUES
(1, 1, 1, '00:00', '24:00'),
(2, 1, 2, '00:00', '24:00'),
(3, 1, 3, '00:00', '24:00'),
(4, 1, 4, '00:00', '24:00'),
(5, 1, 5, '00:00', '24:00'),
(6, 1, 6, '00:00', '24:00'),
(7, 1, 7, '00:00', '24:00');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `status` int(3) unsigned NOT NULL DEFAULT '1',
  `role` varchar(45) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(45) NOT NULL,
  `firstname` varchar(100) NOT NULL,
  `lastname` varchar(100) NOT NULL,
  `position` varchar(100) DEFAULT NULL,
  `company` varchar(100) DEFAULT NULL,
  `phone` varchar(100) DEFAULT NULL,
  `linkedin_id` varchar(45) DEFAULT NULL,
  `timezone` varchar(100) DEFAULT 'Europe/Berlin',
  `dateformat` varchar(100) DEFAULT '%H:%M:%S - %d.%m.%Y',
  `image` varchar(100) DEFAULT NULL,
  `onetimetoken` varchar(100) DEFAULT NULL,
  `samaccountname` varchar(128) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `users_to_autoreports`
--

CREATE TABLE IF NOT EXISTS `users_to_autoreports` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `autoreport_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `users_to_containers`
--

CREATE TABLE IF NOT EXISTS `users_to_containers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `container_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `container_id` (`container_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;

--
-- Tabellenstruktur für Tabelle `acos`
--

CREATE TABLE IF NOT EXISTS `acos` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `parent_id` int(10) DEFAULT NULL,
  `model` varchar(255) DEFAULT NULL,
  `foreign_key` int(10) DEFAULT NULL,
  `alias` varchar(255) DEFAULT NULL,
  `lft` int(10) DEFAULT NULL,
  `rght` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=925 ;

--
-- Daten für Tabelle `acos`
--

INSERT INTO `acos` (`id`, `parent_id`, `model`, `foreign_key`, `alias`, `lft`, `rght`) VALUES
(1, NULL, NULL, NULL, 'controllers', 1, 1758),
(2, 1, NULL, NULL, 'Acknowledgements', 2, 21),
(3, 2, NULL, NULL, 'service', 3, 4),
(4, 2, NULL, NULL, 'host', 5, 6),
(5, 2, NULL, NULL, 'isAuthorized', 7, 8),
(6, 2, NULL, NULL, 'flashBack', 9, 10),
(7, 2, NULL, NULL, 'setFlash', 11, 12),
(8, 2, NULL, NULL, 'serviceResponse', 13, 14),
(9, 2, NULL, NULL, 'getNamedParameter', 15, 16),
(10, 2, NULL, NULL, 'allowedByContainerId', 17, 18),
(11, 2, NULL, NULL, 'render403', 19, 20),
(12, 1, NULL, NULL, 'Administrators', 22, 43),
(13, 12, NULL, NULL, 'index', 23, 24),
(14, 12, NULL, NULL, 'debug', 25, 26),
(15, 12, NULL, NULL, 'isAuthorized', 27, 28),
(16, 12, NULL, NULL, 'flashBack', 29, 30),
(17, 12, NULL, NULL, 'setFlash', 31, 32),
(18, 12, NULL, NULL, 'serviceResponse', 33, 34),
(19, 12, NULL, NULL, 'getNamedParameter', 35, 36),
(20, 12, NULL, NULL, 'allowedByContainerId', 37, 38),
(21, 12, NULL, NULL, 'render403', 39, 40),
(22, 1, NULL, NULL, 'Automaps', 44, 71),
(23, 22, NULL, NULL, 'index', 45, 46),
(24, 22, NULL, NULL, 'add', 47, 48),
(25, 22, NULL, NULL, 'edit', 49, 50),
(26, 22, NULL, NULL, 'view', 51, 52),
(27, 22, NULL, NULL, 'loadServiceDetails', 53, 54),
(28, 22, NULL, NULL, 'delete', 55, 56),
(29, 22, NULL, NULL, 'isAuthorized', 57, 58),
(30, 22, NULL, NULL, 'flashBack', 59, 60),
(31, 22, NULL, NULL, 'setFlash', 61, 62),
(32, 22, NULL, NULL, 'serviceResponse', 63, 64),
(33, 22, NULL, NULL, 'getNamedParameter', 65, 66),
(34, 22, NULL, NULL, 'allowedByContainerId', 67, 68),
(35, 22, NULL, NULL, 'render403', 69, 70),
(36, 1, NULL, NULL, 'Browsers', 72, 91),
(37, 36, NULL, NULL, 'index', 73, 74),
(38, 36, NULL, NULL, 'tenantBrowser', 75, 76),
(42, 36, NULL, NULL, 'isAuthorized', 77, 78),
(43, 36, NULL, NULL, 'flashBack', 79, 80),
(44, 36, NULL, NULL, 'setFlash', 81, 82),
(45, 36, NULL, NULL, 'serviceResponse', 83, 84),
(46, 36, NULL, NULL, 'getNamedParameter', 85, 86),
(47, 36, NULL, NULL, 'allowedByContainerId', 87, 88),
(48, 36, NULL, NULL, 'render403', 89, 90),
(49, 1, NULL, NULL, 'Calendars', 92, 119),
(50, 49, NULL, NULL, 'index', 93, 94),
(51, 49, NULL, NULL, 'add', 95, 96),
(52, 49, NULL, NULL, 'edit', 97, 98),
(53, 49, NULL, NULL, 'delete', 99, 100),
(54, 49, NULL, NULL, 'loadHolidays', 101, 102),
(55, 49, NULL, NULL, 'mass_delete', 103, 104),
(56, 49, NULL, NULL, 'isAuthorized', 105, 106),
(57, 49, NULL, NULL, 'flashBack', 107, 108),
(58, 49, NULL, NULL, 'setFlash', 109, 110),
(59, 49, NULL, NULL, 'serviceResponse', 111, 112),
(60, 49, NULL, NULL, 'getNamedParameter', 113, 114),
(61, 49, NULL, NULL, 'allowedByContainerId', 115, 116),
(62, 49, NULL, NULL, 'render403', 117, 118),
(63, 1, NULL, NULL, 'Category', 120, 137),
(64, 63, NULL, NULL, 'index', 121, 122),
(65, 63, NULL, NULL, 'isAuthorized', 123, 124),
(66, 63, NULL, NULL, 'flashBack', 125, 126),
(67, 63, NULL, NULL, 'setFlash', 127, 128),
(68, 63, NULL, NULL, 'serviceResponse', 129, 130),
(69, 63, NULL, NULL, 'getNamedParameter', 131, 132),
(70, 63, NULL, NULL, 'allowedByContainerId', 133, 134),
(71, 63, NULL, NULL, 'render403', 135, 136),
(72, 1, NULL, NULL, 'Changelogs', 138, 155),
(73, 72, NULL, NULL, 'index', 139, 140),
(74, 72, NULL, NULL, 'isAuthorized', 141, 142),
(75, 72, NULL, NULL, 'flashBack', 143, 144),
(76, 72, NULL, NULL, 'setFlash', 145, 146),
(77, 72, NULL, NULL, 'serviceResponse', 147, 148),
(78, 72, NULL, NULL, 'getNamedParameter', 149, 150),
(79, 72, NULL, NULL, 'allowedByContainerId', 151, 152),
(80, 72, NULL, NULL, 'render403', 153, 154),
(81, 1, NULL, NULL, 'Commands', 156, 193),
(82, 81, NULL, NULL, 'index', 157, 158),
(83, 81, NULL, NULL, 'hostchecks', 159, 160),
(84, 81, NULL, NULL, 'notifications', 161, 162),
(85, 81, NULL, NULL, 'handler', 163, 164),
(86, 81, NULL, NULL, 'add', 165, 166),
(87, 81, NULL, NULL, 'edit', 167, 168),
(88, 81, NULL, NULL, 'delete', 169, 170),
(89, 81, NULL, NULL, 'mass_delete', 171, 172),
(90, 81, NULL, NULL, 'addCommandArg', 173, 174),
(91, 81, NULL, NULL, 'loadMacros', 175, 176),
(92, 81, NULL, NULL, 'terminal', 177, 178),
(93, 81, NULL, NULL, 'isAuthorized', 179, 180),
(94, 81, NULL, NULL, 'flashBack', 181, 182),
(95, 81, NULL, NULL, 'setFlash', 183, 184),
(96, 81, NULL, NULL, 'serviceResponse', 185, 186),
(97, 81, NULL, NULL, 'getNamedParameter', 187, 188),
(98, 81, NULL, NULL, 'allowedByContainerId', 189, 190),
(99, 81, NULL, NULL, 'render403', 191, 192),
(100, 1, NULL, NULL, 'Contactgroups', 194, 221),
(101, 100, NULL, NULL, 'index', 195, 196),
(102, 100, NULL, NULL, 'edit', 197, 198),
(103, 100, NULL, NULL, 'add', 199, 200),
(104, 100, NULL, NULL, 'loadContacts', 201, 202),
(105, 100, NULL, NULL, 'delete', 203, 204),
(106, 100, NULL, NULL, 'mass_delete', 205, 206),
(107, 100, NULL, NULL, 'isAuthorized', 207, 208),
(108, 100, NULL, NULL, 'flashBack', 209, 210),
(109, 100, NULL, NULL, 'setFlash', 211, 212),
(110, 100, NULL, NULL, 'serviceResponse', 213, 214),
(111, 100, NULL, NULL, 'getNamedParameter', 215, 216),
(112, 100, NULL, NULL, 'allowedByContainerId', 217, 218),
(113, 100, NULL, NULL, 'render403', 219, 220),
(114, 1, NULL, NULL, 'Contacts', 222, 251),
(115, 114, NULL, NULL, 'index', 223, 224),
(116, 114, NULL, NULL, 'edit', 225, 226),
(117, 114, NULL, NULL, 'add', 227, 228),
(118, 114, NULL, NULL, 'delete', 229, 230),
(119, 114, NULL, NULL, 'mass_delete', 231, 232),
(120, 114, NULL, NULL, 'loadTimeperiods', 233, 234),
(121, 114, NULL, NULL, 'isAuthorized', 235, 236),
(122, 114, NULL, NULL, 'flashBack', 237, 238),
(123, 114, NULL, NULL, 'setFlash', 239, 240),
(124, 114, NULL, NULL, 'serviceResponse', 241, 242),
(125, 114, NULL, NULL, 'getNamedParameter', 243, 244),
(126, 114, NULL, NULL, 'allowedByContainerId', 245, 246),
(127, 114, NULL, NULL, 'render403', 247, 248),
(128, 1, NULL, NULL, 'Containers', 252, 277),
(129, 128, NULL, NULL, 'index', 253, 254),
(130, 128, NULL, NULL, 'add', 255, 256),
(131, 128, NULL, NULL, 'byTenant', 257, 258),
(132, 128, NULL, NULL, 'byTenantForSelect', 259, 260),
(133, 128, NULL, NULL, 'delete', 261, 262),
(134, 128, NULL, NULL, 'isAuthorized', 263, 264),
(135, 128, NULL, NULL, 'flashBack', 265, 266),
(136, 128, NULL, NULL, 'setFlash', 267, 268),
(137, 128, NULL, NULL, 'serviceResponse', 269, 270),
(138, 128, NULL, NULL, 'getNamedParameter', 271, 272),
(139, 128, NULL, NULL, 'allowedByContainerId', 273, 274),
(140, 128, NULL, NULL, 'render403', 275, 276),
(141, 1, NULL, NULL, 'Cronjobs', 278, 303),
(142, 141, NULL, NULL, 'index', 279, 280),
(143, 141, NULL, NULL, 'add', 281, 282),
(144, 141, NULL, NULL, 'edit', 283, 284),
(145, 141, NULL, NULL, 'delete', 285, 286),
(146, 141, NULL, NULL, 'loadTasksByPlugin', 287, 288),
(147, 141, NULL, NULL, 'isAuthorized', 289, 290),
(148, 141, NULL, NULL, 'flashBack', 291, 292),
(149, 141, NULL, NULL, 'setFlash', 293, 294),
(150, 141, NULL, NULL, 'serviceResponse', 295, 296),
(151, 141, NULL, NULL, 'getNamedParameter', 297, 298),
(152, 141, NULL, NULL, 'allowedByContainerId', 299, 300),
(153, 141, NULL, NULL, 'render403', 301, 302),
(154, 1, NULL, NULL, 'Currentstatereports', 304, 323),
(155, 154, NULL, NULL, 'index', 305, 306),
(156, 154, NULL, NULL, 'createPdfReport', 307, 308),
(157, 154, NULL, NULL, 'isAuthorized', 309, 310),
(158, 154, NULL, NULL, 'flashBack', 311, 312),
(159, 154, NULL, NULL, 'setFlash', 313, 314),
(160, 154, NULL, NULL, 'serviceResponse', 315, 316),
(161, 154, NULL, NULL, 'getNamedParameter', 317, 318),
(162, 154, NULL, NULL, 'allowedByContainerId', 319, 320),
(163, 154, NULL, NULL, 'render403', 321, 322),
(164, 1, NULL, NULL, 'DeletedHosts', 324, 341),
(165, 164, NULL, NULL, 'index', 325, 326),
(166, 164, NULL, NULL, 'isAuthorized', 327, 328),
(167, 164, NULL, NULL, 'flashBack', 329, 330),
(168, 164, NULL, NULL, 'setFlash', 331, 332),
(169, 164, NULL, NULL, 'serviceResponse', 333, 334),
(170, 164, NULL, NULL, 'getNamedParameter', 335, 336),
(171, 164, NULL, NULL, 'allowedByContainerId', 337, 338),
(172, 164, NULL, NULL, 'render403', 339, 340),
(173, 1, NULL, NULL, 'Devicegroups', 342, 365),
(174, 173, NULL, NULL, 'index', 343, 344),
(175, 173, NULL, NULL, 'add', 345, 346),
(176, 173, NULL, NULL, 'edit', 347, 348),
(177, 173, NULL, NULL, 'delete', 349, 350),
(178, 173, NULL, NULL, 'isAuthorized', 351, 352),
(179, 173, NULL, NULL, 'flashBack', 353, 354),
(180, 173, NULL, NULL, 'setFlash', 355, 356),
(181, 173, NULL, NULL, 'serviceResponse', 357, 358),
(182, 173, NULL, NULL, 'getNamedParameter', 359, 360),
(183, 173, NULL, NULL, 'allowedByContainerId', 361, 362),
(184, 173, NULL, NULL, 'render403', 363, 364),
(185, 1, NULL, NULL, 'Documentations', 366, 387),
(186, 185, NULL, NULL, 'view', 367, 368),
(187, 185, NULL, NULL, 'index', 369, 370),
(188, 185, NULL, NULL, 'wiki', 371, 372),
(189, 185, NULL, NULL, 'isAuthorized', 373, 374),
(190, 185, NULL, NULL, 'flashBack', 375, 376),
(191, 185, NULL, NULL, 'setFlash', 377, 378),
(192, 185, NULL, NULL, 'serviceResponse', 379, 380),
(193, 185, NULL, NULL, 'getNamedParameter', 381, 382),
(194, 185, NULL, NULL, 'allowedByContainerId', 383, 384),
(195, 185, NULL, NULL, 'render403', 385, 386),
(196, 1, NULL, NULL, 'Downtimereports', 388, 407),
(197, 196, NULL, NULL, 'index', 389, 390),
(198, 196, NULL, NULL, 'createPdfReport', 391, 392),
(199, 196, NULL, NULL, 'isAuthorized', 393, 394),
(200, 196, NULL, NULL, 'flashBack', 395, 396),
(201, 196, NULL, NULL, 'setFlash', 397, 398),
(202, 196, NULL, NULL, 'serviceResponse', 399, 400),
(203, 196, NULL, NULL, 'getNamedParameter', 401, 402),
(204, 196, NULL, NULL, 'allowedByContainerId', 403, 404),
(205, 196, NULL, NULL, 'render403', 405, 406),
(206, 1, NULL, NULL, 'Downtimes', 408, 431),
(207, 206, NULL, NULL, 'host', 409, 410),
(208, 206, NULL, NULL, 'service', 411, 412),
(209, 206, NULL, NULL, 'index', 413, 414),
(210, 206, NULL, NULL, 'validateDowntimeInputFromBrowser', 415, 416),
(211, 206, NULL, NULL, 'isAuthorized', 417, 418),
(212, 206, NULL, NULL, 'flashBack', 419, 420),
(213, 206, NULL, NULL, 'setFlash', 421, 422),
(214, 206, NULL, NULL, 'serviceResponse', 423, 424),
(215, 206, NULL, NULL, 'getNamedParameter', 425, 426),
(216, 206, NULL, NULL, 'allowedByContainerId', 427, 428),
(217, 206, NULL, NULL, 'render403', 429, 430),
(218, 1, NULL, NULL, 'Exports', 432, 455),
(219, 218, NULL, NULL, 'index', 433, 434),
(220, 218, NULL, NULL, 'isAuthorized', 435, 436),
(221, 218, NULL, NULL, 'flashBack', 437, 438),
(222, 218, NULL, NULL, 'setFlash', 439, 440),
(223, 218, NULL, NULL, 'serviceResponse', 441, 442),
(224, 218, NULL, NULL, 'getNamedParameter', 443, 444),
(225, 218, NULL, NULL, 'allowedByContainerId', 445, 446),
(226, 218, NULL, NULL, 'render403', 447, 448),
(227, 1, NULL, NULL, 'Forward', 456, 473),
(228, 227, NULL, NULL, 'index', 457, 458),
(229, 227, NULL, NULL, 'isAuthorized', 459, 460),
(230, 227, NULL, NULL, 'flashBack', 461, 462),
(231, 227, NULL, NULL, 'setFlash', 463, 464),
(232, 227, NULL, NULL, 'serviceResponse', 465, 466),
(233, 227, NULL, NULL, 'getNamedParameter', 467, 468),
(234, 227, NULL, NULL, 'allowedByContainerId', 469, 470),
(235, 227, NULL, NULL, 'render403', 471, 472),
(236, 1, NULL, NULL, 'GraphCollections', 474, 499),
(237, 236, NULL, NULL, 'index', 475, 476),
(238, 236, NULL, NULL, 'edit', 477, 478),
(239, 236, NULL, NULL, 'display', 479, 480),
(240, 236, NULL, NULL, 'mass_delete', 481, 482),
(241, 236, NULL, NULL, 'loadCollectionGraphData', 483, 484),
(242, 236, NULL, NULL, 'isAuthorized', 485, 486),
(243, 236, NULL, NULL, 'flashBack', 487, 488),
(244, 236, NULL, NULL, 'setFlash', 489, 490),
(245, 236, NULL, NULL, 'serviceResponse', 491, 492),
(246, 236, NULL, NULL, 'getNamedParameter', 493, 494),
(247, 236, NULL, NULL, 'allowedByContainerId', 495, 496),
(248, 236, NULL, NULL, 'render403', 497, 498),
(249, 1, NULL, NULL, 'Graphgenerators', 500, 533),
(250, 249, NULL, NULL, 'index', 501, 502),
(251, 249, NULL, NULL, 'listing', 503, 504),
(252, 249, NULL, NULL, 'mass_delete', 505, 506),
(253, 249, NULL, NULL, 'saveGraphTemplate', 507, 508),
(254, 249, NULL, NULL, 'loadGraphTemplate', 509, 510),
(255, 249, NULL, NULL, 'loadServicesByHostId', 511, 512),
(256, 249, NULL, NULL, 'loadPerfDataStructures', 513, 514),
(257, 249, NULL, NULL, 'loadServiceruleFromService', 515, 516),
(258, 249, NULL, NULL, 'fetchGraphData', 517, 518),
(259, 249, NULL, NULL, 'isAuthorized', 519, 520),
(260, 249, NULL, NULL, 'flashBack', 521, 522),
(261, 249, NULL, NULL, 'setFlash', 523, 524),
(262, 249, NULL, NULL, 'serviceResponse', 525, 526),
(263, 249, NULL, NULL, 'getNamedParameter', 527, 528),
(264, 249, NULL, NULL, 'allowedByContainerId', 529, 530),
(265, 249, NULL, NULL, 'render403', 531, 532),
(266, 1, NULL, NULL, 'Hostchecks', 534, 551),
(267, 266, NULL, NULL, 'index', 535, 536),
(268, 266, NULL, NULL, 'isAuthorized', 537, 538),
(269, 266, NULL, NULL, 'flashBack', 539, 540),
(270, 266, NULL, NULL, 'setFlash', 541, 542),
(271, 266, NULL, NULL, 'serviceResponse', 543, 544),
(272, 266, NULL, NULL, 'getNamedParameter', 545, 546),
(273, 266, NULL, NULL, 'allowedByContainerId', 547, 548),
(274, 266, NULL, NULL, 'render403', 549, 550),
(275, 1, NULL, NULL, 'Hostdependencies', 552, 577),
(276, 275, NULL, NULL, 'index', 553, 554),
(277, 275, NULL, NULL, 'edit', 555, 556),
(278, 275, NULL, NULL, 'add', 557, 558),
(279, 275, NULL, NULL, 'delete', 559, 560),
(280, 275, NULL, NULL, 'loadElementsByContainerId', 561, 562),
(281, 275, NULL, NULL, 'isAuthorized', 563, 564),
(282, 275, NULL, NULL, 'flashBack', 565, 566),
(283, 275, NULL, NULL, 'setFlash', 567, 568),
(284, 275, NULL, NULL, 'serviceResponse', 569, 570),
(285, 275, NULL, NULL, 'getNamedParameter', 571, 572),
(286, 275, NULL, NULL, 'allowedByContainerId', 573, 574),
(287, 275, NULL, NULL, 'render403', 575, 576),
(288, 1, NULL, NULL, 'Hostescalations', 578, 603),
(289, 288, NULL, NULL, 'index', 579, 580),
(290, 288, NULL, NULL, 'edit', 581, 582),
(291, 288, NULL, NULL, 'add', 583, 584),
(292, 288, NULL, NULL, 'delete', 585, 586),
(293, 288, NULL, NULL, 'loadElementsByContainerId', 587, 588),
(294, 288, NULL, NULL, 'isAuthorized', 589, 590),
(295, 288, NULL, NULL, 'flashBack', 591, 592),
(296, 288, NULL, NULL, 'setFlash', 593, 594),
(297, 288, NULL, NULL, 'serviceResponse', 595, 596),
(298, 288, NULL, NULL, 'getNamedParameter', 597, 598),
(299, 288, NULL, NULL, 'allowedByContainerId', 599, 600),
(300, 288, NULL, NULL, 'render403', 601, 602),
(301, 1, NULL, NULL, 'Hostgroups', 604, 637),
(302, 301, NULL, NULL, 'index', 605, 606),
(303, 301, NULL, NULL, 'extended', 607, 608),
(304, 301, NULL, NULL, 'edit', 609, 610),
(305, 301, NULL, NULL, 'add', 611, 612),
(306, 301, NULL, NULL, 'loadHosts', 613, 614),
(307, 301, NULL, NULL, 'delete', 615, 616),
(308, 301, NULL, NULL, 'mass_add', 617, 618),
(309, 301, NULL, NULL, 'mass_delete', 619, 620),
(310, 301, NULL, NULL, 'listToPdf', 621, 622),
(311, 301, NULL, NULL, 'isAuthorized', 623, 624),
(312, 301, NULL, NULL, 'flashBack', 625, 626),
(313, 301, NULL, NULL, 'setFlash', 627, 628),
(314, 301, NULL, NULL, 'serviceResponse', 629, 630),
(315, 301, NULL, NULL, 'getNamedParameter', 631, 632),
(316, 301, NULL, NULL, 'allowedByContainerId', 633, 634),
(317, 301, NULL, NULL, 'render403', 635, 636),
(318, 1, NULL, NULL, 'Hosts', 638, 713),
(319, 318, NULL, NULL, 'index', 639, 640),
(320, 318, NULL, NULL, 'notMonitored', 641, 642),
(321, 318, NULL, NULL, 'edit', 643, 644),
(322, 318, NULL, NULL, 'sharing', 645, 646),
(323, 318, NULL, NULL, 'edit_details', 647, 648),
(324, 318, NULL, NULL, 'add', 649, 650),
(325, 318, NULL, NULL, 'disabled', 651, 652),
(326, 318, NULL, NULL, 'deactivate', 653, 654),
(327, 318, NULL, NULL, 'mass_deactivate', 655, 656),
(328, 318, NULL, NULL, 'enable', 657, 658),
(329, 318, NULL, NULL, 'delete', 659, 660),
(330, 318, NULL, NULL, 'mass_delete', 661, 662),
(331, 318, NULL, NULL, 'copy', 663, 664),
(332, 318, NULL, NULL, 'browser', 665, 666),
(333, 318, NULL, NULL, 'longOutputByUuid', 667, 668),
(334, 318, NULL, NULL, 'gethostbyname', 669, 670),
(335, 318, NULL, NULL, 'gethostbyaddr', 671, 672),
(336, 318, NULL, NULL, 'loadHosttemplate', 673, 674),
(337, 318, NULL, NULL, 'addCustomMacro', 675, 676),
(338, 318, NULL, NULL, 'loadTemplateMacros', 677, 678),
(339, 318, NULL, NULL, 'loadParametersByCommandId', 679, 680),
(340, 318, NULL, NULL, 'loadArguments', 681, 682),
(341, 318, NULL, NULL, 'loadArgumentsAdd', 683, 684),
(342, 318, NULL, NULL, 'loadHosttemplatesArguments', 685, 686),
(343, 318, NULL, NULL, 'getHostByAjax', 687, 688),
(344, 318, NULL, NULL, 'listToPdf', 689, 690),
(345, 318, NULL, NULL, 'ping', 691, 692),
(346, 318, NULL, NULL, 'addParentHosts', 693, 694),
(347, 318, NULL, NULL, 'loadElementsByContainerId', 695, 696),
(348, 318, NULL, NULL, 'checkcommand', 697, 698),
(349, 318, NULL, NULL, 'isAuthorized', 699, 700),
(350, 318, NULL, NULL, 'flashBack', 701, 702),
(351, 318, NULL, NULL, 'setFlash', 703, 704),
(352, 318, NULL, NULL, 'serviceResponse', 705, 706),
(353, 318, NULL, NULL, 'getNamedParameter', 707, 708),
(354, 318, NULL, NULL, 'allowedByContainerId', 709, 710),
(355, 318, NULL, NULL, 'render403', 711, 712),
(356, 1, NULL, NULL, 'Hosttemplates', 714, 747),
(357, 356, NULL, NULL, 'index', 715, 716),
(358, 356, NULL, NULL, 'edit', 717, 718),
(359, 356, NULL, NULL, 'add', 719, 720),
(360, 356, NULL, NULL, 'delete', 721, 722),
(361, 356, NULL, NULL, 'addCustomMacro', 723, 724),
(362, 356, NULL, NULL, 'loadArguments', 725, 726),
(363, 356, NULL, NULL, 'loadArgumentsAdd', 727, 728),
(364, 356, NULL, NULL, 'usedBy', 729, 730),
(365, 356, NULL, NULL, 'loadElementsByContainerId', 731, 732),
(366, 356, NULL, NULL, 'isAuthorized', 733, 734),
(367, 356, NULL, NULL, 'flashBack', 735, 736),
(368, 356, NULL, NULL, 'setFlash', 737, 738),
(369, 356, NULL, NULL, 'serviceResponse', 739, 740),
(370, 356, NULL, NULL, 'getNamedParameter', 741, 742),
(371, 356, NULL, NULL, 'allowedByContainerId', 743, 744),
(372, 356, NULL, NULL, 'render403', 745, 746),
(373, 1, NULL, NULL, 'Instantreports', 748, 769),
(374, 373, NULL, NULL, 'index', 749, 750),
(375, 373, NULL, NULL, 'createPdfReport', 751, 752),
(376, 373, NULL, NULL, 'expandServices', 753, 754),
(377, 373, NULL, NULL, 'isAuthorized', 755, 756),
(378, 373, NULL, NULL, 'flashBack', 757, 758),
(379, 373, NULL, NULL, 'setFlash', 759, 760),
(380, 373, NULL, NULL, 'serviceResponse', 761, 762),
(381, 373, NULL, NULL, 'getNamedParameter', 763, 764),
(382, 373, NULL, NULL, 'allowedByContainerId', 765, 766),
(383, 373, NULL, NULL, 'render403', 767, 768),
(384, 1, NULL, NULL, 'Locations', 770, 793),
(385, 384, NULL, NULL, 'index', 771, 772),
(386, 384, NULL, NULL, 'add', 773, 774),
(387, 384, NULL, NULL, 'edit', 775, 776),
(388, 384, NULL, NULL, 'delete', 777, 778),
(389, 384, NULL, NULL, 'isAuthorized', 779, 780),
(390, 384, NULL, NULL, 'flashBack', 781, 782),
(391, 384, NULL, NULL, 'setFlash', 783, 784),
(392, 384, NULL, NULL, 'serviceResponse', 785, 786),
(393, 384, NULL, NULL, 'getNamedParameter', 787, 788),
(394, 384, NULL, NULL, 'allowedByContainerId', 789, 790),
(395, 384, NULL, NULL, 'render403', 791, 792),
(396, 1, NULL, NULL, 'Logentries', 794, 811),
(397, 396, NULL, NULL, 'index', 795, 796),
(398, 396, NULL, NULL, 'isAuthorized', 797, 798),
(399, 396, NULL, NULL, 'flashBack', 799, 800),
(400, 396, NULL, NULL, 'setFlash', 801, 802),
(401, 396, NULL, NULL, 'serviceResponse', 803, 804),
(402, 396, NULL, NULL, 'getNamedParameter', 805, 806),
(403, 396, NULL, NULL, 'allowedByContainerId', 807, 808),
(404, 396, NULL, NULL, 'render403', 809, 810),
(405, 1, NULL, NULL, 'Login', 812, 839),
(406, 405, NULL, NULL, 'index', 813, 814),
(407, 405, NULL, NULL, 'login', 815, 816),
(408, 405, NULL, NULL, 'onetimetoken', 817, 818),
(409, 405, NULL, NULL, 'logout', 819, 820),
(410, 405, NULL, NULL, 'auth_required', 821, 822),
(411, 405, NULL, NULL, 'lock', 823, 824),
(412, 405, NULL, NULL, 'isAuthorized', 825, 826),
(413, 405, NULL, NULL, 'flashBack', 827, 828),
(414, 405, NULL, NULL, 'setFlash', 829, 830),
(415, 405, NULL, NULL, 'serviceResponse', 831, 832),
(416, 405, NULL, NULL, 'getNamedParameter', 833, 834),
(417, 405, NULL, NULL, 'allowedByContainerId', 835, 836),
(418, 405, NULL, NULL, 'render403', 837, 838),
(419, 1, NULL, NULL, 'Macros', 840, 859),
(420, 419, NULL, NULL, 'index', 841, 842),
(421, 419, NULL, NULL, 'addMacro', 843, 844),
(422, 419, NULL, NULL, 'isAuthorized', 845, 846),
(423, 419, NULL, NULL, 'flashBack', 847, 848),
(424, 419, NULL, NULL, 'setFlash', 849, 850),
(425, 419, NULL, NULL, 'serviceResponse', 851, 852),
(426, 419, NULL, NULL, 'getNamedParameter', 853, 854),
(427, 419, NULL, NULL, 'allowedByContainerId', 855, 856),
(428, 419, NULL, NULL, 'render403', 857, 858),
(429, 1, NULL, NULL, 'Nagiostats', 860, 877),
(430, 429, NULL, NULL, 'index', 861, 862),
(431, 429, NULL, NULL, 'isAuthorized', 863, 864),
(432, 429, NULL, NULL, 'flashBack', 865, 866),
(433, 429, NULL, NULL, 'setFlash', 867, 868),
(434, 429, NULL, NULL, 'serviceResponse', 869, 870),
(435, 429, NULL, NULL, 'getNamedParameter', 871, 872),
(436, 429, NULL, NULL, 'allowedByContainerId', 873, 874),
(437, 429, NULL, NULL, 'render403', 875, 876),
(438, 1, NULL, NULL, 'Notifications', 878, 899),
(439, 438, NULL, NULL, 'index', 879, 880),
(440, 438, NULL, NULL, 'hostNotification', 881, 882),
(441, 438, NULL, NULL, 'serviceNotification', 883, 884),
(442, 438, NULL, NULL, 'isAuthorized', 885, 886),
(443, 438, NULL, NULL, 'flashBack', 887, 888),
(444, 438, NULL, NULL, 'setFlash', 889, 890),
(445, 438, NULL, NULL, 'serviceResponse', 891, 892),
(446, 438, NULL, NULL, 'getNamedParameter', 893, 894),
(447, 438, NULL, NULL, 'allowedByContainerId', 895, 896),
(448, 438, NULL, NULL, 'render403', 897, 898),
(449, 1, NULL, NULL, 'Packetmanager', 900, 919),
(450, 449, NULL, NULL, 'index', 901, 902),
(451, 449, NULL, NULL, 'getPackets', 903, 904),
(452, 449, NULL, NULL, 'isAuthorized', 905, 906),
(453, 449, NULL, NULL, 'flashBack', 907, 908),
(454, 449, NULL, NULL, 'setFlash', 909, 910),
(455, 449, NULL, NULL, 'serviceResponse', 911, 912),
(456, 449, NULL, NULL, 'getNamedParameter', 913, 914),
(457, 449, NULL, NULL, 'allowedByContainerId', 915, 916),
(458, 449, NULL, NULL, 'render403', 917, 918),
(459, 1, NULL, NULL, 'Profile', 920, 939),
(460, 459, NULL, NULL, 'edit', 921, 922),
(461, 459, NULL, NULL, 'deleteImage', 923, 924),
(462, 459, NULL, NULL, 'isAuthorized', 925, 926),
(463, 459, NULL, NULL, 'flashBack', 927, 928),
(464, 459, NULL, NULL, 'setFlash', 929, 930),
(465, 459, NULL, NULL, 'serviceResponse', 931, 932),
(466, 459, NULL, NULL, 'getNamedParameter', 933, 934),
(467, 459, NULL, NULL, 'allowedByContainerId', 935, 936),
(468, 459, NULL, NULL, 'render403', 937, 938),
(469, 1, NULL, NULL, 'Proxy', 940, 961),
(470, 469, NULL, NULL, 'index', 941, 942),
(471, 469, NULL, NULL, 'edit', 943, 944),
(472, 469, NULL, NULL, 'getSettings', 945, 946),
(473, 469, NULL, NULL, 'isAuthorized', 947, 948),
(474, 469, NULL, NULL, 'flashBack', 949, 950),
(475, 469, NULL, NULL, 'setFlash', 951, 952),
(476, 469, NULL, NULL, 'serviceResponse', 953, 954),
(477, 469, NULL, NULL, 'getNamedParameter', 955, 956),
(478, 469, NULL, NULL, 'allowedByContainerId', 957, 958),
(479, 469, NULL, NULL, 'render403', 959, 960),
(480, 1, NULL, NULL, 'Qr', 962, 979),
(481, 480, NULL, NULL, 'index', 963, 964),
(482, 480, NULL, NULL, 'isAuthorized', 965, 966),
(483, 480, NULL, NULL, 'flashBack', 967, 968),
(484, 480, NULL, NULL, 'setFlash', 969, 970),
(485, 480, NULL, NULL, 'serviceResponse', 971, 972),
(486, 480, NULL, NULL, 'getNamedParameter', 973, 974),
(487, 480, NULL, NULL, 'allowedByContainerId', 975, 976),
(488, 480, NULL, NULL, 'render403', 977, 978),
(489, 1, NULL, NULL, 'Registers', 980, 999),
(490, 489, NULL, NULL, 'index', 981, 982),
(491, 489, NULL, NULL, 'check', 983, 984),
(492, 489, NULL, NULL, 'isAuthorized', 985, 986),
(493, 489, NULL, NULL, 'flashBack', 987, 988),
(494, 489, NULL, NULL, 'setFlash', 989, 990),
(495, 489, NULL, NULL, 'serviceResponse', 991, 992),
(496, 489, NULL, NULL, 'getNamedParameter', 993, 994),
(497, 489, NULL, NULL, 'allowedByContainerId', 995, 996),
(498, 489, NULL, NULL, 'render403', 997, 998),
(499, 1, NULL, NULL, 'Rrds', 1000, 1019),
(500, 499, NULL, NULL, 'index', 1001, 1002),
(501, 499, NULL, NULL, 'ajax', 1003, 1004),
(502, 499, NULL, NULL, 'isAuthorized', 1005, 1006),
(503, 499, NULL, NULL, 'flashBack', 1007, 1008),
(504, 499, NULL, NULL, 'setFlash', 1009, 1010),
(505, 499, NULL, NULL, 'serviceResponse', 1011, 1012),
(506, 499, NULL, NULL, 'getNamedParameter', 1013, 1014),
(507, 499, NULL, NULL, 'allowedByContainerId', 1015, 1016),
(508, 499, NULL, NULL, 'render403', 1017, 1018),
(509, 1, NULL, NULL, 'Search', 1020, 1041),
(510, 509, NULL, NULL, 'index', 1021, 1022),
(511, 509, NULL, NULL, 'hostMacro', 1023, 1024),
(512, 509, NULL, NULL, 'serviceMacro', 1025, 1026),
(513, 509, NULL, NULL, 'isAuthorized', 1027, 1028),
(514, 509, NULL, NULL, 'flashBack', 1029, 1030),
(515, 509, NULL, NULL, 'setFlash', 1031, 1032),
(516, 509, NULL, NULL, 'serviceResponse', 1033, 1034),
(517, 509, NULL, NULL, 'getNamedParameter', 1035, 1036),
(518, 509, NULL, NULL, 'allowedByContainerId', 1037, 1038),
(519, 509, NULL, NULL, 'render403', 1039, 1040),
(520, 1, NULL, NULL, 'Servicechecks', 1042, 1059),
(521, 520, NULL, NULL, 'index', 1043, 1044),
(522, 520, NULL, NULL, 'isAuthorized', 1045, 1046),
(523, 520, NULL, NULL, 'flashBack', 1047, 1048),
(524, 520, NULL, NULL, 'setFlash', 1049, 1050),
(525, 520, NULL, NULL, 'serviceResponse', 1051, 1052),
(526, 520, NULL, NULL, 'getNamedParameter', 1053, 1054),
(527, 520, NULL, NULL, 'allowedByContainerId', 1055, 1056),
(528, 520, NULL, NULL, 'render403', 1057, 1058),
(529, 1, NULL, NULL, 'Servicedependencies', 1060, 1085),
(530, 529, NULL, NULL, 'index', 1061, 1062),
(531, 529, NULL, NULL, 'edit', 1063, 1064),
(532, 529, NULL, NULL, 'add', 1065, 1066),
(533, 529, NULL, NULL, 'delete', 1067, 1068),
(534, 529, NULL, NULL, 'loadElementsByContainerId', 1069, 1070),
(535, 529, NULL, NULL, 'isAuthorized', 1071, 1072),
(536, 529, NULL, NULL, 'flashBack', 1073, 1074),
(537, 529, NULL, NULL, 'setFlash', 1075, 1076),
(538, 529, NULL, NULL, 'serviceResponse', 1077, 1078),
(539, 529, NULL, NULL, 'getNamedParameter', 1079, 1080),
(540, 529, NULL, NULL, 'allowedByContainerId', 1081, 1082),
(541, 529, NULL, NULL, 'render403', 1083, 1084),
(542, 1, NULL, NULL, 'Serviceescalations', 1086, 1111),
(543, 542, NULL, NULL, 'index', 1087, 1088),
(544, 542, NULL, NULL, 'edit', 1089, 1090),
(545, 542, NULL, NULL, 'add', 1091, 1092),
(546, 542, NULL, NULL, 'delete', 1093, 1094),
(547, 542, NULL, NULL, 'loadElementsByContainerId', 1095, 1096),
(548, 542, NULL, NULL, 'isAuthorized', 1097, 1098),
(549, 542, NULL, NULL, 'flashBack', 1099, 1100),
(550, 542, NULL, NULL, 'setFlash', 1101, 1102),
(551, 542, NULL, NULL, 'serviceResponse', 1103, 1104),
(552, 542, NULL, NULL, 'getNamedParameter', 1105, 1106),
(553, 542, NULL, NULL, 'allowedByContainerId', 1107, 1108),
(554, 542, NULL, NULL, 'render403', 1109, 1110),
(555, 1, NULL, NULL, 'Servicegroups', 1112, 1143),
(556, 555, NULL, NULL, 'index', 1113, 1114),
(557, 555, NULL, NULL, 'edit', 1115, 1116),
(558, 555, NULL, NULL, 'add', 1117, 1118),
(559, 555, NULL, NULL, 'loadServices', 1119, 1120),
(560, 555, NULL, NULL, 'delete', 1121, 1122),
(561, 555, NULL, NULL, 'mass_delete', 1123, 1124),
(562, 555, NULL, NULL, 'mass_add', 1125, 1126),
(563, 555, NULL, NULL, 'listToPdf', 1127, 1128),
(564, 555, NULL, NULL, 'isAuthorized', 1129, 1130),
(565, 555, NULL, NULL, 'flashBack', 1131, 1132),
(566, 555, NULL, NULL, 'setFlash', 1133, 1134),
(567, 555, NULL, NULL, 'serviceResponse', 1135, 1136),
(568, 555, NULL, NULL, 'getNamedParameter', 1137, 1138),
(569, 555, NULL, NULL, 'allowedByContainerId', 1139, 1140),
(570, 555, NULL, NULL, 'render403', 1141, 1142),
(571, 1, NULL, NULL, 'Services', 1144, 1223),
(572, 571, NULL, NULL, 'index', 1145, 1146),
(573, 571, NULL, NULL, 'notMonitored', 1147, 1148),
(574, 571, NULL, NULL, 'disabled', 1149, 1150),
(575, 571, NULL, NULL, 'add', 1151, 1152),
(576, 571, NULL, NULL, 'edit', 1153, 1154),
(577, 571, NULL, NULL, 'delete', 1155, 1156),
(578, 571, NULL, NULL, 'mass_delete', 1157, 1158),
(579, 571, NULL, NULL, 'copy', 1159, 1160),
(580, 571, NULL, NULL, 'deactivate', 1161, 1162),
(581, 571, NULL, NULL, 'mass_deactivate', 1163, 1164),
(582, 571, NULL, NULL, 'enable', 1165, 1166),
(583, 571, NULL, NULL, 'loadContactsAndContactgroups', 1167, 1168),
(584, 571, NULL, NULL, 'loadParametersByCommandId', 1169, 1170),
(585, 571, NULL, NULL, 'loadNagParametersByCommandId', 1171, 1172),
(586, 571, NULL, NULL, 'loadArgumentsAdd', 1173, 1174),
(587, 571, NULL, NULL, 'loadServicetemplatesArguments', 1175, 1176),
(588, 571, NULL, NULL, 'loadTemplateData', 1177, 1178),
(589, 571, NULL, NULL, 'addCustomMacro', 1179, 1180),
(590, 571, NULL, NULL, 'loadServices', 1181, 1182),
(591, 571, NULL, NULL, 'loadTemplateMacros', 1183, 1184),
(592, 571, NULL, NULL, 'browser', 1185, 1186),
(593, 571, NULL, NULL, 'servicesByHostId', 1187, 1188),
(594, 571, NULL, NULL, 'serviceList', 1189, 1190),
(595, 571, NULL, NULL, 'grapherSwitch', 1191, 1192),
(596, 571, NULL, NULL, 'grapher', 1193, 1194),
(597, 571, NULL, NULL, 'grapherTemplate', 1195, 1196),
(598, 571, NULL, NULL, 'grapherZoom', 1197, 1198),
(599, 571, NULL, NULL, 'grapherZoomTemplate', 1199, 1200),
(600, 571, NULL, NULL, 'createGrapherErrorPng', 1201, 1202),
(601, 571, NULL, NULL, 'longOutputByUuid', 1203, 1204),
(602, 571, NULL, NULL, 'listToPdf', 1205, 1206),
(603, 571, NULL, NULL, 'checkcommand', 1207, 1208),
(604, 571, NULL, NULL, 'isAuthorized', 1209, 1210),
(605, 571, NULL, NULL, 'flashBack', 1211, 1212),
(606, 571, NULL, NULL, 'setFlash', 1213, 1214),
(607, 571, NULL, NULL, 'serviceResponse', 1215, 1216),
(608, 571, NULL, NULL, 'getNamedParameter', 1217, 1218),
(609, 571, NULL, NULL, 'allowedByContainerId', 1219, 1220),
(610, 571, NULL, NULL, 'render403', 1221, 1222),
(611, 1, NULL, NULL, 'Servicetemplategroups', 1224, 1257),
(612, 611, NULL, NULL, 'index', 1225, 1226),
(613, 611, NULL, NULL, 'add', 1227, 1228),
(614, 611, NULL, NULL, 'edit', 1229, 1230),
(615, 611, NULL, NULL, 'allocateToHost', 1231, 1232),
(616, 611, NULL, NULL, 'allocateToHostgroup', 1233, 1234),
(617, 611, NULL, NULL, 'getHostsByHostgroupByAjax', 1235, 1236),
(618, 611, NULL, NULL, 'delete', 1237, 1238),
(619, 611, NULL, NULL, 'loadServicetemplatesByContainerId', 1239, 1240),
(620, 611, NULL, NULL, 'isAuthorized', 1241, 1242),
(621, 611, NULL, NULL, 'flashBack', 1243, 1244),
(622, 611, NULL, NULL, 'setFlash', 1245, 1246),
(623, 611, NULL, NULL, 'serviceResponse', 1247, 1248),
(624, 611, NULL, NULL, 'getNamedParameter', 1249, 1250),
(625, 611, NULL, NULL, 'allowedByContainerId', 1251, 1252),
(626, 611, NULL, NULL, 'render403', 1253, 1254),
(627, 1, NULL, NULL, 'Servicetemplates', 1258, 1299),
(628, 627, NULL, NULL, 'index', 1259, 1260),
(629, 627, NULL, NULL, 'edit', 1261, 1262),
(630, 627, NULL, NULL, 'add', 1263, 1264),
(631, 627, NULL, NULL, 'delete', 1265, 1266),
(632, 627, NULL, NULL, 'usedBy', 1267, 1268),
(633, 627, NULL, NULL, 'loadArguments', 1269, 1270),
(634, 627, NULL, NULL, 'loadContactsAndContactgroups', 1271, 1272),
(635, 627, NULL, NULL, 'loadArgumentsAdd', 1273, 1274),
(636, 627, NULL, NULL, 'loadNagArgumentsAdd', 1275, 1276),
(637, 627, NULL, NULL, 'addCustomMacro', 1277, 1278),
(638, 627, NULL, NULL, 'loadParametersByCommandId', 1279, 1280),
(639, 627, NULL, NULL, 'loadNagParametersByCommandId', 1281, 1282),
(640, 627, NULL, NULL, 'loadElementsByContainerId', 1283, 1284),
(641, 627, NULL, NULL, 'isAuthorized', 1285, 1286),
(642, 627, NULL, NULL, 'flashBack', 1287, 1288),
(643, 627, NULL, NULL, 'setFlash', 1289, 1290),
(644, 627, NULL, NULL, 'serviceResponse', 1291, 1292),
(645, 627, NULL, NULL, 'getNamedParameter', 1293, 1294),
(646, 627, NULL, NULL, 'allowedByContainerId', 1295, 1296),
(647, 627, NULL, NULL, 'render403', 1297, 1298),
(648, 1, NULL, NULL, 'Statehistories', 1300, 1319),
(649, 648, NULL, NULL, 'service', 1301, 1302),
(650, 648, NULL, NULL, 'host', 1303, 1304),
(651, 648, NULL, NULL, 'isAuthorized', 1305, 1306),
(652, 648, NULL, NULL, 'flashBack', 1307, 1308),
(653, 648, NULL, NULL, 'setFlash', 1309, 1310),
(654, 648, NULL, NULL, 'serviceResponse', 1311, 1312),
(655, 648, NULL, NULL, 'getNamedParameter', 1313, 1314),
(656, 648, NULL, NULL, 'allowedByContainerId', 1315, 1316),
(657, 648, NULL, NULL, 'render403', 1317, 1318),
(658, 1, NULL, NULL, 'Statusmaps', 1320, 1343),
(659, 658, NULL, NULL, 'index', 1321, 1322),
(660, 658, NULL, NULL, 'getHostsAndConnections', 1323, 1324),
(661, 658, NULL, NULL, 'clickHostStatus', 1325, 1326),
(662, 658, NULL, NULL, 'view', 1327, 1328),
(663, 658, NULL, NULL, 'isAuthorized', 1329, 1330),
(664, 658, NULL, NULL, 'flashBack', 1331, 1332),
(665, 658, NULL, NULL, 'setFlash', 1333, 1334),
(666, 658, NULL, NULL, 'serviceResponse', 1335, 1336),
(667, 658, NULL, NULL, 'getNamedParameter', 1337, 1338),
(668, 658, NULL, NULL, 'allowedByContainerId', 1339, 1340),
(669, 658, NULL, NULL, 'render403', 1341, 1342),
(670, 1, NULL, NULL, 'System', 1344, 1361),
(671, 670, NULL, NULL, 'changelog', 1345, 1346),
(672, 670, NULL, NULL, 'isAuthorized', 1347, 1348),
(673, 670, NULL, NULL, 'flashBack', 1349, 1350),
(674, 670, NULL, NULL, 'setFlash', 1351, 1352),
(675, 670, NULL, NULL, 'serviceResponse', 1353, 1354),
(676, 670, NULL, NULL, 'getNamedParameter', 1355, 1356),
(677, 670, NULL, NULL, 'allowedByContainerId', 1357, 1358),
(678, 670, NULL, NULL, 'render403', 1359, 1360),
(679, 1, NULL, NULL, 'Systemdowntimes', 1362, 1387),
(680, 679, NULL, NULL, 'index', 1363, 1364),
(681, 679, NULL, NULL, 'addHostdowntime', 1365, 1366),
(682, 679, NULL, NULL, 'addHostgroupdowntime', 1367, 1368),
(683, 679, NULL, NULL, 'addServicedowntime', 1369, 1370),
(684, 679, NULL, NULL, 'delete', 1371, 1372),
(685, 679, NULL, NULL, 'isAuthorized', 1373, 1374),
(686, 679, NULL, NULL, 'flashBack', 1375, 1376),
(687, 679, NULL, NULL, 'setFlash', 1377, 1378),
(688, 679, NULL, NULL, 'serviceResponse', 1379, 1380),
(689, 679, NULL, NULL, 'getNamedParameter', 1381, 1382),
(690, 679, NULL, NULL, 'allowedByContainerId', 1383, 1384),
(691, 679, NULL, NULL, 'render403', 1385, 1386),
(692, 1, NULL, NULL, 'Systemfailures', 1388, 1409),
(693, 692, NULL, NULL, 'index', 1389, 1390),
(694, 692, NULL, NULL, 'add', 1391, 1392),
(695, 692, NULL, NULL, 'delete', 1393, 1394),
(696, 692, NULL, NULL, 'isAuthorized', 1395, 1396),
(697, 692, NULL, NULL, 'flashBack', 1397, 1398),
(698, 692, NULL, NULL, 'setFlash', 1399, 1400),
(699, 692, NULL, NULL, 'serviceResponse', 1401, 1402),
(700, 692, NULL, NULL, 'getNamedParameter', 1403, 1404),
(701, 692, NULL, NULL, 'allowedByContainerId', 1405, 1406),
(702, 692, NULL, NULL, 'render403', 1407, 1408),
(703, 1, NULL, NULL, 'Systemsettings', 1410, 1427),
(704, 703, NULL, NULL, 'index', 1411, 1412),
(705, 703, NULL, NULL, 'isAuthorized', 1413, 1414),
(706, 703, NULL, NULL, 'flashBack', 1415, 1416),
(707, 703, NULL, NULL, 'setFlash', 1417, 1418),
(708, 703, NULL, NULL, 'serviceResponse', 1419, 1420),
(709, 703, NULL, NULL, 'getNamedParameter', 1421, 1422),
(710, 703, NULL, NULL, 'allowedByContainerId', 1423, 1424),
(711, 703, NULL, NULL, 'render403', 1425, 1426),
(712, 1, NULL, NULL, 'Tenants', 1428, 1453),
(713, 712, NULL, NULL, 'index', 1429, 1430),
(714, 712, NULL, NULL, 'add', 1431, 1432),
(715, 712, NULL, NULL, 'delete', 1433, 1434),
(716, 712, NULL, NULL, 'mass_delete', 1435, 1436),
(717, 712, NULL, NULL, 'edit', 1437, 1438),
(718, 712, NULL, NULL, 'isAuthorized', 1439, 1440),
(719, 712, NULL, NULL, 'flashBack', 1441, 1442),
(720, 712, NULL, NULL, 'setFlash', 1443, 1444),
(721, 712, NULL, NULL, 'serviceResponse', 1445, 1446),
(722, 712, NULL, NULL, 'getNamedParameter', 1447, 1448),
(723, 712, NULL, NULL, 'allowedByContainerId', 1449, 1450),
(724, 712, NULL, NULL, 'render403', 1451, 1452),
(725, 1, NULL, NULL, 'Timeperiods', 1454, 1483),
(726, 725, NULL, NULL, 'index', 1455, 1456),
(727, 725, NULL, NULL, 'edit', 1457, 1458),
(728, 725, NULL, NULL, 'add', 1459, 1460),
(729, 725, NULL, NULL, 'delete', 1461, 1462),
(730, 725, NULL, NULL, 'mass_delete', 1463, 1464),
(731, 725, NULL, NULL, 'browser', 1465, 1466),
(732, 725, NULL, NULL, 'controller', 1467, 1468),
(733, 725, NULL, NULL, 'isAuthorized', 1469, 1470),
(734, 725, NULL, NULL, 'flashBack', 1471, 1472),
(735, 725, NULL, NULL, 'setFlash', 1473, 1474),
(736, 725, NULL, NULL, 'serviceResponse', 1475, 1476),
(737, 725, NULL, NULL, 'getNamedParameter', 1477, 1478),
(738, 725, NULL, NULL, 'allowedByContainerId', 1479, 1480),
(739, 725, NULL, NULL, 'render403', 1481, 1482),
(740, 1, NULL, NULL, 'Usergroups', 1484, 1507),
(741, 740, NULL, NULL, 'index', 1485, 1486),
(742, 740, NULL, NULL, 'edit', 1487, 1488),
(743, 740, NULL, NULL, 'add', 1489, 1490),
(744, 740, NULL, NULL, 'delete', 1491, 1492),
(745, 740, NULL, NULL, 'isAuthorized', 1493, 1494),
(746, 740, NULL, NULL, 'flashBack', 1495, 1496),
(747, 740, NULL, NULL, 'setFlash', 1497, 1498),
(748, 740, NULL, NULL, 'serviceResponse', 1499, 1500),
(749, 740, NULL, NULL, 'getNamedParameter', 1501, 1502),
(750, 740, NULL, NULL, 'allowedByContainerId', 1503, 1504),
(751, 740, NULL, NULL, 'render403', 1505, 1506),
(752, 1, NULL, NULL, 'Users', 1508, 1535),
(753, 752, NULL, NULL, 'index', 1509, 1510),
(754, 752, NULL, NULL, 'delete', 1511, 1512),
(755, 752, NULL, NULL, 'add', 1513, 1514),
(756, 752, NULL, NULL, 'edit', 1515, 1516),
(757, 752, NULL, NULL, 'addFromLdap', 1517, 1518),
(758, 752, NULL, NULL, 'resetPassword', 1519, 1520),
(759, 752, NULL, NULL, 'isAuthorized', 1521, 1522),
(760, 752, NULL, NULL, 'flashBack', 1523, 1524),
(761, 752, NULL, NULL, 'setFlash', 1525, 1526),
(762, 752, NULL, NULL, 'serviceResponse', 1527, 1528),
(763, 752, NULL, NULL, 'getNamedParameter', 1529, 1530),
(764, 752, NULL, NULL, 'allowedByContainerId', 1531, 1532),
(765, 752, NULL, NULL, 'render403', 1533, 1534),
(766, 1, NULL, NULL, 'AclExtras', 1536, 1537),
(767, 1, NULL, NULL, 'Admin', 1538, 1539),
(809, 1, NULL, NULL, 'BoostCake', 1540, 1563),
(810, 809, NULL, NULL, 'BoostCake', 1541, 1562),
(811, 810, NULL, NULL, 'index', 1542, 1543),
(812, 810, NULL, NULL, 'bootstrap2', 1544, 1545),
(813, 810, NULL, NULL, 'bootstrap3', 1546, 1547),
(814, 810, NULL, NULL, 'isAuthorized', 1548, 1549),
(815, 810, NULL, NULL, 'flashBack', 1550, 1551),
(816, 810, NULL, NULL, 'setFlash', 1552, 1553),
(817, 810, NULL, NULL, 'serviceResponse', 1554, 1555),
(818, 810, NULL, NULL, 'getNamedParameter', 1556, 1557),
(819, 810, NULL, NULL, 'allowedByContainerId', 1558, 1559),
(820, 810, NULL, NULL, 'render403', 1560, 1561),
(821, 1, NULL, NULL, 'CakePdf', 1564, 1565),
(822, 1, NULL, NULL, 'ChatModule', 1566, 1585),
(823, 822, NULL, NULL, 'Chat', 1567, 1584),
(824, 823, NULL, NULL, 'index', 1568, 1569),
(825, 823, NULL, NULL, 'isAuthorized', 1570, 1571),
(826, 823, NULL, NULL, 'flashBack', 1572, 1573),
(827, 823, NULL, NULL, 'setFlash', 1574, 1575),
(828, 823, NULL, NULL, 'serviceResponse', 1576, 1577),
(829, 823, NULL, NULL, 'getNamedParameter', 1578, 1579),
(830, 823, NULL, NULL, 'allowedByContainerId', 1580, 1581),
(831, 823, NULL, NULL, 'render403', 1582, 1583),
(832, 1, NULL, NULL, 'ClearCache', 1586, 1609),
(833, 832, NULL, NULL, 'ClearCache', 1587, 1608),
(834, 833, NULL, NULL, 'files', 1588, 1589),
(835, 833, NULL, NULL, 'engines', 1590, 1591),
(836, 833, NULL, NULL, 'groups', 1592, 1593),
(837, 833, NULL, NULL, 'isAuthorized', 1594, 1595),
(838, 833, NULL, NULL, 'flashBack', 1596, 1597),
(839, 833, NULL, NULL, 'setFlash', 1598, 1599),
(840, 833, NULL, NULL, 'serviceResponse', 1600, 1601),
(841, 833, NULL, NULL, 'getNamedParameter', 1602, 1603),
(842, 833, NULL, NULL, 'allowedByContainerId', 1604, 1605),
(843, 833, NULL, NULL, 'render403', 1606, 1607),
(844, 1, NULL, NULL, 'DebugKit', 1610, 1631),
(845, 844, NULL, NULL, 'ToolbarAccess', 1611, 1630),
(846, 845, NULL, NULL, 'history_state', 1612, 1613),
(847, 845, NULL, NULL, 'sql_explain', 1614, 1615),
(848, 845, NULL, NULL, 'isAuthorized', 1616, 1617),
(849, 845, NULL, NULL, 'flashBack', 1618, 1619),
(850, 845, NULL, NULL, 'setFlash', 1620, 1621),
(851, 845, NULL, NULL, 'serviceResponse', 1622, 1623),
(852, 845, NULL, NULL, 'getNamedParameter', 1624, 1625),
(853, 845, NULL, NULL, 'allowedByContainerId', 1626, 1627),
(854, 845, NULL, NULL, 'render403', 1628, 1629),
(855, 1, NULL, NULL, 'ExampleModule', 1632, 1633),
(856, 1, NULL, NULL, 'Frontend', 1634, 1653),
(857, 856, NULL, NULL, 'FrontendDependencies', 1635, 1652),
(858, 857, NULL, NULL, 'index', 1636, 1637),
(859, 857, NULL, NULL, 'isAuthorized', 1638, 1639),
(860, 857, NULL, NULL, 'flashBack', 1640, 1641),
(861, 857, NULL, NULL, 'setFlash', 1642, 1643),
(862, 857, NULL, NULL, 'serviceResponse', 1644, 1645),
(863, 857, NULL, NULL, 'getNamedParameter', 1646, 1647),
(864, 857, NULL, NULL, 'allowedByContainerId', 1648, 1649),
(865, 857, NULL, NULL, 'render403', 1650, 1651),
(866, 1, NULL, NULL, 'ListFilter', 1654, 1655),
(867, 1, NULL, NULL, 'NagiosModule', 1656, 1695),
(868, 867, NULL, NULL, 'Cmd', 1657, 1676),
(869, 868, NULL, NULL, 'index', 1658, 1659),
(870, 868, NULL, NULL, 'submit', 1660, 1661),
(871, 868, NULL, NULL, 'isAuthorized', 1662, 1663),
(872, 868, NULL, NULL, 'flashBack', 1664, 1665),
(873, 868, NULL, NULL, 'setFlash', 1666, 1667),
(874, 868, NULL, NULL, 'serviceResponse', 1668, 1669),
(875, 868, NULL, NULL, 'getNamedParameter', 1670, 1671),
(876, 868, NULL, NULL, 'allowedByContainerId', 1672, 1673),
(877, 868, NULL, NULL, 'render403', 1674, 1675),
(878, 867, NULL, NULL, 'Nagios', 1677, 1694),
(879, 878, NULL, NULL, 'index', 1678, 1679),
(880, 878, NULL, NULL, 'isAuthorized', 1680, 1681),
(881, 878, NULL, NULL, 'flashBack', 1682, 1683),
(882, 878, NULL, NULL, 'setFlash', 1684, 1685),
(883, 878, NULL, NULL, 'serviceResponse', 1686, 1687),
(884, 878, NULL, NULL, 'getNamedParameter', 1688, 1689),
(885, 878, NULL, NULL, 'allowedByContainerId', 1690, 1691),
(886, 878, NULL, NULL, 'render403', 1692, 1693),
(887, 12, NULL, NULL, 'testMail', 41, 42),
(888, 114, NULL, NULL, 'addFromLdap', 249, 250),
(889, 1, NULL, NULL, 'Dashboards', 1696, 1757),
(890, 889, NULL, NULL, 'index', 1697, 1698),
(891, 889, NULL, NULL, 'next', 1699, 1700),
(892, 889, NULL, NULL, 'add', 1701, 1702),
(893, 889, NULL, NULL, 'createTab', 1703, 1704),
(894, 889, NULL, NULL, 'createTabFromSharing', 1705, 1706),
(895, 889, NULL, NULL, 'updateSharedTab', 1707, 1708),
(896, 889, NULL, NULL, 'disableUpdate', 1709, 1710),
(897, 889, NULL, NULL, 'renameTab', 1711, 1712),
(898, 889, NULL, NULL, 'deleteTab', 1713, 1714),
(899, 889, NULL, NULL, 'restoreDefault', 1715, 1716),
(900, 889, NULL, NULL, 'updateTitle', 1717, 1718),
(901, 889, NULL, NULL, 'updateColor', 1719, 1720),
(902, 889, NULL, NULL, 'updatePosition', 1721, 1722),
(903, 889, NULL, NULL, 'deleteWidget', 1723, 1724),
(904, 889, NULL, NULL, 'updateTabPosition', 1725, 1726),
(905, 889, NULL, NULL, 'saveTabRotationInterval', 1727, 1728),
(906, 889, NULL, NULL, 'startSharing', 1729, 1730),
(907, 889, NULL, NULL, 'stopSharing', 1731, 1732),
(908, 889, NULL, NULL, 'refresh', 1733, 1734),
(909, 889, NULL, NULL, 'saveStatuslistSettings', 1735, 1736),
(910, 889, NULL, NULL, 'saveTrafficLightService', 1737, 1738),
(911, 889, NULL, NULL, 'getTachoPerfdata', 1739, 1740),
(912, 889, NULL, NULL, 'saveTachoConfig', 1741, 1742),
(913, 889, NULL, NULL, 'isAuthorized', 1743, 1744),
(914, 889, NULL, NULL, 'flashBack', 1745, 1746),
(915, 889, NULL, NULL, 'setFlash', 1747, 1748),
(916, 889, NULL, NULL, 'serviceResponse', 1749, 1750),
(917, 889, NULL, NULL, 'getNamedParameter', 1751, 1752),
(918, 889, NULL, NULL, 'allowedByContainerId', 1753, 1754),
(919, 889, NULL, NULL, 'render403', 1755, 1756),
(920, 218, NULL, NULL, 'broadcast', 449, 450),
(921, 218, NULL, NULL, 'launchExport', 451, 452),
(922, 218, NULL, NULL, 'verifyConfig', 453, 454),
(923, 611, NULL, NULL, 'allocateToMatchingHostgroup', 1255, 1256);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `aros`
--

CREATE TABLE IF NOT EXISTS `aros` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `parent_id` int(10) DEFAULT NULL,
  `model` varchar(255) DEFAULT NULL,
  `foreign_key` int(10) DEFAULT NULL,
  `alias` varchar(255) DEFAULT NULL,
  `lft` int(10) DEFAULT NULL,
  `rght` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Daten für Tabelle `aros`
--

INSERT INTO `aros` (`id`, `parent_id`, `model`, `foreign_key`, `alias`, `lft`, `rght`) VALUES
(1, NULL, 'Usergroup', 1, NULL, 1, 2),
(2, NULL, 'Usergroup', 2, NULL, 3, 4);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `aros_acos`
--

CREATE TABLE IF NOT EXISTS `aros_acos` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `aro_id` int(10) NOT NULL,
  `aco_id` int(10) NOT NULL,
  `_create` varchar(2) NOT NULL DEFAULT '0',
  `_read` varchar(2) NOT NULL DEFAULT '0',
  `_update` varchar(2) NOT NULL DEFAULT '0',
  `_delete` varchar(2) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `ARO_ACO_KEY` (`aro_id`,`aco_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2067 ;

--
-- Daten für Tabelle `aros_acos`
--

INSERT INTO `aros_acos` (`id`, `aro_id`, `aco_id`, `_create`, `_read`, `_update`, `_delete`) VALUES
(736, 2, 3, '1', '1', '1', '1'),
(737, 2, 4, '1', '1', '1', '1'),
(738, 2, 13, '1', '1', '1', '1'),
(739, 2, 23, '1', '1', '1', '1'),
(740, 2, 37, '1', '1', '1', '1'),
(741, 2, 38, '1', '1', '1', '1'),
(745, 2, 50, '1', '1', '1', '1'),
(746, 2, 64, '1', '1', '1', '1'),
(747, 2, 73, '1', '1', '1', '1'),
(748, 2, 82, '1', '1', '1', '1'),
(749, 2, 83, '1', '1', '1', '1'),
(750, 2, 84, '1', '1', '1', '1'),
(751, 2, 85, '1', '1', '1', '1'),
(752, 2, 101, '1', '1', '1', '1'),
(753, 2, 115, '1', '1', '1', '1'),
(754, 2, 129, '1', '1', '1', '1'),
(755, 2, 142, '1', '1', '1', '1'),
(756, 2, 155, '1', '1', '1', '1'),
(757, 2, 156, '1', '1', '1', '1'),
(758, 2, 165, '1', '1', '1', '1'),
(759, 2, 174, '1', '1', '1', '1'),
(760, 2, 186, '1', '1', '1', '1'),
(761, 2, 187, '1', '1', '1', '1'),
(762, 2, 188, '1', '1', '1', '1'),
(763, 2, 197, '1', '1', '1', '1'),
(764, 2, 198, '1', '1', '1', '1'),
(765, 2, 207, '1', '1', '1', '1'),
(766, 2, 209, '1', '1', '1', '1'),
(767, 2, 208, '1', '1', '1', '1'),
(768, 2, 219, '1', '1', '1', '1'),
(769, 2, 237, '1', '1', '1', '1'),
(770, 2, 239, '1', '1', '1', '1'),
(771, 2, 250, '1', '1', '1', '1'),
(772, 2, 267, '1', '1', '1', '1'),
(773, 2, 276, '1', '1', '1', '1'),
(774, 2, 289, '1', '1', '1', '1'),
(775, 2, 302, '1', '1', '1', '1'),
(776, 2, 310, '1', '1', '1', '1'),
(777, 2, 303, '1', '1', '1', '1'),
(778, 2, 319, '1', '1', '1', '1'),
(779, 2, 343, '1', '1', '1', '1'),
(780, 2, 344, '1', '1', '1', '1'),
(781, 2, 320, '1', '1', '1', '1'),
(782, 2, 325, '1', '1', '1', '1'),
(783, 2, 332, '1', '1', '1', '1'),
(784, 2, 333, '1', '1', '1', '1'),
(785, 2, 357, '1', '1', '1', '1'),
(786, 2, 364, '1', '1', '1', '1'),
(787, 2, 374, '1', '1', '1', '1'),
(788, 2, 375, '1', '1', '1', '1'),
(789, 2, 376, '1', '1', '1', '1'),
(790, 2, 385, '1', '1', '1', '1'),
(791, 2, 397, '1', '1', '1', '1'),
(792, 2, 406, '1', '1', '1', '1'),
(793, 2, 407, '1', '1', '1', '1'),
(794, 2, 408, '1', '1', '1', '1'),
(795, 2, 409, '1', '1', '1', '1'),
(796, 2, 410, '1', '1', '1', '1'),
(797, 2, 411, '1', '1', '1', '1'),
(798, 2, 420, '1', '1', '1', '1'),
(799, 2, 421, '1', '1', '1', '1'),
(800, 2, 430, '1', '1', '1', '1'),
(801, 2, 439, '1', '1', '1', '1'),
(802, 2, 440, '1', '1', '1', '1'),
(803, 2, 441, '1', '1', '1', '1'),
(804, 2, 450, '1', '1', '1', '1'),
(805, 2, 470, '1', '1', '1', '1'),
(806, 2, 481, '1', '1', '1', '1'),
(807, 2, 490, '1', '1', '1', '1'),
(808, 2, 491, '1', '1', '1', '1'),
(809, 2, 521, '1', '1', '1', '1'),
(810, 2, 530, '1', '1', '1', '1'),
(811, 2, 543, '1', '1', '1', '1'),
(812, 2, 556, '1', '1', '1', '1'),
(813, 2, 563, '1', '1', '1', '1'),
(814, 2, 572, '1', '1', '1', '1'),
(815, 2, 602, '1', '1', '1', '1'),
(816, 2, 590, '1', '1', '1', '1'),
(817, 2, 573, '1', '1', '1', '1'),
(818, 2, 574, '1', '1', '1', '1'),
(819, 2, 592, '1', '1', '1', '1'),
(820, 2, 593, '1', '1', '1', '1'),
(821, 2, 601, '1', '1', '1', '1'),
(822, 2, 594, '1', '1', '1', '1'),
(823, 2, 612, '1', '1', '1', '1'),
(824, 2, 628, '1', '1', '1', '1'),
(825, 2, 649, '1', '1', '1', '1'),
(826, 2, 650, '1', '1', '1', '1'),
(827, 2, 659, '1', '1', '1', '1'),
(828, 2, 662, '1', '1', '1', '1'),
(829, 2, 680, '1', '1', '1', '1'),
(830, 2, 693, '1', '1', '1', '1'),
(831, 2, 704, '1', '1', '1', '1'),
(832, 2, 713, '1', '1', '1', '1'),
(833, 2, 726, '1', '1', '1', '1'),
(834, 2, 741, '1', '1', '1', '1'),
(835, 2, 753, '1', '1', '1', '1'),
(836, 2, 824, '1', '1', '1', '1'),
(837, 2, 869, '1', '1', '1', '1'),
(838, 2, 879, '1', '1', '1', '1'),
(839, 2, 5, '1', '1', '1', '1'),
(840, 2, 6, '1', '1', '1', '1'),
(841, 2, 7, '1', '1', '1', '1'),
(842, 2, 8, '1', '1', '1', '1'),
(843, 2, 9, '1', '1', '1', '1'),
(844, 2, 10, '1', '1', '1', '1'),
(845, 2, 11, '1', '1', '1', '1'),
(846, 2, 15, '1', '1', '1', '1'),
(847, 2, 16, '1', '1', '1', '1'),
(848, 2, 17, '1', '1', '1', '1'),
(849, 2, 18, '1', '1', '1', '1'),
(850, 2, 19, '1', '1', '1', '1'),
(851, 2, 20, '1', '1', '1', '1'),
(852, 2, 21, '1', '1', '1', '1'),
(853, 2, 29, '1', '1', '1', '1'),
(854, 2, 30, '1', '1', '1', '1'),
(855, 2, 31, '1', '1', '1', '1'),
(856, 2, 32, '1', '1', '1', '1'),
(857, 2, 33, '1', '1', '1', '1'),
(858, 2, 34, '1', '1', '1', '1'),
(859, 2, 35, '1', '1', '1', '1'),
(860, 2, 42, '1', '1', '1', '1'),
(861, 2, 43, '1', '1', '1', '1'),
(862, 2, 44, '1', '1', '1', '1'),
(863, 2, 45, '1', '1', '1', '1'),
(864, 2, 46, '1', '1', '1', '1'),
(865, 2, 47, '1', '1', '1', '1'),
(866, 2, 48, '1', '1', '1', '1'),
(867, 2, 56, '1', '1', '1', '1'),
(868, 2, 57, '1', '1', '1', '1'),
(869, 2, 58, '1', '1', '1', '1'),
(870, 2, 59, '1', '1', '1', '1'),
(871, 2, 60, '1', '1', '1', '1'),
(872, 2, 61, '1', '1', '1', '1'),
(873, 2, 62, '1', '1', '1', '1'),
(874, 2, 65, '1', '1', '1', '1'),
(875, 2, 66, '1', '1', '1', '1'),
(876, 2, 67, '1', '1', '1', '1'),
(877, 2, 68, '1', '1', '1', '1'),
(878, 2, 69, '1', '1', '1', '1'),
(879, 2, 70, '1', '1', '1', '1'),
(880, 2, 71, '1', '1', '1', '1'),
(881, 2, 74, '1', '1', '1', '1'),
(882, 2, 75, '1', '1', '1', '1'),
(883, 2, 76, '1', '1', '1', '1'),
(884, 2, 77, '1', '1', '1', '1'),
(885, 2, 78, '1', '1', '1', '1'),
(886, 2, 79, '1', '1', '1', '1'),
(887, 2, 80, '1', '1', '1', '1'),
(888, 2, 93, '1', '1', '1', '1'),
(889, 2, 94, '1', '1', '1', '1'),
(890, 2, 95, '1', '1', '1', '1'),
(891, 2, 96, '1', '1', '1', '1'),
(892, 2, 97, '1', '1', '1', '1'),
(893, 2, 98, '1', '1', '1', '1'),
(894, 2, 99, '1', '1', '1', '1'),
(895, 2, 107, '1', '1', '1', '1'),
(896, 2, 108, '1', '1', '1', '1'),
(897, 2, 109, '1', '1', '1', '1'),
(898, 2, 110, '1', '1', '1', '1'),
(899, 2, 111, '1', '1', '1', '1'),
(900, 2, 112, '1', '1', '1', '1'),
(901, 2, 113, '1', '1', '1', '1'),
(902, 2, 121, '1', '1', '1', '1'),
(903, 2, 122, '1', '1', '1', '1'),
(904, 2, 123, '1', '1', '1', '1'),
(905, 2, 124, '1', '1', '1', '1'),
(906, 2, 125, '1', '1', '1', '1'),
(907, 2, 126, '1', '1', '1', '1'),
(908, 2, 127, '1', '1', '1', '1'),
(909, 2, 131, '1', '1', '1', '1'),
(910, 2, 132, '1', '1', '1', '1'),
(911, 2, 134, '1', '1', '1', '1'),
(912, 2, 135, '1', '1', '1', '1'),
(913, 2, 136, '1', '1', '1', '1'),
(914, 2, 137, '1', '1', '1', '1'),
(915, 2, 138, '1', '1', '1', '1'),
(916, 2, 139, '1', '1', '1', '1'),
(917, 2, 140, '1', '1', '1', '1'),
(918, 2, 147, '1', '1', '1', '1'),
(919, 2, 148, '1', '1', '1', '1'),
(920, 2, 149, '1', '1', '1', '1'),
(921, 2, 150, '1', '1', '1', '1'),
(922, 2, 151, '1', '1', '1', '1'),
(923, 2, 152, '1', '1', '1', '1'),
(924, 2, 153, '1', '1', '1', '1'),
(925, 2, 157, '1', '1', '1', '1'),
(926, 2, 158, '1', '1', '1', '1'),
(927, 2, 159, '1', '1', '1', '1'),
(928, 2, 160, '1', '1', '1', '1'),
(929, 2, 161, '1', '1', '1', '1'),
(930, 2, 162, '1', '1', '1', '1'),
(931, 2, 163, '1', '1', '1', '1'),
(932, 2, 166, '1', '1', '1', '1'),
(933, 2, 167, '1', '1', '1', '1'),
(934, 2, 168, '1', '1', '1', '1'),
(935, 2, 169, '1', '1', '1', '1'),
(936, 2, 170, '1', '1', '1', '1'),
(937, 2, 171, '1', '1', '1', '1'),
(938, 2, 172, '1', '1', '1', '1'),
(939, 2, 178, '1', '1', '1', '1'),
(940, 2, 179, '1', '1', '1', '1'),
(941, 2, 180, '1', '1', '1', '1'),
(942, 2, 181, '1', '1', '1', '1'),
(943, 2, 182, '1', '1', '1', '1'),
(944, 2, 183, '1', '1', '1', '1'),
(945, 2, 184, '1', '1', '1', '1'),
(946, 2, 189, '1', '1', '1', '1'),
(947, 2, 190, '1', '1', '1', '1'),
(948, 2, 191, '1', '1', '1', '1'),
(949, 2, 192, '1', '1', '1', '1'),
(950, 2, 193, '1', '1', '1', '1'),
(951, 2, 194, '1', '1', '1', '1'),
(952, 2, 195, '1', '1', '1', '1'),
(953, 2, 199, '1', '1', '1', '1'),
(954, 2, 200, '1', '1', '1', '1'),
(955, 2, 201, '1', '1', '1', '1'),
(956, 2, 202, '1', '1', '1', '1'),
(957, 2, 203, '1', '1', '1', '1'),
(958, 2, 204, '1', '1', '1', '1'),
(959, 2, 205, '1', '1', '1', '1'),
(960, 2, 210, '1', '1', '1', '1'),
(961, 2, 211, '1', '1', '1', '1'),
(962, 2, 212, '1', '1', '1', '1'),
(963, 2, 213, '1', '1', '1', '1'),
(964, 2, 214, '1', '1', '1', '1'),
(965, 2, 215, '1', '1', '1', '1'),
(966, 2, 216, '1', '1', '1', '1'),
(967, 2, 217, '1', '1', '1', '1'),
(968, 2, 220, '1', '1', '1', '1'),
(969, 2, 221, '1', '1', '1', '1'),
(970, 2, 222, '1', '1', '1', '1'),
(971, 2, 223, '1', '1', '1', '1'),
(972, 2, 224, '1', '1', '1', '1'),
(973, 2, 225, '1', '1', '1', '1'),
(974, 2, 226, '1', '1', '1', '1'),
(975, 2, 228, '1', '1', '1', '1'),
(976, 2, 229, '1', '1', '1', '1'),
(977, 2, 230, '1', '1', '1', '1'),
(978, 2, 231, '1', '1', '1', '1'),
(979, 2, 232, '1', '1', '1', '1'),
(980, 2, 233, '1', '1', '1', '1'),
(981, 2, 234, '1', '1', '1', '1'),
(982, 2, 235, '1', '1', '1', '1'),
(983, 2, 241, '1', '1', '1', '1'),
(984, 2, 242, '1', '1', '1', '1'),
(985, 2, 243, '1', '1', '1', '1'),
(986, 2, 244, '1', '1', '1', '1'),
(987, 2, 245, '1', '1', '1', '1'),
(988, 2, 246, '1', '1', '1', '1'),
(989, 2, 247, '1', '1', '1', '1'),
(990, 2, 248, '1', '1', '1', '1'),
(991, 2, 255, '1', '1', '1', '1'),
(992, 2, 256, '1', '1', '1', '1'),
(993, 2, 257, '1', '1', '1', '1'),
(994, 2, 258, '1', '1', '1', '1'),
(995, 2, 259, '1', '1', '1', '1'),
(996, 2, 260, '1', '1', '1', '1'),
(997, 2, 261, '1', '1', '1', '1'),
(998, 2, 262, '1', '1', '1', '1'),
(999, 2, 263, '1', '1', '1', '1'),
(1000, 2, 264, '1', '1', '1', '1'),
(1001, 2, 265, '1', '1', '1', '1'),
(1002, 2, 268, '1', '1', '1', '1'),
(1003, 2, 269, '1', '1', '1', '1'),
(1004, 2, 270, '1', '1', '1', '1'),
(1005, 2, 271, '1', '1', '1', '1'),
(1006, 2, 272, '1', '1', '1', '1'),
(1007, 2, 273, '1', '1', '1', '1'),
(1008, 2, 274, '1', '1', '1', '1'),
(1009, 2, 281, '1', '1', '1', '1'),
(1010, 2, 282, '1', '1', '1', '1'),
(1011, 2, 283, '1', '1', '1', '1'),
(1012, 2, 284, '1', '1', '1', '1'),
(1013, 2, 285, '1', '1', '1', '1'),
(1014, 2, 286, '1', '1', '1', '1'),
(1015, 2, 287, '1', '1', '1', '1'),
(1016, 2, 294, '1', '1', '1', '1'),
(1017, 2, 295, '1', '1', '1', '1'),
(1018, 2, 296, '1', '1', '1', '1'),
(1019, 2, 297, '1', '1', '1', '1'),
(1020, 2, 298, '1', '1', '1', '1'),
(1021, 2, 299, '1', '1', '1', '1'),
(1022, 2, 300, '1', '1', '1', '1'),
(1023, 2, 311, '1', '1', '1', '1'),
(1024, 2, 312, '1', '1', '1', '1'),
(1025, 2, 313, '1', '1', '1', '1'),
(1026, 2, 314, '1', '1', '1', '1'),
(1027, 2, 315, '1', '1', '1', '1'),
(1028, 2, 316, '1', '1', '1', '1'),
(1029, 2, 317, '1', '1', '1', '1'),
(1030, 2, 349, '1', '1', '1', '1'),
(1031, 2, 350, '1', '1', '1', '1'),
(1032, 2, 351, '1', '1', '1', '1'),
(1033, 2, 352, '1', '1', '1', '1'),
(1034, 2, 353, '1', '1', '1', '1'),
(1035, 2, 354, '1', '1', '1', '1'),
(1036, 2, 355, '1', '1', '1', '1'),
(1037, 2, 366, '1', '1', '1', '1'),
(1038, 2, 367, '1', '1', '1', '1'),
(1039, 2, 368, '1', '1', '1', '1'),
(1040, 2, 369, '1', '1', '1', '1'),
(1041, 2, 370, '1', '1', '1', '1'),
(1042, 2, 371, '1', '1', '1', '1'),
(1043, 2, 372, '1', '1', '1', '1'),
(1044, 2, 377, '1', '1', '1', '1'),
(1045, 2, 378, '1', '1', '1', '1'),
(1046, 2, 379, '1', '1', '1', '1'),
(1047, 2, 380, '1', '1', '1', '1'),
(1048, 2, 381, '1', '1', '1', '1'),
(1049, 2, 382, '1', '1', '1', '1'),
(1050, 2, 383, '1', '1', '1', '1'),
(1051, 2, 389, '1', '1', '1', '1'),
(1052, 2, 390, '1', '1', '1', '1'),
(1053, 2, 391, '1', '1', '1', '1'),
(1054, 2, 392, '1', '1', '1', '1'),
(1055, 2, 393, '1', '1', '1', '1'),
(1056, 2, 394, '1', '1', '1', '1'),
(1057, 2, 395, '1', '1', '1', '1'),
(1058, 2, 398, '1', '1', '1', '1'),
(1059, 2, 399, '1', '1', '1', '1'),
(1060, 2, 400, '1', '1', '1', '1'),
(1061, 2, 401, '1', '1', '1', '1'),
(1062, 2, 402, '1', '1', '1', '1'),
(1063, 2, 403, '1', '1', '1', '1'),
(1064, 2, 404, '1', '1', '1', '1'),
(1065, 2, 412, '1', '1', '1', '1'),
(1066, 2, 413, '1', '1', '1', '1'),
(1067, 2, 414, '1', '1', '1', '1'),
(1068, 2, 415, '1', '1', '1', '1'),
(1069, 2, 416, '1', '1', '1', '1'),
(1070, 2, 417, '1', '1', '1', '1'),
(1071, 2, 418, '1', '1', '1', '1'),
(1072, 2, 422, '1', '1', '1', '1'),
(1073, 2, 423, '1', '1', '1', '1'),
(1074, 2, 424, '1', '1', '1', '1'),
(1075, 2, 425, '1', '1', '1', '1'),
(1076, 2, 426, '1', '1', '1', '1'),
(1077, 2, 427, '1', '1', '1', '1'),
(1078, 2, 428, '1', '1', '1', '1'),
(1079, 2, 431, '1', '1', '1', '1'),
(1080, 2, 432, '1', '1', '1', '1'),
(1081, 2, 433, '1', '1', '1', '1'),
(1082, 2, 434, '1', '1', '1', '1'),
(1083, 2, 435, '1', '1', '1', '1'),
(1084, 2, 436, '1', '1', '1', '1'),
(1085, 2, 437, '1', '1', '1', '1'),
(1086, 2, 442, '1', '1', '1', '1'),
(1087, 2, 443, '1', '1', '1', '1'),
(1088, 2, 444, '1', '1', '1', '1'),
(1089, 2, 445, '1', '1', '1', '1'),
(1090, 2, 446, '1', '1', '1', '1'),
(1091, 2, 447, '1', '1', '1', '1'),
(1092, 2, 448, '1', '1', '1', '1'),
(1093, 2, 451, '1', '1', '1', '1'),
(1094, 2, 452, '1', '1', '1', '1'),
(1095, 2, 453, '1', '1', '1', '1'),
(1096, 2, 454, '1', '1', '1', '1'),
(1097, 2, 455, '1', '1', '1', '1'),
(1098, 2, 456, '1', '1', '1', '1'),
(1099, 2, 457, '1', '1', '1', '1'),
(1100, 2, 458, '1', '1', '1', '1'),
(1101, 2, 460, '1', '1', '1', '1'),
(1102, 2, 461, '1', '1', '1', '1'),
(1103, 2, 462, '1', '1', '1', '1'),
(1104, 2, 463, '1', '1', '1', '1'),
(1105, 2, 464, '1', '1', '1', '1'),
(1106, 2, 465, '1', '1', '1', '1'),
(1107, 2, 466, '1', '1', '1', '1'),
(1108, 2, 467, '1', '1', '1', '1'),
(1109, 2, 468, '1', '1', '1', '1'),
(1110, 2, 472, '1', '1', '1', '1'),
(1111, 2, 473, '1', '1', '1', '1'),
(1112, 2, 474, '1', '1', '1', '1'),
(1113, 2, 475, '1', '1', '1', '1'),
(1114, 2, 476, '1', '1', '1', '1'),
(1115, 2, 477, '1', '1', '1', '1'),
(1116, 2, 478, '1', '1', '1', '1'),
(1117, 2, 479, '1', '1', '1', '1'),
(1118, 2, 482, '1', '1', '1', '1'),
(1119, 2, 483, '1', '1', '1', '1'),
(1120, 2, 484, '1', '1', '1', '1'),
(1121, 2, 485, '1', '1', '1', '1'),
(1122, 2, 486, '1', '1', '1', '1'),
(1123, 2, 487, '1', '1', '1', '1'),
(1124, 2, 488, '1', '1', '1', '1'),
(1125, 2, 492, '1', '1', '1', '1'),
(1126, 2, 493, '1', '1', '1', '1'),
(1127, 2, 494, '1', '1', '1', '1'),
(1128, 2, 495, '1', '1', '1', '1'),
(1129, 2, 496, '1', '1', '1', '1'),
(1130, 2, 497, '1', '1', '1', '1'),
(1131, 2, 498, '1', '1', '1', '1'),
(1132, 2, 500, '1', '1', '1', '1'),
(1133, 2, 501, '1', '1', '1', '1'),
(1134, 2, 502, '1', '1', '1', '1'),
(1135, 2, 503, '1', '1', '1', '1'),
(1136, 2, 504, '1', '1', '1', '1'),
(1137, 2, 505, '1', '1', '1', '1'),
(1138, 2, 506, '1', '1', '1', '1'),
(1139, 2, 507, '1', '1', '1', '1'),
(1140, 2, 508, '1', '1', '1', '1'),
(1141, 2, 510, '1', '1', '1', '1'),
(1142, 2, 511, '1', '1', '1', '1'),
(1143, 2, 512, '1', '1', '1', '1'),
(1144, 2, 513, '1', '1', '1', '1'),
(1145, 2, 514, '1', '1', '1', '1'),
(1146, 2, 515, '1', '1', '1', '1'),
(1147, 2, 516, '1', '1', '1', '1'),
(1148, 2, 517, '1', '1', '1', '1'),
(1149, 2, 518, '1', '1', '1', '1'),
(1150, 2, 519, '1', '1', '1', '1'),
(1151, 2, 522, '1', '1', '1', '1'),
(1152, 2, 523, '1', '1', '1', '1'),
(1153, 2, 524, '1', '1', '1', '1'),
(1154, 2, 525, '1', '1', '1', '1'),
(1155, 2, 526, '1', '1', '1', '1'),
(1156, 2, 527, '1', '1', '1', '1'),
(1157, 2, 528, '1', '1', '1', '1'),
(1158, 2, 535, '1', '1', '1', '1'),
(1159, 2, 536, '1', '1', '1', '1'),
(1160, 2, 537, '1', '1', '1', '1'),
(1161, 2, 538, '1', '1', '1', '1'),
(1162, 2, 539, '1', '1', '1', '1'),
(1163, 2, 540, '1', '1', '1', '1'),
(1164, 2, 541, '1', '1', '1', '1'),
(1165, 2, 548, '1', '1', '1', '1'),
(1166, 2, 549, '1', '1', '1', '1'),
(1167, 2, 550, '1', '1', '1', '1'),
(1168, 2, 551, '1', '1', '1', '1'),
(1169, 2, 552, '1', '1', '1', '1'),
(1170, 2, 553, '1', '1', '1', '1'),
(1171, 2, 554, '1', '1', '1', '1'),
(1172, 2, 564, '1', '1', '1', '1'),
(1173, 2, 565, '1', '1', '1', '1'),
(1174, 2, 566, '1', '1', '1', '1'),
(1175, 2, 567, '1', '1', '1', '1'),
(1176, 2, 568, '1', '1', '1', '1'),
(1177, 2, 569, '1', '1', '1', '1'),
(1178, 2, 570, '1', '1', '1', '1'),
(1179, 2, 595, '1', '1', '1', '1'),
(1180, 2, 596, '1', '1', '1', '1'),
(1181, 2, 597, '1', '1', '1', '1'),
(1182, 2, 598, '1', '1', '1', '1'),
(1183, 2, 599, '1', '1', '1', '1'),
(1184, 2, 600, '1', '1', '1', '1'),
(1185, 2, 604, '1', '1', '1', '1'),
(1186, 2, 605, '1', '1', '1', '1'),
(1187, 2, 606, '1', '1', '1', '1'),
(1188, 2, 607, '1', '1', '1', '1'),
(1189, 2, 608, '1', '1', '1', '1'),
(1190, 2, 609, '1', '1', '1', '1'),
(1191, 2, 610, '1', '1', '1', '1'),
(1192, 2, 620, '1', '1', '1', '1'),
(1193, 2, 621, '1', '1', '1', '1'),
(1194, 2, 622, '1', '1', '1', '1'),
(1195, 2, 623, '1', '1', '1', '1'),
(1196, 2, 624, '1', '1', '1', '1'),
(1197, 2, 625, '1', '1', '1', '1'),
(1198, 2, 626, '1', '1', '1', '1'),
(1199, 2, 641, '1', '1', '1', '1'),
(1200, 2, 642, '1', '1', '1', '1'),
(1201, 2, 643, '1', '1', '1', '1'),
(1202, 2, 644, '1', '1', '1', '1'),
(1203, 2, 645, '1', '1', '1', '1'),
(1204, 2, 646, '1', '1', '1', '1'),
(1205, 2, 647, '1', '1', '1', '1'),
(1206, 2, 651, '1', '1', '1', '1'),
(1207, 2, 652, '1', '1', '1', '1'),
(1208, 2, 653, '1', '1', '1', '1'),
(1209, 2, 654, '1', '1', '1', '1'),
(1210, 2, 655, '1', '1', '1', '1'),
(1211, 2, 656, '1', '1', '1', '1'),
(1212, 2, 657, '1', '1', '1', '1'),
(1213, 2, 660, '1', '1', '1', '1'),
(1214, 2, 661, '1', '1', '1', '1'),
(1215, 2, 663, '1', '1', '1', '1'),
(1216, 2, 664, '1', '1', '1', '1'),
(1217, 2, 665, '1', '1', '1', '1'),
(1218, 2, 666, '1', '1', '1', '1'),
(1219, 2, 667, '1', '1', '1', '1'),
(1220, 2, 668, '1', '1', '1', '1'),
(1221, 2, 669, '1', '1', '1', '1'),
(1222, 2, 672, '1', '1', '1', '1'),
(1223, 2, 673, '1', '1', '1', '1'),
(1224, 2, 674, '1', '1', '1', '1'),
(1225, 2, 675, '1', '1', '1', '1'),
(1226, 2, 676, '1', '1', '1', '1'),
(1227, 2, 677, '1', '1', '1', '1'),
(1228, 2, 678, '1', '1', '1', '1'),
(1229, 2, 685, '1', '1', '1', '1'),
(1230, 2, 686, '1', '1', '1', '1'),
(1231, 2, 687, '1', '1', '1', '1'),
(1232, 2, 688, '1', '1', '1', '1'),
(1233, 2, 689, '1', '1', '1', '1'),
(1234, 2, 690, '1', '1', '1', '1'),
(1235, 2, 691, '1', '1', '1', '1'),
(1236, 2, 696, '1', '1', '1', '1'),
(1237, 2, 697, '1', '1', '1', '1'),
(1238, 2, 698, '1', '1', '1', '1'),
(1239, 2, 699, '1', '1', '1', '1'),
(1240, 2, 700, '1', '1', '1', '1'),
(1241, 2, 701, '1', '1', '1', '1'),
(1242, 2, 702, '1', '1', '1', '1'),
(1243, 2, 705, '1', '1', '1', '1'),
(1244, 2, 706, '1', '1', '1', '1'),
(1245, 2, 707, '1', '1', '1', '1'),
(1246, 2, 708, '1', '1', '1', '1'),
(1247, 2, 709, '1', '1', '1', '1'),
(1248, 2, 710, '1', '1', '1', '1'),
(1249, 2, 711, '1', '1', '1', '1'),
(1250, 2, 718, '1', '1', '1', '1'),
(1251, 2, 719, '1', '1', '1', '1'),
(1252, 2, 720, '1', '1', '1', '1'),
(1253, 2, 721, '1', '1', '1', '1'),
(1254, 2, 722, '1', '1', '1', '1'),
(1255, 2, 723, '1', '1', '1', '1'),
(1256, 2, 724, '1', '1', '1', '1'),
(1257, 2, 733, '1', '1', '1', '1'),
(1258, 2, 734, '1', '1', '1', '1'),
(1259, 2, 735, '1', '1', '1', '1'),
(1260, 2, 736, '1', '1', '1', '1'),
(1261, 2, 737, '1', '1', '1', '1'),
(1262, 2, 738, '1', '1', '1', '1'),
(1263, 2, 739, '1', '1', '1', '1'),
(1264, 2, 745, '1', '1', '1', '1'),
(1265, 2, 746, '1', '1', '1', '1'),
(1266, 2, 747, '1', '1', '1', '1'),
(1267, 2, 748, '1', '1', '1', '1'),
(1268, 2, 749, '1', '1', '1', '1'),
(1269, 2, 750, '1', '1', '1', '1'),
(1270, 2, 751, '1', '1', '1', '1'),
(1271, 2, 759, '1', '1', '1', '1'),
(1272, 2, 760, '1', '1', '1', '1'),
(1273, 2, 761, '1', '1', '1', '1'),
(1274, 2, 762, '1', '1', '1', '1'),
(1275, 2, 763, '1', '1', '1', '1'),
(1276, 2, 764, '1', '1', '1', '1'),
(1277, 2, 765, '1', '1', '1', '1'),
(1278, 2, 825, '1', '1', '1', '1'),
(1279, 2, 826, '1', '1', '1', '1'),
(1280, 2, 827, '1', '1', '1', '1'),
(1281, 2, 828, '1', '1', '1', '1'),
(1282, 2, 829, '1', '1', '1', '1'),
(1283, 2, 830, '1', '1', '1', '1'),
(1284, 2, 831, '1', '1', '1', '1'),
(1285, 2, 871, '1', '1', '1', '1'),
(1286, 2, 872, '1', '1', '1', '1'),
(1287, 2, 873, '1', '1', '1', '1'),
(1288, 2, 874, '1', '1', '1', '1'),
(1289, 2, 875, '1', '1', '1', '1'),
(1290, 2, 876, '1', '1', '1', '1'),
(1291, 2, 877, '1', '1', '1', '1'),
(1292, 2, 880, '1', '1', '1', '1'),
(1293, 2, 881, '1', '1', '1', '1'),
(1294, 2, 882, '1', '1', '1', '1'),
(1295, 2, 883, '1', '1', '1', '1'),
(1296, 2, 884, '1', '1', '1', '1'),
(1297, 2, 885, '1', '1', '1', '1'),
(1298, 2, 886, '1', '1', '1', '1'),
(1299, 1, 3, '1', '1', '1', '1'),
(1300, 1, 4, '1', '1', '1', '1'),
(1301, 1, 13, '1', '1', '1', '1'),
(1302, 1, 14, '1', '1', '1', '1'),
(1303, 1, 887, '1', '1', '1', '1'),
(1304, 1, 23, '1', '1', '1', '1'),
(1305, 1, 24, '1', '1', '1', '1'),
(1306, 1, 25, '1', '1', '1', '1'),
(1307, 1, 26, '1', '1', '1', '1'),
(1308, 1, 27, '1', '1', '1', '1'),
(1309, 1, 28, '1', '1', '1', '1'),
(1310, 1, 37, '1', '1', '1', '1'),
(1311, 1, 38, '1', '1', '1', '1'),
(1312, 1, 50, '1', '1', '1', '1'),
(1313, 1, 51, '1', '1', '1', '1'),
(1314, 1, 54, '1', '1', '1', '1'),
(1315, 1, 52, '1', '1', '1', '1'),
(1316, 1, 53, '1', '1', '1', '1'),
(1317, 1, 55, '1', '1', '1', '1'),
(1318, 1, 64, '1', '1', '1', '1'),
(1319, 1, 73, '1', '1', '1', '1'),
(1320, 1, 82, '1', '1', '1', '1'),
(1321, 1, 83, '1', '1', '1', '1'),
(1322, 1, 84, '1', '1', '1', '1'),
(1323, 1, 85, '1', '1', '1', '1'),
(1324, 1, 86, '1', '1', '1', '1'),
(1325, 1, 90, '1', '1', '1', '1'),
(1326, 1, 91, '1', '1', '1', '1'),
(1327, 1, 87, '1', '1', '1', '1'),
(1328, 1, 88, '1', '1', '1', '1'),
(1329, 1, 89, '1', '1', '1', '1'),
(1330, 1, 92, '1', '1', '1', '1'),
(1331, 1, 101, '1', '1', '1', '1'),
(1332, 1, 102, '1', '1', '1', '1'),
(1333, 1, 104, '1', '1', '1', '1'),
(1334, 1, 103, '1', '1', '1', '1'),
(1335, 1, 105, '1', '1', '1', '1'),
(1336, 1, 106, '1', '1', '1', '1'),
(1337, 1, 115, '1', '1', '1', '1'),
(1338, 1, 116, '1', '1', '1', '1'),
(1339, 1, 120, '1', '1', '1', '1'),
(1340, 1, 117, '1', '1', '1', '1'),
(1341, 1, 118, '1', '1', '1', '1'),
(1342, 1, 119, '1', '1', '1', '1'),
(1343, 1, 888, '1', '1', '1', '1'),
(1344, 1, 129, '1', '1', '1', '1'),
(1345, 1, 130, '1', '1', '1', '1'),
(1346, 1, 133, '1', '1', '1', '1'),
(1347, 1, 142, '1', '1', '1', '1'),
(1348, 1, 143, '1', '1', '1', '1'),
(1349, 1, 146, '1', '1', '1', '1'),
(1350, 1, 144, '1', '1', '1', '1'),
(1351, 1, 145, '1', '1', '1', '1'),
(1352, 1, 155, '1', '1', '1', '1'),
(1353, 1, 156, '1', '1', '1', '1'),
(1354, 1, 165, '1', '1', '1', '1'),
(1355, 1, 174, '1', '1', '1', '1'),
(1356, 1, 175, '1', '1', '1', '1'),
(1357, 1, 176, '1', '1', '1', '1'),
(1358, 1, 177, '1', '1', '1', '1'),
(1359, 1, 186, '1', '1', '1', '1'),
(1360, 1, 187, '1', '1', '1', '1'),
(1361, 1, 188, '1', '1', '1', '1'),
(1362, 1, 197, '1', '1', '1', '1'),
(1363, 1, 198, '1', '1', '1', '1'),
(1364, 1, 207, '1', '1', '1', '1'),
(1365, 1, 209, '1', '1', '1', '1'),
(1366, 1, 208, '1', '1', '1', '1'),
(1367, 1, 219, '1', '1', '1', '1'),
(1368, 1, 920, '1', '1', '1', '1'),
(1369, 1, 921, '1', '1', '1', '1'),
(1370, 1, 922, '1', '1', '1', '1'),
(1371, 1, 237, '1', '1', '1', '1'),
(1372, 1, 238, '1', '1', '1', '1'),
(1373, 1, 239, '1', '1', '1', '1'),
(1374, 1, 240, '1', '1', '1', '1'),
(1375, 1, 250, '1', '1', '1', '1'),
(1376, 1, 251, '1', '1', '1', '1'),
(1377, 1, 253, '1', '1', '1', '1'),
(1378, 1, 254, '1', '1', '1', '1'),
(1379, 1, 252, '1', '1', '1', '1'),
(1380, 1, 267, '1', '1', '1', '1'),
(1381, 1, 276, '1', '1', '1', '1'),
(1382, 1, 277, '1', '1', '1', '1'),
(1383, 1, 280, '1', '1', '1', '1'),
(1384, 1, 278, '1', '1', '1', '1'),
(1385, 1, 279, '1', '1', '1', '1'),
(1386, 1, 289, '1', '1', '1', '1'),
(1387, 1, 290, '1', '1', '1', '1'),
(1388, 1, 293, '1', '1', '1', '1'),
(1389, 1, 291, '1', '1', '1', '1'),
(1390, 1, 292, '1', '1', '1', '1'),
(1391, 1, 302, '1', '1', '1', '1'),
(1392, 1, 310, '1', '1', '1', '1'),
(1393, 1, 303, '1', '1', '1', '1'),
(1394, 1, 304, '1', '1', '1', '1'),
(1395, 1, 306, '1', '1', '1', '1'),
(1396, 1, 305, '1', '1', '1', '1'),
(1397, 1, 308, '1', '1', '1', '1'),
(1398, 1, 307, '1', '1', '1', '1'),
(1399, 1, 309, '1', '1', '1', '1'),
(1400, 1, 319, '1', '1', '1', '1'),
(1401, 1, 343, '1', '1', '1', '1'),
(1402, 1, 344, '1', '1', '1', '1'),
(1403, 1, 320, '1', '1', '1', '1'),
(1404, 1, 321, '1', '1', '1', '1'),
(1405, 1, 334, '1', '1', '1', '1'),
(1406, 1, 335, '1', '1', '1', '1'),
(1407, 1, 336, '1', '1', '1', '1'),
(1408, 1, 337, '1', '1', '1', '1'),
(1409, 1, 338, '1', '1', '1', '1'),
(1410, 1, 339, '1', '1', '1', '1'),
(1411, 1, 340, '1', '1', '1', '1'),
(1412, 1, 341, '1', '1', '1', '1'),
(1413, 1, 342, '1', '1', '1', '1'),
(1414, 1, 346, '1', '1', '1', '1'),
(1415, 1, 347, '1', '1', '1', '1'),
(1416, 1, 322, '1', '1', '1', '1'),
(1417, 1, 323, '1', '1', '1', '1'),
(1418, 1, 324, '1', '1', '1', '1'),
(1419, 1, 325, '1', '1', '1', '1'),
(1420, 1, 326, '1', '1', '1', '1'),
(1421, 1, 327, '1', '1', '1', '1'),
(1422, 1, 328, '1', '1', '1', '1'),
(1423, 1, 329, '1', '1', '1', '1'),
(1424, 1, 330, '1', '1', '1', '1'),
(1425, 1, 331, '1', '1', '1', '1'),
(1426, 1, 332, '1', '1', '1', '1'),
(1427, 1, 333, '1', '1', '1', '1'),
(1428, 1, 345, '1', '1', '1', '1'),
(1429, 1, 348, '1', '1', '1', '1'),
(1430, 1, 357, '1', '1', '1', '1'),
(1431, 1, 358, '1', '1', '1', '1'),
(1432, 1, 361, '1', '1', '1', '1'),
(1433, 1, 362, '1', '1', '1', '1'),
(1434, 1, 363, '1', '1', '1', '1'),
(1435, 1, 365, '1', '1', '1', '1'),
(1436, 1, 359, '1', '1', '1', '1'),
(1437, 1, 360, '1', '1', '1', '1'),
(1438, 1, 364, '1', '1', '1', '1'),
(1439, 1, 374, '1', '1', '1', '1'),
(1440, 1, 375, '1', '1', '1', '1'),
(1441, 1, 376, '1', '1', '1', '1'),
(1442, 1, 385, '1', '1', '1', '1'),
(1443, 1, 386, '1', '1', '1', '1'),
(1444, 1, 387, '1', '1', '1', '1'),
(1445, 1, 388, '1', '1', '1', '1'),
(1446, 1, 397, '1', '1', '1', '1'),
(1447, 1, 406, '1', '1', '1', '1'),
(1448, 1, 407, '1', '1', '1', '1'),
(1449, 1, 408, '1', '1', '1', '1'),
(1450, 1, 409, '1', '1', '1', '1'),
(1451, 1, 410, '1', '1', '1', '1'),
(1452, 1, 411, '1', '1', '1', '1'),
(1453, 1, 420, '1', '1', '1', '1'),
(1454, 1, 421, '1', '1', '1', '1'),
(1455, 1, 430, '1', '1', '1', '1'),
(1456, 1, 439, '1', '1', '1', '1'),
(1457, 1, 440, '1', '1', '1', '1'),
(1458, 1, 441, '1', '1', '1', '1'),
(1459, 1, 450, '1', '1', '1', '1'),
(1460, 1, 470, '1', '1', '1', '1'),
(1461, 1, 471, '1', '1', '1', '1'),
(1462, 1, 481, '1', '1', '1', '1'),
(1463, 1, 490, '1', '1', '1', '1'),
(1464, 1, 491, '1', '1', '1', '1'),
(1465, 1, 521, '1', '1', '1', '1'),
(1466, 1, 530, '1', '1', '1', '1'),
(1467, 1, 531, '1', '1', '1', '1'),
(1468, 1, 532, '1', '1', '1', '1'),
(1469, 1, 534, '1', '1', '1', '1'),
(1470, 1, 533, '1', '1', '1', '1'),
(1471, 1, 543, '1', '1', '1', '1'),
(1472, 1, 544, '1', '1', '1', '1'),
(1473, 1, 547, '1', '1', '1', '1'),
(1474, 1, 545, '1', '1', '1', '1'),
(1475, 1, 546, '1', '1', '1', '1'),
(1476, 1, 556, '1', '1', '1', '1'),
(1477, 1, 563, '1', '1', '1', '1'),
(1478, 1, 557, '1', '1', '1', '1'),
(1479, 1, 559, '1', '1', '1', '1'),
(1480, 1, 558, '1', '1', '1', '1'),
(1481, 1, 562, '1', '1', '1', '1'),
(1482, 1, 560, '1', '1', '1', '1'),
(1483, 1, 561, '1', '1', '1', '1'),
(1484, 1, 572, '1', '1', '1', '1'),
(1485, 1, 602, '1', '1', '1', '1'),
(1486, 1, 590, '1', '1', '1', '1'),
(1487, 1, 573, '1', '1', '1', '1'),
(1488, 1, 574, '1', '1', '1', '1'),
(1489, 1, 575, '1', '1', '1', '1'),
(1490, 1, 583, '1', '1', '1', '1'),
(1491, 1, 584, '1', '1', '1', '1'),
(1492, 1, 585, '1', '1', '1', '1'),
(1493, 1, 586, '1', '1', '1', '1'),
(1494, 1, 587, '1', '1', '1', '1'),
(1495, 1, 588, '1', '1', '1', '1'),
(1496, 1, 589, '1', '1', '1', '1'),
(1497, 1, 591, '1', '1', '1', '1'),
(1498, 1, 576, '1', '1', '1', '1'),
(1499, 1, 577, '1', '1', '1', '1'),
(1500, 1, 578, '1', '1', '1', '1'),
(1501, 1, 579, '1', '1', '1', '1'),
(1502, 1, 580, '1', '1', '1', '1'),
(1503, 1, 581, '1', '1', '1', '1'),
(1504, 1, 582, '1', '1', '1', '1'),
(1505, 1, 592, '1', '1', '1', '1'),
(1506, 1, 593, '1', '1', '1', '1'),
(1507, 1, 601, '1', '1', '1', '1'),
(1508, 1, 594, '1', '1', '1', '1'),
(1509, 1, 603, '1', '1', '1', '1'),
(1510, 1, 612, '1', '1', '1', '1'),
(1511, 1, 617, '1', '1', '1', '1'),
(1512, 1, 619, '1', '1', '1', '1'),
(1513, 1, 613, '1', '1', '1', '1'),
(1514, 1, 614, '1', '1', '1', '1'),
(1515, 1, 615, '1', '1', '1', '1'),
(1516, 1, 616, '1', '1', '1', '1'),
(1517, 1, 618, '1', '1', '1', '1'),
(1518, 1, 923, '1', '1', '1', '1'),
(1519, 1, 628, '1', '1', '1', '1'),
(1520, 1, 629, '1', '1', '1', '1'),
(1521, 1, 633, '1', '1', '1', '1'),
(1522, 1, 634, '1', '1', '1', '1'),
(1523, 1, 635, '1', '1', '1', '1'),
(1524, 1, 636, '1', '1', '1', '1'),
(1525, 1, 637, '1', '1', '1', '1'),
(1526, 1, 638, '1', '1', '1', '1'),
(1527, 1, 639, '1', '1', '1', '1'),
(1528, 1, 640, '1', '1', '1', '1'),
(1529, 1, 630, '1', '1', '1', '1'),
(1530, 1, 631, '1', '1', '1', '1'),
(1531, 1, 632, '1', '1', '1', '1'),
(1532, 1, 649, '1', '1', '1', '1'),
(1533, 1, 650, '1', '1', '1', '1'),
(1534, 1, 659, '1', '1', '1', '1'),
(1535, 1, 662, '1', '1', '1', '1'),
(1536, 1, 671, '1', '1', '1', '1'),
(1537, 1, 680, '1', '1', '1', '1'),
(1538, 1, 681, '1', '1', '1', '1'),
(1539, 1, 682, '1', '1', '1', '1'),
(1540, 1, 683, '1', '1', '1', '1'),
(1541, 1, 684, '1', '1', '1', '1'),
(1542, 1, 693, '1', '1', '1', '1'),
(1543, 1, 694, '1', '1', '1', '1'),
(1544, 1, 695, '1', '1', '1', '1'),
(1545, 1, 704, '1', '1', '1', '1'),
(1546, 1, 713, '1', '1', '1', '1'),
(1547, 1, 714, '1', '1', '1', '1'),
(1548, 1, 715, '1', '1', '1', '1'),
(1549, 1, 716, '1', '1', '1', '1'),
(1550, 1, 717, '1', '1', '1', '1'),
(1551, 1, 726, '1', '1', '1', '1'),
(1552, 1, 727, '1', '1', '1', '1'),
(1553, 1, 728, '1', '1', '1', '1'),
(1554, 1, 729, '1', '1', '1', '1'),
(1555, 1, 730, '1', '1', '1', '1'),
(1556, 1, 731, '1', '1', '1', '1'),
(1557, 1, 732, '1', '1', '1', '1'),
(1558, 1, 741, '1', '1', '1', '1'),
(1559, 1, 742, '1', '1', '1', '1'),
(1560, 1, 743, '1', '1', '1', '1'),
(1561, 1, 744, '1', '1', '1', '1'),
(1562, 1, 753, '1', '1', '1', '1'),
(1563, 1, 754, '1', '1', '1', '1'),
(1564, 1, 755, '1', '1', '1', '1'),
(1565, 1, 757, '1', '1', '1', '1'),
(1566, 1, 756, '1', '1', '1', '1'),
(1567, 1, 758, '1', '1', '1', '1'),
(1569, 1, 810, '1', '1', '1', '1'),
(1570, 1, 824, '1', '1', '1', '1'),
(1571, 1, 833, '1', '1', '1', '1'),
(1572, 1, 845, '1', '1', '1', '1'),
(1573, 1, 857, '1', '1', '1', '1'),
(1574, 1, 869, '1', '1', '1', '1'),
(1575, 1, 870, '1', '1', '1', '1'),
(1576, 1, 879, '1', '1', '1', '1'),
(1577, 1, 890, '1', '1', '1', '1'),
(1578, 1, 891, '1', '1', '1', '1'),
(1579, 1, 892, '1', '1', '1', '1'),
(1580, 1, 893, '1', '1', '1', '1'),
(1581, 1, 894, '1', '1', '1', '1'),
(1582, 1, 895, '1', '1', '1', '1'),
(1583, 1, 896, '1', '1', '1', '1'),
(1584, 1, 897, '1', '1', '1', '1'),
(1585, 1, 898, '1', '1', '1', '1'),
(1586, 1, 899, '1', '1', '1', '1'),
(1587, 1, 900, '1', '1', '1', '1'),
(1588, 1, 901, '1', '1', '1', '1'),
(1589, 1, 902, '1', '1', '1', '1'),
(1590, 1, 903, '1', '1', '1', '1'),
(1591, 1, 904, '1', '1', '1', '1'),
(1592, 1, 905, '1', '1', '1', '1'),
(1593, 1, 906, '1', '1', '1', '1'),
(1594, 1, 907, '1', '1', '1', '1'),
(1595, 1, 908, '1', '1', '1', '1'),
(1596, 1, 909, '1', '1', '1', '1'),
(1597, 1, 910, '1', '1', '1', '1'),
(1598, 1, 911, '1', '1', '1', '1'),
(1599, 1, 912, '1', '1', '1', '1'),
(1600, 1, 5, '1', '1', '1', '1'),
(1601, 1, 6, '1', '1', '1', '1'),
(1602, 1, 7, '1', '1', '1', '1'),
(1603, 1, 8, '1', '1', '1', '1'),
(1604, 1, 9, '1', '1', '1', '1'),
(1605, 1, 10, '1', '1', '1', '1'),
(1606, 1, 11, '1', '1', '1', '1'),
(1607, 1, 15, '1', '1', '1', '1'),
(1608, 1, 16, '1', '1', '1', '1'),
(1609, 1, 17, '1', '1', '1', '1'),
(1610, 1, 18, '1', '1', '1', '1'),
(1611, 1, 19, '1', '1', '1', '1'),
(1612, 1, 20, '1', '1', '1', '1'),
(1613, 1, 21, '1', '1', '1', '1'),
(1614, 1, 29, '1', '1', '1', '1'),
(1615, 1, 30, '1', '1', '1', '1'),
(1616, 1, 31, '1', '1', '1', '1'),
(1617, 1, 32, '1', '1', '1', '1'),
(1618, 1, 33, '1', '1', '1', '1'),
(1619, 1, 34, '1', '1', '1', '1'),
(1620, 1, 35, '1', '1', '1', '1'),
(1621, 1, 42, '1', '1', '1', '1'),
(1622, 1, 43, '1', '1', '1', '1'),
(1623, 1, 44, '1', '1', '1', '1'),
(1624, 1, 45, '1', '1', '1', '1'),
(1625, 1, 46, '1', '1', '1', '1'),
(1626, 1, 47, '1', '1', '1', '1'),
(1627, 1, 48, '1', '1', '1', '1'),
(1628, 1, 56, '1', '1', '1', '1'),
(1629, 1, 57, '1', '1', '1', '1'),
(1630, 1, 58, '1', '1', '1', '1'),
(1631, 1, 59, '1', '1', '1', '1'),
(1632, 1, 60, '1', '1', '1', '1'),
(1633, 1, 61, '1', '1', '1', '1'),
(1634, 1, 62, '1', '1', '1', '1'),
(1635, 1, 65, '1', '1', '1', '1'),
(1636, 1, 66, '1', '1', '1', '1'),
(1637, 1, 67, '1', '1', '1', '1'),
(1638, 1, 68, '1', '1', '1', '1'),
(1639, 1, 69, '1', '1', '1', '1'),
(1640, 1, 70, '1', '1', '1', '1'),
(1641, 1, 71, '1', '1', '1', '1'),
(1642, 1, 74, '1', '1', '1', '1'),
(1643, 1, 75, '1', '1', '1', '1'),
(1644, 1, 76, '1', '1', '1', '1'),
(1645, 1, 77, '1', '1', '1', '1'),
(1646, 1, 78, '1', '1', '1', '1'),
(1647, 1, 79, '1', '1', '1', '1'),
(1648, 1, 80, '1', '1', '1', '1'),
(1649, 1, 93, '1', '1', '1', '1'),
(1650, 1, 94, '1', '1', '1', '1'),
(1651, 1, 95, '1', '1', '1', '1'),
(1652, 1, 96, '1', '1', '1', '1'),
(1653, 1, 97, '1', '1', '1', '1'),
(1654, 1, 98, '1', '1', '1', '1'),
(1655, 1, 99, '1', '1', '1', '1'),
(1656, 1, 107, '1', '1', '1', '1'),
(1657, 1, 108, '1', '1', '1', '1'),
(1658, 1, 109, '1', '1', '1', '1'),
(1659, 1, 110, '1', '1', '1', '1'),
(1660, 1, 111, '1', '1', '1', '1'),
(1661, 1, 112, '1', '1', '1', '1'),
(1662, 1, 113, '1', '1', '1', '1'),
(1663, 1, 121, '1', '1', '1', '1'),
(1664, 1, 122, '1', '1', '1', '1'),
(1665, 1, 123, '1', '1', '1', '1'),
(1666, 1, 124, '1', '1', '1', '1'),
(1667, 1, 125, '1', '1', '1', '1'),
(1668, 1, 126, '1', '1', '1', '1'),
(1669, 1, 127, '1', '1', '1', '1'),
(1670, 1, 131, '1', '1', '1', '1'),
(1671, 1, 132, '1', '1', '1', '1'),
(1672, 1, 134, '1', '1', '1', '1'),
(1673, 1, 135, '1', '1', '1', '1'),
(1674, 1, 136, '1', '1', '1', '1'),
(1675, 1, 137, '1', '1', '1', '1'),
(1676, 1, 138, '1', '1', '1', '1'),
(1677, 1, 139, '1', '1', '1', '1'),
(1678, 1, 140, '1', '1', '1', '1'),
(1679, 1, 147, '1', '1', '1', '1'),
(1680, 1, 148, '1', '1', '1', '1'),
(1681, 1, 149, '1', '1', '1', '1'),
(1682, 1, 150, '1', '1', '1', '1'),
(1683, 1, 151, '1', '1', '1', '1'),
(1684, 1, 152, '1', '1', '1', '1'),
(1685, 1, 153, '1', '1', '1', '1'),
(1686, 1, 157, '1', '1', '1', '1'),
(1687, 1, 158, '1', '1', '1', '1'),
(1688, 1, 159, '1', '1', '1', '1'),
(1689, 1, 160, '1', '1', '1', '1'),
(1690, 1, 161, '1', '1', '1', '1'),
(1691, 1, 162, '1', '1', '1', '1'),
(1692, 1, 163, '1', '1', '1', '1'),
(1693, 1, 166, '1', '1', '1', '1'),
(1694, 1, 167, '1', '1', '1', '1'),
(1695, 1, 168, '1', '1', '1', '1'),
(1696, 1, 169, '1', '1', '1', '1'),
(1697, 1, 170, '1', '1', '1', '1'),
(1698, 1, 171, '1', '1', '1', '1'),
(1699, 1, 172, '1', '1', '1', '1'),
(1700, 1, 178, '1', '1', '1', '1'),
(1701, 1, 179, '1', '1', '1', '1'),
(1702, 1, 180, '1', '1', '1', '1'),
(1703, 1, 181, '1', '1', '1', '1'),
(1704, 1, 182, '1', '1', '1', '1'),
(1705, 1, 183, '1', '1', '1', '1'),
(1706, 1, 184, '1', '1', '1', '1'),
(1707, 1, 189, '1', '1', '1', '1'),
(1708, 1, 190, '1', '1', '1', '1'),
(1709, 1, 191, '1', '1', '1', '1'),
(1710, 1, 192, '1', '1', '1', '1'),
(1711, 1, 193, '1', '1', '1', '1'),
(1712, 1, 194, '1', '1', '1', '1'),
(1713, 1, 195, '1', '1', '1', '1'),
(1714, 1, 199, '1', '1', '1', '1'),
(1715, 1, 200, '1', '1', '1', '1'),
(1716, 1, 201, '1', '1', '1', '1'),
(1717, 1, 202, '1', '1', '1', '1'),
(1718, 1, 203, '1', '1', '1', '1'),
(1719, 1, 204, '1', '1', '1', '1'),
(1720, 1, 205, '1', '1', '1', '1'),
(1721, 1, 210, '1', '1', '1', '1'),
(1722, 1, 211, '1', '1', '1', '1'),
(1723, 1, 212, '1', '1', '1', '1'),
(1724, 1, 213, '1', '1', '1', '1'),
(1725, 1, 214, '1', '1', '1', '1'),
(1726, 1, 215, '1', '1', '1', '1'),
(1727, 1, 216, '1', '1', '1', '1'),
(1728, 1, 217, '1', '1', '1', '1'),
(1729, 1, 220, '1', '1', '1', '1'),
(1730, 1, 221, '1', '1', '1', '1'),
(1731, 1, 222, '1', '1', '1', '1'),
(1732, 1, 223, '1', '1', '1', '1'),
(1733, 1, 224, '1', '1', '1', '1'),
(1734, 1, 225, '1', '1', '1', '1'),
(1735, 1, 226, '1', '1', '1', '1'),
(1736, 1, 228, '1', '1', '1', '1'),
(1737, 1, 229, '1', '1', '1', '1'),
(1738, 1, 230, '1', '1', '1', '1'),
(1739, 1, 231, '1', '1', '1', '1'),
(1740, 1, 232, '1', '1', '1', '1'),
(1741, 1, 233, '1', '1', '1', '1'),
(1742, 1, 234, '1', '1', '1', '1'),
(1743, 1, 235, '1', '1', '1', '1'),
(1744, 1, 241, '1', '1', '1', '1'),
(1745, 1, 242, '1', '1', '1', '1'),
(1746, 1, 243, '1', '1', '1', '1'),
(1747, 1, 244, '1', '1', '1', '1'),
(1748, 1, 245, '1', '1', '1', '1'),
(1749, 1, 246, '1', '1', '1', '1'),
(1750, 1, 247, '1', '1', '1', '1'),
(1751, 1, 248, '1', '1', '1', '1'),
(1752, 1, 255, '1', '1', '1', '1'),
(1753, 1, 256, '1', '1', '1', '1'),
(1754, 1, 257, '1', '1', '1', '1'),
(1755, 1, 258, '1', '1', '1', '1'),
(1756, 1, 259, '1', '1', '1', '1'),
(1757, 1, 260, '1', '1', '1', '1'),
(1758, 1, 261, '1', '1', '1', '1'),
(1759, 1, 262, '1', '1', '1', '1'),
(1760, 1, 263, '1', '1', '1', '1'),
(1761, 1, 264, '1', '1', '1', '1'),
(1762, 1, 265, '1', '1', '1', '1'),
(1763, 1, 268, '1', '1', '1', '1'),
(1764, 1, 269, '1', '1', '1', '1'),
(1765, 1, 270, '1', '1', '1', '1'),
(1766, 1, 271, '1', '1', '1', '1'),
(1767, 1, 272, '1', '1', '1', '1'),
(1768, 1, 273, '1', '1', '1', '1'),
(1769, 1, 274, '1', '1', '1', '1'),
(1770, 1, 281, '1', '1', '1', '1'),
(1771, 1, 282, '1', '1', '1', '1'),
(1772, 1, 283, '1', '1', '1', '1'),
(1773, 1, 284, '1', '1', '1', '1'),
(1774, 1, 285, '1', '1', '1', '1'),
(1775, 1, 286, '1', '1', '1', '1'),
(1776, 1, 287, '1', '1', '1', '1'),
(1777, 1, 294, '1', '1', '1', '1'),
(1778, 1, 295, '1', '1', '1', '1'),
(1779, 1, 296, '1', '1', '1', '1'),
(1780, 1, 297, '1', '1', '1', '1'),
(1781, 1, 298, '1', '1', '1', '1'),
(1782, 1, 299, '1', '1', '1', '1'),
(1783, 1, 300, '1', '1', '1', '1'),
(1784, 1, 311, '1', '1', '1', '1'),
(1785, 1, 312, '1', '1', '1', '1'),
(1786, 1, 313, '1', '1', '1', '1'),
(1787, 1, 314, '1', '1', '1', '1'),
(1788, 1, 315, '1', '1', '1', '1'),
(1789, 1, 316, '1', '1', '1', '1'),
(1790, 1, 317, '1', '1', '1', '1'),
(1791, 1, 349, '1', '1', '1', '1'),
(1792, 1, 350, '1', '1', '1', '1'),
(1793, 1, 351, '1', '1', '1', '1'),
(1794, 1, 352, '1', '1', '1', '1'),
(1795, 1, 353, '1', '1', '1', '1'),
(1796, 1, 354, '1', '1', '1', '1'),
(1797, 1, 355, '1', '1', '1', '1'),
(1798, 1, 366, '1', '1', '1', '1'),
(1799, 1, 367, '1', '1', '1', '1'),
(1800, 1, 368, '1', '1', '1', '1'),
(1801, 1, 369, '1', '1', '1', '1'),
(1802, 1, 370, '1', '1', '1', '1'),
(1803, 1, 371, '1', '1', '1', '1'),
(1804, 1, 372, '1', '1', '1', '1'),
(1805, 1, 377, '1', '1', '1', '1'),
(1806, 1, 378, '1', '1', '1', '1'),
(1807, 1, 379, '1', '1', '1', '1'),
(1808, 1, 380, '1', '1', '1', '1'),
(1809, 1, 381, '1', '1', '1', '1'),
(1810, 1, 382, '1', '1', '1', '1'),
(1811, 1, 383, '1', '1', '1', '1'),
(1812, 1, 389, '1', '1', '1', '1'),
(1813, 1, 390, '1', '1', '1', '1'),
(1814, 1, 391, '1', '1', '1', '1'),
(1815, 1, 392, '1', '1', '1', '1'),
(1816, 1, 393, '1', '1', '1', '1'),
(1817, 1, 394, '1', '1', '1', '1'),
(1818, 1, 395, '1', '1', '1', '1'),
(1819, 1, 398, '1', '1', '1', '1'),
(1820, 1, 399, '1', '1', '1', '1'),
(1821, 1, 400, '1', '1', '1', '1'),
(1822, 1, 401, '1', '1', '1', '1'),
(1823, 1, 402, '1', '1', '1', '1'),
(1824, 1, 403, '1', '1', '1', '1'),
(1825, 1, 404, '1', '1', '1', '1'),
(1826, 1, 412, '1', '1', '1', '1'),
(1827, 1, 413, '1', '1', '1', '1'),
(1828, 1, 414, '1', '1', '1', '1'),
(1829, 1, 415, '1', '1', '1', '1'),
(1830, 1, 416, '1', '1', '1', '1'),
(1831, 1, 417, '1', '1', '1', '1'),
(1832, 1, 418, '1', '1', '1', '1'),
(1833, 1, 422, '1', '1', '1', '1'),
(1834, 1, 423, '1', '1', '1', '1'),
(1835, 1, 424, '1', '1', '1', '1'),
(1836, 1, 425, '1', '1', '1', '1'),
(1837, 1, 426, '1', '1', '1', '1'),
(1838, 1, 427, '1', '1', '1', '1'),
(1839, 1, 428, '1', '1', '1', '1'),
(1840, 1, 431, '1', '1', '1', '1'),
(1841, 1, 432, '1', '1', '1', '1'),
(1842, 1, 433, '1', '1', '1', '1'),
(1843, 1, 434, '1', '1', '1', '1'),
(1844, 1, 435, '1', '1', '1', '1'),
(1845, 1, 436, '1', '1', '1', '1'),
(1846, 1, 437, '1', '1', '1', '1'),
(1847, 1, 442, '1', '1', '1', '1'),
(1848, 1, 443, '1', '1', '1', '1'),
(1849, 1, 444, '1', '1', '1', '1'),
(1850, 1, 445, '1', '1', '1', '1'),
(1851, 1, 446, '1', '1', '1', '1'),
(1852, 1, 447, '1', '1', '1', '1'),
(1853, 1, 448, '1', '1', '1', '1'),
(1854, 1, 451, '1', '1', '1', '1'),
(1855, 1, 452, '1', '1', '1', '1'),
(1856, 1, 453, '1', '1', '1', '1'),
(1857, 1, 454, '1', '1', '1', '1'),
(1858, 1, 455, '1', '1', '1', '1'),
(1859, 1, 456, '1', '1', '1', '1'),
(1860, 1, 457, '1', '1', '1', '1'),
(1861, 1, 458, '1', '1', '1', '1'),
(1862, 1, 460, '1', '1', '1', '1'),
(1863, 1, 461, '1', '1', '1', '1'),
(1864, 1, 462, '1', '1', '1', '1'),
(1865, 1, 463, '1', '1', '1', '1'),
(1866, 1, 464, '1', '1', '1', '1'),
(1867, 1, 465, '1', '1', '1', '1'),
(1868, 1, 466, '1', '1', '1', '1'),
(1869, 1, 467, '1', '1', '1', '1'),
(1870, 1, 468, '1', '1', '1', '1'),
(1871, 1, 472, '1', '1', '1', '1'),
(1872, 1, 473, '1', '1', '1', '1'),
(1873, 1, 474, '1', '1', '1', '1'),
(1874, 1, 475, '1', '1', '1', '1'),
(1875, 1, 476, '1', '1', '1', '1'),
(1876, 1, 477, '1', '1', '1', '1'),
(1877, 1, 478, '1', '1', '1', '1'),
(1878, 1, 479, '1', '1', '1', '1'),
(1879, 1, 482, '1', '1', '1', '1'),
(1880, 1, 483, '1', '1', '1', '1'),
(1881, 1, 484, '1', '1', '1', '1'),
(1882, 1, 485, '1', '1', '1', '1'),
(1883, 1, 486, '1', '1', '1', '1'),
(1884, 1, 487, '1', '1', '1', '1'),
(1885, 1, 488, '1', '1', '1', '1'),
(1886, 1, 492, '1', '1', '1', '1'),
(1887, 1, 493, '1', '1', '1', '1'),
(1888, 1, 494, '1', '1', '1', '1'),
(1889, 1, 495, '1', '1', '1', '1'),
(1890, 1, 496, '1', '1', '1', '1'),
(1891, 1, 497, '1', '1', '1', '1'),
(1892, 1, 498, '1', '1', '1', '1'),
(1893, 1, 500, '1', '1', '1', '1'),
(1894, 1, 501, '1', '1', '1', '1'),
(1895, 1, 502, '1', '1', '1', '1'),
(1896, 1, 503, '1', '1', '1', '1'),
(1897, 1, 504, '1', '1', '1', '1'),
(1898, 1, 505, '1', '1', '1', '1'),
(1899, 1, 506, '1', '1', '1', '1'),
(1900, 1, 507, '1', '1', '1', '1'),
(1901, 1, 508, '1', '1', '1', '1'),
(1902, 1, 510, '1', '1', '1', '1'),
(1903, 1, 511, '1', '1', '1', '1'),
(1904, 1, 512, '1', '1', '1', '1'),
(1905, 1, 513, '1', '1', '1', '1'),
(1906, 1, 514, '1', '1', '1', '1'),
(1907, 1, 515, '1', '1', '1', '1'),
(1908, 1, 516, '1', '1', '1', '1'),
(1909, 1, 517, '1', '1', '1', '1'),
(1910, 1, 518, '1', '1', '1', '1'),
(1911, 1, 519, '1', '1', '1', '1'),
(1912, 1, 522, '1', '1', '1', '1'),
(1913, 1, 523, '1', '1', '1', '1'),
(1914, 1, 524, '1', '1', '1', '1'),
(1915, 1, 525, '1', '1', '1', '1'),
(1916, 1, 526, '1', '1', '1', '1'),
(1917, 1, 527, '1', '1', '1', '1'),
(1918, 1, 528, '1', '1', '1', '1'),
(1919, 1, 535, '1', '1', '1', '1'),
(1920, 1, 536, '1', '1', '1', '1'),
(1921, 1, 537, '1', '1', '1', '1'),
(1922, 1, 538, '1', '1', '1', '1'),
(1923, 1, 539, '1', '1', '1', '1'),
(1924, 1, 540, '1', '1', '1', '1'),
(1925, 1, 541, '1', '1', '1', '1'),
(1926, 1, 548, '1', '1', '1', '1'),
(1927, 1, 549, '1', '1', '1', '1'),
(1928, 1, 550, '1', '1', '1', '1'),
(1929, 1, 551, '1', '1', '1', '1'),
(1930, 1, 552, '1', '1', '1', '1'),
(1931, 1, 553, '1', '1', '1', '1'),
(1932, 1, 554, '1', '1', '1', '1'),
(1933, 1, 564, '1', '1', '1', '1'),
(1934, 1, 565, '1', '1', '1', '1'),
(1935, 1, 566, '1', '1', '1', '1'),
(1936, 1, 567, '1', '1', '1', '1'),
(1937, 1, 568, '1', '1', '1', '1'),
(1938, 1, 569, '1', '1', '1', '1'),
(1939, 1, 570, '1', '1', '1', '1'),
(1940, 1, 595, '1', '1', '1', '1'),
(1941, 1, 596, '1', '1', '1', '1'),
(1942, 1, 597, '1', '1', '1', '1'),
(1943, 1, 598, '1', '1', '1', '1'),
(1944, 1, 599, '1', '1', '1', '1'),
(1945, 1, 600, '1', '1', '1', '1'),
(1946, 1, 604, '1', '1', '1', '1'),
(1947, 1, 605, '1', '1', '1', '1'),
(1948, 1, 606, '1', '1', '1', '1'),
(1949, 1, 607, '1', '1', '1', '1'),
(1950, 1, 608, '1', '1', '1', '1'),
(1951, 1, 609, '1', '1', '1', '1'),
(1952, 1, 610, '1', '1', '1', '1'),
(1953, 1, 620, '1', '1', '1', '1'),
(1954, 1, 621, '1', '1', '1', '1'),
(1955, 1, 622, '1', '1', '1', '1'),
(1956, 1, 623, '1', '1', '1', '1'),
(1957, 1, 624, '1', '1', '1', '1'),
(1958, 1, 625, '1', '1', '1', '1'),
(1959, 1, 626, '1', '1', '1', '1'),
(1960, 1, 641, '1', '1', '1', '1'),
(1961, 1, 642, '1', '1', '1', '1'),
(1962, 1, 643, '1', '1', '1', '1'),
(1963, 1, 644, '1', '1', '1', '1'),
(1964, 1, 645, '1', '1', '1', '1'),
(1965, 1, 646, '1', '1', '1', '1'),
(1966, 1, 647, '1', '1', '1', '1'),
(1967, 1, 651, '1', '1', '1', '1'),
(1968, 1, 652, '1', '1', '1', '1'),
(1969, 1, 653, '1', '1', '1', '1'),
(1970, 1, 654, '1', '1', '1', '1'),
(1971, 1, 655, '1', '1', '1', '1'),
(1972, 1, 656, '1', '1', '1', '1'),
(1973, 1, 657, '1', '1', '1', '1'),
(1974, 1, 660, '1', '1', '1', '1'),
(1975, 1, 661, '1', '1', '1', '1'),
(1976, 1, 663, '1', '1', '1', '1'),
(1977, 1, 664, '1', '1', '1', '1'),
(1978, 1, 665, '1', '1', '1', '1'),
(1979, 1, 666, '1', '1', '1', '1'),
(1980, 1, 667, '1', '1', '1', '1'),
(1981, 1, 668, '1', '1', '1', '1'),
(1982, 1, 669, '1', '1', '1', '1'),
(1983, 1, 672, '1', '1', '1', '1'),
(1984, 1, 673, '1', '1', '1', '1'),
(1985, 1, 674, '1', '1', '1', '1'),
(1986, 1, 675, '1', '1', '1', '1'),
(1987, 1, 676, '1', '1', '1', '1'),
(1988, 1, 677, '1', '1', '1', '1'),
(1989, 1, 678, '1', '1', '1', '1'),
(1990, 1, 685, '1', '1', '1', '1'),
(1991, 1, 686, '1', '1', '1', '1'),
(1992, 1, 687, '1', '1', '1', '1'),
(1993, 1, 688, '1', '1', '1', '1'),
(1994, 1, 689, '1', '1', '1', '1'),
(1995, 1, 690, '1', '1', '1', '1'),
(1996, 1, 691, '1', '1', '1', '1'),
(1997, 1, 696, '1', '1', '1', '1'),
(1998, 1, 697, '1', '1', '1', '1'),
(1999, 1, 698, '1', '1', '1', '1'),
(2000, 1, 699, '1', '1', '1', '1'),
(2001, 1, 700, '1', '1', '1', '1'),
(2002, 1, 701, '1', '1', '1', '1'),
(2003, 1, 702, '1', '1', '1', '1'),
(2004, 1, 705, '1', '1', '1', '1'),
(2005, 1, 706, '1', '1', '1', '1'),
(2006, 1, 707, '1', '1', '1', '1'),
(2007, 1, 708, '1', '1', '1', '1'),
(2008, 1, 709, '1', '1', '1', '1'),
(2009, 1, 710, '1', '1', '1', '1'),
(2010, 1, 711, '1', '1', '1', '1'),
(2011, 1, 718, '1', '1', '1', '1'),
(2012, 1, 719, '1', '1', '1', '1'),
(2013, 1, 720, '1', '1', '1', '1'),
(2014, 1, 721, '1', '1', '1', '1'),
(2015, 1, 722, '1', '1', '1', '1'),
(2016, 1, 723, '1', '1', '1', '1'),
(2017, 1, 724, '1', '1', '1', '1'),
(2018, 1, 733, '1', '1', '1', '1'),
(2019, 1, 734, '1', '1', '1', '1'),
(2020, 1, 735, '1', '1', '1', '1'),
(2021, 1, 736, '1', '1', '1', '1'),
(2022, 1, 737, '1', '1', '1', '1'),
(2023, 1, 738, '1', '1', '1', '1'),
(2024, 1, 739, '1', '1', '1', '1'),
(2025, 1, 745, '1', '1', '1', '1'),
(2026, 1, 746, '1', '1', '1', '1'),
(2027, 1, 747, '1', '1', '1', '1'),
(2028, 1, 748, '1', '1', '1', '1'),
(2029, 1, 749, '1', '1', '1', '1'),
(2030, 1, 750, '1', '1', '1', '1'),
(2031, 1, 751, '1', '1', '1', '1'),
(2032, 1, 759, '1', '1', '1', '1'),
(2033, 1, 760, '1', '1', '1', '1'),
(2034, 1, 761, '1', '1', '1', '1'),
(2035, 1, 762, '1', '1', '1', '1'),
(2036, 1, 763, '1', '1', '1', '1'),
(2037, 1, 764, '1', '1', '1', '1'),
(2038, 1, 765, '1', '1', '1', '1'),
(2039, 1, 825, '1', '1', '1', '1'),
(2040, 1, 826, '1', '1', '1', '1'),
(2041, 1, 827, '1', '1', '1', '1'),
(2042, 1, 828, '1', '1', '1', '1'),
(2043, 1, 829, '1', '1', '1', '1'),
(2044, 1, 830, '1', '1', '1', '1'),
(2045, 1, 831, '1', '1', '1', '1'),
(2046, 1, 871, '1', '1', '1', '1'),
(2047, 1, 872, '1', '1', '1', '1'),
(2048, 1, 873, '1', '1', '1', '1'),
(2049, 1, 874, '1', '1', '1', '1'),
(2050, 1, 875, '1', '1', '1', '1'),
(2051, 1, 876, '1', '1', '1', '1'),
(2052, 1, 877, '1', '1', '1', '1'),
(2053, 1, 880, '1', '1', '1', '1'),
(2054, 1, 881, '1', '1', '1', '1'),
(2055, 1, 882, '1', '1', '1', '1'),
(2056, 1, 883, '1', '1', '1', '1'),
(2057, 1, 884, '1', '1', '1', '1'),
(2058, 1, 885, '1', '1', '1', '1'),
(2059, 1, 886, '1', '1', '1', '1'),
(2060, 1, 913, '1', '1', '1', '1'),
(2061, 1, 914, '1', '1', '1', '1'),
(2062, 1, 915, '1', '1', '1', '1'),
(2063, 1, 916, '1', '1', '1', '1'),
(2064, 1, 917, '1', '1', '1', '1'),
(2065, 1, 918, '1', '1', '1', '1'),
(2066, 1, 919, '1', '1', '1', '1');

--
-- Tabellenstruktur für Tabelle `usergroups`
--

CREATE TABLE IF NOT EXISTS `usergroups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8 NOT NULL,
  `description` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci AUTO_INCREMENT=3 ;

--
-- Daten für Tabelle `usergroups`
--

INSERT INTO `usergroups` (`id`, `name`, `description`, `created`, `modified`) VALUES
(1, 'Administrator', '', '2015-08-19 14:57:42', '2015-08-19 14:57:42'),
(2, 'Viewer', '', '2015-08-19 15:00:36', '2015-08-19 15:00:36');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

