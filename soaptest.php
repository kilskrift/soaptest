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
$data = new SoapVar( $authentication, SOAP_ENC_OBJECT );

try {
//    $client->__setLocation("https://partnerweb.sveaekonomi.se/WebPayAdminService_Test/AdminService.svc/backward");            
    $return = $client->__soapCall( "GetOrders", array( $data ), array(
            'soapaction' => "http://tempuri.org/IAdminService/GetOrders"
        )
    );
//    $return = $client->GetOrders( array() );           
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