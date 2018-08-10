<?php
ini_set('display_errors', 1); error_reporting(E_ALL);
session_start();

define( 'SITE_NAME', 'Hi Bot' ); //constant values
define( 'EMAIL_FROM', 'kkirmani@gmail.com' );
define( 'SMTP_HOST', 'smtp.gmail.com' );
define( 'SMTP_PORT', 25 );
define( 'SMTP_PASS', '' );
define( 'SMTP_ENCRYPTION', 'tls' );

global $con;

$con = mysqli_connect( 'localhost', 'root', 'JF!@#123*', 'hibot' );
if ( ! $con ) {
	die( "Connection failed" );
}

require_once 'functions.php';
