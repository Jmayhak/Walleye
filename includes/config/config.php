<?php

$wConfigOptions = array(
	'BASE' => '',
    'PRODUCTION' => true,
    'LOCAL' => false,
    //Define the models found in includes/app/models/
    'MODELS' => array(
	    'USER' => 'models/user.php',
		'ADMIN' => 'models/admin.php'
	),
	//Define the controllers found in includes/app/controllers/
	'CONTROLLERS' => array(
		'ADMIN' => 'controllers/admin.php',
		'USER' => 'controller/user.php',
		'API' => 'controller/api.php',
    ),
    //Define the views found in includes/app/views/
    'VIEWS' => array(
	    'BASE_HEADER_VIEW' => 'views/_header.php',
		'BASE_FOOTER_VIEW' => 'views/_footer.php',
		'BASE_INDEX_VIEW' => 'views/index.php',
		'LOGIN_VIEW' => 'views/user/login.php',
		'MOBILE_VIEW' => 'views/mobile/mobile.php'
	)
);

?>
