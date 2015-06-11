<?php
namespace Burzum\Storage\Test\TestCase;

use PHPUnit_Framework_TestCase;
use Burzum\Storage\StorageManager;
use Burzum\Storage\FileStorageUtils;

/**
 * StorageTestCase
 *
 * @author Florian Krämer
 * @copyright 2012 - 2015 Florian Krämer
 * @license MIT
 */
class StorageTestCase extends PHPUnit_Framework_TestCase {

/**
 * Setup test folders and files
 *
 * @return void
 */
    public function setUp()
    {
        parent::setUp();

        $this->testPath = TMP;

        StorageManager::config('Local', array(
            'adapterOptions' => [$this->testPath, true],
            'adapterClass' => '\Gaufrette\Adapter\Local',
            'class' => '\Gaufrette\Filesystem'
        ));

        StorageManager::config('LocalFlysystem', array(
            'adapterOptions' => [$this->testPath],
            'engine' => StorageManager::FLYSYSTEM_ENGINE,
            'adapterClass' => 'Local',
        ));
    }
}
