<?php

// This is the database connection configuration.
return array(
	'connectionString' => 'sqlite:'.dirname(__FILE__).'/../data/testdrive.db',
	// uncomment the following lines to use a MySQL database
	'connectionString' => 'mysql:host=localhost;dbname=dbcoragged_sales',
        'emulatePrepare' => true,
        'username' => 'ragged_user_bd',
        'password' => 'p!hShRB7KnTp',
        'charset' => 'utf8',
);