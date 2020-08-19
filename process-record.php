<?php
/**
 * @var string $dnsDomain
 * @var string $restUrl
 * @var string $apiKey
 * @var string $apiSecret
 */
include_once __DIR__ . "/config.php";
include_once __DIR__ . "/functions.php";
include_once __DIR__ . "/WebsupportRestApi.php";

if ( isset( $_POST['submit'] ) ) {
	makeValidation( $_POST );

	$requestParams = array(
		"type"    => $_POST['type'],
		"name"    => $_POST['address'],
		"content" => $_POST['content'],
	);

	if ( ! empty( $_POST['ttl'] ) ) {
		$requestParams['ttl'] = $_POST['ttl'];
	}

	if ( $_POST['type'] === "MX" ) {
		$requestParams['prio'] = $_POST['prio'];

	} else if ( $_POST['type'] === "SRV" ) {
		$requestParams['prio'] = $_POST['prio'];
		$requestParams['port']   = $_POST['port'];
		$requestParams['weight'] = $_POST['weight'];
	}

	$endpoint = "/user/self/zone/{$dnsDomain}/record/";

	$rest = new WebsupportRestApi( $restUrl, $apiKey, $apiSecret );

	try {
		$response = $rest->post( $endpoint, $requestParams );

		if ( isset( $response->status ) && $response->status === "error" ) {
			foreach ( $response->errors as $field => $error ) {
				$error = array_shift( $error );
				createNotice( "Error field {$field}: {$error}", "error" );
			}
			header( "Location: add-record.php" );
			die();
		}
		createNotice( "Record created successfully!", 'success' );
		header( "Location: index.php" );
		die();
	} catch ( Exception $e ) {
		createNotice( $e->getMessage(), 'error' );
		header( "Location: add-record.php" );
		die();
	}
}

/**
 * @param array $post
 */
function makeValidation( $post ) {
	$allowedParams = array(
		'type'    => array(
			'validation' => "Type is required",
			'required'   => true,
		),
		'address' => array(
			'validation' => "Address is required",
			'required'   => true,
		),
		'content' => array(
			'validation' => "Target address is required",
			'required'   => true,
		),
		'ttl'     => array(
			'required' => false,
		),
		'prio'    => array(
			'validation' => "Priority is required",
			'required'   => true,
			'depend_on'  => array( "MX", "SRV" )
		),
		'port'    => array(
			'validation' => "Port is required",
			'required'   => true,
			'depend_on'  => array( "SRV" )
		),
		'weight'  => array(
			'validation' => "Weight is required",
			'required'   => true,
			'depend_on'  => array( "SRV" )
		),
	);

	$validationMsg = "";

	if ( ! empty( $post['type'] ) ) {
		foreach ( $allowedParams as $key => $param ) {

			if ( empty( $post[ $key ] ) && $param['required'] && ! ( isset( $param['depend_on'] ) && ! in_array( $post['type'],
						$param['depend_on'] ) ) ) {
				$validationMsg .= $param['validation'] . "<br />";
			}
		}
	} else {
		$validationMsg .= $allowedParams['type']['validation'];
	}

	if ( ! empty( $validationMsg ) ) {
		createNotice( $validationMsg, "error" );
		header( "Location: add-record.php" );
		die();
	}
}