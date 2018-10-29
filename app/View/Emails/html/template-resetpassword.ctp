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
</style>
<!-- ########## EMAIL CONTENT ############### -->

<body bgcolor="#FFFFFF">

<!-- HEADER -->
<table class="head-wrap">
    <tr>
        <td></td>
        <td class="header container">

            <div class="content">
                <table>
                    <tr>
                        <td><img src="cid:100" width="120"/></td>
                        <td align="right"><h6 class="collapse"><?php echo __('One-time password'); ?></h6></td>
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
<!-- /HEADER -->


<!-- BODY -->
<table class="body-wrap">
    <tr>
        <td></td>
        <td class="container" bgcolor="#FFFFFF">

            <div class="content">
                <table>
                    <tr>
                        <td>
                            <h5>&nbsp;<?php echo __('Your password was reseted. Your new password is:'); ?></h5>
                            <hr noshade width="560" size="3" align="left">
                            <br>
                            <h1><?php echo $newPassword; ?></h1>
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