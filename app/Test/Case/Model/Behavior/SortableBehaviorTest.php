<?php
App::uses('SortableBehavior', 'Model/Behavior');

/**
 * SortableBehavior Test Case
 *
 */
class SortableBehaviorTest extends CakeTestCase {

/**
 * @var array
 */
	public $fixtures = array(
		'app.sorting'
	);

/**
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->SortingModel = ClassRegistry::init('Sorting');
	}

/**
 * @return void
 */	
	public function testInitialSort() {
		$this->SortingModel->Behaviors->attach('Sortable');

		$this->__createRecords(3);
		$this->SortingModel->restoreSorting();

		$sortings = $this->SortingModel->find('all', array(
			'order' => 'sort ASC'
		));
		$this->assertEqual($sortings[0]['Sorting']['sort'], 1);
		$this->assertEqual($sortings[1]['Sorting']['sort'], 2);
		$this->assertEqual($sortings[2]['Sorting']['sort'], 3);
	}

/**
 * Tests inserting a record with an existing position
 *
 * @return void
 */	
	public function testInsertRecordWithExistingPosition() {
		$this->SortingModel->Behaviors->attach('Sortable');
		
		$this->__createRecords(3, true);
		
		$this->SortingModel->create();
		$this->SortingModel->save(array(
			'name' => 'insertedrecord',
			'sort' => 2
		));
		
		$sortings = $this->SortingModel->find('all', array(
			'order' => 'sort ASC'
		));

		$this->assertEqual(count($sortings), 4);
		$this->assertEqual($sortings[0]['Sorting']['sort'], 1);
		$this->assertEqual($sortings[1]['Sorting']['sort'], 2);
		$this->assertEqual($sortings[2]['Sorting']['sort'], 3);
		$this->assertEqual($sortings[3]['Sorting']['sort'], 4);
		
		$this->assertEqual($sortings[1]['Sorting']['name'], 'insertedrecord');
	}

/**
 * Tests inserting a record with higher position
 *
 * @return void
 */	
	public function testInsertRecordWithHigherPosition() {
		$this->SortingModel->Behaviors->attach('Sortable');
	
		$this->__createRecords(3, true);
	
		$this->SortingModel->create();
		$this->SortingModel->save(array(
			'name' => 'insertedrecord',
			'sort' => 4
		));
	
		$sortings = $this->SortingModel->find('all', array(
			'order' => 'sort ASC'
		));

		$this->assertEqual(count($sortings), 4);
		$this->assertEqual($sortings[0]['Sorting']['sort'], 1);
		$this->assertEqual($sortings[1]['Sorting']['sort'], 2);
		$this->assertEqual($sortings[2]['Sorting']['sort'], 3);
		$this->assertEqual($sortings[3]['Sorting']['sort'], 4);
	
		$this->assertEqual($sortings[3]['Sorting']['name'], 'insertedrecord');
	}

/**
 * Tests inserting a record without a position. Expected behavior is to give 
 * it the highest sort position
 *
 * @return void
 */	
	public function testInsertRecordWithoutPosition() {
		$this->SortingModel->Behaviors->attach('Sortable');

		$this->__createRecords(3, true);

		$this->SortingModel->create();
		$this->SortingModel->save(array(
			'name' => 'insertedrecord',
			'sort' => null
		));

		$sortings = $this->SortingModel->find('all', array(
			'order' => 'sort ASC'
		));

		$this->assertEqual(count($sortings), 4);
		$this->assertEqual($sortings[0]['Sorting']['sort'], 1);
		$this->assertEqual($sortings[1]['Sorting']['sort'], 2);
		$this->assertEqual($sortings[2]['Sorting']['sort'], 3);
		$this->assertEqual($sortings[3]['Sorting']['sort'], 4);

		$this->assertEqual($sortings[3]['Sorting']['name'], 'insertedrecord');
	}


/**
 * Creates $count records in the sorting table
 *
 * @param int $count 
 * @param bool $restore 	Whether to call restoreSorting() after creating the records
 * @return void
 */	
	private function __createRecords($count, $restore = false) {
		for($i = 0; $i < $count; $i++) {
			$this->SortingModel->create();
			$this->SortingModel->save(array(
				'name' => 'sorting ' . $i
			), array(
				'callbacks' => false // prevent SortableBehavior 
			));
		}
		if($restore) {
			$this->SortingModel->restoreSorting();
		}
	}

/**
 * @return void
 */
	public function tearDown() {
		unset($this->SortingModel);
		parent::tearDown();
	}
}