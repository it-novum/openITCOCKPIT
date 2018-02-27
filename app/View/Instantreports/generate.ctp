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
<div class="row">
    <div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
        <h1 class="page-title txt-color-blueDark">
            <i class="fa fa-newspaper-o"></i>
            <?php echo __('Reporting'); ?>
            <span>>
                <?php echo __('Instant Report'); ?>
            </span>
        </h1>
    </div>
</div>
<div id="error_msg"></div>
<div>
    <article class="col-sm-12 col-md-12 col-lg-12">
        <!-- Widget ID (each widget will need unique ID)-->
        <div class="jarviswidget" id="wid-id-2" data-widget-editbutton="false" data-widget-deletebutton="false">
            <header>
                <span class="widget-icon"> <i class="fa fa-file-image-o"></i> </span>
                <h2><?php echo __('Create Instant Report'); ?></h2>
                <div class="widget-toolbar" role="menu">
                    <?php echo $this->Utils->backButton(); ?>
                </div>
            </header>
            <!-- widget div-->
            <div>
                <!-- widget content -->
                <div class="widget-body fuelux">
                    <?php
                    echo $this->Form->create('Instantreport', [
                        'class' => 'form-horizontal',
                        'id'    => 'fuelux-wizard',
                    ]);
                    ?>
                    <!-- wizard form starts here -->
                    <fieldset class="col col-xs-12">
                        <?php
                        echo $this->Form->input('id', [
                                'options'          => Hash::combine($allInstantReports, '{n}.Instantreport.id', '{n}.Instantreport.name'),
                                'data-placeholder' => __('Please select...'),
                                'class'            => 'chosen',
                                'label'            => __('Report'),
                                'style'            => 'width:100%;',
                                'selected'         => isset($this->request->data['Instantreport']['id']) ? $this->request->data['Instantreport']['id'] : $id,
                            ]
                        );
                        echo $this->Form->input('report_format', [
                                'options'          => $reportFormats,
                                'data-placeholder' => __('Please select...'),
                                'class'            => 'chosen',
                                'label'            => __('Report format'),
                                'style'            => 'width:100%;',
                                'selected'         => isset($this->request->data['Instantreport']['report_format']) ? $this->request->data['Instantreport']['report_format'] : 1
                            ]
                        );
                        echo $this->Form->input('start_date', [
                            'label' => __('From'),
                            'type'  => 'text',
                            'class' => 'form-control required',
                            'value' => $this->CustomValidationErrors->refill('start_date', date('d.m.Y', strtotime('-15 days'))),
                        ]);
                        echo $this->Form->input('end_date', [
                            'label'    => __('To'),
                            'type'     => 'text',
                            'class'    => 'form-control required',
                            'reguired' => true,
                            'value'    => $this->CustomValidationErrors->refill('end_date', date('d.m.Y', time())),
                        ]);

                        echo $this->Form->formActions(__('Create'));
                        ?>
                        <br/>
                    </fieldset>
                    <?php echo $this->Form->end(); ?>
                    <?php
                    if (isset($autoreport) && isset($autoreport_data)):
                        echo $this->element('load_report_data', [
                                'autoreport'      => $autoreport,
                                'autoreport_data' => $autoreport_data,
                            ]
                        );
                    endif;
                    ?>
                </div>
                <!-- end widget div -->
            </div>
            <!-- end widget -->
    </article>
    <!-- WIDGET END -->
</div>