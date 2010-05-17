<?php
if(LOCAL == 'true')
{
    define('SERVER_ROOT', '/');
}
else
{
    define('SERVER_ROOT', '/');
}
//HELPERS
define('SERVER_MODELS_ROOT', SERVER_ROOT . 'models/');
define('SERVER_CONTROLLERS_ROOT', SERVER_ROOT . 'controllers/');
define('SERVER_VIEWS_ROOT', SERVER_ROOT . 'views/');
define('SERVER_PLUGINS_ROOT', SERVER_ROOT . 'plugins/');
define('SERVER_TESTS_ROOT', SERVER_ROOT . 'tests/');
define('SERVER_CONFIG_ROOT', SERVER_ROOT . 'config/');
//CONFIG
define('DB_CONFIG', SERVER_CONFIG_ROOT . 'db.php');
define('CONFIG', SERVER_CONFIG_ROOT . 'config.php');
define('CONSTANTS', SERVER_CONFIG_ROOT . 'constants.php');
define('ROUTES', SERVER_CONFIG_ROOT . 'routes.php');
//PQP
define('PQP', SERVER_PLUGINS_ROOT . 'pqp/pqp.php');
define('PQP_DISPLAY', 'display.php');
define('PQP_CONSOLE', 'console.php');
//MODELS
define('MODEL', SERVER_MODELS_ROOT . 'model.php');
define('USER_MODEL', SERVER_MODELS_ROOT . 'user/user.php');
define('PAYMENT_MODEL', SERVER_MODELS_ROOT . 'payment/payment.php');
define('EVENT_MODEL', SERVER_MODELS_ROOT . 'event/event.php');
define('DEPENDENT_MODEL', SERVER_MODELS_ROOT . 'dependent/dependent.php');
define('EXCEPTION_MODEL', SERVER_MODELS_ROOT . 'exception/exception_model.php');
define('API_MODEL', SERVER_MODELS_ROOT . 'api/api.php');
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
