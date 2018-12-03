<?php

ini_set( "display_errors", true);
date_default_timezone_set("Asia/Kolkata");

define( "DB_DSN", "mysql:host=localhost;dbname=cms" );
define( "DB_USERNAME", "" );
define( "DB_PASSWORD", "" );
define( "CLASS_PATH", "classes" );
define( "TEMPLATE_PATH", "templates" );
define( "HOMEPAGE_NUM_ARTICLES", 5 );
define( "ADMIN_USERNAME", "" );
define( "ADMIN_PASSWORD", "" );

require( CLASS_PATH . "/Article.php" );
require( CLASS_PATH . "/Comment.php" );

function handleException( $exception )
{
	echo "Sorry! An error occured." . $exception;
	error_log( $exception->getMessage() );
}

set_exception_handler( 'handleException' );

?>
