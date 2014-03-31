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
    
                'username' => 'sverigetest',
                'password' => 'sverigetest',
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
    'OrdersToRetrieve' => array(
        'GetOrderInformation' => array(
            'ClientId' => 79021,
            'SveaOrderId' => 310256
        )
    )
);
$adminData = $adminService->doRequest('GetOrders', $params);

//The header 'Action' from the namespace 'http://www.w3.org/2005/08/addressing' was not understood by the recipient of this message, causing the message to not be processed.  This error typically indicates that the sender of this message has enabled a communication protocol that the receiver cannot process.  Please ensure that the configuration of the client's binding is consistent with the service's binding.

?>
