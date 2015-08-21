<?php
/**
 * Toolbar groups view
 *
 * PHP 5
 *
 * Copyright 2010-2012, Marc Ypes, The Netherlands
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     2010-2012 Marc Ypes, The Netherlands
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 * @package       ClearCache.View.ClearCache
 */
?>
<table class="debug-table"><?php
	$headers = array(__d('clear_cache', 'Group Name'), __d('clear_cache', 'Cleared Engines'), __d('clear_cache', 'Failed Engines'));
	echo $this->Html->tableHeaders($headers);
	echo $this->Html->tableCells($groups);
?></table>
