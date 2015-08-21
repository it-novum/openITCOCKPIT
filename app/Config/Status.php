<?php
class Status {
	const ACTIVE	= 1;
	const INACTIVE	= 2;
	const DELETED	= 3;
	const SUSPENDED = 4;
	const ACTIVATION_PENDING = 11;
	const DONE		= 5;
	const CANCELLED = 6;

	public static $description = array(
		self::ACTIVE 		=> 'active',
		self::INACTIVE 		=> 'inactive',
		self::DELETED		=> 'deleted',
		self::SUSPENDED		=> 'suspended',
		self::ACTIVATION_PENDING => 'activation pending',
		self::DONE			=> 'done',
		self::CANCELLED		=> 'cancelled'
	);
	
	public static function getMap($states = null) {
		if(!is_array($states)) {
			$states = func_get_args();
		}
		$group = array();
		if($states == null) $states = array_keys(self::$description);
		foreach ($states as $state) {
			$group[$state] = self::getDescription($state);
		}
		return $group;
	}	
	
	public static function getDescription($status) {
		if(isset(self::$description[ $status ])) {
			return self::$description[ $status ];
		}
		return null;
	}
}