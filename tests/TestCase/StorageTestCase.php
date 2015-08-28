<?php
namespace Burzum\StorageFactory\Test\TestCase;

use PHPUnit_Framework_TestCase;
use Burzum\StorageFactory\StorageFactory;

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

        StorageFactory::config('Local', array(
            'adapterOptions' => [$this->testPath, true],
            'adapterClass' => '\Gaufrette\Adapter\Local',
            'class' => '\Gaufrette\Filesystem'
        ));

        StorageFactory::config('LocalFlysystem', array(
            'adapterOptions' => [$this->testPath],
            'engine' => StorageFactory::FLYSYSTEM_ENGINE,
            'adapterClass' => 'Local',
        ));
    }

    public function testSomething() {}
}
