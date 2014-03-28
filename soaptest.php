<?php
$client = new SoapClient(   
    null, 
    array(  
        'location' => "https://partnerweb.sveaekonomi.se/WebPayAdminService_Test/AdminService.svc/backward",
        'uri' => "http://tempuri.org/", 
        'style' => SOAP_RPC,
        'use' => SOAP_LITERAL,    
        'exceptions'=> 1,
        'trace' => 1,
        'soap_version' => SOAP_1_1 
    )
);

class Authentication {
    public $Password;
    public $Username;
    
    function __construct( $password, $username ) {
        $this->Password = new SoapVar( $password, XSD_STRING,"-","--","Password","http://schemas.datacontract.org/2004/07/DataObjects.Admin.Service");
        $this->Username = new SoapVar( $username, XSD_STRING,"-","--","Username","http://schemas.datacontract.org/2004/07/DataObjects.Admin.Service"); 
    }
}
$authentication = new Authentication( "sverigetest", "sverigetest" );

class GetOrderInformation {
    public $ClientId;// = 79021;
    public $SveaOrderId;// = 327410;
    
    function __construct( $clientId, $sveaOrderId ) {
        $this->ClientId = new SoapVar( $clientId, XSD_STRING,"-","--","ClientId","http://schemas.datacontract.org/2004/07/DataObjects.Admin.Service");
        $this->SveaOrderId = new SoapVar( $sveaOrderId, XSD_STRING,"-","--","SveaOrderId","http://schemas.datacontract.org/2004/07/DataObjects.Admin.Service"); 
    }
}

class OrdersToRetrieve {
    public $GetOrderInformation;
    
    function __construct() {
        $getOrderInformation = new GetOrderInformation("79021", "327410");
        $this->GetOrderInformation = new SoapVar( $getOrderInformation, SOAP_ENC_OBJECT, "-","--","OrdersToRetrieve","http://schemas.datacontract.org/2004/07/DataObjects.Admin.Service");
    }
}
$ordersToRetrieve = new OrdersToRetrieve;

class GetOrdersRequest {
    public $Authentication;
    public $OrdersToRetrieve;
    
    function __construct( $authentication, $ordersToRetrieve ) {
        
        $this->Authentication = new SoapVar( $authentication, SOAP_ENC_OBJECT, "-","--","Authentication","http://schemas.datacontract.org/2004/07/DataObjects.Admin.Service");
        $this->OrdersToRetrieve = new SoapVar( $ordersToRetrieve, SOAP_ENC_OBJECT, "-","--","OrdersToRetrieve","http://schemas.datacontract.org/2004/07/DataObjects.Admin.Service");;
    }
}

$req = new GetOrdersRequest( $authentication, $ordersToRetrieve );
$data = new SoapVar( $req, SOAP_ENC_OBJECT, "-", "--", "request", "http://tempuri.org/");


//var_dump( $data );
//var_dump( $authdata );
//die;

try {          
    $return = $client->__soapCall( "GetOrders", array( $data ), array(
            "soapaction" => "http://tempuri.org/IAdminService/GetOrders"
        )
    );
    
} catch (\SoapFault $e) {
    echo "<pre>";
    print_r($e);
    echo "<xmp>";
    echo $client->__getLastRequest() . "\n";
    echo $client->__getLastRequestHeaders();
    echo "</xmp>";
    echo "</pre>";
    die();
}

echo "<pre>";
echo "<xmp>";
echo $client->__getLastRequest() . "\n";
echo $client->__getLastRequestHeaders();

echo $return;
echo "</xmp>";
echo "</pre>";
?>