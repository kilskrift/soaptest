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

//$data = 
//    new SoapParam( 
//        array(    
//            new SoapParam(   
//                array(  
//                    new SoapParam( "sverigetest", "Password" ),
//                    new SoapParam( "sverigetest", "Username" ) 
//                ), 
//                "Authentication" 
//            ),
//            new SoapParam(
//                new SoapParam(   
//                    array(  
//                        new SoapParam( "79021", "ClientId" ),
//                        new SoapParam( "327410", "SveaOrderId" ) 
//                    ), 
//                    "GetOrderInformation" 
//                ),
//                "OrdersToRetrieve"
//            )
//        ),
//        "request"
//    )
//;

class Authentication {
    public $Password = "sverigetest";
    public $Username = "sverigetest";
}
$authentication = new Authentication;

class GetOrderInformation {
    public $ClientId = 79021;
    public $SveaOrderId = 327410;
}

class OrdersToRetrieve {
    public $GetOrderInformation;
    
    function __construct() {
         $this->GetOrderInformation = new GetOrderInformation;
    }
}
$ordersToRetrieve = new OrdersToRetrieve;

class GetOrdersRequest {
    public $Authentication;
    public $OrdersToRetrieve;
    
    function __construct( $authentication, $ordersToRetrieve ) {
        $this->Authentication = $authentication;
        $this->OrdersToRetrieve = $ordersToRetrieve;
    }
}

$req = new GetOrdersRequest( $authentication, $ordersToRetrieve );
$data = new SoapVar( $req, SOAP_ENC_OBJECT, "-", "--", "request", "http://tempuri.org/");

$authdata = new SoapVar( $authentication, SOAP_ENC_OBJECT, "-", "--", "Authentication", "http://schemas.datacontract.org/2004/07/DataObjects.Admin.Service");



class Authentication2 {
    public $Password;
    public $Username;
    
    function __construct() {
        $this->Password = new SoapVar( "sverigetest", XSD_STRING, "-", "--", "Password", "http://schemas.datacontract.org/2004/07/DataObjects.Admin.Service");
        $this->Username = new SoapVar( "sverigetest", XSD_STRING, "-", "--", "Username", "http://schemas.datacontract.org/2004/07/DataObjects.Admin.Service");      
        
    }
}
$authentication2 = new Authentication2;


$authdata2 = new SoapVar( $authentication2, SOAP_ENC_OBJECT, "-", "--", "Authentication", "http://schemas.datacontract.org/2004/07/DataObjects.Admin.Service");

//var_dump( $data );
//var_dump( $authdata );
//die;

try {          
    $return = $client->__soapCall( "GetOrders", array( $authdata2 ), array(
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
?>