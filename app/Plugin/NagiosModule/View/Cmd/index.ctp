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
            <i class="fa fa-terminal fa-fw "></i>
            <?php echo __('Monitoring'); ?>
            <span>>
                <?php echo __('External Commands'); ?>
            </span>
        </h1>
    </div>
</div>
<div id="error_msg"></div>

<div class="jarviswidget" id="wid-id-0">
    <header>
        <span class="widget-icon"> <i class="fa fa-terminal"></i> </span>
        <h2><?php echo __('External Commands'); ?></h2>
    </header>
    <div>
        <div class="widget-body">
            <div class="row">
                <div class="col-xs-12 col-md-12 col-lg-12">
                    <div class="padding-bottom-10">
                        <i class="fa fa-info-circle text-info"></i>
                        <?php echo __('In some cases you may need to send an external command to the monitoring engine for transmitting a passive service state or something similiar.'); ?>
                        <br/>
                        <?php echo __('This interface was designed to help you create these commands by generating an example for each supported command. Additionally you see a list of supported parameters and default values'); ?>
                    </div>

                    <?php
                    echo $this->Form->create('Cmd', [
                        'class' => 'form-horizontal clear',
                    ]);

                    $_commands[0] = '';
                    foreach ($commands as $commandName => $parameters):
                        $_commands[$commandName] = $commandName;
                    endforeach;
                    echo $this->Form->input('Command', [
                        'options'   => $_commands,
                        'class'     => 'chosen',
                        'style'     => 'width: 100%;',
                        'label'     => ['text' => __('External Command'), 'class' => 'col-xs-2 col-md-2 col-lg-2'],
                        'wrapInput' => 'col col-xs-9 col-md-9 col-lg-9',
                    ]);
                    echo $this->Form->end();
                    ?>

                    <?php foreach ($commands as $commandName => $parameters): ?>
                        <div id="<?php echo h($commandName); ?>" style="display:none;" class="api_structure_container">
                            <strong><?php echo __('API structure'); ?>:</strong>
                            <div class="well">
                                <?php if (empty($parameters)): ?>
                                    <i class="text-info"><?php echo __('This command has no parameters'); ?></i>
                                <?php else: ?>
                                    <dl class="dl-horizontal">
                                        <?php foreach ($parameters as $paramName => $paramValue): ?>
                                            <?php
                                            if ($paramName === 'internalMethod') continue;
                                            if ($paramValue === null):
                                                echo '<dt class="hintmark_red col col-md-2">'.$paramName.'</dt>';
                                                echo '<dd><i>'.__('required').'</i></dd>';
                                            else:
                                                echo '<dt class="col col-md-2">'.$paramName.'</dt>';
                                                echo '<dd>'.$paramValue.'</dd>';
                                            endif; ?>
                                        <?php endforeach; ?>
                                    </dl>
                                <?php endif; ?>
                            </div>
                            <br/>
                            <strong><?php echo __('Example HTTP request using default parameters'); ?>:</strong>
                            <div class="well">
                                <!-- Stupid HTML added strang withspaces :/ So we do the php -force way -->
                                <?php
                                $html = $html2 = '';
                                $internalMethod = 'submit';
                                $commandKey = 'command';
                                $commandValue = $commandName;
                                ?>
                                <?php
                                foreach ($parameters as $paramName => $paramValue):
                                    if ($paramName === 'internalMethod') {
                                        $internalMethod = $paramValue;
                                        continue;
                                    }
                                    if ($paramName === 'cmdType') {
                                        $commandKey = $paramName;
                                        $commandValue = is_null($paramValue) ? '$required' : $paramValue;
                                        continue;
                                    }
                                    if ($paramValue === null):
                                        $html .= '<span class="text-primary">'.$paramName.'</span>:<span class="txt-color-magenta">$required</span>/';
                                    endif;
                                    $html2 .= '<span class="text-primary">'.$paramName.'</span>:<span class="txt-color-magenta">'.(is_null($paramValue) ? '$required' : $paramValue).'</span>/';
                                endforeach;
                                $prehtml = 'https://'.h($_SERVER['SERVER_ADDR']).'/nagios_module/cmd/'.$internalMethod.'/<span class="text-primary">'.$commandKey.'</span>:<span class="txt-color-magenta">'.$commandValue.'</span>/';
                                ?>
                                <code>
                                    <span class="txt-color-blueDark"><?php echo $prehtml.$html ?></span>.json?<span class="text-primary">apikey=</span><span class="txt-color-orange">USER_API_KEY</span>
                                </code>
                            </div>
                            <br/>
                            <strong><?php echo __('Example HTTP request without default parameters'); ?>:</strong>
                            <div class="well">
                                <!-- Stupid HTML added strang withspaces :/ So we do the php -force way -->
                                <code>
                                    <span class="txt-color-blueDark"><?php echo $prehtml.$html2 ?></span>.json?<span class="text-primary">apikey=</span><span class="txt-color-orange">USER_API_KEY</span>
                                </code>
                            </div>
                        </div>
                    <?php endforeach; ?>

                    <br />
                    <div class="row">
                        <div class="col-xs-12">
                            <i class="fa fa-info-circle text-info"></i>
                            <?php echo __('You need to create a user defined API key.'); ?>

                            <a href="javascript:void(0);" data-toggle="modal" data-target="#ApiKeyOverviewModal">
                                <?php echo __('Click here for help'); ?>
                            </a>
                        </div>
                    </div>
                    <?php echo $this->element('apikey_help'); ?>

                </div>
            </div>
        </div>
    </div>
</div>
