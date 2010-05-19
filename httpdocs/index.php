<?php

require('../includes/config.php');
require('../includes/core/walleye.php');

$app = Walleye::withOptions($wConfigOptions);
$app->run();

?>
