<?php

App::uses('AppModel', 'Model');

class CrateModuleAppModel extends AppModel {

    /**
     * @return mixed
     */
    public function getPartitions(){
        return $this->getDataSource()->getPartitions($this);
    }

}
