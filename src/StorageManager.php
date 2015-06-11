<?php
namespace Burzum\Storage;

/**
 * StorageManager - Manages and instantiates storage engine adapters.
 *
 * @author Florian Krämer
 * @copyright 2012 - 2015 Florian Krämer
 * @license MIT
 */
class StorageManager {

/**
 * Adapter configs
 *
 * @var array
 */
    protected $_adapterConfig = [];

/**
 * Supported storage engines / libraries
 */
    const GAUFRETTE_ENGINE = 'Gaufrette';
    const FLYSYSTEM_ENGINE = 'Flysystem';

/**
 * Default engine
 *
 * @var string
 */
    public static $defaultEngine = 'Gaufrette';

/**
 * Return a singleton instance of the StorageManager.
 *
 * @return ClassRegistry instance
 */
    public static function &getInstance()
    {
        static $instance = [];
        if (!$instance) {
            $instance[0] = new StorageManager();
        }
        return $instance[0];
    }

/**
 * Gets the configuration array for an adapter.
 *
 * @param string $adapter Configuration name under which the config is stored.
 * @param array $options Adapter configuration.
 * @return mixed
 */
    public static function config($adapter, array $options = [])
    {
        $_this = StorageManager::getInstance();

        if (!empty($options)) {
            return $_this->_adapterConfig[$adapter] = $options;
        }

        if (isset($_this->_adapterConfig[$adapter])) {
            return $_this->_adapterConfig[$adapter];
        }

        return false;
    }

/**
 * Flush all or a single adapter from the config.
 *
 * @param string $name Config name, if none all adapters are flushed.
 * @throws RuntimeException
 * @return boolean True on success
 */
    public static function flush($name = null)
    {
        $_this = StorageManager::getInstance();

        if (!is_null($name)) {
            if (isset($_this->_adapterConfig[$name])) {
                unset($_this->_adapterConfig[$name]);
                return true;
            }
            return false;
        }

        $_this->_adapterConfig = [];
        return true;
    }

/**
 * Gets an adapter or it's config from the adapter store.
 *
 * @param string $adapterName Name of the adapter config.
 * @throws \RuntimeException If no adapter config was found.
 * @return mixed
 */
    protected static function _getAdapter($adapterName)
    {
        $_this = StorageManager::getInstance();

        if (!empty($_this->_adapterConfig[$adapterName]['object'])) {
            return $_this->_adapterConfig[$adapterName]['object'];
        }

        if (!empty($_this->_adapterConfig[$adapterName])) {
            return $_this->_adapterConfig[$adapterName];
        }

        throw new \RuntimeException(sprintf('Invalid Storage Adapter %s!', $adapterName));
    }

/**
 * Get a storage adapter.
 *
 * If a string is passed it tries to get the instance based on the previous set
 * configuration. The object is stored internally and can be returned at any time
 * again by calling this method with the same adapter name.
 *
 * If an array is passed a new adapter object is instantiated and returned. The
 * created object is NOT stored internally!
 *
 * @param mixed $adapterName string of adapter configuration or array of settings
 * @param boolean $renewObject Creates a new instance of the given adapter in the configuration
 * @throws RuntimeException
 * @return Gaufrette object as configured by first argument
 */
    public static function adapter($adapterName, $renewObject = false)
    {
        if (is_string($adapterName)) {
            $adapter = self::_getAdapter($adapterName);
            if (is_object($adapter) && $renewObject === false) {
                return $adapterName;
            }
        }

        $fromConfigStore = true;
        if (is_array($adapterName)) {
            $adapter = $adapterName;
            $fromConfigStore = false;
            if (empty($adapter['adapterClass'])) {
                throw \RuntimeException('No adapter class specified!');
            }
        }

        if (isset($adapter['adapterOptions']) && !is_array($adapter['adapterOptions'])) {
            throw new \InvalidArgumentException(sprintf('The adapter options must be an array!'));
        }
        if (!isset($adapter['adapterOptions'])) {
            $adapter['adapterOptions'] = [];
        }

        if (empty($adapter['engine'])) {
            $adapter['engine'] = self::$defaultEngine;
        }
        if ($adapter['engine'] === self::GAUFRETTE_ENGINE) {
            $object = self::gaufretteFactory($adapter);
        }
        if ($adapter['engine'] === self::FLYSYSTEM_ENGINE) {
            $object = self::flysystemFactory($adapter);
        }

        if (isset($object)) {
            if ($fromConfigStore) {
                $_this = StorageManager::getInstance();
                $_this->_adapterConfig[$adapterName]['object'] = &$object;
            }
            return $object;
        }

        throw new \RuntimeException(sprintf('Invalid engine %s!', $adapter['engine']));
    }

    /**
     * Instantiates Gaufrette adapters.
     *
     * @param array $adapter
     * @return object
     */
    public static function gaufretteFactory(array $adapter)
    {
        if (!class_exists($adapter['adapterClass'])) {
            throw new \RuntimeException(sprintf('Adapter class %s does not exist!', $adapter['adapterClass']));
        }
        $Reflection = new \ReflectionClass($adapter['adapterClass']);
        $adapterObject = $Reflection->newInstanceArgs($adapter['adapterOptions']);
        return new $adapter['class']($adapterObject);
    }

    /**
     * Instantiates Flystem adapters.
     *
     * @param array $adapter
     * @return object
     */
    public static function flysystemFactory(array $adapter)
    {
        if (class_exists($adapter['adapterClass'])) {
            return (new \ReflectionClass($adapter['adapterClass']))->newInstanceArgs($adapter['adapterOptions']);
        }
        $leagueAdapter = '\\League\\Flysystem\\Adapter\\' . $adapter['adapterClass'];
        if (class_exists($leagueAdapter)) {
            return (new \ReflectionClass($leagueAdapter))->newInstanceArgs($adapter['adapterOptions']);
        }
        $leagueAdapter = '\\League\\Flysystem\\' . $adapter['adapterClass'] . '\\' . $adapter['adapterClass'] . 'Adapter';
        if (class_exists($leagueAdapter)) {
            return (new \ReflectionClass($leagueAdapter))->newInstanceArgs($adapter['adapterOptions']);
        }
        throw new \InvalidArgumentException('Unknown adapter');
    }
}
