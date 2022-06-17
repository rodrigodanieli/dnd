<?php

use Sohris\Core\Server;

include "vendor/autoload.php";

$app = new Server();

$app->setRootDir(__DIR__);
$app->loadingServer();