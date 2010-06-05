<?php

$wAppOptions = array(
    'BASE' => '',
    'PRODUCTION' => true,
    'LOCAL' => false,
);

$wRoutes = array(
    '//login/' => 'cUser',
    '//logout/' => 'cUser',
    '//api/' => 'cApi',
    'default' => 'cSite'
);

require('../includes/core/walleye.php');

$app = Walleye::getInstance();
$app->setAppOptions($wAppOptions);
$app->setRoutes($wRoutes);
$app->setDbOptions($wDbConfig);
$app->run();

?>
