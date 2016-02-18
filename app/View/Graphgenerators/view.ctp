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
<!-- Modal window -->
<script><?php // TODO replace this with smart notifications ?>
	window.bootstrapModalContent = <?php echo json_encode($modals) ?>;
	window.App.host_uuids = <?php echo json_encode($host_uuids); ?>;
	window.App.loaded_graph_config = <?php echo json_encode($graph_configuration); ?>;
</script>
<div class="overlay" style="display: none;">
	<div id="nag_longoutput_loader"
		 style="position: absolute; top: 50%; left: 50%; margin-top: -29px; margin-left: -23px; z-index: 20; font-size: 40px; color: #fff;">
		<i class="fa fa-cog fa-lg fa-spin"></i>
	</div>
</div>
<section id="widget-grid" class="" style="min-height:401px; background-color:#FFF;">
	<div class="row">
		<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
			<div class="row" style="display:none;">
				<div class="btn-header pull-right hidden-mobile hidden-tablet">
					<span><a href="javascript:void(0);" id="globalServertime" style="font-weight:normal;" data-render-utc="<?php echo time(); ?>" data-render-servertime="<?php echo date('F d, Y H:i:s'); ?>" server-timezone-offset="<?php $d = new DateTime(); echo $d->getOffset(); ?>" data-original-title="<?php echo __('Server time'); ?>" data-placement="left" rel="tooltip" data-container="body"></a></span>
				</div>
				<div class="btn-header pull-right hidden-mobile hidden-tablet" style="display:none;">
					<?php App::uses('Timezone', 'Lib'); ?>
					<span><a href="javascript:void(0);" id="localClienttime" user-timezone="<?php echo h($this->Auth->user('timezone')); ?>" timezone-offset="<?php echo h(Timezone::getUserSystemOffset($this->Auth->user('timezone'))); ?>" data-original-title="<?php echo __('Your local time'); ?>" data-placement="left" rel="tooltip" data-container="body"></a></span>
				</div>
			</div>
			<div class="row">
				<div class="graph_legend" style="display: none;"></div>
				<div id="graph_loader" style="display: none; text-align: center;">
					<i class="fa fa-cog fa-4x fa-spin"></i>
				</div>
				<div id="graph_data_tooltip"></div>
				<div id="graph">
					<!-- Content will be added by JavaScript GraphComponent -->
				</div>
			</div>
			<div class="col-xs-12 col-md-3 col-lg-5" style="display:none;">
				<div class="row bold"><?php echo __('Servicerules'); ?></div>
				<div id="serviceRules" style="overflow: hidden">
					<!-- content added by AJAX --></div>
			</div>
		</article>
	</div>
</section>

