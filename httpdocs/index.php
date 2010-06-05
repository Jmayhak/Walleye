<?php

require('../includes/config/config.php');
require('../includes/config/routes.php');
require('../includes/config/db.php');
require('../includes/core/walleye.php');

$app = Walleye::getInstance();
$app->setAppOptions($wAppOptions);
$app->setRoutes($wRoutes);
$app->setDbOptions($wDbConfig);
$app->run();

?>
