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
   * Format the output for memory usage information.
   *
   * @param int $size
   *   Memory consumption in bytes.
   *
   * @return string
   *   The human readable memory usage string.
   */
  public function format_memory_usage($size) {
    $unit = array('b', 'kb', 'mb', 'gb', 'tb', 'pb');
    return @round($size / pow(1024, ($i = floor(log($size, 1024)))), 10) . $unit[$i];
  }

  /**
   * Format the execution time output.
   *
   * @param float $time
   *   The time taken to execute returned from microtime().
   *
   * @return string
   *   The human friendly string of the execution time.
   */
  public function format_execution_time($time) {
    return number_format($time, 8);
  }

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
    $memory_start = memory_get_usage();

    // Run through the iterations ignoring any output - we don't need to be
    // outputting anything from here.
    for ($i = 0; $i < $iterations; $i++) {
      ob_start();
      $function();
      ob_end_clean();
    }

    $memory_end = memory_get_usage();
    $end_time = microtime(TRUE);

    // Set the $longest_value to the largest key in the array for pretty
    // formatting.
    if (strlen($name) > $this->longest_value) {
      $this->longest_value = strlen($name);
    }

    // Tack on the results to the existing array.
    $this->results += array(
      $name => array(
        $this->format_execution_time($end_time - $start_time),
        $this->format_memory_usage($memory_end - $memory_start),
      ),
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
    $headers = array('IDENTIFIER', 'EXECUTION TIME', 'MEMORY USAGE');
    $results = $this->results;

    echo "Using {$this->iterations} iterations\n\n";

    // To allow column based output, the longest values are tracked and then a
    // small buffer is added.
    $longest_value = $this->longest_value + 3;
    $execution_time_string_length = strlen($headers[1]) + 3;

    // Format the output to be aligned correctly.
    $mask = "%-{$longest_value}s %-{$execution_time_string_length}s %s\n";
    printf($mask, $headers[0], $headers[1], $headers[2]);

    foreach ($results as $identifer => $method_data) {
      printf($mask, $identifer, "{$method_data[0]}ms", $method_data[1]);
    }
  }
}
