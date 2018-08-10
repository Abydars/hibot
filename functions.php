<?php
require_once 'vendor/autoload.php';

use Phpml\Classification\SVC;
use Phpml\SupportVectorMachine\Kernel;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


if ( isset( $_GET['check'] ) ) {
	echo '<pre>';
	var_dump( getSamples( false, true ) );
	exit;
}
//tell is json or not [ = array { ob	ject json}
function isJson( $str )
{
	return strrpos( $str, '[' ) === 0 || strrpos( $str, '{' ) === 0;
}

function getUser( $user_id = false )
{
	global $con;

	$query  = "SELECT * FROM users;";
	if($user_id)
		$query  = "SELECT * FROM users WHERE user_id = '{$user_id}';";

	$result = $con->query( $query );
	$data   = $result->fetch_all( MYSQLI_ASSOC ); // return key value pair

	if ( count( $data ) == 1 ) {
		//$data[0]['username'] = getUserMetas( $data[0]['user_id'], 'reg-name' );
		return $data[0];
	}

	return $data;
}

function getUserMetas( $user_id, $key = false )
{
	global $con;

	$query = "SELECT * FROM user_meta WHERE user_id = '{$user_id}';";

	if ( $key ) {
		$query = "SELECT * FROM user_meta WHERE meta_key = '{$key}' AND user_id = '{$user_id}';";
	}

	$result      = mysqli_query( $con, $query );
	$meta_exists = $result->num_rows > 0;

	if ( ! $key ) {
		$results = [];

		while ( $row = $result->fetch_assoc() ) {
			$value                       = $row['meta_value'];
			$results[ $row['meta_key'] ] = isJson( $value ) ? json_decode( $value, true ) : $value; // parse php array
		}

		return $results;
	}

	if ( $meta_exists ) {
		$value = $result->fetch_assoc()['meta_value'];

		return json_decode( $value, true );
	}

	return false;
}

function getSamples( $column = false, $show_labels = false )
{
	global $con;

	$query   = "SELECT * FROM diseases";
	$result  = $con->query( $query );
	$samples = [];

	while ( $row = $result->fetch_assoc() ) {
		$query    = "SELECT ds.symptom_id, s.name FROM disease_symptoms AS ds JOIN symptoms AS s ON s.id=ds.symptom_id WHERE disease_id = '{$row['id']}'";
		$result_2 = mysqli_query( $con, $query );

		while ( $symptom = $result_2->fetch_assoc() ) {
			if ( $show_labels ) {
				$samples[ $row['id'] ]['data'][] = $symptom['name'];
			} else {
				$samples[ $row['id'] ]['data'][] = intval( $symptom['symptom_id'] );
			}
		}

		if ( ! empty( $samples[ $row['id'] ]['data'] ) ) { //if symptom exist
			$samples[ $row['id'] ]['label'] = $row['label']; //sysmtom exist then label set in sample varibale
		}
	}

	if ( $column ) {
		return array_column( $samples, $column );
	}

	return $samples;
}

function predict( $data )
{
	// return array();

	$classifier = new SVC(
		Kernel::LINEAR, // $kernel
		1.0,            // $costx
		3,              // $degree
		null,           // $gamma
		0.0,            // $coef0
		0.001,          // $tolerance
		100,            // $cacheSize
		true,           // $shrinking
		true            // $probabilityEstimates, set to true
	);

	$samples = getSamples( 'data' ); //samples = symptoms
	$labels  = getSamples( 'label' ); // labels = disease

	for($i = 0; $i < 6; $i ++) {
		$samples = array_merge($samples, $samples);
		$labels = array_merge($labels, $labels);
	}

	/*
	$most = 0;
	foreach ( $samples as $s ) {
		if ( count( $s ) > $most ) {
			$most = count( $s );
		}
	}
	foreach ( $samples as $k => $s ) {
		for ( $i = count( $s ); $i < $most; $i ++ ) {
			$samples[ $k ][ $i ] = $s[ ( $i % count( $s ) ) ];
		}
	}
	*/
	
	$samples = [[1, 3], [1, 4], [2, 4], [3, 1], [4, 1], [4, 2]];
	$labels = ['a', 'a', 'a', 'b', 'b', 'b'];

	$classifier->train( $samples, $labels );
	$predictions = $classifier->predictProbability( array_map( function ( $d ) {
		return intval( $d );
	}, $data ) );

	/*
	$has         = false;
	$percent     = 1;

	$predictions = array_map( function ( $prediction ) {
		$prediction = 1 - $prediction;

		return $prediction;
	}, $predictions );

	while ( $has == false ) {
		$predictions2 = array_filter( $predictions, function ( $prediction ) use ( &$percent ) {
			return $prediction > $percent;
		} );
		$has          = count( $predictions2 ) > 0;
		$percent      -= 0.1;

		if ( $has ) {
			$predictions = $predictions2;
		}

		if ( $percent < 0 ) {
			break;
		}
	}
	*/

	return $predictions;
}

function getSymptoms($disease_id = false)
{
	global $con;

	$query = "SELECT * FROM symptoms";
	if($disease_id) {
		$q = "SELECT symptom_id FROM disease_symptoms WHERE disease_id = '{$disease_id}'";
		$result = mysqli_query($con, $q);
		$ids = [];
		while ( $row = $result->fetch_assoc() ) {
			$ids[] = $row['symptom_id'];
		}
		if($ids) {
			$ids = implode(", ", $ids);
			$query = "SELECT * FROM symptoms WHERE id IN ({$ids})"; // bring all symptom where id exists in ids
		} else {
			return [];
		}
	}

	$symptoms = [];
	$result   = mysqli_query( $con, $query );

	while ( $row = $result->fetch_assoc() ) {
		$symptoms[] = $row;
	}

	return $symptoms;
}

function notification( $to, $subject, $text )
{
	$mail = new PHPMailer( true );                              // Passing `true` enables exceptions
	try {
		//Server settings
		$mail->SMTPDebug = 2;                                 // Enable verbose debug output
		$mail->isSMTP();                                      // Set mailer to use SMTP
		$mail->Host       = SMTP_HOST;  // Specify main and backup SMTP servers
		$mail->SMTPAuth   = true;                               // Enable SMTP authentication
		$mail->Username   = EMAIL_FROM;                 // SMTP username
		$mail->Password   = SMTP_PASS;                           // SMTP password
		$mail->SMTPSecure = SMTP_ENCRYPTION;                            // Enable TLS encryption, `ssl` also accepted
		$mail->Port       = SMTP_PORT;                                    // TCP port to connect to

		//Recipients
		$mail->setFrom( EMAIL_FROM, SITE_NAME );
		$mail->addAddress( $to );     // Add a recipient
		$mail->addReplyTo( EMAIL_FROM, SITE_NAME );

		//Content
		$mail->isHTML( true );                                  // Set email format to HTML
		$mail->Subject = $subject;
		$mail->Body    = $text;

		$mail->send();

		return true;
	} catch ( Exception $e ) {
		return $mail->ErrorInfo;
	}
}

function getDiseases( $id = false )
{
	global $con;

	$query  = "SELECT * FROM diseases;";
	if($id)
		$query  = "SELECT * FROM diseases WHERE id = '{$id}';";

	$result = $con->query( $query );
	$data   = $result->fetch_all( MYSQLI_ASSOC );

	if ( count( $data ) == 1 ) {
		return $data[0];
	}

	return $data;
}

function deleteDiseaseSymptomRelation($disease_id, $symptom_id) {
	global $con;

	if(!$symptom_id)
		$query = "DELETE FROM disease_symptoms WHERE disease_id = '{$disease_id}'";
	else if(!$disease_id)
		$query = "DELETE FROM disease_symptoms WHERE symptom_id = '{$symptom_id}'";
	else
		$query  = "DELETE FROM disease_symptoms WHERE disease_id = '{$disease_id}' AND symptom_id = '{$symptom_id}';";

	return $con->query( $query );
}

function createDiseaseSymptomRelation($disease_id, $symptom_id) {
	global $con;

	$query  = "INSERT INTO disease_symptoms (disease_id, symptom_id) VALUES('{$disease_id}', '{$symptom_id}');";
	return $con->query( $query );
}
