<?php

App::uses('AppModel', 'Model');

class CrateModuleAppModel extends AppModel {

    /**
     * @return mixed
     */
    public function getPartitions(){
        return $this->getDataSource()->getPartitions($this);
    }

    /**
     * @param int $key
     * @return mixed
     */
    public function dropPartition($key){
        return $this->getDataSource()->dropPartition($this, $key);
    }

}
