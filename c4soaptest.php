<?php
namespace Payment\SveaWebpay;
class AdminService
{
    protected $client;
 
    public function __construct()
    {
        $this->client = new \SoapClient(
            null,
            array(
                'location' => "https://partnerweb.sveaekonomi.se/WebPayAdminService_Test/AdminService.svc/backward",
                'uri' => "http://tempuri.org/",
                'use' => SOAP_LITERAL,    
                'exceptions'=> 1,
                'connection_timeout' => 60,
                'trace' => 1,
                'soap_version' => SOAP_1_1
            )
        );
    }
 
    public function doRequest($action, $data = array())
    {
        try {
            $return = $this->client->__soapCall($action, $data, array(
                "soapaction" => 'http://tempuri.org/IAdminService/'.$action,
            ));
        } catch (\SoapFault $e) {
            echo "<pre>";
            print_r($e);
            echo "<xmp>";
            echo $this->client->__getLastRequest() . "\n";
            echo $this->client->__getLastRequestHeaders();
            echo "</xmp>";
            echo "</pre>";
            die();       
        }

        
        //yay!
        echo "<pre>";
        print_r($adminData);
        echo "<xmp>";
        echo $this->client->__getLastRequest() . "\n";
        echo $this->client->__getLastRequestHeaders();
        echo "</xmp>";
        echo "</pre>";
                
        return $return;
    }
}
 
class Authentication {
    public $Password;
    public $Username;
    
    function __construct( $password, $username ) {
        $this->Password = new \SoapVar( $password, XSD_STRING,"-","--","Password","http://schemas.datacontract.org/2004/07/DataObjects.Admin.Service");
        $this->Username = new \SoapVar( $username, XSD_STRING,"-","--","Username","http://schemas.datacontract.org/2004/07/DataObjects.Admin.Service"); 
    }
}
$authentication = new Authentication( "sverigetest", "sverigetest" );

class GetOrderInformation {
    public $ClientId;// = 79021;
    public $SveaOrderId;// = 327410;
    
    function __construct( $clientId, $sveaOrderId ) {
        $this->ClientId = new \SoapVar( $clientId, XSD_STRING,"-","--","ClientId","http://schemas.datacontract.org/2004/07/DataObjects.Admin.Service");
        $this->SveaOrderId = new \SoapVar( $sveaOrderId, XSD_STRING,"-","--","SveaOrderId","http://schemas.datacontract.org/2004/07/DataObjects.Admin.Service"); 
    }
}

class OrdersToRetrieve {
    public $GetOrderInformation;
    
    function __construct() {
        $getOrderInformation = new GetOrderInformation("79021", "327410");
        $this->GetOrderInformation = new \SoapVar( $getOrderInformation, SOAP_ENC_OBJECT, "-","--","OrdersToRetrieve","http://schemas.datacontract.org/2004/07/DataObjects.Admin.Service");
    }
}
$ordersToRetrieve = new OrdersToRetrieve;

class GetOrdersRequest {
    public $Authentication;
    public $OrdersToRetrieve;
    
    function __construct( $authentication, $ordersToRetrieve ) {
        
        $this->Authentication = new \SoapVar( $authentication, SOAP_ENC_OBJECT, "-","--","Authentication","http://schemas.datacontract.org/2004/07/DataObjects.Admin.Service");
        $this->OrdersToRetrieve = new \SoapVar( $ordersToRetrieve, SOAP_ENC_OBJECT, "-","--","OrdersToRetrieve","http://schemas.datacontract.org/2004/07/DataObjects.Admin.Service");;
    }
}

$req = new GetOrdersRequest( $authentication, $ordersToRetrieve );
$params = new \SoapVar( $req, SOAP_ENC_OBJECT, "-", "--", "request", "http://tempuri.org/");


$adminService = new \Payment\SveaWebpay\AdminService;
//$params = array(
//    'Authentication' => array(
//        'Username' => 'sverigetest',
//        'Password' => 'sverigetest',        
//    ),
//    'OrdersToRetrieve' => array(
//        'GetOrderInformation' => array(
//            'ClientId' => 79021,
//            'SveaOrderId' => 310256
//        )
//    )
//);
//$adminData = $adminService->doRequest('GetOrders', $params);

$adminData = $adminService->doRequest('GetOrders', array( $params) );

?>
