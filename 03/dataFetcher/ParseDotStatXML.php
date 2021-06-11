<?php

function pullJSON($inputUrl, $outputFile, $renames = null) {
	$parser = new OECDDataParser();
	$data = $parser->fetchData($inputUrl);
	if (is_null($renames)) {
		$data->renameKeys();
	} else {
		$data->renameKeysInDimensions($renames);
	}
	$data->saveJSON($outputFile);
}

class OECDDataParser {
	public function fetchData($infile) {
		if (!headers_sent()) header('Content-Type: text/plain');
		printf("parsing XML data from %s\n", $infile);
		$xmlstr = @file_get_contents($infile);
                if ($xmlstr === FALSE) throw new Exception("could not read file");
		return $this->parseXML($xmlstr);
	}

	public function sdmxVersion($xmlns, $root) {
		$sdmxVersions = array(
					"2.0" => array(
							"xmlns" => "http://www.SDMX.org/resources/SDMXML/schemas/v2_0/message",
							"root"	=> "MessageGroup",
							"parseSDMX" => "parseSDMX20",
						),
					"2.1" => array(
							"xmlns" => "http://www.sdmx.org/resources/sdmxml/schemas/v2_1/message",
							"root"	=> "GenericData",
							"parseSDMX" => "parseSDMX21",
						),
					);
		foreach ($sdmxVersions as $version => $settings) {
			if ($xmlns == $settings['xmlns'] && $root == $settings['root']) return $settings;
		}
		throw new Exception("Unknown SDMX version");
	}

	public function parseSDMX20($xmldata) {
		$xpath = new DOMXPath($xmldata);
		$xpath->registerNamespace('m', 'http://www.SDMX.org/resources/SDMXML/schemas/v2_0/message');
		$xpath->registerNamespace('g', 'http://www.SDMX.org/resources/SDMXML/schemas/v2_0/generic');

		$data = new ChartData();
		$yearLabel="YEAR";
		$yearArr = array();

		//gather keys
		foreach ($xpath->query('//g:Series/g:SeriesKey/g:Value') as $seriesKey) {
				$keyLabel = $seriesKey->getAttribute('concept');
				$key = $seriesKey->getAttribute('value');
				$data->addKey($keyLabel, $key);
		}
		foreach ($xpath->query('//g:Series/g:Obs/g:Time') as $obsDimension) {
				$year = $obsDimension->nodeValue;
				$data->addKey($yearLabel, $year);
		}
		sort($data->keyArr[$yearLabel]);

		//fill array with nulls
		$data->initializeData();

		//gather data
		foreach ($xpath->query('//g:Series') as $series) {
			$indexArr = array();
			foreach ($xpath->query('g:SeriesKey/g:Value', $series) as $seriesKey) {
				$keyLabel = $seriesKey->getAttribute('concept');
				$key = $seriesKey->getAttribute('value');
				$indexArr[$keyLabel] = $key;
			}
			foreach ($xpath->query('g:Obs', $series) as $dataPoint) {
				$year = $xpath->evaluate('string(g:Time/text())', $dataPoint);
				$value = $xpath->evaluate('number(g:ObsValue/@value)', $dataPoint);
				if (is_nan($value)) $value = null;
				$indexArr[$yearLabel] = $year;
				$data->setData($indexArr,$value);
			}
		}

		return $data;
	}

	public function parseSDMX21($xmldata) {
		$xpath = new DOMXPath($xmldata);
		$xpath->registerNamespace('m', 'http://www.sdmx.org/resources/sdmxml/schemas/v2_1/message');
		$xpath->registerNamespace('g', 'http://www.sdmx.org/resources/sdmxml/schemas/v2_1/data/generic');

		$data = new ChartData();
		$yearLabel="YEAR";
		$yearArr = array();

		//gather keys
		foreach ($xpath->query('/m:GenericData/m:DataSet/g:Series/g:SeriesKey/g:Value') as $seriesKey) {
				$keyLabel = $seriesKey->getAttribute('id');
				$key = $seriesKey->getAttribute('value');
				$data->addKey($keyLabel, $key);
		}
		foreach ($xpath->query('/m:GenericData/m:DataSet/g:Series/g:Obs/g:ObsDimension') as $obsDimension) {
				$year = $obsDimension->getAttribute('value');
				$data->addKey($yearLabel, $year);
		}
		sort($data->keyArr[$yearLabel]);

		//fill array with nulls
		$data->initializeData();

		//gather data
		foreach ($xpath->query('/m:GenericData/m:DataSet/g:Series') as $series) {
			$indexArr = array();
			foreach ($xpath->query('g:SeriesKey/g:Value', $series) as $seriesKey) {
				$keyLabel = $seriesKey->getAttribute('id');
				$key = $seriesKey->getAttribute('value');
				$indexArr[$keyLabel] = $key;
			}
			$mult = $xpath->evaluate('number(g:Attributes/g:Value[@id="UNIT_MULT"]/@value)', $series);
			if (is_nan($mult)) $mult = 1;
			else $mult = pow(10, $mult);
			$unit = $xpath->evaluate('string(g:Attributes/g:Value[@id="UNIT"]/@value)', $series);
			foreach ($xpath->query('g:Obs', $series) as $dataPoint) {
				$year = $xpath->evaluate('string(g:ObsDimension/@value)', $dataPoint);
				$value = $xpath->evaluate('number(g:ObsValue/@value)', $dataPoint);
				if (is_nan($value)) $value = null;
                                else $value *= $mult;
				$indexArr[$yearLabel] = $year;
				$data->setData($indexArr,$value);
			}
		}

		return $data;
	}

	public function parseXML($xmlstr) {
		$xmldata = new DOMDocument();
		if (!$xmldata->loadXML($xmlstr)) throw new Exception("could not parse xml");
		$version = $this->sdmxVersion($xmldata->firstChild->namespaceURI, $xmldata->firstChild->localName);
		return call_user_func(array($this, $version['parseSDMX']), $xmldata);
	}
}

class ChartData {
	private $keyTable = array(
		'OTO'  => 'OECD',
		'EA15' => 'EUR',
		'OAVG' => 'OECD',
	);

	public $dimensionArr;
	public $keyArr;
	public $data;
	
	function __construct() {
		$this->keyArr = array();
		$this->dimensionArr = array();
		$this->data = NULL;
	}
	
	public function addKey($dimension, $key) {
		if (!in_array($dimension, $this->dimensionArr)) {
			$this->dimensionArr[] = $dimension;
			$this->keyArr[$dimension] = array();
		}
		if (!in_array($key, $this->keyArr[$dimension])) $this->keyArr[$dimension][] = $key;
	}
	
	public function setKeys($dimension, $keys) {
		if (!in_array($dimension, $this->dimensionArr)) {
			$this->dimensionArr[] = $dimension;
		}
		$this->keyArr[$dimension] = $keys;
	}
	
	public function initArr($countArr, $val) {
		foreach (array_reverse($countArr) as $count) {
			$a = array();
			while ($count--) $a[] = $val;
			$val = $a;
		}
		return $val;
	}
			
	public function initializeData() {
		$countArr = array();
		foreach ($this->dimensionArr as $dimension) {
			$countArr[]=count($this->keyArr[$dimension]);
		}
		$this->data = $this->initArr($countArr,NULL);
	}

	public function setData($keyDict, $value) {
		//get indexes
		$idxArr = array();
		$dimensionSizeArr = array();
		foreach ($this->dimensionArr as $dimension) {
			if (!isset($keyDict[$dimension])) return;
			$idx = array_search($keyDict[$dimension], $this->keyArr[$dimension]);
			if ($idx === FALSE) return;
			$idxArr[] = $idx;
			$dimensionSizeArr[] = count($this->keyArr[$dimension]);
		}
		//$this->addToArrSquare($this->data,$idxArr,$value,$dimensionSizeArr);
		$this->addToArr($this->data,$idxArr,$value);
	}
	
	private function addToArr( & $arr, $idxArr, $value) {
		//traverse array, expanding as necessary
		$dataVal =& $arr;
		for ($i = 0; $i < count($idxArr); $i++ ) {
			$idx = $idxArr[$i];
			if ($dataVal == NULL) $dataVal = array();
			while (count($dataVal) <= $idx) $dataVal[] = NULL;
			$dataVal = & $dataVal[$idx];
		}
		//set value
		$dataVal = $value;
	}
	
	private function addToArrSquare( & $arr, $idxArr, $value, $maxIdxArr) {
		//traverse array, expanding as necessary
		$dataVal =& $arr;
		for ($i = 0; $i < count($idxArr); $i++ ) {
			$idx = $idxArr[$i];
			if ($dataVal == NULL) {
				$dataVal = array();
				for ($j = 0; $j < $maxIdxArr[$i];$j++) $dataVal[]=NULL;
			}
			$dataVal = & $dataVal[$idx];
		}
		//set value
		$dataVal = $value;
	}

 	/**
	 * rename dimensions
	 */
	public function renameDimensions($renames) {
		foreach ($this->dimensionArr as &$dimension) {
			if (isset($renames[$dimension])) {
				$new = $renames[$dimension];
				printf("replacing dimension %s with %s\n", $dimension, $new);
				$dimension = $new;
			}
		}
	}

 	/**
	 * rename key values in specific dimensions
	 */
	public function renameKeysInDimensions($renames) {
		foreach ($this->dimensionArr as $dimension) {
			if (!isset($renames[$dimension])) continue;
			foreach ($this->keyArr[$dimension] as &$name) {
				if (isset($renames[$dimension][$name])) {
					$new = $renames[$dimension][$name];
					printf("replacing %s:%s with %s\n", $dimension, $name, $new);
					$name = $new;
				}
			}
		}
	}

 	/**
	 * rename key values
	 */
	public function renameKeys($renames = null) {
		if (is_null($renames)) $renames = $this->keyTable;
		foreach ($this->dimensionArr as $dimension) {
			foreach ($this->keyArr[$dimension] as &$name) {
				if (isset($renames[$name])) {
					$new = $renames[$name];
					printf("replacing %s:%s with %s\n", $dimension, $name, $new);
					$name = $new;
				}
			}
		}
	}

	public function toJSON() {
		$jsonObj = array();
		$jsonObj['dimensions'] = $this->dimensionArr;
		$jsonObj['keys'] = array();
		foreach ($this->dimensionArr as $dimension) {
			$jsonObj['keys'][]=$this->keyArr[$dimension];
		}
		$jsonObj['data'] =& $this->data;
		$json = json_encode($jsonObj);
                if ($json === FALSE) throw new Exception("could not JSON encode");
		return $json;
	}

        public function saveJSON($outfile) {
		if (file_put_contents($outfile,$this->toJSON()) === FALSE) throw new Exception("could not save JSON");
		return $this;
        }

}


?>
