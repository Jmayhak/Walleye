<?php

// configure the database
$dbOptions = array();

// DEVELOPMENT
$dbOptions['ENGINE'] = (getenv('ENGINE')) ? getenv('ENGINE') : 'mysql';
$dbOptions['SERVER'] = (getenv('SERVER')) ? getenv('SERVER') : '';
$dbOptions['USER'] = (getenv('USER')) ? getenv('USER') : '';
$dbOptions['PASS'] = (getenv('PASS')) ? getenv('PASS') : '';
$dbOptions['DATABASE'] = (getenv('DATABASE')) ? getenv('DATABASE') : '';
$dbOptions['PORT'] = (getenv('PORT')) ? (int)getenv('PORT') : '';

/* End of file */
