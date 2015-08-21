<?php

App::uses('AdditionalLinksComponent', 'Controller/Component');


class AdditionalLinksComponentTest extends CakeTestCase{
	public static $additionalLinks = [];
	public static $additionalLinksComponent = null;

	public function testFetchLinkDataOutput(){
		$actual = self::$additionalLinksComponent->fetchLinkData('hosts', 'index', 'top');
		$expected = [
				[
					'url' => 'http://www.example.com',
					'options' => [
						'class' => 'btn btn-xs btn-primary',
						'target' => '_blank',
						'escapeTitle' => false,
					],
					'confirmMessage' => false,
					'title' => '<i class="fa fa-cog"></i> Custom Button',
				]
			];
		$this->assertEquals($actual, $expected);
		
		$actual = self::$additionalLinksComponent->fetchLinkData('hosts', 'index', 'list');
		$expected = [
				[
					'url' => [],
					'options' => [],
					'confirmMessage' => false,
					'title' => 'Additional Link 1',
				],
				[
					'url' => [
						'controller' => 'hosts',
						'action' => 'index',
					],
					'options' => [],
					'confirmMessage' => false,
					'title' => 'Additional Link 2',
				],
				[
					'url' => [
						'controller' => 'custom_hosts',
						'action' => 'edit',
						'autoIndex',
						50,
					],
					'options' => [],
					'confirmMessage' => false,
					'title' => 'Additional Link 3 - With Index',
				],
			];
		$this->assertEquals($actual, $expected);
	}

	/**
	 * Is called once before the test methods in a case are started.
	 *
	 * Must be static.
	 */
	public static function setupBeforeClass(){
		$Collection = new ComponentCollection();
		
		self::$additionalLinksComponent = new AdditionalLinksComponent($Collection);

		// Testdata
		self::$additionalLinksComponent->additionalLinks = [
			[
				'positioning' => [
					'controller' => 'hosts',
					'action' => 'index',
					'viewPosition' => 'list', // The position within the controller/action
					'sorting' => 9999, // Sorting value
				],
				'link' => [
					'title' => 'Additional Link 1',
					'url' => [],
				],
			], [
				'positioning' => [
					'controller' => 'hosts',
					'action' => 'index',
					'viewPosition' => 'list', // The position within the controller/action
					'sorting' => 1000, // Sorting value
				],
				'link' => [
					'title' => 'Additional Link 2',
					'url' => [
						'controller' => 'hosts',
						'action' => 'index',
					],
				],
			], [
				'positioning' => [
					'controller' => 'hosts',
					'action' => 'index',
					'viewPosition' => 'list', // The position within the controller/action
					'sorting' => 2000, // Sorting value
				],
				'link' => [
					'title' => 'Additional Link 3 - With Index',
					// Borrowed from MySQL: 'auto_increment' gets 'autoIndex' here
					// 'autoIndex' will be automatically replaced in list context by the right
					// index of the entry
					'url' => [
						'controller' => 'custom_hosts',
						'action' => 'edit',
						'autoIndex',
						50, // fixed value for testing purposes, shouldn't get changed!
					],
				],
			], [
				'positioning' => [
					'controller' => 'hosts',
					'action' => 'index',
					'viewPosition' => 'top', // The position within the controller/action
					'sorting' => 1000, // Sorting value
				],
				'link' => [
					'title' => '<i class="fa fa-cog"></i> ' . h(__('Custom Button')),
					'options' => [
						'class' => 'btn btn-xs btn-primary',
						'target' => '_blank',
						'escapeTitle' => false,
					],
					'url' => 'http://www.example.com',
				],
			],
		];
	}

	/**
	 * Is called before _every_ test method.
	 */
	public function setUp(){
		parent::setUp();
	}

	/**
	 * Is called after _every_ test method.
	 */
	public function tearDown(){
		parent::tearDown();
	}

	/**
	 * Is called once After the test methods in a case are started.
	 *
	 * Must be static.
	 */
	public static function setupAfterClass(){
		parent::setupAfterClass();
	}
}
