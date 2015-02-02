# PHP Benchmark - The easy way

This is designed to make benchmarking PHP easy.

### Installation

```
git clone git@github.com:jacobbednarz/php-bench.git
```

### Usage

To run the benchmark, you need to include the class into a file and instantiate the class.

```php
<?php

include_once 'path/to/benchmark.php';

$b = new Benchmark;
```

After that, you need to add the 'reports' and the methods you wish to benchmark.

```php
<?php

include_once 'path/to/benchmark.php';

// Here are the methods we want to benchmark against each other.
function my_method() {
  return "a" . "b";
}

function my_method2() {
  return "a" + "b";
}

$b = new Benchmark;
$b->report('foo', 'my_method');
$b->report('foo2', 'my_method2');
$b->bench();
```

Expected output:

```
$ php benchmark.php

IDENTIFIER    EXECUTION TIME    MEMORY USAGE
foo           0.00003099ms      128b
foo2          0.00000906ms      128b
```

### Set the iterations

If you need to perform more iterations than the default (10), you can use `setIterations()` to set the value.

```php
<?php

include_once 'path/to/benchmark.php';

// Here are the methods we want to benchmark against each other.
function my_method() {
  return "a" . "b";
}

function my_method2() {
  return "a" + "b";
}

$b = new Benchmark;
$b->setIterations(1000);
$b->report('foo', 'my_method');
$b->report('foo2', 'my_method2');
$b->bench();
```
