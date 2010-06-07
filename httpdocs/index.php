<?php

require('../includes/core/walleye.php');

$app = Walleye::getInstance();
$app->setAppOptions($wAppOptions);
$app->setRoutes($wRoutes);
$app->setDbOptions($wDbConfig);
$app->run();

?>
