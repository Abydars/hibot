<?php
error_reporting( 0 );
session_start();

define( 'SITE_NAME', 'Hi Bot' );
define( 'EMAIL_FROM', 'kkirmani@gmail.com' );
define( 'SMTP_HOST', 'smtp.gmail.com' );
define( 'SMTP_PORT', 25 );
define( 'SMTP_PASS', '' );
define( 'SMTP_ENCRYPTION', 'tls' );

global $con;

$con = mysqli_connect( 'localhost', 'root', '', 'hibot' );
if ( ! $con ) {
	die( "Connection failed" );
}

require_once 'functions.php';