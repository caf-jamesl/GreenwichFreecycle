<?php

namespace GreenwichFreecycle\Web\Utilities;

require_once (dirname(__DIR__). '/Utilities/Autoloader.php');

class AddressApiManagement
{
     public function getAddress($postcode)
     {
        $service_url = "https://api.postcodes.io/postcodes/" . $postcode;
        $curl = curl_init($service_url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $curl_response = curl_exec($curl);
        curl_close($curl);
        return json_decode($curl_response, true);
     }
}

?>