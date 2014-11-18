<?php

class Benchmark {
  /**
   * The number of iterations to perform.
   * @type integer
   */
  public $iterations = 10;

  /**
   * The results from the benchmark run.
   * @type array
   */
  public $results = array();

  /**
   * The longest array key value. This is purely for formatting.
   * @type integer
   */
  public $longest_value = 10;

  /**
   * Create a report of the method being profiled.
   *
   * @param string $name
   *   The identifer (or name) for the report.
   * @param string $function
   *   Callback method to benchmark.
   */
  public function report($name, $function) {
    // Ensure that the method is callable before running through the report.
    if (!is_callable($function)) {
      echo "Method '$function' was not found. Skipping..." . PHP_EOL;
      return;
    }

    $iterations = $this->iterations;
    $start_time = microtime(TRUE);

    // Run through the iterations ignoring any output - we don't need to be
    // outputting anything from here.
    for ($i = 0; $i < $iterations; $i++) {
      ob_start();
      $function();
      ob_end_clean();
    }

    $end_time = microtime(TRUE);

    // Set the $longest_value to the largest key in the array for pretty
    // formatting.
    if (strlen($name) > $this->longest_value) {
      $this->longest_value = strlen($name);
    }

    // Tack on the results to the existing array.
    $this->results += array(
      $name => number_format(($end_time) - ($start_time), 8),
    );
  }

  /**
   * Set the iterations for the benchmark.
   *
   * @param integer $iterations
   *   The number of iterations to perform on your code block.
   */
  public function setIterations($iterations) {
    $this->iterations = $iterations;
  }

  /**
   * Run the benchmark and output the results.
   */
  public function bench() {
    $results = $this->results;
    $longest_value = $this->longest_value + 3;

    // Format the output to be aligned correctly.
    $mask = "%-{$longest_value}s %s\n";
    printf($mask, 'IDENTIFIER', 'EXECUTION TIME');

    foreach ($results as $key => $value) {
      printf($mask, $key, "{$value}ms");
    }
  }
}
