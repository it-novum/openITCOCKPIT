<?php
// Copyright (C) <2015>  <it-novum GmbH>
//
// This file is dual licensed
//
// 1.
//	This program is free software: you can redistribute it and/or modify
//	it under the terms of the GNU General Public License as published by
//	the Free Software Foundation, version 3 of the License.
//
//	This program is distributed in the hope that it will be useful,
//	but WITHOUT ANY WARRANTY; without even the implied warranty of
//	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//	GNU General Public License for more details.
//
//	You should have received a copy of the GNU General Public License
//	along with this program.  If not, see <http://www.gnu.org/licenses/>.
//

// 2.
//	If you purchased an openITCOCKPIT Enterprise Edition you can use this file
//	under the terms of the openITCOCKPIT Enterprise Edition license agreement.
//	License agreement and license key will be shipped with the order
//	confirmation.

?>
Service <?php echo h($parameters['servicedesc']); ?> on Host <?php echo h($parameters['hostname']); ?>


Time: <?php echo date('H:i:s T'); ?>

Hostname: <?php echo h($parameters['hostname']); ?>

Hostdescription: <?php echo h($parameters['hostdescription']); ?>

Hostaddress: <?php echo h($parameters['hostaddress']); ?>

Servicename: <?php echo h($parameters['servicedesc']); ?>

State: <?php echo h($parameters['servicestate']); ?>


Output: <?php echo h($parameters['serviceoutput']); ?>

<?php if(isset($parameters['servicelongoutput']) && !empty($parameters['servicelongoutput'])): ?>

Long service output:
<?php echo h(str_replace(['\n', '\r\n', '\r'], "\n", $parameters['servicelongoutput'])); ?>
<?php endif; ?>



--- BEGIN TICKET SYSTEM INFORMATION ---
TICKET_HOSTNAME: <?php echo h($parameters['hostname']);
echo PHP_EOL; ?>
TICKET_HOSTUUID: <?php echo $parameters['hostUuid'];
echo PHP_EOL; ?>
TICKET_SERVICEDESC: <?php echo $parameters['servicedesc'];
echo PHP_EOL; ?>
TICKET_SERVICEUUID: <?php echo $parameters['serviceUuid'];
echo PHP_EOL; ?>
TICKET_STATE: <?php echo h($parameters['servicestate']);
echo PHP_EOL; ?>
TICKET_NOTIFICATIONTYPE: SERVICE
TICKET_COMMAND_NUMBER: 34
--- END TICKET SYSTEM INFORMATION ---

<?php if ($parameters['servicestate'] != 'OK'): ?>
    --- BEGIN ACK2 INFORMATION ---
    ACK_HOSTNAME: <?php echo h($parameters['hostname']);
    echo PHP_EOL; ?>
    ACK_HOSTUUID: <?php echo $parameters['hostUuid'];
    echo PHP_EOL; ?>
    ACK_SERVICEDESC: <?php echo $parameters['servicedesc'];
    echo PHP_EOL; ?>
    ACK_SERVICEUUID: <?php echo $parameters['serviceUuid'];
    echo PHP_EOL; ?>
    ACK_STATE: <?php echo h($parameters['servicestate']);
    echo PHP_EOL; ?>
    ACK_NOTIFICATIONTYPE: SERVICE
    --- END ACK2 INFORMATION ---
<?php endif; ?>
