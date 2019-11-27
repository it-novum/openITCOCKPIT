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
<?php echo $this->Form->create('LoginUser', [
    'url'           => '/login/login',
    'class'         => 'lockscreen animated flipInY',
    'inputDefaults' => [
        'wrapInput' => false,
        'label'     => false,
        'div'       => false,
    ],
]);

/* Information zu einem Benutzer können mit folgendme Aufruf ausgelesen werden:
    $this->Auth->user('email');
    
    Dies funktioniert hier allerdings nicht, da der Benutzer abgemeldet wurde um unerlaubten Zugriff zu verhindern!
    Deswegen werden alle Daten vom Controller in $user geschrieben und in der View nur ausgegeben.
*/

?>
    <div class="logo">
        <h1 class="semi-bold"><?php echo $this->html->image('itc_logo_ball.png'); ?><?php echo $systemname; ?></h1>
    </div>
    <div id="lockContainer">
        <?php
        if ($user['image'] != null && $user['image'] != ''):
            if (file_exists(WWW_ROOT . 'userimages' . DS . $user['image'])):
                echo $this->html->image('/userimages' . DS . $user['image'], ['width' => 120, 'height' => 'auto', 'id' => 'userImage', 'style' => 'border-left: 3px solid #40AC2B;']);
            else:
                echo $this->html->image('fallback_user.png', ['width' => 120, 'height' => 'auto', 'id' => 'userImage', 'style' => 'border-left: 3px solid #40AC2B;']);
            endif;
        else:
            echo $this->html->image('fallback_user.png', ['width' => 120, 'height' => 'auto', 'id' => 'userImage', 'style' => 'border-left: 3px solid #40AC2B;']);
        endif;
        ?>
        <div>
            <h1>
                <i class="fa fa-user fa-3x text-muted air air-top-right hidden-mobile"></i><?php echo $user['full_name']; ?>
                <small><i class="fa fa-lock text-muted"></i> &nbsp;<?php echo $language['locked']; ?></small>
            </h1>
            <p class="text-muted">
                <a href="mailto:<?php echo $user['email']; ?>"><?php echo $user['email']; ?></a>
            </p>
            <?php echo $this->Form->hidden('email', ['value' => $user['email']]); ?>
            <div class="input-group">
                <?php echo $this->Form->input('password', ['placeholder' => __('Password'), 'tabindex' => '1']); ?>
                <?php echo $this->Form->input('auth_method', ['type' => 'hidden', 'value' => $authMethod]); ?>
                <?php echo $this->Form->input('remember_me', ['type' => 'hidden', 'value' => 0]); ?>
                <div class="input-group-btn">
                    <button class="btn btn-primary" type="submit">
                        <i class="fa fa-key"></i>
                    </button>
                </div>
            </div>
            <p class="no-margin margin-top-5">
                <a href="/login/logout"><i class="fa fa-mail-forward"></i> Login as someone else? </a>
            </p>
        </div>

    </div>
    <p class="font-xs margin-top-5">
        Copyright <a href="http://it-novum.com" target="_blank">it-novum GmbH</a> 2005 - <?php echo date('Y'); ?>
    </p>
    </form>
<?php echo $this->Form->end(); ?>