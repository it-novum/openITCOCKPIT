<?php 
/**
 * Dev Helper Shell
 */
class UtilsShell extends AppShell {
	public $tasks = array(
		'BuildBootstrap', 
		'CompressAssets', 
		'GetLocaleStrings', 
		'ClearCache',
		'AppBake'
	);

	public function main() {
		if(isset($this->args[0]) && $this->args[0] == 'deploy') {
			$this->out('Running complete deployment preparation process');
			$this->BuildBootstrap->execute();
			$this->CompressAssets->execute();
			$this->ClearCache->execute();
		}
	}
}