PHPCR Benchmarks
================

Benchmarking suite for PHPCR which uses the same bootstrapping process as the
[PHPCR-API-tests](https://github.com/phpcr/phpcr-api-tests).

Usage
-----

The benchmarking suite uses the existing `phpunit.xml` configuration. Ensure that the PHPCR-API tests are working.

Include as a dev requirement:

````bash
$ composer require "phpcr/phpcr-benchmarks"
````

Run:

````bash
$ ./vendor/bin/phpbench run --config=vendor/phpcr/phpcr-benchmarks/config/phpbench.json
````

For more information see the documentation for [PHPBench](https://github.com/phpbench/phpbench).
