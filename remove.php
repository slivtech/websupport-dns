<?php
/**
 * @var string $dnsDomain
 * @var string $restUrl
 * @var string $apiKey
 * @var string $apiSecret
 */
if (isset($_GET['id']) && !empty($_GET['id']) && intval($_GET['id']) > 0) {
	include_once __DIR__ . "/config.php";
	include_once __DIR__ . "/functions.php";
	include_once __DIR__ . "/WebsupportRestApi.php";
	$id = intval($_GET['id']);
	$endpoint = "/user/self/zone/{$dnsDomain}/record/{$id}";

	$rest = new WebsupportRestApi( $restUrl, $apiKey, $apiSecret );

	try {
		$response = $rest->delete( $endpoint );
		createNotice("Record deleted successfully!", 'success');
	} catch ( Exception $e ) {
		createNotice($e->getMessage(), 'error');
	}

	header("Location: index.php");
	die();
} else {
	header("Location: 404.php");
	die();
}