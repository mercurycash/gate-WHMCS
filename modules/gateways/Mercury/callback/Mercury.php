<?php

require_once(dirname(__FILE__) . '../Mercury.php');

use Mercury\Mercury;


$gatewayModule = basename(__FILE__, '.php');
$gateway = getGatewayVariables($gatewayModule);
if (!$gateway['type']) {
    die("Module Not Activated");
}

