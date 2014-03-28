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
?>