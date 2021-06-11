<?php
/**
 * Classes and utility functions for manipulating multidimensional data.
 *
 * @todo: move mdGet and mdSet into a class
 */

/**
 * Compare two floating point values and return integer suitable for user defined sort functions.
 *
 * @param float $a
 * @param float $b
 * @return int
*/
function cmpFloat($a, $b) {
  if ($a < $b) return -1;
  else if ($a > $b) return 1;
  else return 0;
}
    
/**
 * Fetch value in multidimensional array
 *
 * @param float[] $array
 * @param int[] $index
 * @return float
*/
function mdGet(&$array, $index) {
  foreach ($index as $i) {
    $array =& $array[$i];
  }
  return $array;
}

/**
 * Set value in multidimensional array
 *
 * @param float[] $array
 * @param int[] $index
 * @param float $value
 * @return void
*/
function mdSet(&$array, $index, $value) {
  $last = count($index)-1;
  foreach ($index as $k => $i) {
    if ($k < $last) {
      if (!isset($array[$i])) $array[$i] = array();
      $array =& $array[$i];
    } else {
      $array[$i] = $value;
    }
  }
}

/** A multidimensional counter. */
class mdCounter
{
  /** @var int[] */
  private $shape;

  /** @var int[] */
  private $cntr;

  /** @var bool */
  private $done = FALSE;

  /**
   * Construct multidimensional counter with given shape.
   *
   * @param int[] $shape
   */
  function __construct($shape) {
    $this->shape = $shape;
  }

  /**
   * Get current value of counter.
   *
   * @return int[]
   */
  public function get() {
    return $this->cntr;
  }

  /**
   * Get next value of counter.
   *
   * This method returns a list of zeros on a new object.
   *
   * If the counter has reached the end it returns FALSE.
   *
   * @return int[]|false
   */
  public function next() {
    if ($this->done) return FALSE;
    if (is_null($this->cntr)) {
       $this->cntr = array_fill(0, count($this->shape), 0);
       return $this->cntr;
    }
    for ($i=count($this->shape); $i--; ) {
      $this->cntr[$i]++;
      if ($this->cntr[$i] >= $this->shape[$i]) {
        $this->cntr[$i]=0; // reset to zero and increase next dimension
      } else {
        return $this->cntr;
      }
    }
    $this->done = TRUE;
    return FALSE;
  }
}

/**
 * This class represents multidimensional data.
 */
class DC
{
  /** @var string[] */
  private $dimensions;

  /** @var string[] */
  private $keys;

  /** @var array */
  private $data;

  /**
   * Convert data into JSON format.
   *
   * Returns a JSON string in the following format:
   * ```javascript
   *   {
   *     'dimensions': [...],
   *     'keys': [...],
   *     'data': [...]
   *   }
   * ```
   *
   * @return string
   */
  public function toJSON() {
      return json_encode(array('dimensions' => $this->dimensions,
                               'keys' => $this->keys, 
                               'data' => $this->data));
  }

  /**
   * Convert data into arrays.
   *
   * Returns an array in the following format:
   * ```php
   *       array(
   *         'dimensions' => array(...),
   *         'keys'       => array(...),
   *         'data'       => array(...)
   *       )
   * ```
   *
   * @return array
   */
  public function toArray() {
    return array('dimensions' => $this->dimensions,
                 'keys' => $this->keys, 
                 'data' => $this->data);
  }

  /**
   * Converts data from JSON string.
   *
   * Expects a JSON string in the following format:
   * ```javascript
   *   {
   *     'dimensions': [...],
   *     'keys': [[...], [...], ...],
   *     'data': [...]
   *   }
   * ```
   * The fields have the following meaning:
   *  - dimensions: the names of the dimensions
   *  - keys: the possible keys for each dimension
   *  - data: a multidimensional array of numbers/nulls
   *
   * @param string $json
   * @return void
   * @todo validate result of json_decode()
   */
  public function fromJSON($json) {
    $v = json_decode($json, TRUE);
//    if (json_last_error() == JSON_ERROR_NONE) {
      $this->dimensions = $v['dimensions'];
      $this->keys = $v['keys'];
      $this->data = $v['data'];
    //} else {
    //  throw new Exception("could not parse input");
//    }
  }

  /**
   * Load data from JSON formatted file.
   *
   * @see DC::fromJSON() Details of the JSON format.
   * @param string $filename
   * @return void
   */
  public function loadJSON($filename) {
    $json = file_get_contents($filename, true);
    if ($json === FALSE) {
      throw new Exception("could not read file");
    } else {
      $this->fromJSON($json);
    }
  }

  /**
   * Internal function to convert CSV file entries to float|null.
   *
   * @ignore
   * @param string $item
   * @return float|null
   */
  public function checkNull($item) {
    if ($item=='-' || $item=='xx' || $item=='..' || $item=='#N/A') return NULL;
    return floatval($item);
  }

  /**
   * Convert multidimensional CSV files into JSON format.
   *
   * Observation value is expected to be in the last column,
   * preceding keys do not need to be unique.
   *
   * Example:
   *
   *  ```php
   *    loadMultiCSV('SectorRecipient2011.csv');
   *  ```
   *
   * @param string $inputFile
   * @return void
   */
  function loadMultiCSV($inputFile) {
    $oldval = ini_set('auto_detect_line_endings', '1');
    $dims = array();
    $keys = array();
    $data = array();
    $maxEntries = 0;
    $row = 0;
    if (($handle = fopen($inputFile, 'r')) === FALSE) {
      throw new Exception("could not read file");
    }
    while (($line = fgetcsv($handle)) !== FALSE) {
      $row++;
      if ($row == 1) {
        $dims = $line;
        $keys = array_fill(0, count($dims), array());
      } else {
        $d =& $data;
        $obs = $this->checkNull(array_pop($line));
        foreach ($line as $idx => $v) {
          $k = array_search($v, $keys[$idx]);
          if ($k === FALSE) {
            $k = count($keys[$idx]);
            $keys[$idx][$k] = $v;
          }
          // insert missing entries
          for ($cnt = count($d); $cnt < $k; $cnt++) $d[$cnt] = NULL;
          $d =& $d[$k];
        }
        $k = count($d);
        if ($k > $maxEntries) $maxEntries = $k;
        $d[] = $obs;
      }
    }
    fclose($handle);
    ini_set('auto_detect_line_endings', $oldval);
    $keys[count($keys)-1] = range(0, $maxEntries);
    $this->dimensions = $dims;
    $this->keys = $keys;
    $this->data = $data;
  }

  /**
   * Convert CSV files into JSON format.
   *
   * Default key for rowName is 'COUNTRY'.
   *
   * Default key for colnName is 'YEAR'.
   *
   * Examples:
   *
   *  ```php
   *   loadCSVTable(array('file' => 'CoreInflation.csv'));
   *  ```
   *
   *  ```php
   *   loadCSVTable(array('file' => 'ByAgeMeanDisposableIncome.csv',
   *                      'rowName' => 'COUNTRY',
   *                      'colName' => 'AGE'));
   *  ```
   *
   * @param array $o
   * @return void
   */
  public function loadCSVTable($o) {
    // merge arguments with default values
    $o = array_merge(array('colName' => 'YEAR',
                           'rowName' => 'COUNTRY',
                           'stringValues' => FALSE),
                     $o);
    $this->dimensions = array($o['rowName'], $o['colName']);
    $inputFile = $o['file'];
    $oldval = ini_set('auto_detect_line_endings', '1');
    $row = 0;
    $this->data = array();
    if (($handle = fopen($inputFile, 'r')) === FALSE) {
      throw new Exception("could not read file");
    }
    $rows = array();
    while (($line = fgetcsv($handle)) !== FALSE) {
      $row++;
      if ($row == 1) {
        array_shift($line);
        $columns = $line;
      } else {
        $rows[] = array_shift($line);
        if ($o['stringValues']) {
          $this->data[] = $line;                    
        } else {
          $this->data[] = array_map(array($this, 'checkNull'), $line);          
        }
      }
    }
    fclose($handle);
    ini_set('auto_detect_line_endings', $oldval);
    $this->keys = array($rows, $columns);
  }

  /**
   * Save data to JSON formatted file.
   *
   * @see DC::fromJSON() Details of the JSON format.
   * @param string $filename
   * @return void
   */
  public function saveJSON($filename) {
    if (file_put_contents($filename, $this->toJSON()) === FALSE) {
      throw new Excpetion("could not write file");
    }
  }

  /**
   * Print data in JSON format.
   *
   * @see DC::fromJSON() Details of the JSON format.
   * @return void
   */
  public function dumpJSON() {
    print($this->toJSON());
  }

  /**
   * Returns an array mapping dimensions to their respective keys.
   *
   * @return array
   */
  public function keyMap() {
    $k = array();
    foreach ($this->dimensions as $i => $d) {
      $k[$d] = $this->keys[$i];
    }
    return $k;
  }

  /**
   * Returns the shape of the data.
   *
   * The shape is the number of keys in each dimension.
   *
   * @return int[]
   */
  public function shape() {
    return array_map('count', $this->keys);
  }

  /**
   * Order the dimensions in the given order.
   *
   * @param string[] $dims List of dimensions
   * @return void
   */
  public function orderDimensions($dims) {
    // check if already sorted
    if ($dims === $this->dimensions) return;

    // check for missing dimensions
    $missingDims = array_diff($dims, $this->dimensions);
    if (count($missingDims)) {
      throw new Exception("missing dimensions ".join(',', $missingDims));
    }

    // rebuild keys array
    $keys = array();
    $keyMap = $this->keyMap();
    foreach ($dims as $d) {
      $keys[] = $keyMap[$d];
    }

    // rebuild data array
    $data = array();
    $dest = new mdCounter(array_map('count', $keys));
    $srcIdx = array_fill(0, count($this->dimensions), 0);
    $dimIdx = array();
    foreach ($dims as $d) {
      $dimIdx[] = array_search($d, $this->dimensions);
    }
    while (($destIdx = $dest->next()) !== FALSE) {
      foreach ($dimIdx as $i => $j) $srcIdx[$j]=$destIdx[$i];
      mdSet($data, $destIdx, mdGet($this->data, $srcIdx));
    }

    $this->dimensions = $dims;
    $this->keys = $keys;
    $this->data = $data;
  }

  /**
   * Build multidimensional array with given dimensions collapsed to latest value.
   *
   * The last entry in {$index} is treated specially: any missing values will be
   * replaced by an earlier value.
   *
   * @param array $index Array mapping dimensions to a single key
   * @return self|null
   */
  public function filterLatest($index) {
    $keyMap = $this->keyMap();
    $cube = new DC();
    foreach ($index as $dim => $key) {
      if (!array_key_exists($dim, $keyMap)) {
        return null;
      } elseif (array_search($key, $keyMap[$dim]) === FALSE) {
        return null;
      }
    }
    $cube->dimensions = array_values(array_diff($this->dimensions, array_keys($index)));
    $cube->keys = array();
    foreach ($cube->dimensions as $d) $cube->keys[] = $keyMap[$d];
    $cube->data = array();
    $dest = new mdCounter(array_map('count', $cube->keys));
    $srcIndex = array();
    $dimIdx = array();
    foreach ($this->dimensions as $i => $d) {
      if (isset($index[$d])) {
        $pos = count($srcIdx);
        $srcIdx[] = array_search($index[$d], $this->keys[$i]);
      } else {
        $srcIdx[] = 0;
        $dimIdx[] = $i;
      }
    }
    while (($destIdx = $dest->next()) !== FALSE) {
      foreach ($dimIdx as $i => $j) $srcIdx[$j]=$destIdx[$i];
      $curSrcIdx = $srcIdx;
      while (($val = mdGet($this->data, $curSrcIdx)) === null) {
        if (!$curSrcIdx[$pos]--) break;
      }
      mdSet($cube->data, $destIdx, $val);
    }
    return $cube;
  }

  /**
   * Build multidimensional array with given dimensions collapsed to one key.
   *
   * @param array $index Array mapping dimensions to a single key
   * @return self|null
   */
  public function filter($index) {
    $keyMap = $this->keyMap();
    $cube = new DC();
    foreach ($index as $dim => $key) {
      if (!array_key_exists($dim, $keyMap)) {
        return null;
      } elseif (array_search($key, $keyMap[$dim]) === FALSE) {
        return null;
      }
    }
    $cube->dimensions = array_values(array_diff($this->dimensions, array_keys($index)));
    $cube->keys = array();
    foreach ($cube->dimensions as $d) $cube->keys[] = $keyMap[$d];
    $cube->data = array();
    $dest = new mdCounter(array_map('count', $cube->keys));
    $srcIndex = array();
    $dimIdx = array();
    foreach ($this->dimensions as $i => $d) {
      if (isset($index[$d])) {
        $srcIdx[] = array_search($index[$d], $this->keys[$i]);
      } else {
        $srcIdx[] = 0;
        $dimIdx[] = $i;
      }
    }
    while (($destIdx = $dest->next()) !== FALSE) {
      foreach ($dimIdx as $i => $j) $srcIdx[$j]=$destIdx[$i];
      mdSet($cube->data, $destIdx, mdGet($this->data, $srcIdx));
    }
    return $cube;
  }

  /**
   * Retrieve value at given index.
   *
   * All dimensions are optional, the first key will be used for
   * missing dimensions.
   *
   * @param array $index Array mapping dimensions to a single key
   * @return float
   */
  public function atIndex($index) {
    $cntr = array();
    foreach ($this->dimensions as $i => $d) {
      if (isset($index[$d])) {
        $pos = array_search($index[$d], $this->keys[$i]);
        if ($pos === FALSE) {
          return NULL;
        }
        $cntr[] = $pos;
      } else {
        $cntr[] = 0;
      }
    }
    return mdGet($this->data, $cntr);
  }

  /**
   * Set value at given index.
   *
   * All dimensions are optional, the first key will be used for
   * missing dimensions.
   *
   * @param array $index Array mapping dimensions to a single key
   * @param float $value New value
   * @return void
   * @todo check for non-existent keys
   */
  public function updateAtIndex($index, $value) {
    $cntr = array();
    foreach ($this->dimensions as $i => $d) {
      if (isset($index[$d])) {
        $cntr[] = array_search($index[$d], $this->keys[$i]);
      } else {
        $cntr[] = 0;
      }
    }
    mdSet($this->data, $cntr, $value);
  }

  /**
   * Update values using a callback function
   *
   * The callback function
   * is called for every entry in the array and
   * the array is updated with the return value.
   *
   * @param callback $callback
   *    float $callback(float $val, array $index)
   * @return void
   */
  public function updateValues($callback) {
    $dest = new mdCounter($this->shape());
    while (($destIdx = $dest->next()) !== FALSE) {
      $val = mdGet($this->data, $destIdx);
      $index = array();
      foreach ($destIdx as $i => $j) $index[$this->dimensions[$i]] = $this->keys[$i][$j];
      $val = call_user_func($callback, $val, $index);
      mdSet($this->data, $destIdx, $val);
    }
  }

  /**
   * Add muliple new keys in a single dimension.
   *
   * The callback function is called once for each set
   * of new values.
   *
   * @param string $dim Dimension for new keys
   * @param string[] $keys New keys
   * @param callback $callback
   *    float[] $callback(array $index)
   * @return void
   */
  public function addKeys($dim, $keys, $callback) {
    $dimIdx = array_search($dim, $this->dimensions);
    $keyLen = count($this->keys[$dimIdx]);
    $shape = $this->shape();
    $shape[$dimIdx] = 1;
    $dest = new mdCounter($this->shape());
    while (($destIdx = $dest->next()) !== FALSE) {
      $destIdx[$dimIdx] = $keyLen;
      $index = array();
      foreach ($destIdx as $i => $j) if ($i!=$dimIdx) $index[$this->dimensions[$i]] = $this->keys[$i][$j];
      $vals = call_user_func($callback, $index);
      foreach ($vals as $val) {
        mdSet($this->data, $destIdx, $val);
        $destIdx[$dimIdx]++;
      }
    }
    foreach ($keys as $key) $this->keys[$dimIdx][] = $key;
  }

  /**
   * Add one key in a single dimension.
   *
   * The callback function is called for each index
   * of the other dimensions.
   *
   * @param string $dim Dimension for new key
   * @param string $key New key
   * @param callback $callback
   *    float $callback(array $index)
   * @return void
   */
  public function addKey($dim, $key, $callback) {
    $dimIdx = array_search($dim, $this->dimensions);
    $keyLen = count($this->keys[$dimIdx]);
    $shape = $this->shape();
    $shape[$dimIdx] = 1;
    $dest = new mdCounter($this->shape());
    while (($destIdx = $dest->next()) !== FALSE) {
      $destIdx[$dimIdx] = $keyLen;
      $index = array();
      foreach ($destIdx as $i => $j) if ($i!=$dimIdx) $index[$this->dimensions[$i]] = $this->keys[$i][$j];
      $val = call_user_func($callback, $index);
      mdSet($this->data, $destIdx, $val);
    }
    $this->keys[$dimIdx][] = $key;
  }

  /**
   * Initialize a multidimensional array.
   *
   * @param int[] $countArr Shape of array
   * @param float|null $val Initial value
   * @return array
   */
  private function initArr($countArr, $val) {
    foreach (array_reverse($countArr) as $count) {
      $a = array();
      while ($count--) $a[] = $val;
      $val = $a;
    }
    return $val;
  }
                        
  /**
   * Initialize multidimensional array with given dimensions and keys.
   *
   * @param string[] $dim Dimensions
   * @param string[] $keys Keys for each dimension
   * @return void
   */
  public function initialize($dim, $keys) {
    $this->dimensions = $dim;
    $this->keys = $keys;
    $this->data = $this->initArr(array_map('count', $keys),NULL);
  }

  /**
   * Sort the keys of a given dimension by callback function.
   *
   * @param string $dim Dimension to sort
   * @param callback $callback Comparison function.
   *       int $callback (float $a, float $b);
   * @return void
   */
  public function sortDimension($dim, $callback) {
    $i = array_search($dim, $this->dimensions);
    if ($i === FALSE) {
      throw new Exception("no such dimension");
    }
    $key = $this->keys[$i];
    usort($key, $callback);
    $this->setDimension($dim, $key);
  }

  /**
   * Filter a dimension by a fixed list of keys.
   *
   * @param string $dim Dimension to filter
   * @param string[] $key Keys to keep
   * @return void
   */
  public function setDimension($dim, $key) {
    $i = array_search($dim, $this->dimensions);
    if ($i === FALSE) {
      throw new Exception("no such dimension");
    }
    $keys = $this->keys;
    $keys[$i] = $key;

    $data = array();
    $dest = new mdCounter(array_map('count', $keys));
    $oldIDs = array();
    foreach ($key as $v) {
      $j = array_search($v, $this->keys[$i]);
      if ($j === FALSE) {
        throw new Exception("no such key: $v");
      }
      $oldIDs[] = $j;
    }
    while (($destIdx = $dest->next()) !== FALSE) {
      $srcIdx = $destIdx;
      $srcIdx[$i] = $oldIDs[$srcIdx[$i]];
      mdSet($data, $destIdx, mdGet($this->data, $srcIdx));
    }
    
    $this->keys = $keys;
    $this->data = $data;
  }

    /**
     * Return a multidimensional array.
     *
     * @param string[] $dimensions Dimensions to return
     * @param string[] $skip (optional) Keys to skip, "Mean" and "StdDev" are skipped by default
     * @return array
     */
    public function toPHParray($dimensions, $skip = array('Mean', 'StdDev')) {
        $keyMap = $this->keyMap();
        $shape = array();
        foreach ($dimensions as $d) {
            $shape[] = count($keyMap[$d]);
        }
        $counter = new mdCounter($shape);
        $result = array();
        while (($idx = $counter->next()) !== FALSE) {
            $k = array();
            foreach ($idx as $d => $i) {
                $dim = $dimensions[$d];
                $k[$dim] = $keyMap[$dim][$i];
                if (array_search($k[$dim], $skip) !== FALSE) continue 2;
            }
            $val = $this->atIndex($k);
            mdSet($result, array_values($k), $val);
        }
        return $result;
    }

    /**
     * Return a multidimensional array containing only latest values
     *
     * @param string[] $dimensions Dimensions to return
     * @param string   $timeDimension Dimension containing dates
     * @param string   $latest (optional) Latest date to select, default: latest date in data
     * @return array
     */
    public function toLatestValues($dimensions, $timeDimension, $latest = NULL) {
        $keyMap = $this->keyMap();
        if ($latest === NULL) {
            $latestIdx = count($keyMap[$timeDimension])-1;
        } else {
            $latestIdx = array_search($latest, $keyMap[$timeDimension]);
            if ($latestIdx === FALSE) {
                throw new Exception("latest date not found in keys");
            }
        }
        $shape = array();
        foreach ($dimensions as $d) {
            $shape[] = count($keyMap[$d]);
        }
        $counter = new mdCounter($shape);
        $result = array();
        while (($idx = $counter->next()) !== FALSE) {
            $k = array();
            foreach ($idx as $d => $i) {
                $dim = $dimensions[$d];
                $k[$dim] = $keyMap[$dim][$i];
            }
            $result_idx = array_values($k);
            $time = $latestIdx+1;
            $val = NULL;
            while ($val === NULL && $time > 0) {
                $time--;
                $k[$timeDimension] = $keyMap[$timeDimension][$time];
                $val = $this->atIndex($k);
            }
            if ($val === NULL) $time = $latestIdx;
            mdSet($result, $result_idx, array($keyMap[$timeDimension][$time], $val));
        }
        return $result;
    }

    /** @var string */
    private $stddev_dimension;
    /** @var string[] */
    private $stddev_keys;

    /**
     * Calculate mean only.
     *
     * @ignore
     * @param string[] $index
     * @return float
     */
    public function _calcMean($index) {
        $M = 0.0;
        $k = 0;
        foreach ($this->stddev_keys as $key) {
            $index[$this->stddev_dimension] = $key;
            $x = $this->atIndex($index);
            if ($x !== null) {
                $k++;
                $M = $M + ($x - $M) / $k;
            }
        }
        return $M;
    }

    /**
     * Insert mean value into a dimension
     *
     * @param string $dimension
     * @param string[] $keys Keys for calculating the mean
     * @param string $keyname (optional) Name of new key, "Mean" by default
     * @return void
     */
    public function addMean($dimension, $keys, $keyname = 'Mean') {
        $this->stddev_dimension = $dimension;
        $this->stddev_keys = $keys;
        $this->addKey($dimension, $keyname, array($this, '_calcMean'));
    }

    /**
     * Calculate (sample) standard deviation.
     *
     * Calculate (sample) standard deviation with Welford's method,
     * the last step uses Bessel's correction
     * [ division by ($k-1) instead of $k ].
     *
     * @ignore
     * @param array $index
     * @return float[]
     * @link http://www.johndcook.com/standard_deviation.html
     */
    public function _calcStdDev($index) {
        $M = 0.0;
        $S = 0.0;
        $k = 0;
        foreach ($this->stddev_keys as $key) {
            $index[$this->stddev_dimension] = $key;
            $x = $this->atIndex($index);
            if ($x !== null) {
                $k++;
                $oldM = $M;
                $M = $M + ($x - $M) / $k;
                $S = $S + ($x - $oldM) * ($x - $M);
            }
        }
        if ($k > 1) return array($M, sqrt($S / ($k-1)));
        else return array($M, 0.0);
    }

    /**
     * Insert mean value and standard deviation into a dimension
     *
     * @param string $dimension
     * @param string[] $keys Keys for calculating the mean
     * @param string[] $keynames (optional) Names of the new keys, "Mean" and "Stddev" by default
     * @return void
     */
    public function addMeanStdDev($dimension, $keys, $keynames = array('Mean', 'StdDev')) {
        $this->stddev_dimension = $dimension;
        $this->stddev_keys = $keys;
        $this->addKeys($dimension, $keynames, array($this, '_calcStdDev'));
    }

    /** @var string */
    private $adj_dimension;
    /** @var string[] */
    private $adj_keynames;
    /** @var array */
    private $adj_ranges;

    /**
     * @ignore
     * @param float|null $val
     * @param array $index
     * @return float|null
     */
    public function _update($val, $index) {
        if ($val === null) return null;
        if (array_search($index[$this->adj_dimension], $this->adj_keynames) !== FALSE) return $val;
        $index[$this->adj_dimension]=$this->adj_keynames[0];
        $mean = $this->atIndex($index);
        $index[$this->adj_dimension]=$this->adj_keynames[1];
        $stdDev = $this->atIndex($index);
        foreach ($this->adj_ranges as $i => $factor) {
            if ($factor === null) return $i;
            if ($val > $mean + $factor * $stdDev) return $i;
        }
        return $i;
    }

    /**
     * Adjust values of one dimension to the mean value.
     *
     * First calculates mean and standard deviation, then adjusts the values according
     * to the given array $ranges which has the following format:
     * ```php
     * array(
     *   k_0  => v_0,           // k_0 is new value for values above v_0*StdDev
     *   k_1  => v_1,           // k_1 is new value for values between v_0*StdDev and v_1*StdDev
     *   ...
     *   k_n  => null           // k_n is new value for values below v_n-1*StdDev
     * )
     * ```
     *
     * @param string $dimension
     * @param array $ranges
     * @param string[] $keynames (optional) Names of the new keys, "Mean" and "Stddev" by default
     * @return void
     */
    public function adjustRelativeToMean($dimension, $ranges, $keynames = array('Mean', 'StdDev')) {
        $this->adj_dimension=$dimension;
        $this->adj_keynames=$keynames;
        $this->adj_ranges=$ranges;
        $this->updateValues(array($this, '_update'));
    }

  /**
   * Sort the keys of a dimension by sum of other values.
   *
   * @param string $dim Dimension to sort
   * @param int $order Sort ascending (SORT_ASC) or descending (SORT_DESC)
   * @return void
   */
  public function sortDimensionOverSum($dim, $sort = SORT_ASC) {
    $dimIdx = array_search($dim, $this->dimensions);
    if ($dimIdx === FALSE) {
      throw new Exception("no such dimension");
    }
    if ($sort != SORT_ASC && $sort != SORT_DESC) {
      throw new Exception("unknown sort order specified");
    }
    $key = $this->keys[$dimIdx];
    $shape = $this->shape();
    $shape[$dimIdx] = 1;
    $result = array();
    foreach ($key as $kIdx => $k) {
      $r = 0;
      $src = new mdCounter($shape);
      while (($index = $src->next()) !== FALSE) {
        $index[$dimIdx] = $kIdx;
        $val = mdGet($this->data, $index);
        $r += $val;
        $index[$dimIdx] = 0;
      }
      $result[$k] = $r;
    }
    usort($key, function($a, $b) use($result) {
                    $v = cmpFloat($result[$a], $result[$b]);
                    if ($sort == SORT_ASC) return $v;
                    return -$v;
    });
    $this->setDimension($dim, $key);
  }

}
?>
