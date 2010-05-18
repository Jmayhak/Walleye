<?php

require('../config/config.php');

$app = Walleye::withOptions($WalleyeConfigOptions);
$app->start();

?>
