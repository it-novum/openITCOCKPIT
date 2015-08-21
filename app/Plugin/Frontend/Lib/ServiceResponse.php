<?php
/**
 * Custom CakeResponse for Service calls
 *
 * @package default
 */
class ServiceResponse extends CakeResponse {
/**
 * Constructor
 *
 * @param string $code 	One of Types::CODE_*, or an array containing 'code' and 'data' keys
 * @param array $data 	data to return
 */
	public function __construct($code, array $data = array()) {
		if(is_array($code)) {
			$body = Set::merge(array(
				'code' => Types::CODE_SUCCESS,
				'data' => array()
			), $code);
		} else {
			$body = array(
				'code' => $code,
				'data' => $data
			);
		}
		$options = array(
			'type' => 'json',
			'body' => json_encode($body)
		);
		parent::__construct($options);
	}
}