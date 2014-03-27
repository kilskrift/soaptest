<?php

$wsdl_url = 'https://partnerweb.sveaekonomi.se/WebPayAdminService_test/AdminService.svc?wsdl';
$client = new SoapClient($wsdl_url);

echo "<pre>";
print_r($client->__getFunctions());
//   0 => string 'GetOrdersResponse GetOrders(GetOrders $parameters)' (length=50)

print_r($client->__getTypes());
//   18 => string 'struct GetOrdersRequest {
//    ArrayOfGetOrderInformation OrdersToRetrieve;
//   }'
//   19 => string 'struct BasicRequest {
//    Authentication Authentication;
//    ArrayOfSetting Settings;
//   }' (length=81)
//   20 => string 'struct Authentication {
//    string Password;
//    string Username;
//   }' (length=61)
//   21 => string 'struct ArrayOfSetting {
//    Setting Setting;
//   }' (length=43)
//   22 => string 'struct Setting {
//    string Name;
//    string Value;
//   }' (length=47)
//    //23 => string 'struct ArrayOfGetOrderInformation {
//    GetOrderInformation GetOrderInformation;
//   }' (length=79)
//   24 => string 'struct GetOrderInformation {
//    long ClientId;
//    OrderType OrderType;
//    long SveaOrderId;
//   }'

?>