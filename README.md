Storage Factory Library for Flysystem and Gaufrette
===================================================

[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.txt) 
[![Build Status](https://img.shields.io/travis/burzum/storage/3.0.svg?style=flat-square)](https://travis-ci.org/burzum/storage) 
[![Coverage Status](https://img.shields.io/coveralls/burzum/storage/3.0.svg?style=flat-square)](https://coveralls.io/r/burzum/storage)

Requirements
------------

 * PHP 5.4+

At least one of both:

 * Gaufrette Library
 * Flysystem Library

How to use it
-------------

Configure the adapter instances:

```php
$basePath = '/your/base/path';
StorageManager::config('LocalGaufrette', array(
	'adapterOptions' => [$basePath, true],
	'adapterClass' => '\Gaufrette\Adapter\Local',
	'class' => '\Gaufrette\Filesystem'
));
StorageManager::config('LocalFlysystem', array(
	'adapterOptions' => [$basePath],
	'engine' => StorageManager::FLYSYSTEM_ENGINE,
	'adapterClass' => 'Local',
));
```

And get instances of the adapters as you need them.

```php
$flysystemLocalFSAdapter = StorageManager::adapter('LocalGaufrette');
$gaufretteLocalFSAdapter = StorageManager::adapter('LocalFlysystem');
```

Flush or renews adapter objects:

```php
// Flushes a specific adapter
StorageManager::flush('LocalGaufrette');
// Flushes ALL adapters
StorageManager::flush();

// Renews an adapter, set second arg to true
StorageManager::adapter('LocalGaufrette', true);
```

Support
-------

For bugs and feature requests, please use the [issues](https://github.com/burzum/storage/issues) section of this repository.

Contributing
------------

To contribute to this repository please follow a few basic rules.

* Pull requests must be send to the ```develop``` branch.
* Contributions must follow the [PSR2 coding standard recommendation](https://github.com/php-fig).
* [Unit tests](https://phpunit.de/) are required.

License
-------

Copyright 2012 - 2015, Florian Krämer

Licensed under The MIT License
Redistributions of files must retain the above copyright notice.
