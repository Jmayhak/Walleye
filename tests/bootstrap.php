<?php
define('PRODUCTION', false);
define('DEVELOPMENT', true);
define('TESTING', false);
define('LOCAL', true);

require("../config/file_locations.php");
require(CONFIG);
require(ROUTES);
require(CONSTANTS);
require(MODEL);
require(CONTROLLER);
require(PQP);
//require_once PQP;
session_start();
?>
