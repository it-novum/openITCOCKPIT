<?php
// Copyright (C) <2015>  <it-novum GmbH>
//
// This file is dual licensed
//
// 1.
//  This program is free software: you can redistribute it and/or modify
//  it under the terms of the GNU General Public License as published by
//  the Free Software Foundation, version 3 of the License.
//
//  This program is distributed in the hope that it will be useful,
//  but WITHOUT ANY WARRANTY; without even the implied warranty of
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//  GNU General Public License for more details.
//
//  You should have received a copy of the GNU General Public License
//  along with this program.  If not, see <http://www.gnu.org/licenses/>.
//
// 2.
//  If you purchased an openITCOCKPIT Enterprise Edition you can use this file
//  under the terms of the openITCOCKPIT Enterprise Edition license agreement.
//  License agreement and license key will be shipped with the order
//  confirmation.

namespace itnovum\openITCOCKPIT\InitialDatabase;


class Commands extends Importer {

    /**
     * @property \Command $Model
     */

    /**
     * @return bool
     */
    public function import() {
        if ($this->isTableEmpty()) {
            $data = $this->getData();
            foreach ($data as $record) {
                $this->Model->create();
                $this->Model->saveAll($record);
            }
        }

        return true;
    }

    /**
     * @return array
     */
    public function getData() {
        $data = [
            0  =>
                [
                    'Command'         =>
                        [
                            'id'           => '1',
                            'name'         => 'host-notify-by-cake',
                            'command_line' => '/usr/share/openitcockpit/app/Console/cake nagios_notification -q --type Host --notificationtype $NOTIFICATIONTYPE$ --hostname "$HOSTNAME$" --hoststate "$HOSTSTATE$" --hostaddress "$HOSTADDRESS$" --hostoutput "$HOSTOUTPUT$" --contactmail "$CONTACTEMAIL$" --contactalias "$CONTACTALIAS$" --hostackauthor "$HOSTACKAUTHOR$" --hostackcomment "$HOSTACKCOMMENT$" --format "both"',
                            'command_type' => '3',
                            'human_args'   => null,
                            'uuid'         => 'a13ff7f1-0642-4a11-be05-9931ca98da10',
                            'description'  => 'Send a host notification as mail',
                        ],
                    'Commandargument' =>
                        [],
                ],
            1  =>
                [
                    'Command'         =>
                        [
                            'id'           => '2',
                            'name'         => 'service-notify-by-cake',
                            'command_line' => '/usr/share/openitcockpit/app/Console/cake nagios_notification -q --type Service --notificationtype $NOTIFICATIONTYPE$ --hostname "$HOSTNAME$" --hoststate "$HOSTSTATE$" --hostaddress "$HOSTADDRESS$" --hostoutput "$HOSTOUTPUT$" --contactmail "$CONTACTEMAIL$" --contactalias "$CONTACTALIAS$" --servicedesc "$SERVICEDESC$" --servicestate "$SERVICESTATE$" --serviceoutput "$SERVICEOUTPUT$" --serviceackauthor "$SERVICEACKAUTHOR$" --serviceackcomment "$SERVICEACKCOMMENT$" --format "both"',
                            'command_type' => '3',
                            'human_args'   => null,
                            'uuid'         => 'a517bbb6-f299-4b57-9865-a4e0b70597e4',
                            'description'  => 'Send a service notification as mail',
                        ],
                    'Commandargument' =>
                        [],
                ],
            2  =>
                [
                    'Command'         =>
                        [
                            'id'           => '3',
                            'name'         => 'check_ping',
                            'command_line' => '$USER1$/check_ping -H $HOSTADDRESS$ -w $ARG1$ -c $ARG2$ -p 5',
                            'command_type' => '1',
                            'human_args'   => null,
                            'uuid'         => 'cdd9ba25-a4d8-4261-a551-32164d4dde14',
                            'description'  => '',
                        ],
                    'Commandargument' =>
                        [
                            0 =>
                                [
                                    'id'         => '1',
                                    'command_id' => '3',
                                    'name'       => '$ARG1$',
                                    'human_name' => 'Warning',
                                    'created'    => '2015-01-05 15:18:23',
                                    'modified'   => '2015-01-05 15:18:23',
                                ],
                            1 =>
                                [
                                    'id'         => '2',
                                    'command_id' => '3',
                                    'name'       => '$ARG2$',
                                    'human_name' => 'Critical',
                                    'created'    => '2015-01-05 15:18:23',
                                    'modified'   => '2015-01-05 15:18:23',
                                ],
                        ],
                ],
            3  =>
                [
                    'Command'         =>
                        [
                            'id'           => '4',
                            'name'         => 'check-host-alive',
                            'command_line' => '$USER1$/check_icmp -H $HOSTADDRESS$ -w $ARG1$ -c $ARG2$ -p 1',
                            'command_type' => '2',
                            'human_args'   => null,
                            'uuid'         => '5a538ebc-03de-4ce6-8e32-665b841abde3',
                            'description'  => '',
                        ],
                    'Commandargument' =>
                        [
                            0 =>
                                [
                                    'id'         => '3',
                                    'command_id' => '4',
                                    'name'       => '$ARG1$',
                                    'human_name' => 'Warning',
                                    'created'    => '2015-01-05 15:21:32',
                                    'modified'   => '2015-01-05 15:21:32',
                                ],
                            1 =>
                                [
                                    'id'         => '4',
                                    'command_id' => '4',
                                    'name'       => '$ARG2$',
                                    'human_name' => 'Critical',
                                    'created'    => '2015-01-05 15:21:32',
                                    'modified'   => '2015-01-05 15:21:32',
                                ],
                        ],
                ],
            4  =>
                [
                    'Command'         =>
                        [
                            'id'           => '5',
                            'name'         => 'check_dhcp',
                            'command_line' => '$USER1$/check_dhcp -s $ARG1$ -r $ARG2$ -i $ARG3$',
                            'command_type' => '1',
                            'human_args'   => null,
                            'uuid'         => '685d5fe1-8847-4df8-8b0e-57e9d8d8def1',
                            'description'  => 'This plugin tests the availability of DHCP servers on a network.',
                        ],
                    'Commandargument' =>
                        [
                            0 =>
                                [
                                    'id'         => '5',
                                    'command_id' => '5',
                                    'name'       => '$ARG1$',
                                    'human_name' => 'IP of DHCP server',
                                    'created'    => '2015-01-15 23:16:23',
                                    'modified'   => '2015-01-15 23:16:23',
                                ],
                            1 =>
                                [
                                    'id'         => '6',
                                    'command_id' => '5',
                                    'name'       => '$ARG2$',
                                    'human_name' => 'IP we expect',
                                    'created'    => '2015-01-15 23:16:23',
                                    'modified'   => '2015-01-15 23:16:23',
                                ],
                            2 =>
                                [
                                    'id'         => '7',
                                    'command_id' => '5',
                                    'name'       => '$ARG3$',
                                    'human_name' => 'Listening interface (i.e. eth0)',
                                    'created'    => '2015-01-15 23:16:23',
                                    'modified'   => '2015-01-15 23:16:23',
                                ],
                        ],
                ],
            5  =>
                [
                    'Command'         =>
                        [
                            'id'           => '6',
                            'name'         => 'check_ftp',
                            'command_line' => '$USER1$/check_ftp -H $HOSTADDRESS$',
                            'command_type' => '1',
                            'human_args'   => null,
                            'uuid'         => 'c63955f5-b971-4bc7-b8e4-eacf991f7a2c',
                            'description'  => 'This plugin tests FTP connections with the specified host (or unix socket).',
                        ],
                    'Commandargument' =>
                        [],
                ],
            6  =>
                [
                    'Command'         =>
                        [
                            'id'           => '7',
                            'name'         => 'check_http',
                            'command_line' => '$USER1$/check_http -H $HOSTADDRESS$ -w $ARG1$ -c $ARG2$ -u $ARG3$',
                            'command_type' => '1',
                            'human_args'   => null,
                            'uuid'         => '9f83fd32-0718-459b-a99e-977e0282da2d',
                            'description'  => 'This plugin tests the HTTP service on the specified host. It can test
normal (http) and secure (https) servers, follow redirects, search for
strings and regular expressions, check connection times, and report on
certificate expiration times.',
                        ],
                    'Commandargument' =>
                        [
                            0 =>
                                [
                                    'id'         => '8',
                                    'command_id' => '7',
                                    'name'       => '$ARG1$',
                                    'human_name' => 'Warning (seconds)',
                                    'created'    => '2015-01-15 23:18:48',
                                    'modified'   => '2015-01-15 23:52:08',
                                ],
                            1 =>
                                [
                                    'id'         => '9',
                                    'command_id' => '7',
                                    'name'       => '$ARG2$',
                                    'human_name' => 'Critical (seconds)',
                                    'created'    => '2015-01-15 23:18:48',
                                    'modified'   => '2015-01-15 23:52:08',
                                ],
                            2 =>
                                [
                                    'id'         => '10',
                                    'command_id' => '7',
                                    'name'       => '$ARG3$',
                                    'human_name' => 'URL (default /)',
                                    'created'    => '2015-01-15 23:18:48',
                                    'modified'   => '2015-01-15 23:52:08',
                                ],
                        ],
                ],
            7  =>
                [
                    'Command'         =>
                        [
                            'id'           => '8',
                            'name'         => 'check_https',
                            'command_line' => '$USER1$/check_http -H $HOSTADDRESS$ -w $ARG1$ -c $ARG2$ -u $ARG3$ -S -p 443 -L',
                            'command_type' => '1',
                            'human_args'   => null,
                            'uuid'         => 'fd4ee227-e672-42e2-a21f-d3a2076bb899',
                            'description'  => 'This plugin tests the HTTP service on the specified host. It can test
normal (http) and secure (https) servers, follow redirects, search for
strings and regular expressions, check connection times, and report on
certificate expiration times.',
                        ],
                    'Commandargument' =>
                        [
                            0 =>
                                [
                                    'id'         => '11',
                                    'command_id' => '8',
                                    'name'       => '$ARG1$',
                                    'human_name' => 'Warning (seconds)',
                                    'created'    => '2015-01-15 23:19:34',
                                    'modified'   => '2015-01-15 23:19:34',
                                ],
                            1 =>
                                [
                                    'id'         => '12',
                                    'command_id' => '8',
                                    'name'       => '$ARG2$',
                                    'human_name' => 'Critical (seconds)',
                                    'created'    => '2015-01-15 23:19:34',
                                    'modified'   => '2015-01-15 23:19:34',
                                ],
                            2 =>
                                [
                                    'id'         => '13',
                                    'command_id' => '8',
                                    'name'       => '$ARG3$',
                                    'human_name' => 'URL (default /)',
                                    'created'    => '2015-01-15 23:19:34',
                                    'modified'   => '2015-01-15 23:19:34',
                                ],
                        ],
                ],
            8  =>
                [
                    'Command'         =>
                        [
                            'id'           => '9',
                            'name'         => 'check_telnet',
                            'command_line' => '$USER1$/check_tcp -H $HOSTADDRESS$ -p 23',
                            'command_type' => '1',
                            'human_args'   => null,
                            'uuid'         => 'db5f1e3c-7031-420e-9998-1520119fc28d',
                            'description'  => 'This plugin tests TCP connections with the specified host (or unix socket) on port 23 for telnet',
                        ],
                    'Commandargument' =>
                        [],
                ],
            9  =>
                [
                    'Command'         =>
                        [
                            'id'           => '10',
                            'name'         => 'check_tcp',
                            'command_line' => '$USER1$/check_tcp -H $HOSTADDRESS$ -p $ARG1$',
                            'command_type' => '1',
                            'human_args'   => null,
                            'uuid'         => '9e659fe6-9f92-4af4-8f4d-905a2e2e6073',
                            'description'  => 'This plugin tests TCP connections with the specified host (or unix socket).',
                        ],
                    'Commandargument' =>
                        [
                            0 =>
                                [
                                    'id'         => '14',
                                    'command_id' => '10',
                                    'name'       => '$ARG1$',
                                    'human_name' => 'Port No',
                                    'created'    => '2015-01-15 23:20:49',
                                    'modified'   => '2015-01-15 23:20:49',
                                ],
                        ],
                ],
            10 =>
                [
                    'Command'         =>
                        [
                            'id'           => '11',
                            'name'         => 'check_ssh',
                            'command_line' => '$USER1$/check_ssh $HOSTADDRESS$',
                            'command_type' => '1',
                            'human_args'   => null,
                            'uuid'         => 'a62423e1-b6ca-4b55-8374-08c73d7b38f9',
                            'description'  => 'Try to connect to an SSH server at specified server and port',
                        ],
                    'Commandargument' =>
                        [],
                ],
            11 =>
                [
                    'Command'         =>
                        [
                            'id'           => '12',
                            'name'         => 'check_smtp',
                            'command_line' => '$USER1$/check_smtp -H $HOSTADDRESS$ -w $ARG1$ -c $ARG2$',
                            'command_type' => '1',
                            'human_args'   => null,
                            'uuid'         => '72d2f4fc-7913-4a0e-8163-fe0810867318',
                            'description'  => 'This plugin will attempt to open an SMTP connection with the host.',
                        ],
                    'Commandargument' =>
                        [
                            0 =>
                                [
                                    'id'         => '15',
                                    'command_id' => '12',
                                    'name'       => '$ARG1$',
                                    'human_name' => 'Warning (seconds)',
                                    'created'    => '2015-01-15 23:22:04',
                                    'modified'   => '2015-01-15 23:22:04',
                                ],
                            1 =>
                                [
                                    'id'         => '16',
                                    'command_id' => '12',
                                    'name'       => '$ARG2$',
                                    'human_name' => 'Critical (seconds)',
                                    'created'    => '2015-01-15 23:22:04',
                                    'modified'   => '2015-01-15 23:22:04',
                                ],
                        ],
                ],
            12 =>
                [
                    'Command'         =>
                        [
                            'id'           => '13',
                            'name'         => 'check_pop',
                            'command_line' => '$USER1$/check_pop -H $HOSTADDRESS$ -w $ARG1$ -c $ARG2$',
                            'command_type' => '1',
                            'human_args'   => null,
                            'uuid'         => 'a19098ba-ca85-4d84-8f11-aecf8f489571',
                            'description'  => 'This plugin tests POP connections with the specified host (or unix socket).',
                        ],
                    'Commandargument' =>
                        [
                            0 =>
                                [
                                    'id'         => '17',
                                    'command_id' => '13',
                                    'name'       => '$ARG1$',
                                    'human_name' => 'Warning (seconds)',
                                    'created'    => '2015-01-15 23:23:10',
                                    'modified'   => '2015-01-15 23:23:10',
                                ],
                            1 =>
                                [
                                    'id'         => '18',
                                    'command_id' => '13',
                                    'name'       => '$ARG2$',
                                    'human_name' => 'Critical (seconds)',
                                    'created'    => '2015-01-15 23:23:10',
                                    'modified'   => '2015-01-15 23:23:10',
                                ],
                        ],
                ],
            13 =>
                [
                    'Command'         =>
                        [
                            'id'           => '14',
                            'name'         => 'check_ntp_time',
                            'command_line' => '$USER1$/check_ntp_time -H $HOSTADDRESS$ -w $ARG1$ -c $ARG2$',
                            'command_type' => '1',
                            'human_args'   => null,
                            'uuid'         => 'bd682bb8-9bd0-4272-aec3-2fa745b47fbc',
                            'description'  => 'This plugin checks the clock offset with the ntp server',
                        ],
                    'Commandargument' =>
                        [
                            0 =>
                                [
                                    'id'         => '19',
                                    'command_id' => '14',
                                    'name'       => '$ARG1$',
                                    'human_name' => 'Warn offset (seconds)',
                                    'created'    => '2015-01-15 23:32:59',
                                    'modified'   => '2015-01-15 23:32:59',
                                ],
                            1 =>
                                [
                                    'id'         => '20',
                                    'command_id' => '14',
                                    'name'       => '$ARG2$',
                                    'human_name' => 'Crit offset (seconds)',
                                    'created'    => '2015-01-15 23:32:59',
                                    'modified'   => '2015-01-15 23:32:59',
                                ],
                        ],
                ],
            14 =>
                [
                    'Command'         =>
                        [
                            'id'           => '15',
                            'name'         => 'check_ntp_peer',
                            'command_line' => '$USER1$/check_ntp_peer -H $HOSTADDRESS$ -w $ARG1$ -c $ARG2$',
                            'command_type' => '1',
                            'human_args'   => null,
                            'uuid'         => '8dd4b139-6912-4ccb-af00-14025eedf2da',
                            'description'  => 'This plugin checks the selected ntp server',
                        ],
                    'Commandargument' =>
                        [
                            0 =>
                                [
                                    'id'         => '21',
                                    'command_id' => '15',
                                    'name'       => '$ARG1$',
                                    'human_name' => 'Warn offset (seconds)',
                                    'created'    => '2015-01-15 23:34:21',
                                    'modified'   => '2015-01-15 23:34:21',
                                ],
                            1 =>
                                [
                                    'id'         => '22',
                                    'command_id' => '15',
                                    'name'       => '$ARG2$',
                                    'human_name' => 'Crit offset (seconds)',
                                    'created'    => '2015-01-15 23:34:21',
                                    'modified'   => '2015-01-15 23:34:21',
                                ],
                        ],
                ],
            15 =>
                [
                    'Command'         =>
                        [
                            'id'           => '16',
                            'name'         => 'check_none',
                            'command_line' => '$USER1$/check_none $ARG1$',
                            'command_type' => '1',
                            'human_args'   => null,
                            'uuid'         => '6ea1e4a4-996f-4673-9099-7c9235aca11b',
                            'description'  => 'This checks nothing',
                        ],
                    'Commandargument' =>
                        [
                            0 =>
                                [
                                    'id'         => '39',
                                    'command_id' => '16',
                                    'name'       => '$ARG1$',
                                    'human_name' => 'ARG1',
                                    'created'    => '2015-01-16 00:17:45',
                                    'modified'   => '2015-01-16 00:17:45',
                                ],
                        ],
                ],
            16 =>
                [
                    'Command'         =>
                        [
                            'id'           => '17',
                            'name'         => 'check_local_disk',
                            'command_line' => '$USER1$/check_disk -w $ARG1$ -c $ARG2$ -p $ARG3$',
                            'command_type' => '1',
                            'human_args'   => null,
                            'uuid'         => '74a59dd0-2eff-4f41-9bcd-3e8786f34f04',
                            'description'  => 'This plugin checks the amount of used disk space on a mounted file system
and generates an alert if free space is less than one of the threshold values',
                        ],
                    'Commandargument' =>
                        [
                            0 =>
                                [
                                    'id'         => '23',
                                    'command_id' => '17',
                                    'name'       => '$ARG1$',
                                    'human_name' => 'Warning (%)',
                                    'created'    => '2015-01-15 23:36:34',
                                    'modified'   => '2015-01-15 23:36:34',
                                ],
                            1 =>
                                [
                                    'id'         => '24',
                                    'command_id' => '17',
                                    'name'       => '$ARG2$',
                                    'human_name' => 'Critical (%)',
                                    'created'    => '2015-01-15 23:36:34',
                                    'modified'   => '2015-01-15 23:36:34',
                                ],
                            2 =>
                                [
                                    'id'         => '25',
                                    'command_id' => '17',
                                    'name'       => '$ARG3$',
                                    'human_name' => 'Moint point',
                                    'created'    => '2015-01-15 23:36:34',
                                    'modified'   => '2015-01-15 23:36:34',
                                ],
                        ],
                ],
            17 =>
                [
                    'Command'         =>
                        [
                            'id'           => '18',
                            'name'         => 'check_local_users',
                            'command_line' => '$USER1$/check_users -w $ARG1$ -c $ARG2$',
                            'command_type' => '1',
                            'human_args'   => null,
                            'uuid'         => 'dd9f7422-1390-4b31-981b-c22bf4dfd00a',
                            'description'  => 'This plugin checks the number of users currently logged in on the local
system and generates an error if the number exceeds the thresholds specified.',
                        ],
                    'Commandargument' =>
                        [
                            0 =>
                                [
                                    'id'         => '26',
                                    'command_id' => '18',
                                    'name'       => '$ARG1$',
                                    'human_name' => 'Warning',
                                    'created'    => '2015-01-15 23:37:22',
                                    'modified'   => '2015-01-15 23:37:22',
                                ],
                            1 =>
                                [
                                    'id'         => '27',
                                    'command_id' => '18',
                                    'name'       => '$ARG2$',
                                    'human_name' => 'Critical',
                                    'created'    => '2015-01-15 23:37:22',
                                    'modified'   => '2015-01-15 23:37:22',
                                ],
                        ],
                ],
            18 =>
                [
                    'Command'         =>
                        [
                            'id'           => '19',
                            'name'         => 'check_local_load',
                            'command_line' => '$USER1$/check_load -w $ARG1$ -c $ARG2$',
                            'command_type' => '1',
                            'human_args'   => null,
                            'uuid'         => '84084403-5c21-4273-835b-d8ac770b4a9f',
                            'description'  => 'This plugin tests the current system load average.',
                        ],
                    'Commandargument' =>
                        [
                            0 =>
                                [
                                    'id'         => '28',
                                    'command_id' => '19',
                                    'name'       => '$ARG1$',
                                    'human_name' => 'Warn (5, 10, 15)',
                                    'created'    => '2015-01-15 23:38:14',
                                    'modified'   => '2015-01-15 23:38:14',
                                ],
                            1 =>
                                [
                                    'id'         => '29',
                                    'command_id' => '19',
                                    'name'       => '$ARG2$',
                                    'human_name' => 'Crit (5, 10, 15)',
                                    'created'    => '2015-01-15 23:38:14',
                                    'modified'   => '2015-01-15 23:38:14',
                                ],
                        ],
                ],
            19 =>
                [
                    'Command'         =>
                        [
                            'id'           => '20',
                            'name'         => 'check_local_mailq',
                            'command_line' => '$USER1$/check_mailq -w $ARG1$ -c $ARG2$',
                            'command_type' => '1',
                            'human_args'   => null,
                            'uuid'         => '9da5cee2-0408-4d47-ab05-8b9ff33b6a9b',
                            'description'  => 'Checks the number of messages in the mail queue (supports multiple sendmail queues, qmail)
Feedback/patches to support non-sendmail mailqueue welcome',
                        ],
                    'Commandargument' =>
                        [
                            0 =>
                                [
                                    'id'         => '30',
                                    'command_id' => '20',
                                    'name'       => '$ARG1$',
                                    'human_name' => 'Warning',
                                    'created'    => '2015-01-15 23:38:52',
                                    'modified'   => '2015-01-15 23:38:52',
                                ],
                            1 =>
                                [
                                    'id'         => '31',
                                    'command_id' => '20',
                                    'name'       => '$ARG2$',
                                    'human_name' => 'Critical',
                                    'created'    => '2015-01-15 23:38:52',
                                    'modified'   => '2015-01-15 23:38:52',
                                ],
                        ],
                ],
            20 =>
                [
                    'Command'         =>
                        [
                            'id'           => '21',
                            'name'         => 'check_local_procs',
                            'command_line' => '$USER1$/check_procs -w $ARG1$ -c $ARG2$ -a $ARG3$',
                            'command_type' => '1',
                            'human_args'   => null,
                            'uuid'         => '2a8a0335-15fd-40d5-8f5a-40ee25c85a8a',
                            'description'  => 'Checks all processes and generates WARNING or CRITICAL states if the specified
metric is outside the required threshold ranges. The metric defaults to number
of processes.  Search filters can be applied to limit the processes to check.',
                        ],
                    'Commandargument' =>
                        [
                            0 =>
                                [
                                    'id'         => '32',
                                    'command_id' => '21',
                                    'name'       => '$ARG1$',
                                    'human_name' => 'Warning (Range)',
                                    'created'    => '2015-01-15 23:40:29',
                                    'modified'   => '2015-01-16 00:01:49',
                                ],
                            1 =>
                                [
                                    'id'         => '33',
                                    'command_id' => '21',
                                    'name'       => '$ARG2$',
                                    'human_name' => 'Critical (Range)',
                                    'created'    => '2015-01-15 23:40:29',
                                    'modified'   => '2015-01-16 00:01:49',
                                ],
                            2 =>
                                [
                                    'id'         => '34',
                                    'command_id' => '21',
                                    'name'       => '$ARG3$',
                                    'human_name' => 'Name',
                                    'created'    => '2015-01-15 23:40:29',
                                    'modified'   => '2015-01-16 00:01:49',
                                ],
                        ],
                ],
            21 =>
                [
                    'Command'         =>
                        [
                            'id'           => '22',
                            'name'         => 'check_local_procs_total',
                            'command_line' => '$USER1$/check_procs -w $ARG1$ -c $ARG2$',
                            'command_type' => '1',
                            'human_args'   => null,
                            'uuid'         => '62b42c52-3103-427e-bbb2-8ad4e5db6563',
                            'description'  => 'Checks all processes and generates WARNING or CRITICAL states if the specified
metric is outside the required threshold ranges. The metric defaults to number
of processes.  Search filters can be applied to limit the processes to check.',
                        ],
                    'Commandargument' =>
                        [
                            0 =>
                                [
                                    'id'         => '35',
                                    'command_id' => '22',
                                    'name'       => '$ARG1$',
                                    'human_name' => 'Warning (Range)',
                                    'created'    => '2015-01-15 23:41:07',
                                    'modified'   => '2015-01-15 23:41:07',
                                ],
                            1 =>
                                [
                                    'id'         => '36',
                                    'command_id' => '22',
                                    'name'       => '$ARG2$',
                                    'human_name' => 'Critical (Range)',
                                    'created'    => '2015-01-15 23:41:07',
                                    'modified'   => '2015-01-15 23:41:07',
                                ],
                        ],
                ],
            22 =>
                [
                    'Command'         =>
                        [
                            'id'           => '23',
                            'name'         => 'check_by_ssh',
                            'command_line' => '$USER1$/check_by_ssh -H $HOSTADDRESS$ -l "$ARG1$" -C "$ARG2$"',
                            'command_type' => '1',
                            'human_args'   => null,
                            'uuid'         => 'fd52f68f-e0f9-4c69-9f69-bc1b2d97188a',
                            'description'  => 'This plugin uses SSH to execute commands on a remote host',
                        ],
                    'Commandargument' =>
                        [
                            0 =>
                                [
                                    'id'         => '37',
                                    'command_id' => '23',
                                    'name'       => '$ARG1$',
                                    'human_name' => 'Username',
                                    'created'    => '2015-01-15 23:42:43',
                                    'modified'   => '2015-01-15 23:42:43',
                                ],
                            1 =>
                                [
                                    'id'         => '38',
                                    'command_id' => '23',
                                    'name'       => '$ARG2$',
                                    'human_name' => 'Command',
                                    'created'    => '2015-01-15 23:42:43',
                                    'modified'   => '2015-01-15 23:42:43',
                                ],
                        ],
                ],
            23 =>
                [
                    'Command'         =>
                        [
                            'id'           => '24',
                            'name'         => 'check_mk_active',
                            'command_line' => 'python /opt/openitc/nagios/3rd/check_mk/var/oitc_precompiled/$HOSTNAME$.py',
                            'command_type' => '1',
                            'human_args'   => null,
                            'uuid'         => 'e02cbbc7-aa72-42e2-b7c6-fb4fb7b6519b',
                            'description'  => 'Execute a check_mk python file, generated by check_mk and openITCOCKPIT',
                        ],
                    'Commandargument' =>
                        [],
                ],
            24 =>
                [
                    'Command'         =>
                        [
                            'name'         => 'host-notify-by-pushover',
                            'command_line' => '/usr/share/openitcockpit/app/Console/cake pushover_notification --type Host --notificationtype $NOTIFICATIONTYPE$ --hostuuid "$HOSTNAME$" --state "$HOSTSTATEID$" --output "$HOSTOUTPUT$"  --pushover-api-token "$_CONTACTPUSHOVERAPP$" --pushover-user-token "$_CONTACTPUSHOVERUSER$" --ackauthor "$HOSTACKAUTHOR$" --ackcomment "$HOSTACKCOMMENT$" --proxy 1',
                            'command_type' => '3',
                            'human_args'   => null,
                            'uuid'         => '93f28eb9-9cce-4373-9a39-c6fef86419eb',
                            'description'  => 'Send a host notification via Pushover.net',
                        ],
                    'Commandargument' =>
                        [],
                ],
            25 =>
                [
                    'Command'         =>
                        [
                            'name'         => 'service-notify-by-pushover',
                            'command_line' => '/usr/share/openitcockpit/app/Console/cake pushover_notification --type Service --notificationtype $NOTIFICATIONTYPE$ --hostuuid "$HOSTNAME$" --serviceuuid "$SERVICEDESC$" --state "$SERVICESTATEID$" --output "$SERVICEOUTPUT$"  --pushover-api-token "$_CONTACTPUSHOVERAPP$" --pushover-user-token "$_CONTACTPUSHOVERUSER$" --ackauthor "$SERVICEACKAUTHOR$" --ackcomment "$SERVICEACKCOMMENT$" --proxy 1',
                            'command_type' => '3',
                            'human_args'   => null,
                            'uuid'         => '52c9c3e2-4a00-40a4-8e75-db4649eba846',
                            'description'  => 'Send a service notification via Pushover.net',
                        ],
                    'Commandargument' =>
                        [],
                ],
        ];

        return $data;
    }

}