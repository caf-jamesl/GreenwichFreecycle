<?php

namespace GreenwichFreecycle\Web\Controller;

require_once (dirname(__DIR__). '/Utilities/Autoloader.php');

use GreenwichFreecycle\Web\Business\UserManagement;

$userManagement = new UserManagement();
$userManagement->logout();
header('Location: Index.php');
exit();

?> 