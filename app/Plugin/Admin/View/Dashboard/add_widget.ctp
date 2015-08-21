<div widget-id="<?php echo $result['Widget']['id'];?>" class="grid-stack-item" data-gs-x="0" data-gs-y="0" data-gs-width="<?php echo $result['Widget']['width'];?>" data-gs-height="<?php echo $result['Widget']['height'];?>">
	<div class="grid-stack-item-content">
		<div class="jarviswidget jarviswidget-color-blue jarviswidget-sortable" role="widget">
			<header role="heading">
				<div class="jarviswidget-ctrls" role="menu">
					<a href="javascript:void(0);" class="button-icon jarviswidget-edit-btn" rel="tooltip" title="" data-placement="bottom" data-original-title="<?php echo __('Edit title'); ?>"><i class="fa fa-cog "></i></a>
					<div class="widget-toolbar" role="menu">
						<a data-toggle="dropdown" class="data-widget-colorbutton button-icon dropdown-toggle color-box selector" href="javascript:void(0);"></a>
						<ul class="dropdown-menu arrow-box-up-right color-select pull-right">
							<?php foreach($barColors as $color => $name): ?>
								<li>
									<span class="bg-color-<?php echo $color; ?> color-bar-picker"
										 data-color="<?php echo $name['color']; ?>"
										 data-widget-setstyle="jarviswidget-color-<?php echo $color; ?>"
										 rel="tooltip" data-placement="top"
										 data-original-title="<?php echo $name['title']; ?>">
									</span>
								</li>
							<?php endforeach ?>
						</ul>
					</div>
					<a href="javascript:void(0);" class="button-icon jarviswidget-delete-btn" rel="tooltip" title="" data-placement="bottom" data-original-title="<?php echo __('Delete'); ?>"><i class="fa fa-times"></i></a>
				</div>
				<h2><i class='fa fa-<?php echo $widgetTypes[$result['Widget']['type_id']]['icon']?>'>&nbsp;</i><?php echo h($result['Widget']['title']);?></h2>
			</header>
			<div role="content">
				<?php
					echo $this->Widget->get($result['Widget']['type_id']);
				?>
			</div>
		</div>
	</div>
</div>