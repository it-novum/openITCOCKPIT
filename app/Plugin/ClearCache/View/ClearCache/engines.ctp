<?php
/**
 * Toolbar engines view
 * PHP 5
 * Copyright 2010-2012, Marc Ypes, The Netherlands
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 * @copyright     2010-2012 Marc Ypes, The Netherlands
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 * @package       ClearCache.View.ClearCache
 */
?>
<table class="debug-table"><?php
    $headers = [__d('clear_cache', 'Engine Name'), __d('clear_cache', 'Result')];
    echo $this->Html->tableHeaders($headers);
    echo $this->Html->tableCells($engines);
    ?></table>
