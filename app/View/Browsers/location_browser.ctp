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
<ol class="breadcrumb">
	<?php
	$current_node = $top_node;
	if($top_node['Container']['parent_id'] != null):
		foreach($parents as $parent):
			if($parent['Container']['containertype_id'] == CT_GLOBAL){
				echo '<li>'.$this->Html->link($parent['Container']['name'], 'index/'.$parent['Container']['id']).'</li>';
			}else{
				echo '<li>'.$this->Html->link($parent['Container']['name'], $this->BrowserMisc->browserLink($parent['Container']['containertype_id']).'/'.$parent['Container']['id']).'</li>';
			}
		endforeach;
	endif;
	?>
	<li class="active"><?php echo $current_node['Container']['name']; ?><li>
</ol>

<div class="row">
	<article class="col-sm-2 col-md-2 col-lg-2">
		<div data-widget-fullscreenbutton="false" data-widget-editbutton="false" id="wid-id-1" class="jarviswidget jarviswidget-color-blueDark" style="" role="widget">
			<header role="heading">
				<span class="widget-icon"> <i class="fa fa-list-ul  txt-color-white"></i> </span>
				<h2> <?php echo __('nodes'); ?> </h2>
				<!-- <div class="widget-toolbar" role="menu"></div> -->
			</header>
			<div role="content">
				<div class="widget-body widget-hide-overflow">
						
						<?php foreach($browser as $b): ?>
							<!--<?php 
							$faClass = $this->BrowserMisc->containertypeIcon($b['containertype_id']);
							$link = $this->BrowserMisc->browserLink($b['containertype_id']);
							?>
							<?php debug($b); ?>
							<i class="fa <?php echo $faClass; ?>"></i>
							<?php //echo $this->Html->link($b['Host']['name'], $link.'/'.$b['Host']['id']); ?>
							-->
							<br /> 
						<?php endforeach; ?>
				</div>
			</div>
		</div>
	</article>
</div>