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
<?php $this->Paginator->options(['url' => Hash::merge($this->params['named'], $this->params['pass'], ['Listsettings' => $AcknowledgementListsettings])]); ?>
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-5 col-lg-5">
        <h1 class="page-title <?php echo $this->Status->HostStatusColor($host['Host']['uuid']); ?>">
            <?php echo $this->Monitoring->serviceFlappingIcon($this->Status->get($host['Host']['uuid'], 'is_flapping'), 'padding-left-5'); ?>
            <i class="fa fa-cog fa-fw"></i>
            <?php echo h($host['Host']['name']); ?>
            <span>
				&nbsp;<?php echo __('on'); ?>&nbsp;
			</span>
        </h1>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7">
        <h5>
            <div class="pull-right">
                <a href="/hosts/browser/<?php echo $host['Host']['id']; ?>" class="btn btn-primary btn-sm"><i
                            class="fa fa-arrow-circle-left"></i> <?php echo $this->Html->underline('b', __('Back to Host')); ?>
                </a>
                <?php echo $this->element('host_browser_menu'); ?>
            </div>
        </h5>
    </div>
</div>

<section id="widget-grid" class="">

    <div class="row">
        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-1" data-widget-editbutton="false">
                <header>
                    <div class="widget-toolbar" role="menu">
                        <?php echo $this->Html->link(__('Search'), 'javascript:', ['class' => 'oitc-list-filter btn btn-xs btn-primary toggle', 'hide-on-render' => 'true', 'icon' => 'fa fa-search']); ?>
                        <?php
                        if ($isFilter):
                            echo $this->ListFilter->resetLink(null, ['class' => 'btn-danger btn-xs', 'icon' => 'fa fa-times']);
                        endif;
                        ?>
                    </div>
                    <div class="widget-toolbar" role="menu">
                        <a href="javascript:void(0);" class="dropdown-toggle selector" data-toggle="dropdown"><i
                                    class="fa fa-lg fa-table"></i></a>
                        <ul class="dropdown-menu arrow-box-up-right pull-right">
                            <li style="width: 100%;"><a href="javascript:void(0)" class="select_datatable text-left"
                                                        my-column="0"><input type="checkbox" class="pull-left"/>
                                    &nbsp; <?php echo __('State'); ?></a></li>
                            <li style="width: 100%;"><a href="javascript:void(0)" class="select_datatable text-left"
                                                        my-column="1"><input type="checkbox" class="pull-left"/>
                                    &nbsp; <?php echo __('Date'); ?></a></li>
                            <li style="width: 100%;"><a href="javascript:void(0)" class="select_datatable text-left"
                                                        my-column="2"><input type="checkbox" class="pull-left"/>
                                    &nbsp; <?php echo __('Author'); ?></a></li>
                            <li style="width: 100%;"><a href="javascript:void(0)" class="select_datatable text-left"
                                                        my-column="3"><input type="checkbox" class="pull-left"/>
                                    &nbsp; <?php echo __('Comment'); ?></a></li>
                            <li style="width: 100%;"><a href="javascript:void(0)" class="select_datatable text-left"
                                                        my-column="4"><input type="checkbox" class="pull-left"/>
                                    &nbsp; <?php echo __('Sticky'); ?></a></li>
                        </ul>
                        <div class="clearfix"></div>
                    </div>

                    <div id="switch-1" class="widget-toolbar" role="menu">
                        <?php
                        echo $this->Form->create('acknowledgements', [
                            'class' => 'form-horizontal clear',
                            'url'   => 'host/'.$host['Host']['id'] //reset the URL on submit
                        ]);

                        ?>

                        <div class="widget-toolbar pull-left" role="menu">
                            <span style="line-height: 32px;" class="pull-left"><?php echo __('From:'); ?></span>
                            <input class="form-control text-center pull-left margin-left-10" style="width: 78%;"
                                   type="text" maxlength="255"
                                   value="<?php echo $AcknowledgementListsettings['from']; ?>"
                                   name="data[Listsettings][from]">
                        </div>

                        <div class="widget-toolbar pull-left" role="menu">
                            <span style="line-height: 32px;" class="pull-left"><?php echo __('To:'); ?></span>
                            <input class="form-control text-center pull-left margin-left-10" style="width: 85%;"
                                   type="text" maxlength="255" value="<?php echo $AcknowledgementListsettings['to']; ?>"
                                   name="data[Listsettings][to]">
                        </div>

                        <div class="btn-group">
                            <?php
                            $listoptions = [
                                '30'  => [
                                    'submit_target' => '#listoptions_hidden_limit',
                                    'value'         => 30,
                                    'human'         => 30,
                                    'selector'      => '#listoptions_limit',
                                ],
                                '50'  => [
                                    'submit_target' => '#listoptions_hidden_limit',
                                    'value'         => 50,
                                    'human'         => 50,
                                    'selector'      => '#listoptions_limit',
                                ],
                                '100' => [
                                    'submit_target' => '#listoptions_hidden_limit',
                                    'value'         => 100,
                                    'human'         => 100,
                                    'selector'      => '#listoptions_limit',
                                ],
                                '300' => [
                                    'submit_target' => '#listoptions_hidden_limit',
                                    'value'         => 300,
                                    'human'         => 300,
                                    'selector'      => '#listoptions_limit',
                                ],
                            ];

                            $selected = 30;
                            if (isset($AcknowledgementListsettings['limit']) && isset($listoptions[$AcknowledgementListsettings['limit']]['human'])) {
                                $selected = $listoptions[$AcknowledgementListsettings['limit']]['human'];
                            }
                            ?>
                            <button data-toggle="dropdown" class="btn dropdown-toggle btn-xs btn-default">
                                <span id="listoptions_limit"><?php echo $selected; ?></span> <i
                                        class="fa fa-caret-down"></i>
                            </button>
                            <ul class="dropdown-menu pull-right">
                                <?php foreach ($listoptions as $listoption): ?>
                                    <li>
                                        <a href="javascript:void(0);" class="listoptions_action"
                                           selector="<?php echo $listoption['selector']; ?>"
                                           submit_target="<?php echo $listoption['submit_target']; ?>"
                                           value="<?php echo $listoption['value']; ?>"><?php echo $listoption['human']; ?></a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                            <input type="hidden"
                                   value="<?php if (isset($AcknowledgementListsettings['limit'])): echo $AcknowledgementListsettings['limit']; endif; ?>"
                                   id="listoptions_hidden_limit" name="data[Listsettings][limit]"/>
                        </div>


                        <?php
                        $state_types = [
                            'up'          => __('Up'),
                            'down'        => __('Down'),
                            'unreachable' => __('Unreachable'),
                        ];
                        ?>

                        <div class="btn-group">
                            <button data-toggle="dropdown" class="btn dropdown-toggle btn-xs btn-default">
                                <?php echo __('State types'); ?> <i class="fa fa-caret-down"></i>
                            </button>
                            <ul class="dropdown-menu pull-right">
                                <?php
                                foreach ($state_types as $state_type => $name):
                                    $checked = '';
                                    if (isset($AcknowledgementListsettings['state_types'][$state_type]) && $AcknowledgementListsettings['state_types'][$state_type] == 1):
                                        $checked = 'checked="checked"';
                                    endif;
                                    ?>
                                    <li>
                                        <input type="hidden" value="0"
                                               name="data[Listsettings][state_types][<?php echo $state_type; ?>]"/>
                                    <li style="width: 100%;"><a href="javascript:void(0)"
                                                                class="listoptions_checkbox text-left"><input
                                                    type="checkbox"
                                                    name="data[Listsettings][state_types][<?php echo $state_type; ?>]"
                                                    value="1" <?php echo $checked; ?>/> &nbsp; <?php echo $name; ?></a>
                                    </li>
                                    </li>
                                <?php endforeach ?>
                            </ul>
                        </div>

                        <button class="btn btn-xs btn-success toggle"><i
                                    class="fa fa-check"></i> <?php echo __('Apply'); ?></button>

                        <?php
                        echo $this->Form->end();
                        ?>
                    </div>

                    <div class="jarviswidget-ctrls" role="menu">
                    </div>
                    <span class="widget-icon"> <i class="fa fa-history"></i> </span>
                    <h2><?php echo __('Acknowledgement history'); ?> </h2>

                </header>

                <!-- widget div-->
                <div>

                    <!-- widget content -->
                    <div class="widget-body no-padding">
                        <?php echo $this->ListFilter->renderFilterbox($filters, ['formActionParams' => ['url' => Router::url(Hash::merge($this->params['named'], $this->params['pass'], ['Listsettings' => $AcknowledgementListsettings])), 'merge' => false]], '<i class="fa fa-search"></i> '.__('Search'), false, false); ?>

                        <table id="acknowledgements_list" class="table table-striped table-bordered smart-form"
                               style="">
                            <thead>
                            <tr>
                                <?php $order = $this->Paginator->param('order'); ?>
                                <th class="no-sort"><?php echo $this->Utils->getDirection($order, 'Acknowledged.state');
                                    echo $this->Paginator->sort('Acknowledged.state', __('State')); ?></th>
                                <th class="no-sort"><?php echo $this->Utils->getDirection($order, 'Acknowledged.entry_time');
                                    echo $this->Paginator->sort('Acknowledged.entry_time', __('Date')); ?></th>
                                <th class="no-sort"><?php echo $this->Utils->getDirection($order, 'Acknowledged.author_name');
                                    echo $this->Paginator->sort('Acknowledged.author_name', __('Author')); ?></th>
                                <th class="no-sort"><?php echo $this->Utils->getDirection($order, 'Acknowledged.comment_data');
                                    echo $this->Paginator->sort('Acknowledged.comment_data', __('Comment')); ?></th>
                                <th class="no-sort"><?php echo $this->Utils->getDirection($order, 'Acknowledged.is_sticky');
                                    echo $this->Paginator->sort('Acknowledged.is_sticky', __('Sticky')); ?></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php //debug($all_notification); ?>
                            <?php foreach ($all_acknowledgements as $acknowledgement): ?>
                                <tr>
                                    <td class="text-center"><?php echo $this->Status->humanHostStatus($host['Host']['uuid'], 'javascript:void(0)', [$host['Host']['uuid'] => ['Hoststatus' => ['current_state' => $acknowledgement['Acknowledged']['state']]]])['html_icon']; ?></td>
                                    <td><?php echo $this->Time->format($acknowledgement['Acknowledged']['entry_time'], $this->Auth->user('dateformat'), false, $this->Auth->user('timezone')); ?></td>
                                    <td><?php echo h($acknowledgement['Acknowledged']['author_name']); ?></td>
                                    <td><?php echo h($acknowledgement['Acknowledged']['comment_data']); ?></td>
                                    <td>
                                        <?php
                                        if ($acknowledgement['Acknowledged']['is_sticky'] == 1):
                                            echo __('True');
                                        else:
                                            echo __('False');
                                        endif;
                                        ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                        <?php if (empty($all_acknowledgements)): ?>
                            <div class="noMatch">
                                <center>
                                    <span class="txt-color-red italic"><?php echo __('No entries match the selection'); ?></span>
                                </center>
                            </div>
                        <?php endif; ?>

                        <div style="padding: 5px 10px;">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="dataTables_info" style="line-height: 32px;"
                                         id="datatable_fixed_column_info"><?php echo $this->Paginator->counter(__('Page').' {:page} '.__('of').' {:pages}, '.__('Total').' {:count} '.__('entries')); ?></div>
                                </div>
                                <div class="col-sm-6 text-right">
                                    <div class="dataTables_paginate paging_bootstrap">
                                        <?php echo $this->Paginator->pagination([
                                            'ul' => 'pagination',
                                        ]); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </div>
</section>
