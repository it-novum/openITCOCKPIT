<div id="flash-<?php echo h($key) ?>" class="alert alert-danger">
    <?php echo h($message) ?>:
    <br>
    <?php
    /*
       see Host.php:__allowDelete()
       Requires this structure:
       array(
            'Host' => array(
                'AUTOREPORT' => [
                        0 => 1,
                        1 => 2,
                        3 => 43,
                        4 => 52,
                    ]
                    'EVENTCORRELATION' => [
                        0 => 4,
                        1 => 2,
                        3 => 63
                    ]
            ),
            'Service' => array(
                    'AUTOREPORT' => [
                        0 => 1,
                        1 => 2,
                        3 => 43,
                        4 => 52,
                    ]
                    'EVENTCORRELATION' => [
                        0 => 4,
                        1 => 2,
                        3 => 63
                    ]

            )
        )
     */


    if (isset($params['usedBy']) && !empty($params['usedBy'])): ?>
        <?php
        if (isset($params['usedBy']['Host']) && !empty($params['usedBy']['Host'])): ?>
            <span><?php echo __('Hosts are in use by the following modules'); ?></span>
            <?php foreach ($params['usedBy']['Host'] as $moduleName => $hosts):
                //hosts
                $link = Router::url(array_merge([
                    'controller' => Inflector::pluralize(strtolower($moduleName)),
                    'action'     => 'hostUsedBy',
                    'plugin'     => strtolower($moduleName) . '_module',
                ],
                    $hosts
                ));
                ?>
                <ul>
                    <li>
                        <a href="<?php echo $link; ?>"><?php echo h($moduleName); ?></a>
                    </li>
                </ul>
            <?php
            endforeach;
        endif; ?>

        <?php
        if (isset($params['usedBy']['Service']) && !empty($params['usedBy']['Service'])): ?>
            <span><?php echo __('The Services are in use by the following modules'); ?></span>
            <?php foreach ($params['usedBy']['Service'] as $moduleName => $services):
                //services
                $link = Router::url(array_merge([
                    'controller' => Inflector::pluralize(strtolower($moduleName)),
                    'action'     => 'serviceUsedBy',
                    'plugin'     => strtolower($moduleName) . '_module',
                ],
                    $services
                ));
                ?>
                <ul>
                    <li>
                        <a href="<?php echo $link; ?>"><?php echo h($moduleName); ?></a>
                    </li>
                </ul>
            <?php
            endforeach;
        endif;
    endif;
    ?>
</div>
