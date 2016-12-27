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
<?php //  Router::url(array('controller' => 'commands', 'action' => 'delete')) ?>
<div class="row">
    <div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
        <h1 class="page-title txt-color-blueDark">
            <i class="fa fa-terminal fa-fw "></i>
            Nagios
            <span>> 
				Commands
			</span>
            <div class="third_level"> <?php echo ucfirst($this->params['action']); ?></div>
        </h1>
    </div>
</div>
<div id="error_msg"></div>

<div class="jarviswidget" id="wid-id-0">
    <header>
        <span class="widget-icon"> <i class="fa fa-terminal"></i> </span>
        <h2>Edit command</h2>
        <div class="widget-toolbar" role="menu">
            <?php echo $this->Utils->deleteButton(null, $command[0]['Command']['object_id']); ?>
            <?php echo $this->Utils->backButton(); ?>
        </div>
    </header>
    <div>
        <div class="widget-body">
            <?php
            echo $this->Form->create('Command', [
                'class' => 'form-horizontal clear',
            ]);
            echo $this->Form->input('Command.object_id', ['type' => 'hidden', 'value' => $command[0]['Command']['object_id']]);
            echo $this->Form->input('Objects.name1', ['value' => $command[0]['Objects']['name1']]);
            echo $this->Form->input('command_line', ['value' => $command[0]['Command']['command_line']]);
            ?>
            <br/>
            <div id="console"></div>
            <br/>
            <?php echo $this->Form->formActions(); ?>
        </div>
    </div>
</div>