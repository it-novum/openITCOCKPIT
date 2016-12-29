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

/*
We need to find a better solution!
<div class="form-group">
   <label class="col col-md-2 control-label" for="ContainerName"><?php echo __('parent node'); ?></label>
   <div class="col col-xs-10">
       <select class="select2, select_path" data-placeholder="<?php echo __('please select');?>">
           <!--
               We need an empty option for the browsers -.-
               See: http://ivaynberg.github.io/select2/
           -->
           <option></option>
           <?php foreach($paths as $parent_id => $path): ?>
               <option value="<?php echo $parent_id; ?>"><?php echo $path; ?>
           <?php endforeach; ?>
       </select>
       <?php //echo $this->Form->input('Container.parent_id', array('options' => ($paths), 'class' => 'select2, select_path', 'label' => false, 'data-placeholder' => __('please select'))); ?>
   </div>
</div>
*/
?>
<div class="form-group">
    <label class="col col-md-2 control-label" for="ContainerName"><?php echo __('parent node'); ?></label>
    <div class="col col-xs-10">
        <?php echo $this->Form->input('Container.parent_id', ['options' => ($paths), 'class' => 'chosen select_path', 'label' => false, 'data-placeholder' => __('please select')]); ?>
    </div>
</div>