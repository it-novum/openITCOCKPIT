<div class="row">
    <div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
        <h1 class="page-title txt-color-blueDark">
            <i class="fa fa-dashboard fa-fw "></i>
            <?php echo __('Dashboard'); ?>
        </h1>
    </div>
</div>
<div class="jarviswidget jarviswidget-color-blueDark">
    <header role="heading" class="DashboardMainHeader">
        <div class="tabsContainer">
            <ul id="widget-tab-1" class="nav pull-left nav-tabs">
                <?php
                foreach ($tabs as $tab):
                    $activeTabId = $tab['DashboardTab']['id'];
                    $tabName = $tab['DashboardTab']['name'];
                    $position = $tab['DashboardTab']['position'];
                    $isShared = $tab['DashboardTab']['shared'];
                    $sourceTabId = $tab['DashboardTab']['source_tab_id'];
                    ?>
                    <?php if ($tabId == $activeTabId): ?>
                    <li data-id="<?php echo $activeTabId; ?>" class="active dropdown-toggle dashboardTab"
                        data-name="<?php echo h($tabName); ?>" position="<?php echo $position; ?>">
                        <a class="pointer" data-toggle="dropdown" href="javascript:void(0)">
                            <span class="text"><?php echo h($tabName); ?></span>
                            <b class="caret"></b>
                        </a>
                        <ul class="dropdown-menu">
                            <li>
                                <a class="renameTab" href="javascript:void(0)">
                                    <i class="fa fa-pencil-square-o"></i>
                                    <?php echo __('Rename'); ?>
                                </a>
                                <?php if (!$isShared): ?>
                                    <a class="shareTab" href="javascript:void(0)">
                                        <i class="fa fa-share-alt"></i>
                                        <?php echo __('Start sharing'); ?>
                                    </a>
                                <?php else: ?>
                                    <a class="stopShareTab" href="javascript:void(0)">
                                        <i class="fa fa-share-alt"></i>
                                        <?php echo __('Stop sharing'); ?>
                                    </a>
                                <?php endif ?>
                                <a class="deleteTab" href="javascript:void(0)">
                                    <i class="fa fa-trash-o"></i>
                                    <?php echo __('Delete'); ?>
                                </a>
                                <?php if (intval($sourceTabId) !== 0): ?>
                                    <a class="refreshTab" href="javascript:void(0)">
                                        <i class="fa fa-refresh"></i>
                                        <?php echo __('Get update'); ?>
                                    </a>
                                <?php endif ?>
                            </li>
                        </ul>
                    </li>
                <?php else: ?>
                    <li data-id="<?php echo $activeTabId; ?>" data-name="<?php echo h($tabName); ?>"
                        class="dashboardTab" position="<?php echo $position; ?>">
                        <a href="/admin/Dashboard/index/<?php echo $activeTabId; ?>"><?php echo h($tabName); ?></a>
                    </li>
                <?php endif ?>
                <?php endforeach; ?>
            </ul>
            <div class="newTabContainer" role="menu">
                <div class="btn-group">
                    <button class="btn btn-xs btn-success" data-toggle="dropdown" aria-expanded="false">
                        <i class="fa fa-plus"></i>
                    </button>
                    <ul class="newTabsList dropdown-menu pull-right">
                        <li class="addNewTab"><a href="javascript:void(0);"><i class="fa fa-plus">&nbsp;</i>New Tab</a>
                        </li>
                        <hr>
                        <li class="addSharedTab">
                            <?php
                            echo $this->Form->create('chooseSharedTab', [
                                'class' => 'sharedTabsForm clear',
                                'id'    => '',
                            ]);

                            $allSharedTabs = $this->Html->chosenPlaceholder($sharedTabsForSelect);
                            $options = [
                                'options'   => $allSharedTabs,
                                'label'     => __('Select a shared Tab'),
                                'class'     => 'chosen selectSharedTab elementInput',
                                'wrapInput' => 'col col-xs-8 selectSharedTab',
                            ];
                            echo $this->Form->input('sharedTabSelect', $options);

                            $options_button = [
                                'label' => 'Save',
                                'class' => 'sharedTabSave btn btn-primary',
                            ];
                            echo $this->Form->end($options_button);

                            ?>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="rotateTabs">
                <div class="btn-group">
                    <button class="btn btn-xs btn-primary" data-toggle="dropdown" aria-expanded="false">
                        <i class="fa fa-refresh"></i>
                    </button>
                    <ul class="dropdown-menu pull-right">
                        <li>
                            <div class="form-group form-group-slider <?php echo $this->CustomValidationErrors->errorClass('notification_interval'); ?>">
                                <label class="col rotationSliderLabel"
                                       for="tabRotationInterval"><?php echo h('Choose tab rotation interval'); ?></label>
                                <div class="col rotationSlider">
                                    <input type="text" id="tabRotationInterval" maxlength="255" value=""
                                           class="form-control slider slider-success" name="data[rotationInterval]"
                                           data-slider-min="0" data-slider-max="1200"
                                           data-slider-value="<?php echo $tabRotationInterval['User']['dashboard_tab_rotation']; ?>"
                                           data-slider-selection="before"
                                           data-slider-step="<?php echo Configure::read('NagiosModule.SLIDER_STEPSIZE'); ?>"
                                           human="#HostNotificationinterval_human">
                                </div>
                                <div class="col rotationSliderInput">
                                    <input type="number" id="_tabRotationInterval"
                                           human="#HostNotificationinterval_human"
                                           value="<?php echo $tabRotationInterval['User']['dashboard_tab_rotation']; ?>"
                                           slider-for="HostNotificationinterval" class="form-control slider-input"
                                           name="data[Host][notification_interval]">
                                    <span class="note"
                                          id="HostNotificationinterval_human"><?php echo $this->Utils->secondsInWords($this->CustomValidationErrors->refill('notification_interval', $tabRotationInterval['User']['dashboard_tab_rotation'])); ?></span>
                                    <?php echo $this->CustomValidationErrors->errorHTML('notification_interval'); ?>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="newWidgetContainer" role="menu">
            <div class="btn-group">
                <button class="btn btn-xs btn-success" data-toggle="dropdown" aria-expanded="false">
                    <i class="fa fa-plus"></i> New Widget <i class="fa fa-caret-down"></i>
                </button>
                <ul class="dropdown-menu pull-right">
                    <?php
                    foreach ($widgetTypes as $key => $value):
                        echo "<li widget-type='".$key."' class='addWidget'><a href='javascript:void(0);'><i class='fa fa-".$value['icon']."'>&nbsp;</i>".$value['title']."</a></li>";
                    endforeach;
                    ?>
                    <hr>
                    <li widget-type="standard" class="addWidget"><a href="javascript:void(0);"><i class="fa fa-recycle">
                                &nbsp;</i>Restore default</a></li>
                </ul>
            </div>
        </div>
        <div class="clearfix"></div>
    </header>
    <div class="gridstackContainer">
        <div class="grid-stack" data-gs-width="12" data-gs-animate="yes">
            <?php foreach ($widgets as $widget): ?>
                <div widget-id="<?php echo $widget['Widget']['id']; ?>" class="grid-stack-item"
                     data-gs-x="<?php echo $widget['Widget']['row']; ?>"
                     data-gs-y="<?php echo $widget['Widget']['col']; ?>"
                     data-gs-width="<?php echo $widget['Widget']['width'] ?>"
                     data-gs-height="<?php echo $widget['Widget']['height'] ?>">
                    <div class="grid-stack-item-content">
                        <div class="jarviswidget jarviswidget-color-blue jarviswidget-sortable" role="widget">
                            <header role="heading" style="background-color:<?php echo $widget['Widget']['color'] ?>">
                                <div class="jarviswidget-ctrls" role="menu">
                                    <a href="javascript:void(0);" class="button-icon jarviswidget-edit-btn"
                                       rel="tooltip" title="" data-placement="bottom"
                                       data-original-title="<?php echo __('Edit title'); ?>"><i class="fa fa-cog "></i></a>
                                    <div class="widget-toolbar" role="menu">
                                        <a data-toggle="dropdown"
                                           class="data-widget-colorbutton button-icon dropdown-toggle color-box selector"
                                           href="javascript:void(0);"></a>
                                        <ul class="dropdown-menu arrow-box-up-right color-select pull-right">
                                            <?php foreach ($barColors as $color => $name): ?>
                                                <li>
													<span class="bg-color-<?php echo $color; ?> color-bar-picker"
                                                          data-color="<?php echo $name['color']; ?>"
                                                          data-widget-setstyle="jarviswidget-color-<?php echo $color; ?>"
                                                          rel="tooltip" data-placement="top"
                                                          data-original-title="<?php echo $name['title']; ?>">
													</span>
                                                </li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </div>
                                    <a href="javascript:void(0);" class="button-icon jarviswidget-delete-btn"
                                       rel="tooltip" title="" data-placement="bottom"
                                       data-original-title="<?php echo __('Delete'); ?>"><i class="fa fa-times"></i></a>
                                </div>
                                <h2><i class='fa fa-<?php echo $widgetTypes[$widget['Widget']['type_id']]['icon'] ?>'>
                                        &nbsp;</i><?php echo h($widget['Widget']['title']); ?></h2>
                            </header>
                            <div role="content">
                                <?php
                                echo $this->Widget->get($widget['Widget']['type_id']);
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
