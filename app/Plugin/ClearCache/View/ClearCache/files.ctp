<?php
/**
 * Toolbar files view
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
	$mask = empty($this->params['pass']) ? '.*' : implode(' ', $this->params['pass']);
	$headers = array(__d('clear_cache', 'File Name (%s)', $mask), __d('clear_cache', 'Result'));
	if (empty($files)):
		$files = array(array(__d('clear_cache', 'No files found.'), ''));
	endif;
	echo $this->Html->tableHeaders($headers);
	echo $this->Html->tableCells($files);
?></table>
