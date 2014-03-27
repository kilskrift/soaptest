<?php
//Create the client object
$soapclient = new SoapClient(   'http://www.webservicex.net/globalweather.asmx?WSDL', 
                                array(  'exceptions'=> 1,
                                        'trace' => 1,
                                        'soap_version' => SOAP_1_1 )
                                );

var_dump( $soapclient->__getFunctions());
var_dump( $soapclient->__getTypes());

//Use the functions of the client, the params of the function are in 
//the associative array
$params = array('CountryName' => 'Spain', 'CityName' => 'Alicante');
$response = $soapclient->getWeather($params);

print_r("getWeather(Spain, Alicante):");

echo "<xmp>";
print_r($soapclient->__getLastRequestHeaders());
print_r($soapclient->__getLastRequest());
echo "</xmp>";

var_dump($response);

?>