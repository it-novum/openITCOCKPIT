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
<?php $this->Paginator->options(array('url' => $this->params['named'])); ?>
<div class="row">
	<div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
		<h1 class="page-title txt-color-blueDark">
			<i class="fa fa-user fa-fw "></i>
				<?php echo __('Monitoring'); ?>
			<span>>
				<?php echo __('Contacts'); ?>
			</span>
		</h1>
	</div>
</div>

<section id="widget-grid" class="">
	<div class="row">
		<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
			<div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-1" data-widget-editbutton="false" >
				<header>
					<div class="widget-toolbar" role="menu">
						<?php
						if($this->Acl->hasPermission('add')):
							echo $this->Html->link(__('New'), '/'.$this->params['controller'].'/add', array('class' => 'btn btn-xs btn-success', 'icon' => 'fa fa-plus'));
							echo " "; //Fix HTML
						endif;
						echo $this->Html->link(__('Search'), 'javascript:', array('class' => 'oitc-list-filter btn btn-xs btn-primary toggle', 'hide-on-render' => 'true', 'icon' => 'fa fa-search'));
						if($isFilter):
							echo " "; //Fix HTML
							echo $this->ListFilter->resetLink(null, array('class' => 'btn-danger btn-xs', 'icon' => 'fa fa-times'));
						endif;
						?>
						</div>
						<div class="widget-toolbar" role="menu">
						<a href="javascript:void(0);" class="dropdown-toggle selector" data-toggle="dropdown"><i class="fa fa-lg fa-table"></i></a>
						<ul class="dropdown-menu arrow-box-up-right pull-right">
							<li style="width: 100%;"><a href="javascript:void(0)" class="select_datatable text-left" class="select_datatable text-left" my-column="1"><input type="checkbox" class="pull-left" /> &nbsp; <?php echo __('Contact name'); ?></a></li>
							<li style="width: 100%;"><a href="javascript:void(0)" class="select_datatable text-left" class="select_datatable text-left" my-column="2"><input type="checkbox" class="pull-left" /> &nbsp; <?php echo __('Description'); ?></a></li>
							<li style="width: 100%;"><a href="javascript:void(0)" class="select_datatable text-left" class="select_datatable text-left" my-column="3"><input type="checkbox" class="pull-left" /> &nbsp; <?php echo __('Email'); ?></a></li>
							<li style="width: 100%;"><a href="javascript:void(0)" class="select_datatable text-left" class="select_datatable text-left" my-column="4"><input type="checkbox" class="pull-left" /> &nbsp; <?php echo __('Pager'); ?></a></li>
							<li style="width: 100%;"><a href="javascript:void(0)" class="select_datatable text-left" class="select_datatable text-left" my-column="5"><input type="checkbox" class="pull-left" /> &nbsp; <?php echo __('Notifications (Host)'); ?></a></li>
							<li style="width: 100%;"><a href="javascript:void(0)" class="select_datatable text-left" class="select_datatable text-left" my-column="6"><input type="checkbox" class="pull-left" /> &nbsp; <?php echo __('Notifications (Service)'); ?></a></li>
						</ul>
						<div class="clearfix"></div>
					</div>
					<div class="jarviswidget-ctrls" role="menu">
					</div>
					<span class="widget-icon hidden-mobile"> <i class="fa fa-user"></i> </span>
					<h2 class="hidden-mobile"><?php echo __('Contacts'); ?> </h2>

				</header>

				<div>
					<div class="widget-body no-padding">
						<?php echo $this->ListFilter->renderFilterbox($filters, array(), '<i class="fa fa-search"></i> '.__('search'), false, false); ?>
						<div class="mobile_table">
							<table id="contact_list" class="table table-striped table-bordered smart-form" style="">
								<thead>
									<tr>
										<?php $order = $this->Paginator->param('order'); ?>
										<th class="no-sort" style="width: 15px;"><i class="fa fa-check-square-o fa-lg"></i></th>
										<th class="select_datatable no-sort"><?php echo $this->Utils->getDirection($order, 'Contact.name'); echo $this->Paginator->sort('Contact.name', __('Contact name')); ?></th>
										<th class="no-sort"><?php echo $this->Utils->getDirection($order, 'Contacts.description'); echo $this->Paginator->sort('Contact.description', __('Description')); ?></th>
										<th class="no-sort"><?php echo __('Email'); ?></th>
										<th class="no-sort"><?php echo __('Pager');?></th>
										<th class="no-sort"><?php echo __('Notifications (Host)'); ?></th>
										<th class="no-sort"><?php echo __('Notifications (Service)'); ?></th>
										<th class="no-sort"></th>
									</tr>
								</thead>
								<tbody>
									<?php
										$notification_settings = array(
											'host' => array(
												'notify_host_recovery',
												'notify_host_down',
												'notify_host_unreachable',
												'notify_host_flapping',
												'notify_host_downtime',
											),
											'service' => array(
												'notify_service_recovery',
												'notify_service_warning',
												'notify_service_unknown',
												'notify_service_critical',
												'notify_service_flapping',
												'notify_service_downtime',
											),
										);
									?>
									<?php foreach($all_contacts as $contact): ?>
										<tr>
											<td class="text-center" style="width: 15px;">
												<?php if($contact['allowEdit'] === true): ?>
													<input class="massChange" type="checkbox" name="contact[<?php echo $contact['Contact']['id']; ?>]" contactname="<?php echo h($contact['Contact']['name']); ?>" value="<?php echo $contact['Contact']['id']; ?>" />
												<?php endif; ?>
											</td>
											<td><?php echo $contact['Contact']['name']; ?></td>
											<td><?php echo $contact['Contact']['description']; ?></td>
											<td><?php echo $contact['Contact']['email']; ?></td>
											<td><?php echo $contact['Contact']['phone']; ?></td>
											<?php foreach ($notification_settings as $key => $notification_settings_arr): ?>
												<?php
												$notification_status = 'success';
												$notification_status_message = 'On';
												if(!$contact['Contact'][$key.'_notifications_enabled']):
													$notification_status = 'danger';
													$notification_status_message = 'Off';
												endif;
													?>
												<td>
													<div>
														<i class="fa fa-envelope-o"></i><?php echo __('Notifications enabled:'); ?>
														<span class="onoffswitch">
															<input type="checkbox" id="<?php echo $contact['Contact']['id'].$key; ?>NotificationsEnabled" <?php echo ($contact['Contact'][$key.'_notifications_enabled'])?' checked="checked" ':''; ?> class="onoffswitch-checkbox" name="onoffswitch" disabled="disabled">
															<label for="<?php echo $contact['Contact']['id'].$key; ?>NotificationsEnabled" class="onoffswitch-label" style="cursor:default;">
																<span data-swchoff-text="<?php echo __('Off'); ?>" data-swchon-text="<?php echo __('On'); ?>" class="onoffswitch-inner"></span>
																<span class="onoffswitch-switch"></span>
															</label>
														</span>
													</div>
													<div style="margin-top:10px;">
													<?php foreach($notification_settings_arr as $notification_setting):?>
													<?php echo (($contact['Contact'][$notification_setting])?'<i class="fa fa-check txt-color-green"></i>':'<i class="fa fa-times txt-color-red"></i>').' '.$notification_setting;?>
														<br />
													<?php endforeach;?>
													</div>
												</td>
											<?php endforeach; ?>
											<td>
												<?php if($contact['allowEdit'] === true && $this->Acl->hasPermission('edit')): ?>
													<center><a href="/<?php echo $this->params['controller']; ?>/edit/<?php echo $contact['Contact']['id']; ?>" data-original-title="<?php echo __('edit'); ?>"><i id="list_edit" class="fa fa-gear fa-lg txt-color-teal"></i></a></center>
												<?php endif; ?>
											</td>
										</tr>
									<?php endforeach; ?>
								</tbody>
							</table>
						</div>
						
						<?php echo $this->element('contacts_mass_changes');?>
						
						<div style="padding: 5px 10px;">
							<div class="row">
								<div class="col-sm-6">
									<div class="dataTables_info" style="line-height: 32px;" id="datatable_fixed_column_info"><?php echo $this->Paginator->counter(__('paginator.showing').' {:page} '.__('of').' {:pages}, '.__('paginator.overall').' {:count} '.__('entries')); ?></div>
								</div>
								<div class="col-sm-6 text-right">
									<div class="dataTables_paginate paging_bootstrap">
										<?php echo $this->Paginator->pagination(array(
											'ul' => 'pagination'
										)); ?>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
	</div>
</section>
