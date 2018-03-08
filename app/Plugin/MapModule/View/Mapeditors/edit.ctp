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
            <i class="fa fa-map-marker fa-fw "></i>
            <?php echo __('Map'); ?>
            <span>>
                <?php echo __('Map editor'); ?>
			</span>
            <div class="third_level"> <?php echo ucfirst($this->params['action']); ?></div>
        </h1>
    </div>
</div>
<div id="error_msg"></div>

<div class="jarviswidget" id="wid-id-0">
    <header>
        <span class="widget-icon"> <i class="fa fa-map-marker"></i> </span>
        <h2><?php echo __('Edit Map in editor'); ?>
            <span class="col txt-color-redLight font-xs padding-left-30">
				<i class="fa fa-exclamation-circle"></i> <?php echo __('empty map objects will be removed automatically'); ?>
			</span>
        </h2>
        <div class="widget-toolbar" role="menu">
            <?php echo $this->Utils->backButton(null, '/map_module/maps'); ?>

        </div>
    </header>
    <div id="map-editor">
        <!-- Background image loading -->
        <?php
        $css = '';
        $removeBGButtonVisibility = 'display:none;';
        if ($map['Map']['background'] != null && $map['Map']['background'] != ''):
            $filePath = $backgroundThumbs['path'] . '/' . $map['Map']['background'];
            if (file_exists($filePath)):
                $size = getimagesize($backgroundThumbs['path'] . DS . $map['Map']['background']);
                $css = 'width: ' . $size[0] . 'px; height: ' . $size[1] . 'px; background-image: url(' . $backgroundThumbs['webPath'] . '/' . $map['Map']['background'] . '); background-repeat: no-repeat';
                $removeBGButtonVisibility = 'display:inline;';
            else:
                echo '<div class="alert alert-danger fade in">
						<button class="close" data-dismiss="alert">×</button>
						<i class="fa-fw fa fa-times"></i>
						<strong>' . __('Error!') . '</strong> ' . __('Loading Background image failed!') . '
					</div>';
            endif;
        endif;
        ?>

        <div id="lineInfoBox" class="alert alert-info fade in lineInfoBoxStyle">
            <button class="close" data-dismiss="alert">
                ×
            </button>
            <i class="fa-fw fa fa-info"></i>
            <input type="hidden" id="linePoint1"
                   value="<?php echo __('Please click to add the first point of your line!'); ?>">
            <input type="hidden" id="linePoint2"
                   value="<?php echo __('Please click to add the second point of your line!'); ?>">
            <strong>Info!</strong> <span id="lineInfoText"></span>
        </div>

        <div id="editTextErrorBox" class="alert alert-warning fade in editTextErrorBoxStyle">
            <i class="fa-fw fa fa-info"></i>
            <strong>Warning!</strong> <span
                    id="editTextErrorText"><?php echo __('Please enable Grid to edit the Text!'); ?></span>
        </div>

        <div id="createTextErrorBox" class="alert alert-warning fade in editTextErrorBoxStyle">
            <i class="fa-fw fa fa-info"></i>
            <strong>Warning!</strong> <span
                    id="createTextErrorText"><?php echo __('Please enable Grid to add a new Text!'); ?></span>
        </div>

        <div id="removeBG" class="btn btn-default btn-xs removeBackgroundButton"
             style="<?php echo $removeBGButtonVisibility; ?>">
            <i class="glyphicon glyphicon-remove"></i>
        </div>

        <div class="widget-body">
            <!-- Button Upload BG -->
            <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#UploadModal"
                    id="background-upload-btn">
                <?php echo __('Upload Background'); ?>
            </button>

            <!-- Button Upload icons set -->
            <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#UploadIconsModal"
                    id="icons-upload-btn">
                <?php echo __('Upload Items set'); ?>
            </button>

            <!-- Button Options -->
            <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#OptionsModal"
                    id="show-options-btn">
                <?php echo __('Options'); ?>
            </button>

            <!-- Upload Dialog -->
            <div class="modal fade" id="UploadModal" tabindex="-1" role="dialog" aria-labelledby="UploadLabel"
                 aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal"><span
                                        aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                            <h4 class="modal-title"
                                id="UploadLabel"><?php echo __('Upload your Map Background'); ?></h4>
                        </div>
                        <div class="modal-body">
                            <div class="alert alert-danger" id="backgrounds-upload-error" style="display: none"></div>
                            <div class="alert alert-success" id="backgrounds-upload-success"
                                 style="display: none"></div>
                            <div class="row">
                                <div class="background-dropzone dropzone"
                                     action="/map_module/backgroundUploads/upload/">
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Upload Dialog -->
            <div class="modal fade" id="UploadIconsModal" tabindex="-1" role="dialog" aria-labelledby="UploadLabel"
                 aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal"><span
                                        aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                            <h4 class="modal-title" id="UploadLabel"><?php echo __('Upload your Icons Set'); ?></h4>
                        </div>
                        <div class="modal-body">
                            <div class="alert alert-danger" id="icons-upload-error" style="display: none"></div>
                            <div class="alert alert-success" id="icons-upload-success" style="display: none"></div>
                            <div class="row">
                                <div class="icons-dropzone dropzone"
                                     action="/map_module/backgroundUploads/uploadIconsSet/">
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Options Dialog -->
            <div class="modal fade" id="OptionsModal" tabindex="-1" role="dialog" aria-labelledby="OptionsLabel"
                 aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal"><span
                                        aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                            <h4 class="modal-title" id="OptionsLabel"><?php echo __('Options'); ?></h4>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <!-- Grid Options -->
                                <div class="row">
                                    <div class="padding-left-20">
                                        <div class="form-group no-padding">
                                            <?php echo $this->Form->fancyCheckbox('enable_Grid_Slider', [
                                                'caption'          => __('Enable Grid'),
                                                'captionGridClass' => 'col col-xs-3',
                                                'icon'             => '<i class="glyphicon glyphicon-th"></i> ',
                                                'class'            => 'onoffswitch-checkbox',
                                                'wrapGridClass'    => 'col col-xs-2',
                                            ]); ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="collapse" id="MapGridCollapseOptions">
                                    <div class="well">
                                        <!-- Grid Slider -->
                                        <div class="row">
                                            <div class="form-group form-group-slider">
                                                <label class="col col-md-4 control-label " for="MapGridSizeX"><i
                                                            class="fa fa-plus"></i> <?php echo __('Map Grid size'); ?>
                                                </label>
                                                <div class="col col-md-5 hidden-mobile">
                                                    <input
                                                            type="text"
                                                            id="MapGridSizeX"
                                                            maxlength="255"
                                                            value=""
                                                            class="form-control slider slider-success"
                                                            name="MapGridSizeX"
                                                            data-slider-min="20"
                                                            data-slider-max="200"
                                                            data-slider-value="50"
                                                            data-slider-selection="before"
                                                            data-slider-step="5"
                                                            human="#MapGridSizeX_human">
                                                </div>
                                                <div class="col col-xs-8 col-md-3">
                                                    <input type="number" id="_MapGridSizeX" human="#MapGridSizeX_human"
                                                           min="20" value="50" slider-for="MapGridSizeX"
                                                           class="form-control slider-input" name="_MapGridSizeX_human">
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Grid Colorpicker -->
                                        <div class="row">
                                            <label for="MapGridColor" class="col col-md-4"><i
                                                        class="fa fa-paint-brush"></i> <?php echo __('Map Grid Color'); ?>
                                            </label>
                                            <div id="MapGridColorContainer" class="input-append color col col-md-3"
                                                 data-color-format="hex">
                                                <input type="text" id="MapGridColor" class="form-control"
                                                       value="#DDDDDD">
                                                <span class="add-on"><i></i></span>
                                            </div>
                                        </div>
                                        <!-- Magnetic grid -->
                                        <div class="row">
                                            <div class="form-group no-padding">
                                                <?php echo $this->Form->fancyCheckbox('enable_Magnetic_Grid', [
                                                    'caption'          => __('Magnetic Grid'),
                                                    'captionGridClass' => 'col col-md-4',
                                                    'icon'             => '<i class="fa fa-magnet"></i> ',
                                                    'class'            => 'onoffswitch-checkbox',
                                                    'wrapGridClass'    => 'col col-xs-2',
                                                ]); ?>
                                            </div>
                                        </div>
                                        <!-- Text scale with grid -->
                                        <div class="row">
                                            <div class="form-group no-padding">
                                                <?php echo $this->Form->fancyCheckbox('enable_Text_Scale_With_Grid', [
                                                    'caption'          => __('Scale Text with Grid'),
                                                    'captionGridClass' => 'col col-md-4',
                                                    'icon'             => '<i class="fa fa-font"></i> ',
                                                    'class'            => 'onoffswitch-checkbox',
                                                    'wrapGridClass'    => 'col col-xs-2',
                                                ]); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <!-- menu panel Autohide -->
                                <div class="row">
                                    <div class="padding-left-20">
                                        <div class="form-group no-padding">
                                            <?php echo $this->Form->fancyCheckbox('autohide_menu', [
                                                'caption'          => __('Autohide Menu'),
                                                'captionGridClass' => 'col col-xs-3',
                                                'icon'             => '<i class="fa fa-bars"></i> ',
                                                'class'            => 'onoffswitch-checkbox',
                                                'wrapGridClass'    => 'col col-xs-2',
                                            ]); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">
                                <?php echo __('Close'); ?>
                            </button>
                        </div>
                    </div>
                </div>
            </div>


            <!-- save map manually button -->
            <?php
            echo $this->Form->create('Map', [
                'class' => 'form-horizontal clear',
                'style' => 'display: inline;',
            ]);
            echo $this->Form->input('id', ['type' => 'hidden', 'value' => $map['Map']['id']]);
            echo $this->Form->input('background', ['type' => 'hidden', 'value' => $map['Map']['background']]);
            echo $this->Form->input('name', ['type' => 'hidden', 'value' => $map['Map']['name']]);
            echo $this->Form->input('title', ['type' => 'hidden', 'value' => $map['Map']['title']]);
            ?>
            <button type="submit" class="btn btn-primary btn-sm" id="save-map-btn">
                <?php echo __('Save'); ?>
            </button>
            <br>
            <br>
            <!-- Map draw container -->
            <div id="MapContainer" class="well" style="overflow: auto;">

                <!-- The background image will be inserted here -->
                <div id="jsPlumb_playground" class="resetMargin MapPlaygroundStyle" style="<?php echo $css; ?>">
                    <?php App::uses('UUID', 'Lib'); ?>
                    <?php foreach ($map['Mapitem'] as $key => $item):
                        $uuid = UUID::v4();
                        ?>
                        <!-- Mapitems -->
                        <div id="<?php echo $uuid; ?>" class="itemElement iconContainer dragElement"
                             style="position:absolute; top: <?php echo $item['y']; ?>px; left: <?php echo $item['x']; ?>px;z-index:<?php echo $item['z_index']; ?>">
                            <img src="/map_module/img/items/<?php echo $item['iconset']; ?>/ok.png"
                                 onerror="this.src='/map_module/img/items/missing.png';">
                            <input type="hidden" data-key="id" name="data[Mapitem][<?php echo $uuid; ?>][id]"
                                   value="<?php echo $item['id']; ?>"/>
                            <input type="hidden" data-key="x" name="data[Mapitem][<?php echo $uuid; ?>][x]"
                                   value="<?php echo $item['x']; ?>"/>
                            <input type="hidden" data-key="y" name="data[Mapitem][<?php echo $uuid; ?>][y]"
                                   value="<?php echo $item['y']; ?>"/>
                            <input type="hidden" data-key="z_index" name="data[Mapitem][<?php echo $uuid; ?>][z_index]"
                                   value="<?php echo $item['z_index']; ?>"/>
                            <input type="hidden" data-key="limit" name="data[Mapitem][<?php echo $uuid; ?>][limit]"
                                   value="<?php echo $item['limit']; ?>"/>
                            <input type="hidden" data-key="iconset" name="data[Mapitem][<?php echo $uuid; ?>][iconset]"
                                   value="<?php echo $item['iconset']; ?>"/>
                            <input type="hidden" data-key="type" name="data[Mapitem][<?php echo $uuid; ?>][type]"
                                   value="<?php echo $item['type']; ?>"/>
                            <input type="hidden" data-key="object_id"
                                   name="data[Mapitem][<?php echo $uuid; ?>][object_id]"
                                   value="<?php echo $item['object_id']; ?>"/>
                        </div>
                    <?php endforeach; ?>
                    <?php foreach ($map['Mapline'] as $key => $line): ?>
                        <?php $uuid = UUID::v4(); ?>
                        <!-- Maplines -->
                        <div id="<?php echo $uuid; ?>" data-lineId="<?php echo $line['id'] ?>"
                             class="itemElement lineContainer">
                            <input type="hidden" class="itemElementSVG" data-key="id"
                                   name="data[Mapline][<?php echo $uuid; ?>][id]" value="<?php echo $line['id']; ?>"/>
                            <input type="hidden" class="itemElementSVG" data-key="startX"
                                   name="data[Mapline][<?php echo $uuid; ?>][startX]"
                                   value="<?php echo $line['startX']; ?>"/>
                            <input type="hidden" class="itemElementSVG" data-key="endX"
                                   name="data[Mapline][<?php echo $uuid; ?>][endX]"
                                   value="<?php echo $line['endX']; ?>"/>
                            <input type="hidden" class="itemElementSVG" data-key="startY"
                                   name="data[Mapline][<?php echo $uuid; ?>][startY]"
                                   value="<?php echo $line['startY']; ?>"/>
                            <input type="hidden" data-key="z_index" name="data[Mapline][<?php echo $uuid; ?>][z_index]"
                                   value="<?php echo $line['z_index']; ?>"/>
                            <input type="hidden" class="itemElementSVG" data-key="endY"
                                   name="data[Mapline][<?php echo $uuid; ?>][endY]"
                                   value="<?php echo $line['endY']; ?>"/>
                            <input type="hidden" data-key="limit" name="data[Mapline][<?php echo $uuid; ?>][limit]"
                                   value="<?php echo $line['limit']; ?>"/>
                            <input type="hidden" data-key="iconset" name="data[Mapline][<?php echo $uuid; ?>][iconset]"
                                   value="<?php echo $line['iconset']; ?>"/>
                            <input type="hidden" data-key="type" name="data[Mapline][<?php echo $uuid; ?>][type]"
                                   value="<?php echo $line['type']; ?>"/>
                            <input type="hidden" data-key="object_id"
                                   name="data[Mapline][<?php echo $uuid; ?>][object_id]"
                                   value="<?php echo $line['object_id']; ?>"/>
                        </div>
                    <?php endforeach; ?>
                    <?php foreach ($map['Mapgadget'] as $key => $gadget): ?>
                        <?php $uuid = UUID::v4(); ?>
                        <!-- Mapgadgets -->
                        <div id="<?php echo $uuid; ?>" data-gadgetId="<?php echo $gadget['id'] ?>"
                             class="itemElement gadgetContainer">
                            <input type="hidden" data-key="id" name="data[Mapgadget][<?php echo $uuid; ?>][id]"
                                   value="<?php echo $gadget['id']; ?>"/>
                            <input type="hidden" data-key="x" name="data[Mapgadget][<?php echo $uuid; ?>][x]"
                                   value="<?php echo $gadget['x']; ?>"/>
                            <input type="hidden" data-key="y" name="data[Mapgadget][<?php echo $uuid; ?>][y]"
                                   value="<?php echo $gadget['y']; ?>"/>
                            <input type="hidden" data-key="size_x" name="data[Mapgadget][<?php echo $uuid; ?>][size_x]"
                                   value="<?php echo $gadget['size_x']; ?>"/>
                            <input type="hidden" data-key="size_y" name="data[Mapgadget][<?php echo $uuid; ?>][size_y]"
                                   value="<?php echo $gadget['size_y']; ?>"/>
                            <input type="hidden" data-key="limit" name="data[Mapgadget][<?php echo $uuid; ?>][limit]"
                                   value="<?php echo $gadget['limit']; ?>"/>
                            <input type="hidden" data-key="z_index"
                                   name="data[Mapgadget][<?php echo $uuid; ?>][z_index]"
                                   value="<?php echo $gadget['z_index']; ?>"/>
                            <input type="hidden" data-key="gadget" name="data[Mapgadget][<?php echo $uuid; ?>][gadget]"
                                   value="<?php echo $gadget['gadget']; ?>"/>
                            <input type="hidden" data-key="type" name="data[Mapgadget][<?php echo $uuid; ?>][type]"
                                   value="<?php echo $gadget['type']; ?>"/>
                            <input type="hidden" data-key="object_id"
                                   name="data[Mapgadget][<?php echo $uuid; ?>][object_id]"
                                   value="<?php echo $gadget['object_id']; ?>"/>
                            <input type="hidden" data-key="transparent_background"
                                   name="data[Mapgadget][<?php echo $uuid; ?>][transparent_background]"
                                   value="<?php echo $gadget['transparent_background']; ?>"/>
                            <input type="hidden" data-key="show_label"
                                   name="data[Mapgadget][<?php echo $uuid; ?>][show_label]"
                                   value="<?php echo $gadget['show_label']; ?>"/>
                            <input type="hidden" data-key="font_size"
                                   name="data[Mapgadget][<?php echo $uuid; ?>][font_size]"
                                   value="<?php echo $gadget['font_size']; ?>"/>
                        </div>
                    <?php endforeach; ?>
                    <?php foreach ($map['Mapicon'] as $key => $icon): ?>
                        <?php $uuid = UUID::v4(); ?>
                        <!-- Mapicons -->
                        <div id="<?php echo $uuid; ?>" class="itemElement statelessIconContainer dragElement"
                             style="position:absolute; top: <?php echo $icon['y']; ?>px; left: <?php echo $icon['x']; ?>px; z-index:<?php echo $icon['z_index']; ?>">
                            <img src="/map_module/img/icons/<?php echo $icon['icon']; ?>"
                                 onerror="this.src='/map_module/img/items/missing.png';">
                            <input type="hidden" data-key="id" name="data[Mapicon][<?php echo $uuid; ?>][id]"
                                   value="<?php echo $icon['id']; ?>"/>
                            <input type="hidden" data-key="x" name="data[Mapicon][<?php echo $uuid; ?>][x]"
                                   value="<?php echo $icon['x']; ?>"/>
                            <input type="hidden" data-key="y" name="data[Mapicon][<?php echo $uuid; ?>][y]"
                                   value="<?php echo $icon['y']; ?>"/>
                            <input type="hidden" data-key="z_index" name="data[Mapicon][<?php echo $uuid; ?>][z_index]"
                                   value="<?php echo $icon['z_index']; ?>"/>
                            <input type="hidden" data-key="icon" name="data[Mapicon][<?php echo $uuid; ?>][icon]"
                                   value="<?php echo $icon['icon']; ?>"/>
                        </div>
                    <?php endforeach; ?>
                    <?php foreach ($map['Maptext'] as $key => $text): ?>
                        <?php $uuid = UUID::v4(); ?>
                        <!-- Maptext -->
                        <div id="<?php echo $uuid; ?>" class="textContainer dragElement"
                             style="position:absolute;top:<?php echo $text['y']; ?>px; left: <?php echo $text['x']; ?>px;z-index:<?php echo $text['z_index']; ?>">
                            <div id="spanText_<?php echo $uuid; ?>" class="textElement"
                                 style="position:absolute;font-size:11px;"><?php echo $text['text']; ?></div>
                            <input type="hidden" data-key="id" name="data[Maptext][<?php echo $uuid; ?>][id]"
                                   value="<?php echo $text['id']; ?>"/>
                            <input type="hidden" data-key="x" name="data[Maptext][<?php echo $uuid; ?>][x]"
                                   value="<?php echo $text['x']; ?>"/>
                            <input type="hidden" data-key="y" name="data[Maptext][<?php echo $uuid; ?>][y]"
                                   value="<?php echo $text['y']; ?>"/>
                            <input type="hidden" data-key="z_index" name="data[Maptext][<?php echo $uuid; ?>][z_index]"
                                   value="<?php echo $text['z_index']; ?>"/>
                            <input type="hidden" data-key="text" name="data[Maptext][<?php echo $uuid; ?>][text]"
                                   value="<?php echo $text['text']; ?>"/>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php echo $this->Form->end(); ?>


            <!-- menu panel for background thumbs and items -->
            <div id="mapMenuPanel" class="panel panel-default mapMenuPanelContainer">
                <div id="mapMenuMinimizeBtn" style="display:none" class="pointer">
                    <i class="fa fa-bars minimizedMenuPanelIcon" style="font-size:28px"></i>
                </div>
                <div id="mapMenuPanelBody" class="panel-body menuPanelBody">
                    <!-- accordion start -->
                    <div class="panel-group" id="accordion">
                        <!-- background panel start -->
                        <div class="panel panel-default">
                            <div class="panel-heading" data-toggle="collapse" data-target="#backgroundTab"
                                 data-parent="#accordion">
                                <h4 class="panel-title">
                                    <a>
                                        <?php echo __('Backgrounds'); ?>
                                    </a>
                                </h4>
                            </div>
                            <div id="backgroundTab" class="panel-collapse collapse in">
                                <div id="background-panel" class="panel-body thumbnailPanel menuPanel">
                                    <?php
                                    $i = 0;
                                    foreach ($backgroundThumbs['files'] as $file):
                                        $path = $backgroundThumbs['thumbPath'] . '/thumb_' . $file['savedName'];
                                        ?>
                                        <div class="col-xs-6 col-sm-6 col-md-6 backgroundContainer thumbnailSize"
                                             title="<?php echo h($file['displayName']); ?>">
                                            <div class="thumbnail backgroundThumbnailStyle background-thumbnail">
                                                <img class="background" src="<?php echo $path; ?>"
                                                     original="<?php echo $backgroundThumbs['webPath'] . '/' . $file['savedName']; ?>"
                                                     filename-id="<?php echo $file['id']; ?>"
                                                     filename="<?php echo h($file['savedName']); ?>">
                                            </div>
                                        </div>
                                        <?php
                                        $i++;
                                    endforeach;
                                    ?>
                                </div>
                            </div>
                        </div>
                        <!-- background panel end -->
                        <!-- item panel start -->
                        <div class="panel panel-default">
                            <div class="panel-heading" data-toggle="collapse" data-target="#itemTab"
                                 data-parent="#accordion">
                                <h4 class="panel-title">
                                    <a>
                                        <?php echo __('Items'); ?>
                                    </a>
                                </h4>
                            </div>
                            <div id="itemTab" class="panel-collapse collapse">
                                <div id="item-panel" class="panel-body thumbnailPanel menuPanel">
                                    <!-- items -->
                                    <?php
                                    $i = 0;
                                    foreach ($iconSets['items']['iconsets'] as $iconset):
                                        $path = $iconSets['items']['webPath'] . '/' . $iconset['savedName'] . '/' . 'ok.png';
                                        ?>
                                        <div class="col-xs-6 col-sm-6 col-md-6 backgroundContainer"
                                             title="<?php echo h($iconset['displayName']); ?>">
                                            <div class="drag-element thumbnail thumbnailFix iconset-thumbnail">
                                                <?php if ($iconset['dimension'] < 80): ?>
                                                    <span class="valignHelper"></span>
                                                <?php endif; ?>
                                                <img class="iconset" src="<?php echo $path; ?>"
                                                     iconset-id="<?php echo $iconset['id']; ?>"
                                                     iconset="<?php echo $iconset['savedName']; ?>">
                                            </div>
                                        </div>
                                    <?php
                                    endforeach;
                                    ?>
                                </div>
                            </div>
                        </div>
                        <!-- item panel end -->
                        <!-- gadget panel start -->
                        <div class="panel panel-default">
                            <div class="panel-heading" data-toggle="collapse" data-target="#gadgetTab"
                                 data-parent="#accordion">
                                <h4 class="panel-title">
                                    <a>
                                        <?php echo __('Gadgets'); ?>
                                    </a>
                                </h4>
                            </div>
                            <div id="gadgetTab" class="panel-collapse collapse">
                                <div id="gadget-panel" class="panel-body thumbnailPanel menuPanel">
                                    <!-- gadgets -->
                                </div>
                            </div>
                        </div>
                        <!-- gadget panel end -->
                        <!-- icon panel start -->
                        <div class="panel panel-default">
                            <div class="panel-heading" data-toggle="collapse" data-target="#iconTab"
                                 data-parent="#accordion">
                                <h4 class="panel-title">
                                    <a>
                                        <?php echo __('Icons'); ?>
                                    </a>
                                </h4>
                            </div>
                            <div id="iconTab" class="panel-collapse collapse">
                                <div id="icon-panel" class="panel-body thumbnailPanel menuPanel">
                                    <?php
                                    if (!empty($icons['icons']['icons'])):
                                        foreach ($icons['icons']['icons'] as $icon):
                                            $path = $icons['icons']['webPath']; ?>
                                            <div class="drag-element col-xs-6 col-sm-6 col-md-6 statelessIcon">
                                                <div class="thumbnail">
                                                    <img class="icon" src="<?php echo $path . '/' . $icon; ?>"
                                                         icon="<?php echo h($icon); ?>">
                                                </div>
                                            </div>
                                        <?php endforeach;
                                    endif; ?>
                                </div>
                            </div>
                        </div>
                        <!-- icon panel end -->
                        <!-- misc panel start -->
                        <div class="panel panel-default">
                            <div class="panel-heading" data-toggle="collapse" data-target="#miscTab"
                                 data-parent="#accordion">
                                <h4 class="panel-title">
                                    <a>
                                        <?php echo __('Misc.'); ?>
                                    </a>
                                </h4>
                            </div>
                            <div id="miscTab" class="panel-collapse collapse">
                                <div id="misc-panel" class="panel-body thumbnailPanel menuPanel">
                                    <button id="createLine"
                                            class="btn btn-primary btn-default btn-sm btn-block"><?php echo __('Create Line') ?></button>
                                    <br>
                                    <button id="createText"
                                            class="btn btn-primary btn-default btn-sm btn-block"><?php echo __('Create Text') ?></button>
                                </div>
                            </div>
                        </div>
                        <!-- misc panel end -->
                    </div>
                    <!-- accordion end -->
                </div>
            </div>
            <!-- menu panel end -->

            <!-- Element wizard Modal Dialog -->
            <div class="modal fade" id="ElementWizardModal" tabindex="-1" role="dialog"
                 aria-labelledby="ElementWizardLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal"><span
                                        aria-hidden="true">&times;</span><span
                                        class="sr-only"><?php echo __('Close'); ?></span></button>
                            <h4 class="modal-title" id="ElementWizardLabel"><?php echo __('Edit Element'); ?></h4>
                            <span id="tempElementUUID"></span>
                        </div>
                        <div class="modal-body">
                            <div id="ElementWizardModalContent">
                                <div class="row">
                                    <div class="col-md-6">
                                        <select name="" id="ElementWizardChoseType" class="chosen"
                                                data-placeholder="<?php echo __('Please select...'); ?>">
                                            <option value=""></option>
                                            <option value="host"><?php echo __('Host'); ?></option>
                                            <option value="service"><?php echo __('Service'); ?></option>
                                            <option value="servicegroup"><?php echo __('Servicegroup'); ?></option>
                                            <option value="hostgroup"><?php echo __('Hostgroup'); ?></option>
                                            <option value="map"><?php echo __('Map'); ?></option>
                                        </select>
                                    </div>
                                    <!-- Host form -->
                                    <div class="col-xs-12" id="addElement_host" style="display:none;">
                                        <div class="padding-top-20"></div>
                                        <?php
                                        echo $this->Form->create('addHost', [
                                            'class' => 'form-horizontal clear',
                                        ]);
                                        echo $this->Form->input('object_id', [
                                                //'options'          => $this->Html->chosenPlaceholder($hosts),
                                                'data-placeholder' => __('Please select...'),
                                                'multiple'         => false,
                                                'class'            => 'elementInput',
                                                'style'            => 'width: 100%',
                                                'label'            => __('Host'),
                                                'wrapInput'        => 'col col-xs-8',
                                                'element-property' => 'chosen',
                                                'content'          => 'object_id',
                                            ]
                                        );
                                        echo $this->Form->input('x', ['value' => 0, 'label' => __('Position X'), 'wrapInput' => 'col col-xs-8', 'class' => 'form-control elementInput', 'element-property' => 'text', 'content' => 'x']);
                                        echo $this->Form->input('y', ['value' => 0, 'label' => __('Position Y'), 'wrapInput' => 'col col-xs-8', 'class' => 'form-control elementInput', 'element-property' => 'text', 'content' => 'y']);

                                        echo $this->Form->input('z_index', [
                                                'type'             => 'number',
                                                'value'            => 0,
                                                'min'              => 0,
                                                'step'             => 1,
                                                'label'            => __('Z Index'),
                                                'wrapInput'        => 'col col-xs-8',
                                                'class'            => 'form-control elementInput',
                                                'element-property' => 'number',
                                                'content'          => 'z_index'
                                            ]
                                        );

                                        //	echo $this->Form->input('limit', ['value' => 0, 'label' => __('Hover child limit'), 'wrapInput' => 'col col-xs-8', 'class' => 'elementInput' ,'element-property' => 'text', 'content' => 'limit']);
                                        $_iconset = [];
                                        foreach ($iconSets['items']['iconsets'] as $name) {
                                            $_iconset[$name['savedName']] = $name['displayName'];
                                        }
                                        echo $this->Form->input('iconset', [
                                                'options'          => $this->Html->chosenPlaceholder($_iconset),
                                                'data-placeholder' => __('Please select...'),
                                                'multiple'         => false,
                                                'class'            => 'chosen elementInput',
                                                'style'            => 'width: 100%',
                                                'label'            => __('Iconset'),
                                                'wrapInput'        => 'col col-xs-8',
                                                'element-property' => 'chosen',
                                                'content'          => 'iconset',
                                            ]
                                        );


                                        echo $this->Form->end();
                                        ?>
                                    </div>
                                    <!-- Service form -->
                                    <div class="col-xs-12" id="addElement_service" style="display:none;">
                                        <div class="padding-top-20"></div>
                                        <?php
                                        echo $this->Form->create('addService', [
                                            'class' => 'form-horizontal clear',
                                        ]);
                                        echo $this->Form->input('HostObject_id', [
                                                //'options'          => $this->Html->chosenPlaceholder($hosts),
                                                'data-placeholder' => __('Please select...'),
                                                'multiple'         => false,
                                                'class'            => 'elementInput',
                                                'style'            => 'width: 100%',
                                                'label'            => __('Host'),
                                                'wrapInput'        => 'col col-xs-8',
                                                'element-property' => 'chosen',
                                                'content'          => 'object_id',
                                            ]
                                        );
                                        echo $this->Form->input('object_id', [
                                                'data-placeholder' => __('Please select...'),
                                                'multiple'         => false,
                                                'class'            => 'chosen elementInput',
                                                'style'            => 'width: 100%',
                                                'label'            => __('Service'),
                                                'wrapInput'        => 'col col-xs-8',
                                                'element-property' => 'chosen',
                                                'content'          => 'object_id',
                                            ]
                                        );
                                        echo $this->Form->input('x', ['value' => 0, 'label' => __('Position X'), 'wrapInput' => 'col col-xs-8', 'class' => 'form-control elementInput', 'element-property' => 'text', 'content' => 'x']);
                                        echo $this->Form->input('y', ['value' => 0, 'label' => __('Position Y'), 'wrapInput' => 'col col-xs-8', 'class' => 'form-control elementInput', 'element-property' => 'text', 'content' => 'y']);

                                        echo $this->Form->input('z_index', [
                                                'type'             => 'number',
                                                'value'            => 0,
                                                'min'              => 0,
                                                'step'             => 1,
                                                'label'            => __('Z Index'),
                                                'wrapInput'        => 'col col-xs-8',
                                                'class'            => 'form-control elementInput',
                                                'element-property' => 'number',
                                                'content'          => 'z_index'
                                            ]
                                        );

                                        //echo $this->Form->input('limit', ['value' => 0, 'label' => __('Hover child limit'), 'wrapInput' => 'col col-xs-8', 'class' => 'elementInput' ,'element-property' => 'text', 'content' => 'limit']);
                                        echo $this->Form->input('iconset', [
                                                'options'          => $this->Html->chosenPlaceholder($_iconset),
                                                'data-placeholder' => __('Please select...'),
                                                'multiple'         => false,
                                                'class'            => 'chosen elementInput',
                                                'style'            => 'width: 100%',
                                                'label'            => __('Iconset'),
                                                'wrapInput'        => 'col col-xs-8',
                                                'element-property' => 'chosen',
                                                'content'          => 'iconset',
                                            ]
                                        );
                                        echo $this->Form->end();
                                        ?>
                                    </div>
                                    <!-- Servicegroup form -->
                                    <div class="col-xs-12" id="addElement_servicegroup" style="display:none;">
                                        <div class="padding-top-20"></div>
                                        <?php
                                        echo $this->Form->create('addServicegroup', [
                                            'class' => 'form-horizontal clear',
                                        ]);
                                        echo $this->Form->input('object_id', [
                                                'options'          => $this->Html->chosenPlaceholder($servicegroup),
                                                'data-placeholder' => __('Please select...'),
                                                'multiple'         => false,
                                                'class'            => 'chosen elementInput',
                                                'style'            => 'width: 100%',
                                                'label'            => __('Servicegroup'),
                                                'wrapInput'        => 'col col-xs-8',
                                                'element-property' => 'chosen',
                                                'content'          => 'object_id',
                                            ]
                                        );
                                        echo $this->Form->input('x', ['value' => 0, 'label' => __('Position X'), 'wrapInput' => 'col col-xs-8', 'class' => 'form-control elementInput', 'element-property' => 'text', 'content' => 'x']);
                                        echo $this->Form->input('y', ['value' => 0, 'label' => __('Position Y'), 'wrapInput' => 'col col-xs-8', 'class' => 'form-control elementInput', 'element-property' => 'text', 'content' => 'y']);

                                        echo $this->Form->input('z_index', [
                                                'type'             => 'number',
                                                'value'            => 0,
                                                'min'              => 0,
                                                'step'             => 1,
                                                'label'            => __('Z Index'),
                                                'wrapInput'        => 'col col-xs-8',
                                                'class'            => 'form-control elementInput',
                                                'element-property' => 'number',
                                                'content'          => 'z_index'
                                            ]
                                        );

                                        //echo $this->Form->input('limit', ['value' => 0, 'label' => __('Hover child limit'), 'wrapInput' => 'col col-xs-8', 'class' => 'elementInput' ,'element-property' => 'text', 'content' => 'limit']);
                                        echo $this->Form->input('iconset', [
                                                'options'          => $this->Html->chosenPlaceholder($_iconset),
                                                'data-placeholder' => __('Please select...'),
                                                'multiple'         => false,
                                                'class'            => 'chosen elementInput',
                                                'style'            => 'width: 100%',
                                                'label'            => __('Iconset'),
                                                'wrapInput'        => 'col col-xs-8',
                                                'element-property' => 'chosen',
                                                'content'          => 'iconset',
                                            ]
                                        );
                                        echo $this->Form->end();
                                        ?>
                                    </div>
                                    <!-- Hostgroup form -->
                                    <div class="col-xs-12" id="addElement_hostgroup" style="display:none;">
                                        <div class="padding-top-20"></div>
                                        <?php
                                        echo $this->Form->create('addHostgroup', [
                                            'class' => 'form-horizontal clear',
                                        ]);
                                        echo $this->Form->input('object_id', [
                                                'options'          => $this->Html->chosenPlaceholder($hostgroup),
                                                'data-placeholder' => __('Please select...'),
                                                'multiple'         => false,
                                                'class'            => 'chosen elementInput',
                                                'style'            => 'width: 100%',
                                                'label'            => __('Hostgroup'),
                                                'wrapInput'        => 'col col-xs-8',
                                                'element-property' => 'chosen',
                                                'content'          => 'object_id',
                                            ]
                                        );
                                        echo $this->Form->input('x', ['value' => 0, 'label' => __('Position X'), 'wrapInput' => 'col col-xs-8', 'class' => 'form-control elementInput', 'element-property' => 'text', 'content' => 'x']);
                                        echo $this->Form->input('y', ['value' => 0, 'label' => __('Position Y'), 'wrapInput' => 'col col-xs-8', 'class' => 'form-control elementInput', 'element-property' => 'text', 'content' => 'y']);

                                        echo $this->Form->input('z_index', [
                                                'type'             => 'number',
                                                'value'            => 0,
                                                'min'              => 0,
                                                'step'             => 1,
                                                'label'            => __('Z Index'),
                                                'wrapInput'        => 'col col-xs-8',
                                                'class'            => 'form-control elementInput',
                                                'element-property' => 'number',
                                                'content'          => 'z_index'
                                            ]
                                        );

                                        //echo $this->Form->input('limit', ['value' => 0, 'label' => __('Hover child limit'), 'wrapInput' => 'col col-xs-8', 'class' => 'elementInput' ,'element-property' => 'text', 'content' => 'limit']);
                                        echo $this->Form->input('iconset', [
                                                'options'          => $this->Html->chosenPlaceholder($_iconset),
                                                'data-placeholder' => __('Please select...'),
                                                'multiple'         => false,
                                                'class'            => 'chosen elementInput',
                                                'style'            => 'width: 100%',
                                                'label'            => __('Iconset'),
                                                'wrapInput'        => 'col col-xs-8',
                                                'element-property' => 'chosen',
                                                'content'          => 'iconset',
                                            ]
                                        );
                                        echo $this->Form->end();
                                        ?>
                                    </div>
                                    <!-- Map form -->
                                    <div class="col-xs-12" id="addElement_map" style="display:none;">
                                        <div class="padding-top-20"></div>
                                        <?php
                                        echo $this->Form->create('addMap', [
                                            'class' => 'form-horizontal clear',
                                        ]);
                                        echo $this->Form->input('object_id', [
                                                'options'          => $this->Html->chosenPlaceholder($mapList),
                                                'data-placeholder' => __('Please select...'),
                                                'multiple'         => false,
                                                'class'            => 'chosen elementInput',
                                                'style'            => 'width: 100%',
                                                'label'            => __('Map'),
                                                'wrapInput'        => 'col col-xs-8',
                                                'element-property' => 'chosen',
                                                'content'          => 'object_id',
                                            ]
                                        );
                                        echo $this->Form->input('x', ['value' => 0, 'label' => __('Position X'), 'wrapInput' => 'col col-xs-8', 'class' => 'form-control elementInput', 'element-property' => 'text', 'content' => 'x']);
                                        echo $this->Form->input('y', ['value' => 0, 'label' => __('Position Y'), 'wrapInput' => 'col col-xs-8', 'class' => 'form-control elementInput', 'element-property' => 'text', 'content' => 'y']);

                                        echo $this->Form->input('z_index', [
                                                'type'             => 'number',
                                                'value'            => 0,
                                                'min'              => 0,
                                                'step'             => 1,
                                                'label'            => __('Z Index'),
                                                'wrapInput'        => 'col col-xs-8',
                                                'class'            => 'form-control elementInput',
                                                'element-property' => 'number',
                                                'content'          => 'z_index'
                                            ]
                                        );

                                        //echo $this->Form->input('limit', ['value' => 0, 'label' => __('Hover child limit'), 'wrapInput' => 'col col-xs-8', 'class' => 'elementInput' ,'element-property' => 'text', 'content' => 'limit']);
                                        echo $this->Form->input('iconset', [
                                                'options'          => $this->Html->chosenPlaceholder($_iconset),
                                                'data-placeholder' => __('Please select...'),
                                                'multiple'         => false,
                                                'class'            => 'chosen elementInput',
                                                'style'            => 'width: 100%',
                                                'label'            => __('Iconset'),
                                                'wrapInput'        => 'col col-xs-8',
                                                'element-property' => 'chosen',
                                                'content'          => 'iconset',
                                            ]
                                        );
                                        echo $this->Form->end();
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer elementWizardFooter">
                            <input type="hidden" value="<?php echo __('Delete'); ?>">
                            <input type="hidden" id="elementEditSaveText" value="<?php echo __('Save Element'); ?>">
                            <input type="hidden" id="elementAddSaveText" value="<?php echo __('Add Element'); ?>">
                            <button type="button" class="btn btn-default"
                                    data-dismiss="modal"><?php echo __('Close'); ?></button>
                            <button id="saveElementPropertiesBtn" type="button"
                                    class="btn btn-primary"><?php echo __('Add Element'); ?></button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- element wizard Modal end -->


            <!-- Line wizard Modal Dialog -->
            <div class="modal fade" id="LineWizardModal" tabindex="-1" role="dialog" aria-labelledby="LineWizardLabel"
                 aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal"><span
                                        aria-hidden="true">&times;</span><span
                                        class="sr-only"><?php echo __('Close'); ?></span></button>
                            <h4 class="modal-title" id="LineWizardLabel"><?php echo __('Line'); ?></h4>
                        </div>
                        <div class="modal-body">
                            <div id="LineWizardModalContent">
                                <div class="row">
                                    <div class="col-md-6">
                                        <select name="" id="LineWizardChoseType" class="chosen"
                                                data-placeholder="<?php echo __('Please select...'); ?>">
                                            <option value=""></option>
                                            <option value="host"><?php echo __('Host'); ?></option>
                                            <option value="service"><?php echo __('Service'); ?></option>
                                            <option value="servicegroup"><?php echo __('Servicegroup'); ?></option>
                                            <option value="hostgroup"><?php echo __('Hostgroup'); ?></option>
                                            <option value="stateless"><?php echo __('Stateless'); ?></option>
                                        </select>
                                    </div>
                                    <!-- Host form -->
                                    <div class="col-xs-12" id="addLine_host" style="display:none;">
                                        <div class="padding-top-20"></div>
                                        <?php
                                        echo $this->Form->create('addHostLine', [
                                            'class' => 'form-horizontal clear',
                                        ]);
                                        echo $this->Form->input('object_id', [
                                                //'options'          => $this->Html->chosenPlaceholder($hosts),
                                                'data-placeholder' => __('Please select...'),
                                                'multiple'         => false,
                                                'class'            => 'lineInput',
                                                'style'            => 'width: 100%',
                                                'label'            => __('Host'),
                                                'wrapInput'        => 'col col-xs-8',
                                                'element-property' => 'chosen',
                                                'content'          => 'object_id',
                                            ]
                                        );
                                        echo $this->Form->input('startX', ['value' => 0, 'label' => __('Start X'), 'wrapInput' => 'col col-xs-8', 'class' => 'form-control lineInput', 'element-property' => 'text', 'content' => 'startX']);
                                        echo $this->Form->input('endX', ['value' => 0, 'label' => __('End X'), 'wrapInput' => 'col col-xs-8', 'class' => 'form-control lineInput', 'element-property' => 'text', 'content' => 'endX']);
                                        echo $this->Form->input('startY', ['value' => 0, 'label' => __('Start Y'), 'wrapInput' => 'col col-xs-8', 'class' => 'form-control lineInput', 'element-property' => 'text', 'content' => 'startY']);
                                        echo $this->Form->input('endY', ['value' => 0, 'label' => __('End Y'), 'wrapInput' => 'col col-xs-8', 'class' => 'form-control lineInput', 'element-property' => 'text', 'content' => 'endY']);
                                        echo $this->Form->input('z_index', [
                                                'type'             => 'number',
                                                'value'            => 0,
                                                'min'              => 0,
                                                'step'             => 1,
                                                'label'            => __('Z Index'),
                                                'wrapInput'        => 'col col-xs-8',
                                                'class'            => 'form-control lineInput',
                                                'element-property' => 'number',
                                                'content'          => 'z_index'
                                            ]
                                        );
                                        //echo $this->Form->input('limit', ['value' => 0, 'label' => __('Hover child limit'), 'wrapInput' => 'col col-xs-8', 'class' => 'lineInput' ,'element-property' => 'text', 'content' => 'limit']);
                                        echo $this->Form->end();
                                        ?>
                                    </div>
                                    <!-- Service form -->
                                    <div class="col-xs-12" id="addLine_service" style="display:none;">
                                        <div class="padding-top-20"></div>
                                        <?php
                                        echo $this->Form->create('addServiceLine', [
                                            'class' => 'form-horizontal clear',
                                        ]);
                                        echo $this->Form->input('HostObject_id', [
                                                //'options'          => $this->Html->chosenPlaceholder($hosts),
                                                'data-placeholder' => __('Please select...'),
                                                'multiple'         => false,
                                                'class'            => 'lineInput',
                                                'style'            => 'width: 100%',
                                                'label'            => __('Host'),
                                                'wrapInput'        => 'col col-xs-8',
                                                'element-property' => 'chosen',
                                                'content'          => 'object_id',
                                            ]
                                        );
                                        echo $this->Form->input('object_id', [
                                                'data-placeholder' => __('Please select...'),
                                                'multiple'         => false,
                                                'class'            => 'chosen lineInput',
                                                'style'            => 'width: 100%',
                                                'label'            => __('Service'),
                                                'wrapInput'        => 'col col-xs-8',
                                                'element-property' => 'chosen',
                                                'content'          => 'object_id',
                                            ]
                                        );
                                        echo $this->Form->input('startX', ['value' => 0, 'label' => __('Start X'), 'wrapInput' => 'col col-xs-8', 'class' => 'form-control lineInput', 'element-property' => 'text', 'content' => 'startX']);
                                        echo $this->Form->input('endX', ['value' => 0, 'label' => __('End X'), 'wrapInput' => 'col col-xs-8', 'class' => 'form-control lineInput', 'element-property' => 'text', 'content' => 'endX']);
                                        echo $this->Form->input('startY', ['value' => 0, 'label' => __('Start Y'), 'wrapInput' => 'col col-xs-8', 'class' => 'form-control lineInput', 'element-property' => 'text', 'content' => 'startY']);
                                        echo $this->Form->input('endY', ['value' => 0, 'label' => __('End Y'), 'wrapInput' => 'col col-xs-8', 'class' => 'form-control lineInput', 'element-property' => 'text', 'content' => 'endY']);
                                        echo $this->Form->input('z_index', [
                                                'type'             => 'number',
                                                'value'            => 0,
                                                'min'              => 0,
                                                'step'             => 1,
                                                'label'            => __('Z Index'),
                                                'wrapInput'        => 'col col-xs-8',
                                                'class'            => 'form-control lineInput',
                                                'element-property' => 'number',
                                                'content'          => 'z_index'
                                            ]
                                        );
                                        //echo $this->Form->input('limit', ['value' => 0, 'label' => __('Hover child limit'), 'wrapInput' => 'col col-xs-8', 'class' => 'lineInput' ,'element-property' => 'text', 'content' => 'limit']);
                                        echo $this->Form->end();
                                        ?>
                                    </div>
                                    <!-- Servicegroup form -->
                                    <div class="col-xs-12" id="addLine_servicegroup" style="display:none;">
                                        <div class="padding-top-20"></div>
                                        <?php
                                        echo $this->Form->create('addServicegroupLine', [
                                            'class' => 'form-horizontal clear',
                                        ]);
                                        echo $this->Form->input('object_id', [
                                                'options'          => $this->Html->chosenPlaceholder($servicegroup),
                                                'data-placeholder' => __('Please select...'),
                                                'multiple'         => false,
                                                'class'            => 'chosen lineInput',
                                                'style'            => 'width: 100%',
                                                'label'            => __('Servicegroup'),
                                                'wrapInput'        => 'col col-xs-8',
                                                'element-property' => 'chosen',
                                                'content'          => 'object_id',
                                            ]
                                        );
                                        echo $this->Form->input('startX', ['value' => 0, 'label' => __('Start X'), 'wrapInput' => 'col col-xs-8', 'class' => 'lineInput', 'element-property' => 'text', 'content' => 'startX']);
                                        echo $this->Form->input('endX', ['value' => 0, 'label' => __('End X'), 'wrapInput' => 'col col-xs-8', 'class' => 'lineInput', 'element-property' => 'text', 'content' => 'endX']);
                                        echo $this->Form->input('startY', ['value' => 0, 'label' => __('Start Y'), 'wrapInput' => 'col col-xs-8', 'class' => 'lineInput', 'element-property' => 'text', 'content' => 'startY']);
                                        echo $this->Form->input('endY', ['value' => 0, 'label' => __('End Y'), 'wrapInput' => 'col col-xs-8', 'class' => 'lineInput', 'element-property' => 'text', 'content' => 'endY']);
                                        echo $this->Form->input('z_index', [
                                                'type'             => 'number',
                                                'value'            => 0,
                                                'min'              => 0,
                                                'step'             => 1,
                                                'label'            => __('Z Index'),
                                                'wrapInput'        => 'col col-xs-8',
                                                'class'            => 'form-control lineInput',
                                                'element-property' => 'number',
                                                'content'          => 'z_index'
                                            ]
                                        );
                                        //echo $this->Form->input('limit', ['value' => 0, 'label' => __('Hover child limit'), 'wrapInput' => 'col col-xs-8', 'class' => 'lineInput' ,'element-property' => 'text', 'content' => 'limit']);
                                        echo $this->Form->end();
                                        ?>
                                    </div>
                                    <!-- Hostgroup form -->
                                    <div class="col-xs-12" id="addLine_hostgroup" style="display:none;">
                                        <div class="padding-top-20"></div>
                                        <?php
                                        echo $this->Form->create('addHostgroupLine', [
                                            'class' => 'form-horizontal clear',
                                        ]);
                                        echo $this->Form->input('object_id', [
                                                'options'          => $this->Html->chosenPlaceholder($hostgroup),
                                                'data-placeholder' => __('Please select...'),
                                                'multiple'         => false,
                                                'class'            => 'chosen lineInput',
                                                'style'            => 'width: 100%',
                                                'label'            => __('Hostgroup'),
                                                'wrapInput'        => 'col col-xs-8',
                                                'element-property' => 'chosen',
                                                'content'          => 'object_id',
                                            ]
                                        );
                                        echo $this->Form->input('startX', ['value' => 0, 'label' => __('Start X'), 'wrapInput' => 'col col-xs-8', 'class' => 'lineInput', 'element-property' => 'text', 'content' => 'startX']);
                                        echo $this->Form->input('endX', ['value' => 0, 'label' => __('End X'), 'wrapInput' => 'col col-xs-8', 'class' => 'lineInput', 'element-property' => 'text', 'content' => 'endX']);
                                        echo $this->Form->input('startY', ['value' => 0, 'label' => __('Start Y'), 'wrapInput' => 'col col-xs-8', 'class' => 'lineInput', 'element-property' => 'text', 'content' => 'startY']);
                                        echo $this->Form->input('endY', ['value' => 0, 'label' => __('End Y'), 'wrapInput' => 'col col-xs-8', 'class' => 'lineInput', 'element-property' => 'text', 'content' => 'endY']);
                                        echo $this->Form->input('z_index', [
                                                'type'             => 'number',
                                                'value'            => 0,
                                                'min'              => 0,
                                                'step'             => 1,
                                                'label'            => __('Z Index'),
                                                'wrapInput'        => 'col col-xs-8',
                                                'class'            => 'form-control lineInput',
                                                'element-property' => 'number',
                                                'content'          => 'z_index'
                                            ]
                                        );
                                        //echo $this->Form->input('limit', ['value' => 0, 'label' => __('Hover child limit'), 'wrapInput' => 'col col-xs-8', 'class' => 'lineInput' ,'element-property' => 'text', 'content' => 'limit']);
                                        echo $this->Form->end();
                                        ?>
                                    </div>
                                    <div class="col-xs-12" id="addLine_stateless" style="display:none;">
                                        <div class="padding-top-20"></div>
                                        <?php
                                        echo $this->Form->create('addStatelessLine', [
                                            'class' => 'form-horizontal clear',
                                        ]);
                                        echo $this->Form->input('startX', ['value' => 0, 'label' => __('Start X'), 'wrapInput' => 'col col-xs-8', 'class' => 'lineInput', 'element-property' => 'text', 'content' => 'startX']);
                                        echo $this->Form->input('endX', ['value' => 0, 'label' => __('End X'), 'wrapInput' => 'col col-xs-8', 'class' => 'lineInput', 'element-property' => 'text', 'content' => 'endX']);
                                        echo $this->Form->input('startY', ['value' => 0, 'label' => __('Start Y'), 'wrapInput' => 'col col-xs-8', 'class' => 'lineInput', 'element-property' => 'text', 'content' => 'startY']);
                                        echo $this->Form->input('endY', ['value' => 0, 'label' => __('End Y'), 'wrapInput' => 'col col-xs-8', 'class' => 'lineInput', 'element-property' => 'text', 'content' => 'endY']);
                                        echo $this->Form->input('z_index', [
                                                'type'             => 'number',
                                                'value'            => 0,
                                                'min'              => 0,
                                                'step'             => 1,
                                                'label'            => __('Z Index'),
                                                'wrapInput'        => 'col col-xs-8',
                                                'class'            => 'form-control lineInput',
                                                'element-property' => 'number',
                                                'content'          => 'z_index'
                                            ]
                                        );
                                        //echo $this->Form->input('limit', ['value' => 0, 'label' => __('Hover child limit'), 'wrapInput' => 'col col-xs-8', 'class' => 'lineInput' ,'element-property' => 'text', 'content' => 'limit']);
                                        echo $this->Form->end();
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer lineWizardFooter">
                            <input type="hidden" value="<?php echo __('Delete'); ?>">
                            <button type="button" class="btn btn-default"
                                    data-dismiss="modal"><?php echo __('Close'); ?></button>
                            <button id="saveLinePropertiesBtn" type="button"
                                    class="btn btn-primary"><?php echo __('Save Line'); ?></button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- line wizard Modal end -->

            <!-- Gadget wizard Modal Dialog -->
            <div class="modal fade" id="GadgetWizardModal" tabindex="-1" role="dialog"
                 aria-labelledby="GadgetWizardLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal"><span
                                        aria-hidden="true">&times;</span><span
                                        class="sr-only"><?php echo __('Close'); ?></span></button>
                            <h4 class="modal-title" id="GadgetWizardLabel"><?php echo __('Edit Gadget'); ?></h4>
                            <span id="tempGadgetUUID"></span>
                        </div>
                        <div class="modal-body">
                            <div id="GadgetWizardModalContent">
                                <div class="row">
                                    <div class="col-md-6" style="display:none">
                                        <select name="" id="GadgetWizardChoseType" class="chosen"
                                                data-placeholder="<?php echo __('Please select...'); ?>">
                                            <option value="service"><?php echo __('Service'); ?></option>
                                        </select>
                                    </div>
                                    <!-- Service form -->
                                    <div class="col-xs-12" id="addGadget_service">
                                        <div class="padding-top-20"></div>
                                        <?php
                                        echo $this->Form->create('addServiceGadget', [
                                            'class' => 'form-horizontal clear',
                                        ]);
                                        echo $this->Form->input('HostObject_id', [
                                                //'options'          => $this->Html->chosenPlaceholder($hosts),
                                                'data-placeholder' => __('Please select...'),
                                                'multiple'         => false,
                                                'class'            => 'gadgetInput',
                                                'style'            => 'width: 100%',
                                                'label'            => __('Host'),
                                                'wrapInput'        => 'col col-xs-8',
                                                'element-property' => 'chosen',
                                                'content'          => 'object_id',
                                            ]
                                        );
                                        echo $this->Form->input('object_id', [
                                                'data-placeholder' => __('Please select...'),
                                                'multiple'         => false,
                                                'class'            => 'chosen gadgetInput',
                                                'style'            => 'width: 100%',
                                                'label'            => __('Service'),
                                                'wrapInput'        => 'col col-xs-8',
                                                'element-property' => 'chosen',
                                                'content'          => 'object_id',
                                            ]
                                        );
                                        echo $this->Form->input('x', [
                                            'value'            => 0,
                                            'label'            => __('Position X'),
                                            'wrapInput'        => 'col col-xs-8',
                                            'class'            => 'form-control gadgetInput',
                                            'element-property' => 'text',
                                            'content'          => 'x'
                                        ]);
                                        echo $this->Form->input('y', [
                                            'value'            => 0,
                                            'label'            => __('Position Y'),
                                            'wrapInput'        => 'col col-xs-8',
                                            'class'            => 'form-control gadgetInput',
                                            'element-property' => 'text',
                                            'content'          => 'y'
                                        ]);

                                        echo $this->Form->input('size_x', [
                                            'value'            => 0,
                                            'label'            => __('Size X'),
                                            'wrapInput'        => 'col col-xs-8',
                                            'class'            => 'gadgetInput',
                                            'element-property' => 'text',
                                            'content'          => 'size_x'
                                        ]);

                                        echo $this->Form->input('size_y', [
                                            'value'            => 0,
                                            'label'            => __('Size Y'),
                                            'wrapInput'        => 'col col-xs-8',
                                            'class'            => 'gadgetInput',
                                            'element-property' => 'text',
                                            'content'          => 'size_y'
                                        ]);
                                        echo $this->Form->input('z_index', [
                                                'type'             => 'number',
                                                'value'            => 0,
                                                'min'              => 0,
                                                'step'             => 1,
                                                'label'            => __('Z Index'),
                                                'wrapInput'        => 'col col-xs-8',
                                                'class'            => 'form-control gadgetInput',
                                                'element-property' => 'number',
                                                'content'          => 'z_index'
                                            ]
                                        );
                                        echo $this->Form->fancyCheckbox('transparent_background', [
                                            'caption'          => __('Transparent Background'),
                                            'captionGridClass' => 'col col-md-4 hidden rrdBackground',
                                            'class'            => 'onoffswitch-checkbox gadgetInput',
                                            'wrapGridClass'    => 'col col-xs-8 hidden rrdBackground',
                                            'content'          => 'transparent_background',
                                        ]);
                                        echo $this->Form->fancyCheckbox('show_label', [
                                            'caption'          => __('Show Label'),
                                            'captionGridClass' => 'col col-md-3 hidden showLabel',
                                            'class'            => 'onoffswitch-checkbox gadgetInput',
                                            'wrapGridClass'    => 'col col-xs-9 hidden showLabel',
                                            'content'          => 'show_label',
                                        ]);

                                        echo $this->Form->input('font_size', [
                                            'value'            => 13,
                                            'caption'          => __('Font Size'),
                                            'captionGridClass' => 'col col-md-3 gadgetFontSize hidden',
                                            'wrapGridClass'    => 'col col-xs-9 gadgetFontSize hidden',
                                            'wrapInput'        => 'col col-xs-8',
                                            'class'            => 'form-control gadgetInput',
                                            'element-property' => 'text',
                                            'content'          => 'font_size'
                                        ]);
                                        //echo $this->Form->input('limit', ['value' => 0, 'label' => __('Hover child limit'), 'wrapInput' => 'col col-xs-8', 'class' => 'gadgetInput' ,'element-property' => 'text', 'content' => 'limit']);
                                        echo $this->Form->end();
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer gadgetWizardFooter">
                            <input type="hidden" value="<?php echo __('Delete'); ?>">
                            <input type="hidden" id="gadgetEditSaveText" value="<?php echo __('Save Gadget'); ?>">
                            <input type="hidden" id="gadgetAddSaveText" value="<?php echo __('Add Gadget'); ?>">
                            <button type="button" class="btn btn-default"
                                    data-dismiss="modal"><?php echo __('Close'); ?></button>
                            <button id="saveGadgetPropertiesBtn" type="button"
                                    class="btn btn-primary"><?php echo __('Add Gadget'); ?></button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- gadget wizard Modal end -->
            <!-- statelessIcon wizard Modal Dialog -->
            <div class="modal fade" id="StatelessIconWizardModal" tabindex="-1" role="dialog"
                 aria-labelledby="StatelessIconWizardLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal"><span
                                        aria-hidden="true">&times;</span><span
                                        class="sr-only"><?php echo __('Close'); ?></span></button>
                            <h4 class="modal-title"
                                id="StatelessIconWizardLabel"><?php echo __('Edit Stateless Icon'); ?></h4>
                            <span id="tempStatelessIconUUID"></span>
                        </div>
                        <div class="modal-body">
                            <div id="StatelessIconWizardModalContent">
                                <div class="row">
                                    <div class="col-xs-12" id="editStatelessIcons">
                                        <div class="padding-top-20"></div>
                                        <?php
                                        echo $this->Form->create('editStatelessIcon', [
                                            'class' => 'form-horizontal clear',
                                        ]);
                                        echo $this->Form->input('x', ['value' => 0, 'label' => __('Position X'), 'wrapInput' => 'col col-xs-8', 'class' => 'statelessIconInput', 'element-property' => 'text', 'content' => 'x']);
                                        echo $this->Form->input('y', ['value' => 0, 'label' => __('Position Y'), 'wrapInput' => 'col col-xs-8', 'class' => 'statelessIconInput', 'class' => 'statelessIconInput', 'element-property' => 'text', 'content' => 'y']);
                                        echo $this->Form->input('z_index', [
                                                'type'             => 'number',
                                                'value'            => 0,
                                                'min'              => 0,
                                                'step'             => 1,
                                                'label'            => __('Z Index'),
                                                'wrapInput'        => 'col col-xs-8',
                                                'class'            => 'form-control statelessIconInput',
                                                'element-property' => 'number',
                                                'content'          => 'z_index'
                                            ]
                                        );
                                        echo $this->Form->end();
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer statelessIconWizardFooter">
                            <input type="hidden" value="<?php echo __('Delete'); ?>">
                            <button type="button" class="btn btn-default"
                                    data-dismiss="modal"><?php echo __('Close'); ?></button>
                            <button id="saveStatelessIconPropertiesBtn" type="button"
                                    class="btn btn-primary"><?php echo __('Save Stateless Icon'); ?></button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- statelessIcon wizard Modal end -->
            <!-- Text Modal Dialog -->
            <div class="modal fade" id="textWizardModal" tabindex="-1" role="dialog" aria-labelledby="textWizardLabel"
                 aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal"><span
                                        aria-hidden="true">&times;</span><span
                                        class="sr-only"><?php echo __('Close'); ?></span></button>
                            <h4 class="modal-title" id="textWizardLabel"><?php echo __('Text'); ?></h4>
                            <span id="tempTextUUID"></span>
                        </div>
                        <div class="modal-body">
                            <div id="textWizardModalContent">
                                <div id="insert-link-area" class="row">
                                    <?php
                                    echo $this->Form->input('insert-link-type', [
                                        'options'          => [__('Other map'), __('External link')],
                                        'data-placeholder' => __('Please select...'),
                                        'label'            => ['text' => __('Link type')],
                                        'class'            => 'chosen col',
                                        'selected'         => 0,
                                        'multiple'         => false,
                                    ]);
                                    ?>
                                    <div class="col-lg-12 margin-top-5 no-padding" id="link-map-area">
                                        <?php
                                        echo $this->Form->input('insert-link-map', [
                                                'options'          => $this->Html->chosenPlaceholder($mapList),
                                                'data-placeholder' => __('Please select...'),
                                                'multiple'         => false,
                                                'class'            => 'chosen col margin-top-5',
                                                'style'            => 'width: 100%',
                                                'label'            => __('Map'),
                                                'element-property' => 'chosen',
                                            ]
                                        );
                                        ?>
                                    </div>
                                    <div class="col-lg-12 margin-top-5 no-padding" id="link-url-area"
                                         style="display:none;">
                                        <?php echo $this->Form->input('insert-link-url', [
                                            'label'       => __('URL:'),
                                            'placeholder' => 'https://openitcockpit.io',
                                            'style'       => 'width: 100%;',
                                            'class'       => 'margin-top-5'
                                        ]); ?>
                                    </div>
                                    <?php echo $this->Form->input('insert-link-description', [
                                        'label'       => __('Description:'),
                                        'placeholder' => __('Official page for openITCOCKPIT'),
                                        'style'       => 'width: 100%;',
                                        'class'       => 'margin-top-5'
                                    ]); ?>
                                    <div class="col-lg-12 margin-top-5 no-padding">
                                        <?php echo $this->Form->fancyCheckbox('insert-link-tab', [
                                            'caption'          => __('Open in a new tab'),
                                            'wrapGridClass'    => 'col col-md-1',
                                            'captionGridClass' => 'col col-md-2',
                                            'captionClass'     => 'control-label text-left',
                                            'checked'          => 1,
                                        ]); ?>
                                    </div>
                                    <div class="col-lg-12 text-right margin-top-5">
                                        <button id="cancel-insert-link" type="button"
                                                class="btn btn-default"><?php echo __('Cancel'); ?></button>
                                        <button id="perform-insert-link" type="button"
                                                class="btn btn-primary"><?php echo __('Insert'); ?></button>
                                    </div>
                                </div>
                                <div id="insert-text-area" class="row">
                                    <div class="col-xs-12" id="editText">
                                        <div class="padding-top-20"></div>
                                        <?php
                                        echo $this->Form->create('editText', [
                                            'class' => 'form-horizontal clear',
                                        ]);
                                        ?>
                                        <div class="jarviswidget">
                                            <header>
                                                <div class="widget-toolbar pull-left" role="menu">
                                                    <div class="btn-group">
                                                        <a href="javascript:void(0);" class="btn btn-xs btn-default"><i
                                                                    class="fa fa-font"></i> <?php echo __('Font size'); ?>
                                                        </a>
                                                        <a href="javascript:void(0);" data-toggle="dropdown"
                                                           class="btn btn-xs btn-default dropdown-toggle"><span
                                                                    class="caret"></span></a>
                                                        <ul class="dropdown-menu">
                                                            <li>
                                                                <a href="javascript:void(0);" select-fsize="true"
                                                                   fsize="xx-small"><?php echo __('Smallest'); ?></a>
                                                            </li>
                                                            <li>
                                                                <a href="javascript:void(0);" select-fsize="true"
                                                                   fsize="x-small"><?php echo __('Smaller'); ?></a>
                                                            </li>
                                                            <li>
                                                                <a href="javascript:void(0);" select-fsize="true"
                                                                   fsize="small"><?php echo __('Small'); ?></a>
                                                            </li>
                                                            <li class="divider"></li>
                                                            <li>
                                                                <a href="javascript:void(0);" select-fsize="true"
                                                                   fsize="large"><?php echo __('Big'); ?></a>
                                                            </li>
                                                            <li>
                                                                <a href="javascript:void(0);" select-fsize="true"
                                                                   fsize="x-large"><?php echo __('Bigger'); ?></a>
                                                            </li>
                                                            <li>
                                                                <a href="javascript:void(0);" select-fsize="true"
                                                                   fsize="xx-large"><?php echo __('Biggest'); ?></a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                    <div class="widget-toolbar pull-left" style="border:0px;"
                                                         role="menu">
                                                        <a href="javascript:void(0);"
                                                           class="dropdown-toggle color-box selector bg-color-darken"
                                                           id="currentColor" color="#404040"
                                                           current-color="bg-color-darken" data-toggle="dropdown"></a>
                                                        <ul class="dropdown-menu arrow-box-up-right pull-right color-select">
                                                            <li style="display: inline-block; margin:0; float: none;">
                                                                <span data-original-title="<?php echo __('Green Grass'); ?>"
                                                                      data-placement="left" rel="tooltip"
                                                                      data-widget-setstyle="jarviswidget-color-green"
                                                                      select-color="true" color="#356E35"
                                                                      class="bg-color-green"></span></li>
                                                            <li style="display: inline-block; margin:0; float: none;">
                                                                <span data-original-title="<?php echo __('Dark Green'); ?>"
                                                                      data-placement="top" rel="tooltip"
                                                                      data-widget-setstyle="jarviswidget-color-greenDark"
                                                                      select-color="true" color="#496949"
                                                                      class="bg-color-greenDark"></span></li>
                                                            <li style="display: inline-block; margin:0; float: none;">
                                                                <span data-original-title="<?php echo __('Light Green'); ?>"
                                                                      data-placement="top" rel="tooltip"
                                                                      data-widget-setstyle="jarviswidget-color-greenLight"
                                                                      select-color="true" color="#71843F"
                                                                      class="bg-color-greenLight"></span></li>
                                                            <li style="display: inline-block; margin:0; float: none;">
                                                                <span data-original-title="<?php echo __('Purple'); ?>"
                                                                      data-placement="top" rel="tooltip"
                                                                      data-widget-setstyle="jarviswidget-color-purple"
                                                                      select-color="true" color="#6E587A"
                                                                      class="bg-color-purple"></span></li>
                                                            <li style="display: inline-block; margin:0; float: none;">
                                                                <span data-original-title="<?php echo __('Magenta'); ?>"
                                                                      data-placement="top" rel="tooltip"
                                                                      data-widget-setstyle="jarviswidget-color-magenta"
                                                                      select-color="true" color="#6E3671"
                                                                      class="bg-color-magenta"></span></li>
                                                            <li style="display: inline-block; margin:0; float: none;">
                                                                <span data-original-title="<?php echo __('Pink'); ?>"
                                                                      data-placement="right" rel="tooltip"
                                                                      data-widget-setstyle="jarviswidget-color-pink"
                                                                      select-color="true" color="#AC5287"
                                                                      class="bg-color-pink"></span></li>
                                                            <li style="display: inline-block; margin:0; float: none;">
                                                                <span data-original-title="<?php echo __('Fade Pink'); ?>"
                                                                      data-placement="left" rel="tooltip"
                                                                      data-widget-setstyle="jarviswidget-color-pinkDark"
                                                                      select-color="true" color="#A8829F"
                                                                      class="bg-color-pinkDark"></span></li>
                                                            <li style="display: inline-block; margin:0; float: none;">
                                                                <span data-original-title="<?php echo __('Light Blue'); ?>"
                                                                      data-placement="top" rel="tooltip"
                                                                      data-widget-setstyle="jarviswidget-color-blueLight"
                                                                      select-color="true" color="#92A2A8"
                                                                      class="bg-color-blueLight"></span></li>
                                                            <li style="display: inline-block; margin:0; float: none;">
                                                                <span data-original-title="<?php echo __('Teal'); ?>"
                                                                      data-placement="top" rel="tooltip"
                                                                      data-widget-setstyle="jarviswidget-color-teal"
                                                                      select-color="true" color="#568A89"
                                                                      class="bg-color-teal"></span></li>
                                                            <li style="display: inline-block; margin:0; float: none;">
                                                                <span data-original-title="<?php echo __('Ocean Blue'); ?>"
                                                                      data-placement="top" rel="tooltip"
                                                                      data-widget-setstyle="jarviswidget-color-blue"
                                                                      select-color="true" color="#57889C"
                                                                      class="bg-color-blue"></span></li>
                                                            <li style="display: inline-block; margin:0; float: none;">
                                                                <span data-original-title="<?php echo __('Night Sky'); ?>"
                                                                      data-placement="top" rel="tooltip"
                                                                      data-widget-setstyle="jarviswidget-color-blueDark"
                                                                      select-color="true" color="#4C4F53"
                                                                      class="bg-color-blueDark"></span></li>
                                                            <li style="display: inline-block; margin:0; float: none;">
                                                                <span data-original-title="<?php echo __('Night'); ?>"
                                                                      data-placement="right" rel="tooltip"
                                                                      data-widget-setstyle="jarviswidget-color-darken"
                                                                      select-color="true" color="#404040"
                                                                      class="bg-color-darken"></span></li>
                                                            <li style="display: inline-block; margin:0; float: none;">
                                                                <span data-original-title="<?php echo __('Day Light'); ?>"
                                                                      data-placement="left" rel="tooltip"
                                                                      data-widget-setstyle="jarviswidget-color-yellow"
                                                                      select-color="true" color="#B09B5B"
                                                                      class="bg-color-yellow"></span></li>
                                                            <li style="display: inline-block; margin:0; float: none;">
                                                                <span data-original-title="<?php echo __('Orange'); ?>"
                                                                      data-placement="bottom" rel="tooltip"
                                                                      data-widget-setstyle="jarviswidget-color-orange"
                                                                      select-color="true" color="#C79121"
                                                                      class="bg-color-orange"></span></li>
                                                            <li style="display: inline-block; margin:0; float: none;">
                                                                <span data-original-title="<?php echo __('Dark Orange'); ?>"
                                                                      data-placement="bottom" rel="tooltip"
                                                                      data-widget-setstyle="jarviswidget-color-orangeDark"
                                                                      select-color="true" color="#A57225"
                                                                      class="bg-color-orangeDark"></span></li>
                                                            <li style="display: inline-block; margin:0; float: none;">
                                                                <span data-original-title="<?php echo __('Red Rose'); ?>"
                                                                      data-placement="bottom" rel="tooltip"
                                                                      data-widget-setstyle="jarviswidget-color-red"
                                                                      select-color="true" color="#A90329"
                                                                      class="bg-color-red"></span></li>
                                                            <li style="display: inline-block; margin:0; float: none;">
                                                                <span data-original-title="<?php echo __('Light Red'); ?>"
                                                                      data-placement="bottom" rel="tooltip"
                                                                      data-widget-setstyle="jarviswidget-color-redLight"
                                                                      select-color="true" color="#A65858"
                                                                      class="bg-color-redLight"></span></li>
                                                            <li style="display: inline-block; margin:0; float: none;">
                                                                <span data-original-title="<?php echo __('Purity'); ?>"
                                                                      data-placement="right" rel="tooltip"
                                                                      data-widget-setstyle="jarviswidget-color-white"
                                                                      select-color="true" color="#FFFFFF"
                                                                      class="bg-color-white"></span></li>
                                                        </ul>
                                                    </div>
                                                    <span class="padding-left-10"></span>
                                                    <a href="javascript:void(0);" class="btn btn-default" wysiwyg="true"
                                                       task="bold"><i class="fa fa-bold"></i></a>
                                                    <a href="javascript:void(0);" class="btn btn-default" wysiwyg="true"
                                                       task="italic"><i class="fa fa-italic"></i></a>
                                                    <a href="javascript:void(0);" class="btn btn-default" wysiwyg="true"
                                                       task="underline"><i class="fa fa-underline"></i></a>
                                                    <span class="padding-left-10"></span>
                                                    <a href="javascript:void(0);" class="btn btn-default" wysiwyg="true"
                                                       task="left"><i class="fa fa-align-left"></i></a>
                                                    <a href="javascript:void(0);" class="btn btn-default" wysiwyg="true"
                                                       task="center"><i class="fa fa-align-center"></i></a>
                                                    <a href="javascript:void(0);" class="btn btn-default" wysiwyg="true"
                                                       task="right"><i class="fa fa-align-right"></i></a>
                                                    <a href="javascript:void(0);" class="btn btn-default" wysiwyg="true"
                                                       task="justify"><i class="fa fa-align-justify"></i></a>
                                                    <span class="padding-left-10"></span>
                                                    <a href="javascript:void(0);" class="btn btn-default"
                                                       id="insert-link"><i class="fa fa-link"></i></a>
                                                </div>
                                                <div class="widget-toolbar pull-right" role="menu"></div>
                                            </header>
                                            <div>
                                                <div class="widget-body">
                                                    <textarea class="form-control" style="width: 100%; height: 200px;"
                                                              id="docuText"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <?php
                                        //										echo $this->Form->input('text', ['label' => __('Text'), 'wrapInput' => 'col col-xs-8', 'class' => 'textInput' ,'element-property' => 'text', 'content' => 'text', 'placeholder' => __('Please Enter your Text')]);
                                        //										echo $this->Form->input('font_size', ['value' => 12, 'label' => __('Font Size'), 'wrapInput' => 'col col-xs-8', 'class' => 'textInput' ,'element-property' => 'font_size', 'content' => 'font_size']);
                                        echo $this->Form->input('z_index', [
                                                'type'             => 'number',
                                                'value'            => 0,
                                                'min'              => 0,
                                                'step'             => 1,
                                                'label'            => __('Z Index'),
                                                'wrapInput'        => 'col col-xs-8',
                                                'class'            => 'form-control textInput',
                                                'element-property' => 'number',
                                                'content'          => 'z_index'
                                            ]
                                        );
                                        echo $this->Form->end();
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="insert-modal-footer" class="modal-footer textWizardFooter">
                            <button id="deleteTextPropertiesBtn" type="button" class="btn btn-danger"
                                    style="display:none;"><?php echo __('Delete'); ?></button>
                            <button id="dismissTextProperties" type="button" class="btn btn-default"
                                    data-dismiss="modal"><?php echo __('Close'); ?></button>
                            <button id="saveTextPropertiesBtn" type="button"
                                    class="btn btn-primary"><?php echo __('Save Text'); ?></button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Text wizard Modal end -->
            <!-- Delete Background Modal Dialog -->
            <div class="modal fade" id="deleteBackgroundModal" tabindex="-1" role="dialog"
                 aria-labelledby="deleteBackgroundLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal"><span
                                        aria-hidden="true">&times;</span><span
                                        class="sr-only"><?php echo __('Close'); ?></span></button>
                            <h4 class="modal-title"
                                id="deleteBackgroundLabel"><?php echo __('Delete Background image'); ?></h4>
                        </div>
                        <input type="hidden" id="backgoundFilename" value=""/>
                        <div class="modal-body">
                            <div id="deleteBackgroundModalContent">
                                <div class="row">
                                    <div class="col-xs-12" id="editText">
                                        <div class="padding-top-20"></div>
                                        <?php
                                        echo __('Do you really want to delete this Background image? It could be used in multiple maps so please check if can be deleted');
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer deleteBackgroundFooter">
                            <button id="confirmDeleteBackgroundBtn" type="button"
                                    class="btn btn-danger"><?php echo __('Delete'); ?></button>
                            <button id="dismissDeleteBackground" type="button" class="btn btn-default"
                                    data-dismiss="modal"><?php echo __('Cancel'); ?></button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Delete Background Modal end -->

            <!-- Delete Iconset Modal Dialog -->
            <div class="modal fade" id="deleteIconsetModal" tabindex="-1" role="dialog"
                 aria-labelledby="deleteIconsetLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal"><span
                                        aria-hidden="true">&times;</span><span
                                        class="sr-only"><?php echo __('Close'); ?></span></button>
                            <h4 class="modal-title" id="deleteIconsetLabel"><?php echo __('Delete Icon set'); ?></h4>
                        </div>
                        <input type="hidden" id="IconsetId" value=""/>
                        <div class="modal-body">
                            <div id="deleteBackgroundModalContent">
                                <div class="row">
                                    <div class="col-xs-12" id="editText">
                                        <div class="padding-top-20"></div>
                                        <?php
                                        echo __('Do you really want to delete this Icon set? It could be used in multiple maps so please check if can be deleted');
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer deleteIconsetFooter">
                            <button id="confirmDeleteIconsetBtn" type="button"
                                    class="btn btn-danger"><?php echo __('Delete'); ?></button>
                            <button id="dismissDeleteIconset" type="button" class="btn btn-default"
                                    data-dismiss="modal"><?php echo __('Cancel'); ?></button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Delete Iconset Modal end -->
        </div>
    </div>
</div>