<?php
class UserRights {

	/**
	 * Holds the rights config.
	 * 
	 * Format: 
	 * 		'userCanDoStuff' => array(
	 * 			role1, role2
	 * 		),
	 * 		'userCanDoOtherStuff' => array(
	 * 			role3
	 * 		)
	 *
	 * @var string
	 */
	protected $_rightsConfig = array();

	/**
	 * Constructor
	 *
	 * @param array $rightsConfig	The rights configuration 
	 */
	public function __construct(array $rightsConfig) {
		$this->_rightsConfig = $rightsConfig;
	}

	/**
	 * Checks if the given user has a right
	 *
	 * @param array $user 
	 * @param string $right 
	 * @return bool
	 * @author Robert Scherer
	 */
	public function userHasRight(array $user, $right) {
		$hasRight = false;
		if(isset($user['role']) && !empty($right) && isset($this->_rightsConfig[ $right ])) {
			if(in_array($user['role'], $this->_rightsConfig[ $right ])) {
				$hasRight = true;
			}
		}
		return $hasRight;
	}
}