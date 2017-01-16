<?php
// Copyright (C) <2017>  <it-novum GmbH>
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
<div class="row">
    <div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
        <h1 class="page-title txt-color-blueDark">
            <i class="fa fa-star fa-fw "></i>
            <?php echo __('Edit OAuth2 Client Connection'); ?>
            <div class="third_level"> <?php echo ucfirst($this->params['action']); ?></div>
        </h1>
    </div>
</div>
<div id="error_msg"></div>

<div class="jarviswidget" id="wid-id-0">
    <header>
        <span class="widget-icon"> <i class="fa fa-star"></i> </span>
        <h2><?php echo __('Edit OAuth2 Client Connection'); ?></h2>
        <div class="widget-toolbar" role="menu">
            <?php echo $this->Utils->backButton(); ?>
        </div>
    </header>
    <div>
        <div class="widget-body">
            <?php
            echo $this->Form->create('Oauth2client', [
                'class' => 'form-horizontal clear',
            ]);
            echo $this->Form->input('id', ['type' => 'hidden', 'value' => $oauth2Client['Oauth2client']['id']]);
            echo $this->Form->input('Oauth2client.provider', ['label' => __('OAuth2 Provider'), 'placeholder' => 'PingIdentity', 'value' => $oauth2Client['Oauth2client']['provider']]);
            echo $this->Form->input('Oauth2client.client_id', ['label' => __('Client ID'), 'placeholder' => 'OAuth2 Client ID', 'value' => $oauth2Client['Oauth2client']['client_id'], 'type' => 'text']);
            echo $this->Form->input('Oauth2client.client_secret', ['label' => __('Client secret'), 'value' => $oauth2Client['Oauth2client']['client_secret']]);
            echo $this->Form->input('redirect_uri', ['label' => __('Redirect URI'), 'readOnly' => true, 'value' => $returnUrl]);
            echo $this->Form->input('Oauth2client.url_authorize', ['label' => __('URL Authorize'), 'value' => $oauth2Client['Oauth2client']['url_authorize']]);
            echo $this->Form->input('Oauth2client.url_accessToken', ['label' => __('URL Access Token'), 'value' => $oauth2Client['Oauth2client']['url_accessToken']]);
            ?>
            <div class="form-group">
                <?php
                echo $this->Form->fancyCheckbox('Oauth2client.show_login_page', [
                    'caption'          => __('Show login page'),
                    'captionGridClass' => 'col col-md-2 text-right',
                    'class'            => 'onoffswitch-checkbox notification_control',
                    'checked'          => $oauth2Client['Oauth2client']['show_login_page'],
                    'wrapGridClass'    => 'col col-xs-2',
                ]);
                ?>
            </div>
            <?php
            echo $this->Form->input('Oauth2client.button_text', ['label' => __('Button text'), 'value' => $oauth2Client['Oauth2client']['button_text']]);
            ?>
            <div class="form-group">
                <?php
                echo $this->Form->fancyCheckbox('Oauth2client.active', [
                    'caption'          => __('Active'),
                    'captionGridClass' => 'col col-md-2 text-right',
                    'class'            => 'onoffswitch-checkbox notification_control',
                    'checked'          => $oauth2Client['Oauth2client']['active'],
                    'wrapGridClass'    => 'col col-xs-2',
                ]);
                ?>
            </div>
            <br>
            <br>
            <?php echo $this->Form->formActions(); ?>
        </div>
    </div>
</div>

