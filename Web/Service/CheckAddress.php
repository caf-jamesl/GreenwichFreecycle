<?php

namespace GreenwichFreecycle\Web\Service;

error_reporting(0);

require_once (dirname(__DIR__). '/Utilities/Autoloader.php');

use GreenwichFreecycle\Web\Utilities\AddressApiManagement;

header('Content-type: text/plain');
$addressApiManagement = new AddressApiManagement;
$result = $addressApiManagement->getAddress($postcode);
if(!($result['status'] == 200))
{
exit('Notfound');
}
exit('Found');

?>