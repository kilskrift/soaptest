<?php
namespace Payment\SveaWebpay;
class AdminService
{
    protected $client;
        
// As creating a soapclient using the AdminService wdsl from https://partnerweb.sveaekonomi.se/WebPayAdminService_test/AdminService.svc?wsdl seems to confuse the php soapclient (it seem to alway use the soap 1.2. .../secure endpoint, which is currently not supported by the service) itself), 
 
// 1. We create the PHP SoapClient without specifying a wdsl, instead making the necessary settings in the arguments array. We make sure to set 'location' to the service SOAP 1.1. endpoint, .../backward, and 'use' OAP_LITERAL encoding, along with http header SOAP 1.1. encoding (text/xml).

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
        print_r($return);
        echo "<xmp>";
        echo $this->client->__getLastRequest() . "\n";
        echo $this->client->__getLastRequestHeaders();
        echo "</xmp>";
        echo "</pre>";
                
        return $return;
    }
}
 
// We now need to make sure that the service GetOrders request is made with the argument data passsed in well-formed xml. (I used the request XML from a call to the service made via SoapUI to ensure that the data is passed in the "correc" manner. (We're investigating why SoapUI can digest the service wdsl whereas php SOAP can't at the moment, so this is sort of a workaround for the time being.)

// 2. We build the complexType xml by using SoapVar on php objects, making sure we specify xml node names and relevant namespace for each node (this can perhaps be done more elegantly using i.e. classmap, even if specifying a wdsl upon soapclient instantiation?)

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

// The service functions and expected request format type can be fetched by instantiating a soapclient in wdsl mode and calling __getFunctions() and __getTypes, see the companion file dump_admin_service_functions_and_types.php.
// 
// 3. We build the outer request, making sure that we follow the chosen soapActoin type and format (i.e. GetOrdersRequest) given in the wdsl (make sure to include the authentication in the request soap body, not the soap header or the the soap request http header). 

$action = "GetOrders";
$params = new \SoapVar( $req, SOAP_ENC_OBJECT, "-", "--", "request", "http://tempuri.org/");

// do the request
$adminService = new \Payment\SveaWebpay\AdminService;
$adminData = $adminService->doRequest( $action, array( $params ) ); // 2nd arg should be array

?>
