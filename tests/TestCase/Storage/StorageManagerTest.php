<?php
/**
 * StorageFactoryTest
 *
 * @author Florian Krämer
 * @copyright 2012 - 2015 Florian Krämer
 * @license MIT
 */
namespace Burzum\StorageFactory\Test\TestCase\Storage;

use Burzum\StorageFactory\Test\TestCase\StorageTestCase;
use Burzum\StorageFactory\StorageFactory;

class StorageFactoryTest extends StorageTestCase {

/**
 * testAdapter
 *
 * @return void
 */
    public function testAdapter()
    {
        $result = StorageFactory::get('Local');
        $this->assertEquals(get_class($result), 'Gaufrette\Filesystem');

        $result = StorageFactory::get('LocalFlysystem');
        $this->assertEquals(get_class($result), 'League\Flysystem\Adapter\Local');

        try {
            StorageFactory::get('Does Not Exist');
            $this->fail('Exception not thrown!');
        } catch (\RuntimeException $e) {}
    }

/**
 * testConfig
 *
 * @return void
 */
    public function testConfig()
    {
        $result = StorageFactory::config('Local');
        $expected = [
            'adapterOptions' => [
                0 => $this->testPath,
                1 => true
            ],
            'adapterClass' => '\Gaufrette\Adapter\Local',
            'class' => '\Gaufrette\Filesystem'
        ];
        $this->assertEquals($result, $expected);
        $this->assertFalse(StorageFactory::config('Does not exist'));
    }

/**
 * testFlush
 *
 * @return void
 */
    public function testFlush()
    {
        $config = StorageFactory::config('Local');
        $result  = StorageFactory::flush('Local');
        $this->assertTrue($result);
        $result  = StorageFactory::flush('Does not exist');
        $this->assertFalse($result);
        StorageFactory::config('Local', $config);
    }
}
