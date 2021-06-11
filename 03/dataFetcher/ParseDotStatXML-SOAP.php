<?php

require_once(__DIR__.'/ParseDotStatXML.php');

//adding SOAP authentication
require_once(__DIR__.'/../../BaseURL.php');
require_once(__DIR__.'/../../02projects/'.$themeURL.'/urlMapperConfig.php');


// fetch XML data via SOAP interface and write into JSON file
function pullSOAPJSON($inputFile, $outputFile, $renames = null) {
	$parser = new OECDSOAPParser();
	$data = $parser->fetchData($inputFile);
	if (is_null($renames)) {
		$data->renameKeys();
	} else {
		$data->renameKeysInDimensions($renames);
	}
	$data->saveJSON($outputFile);
}

class OECDSOAPParser {
	// authentication data
	public $logon;
	public $domain;
	public $password;

	function __construct() {
	// initialize authentication data
		global $ConfigSOAP;
		$this->logon = $ConfigSOAP['logon'];
		$this->domain = $ConfigSOAP['domain'];
		$this->password = $ConfigSOAP['password'];
	}

	// fetch XML data via SOAP interface and write into JSON file
	public function fetchData($inputFile) {
		if (!headers_sent()) header('Content-Type: text/plain');
		printf("OECDSOAPParser: parsing XML data from %s\n", $inputFile);
		ob_start();
		include($inputFile);
		$query = ob_get_clean();
		if ($query === FALSE) die("could not open $inputFile\n");
		$xmlstr = $this->getGenericData($query);
		$parser = new OECDDataParser();
		return $parser->parseXML($xmlstr);
	}

	// called in case of an error in the SOAP request
	private function soapFailure($client, $ex) {
  		die("SOAP Failure: $ex\n".
		    $client->__getLastRequestHeaders()."\n".
  		    $client->__getLastRequest()."\n".
  		    $client->__getLastResponseHeaders()."\n".
  	            $client->__getLastResponse()."\n");
	}

	// return results of GetGenericData SOAP method
	public function getGenericData($query) {
		try {
			$client = new SoapClient('https://stats.oecd.org/SDMXWS/sdmx.asmx?WSDL', array(
					'soap_version' => SOAP_1_2,
					'login'        => $this->domain . '\\' . $this->logon,
					'password'     => $this->password,
					'trace'        => TRUE));
			$query = '<QueryMessage xmlns="http://stats.oecd.org/OECDStatWS/SDMX/">' . $query . '</QueryMessage>';
			$res = $client->GetGenericData(array('QueryMessage' => new SoapVar($query, XSD_ANYXML, 'QueryMessage')));
			return $res->GetGenericDataResult->any;
		} catch (Exception $ex) { $this->soapFailure($client, $ex); }
	}
}

?>
