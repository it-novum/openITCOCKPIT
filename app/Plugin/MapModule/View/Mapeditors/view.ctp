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
<?php if(!$isFullscreen): ?>
	<div class="row">
		<div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
			<h1 class="page-title txt-color-blueDark">
				<i class="fa fa-map-marker fa-fw "></i>
					<?php echo __('Map'); ?>
				<span>>
					<?php echo __('View'); ?>
				</span>
			</h1>
		</div>
	</div>
<?php endif; ?>
<div id="error_msg"></div>

<div class="jarviswidget" id="wid-id-0">
	<header>
		<span class="widget-icon"> <i class="fa fa-map-marker"></i> </span>
		<h2><?php echo __('View map');?></h2>
		<div class="widget-toolbar" role="menu">
			<?php echo $this->Utils->backButton(null,'/map_module/maps');?>
			<?php if(!$isFullscreen): ?>
				<a href="<?php echo Router::url(['controller' => 'mapeditors', 'action' => 'view', 'plugin' => 'map_module', 'fullscreen' => 1, $map['Map']['id']]); ?>" class="btn btn-default btn-xs"><i class="glyphicon glyphicon-resize-full"></i> <?php echo __('Open fullscreen');?></a>
			<?php else: ?>
				<a href="<?php echo Router::url(['controller' => 'mapeditors', 'action' => 'view', 'plugin' => 'map_module', $map['Map']['id']]); ?>" class="btn btn-default btn-xs"><i class="glyphicon glyphicon-resize-small"></i> <?php echo __('Leave fullscreen');?></a>
			<?php endif; ?>
		</div>
	</header>
	<div id="map-editor">
		<?php
			$css = '';
			if($map['Map']['background'] != null && $map['Map']['background'] != ''):
				$filePath = $backgroundThumbs['backgrounds']['path'].'/'.$map['Map']['background'];
				if(file_exists($filePath)):
					$size = getimagesize($backgroundThumbs['backgrounds']['path'].DS.$map['Map']['background']);
					$css = 'width: '.$size[0].'px; height: '.$size[1].'px; background-image: url('.$backgroundThumbs['backgrounds']['webPath'].'/'.$map['Map']['background'].'); background-repeat: no-repeat';
				else:
					echo '<div class="alert alert-danger fade in">
							<button class="close" data-dismiss="alert">×</button>
							<i class="fa-fw fa fa-times"></i>
							<strong>'.__('Error!').'</strong> '.__('Loading Background image failed!').'
						</div>';
				endif;
			endif;
			?>
		<div class="widget-body">
			<br>
			<!-- Map draw container -->
			<div id="MapContainer" class="well" style="overflow: scroll;">

				<div id="jsPlumb_playground" class="resetMargin" style="min-height:600px; <?php echo $css; ?>">
					<?php App::uses('UUID', 'Lib'); ?>

					<!-- Icons -->
					<?php foreach($map_items as $item):
						$uuid = UUID::v4();
						?>
					<?php
						switch ($item['Mapitem']['type']) {
							case 'host':
								$state = $this->Mapstatus->hoststatus($item['Host']['uuid']);
								break;
							case 'service':
								$state = $this->Mapstatus->servicestatus($item['Service']['uuid']);
								break;
							case 'servicegroup':
								$state = $this->Mapstatus->servicegroupstatus($item['Servicegroup']['uuid']);
								break;
							case 'hostgroup':
								$state = $this->Mapstatus->hostgroupstatus($item['Hostgroup']['uuid']);
								break;
							case 'map':
								$state = $this->Mapstatus->mapstatus($item['Map']['id']);
								break;
						}
						?>
						<!-- add icons -->
						<?php if($item['Mapitem']['type'] == 'map'): ?>
						<div id="<?php echo $uuid; ?>" 
						class="elementHover" 
						data-type="<?php echo ucfirst($item['Mapitem']['type']); ?>" 
						data-uuid="<?php echo $item['Map']['id']; ?>" style="position:absolute; top: <?php echo $item['Mapitem']['y']; ?>px; left: <?php echo $item['Mapitem']['x']; ?>px;">
						<a href="/<?php echo 'map_module/mapeditors/view/'. $item['Mapitem']['object_id']; ?>">
					<?php else:?>
						<div id="<?php echo $uuid; ?>" class="elementHover" data-type="<?php echo ucfirst($item['Mapitem']['type']); ?>" data-uuid="<?php echo $item[ucfirst($item['Mapitem']['type'])]['uuid']; ?>" style="position:absolute; top: <?php echo $item['Mapitem']['y']; ?>px; left: <?php echo $item['Mapitem']['x']; ?>px;">
						<?php
							if($item['Mapitem']['type'] !== 'servicegroup'):
						?>
						<a href="/<?php echo Inflector::pluralize($item['Mapitem']['type']); ?>/<?php echo ($item['Mapitem']['type'] === 'hostgroup')?'extended':'browser';?>/<?php echo $item[ucfirst($item['Mapitem']['type'])]['id']; ?>">
							<?php
							endif;
							?>
					<?php endif; ?>
							<img src="/map_module/img/items/<?php echo $item['Mapitem']['iconset']; ?>/<?php echo $state['image']; ?>">
							<!-- hidden data field -->
							<input type="hidden" name="data[Mapitem][<?php echo $uuid; ?>][<?php echo $item['Mapitem']['type']; ?>_id]" value="<?php echo $item[ucfirst($item['Mapitem']['type'])]['id']; ?>" />
						</a>
						</div>
					<?php endforeach; ?>

					<!-- Lines -->
					<?php foreach($map_lines as $line):
						$uuid = UUID::v4();
						?>
						<?php
						switch ($line['Mapline']['type']) {
							case 'host':
								$state = $this->Mapstatus->hoststatus($line['Host']['uuid']);
								$lineColor = $this->Status->HostStatusColorSimple($state['state']);
								break;
							case 'service':
								$state = $this->Mapstatus->servicestatus($line['Service']['uuid']);
								$lineColor = $this->Status->ServiceStatusColorSimple($state['state']);
								break;
							case 'servicegroup':
								$state = $this->Mapstatus->servicegroupstatus($line['Servicegroup']['uuid']);
								$lineColor = $this->Status->ServiceStatusColorSimple($state['state']);
								break;
							case 'hostgroup':
								$state = $this->Mapstatus->hostgroupstatus($line['Hostgroup']['uuid']);
								$lineColor = $this->Status->ServiceStatusColorSimple($state['state']);
								break;
						}
						?>
						<!-- add container for lines -->
						<div id="<?php echo $uuid; ?>" data-lineId="<?php echo $line['Mapline']['id'] ?>" class="lineContainer">
							<input type="hidden" name="data[Mapline][<?php echo $uuid; ?>][<?php echo $line['Mapline']['type']; ?>_id]" value="<?php echo $line[ucfirst($line['Mapline']['type'])]['id'] ?>">
							<input type="hidden" id="popoverType_<?php echo $line['Mapline']['id']; ?>" class="popoverTypeHidden" data-type="<?php echo $line['Mapline']['type']; ?>" data-uuid="<?php echo $line[ucfirst($line['Mapline']['type'])]['uuid']; ?>" data-color="<?php echo $lineColor['hexColor']; ?>" data-link="/<?php echo Inflector::pluralize($line['Mapline']['type']); ?>/browser/<?php echo $line[ucfirst($line['Mapline']['type'])]['id']; ?>">
						</div>
				<?php endforeach; ?>

				<!-- Gadgets -->
				<?php
					foreach($map_gadgets as $gadget):
						$uuid = UUID::v4();
						?>
					<?php
						switch ($gadget['Mapgadget']['type']) {
							case 'host':
								$state = $this->Mapstatus->hoststatus($gadget['Host']['uuid']);
								break;
							case 'service':
								$state = $this->Mapstatus->servicestatus($gadget['Service']['uuid']);
								break;
							case 'servicegroup':
								$state = $this->Mapstatus->servicegroupstatus($gadget['Servicegroup']['uuid']);
								break;
							case 'hostgroup':
								$state = $this->Mapstatus->hostgroupstatus($gadget['Hostgroup']['uuid']);
								break;
						}
						$RRDGraphLink = '';
						if(ucfirst($gadget['Mapgadget']['type']) == 'Service' && $gadget['Mapgadget']['gadget'] == 'RRDGraph'){
							$Rrd = ClassRegistry::init('Rrd');
							$rrd_path = Configure::read('rrd.path');
							if(file_exists($rrd_path.$gadget['Service']['host_uuid'].DS.$gadget['Service']['uuid'].'.xml')):
								$rrd_structure_datasources = $Rrd->getPerfDataStructure($rrd_path.$gadget['Service']['host_uuid'].DS.$gadget['Service']['uuid'].'.xml');
								$rrdBackgroundColor = 'BACK#FFFFFFFF';
								if(isset($gadget['Mapgadget']['transparent_background']) && $gadget['Mapgadget']['transparent_background'] == true){
									$rrdBackgroundColor = 'BACK#FFFFFF00';
								}
								$options = [
									'start' => strtotime('1 hour ago'),
									'end' => time(),
									'path' => $rrd_path,
									'host_uuid' => $gadget['Service']['host_uuid'],
									'service_uuid' => $gadget['Service']['uuid'],
									'width' => 300,
									'color' => [
										$rrdBackgroundColor,
										'CANVAS#FFFFFF99',
										'ARROW#000000FF',
										'SHADEA#FFFFFF00',
										'SHADEB#FFFFFF00',
									]
								];
								$RRDGraphLink = $Rrd->createRrdGraph($rrd_structure_datasources[0],$options, [], true)['webPath'];
							endif;
						}
						?>
						<!-- add gadget data field -->
						<div id="<?php echo $uuid; ?>" data-uuid="<?php echo $gadget[ucfirst($gadget['Mapgadget']['type'])]['uuid']; ?>" class="gadgetContainer">
						<a href="/<?php echo Inflector::pluralize($gadget['Mapgadget']['type']); ?>/browser/<?php echo $gadget[ucfirst($gadget['Mapgadget']['type'])]['id']; ?>">
						<?php
							if($gadget['Mapgadget']['type'] == 'host' || $gadget['Mapgadget']['type'] == 'service'):
						?>
							<!-- hidden data field -->
							<input
							type="hidden"
							id="popoverGadgetType_<?php echo $gadget['Mapgadget']['id']; ?>"
							class="popoverGadgetTypeHidden"
							data-type="<?php echo $gadget['Mapgadget']['type']; ?>"
							data-uuid="<?php echo $gadget[ucfirst($gadget['Mapgadget']['type'])]['uuid']; ?>"
							data-hostUuid="<?php echo (ucfirst($gadget['Mapgadget']['type']) == 'Service'?$gadget['Service']['host_uuid']:''); ?>"
							data-rrdlink="<?php echo (isset($RRDGraphLink)?$RRDGraphLink:'');?>"
							data-link="/<?php echo Inflector::pluralize($gadget['Mapgadget']['type']); ?>/browser/<?php echo $gadget[ucfirst($gadget['Mapgadget']['type'])]['id']; ?>"
							data-perfdata='<?php echo (empty($state['perfdata']))?'':json_encode($this->Perfdata->parsePerfData($state['perfdata'])); ?>'
							data-state='<?php echo $state['state']; ?>'
							data-flapping='<?php echo $state['is_flapping'] ?>'>
						<?php endif;?>
						</a>
						</div>
					<?php endforeach; ?>
				<!-- Stateless Icons -->
					<?php foreach ($map['Mapicon'] as $key => $icon):
							$uuid = UUID::v4(); ?>
							<div id="<?php echo $uuid; ?>" class="statelessIconContainer" style="position:absolute;top:<?php echo $icon['y']; ?>px;left:<?php echo $icon['x']; ?>px;">
								<img src="/map_module/img/icons/<?php echo $icon['icon']; ?>" />
							</div>
					<?php endforeach; ?>

					<?php foreach ($map_texts as $text):
							$uuid = UUID::v4();?>
							<div id="<?php echo $uuid; ?>" class="textContainer" style="position:absolute;top:<?php echo $text['Maptext']['y']; ?>px;left:<?php echo $text['Maptext']['x']; ?>px;">
								<span id="spanText_<?php echo $uuid; ?>" class="textElement" style="font-size:<?php echo $text['Maptext']['font_size']; ?>px;"><?php echo $text['Maptext']['text']; ?></span>
							</div>
					<?php endforeach; ?>
				</div>
			</div>
			<?php echo $this->Form->end(); ?>
		</div>
	</div>
</div>