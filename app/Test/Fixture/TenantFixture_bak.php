<?php

class TenantFixture extends CakeTestFixture {
    public $import = [
        'model'      => 'Tenant',
        'records'    => true,
        'connection' => 'test'
    ];
}