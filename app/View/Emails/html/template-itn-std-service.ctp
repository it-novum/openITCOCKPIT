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

<style>
    /* STYLE DEFINITIONS */
    * {
        margin: 0;
        padding: 0
    }

    * {
        font-family: "Helvetica Neue", "Helvetica", Helvetica, Arial, sans-serif
    }

    img {
        max-width: 100%
    }

    .collapse {
        margin: 0;
        padding: 0
    }

    body {
        -webkit-font-smoothing: antialiased;
        -webkit-text-size-adjust: none;
        width: 100% !important;
        height: 100%
    }

    a {
        color: #2ba6cb
    }

    .btn {
        display: inline-block;
        padding: 6px 12px;
        margin-bottom: 0;
        font-size: 14px;
        font-weight: normal;
        line-height: 1.428571429;
        text-align: center;
        white-space: nowrap;
        vertical-align: middle;
        cursor: pointer;
        border: 1px solid transparent;
        border-radius: 4px;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        -o-user-select: none;
        user-select: none;
        color: #333;
        background-color: white;
        border-color: #CCC;
    }

    p.callout {
        padding: 15px;
        background-color: #ecf8ff;
        margin-bottom: 15px
    }

    .callout a {
        font-weight: bold;
        color: #2ba6cb
    }

    table.social {
        background-color: #ebebeb
    }

    .social .soc-btn {
        padding: 3px 7px;
        border-radius: 2px;
        -webkit-border-radius: 2px;
        -moz-border-radius: 2px;
        font-size: 12px;
        margin-bottom: 10px;
        text-decoration: none;
        color: #FFF;
        font-weight: bold;
        display: block;
        text-align: center
    }

    a.fb {
        background-color: #3b5998 !important
    }

    a.tw {
        background-color: #1daced !important
    }

    a.gp {
        background-color: #db4a39 !important
    }

    a.ms {
        background-color: #000 !important
    }

    .sidebar .soc-btn {
        display: block;
        width: 100%
    }

    table.head-wrap {
        width: 100%;
        background-color: #f3f3f3;
        background-image: linear-gradient(to bottom, #f3f3f3, #e2e2e2);
        background-repeat: repeat-x;
    }

    .header.container table td.logo {
        padding: 15px
    }

    .header.container table td.label {
        padding: 15px;
        padding-left: 0
    }

    table.body-wrap {
        width: 100%
    }

    table.footer-wrap {
        width: 100%;
        clear: both !important
    }

    .footer-wrap .container td.content p {
        border-top: 1px solid #d7d7d7;
        padding-top: 15px
    }

    .footer-wrap .container td.content p {
        font-size: 10px;
        font-weight: bold
    }

    h1, h2, h3, h4, h5, h6 {
        font-family: "HelveticaNeue-Light", "Helvetica Neue Light", "Helvetica Neue", Helvetica, Arial, "Lucida Grande", sans-serif;
        line-height: 1.1;
        margin-bottom: 15px;
        color: #000
    }

    h1 small, h2 small, h3 small, h4 small, h5 small, h6 small {
        font-size: 60%;
        color: #6f6f6f;
        line-height: 0;
        text-transform: none
    }

    h1 {
        font-weight: 200;
        font-size: 44px
    }

    h2 {
        font-weight: 200;
        font-size: 37px
    }

    h3 {
        font-weight: 500;
        font-size: 27px
    }

    h4 {
        font-weight: 500;
        font-size: 23px
    }

    h5 {
        font-weight: 900;
        font-size: 17px
    }

    h6 {
        font-weight: 900;
        font-size: 14px;
        text-transform: uppercase;
        color: #444
    }

    .collapse {
        margin: 0 !important
    }

    p, ul {
        margin-bottom: 10px;
        font-weight: normal;
        font-size: 14px;
        line-height: 1.6
    }

    p.lead {
        font-size: 17px
    }

    p.last {
        margin-bottom: 0
    }

    ul li {
        margin-left: 5px;
        list-style-position: inside
    }

    ul.sidebar {
        background: #ebebeb;
        display: block;
        list-style-type: none
    }

    ul.sidebar li {
        display: block;
        margin: 0
    }

    ul.sidebar li a {
        text-decoration: none;
        color: #666;
        padding: 10px 16px;
        margin-right: 10px;
        cursor: pointer;
        border-bottom: 1px solid #777;
        border-top: 1px solid #fff;
        display: block;
        margin: 0
    }

    ul.sidebar li a.last {
        border-bottom-width: 0
    }

    ul.sidebar li a h1, ul.sidebar li a h2, ul.sidebar li a h3, ul.sidebar li a h4, ul.sidebar li a h5, ul.sidebar li a h6, ul.sidebar li a p {
        margin-bottom: 0 !important
    }

    .container {
        display: block !important;
        max-width: 600px !important;
        margin: 0 auto !important;
        clear: both !important
    }

    .content {
        padding: 15px;
        max-width: 600px;
        margin: 0 auto;
        display: block
    }

    .content table {
        width: 100%
    }

    .column {
        width: 300px;
        float: left
    }

    .column tr td {
        padding: 15px
    }

    .column-wrap {
        padding: 0 !important;
        margin: 0 auto;
        max-width: 600px !important
    }

    .column table {
        width: 100%
    }

    .social .column {
        width: 280px;
        min-width: 279px;
        float: left
    }

    .clear {
        display: block;
        clear: both
    }

    .label {
        border-radius: 0.25em;
        color: #fff;
        display: inline;
        font-weight: 700;
        line-height: 1;
        padding: 0.1em 0.6em 0.1em;
        text-align: center;
        vertical-align: baseline;
        white-space: nowrap;
    }

    .bg-color-blue {
        background-color: #57889c !important;
    }

    .bg-color-blueLight {
        background-color: #92a2a8 !important;
    }

    .bg-color-blueDark {
        background-color: #4c4f53 !important;
    }

    .bg-color-green {
        background-color: #356e35 !important;
    }

    .bg-color-greenLight {
        background-color: #71843f !important;
    }

    .bg-color-greenDark {
        background-color: #496949 !important;
    }

    .bg-color-red {
        background-color: #a90329 !important;
    }

    .bg-color-yellow {
        background-color: #b09b5b !important;
    }

    .bg-color-orange {
        background-color: #c79121 !important;
    }

    .bg-color-orangeDark {
        background-color: #a57225 !important;
    }

    .bg-color-grayDark {
        background-color: #525252 !important;
    }

    .bg-color-magenta {
        background-color: #6e3671 !important;
    }

    .UP {
        color: #356e35 !important;
    }

    .DOWN {
        color: #a90329 !important;
    }

    .UNREACHABLE {
        color: #525252 !important;
    }

    .OK {
        color: #356e35 !important;
    }

    .WARNING {
        color: #b09b5b !important;
    }

    .CRITICAL {
        color: #a90329 !important;
    }

    .UNKNOWN {
        color: #525252 !important;
    }

    .txt-color-green {
        color: #356e35 !important;
    }

    .txt-color-red {
        color: #a90329 !important;
    }

    .txt-color-orange {
        color: #b19a6b !important;
    }

    .txt-color-blueLight {
        color: #92a2a8 !important;
    }

    .notification_type {
        position: relative;
    }

    .notification_type:after {
        content: '<?php echo h($parameters['notificationtype']); ?>';
        color: white;
        font-size: x-large;
        bottom: 0px;
        line-height: 1;
        letter-spacing: 10px;
        font-weight: bold;
        text-shadow: 1px 1px 1px #A09D9D, 0 0 0 #000, 1px 1px 1px #def;
        position: absolute;
    }

    @media only screen and (max-width: 600px) {
        a[class="btn"] {
            display: block !important;
            margin-bottom: 10px !important;
            background-image: none !important;
            margin-right: 0 !important
        }

        div[class="column"] {
            width: auto !important;
            float: none !important
        }

        table.social div[class="column"] {
            width: auto !important
        }
    }

    /* EVC Summary View*/
    .criticalEvcSummary {
        color: white !important;
        -webkit-box-shadow: 3px 3px 1px -2px rgba(0, 0, 0, 0.75);
        -moz-box-shadow: 3px 3px 1px -2px rgba(0, 0, 0, 0.75);
        box-shadow: 3px 3px 1px -2px rgba(0, 0, 0, 0.75);
        padding: 2px;
        background: linear-gradient(to right, #4c4f53 15px, #d9534f 2%); /* W3C */

    }

    .criticalEvcSummary:after {
        font-family: FontAwesome;
        /*content: "\f071";*/
        color: white;
    }

    .okEvcSummary {
        -webkit-box-shadow: 3px 3px 1px -2px rgba(0, 0, 0, 0.75);
        -moz-box-shadow: 3px 3px 1px -2px rgba(0, 0, 0, 0.75);
        box-shadow: 3px 3px 1px -2px rgba(0, 0, 0, 0.75);
        padding: 5px;
        color: white;
        background: linear-gradient(to right, #4c4f53 15px, #5cb85c 2%); /* W3C */
    }

    .unknownEvcSummary {
        -webkit-box-shadow: 3px 3px 1px -2px rgba(0, 0, 0, 0.75);
        -moz-box-shadow: 3px 3px 1px -2px rgba(0, 0, 0, 0.75);
        box-shadow: 3px 3px 1px -2px rgba(0, 0, 0, 0.75);
        padding: 2px;
        color: white;
        background: linear-gradient(to right, #4c4f53 15px, #888888 2%); /* W3C */
    }

    .warningEvcSummary {
        -webkit-box-shadow: 3px 3px 1px -2px rgba(0, 0, 0, 0.75);
        -moz-box-shadow: 3px 3px 1px -2px rgba(0, 0, 0, 0.75);
        box-shadow: 3px 3px 1px -2px rgba(0, 0, 0, 0.75);
        padding: 2px;
        color: white;
        background: linear-gradient(to right, #4c4f53 15px, #f0ad4e 2%); /* W3C */
    }

    .disabledEvcSummary {
        background: repeating-linear-gradient(
                -45deg,
                #ffffff,
                #ffffff 10px,
                #eaf0f2 10px,
                #eaf0f2 20px
        );
    }

    .disabledEvcSummary:after {
        content: "\f1e6";
        font-family: FontAwesome;
        color: #ff0000;
        font-size: 11px;
    }

    .gatter {
        display: block;
        white-space: nowrap;
        font-family: Verdana;
        -moz-transform: rotate(-90deg);
        -moz-transform-origin: center center;
        -webkit-transform: rotate(-90deg);
        -webkit-transform-origin: center center;
        -ms-transform: rotate(-90deg);
        -ms-transform-origin: center center;
        width: 15px;
        bottom: 1px;
        position: absolute;
        font-size: 14px;
        text-shadow: 1px 1px #7d7d7d;
        font-family: 'Courier New', Arial;
    }

    #evcSummary {
        border-spacing: 5px;
        border-collapse: separate;
    }

    #evcSummary td {
        position: relative;
    }

    #evcSummary td div {
        position: absolute;
        left: 0;
    }

    #evcSummary span {
        padding: 5px 10px 5px 20px;
        display: inline-block;
        font-size: 13px;
        font-family: 'Courier New', Arial;
    }

    .disabledEvcSummary span {
        padding: 15px 1px 15px 20px;
        display: inline-block;
        font-size: 13px;
        font-family: 'Courier New', Arial;
    }

    .borderOkEvcSummary {
        border-right: 5px solid #5cb85c;
    }

    .borderWarningEvcSummary {
        border-right: 5px solid #f0ad4e;
    }

    .borderCriticalEvcSummary {
        border-right: 5px solid #d9534f;
    }

    .borderUnknownEvcSummary {
        border-right: 5px solid #888888;
    }

    .borderDisabledEvcSummary {
        border-right: 5px solid #dee3e5;
    }

    .borderNotMonitoredEvcSummary {
        border-right: 5px solid #428bca;
    }

    .virtualLayerService {
        font-weight: bold;
        padding: 10px 1px 10px 15px !important;
    }
</style>
<!-- ########## EMAIL CONTENT ############### -->

<body bgcolor="#FFFFFF">
<table class="head-wrap">
    <tr>
        <td></td>
        <td class="header container">

            <div class="content notification_type">
                <table>
                    <tr>
                        <td>
                            <?php if (!$parameters['no-attachments']): ?>
                                <img src="cid:100" width="120"/>
                            <?php endif; ?>
                        </td>
                        <td align="right"><h6 class="collapse"><?php echo __('openITCOCKPIT Notification'); ?></h6></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td align="right"><h6 class="collapse"><?php echo __('it-novum GmbH'); ?></h6></td>
                    </tr>
                </table>
            </div>

        </td>
        <td></td>
    </tr>
</table>

<table class="body-wrap">
    <tr>
        <td></td>
        <td class="container" bgcolor="#FFFFFF">

            <div class="content">
                <table>
                    <tr>
                        <td>
                            <h5>&nbsp;<?php echo __('Service'); ?> <span><a
                                            href='https://<?php echo $_systemsettings['SYSTEM']['SYSTEM.ADDRESS'] ?>/forward/index/uuid:<?php echo $parameters['serviceUuid'] ?>/action:browser/model:Service'
                                            style="text-decoration:none"
                                            class="<?php echo $parameters['servicestate']; ?>"><?php echo $parameters['servicedesc']; ?></a></span>
                                on Host <span><a
                                            href='https://<?php echo $_systemsettings['SYSTEM']['SYSTEM.ADDRESS'] ?>/forward/index/uuid:<?php echo $parameters['hostUuid'] ?>/action:browser/model:Host'
                                            style="text-decoration:none"
                                            class="<?php echo h($parameters['hoststate']); ?>"><?php echo h($parameters['hostname']); ?></a></span>
                            </h5>
                            <hr noshade width="560" size="3" align="left">
                            <br>
                            <table width="100%">
                                <?php
                                if (isset($parameters['notificationtype']) && $parameters['notificationtype'] === 'ACKNOWLEDGEMENT'): ?>
                                    <tr>
                                        <td colspan="2">
                                            <i class="fa fa-user fa-stack-2x"></i>
                                            <strong><?php
                                                echo __('The current status was acknowledged by %s ', $parameters['serviceackauthor']);
                                                echo __('with the comment "');
                                                if (!empty($_systemsettings['TICKET_SYSTEM']['TICKET_SYSTEM.URL']) && preg_match('/^(Ticket)_?(\d+);?(\d+)/', $parameters['serviceackcomment'], $ticketDetails)):
                                                    echo (isset($ticketDetails[1], $ticketDetails[3], $ticketDetails[2])) ?
                                                        $this->Html->link(
                                                            $ticketDetails[1] . ' ' . $ticketDetails[2],
                                                            $_systemsettings['TICKET_SYSTEM']['TICKET_SYSTEM.URL'] . $ticketDetails[3],
                                                            ['target' => '_blank']) : $parameters['serviceackcomment'];
                                                else:
                                                    echo h($parameters['serviceackcomment']);
                                                endif;
                                                ?>".
                                            </strong>
                                        </td>
                                    </tr>
                                <?php
                                endif;
                                ?>
                                <tr>
                                    <td><strong><?php echo __('Time'); ?>:</strong></td>
                                    <td><?php echo date('H:i:s T'); ?></td>
                                </tr>
                                <tr>
                                    <td><strong><?php echo __('Host Name'); ?>:</strong></td>
                                    <td><?php echo h($parameters['hostname']); ?></td>
                                </tr>
                                <tr>
                                    <td><strong><?php echo __('Hostdescription'); ?>:</strong></td>
                                    <td><?php echo h($parameters['hostdescription']); ?></td>
                                </tr>
                                <tr>
                                    <td><strong><?php echo __('Hostaddress'); ?>:</strong></td>
                                    <td><?php echo h($parameters['hostaddress']); ?></td>
                                </tr>
                                <tr>
                                    <td><strong><?php echo __('Servicename'); ?>:</strong></td>
                                    <td><?php echo h($parameters['servicedesc']); ?></td>
                                </tr>
                                <tr>
                                    <td><strong><?php echo __('State'); ?>:</strong></td>
                                    <td class=<?php echo h($parameters['servicestate']); ?>><?php echo h($parameters['servicestate']); ?></td>
                                </tr>
                            </table>
                            <br/>
                            <strong><?php echo __('Output'); ?>:</strong>
                            <p class="lead"> <?php echo h($parameters['serviceoutput']); ?> </p>
                            <br/>
                            <?php if(isset($parameters['servicelongoutput']) && !empty($parameters['servicelongoutput'])): ?>
                                <strong><?php echo __('Service long output'); ?>:</strong>
                                <p class="lead"> <?php echo str_replace(['\n', '\r\n', '\r'], '<br/>', h($parameters['servicelongoutput'])); ?> </p>
                                <br/>
                            <?php endif; ?>


                            <table class="social" width="100%">
                                <?php if (!$parameters['no-attachments']): ?>
                                    <?php foreach ($contentIDs as $contentID): ?>
                                        <tr>
                                            <td>
                                                <img src="cid:<?php echo $contentID; ?>" alt='Chart' width="560"
                                                     height="180" style="background:#fff;background-color:#fff;"/>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </table>
                            <?php
                            if (!empty($parameters['evcTree'])):
                                $this->Helpers->load('EventcorrelationModule.Evc');
                                echo $this->Evc->getEvcDataAsTable(
                                    $parameters['evcTree'],
                                    $parameters['servicestatus'],
                                    $parameters['serviceUuid']
                                );
                                ?>
                                <br/>
                                <p>
                                    <?php
                                    echo nl2br(h($parameters['servicestatus'][$parameters['serviceUuid']]['Servicestatus']['long_output']));
                                    ?>
                                </p>
                            <?php
                            endif;
                            ?>
                            <br/><br/>
                            <?php if ($parameters['servicestate'] != 'OK'): ?>
                                --- BEGIN ACK INFORMATION ---
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
                                --- END ACK INFORMATION ---
                            <?php endif; ?>
                        </td>
                    </tr>
                </table>
            </div>
        </td>
        <td></td>
    </tr>
</table>
<table class="footer-wrap">
    <tr>
        <td></td>
        <td class="container">
            <div class="content">
                <table>
                    <tr>
                        <hr noshade width="560" size="3" align="left">
                        <br>
                        <td align="center">
                            <p>
                                <a href="http://www.openitcockpit.com"><?php echo __('openITCOCKPIT'); ?></a> |
                                <a href="http://www.it-novum.com"><?php echo __('it-novum'); ?></a>
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td align="center">
                            <p>
                                <?php echo date('l jS \of F Y'); ?>
                            </p>
                        </td>
                    </tr>
                </table>
            </div>
        </td>
        <td></td>
    </tr>
</table>

<!--
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
-->
