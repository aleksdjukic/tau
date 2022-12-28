<?php

# service configuration ##############################################################################
$endPoint = "https://preprodservices.crif-online.ch/CrifSS/CrifSoapServiceV1";
$username = "testUser";
$password = "Please_request_credentials_from_Deltavista";

$MAJOR_VERSION = 1;
$MINOR_VERSION = 5;  # <== adapt the major and minor version according the documentation / wsdl 
	                 #     you have received with the developement kit
 	                 #     1.5 is likely to reflect an outdated version.

# set the correct contract according to contract, i.e. CREDIT_CHECK_CONSUMER, QUICK_CHECK_CONSUMER, ...
# DO NOT copy this code for production reason. The example code is not necessary reflecting the latest interface 
# version. The purpose of this code is to demonstrate how an integration could look like. 
# Please build your own client based on the included WSDL and use this only as reference for your own code. 
$ReportType = "SET_YOUR_REPORT_ACCORDING_TO_CONTRACT";

######################################################################################################


$address = array(  
	  "firstname" => "Anna"
	, "name" => "Test"
	, "street" => "Hardstrasse"
	, "houseNumber" => "73"
	, "zip" => "5430"
	, "city" => "Wettingen"
	, "country" => "CHE"
	, "birthdate" => "1974-11-11"
	);
$referenceNumber = "DemoTestRefPHPNo";

//$solvencyChecker = new SolvencyChecker($endPoint, $username, $password, $MAJOR_VERSION, $MINOR_VERSION, $ReportType);
//$isSolvent = $solvencyChecker->isSolvent($address, $referenceNumber);
		
//if($isSolvent) 
////	echo "<br>***************************************<br>Anna Test is solvent!";
//else
///	echo "<br>***************************************<br>Anna Test is not solvent";

function logger($message) {
	//echo "<br>";
	//echo htmlentities("Logger [replace this with your own function] $message");
}

class SolvencyChecker {
	private $MAJOR_VERSION = null;
	private $MINOR_VERSION = null;
	private $ReportType = null;

	private $client = null;
	
	private $endPoint = null;
	private $username = null;
	private $password = null;
	
	function __construct($_endPoint, $_username, $_password, $_major_version, $_minor_version, $_reportType) {
		//print_r(WC()->customer->get_billing_country());
		$this->endPoint = $_endPoint;
		$this->username = $_username;
		$this->password = $_password;
		$this->MAJOR_VERSION = $_major_version;
		$this->MINOR_VERSION = $_minor_version;
		$this->ReportType = $_reportType;
		$this->client = new SoapClient(WOOCRIFPAY. "/inc/crif-soap-service_v1.0.wsdl", array('trace' => 1, "location" => $this->endPoint, 'soap_version' => SOAP_1_2));
	}
   
	public function isSolvent($address, $refno) {

		$request = $this->getRequestData($address, $refno);
		if($address['country'] == 'AT')
			$this->ReportType = 'QUICK_CHECK_CONSUMER';

		$order_awaiting_payment = WC()->session->get( 'order_awaiting_payment', 0 );
		WC()->session->set( 'crif_request_sent', $order_awaiting_payment );
		
		try { 
			$response = $this->client->getReport($request);
			
			logger("Request: " . $this->client->__getLastRequest());
			logger("Response: " . $this->client->__getLastResponse());
			
			$addressMatchResult = $response->addressMatchResult;
			$decisionMatrix = $response->decisionMatrix;
			
			$archivingId = $response->archivingId;
			$archivingIdString = number_format($archivingId, 0, '.', '');
			logger("SolvencyChecker succeeded, archivingId: $archivingIdString");
			
			if($addressMatchResult == null)
				throw new Exception("AddressMatchResult must not be null!");

			if($addressMatchResult->addressMatchResultType == "CANDIDATES")
				throw new Exception("No candidates handling implemented, please review Configuration of Deltavista Service");
				
			if($decisionMatrix == null)
				throw new Exception("DecisionMatrix must not be null!");
			
			if ($decisionMatrix->decision == "GREEN")
				return true;			
			
			return false;
			
		} catch (SoapFault $e) { 
			$faultstring = $e->faultstring;
			if (isset($e->detail) && isset($e->detail->error)) {
				$code =  $e->detail->error->code;
				$message = $e->detail->error->messageText;
				$this->logError("$faultstring; Code $code; Message: $message", $e);
			}
			else {
				$this->logError("$faultstring", $e);
			}
			
			/*throw new Exception(__('Select the date of your birth') );*/
			
		} 
    }
	
	private function logError($text, $e) {
		$message = "SolvencyChecker Error - $text";
		logger ($message);
		error_log ($message);
	}
	
	private function getRequestData($address, $refno) {
		$request = array(  
		  "referenceNumber" => $refno
		, "targetReportFormat" => "NONE"
		, "reportType" => $this->ReportType
		, "control" => $this->getControlData()
		, "identityDescriptor" => $this->getCredentials()
		, "searchedAddress" => $this->getConsumerAddress($address)
		);
		
		return $request;
	}

	private function getConsumerAddress($address) {
	
		$location  = new Location();
		$location->street = $address["street"];
		$location->houseNumber = $address["houseNumber"];
		$location->zip = $address["zip"];
		$location->city = $address["city"];
		$location->country = $address["country"];

		$personAddressDescription  = new PersonAddressDescription();
		$personAddressDescription->firstName = $address["firstname"];
		$personAddressDescription->lastName = $address["name"];
		$personAddressDescription->sex = "UNKNOWN";
		$personAddressDescription->birthDate = $address["birthdate"];
		$personAddressDescription->location = $location;
		
		$personAddressDescriptionSoapVar = new SoapVar($personAddressDescription, SOAP_ENC_OBJECT, "PersonAddressDescription", "http://www.crif-online.ch/webservices/crifsoapservice/v1.00");
		
		return $personAddressDescriptionSoapVar;
	}
	
	private function getCredentials() {
		$identityDescriptor  = new IdentityDescriptor();
		$identityDescriptor->userName = $this->username;
		$identityDescriptor->password = $this->password;
		return $identityDescriptor;		
	}

	private function getControlData() {
		$controlData  = new ControlData();
		$controlData->majorVersion = $this->MAJOR_VERSION;
		$controlData->minorVersion = $this->MINOR_VERSION;
		return $controlData;		
	}		
}

class AddressDescription {
	public $location = null;
	public $contactItems = null;
}

class PersonAddressDescription extends AddressDescription {
	public $firstName = null;
	public $lastName = null;
	public $maidenName = null;
	public $sex = null;
	public $birthDate = null;
}

class Location {
	public $street = null;
	public $houseNumber = null;
	public $zip = null;
	public $city = null;
	public $country = null;
}

class ContactItem {
	public $contactText = null;
	public $contactType = null;
}

class ControlData {
	public $majorVersion = null;
	public $minorVersion = null;
}

class IdentityDescriptor {
	public $userName = null;
	public $password = null;
	public $endUserId = null;
	public $costGroupId = null;
}

?>