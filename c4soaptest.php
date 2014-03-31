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
                'username' => 'sverigetest',
                'password' => 'sverigetest',
                'exceptions'=> 1,
                'connection_timeout' => 60,
                'trace' => 1,
                'soap_version' => SOAP_1_2
            )
        );
    }
 
    public function doRequest($action, $data = array())
    {
        try {
            $actionHeader = new \SoapHeader('http://www.w3.org/2005/08/addressing', 'Action', 'http://tempuri.org/IAdminService/'.$action, true);
            //Add the headers into the client
            $this->client->__setSoapHeaders($actionHeader);
            $return = $this->client->__soapCall($action, $data);
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


//    [message:protected] => The message with To '' cannot be processed at the receiver, due to an AddressFilter mismatch at the EndpointDispatcher.  Check that the sender and receiver's EndpointAddresses agree.
//

?>
