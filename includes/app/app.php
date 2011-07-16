<?php

// configure the app
$appOptions = array();

// errors and logs are logged to the Logs table in the db
$appOptions['LOG_ERRORS'] = (getenv('LOG_ERRORS')) ? (boolean)getenv('LOG_ERRORS') : true;

// Enter the expiration time in days
$appOptions['REG_KEY_EXPIRE_TIME'] = (getenv('REG_KEY_EXPIRE_TIME')) ? getenv('REG_KEY_EXPIRE_TIME') : '7';

// The length a session lasts in days (php.ini controls the session variable)
$appOptions['SESSION_KEY_EXPIRE_TIME'] = (getenv('SESSION_KEY_EXPIRE_TIME')) ? getenv('SESSION_KEY_EXPIRE_TIME') : '1';

// if in \Walleye\Walleye::PRODUCTION, no php warning/errors will be shown
$appOptions['ENVIRONMENT'] = (getenv('ENVIRONMENT')) ? getenv('ENVIRONMENT') : \Walleye\Walleye::PRODUCTION;

// Print out information about the application on GET and POST (WILL STOP REDIRECT)
$appOptions['PRINT_APP_INFO_ON_LOAD'] = (getenv('PRINT_APP_INFO_ON_LOAD')) ? (boolean)getenv('PRINT_APP_INFO_ON_LOAD') : false;

// The name emails are masked as from
$appOptions['EMAIL_FROM'] = (getenv('EMAIL_FROM')) ? getenv('EMAIL_FROM') : '';

// the google api key for retrieving jquery and jquery ui
$appOptions['GOOGLE_API_KEY'] = (getenv('GOOGLE_API_KEY')) ? getenv('GOOGLE_API_KEY') : '';

// the version of the application
$appOptions['VERSION'] = '0.0.0.0';

/* End of file */
