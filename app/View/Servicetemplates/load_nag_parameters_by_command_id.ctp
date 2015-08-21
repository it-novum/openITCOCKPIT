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

/*
 *         _                    _               
 *   __ _ (_) __ ___  __ __   _(_) _____      __
 *  / _` || |/ _` \ \/ / \ \ / / |/ _ \ \ /\ / /
 * | (_| || | (_| |>  <   \ V /| |  __/\ V  V / 
 *  \__,_|/ |\__,_/_/\_\   \_/ |_|\___| \_/\_/  
 *      |__/                                    
*/

if(!empty($commandarguments)):
	foreach($commandarguments as $commandargument):
		echo $this->Form->input('Servicetemplateeventcommandargumentvalue.'.$commandargument['Commandargument']['id'].'.value', [
			'label' => [
				'class' => 'col col-md-2 control-label text-primary',
				'text' => $commandargument['Commandargument']['human_name']
			],
			'div' => [
				'class' => 'form-group'
			],
			'class' => 'form-control',
			'wrapInput' => 'col col-md-8',
			'value' => (isset($commandargument['Servicetemplateeventcommandargumentvalue']['value']) && $commandargument['Servicetemplateeventcommandargumentvalue']['value'] !== null)?$commandargument['Servicetemplateeventcommandargumentvalue']['value']:''
		]);
		echo $this->Form->input('Servicetemplateeventcommandargumentvalue.'.$commandargument['Commandargument']['id'].'.commandargument_id',[
			'type' => 'hidden',
			'value' => $commandargument['Commandargument']['id']
		]);
		// debug($commandargument);
		if(isset($commandargument['Servicetemplateeventcommandargumentvalue']['id']) && $commandargument['Servicetemplateeventcommandargumentvalue']['id'] !== null):
			echo $this->Form->input('Servicetemplateeventcommandargumentvalue.'.$commandargument['Commandargument']['id'].'.id',[
				'type' => 'hidden',
				'value' => $commandargument['Servicetemplateeventcommandargumentvalue']['id']
			]);
		endif;
	endforeach;
else:
	?>
	<div class="form-group">
		<label class="col col-md-2 control-label hidden-mobile hidden-tablet"><!-- spacer for nice layout --></label>
		<label class="col col-md-8 col-xs-12 text-primary"><i class="fa fa-info-circle"></i> <?php echo __('no parameters for this command defined'); ?></label>
	</div>
	<?php
endif;
