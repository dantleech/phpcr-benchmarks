PHPCR Benchmarks
================

Benchmarking suite for PHPCR which uses the same bootstrapping process as the
[PHPCR-API-tests](https://github.com/phpcr/phpcr-api-tests).

Usage
-----

Ensure that the PHPCR-API tests are working.

Include as a dev requirement:

````bash
$ composer require "phpcr/phpcr-benchmarks"
````

Run:

````bash
$ ./vendor/bin/phpbench ./vendor/phpcr/phpcr-benchmarks/benchmarks
````

With detailed report:

````bash
$ ./vendor/bin/phpbench ./vendor/phpcr/phpcr-benchmarks/benchmarks \
    --report={"name": "console_table", "memory": true}
````

With a filter:

````bash
$ ./vendor/bin/phpbench ./vendor/phpcr/phpcr-benchmarks/benchmarks \
    --report=console_table
    --filter=benchInsert
````

For more information see the documentation for [PHPBench](https://github.com/dantleech/phpbench).

