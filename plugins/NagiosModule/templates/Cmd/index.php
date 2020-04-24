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

/**
 * @var \App\View\AppView $this
 * @var array $externalCommands
 */

?>
<ol class="breadcrumb page-breadcrumb">
    <li class="breadcrumb-item">
        <a ui-sref="DashboardsIndex">
            <i class="fa fa-home"></i> <?php echo __('Home'); ?>
        </a>
    </li>
    <li class="breadcrumb-item">
        <a ui-sref="ExternalCommandsIndex">
            <i class="fa fa-code"></i> <?php echo __('Monitoring Engine'); ?>
        </a>
    </li>
    <li class="breadcrumb-item">
        <i class="fa fa-code"></i> <?php echo __('External Command'); ?>
    </li>
</ol>

<div class="row">
    <div class="col-xl-12">
        <div id="panel-1" class="panel">
            <div class="panel-hdr">
                <h2>
                    <?php echo __('External commands'); ?>
                    <span class="fw-300"><i><?php echo __('overview'); ?></i></span>
                </h2>
            </div>
            <div class="panel-container show">
                <div class="panel-content">

                    <div class="row">
                        <div class="col-lg-12">
                            <div class="alert alert-info alert-block">
                                <a class="close" data-dismiss="alert" href="javascript:void(0);">×</a>
                                <h4 class="alert-heading"><?php echo __('What is this API for?'); ?></h4>
                                <?php echo __('This API can be used by third party application to send commands to the monitoring backend.'); ?>
                                <br/>
                                <?php echo __('This could be useful to transfer passive check results or to acknowledge host or service states.'); ?>
                            </div>
                        </div>
                    </div>

                    <div class="form-group required">
                        <label class="control-label" for="commandType">
                            <?php echo __('Command type'); ?>
                        </label>
                        <select
                            id="commandType"
                            data-placeholder="<?php echo __('Please choose'); ?>"
                            class="form-control"
                            chosen=""
                            ng-init="selectedCommand='<?= h(array_keys($externalCommands)[0]) ?>'"
                            ng-model="selectedCommand">
                            <?php
                            foreach ($externalCommands as $key => $args) :
                                printf('<option value="%s">%s</option>', h($key), h($key));
                            endforeach;
                            ?>
                        </select>
                    </div>

                    <?php foreach ($externalCommands as $key => $args) : ?>
                        <div class="row padding-top-10" ng-show="'<?= h($key) ?>' === selectedCommand">
                            <div class="col-lg-3">
                                <div class="code-font">
                                    <table class="table table-striped m-0 table-bordered table-hover table-sm">
                                        <thead>
                                        <tr>
                                            <th><?= __('Parameter'); ?></th>
                                            <th><?= __('Default value'); ?></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php foreach ($args as $argKey => $argValue): ?>
                                            <tr>
                                                <td class="text-align-left">
                                                    <?= h($argKey) ?>
                                                </td>
                                                <td>
                                                    <?php if ($argValue === null): ?>
                                                        <code><i><?= __('required') ?></i></code>
                                                    <?php else: ?>
                                                        <code><?= h($argValue) ?></code>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>

                                        <?php if (empty($args)): ?>
                                            <tr>
                                                <td class="text-align-left text-info" colspan="2">
                                                    <i><?= __('No parameters'); ?></i>
                                                </td>
                                            </tr>
                                        <?php endif; ?>

                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="col-lg-9">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend" style="width: 100%;">
                                                <span class="input-group-text bg-downtime txt-color-white"
                                                      style="width: 70px;">
                                                    GET
                                                </span>
                                                <?php
                                                $dest = 'submit';
                                                if ($key === 'ACKNOWLEDGE_OTRS_HOST_SVC_PROBLEM'):
                                                    $dest = 'ack';
                                                endif;

                                                $urlArgs = [];
                                                foreach ($args as $argKey => $argValue):
                                                    if ($argValue === null):
                                                        $urlArgs[] = sprintf('%s=<%s>', $argKey, $argKey);
                                                    else:
                                                        $urlArgs[] = sprintf('%s=%s', $argKey, $argValue);
                                                    endif;
                                                endforeach;

                                                if (!empty($urlArgs)):
                                                    $getUrl = sprintf(
                                                        'https://%s/nagios_module/cmd/%s.json?command=%s&%s&apikey=%s',
                                                        h($_SERVER['SERVER_ADDR']),
                                                        $dest,
                                                        $key,
                                                        implode('&', $urlArgs),
                                                        __('YOUR_API_KEY_HERE')
                                                    );
                                                else:
                                                    $getUrl = sprintf(
                                                        'https://%s/nagios_module/cmd/%s.json?command=%s&apikey=%s',
                                                        h($_SERVER['SERVER_ADDR']),
                                                        $dest,
                                                        $key,
                                                        __('YOUR_API_KEY_HERE')
                                                    );
                                                endif;
                                                ?>

                                                <input
                                                    type="text"
                                                    class="form-control"
                                                    readonly="readonly"
                                                    value="<?= $getUrl ?>">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-12 padding-top-10">
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend" style="width: 100%;">
                                                <span class="input-group-text bg-up txt-color-white"
                                                      style="width: 70px;">
                                                    POST
                                                </span>
                                                <?php
                                                $dest = 'submit';
                                                if ($key === 'ACKNOWLEDGE_OTRS_HOST_SVC_PROBLEM'):
                                                    $dest = 'ack';
                                                endif;

                                                $postUrl = sprintf(
                                                    'https://%s/nagios_module/cmd/%s.json?apikey=%s',
                                                    h($_SERVER['SERVER_ADDR']),
                                                    $dest,
                                                    __('YOUR_API_KEY_HERE')
                                                );

                                                $postJson = [
                                                    'command' => $key
                                                ];
                                                foreach ($args as $argKey => $argValue):
                                                    if ($argValue === null):
                                                        $postJson[$argKey] = '<' . $argKey . '>';
                                                    else:
                                                        $postJson[$argKey] = $argValue;
                                                    endif;
                                                endforeach;
                                                ?>

                                                <textarea class="form-control" readonly="readonly">curl -X POST -k -H "Content-Type: application/json" <?= $postUrl; ?> -d '<?= json_encode($postJson); ?>'</textarea>
                                            </div>
                                        </div>

                                        <div class="col-lg-12 help-block">
                                            <?php $help = __('You need to create an openITCOCKPIT user defined API key first.');
                                            $help .= '<a href="javascript:void(0);" data-toggle="modal" data-target="#ApiKeyOverviewModal"> ' . __('Click here for help') . '</a>';
                                            echo $help;
                                            ?>
                                            <br/>
                                            <code>.json</code>
                                            <?= __('is not required in the URL and can be removed.'); ?>
                                        </div>

                                        <?php if ($key !== 'ACKNOWLEDGE_OTRS_HOST_SVC_PROBLEM'): ?>
                                            <div class="col-lg-12 help-block">
                                                <a href="https://www.naemon.org/documentation/developer/externalcommands/<?= strtolower($key) ?>.html"
                                                   target="_blank">
                                                    <?= __('External documentation') ?>
                                                </a>
                                                <i class="fas fa-external-link-alt"></i>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php echo $this->element('apikey_help'); ?>
