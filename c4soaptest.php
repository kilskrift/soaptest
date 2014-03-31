<?php
namespace Payment\SveaWebpay;
class AdminService
{
    protected $client;
 
    public function __construct()
    {
        $this->client = new \SoapClient(
            'https://partnerweb.sveaekonomi.se/WebPayAdminService_test/AdminService.svc?wsdl',
            array(
                'location' => "https://partnerweb.sveaekonomi.se/WebPayAdminService_Test/AdminService.svc/backward",
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
            echo $client->__getLastRequest() . "\n";
            echo $client->__getLastRequestHeaders();
            echo "</xmp>";
            echo "</pre>";
            die();       
        }
        return $return;
    }
}
 
$adminService = new \Payment\SveaWebpay\AdminService;
$params = array(
    'Authentication' => array(
        'Username' => 'sverigetest',
        'Password' => 'sverigetest',        
    ),
    'OrdersToRetrieve' => array(
        'GetOrderInformation' => array(
            'ClientId' => 79021,
            'SveaOrderId' => 310256
        )
    )
);
$adminData = $adminService->doRequest('GetOrders', $params);

//The formatter threw an exception while trying to deserialize the message: Error in deserializing body of request message for operation 'GetOrders'. End element 'Body' from namespace 'http://schemas.xmlsoap.org/soap/envelope/' expected. Found element 'param1' from namespace ''. Line 2, position 150.

?>
