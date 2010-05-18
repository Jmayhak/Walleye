<?php

$WalleyeConfigOptions = array(
    'PRODUCTION' => true,
    'DEVELOPMENT' => false,
    'TESTING' => false,
    'LOCAL' => false,
    'DB_SERVER' => '',
    'DB_USER' => '',
    'DB_PASS' => '',
    'DB_DATABASE' => ''


);

//MODELS
define('MODEL', SERVER_MODELS_ROOT . 'model.php');
define('USER_MODEL', SERVER_MODELS_ROOT . 'user/user.php');
define('ADMIN_MODEL', SERVER_MODELS_ROOT . 'admin/admin.php');
define('DB_SQL_CORE', SERVER_MODELS_ROOT . 'ez_sql_core.php');
define('DB_SQL_WRAPPER', SERVER_MODELS_ROOT . 'ez_sql_mysql.php');
//CONTROLLERS
define('CONTROLLER', SERVER_CONTROLLERS_ROOT . 'controller.php');
define('ADMIN_CONTROLLER', SERVER_CONTROLLERS_ROOT . 'admin/admin.php');
define('USER_CONTROLLER', SERVER_CONTROLLERS_ROOT . 'user/user.php');
define('API_CONTROLLER', SERVER_CONTROLLERS_ROOT . 'api/api.php');
//VIEWS
define('BASE_HEADER', SERVER_VIEWS_ROOT . '_header.php');
define('BASE_FOOTER', SERVER_VIEWS_ROOT . '_footer.php');
define('BASE_INDEX', SERVER_VIEWS_ROOT . 'index.php');
define('LOGIN_VIEW', SERVER_VIEWS_ROOT . 'user/login.php');
define('MOBILE_VIEW', SERVER_VIEWS_ROOT . 'mobile/mobile.php');

?>
