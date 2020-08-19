<?php
class WebsupportRestApi {

	/**
	 * @var string
	 */
	protected $restUrl;

	/**
	 * @var string
	 */
	protected $apiVersion;

	/**
	 * @var string
	 */
	protected $apiKey;

	/**
	 * @var string
	 */
	protected $apiSecret;

	/**
	 * @var int
	 */
	protected $statusCode;

	/**
	 * WebsupportRestApi constructor.
	 *
	 * @param string $restUrl
	 * @param string $apiKey
	 * @param string $apiSecret
	 * @param string $apiVersion
	 */
	public function __construct( $restUrl, $apiKey, $apiSecret, $apiVersion = "v1" ) {
		$this->restUrl    = trim( $restUrl, "/" );
		$this->apiKey     = $apiKey;
		$this->apiSecret  = $apiSecret;
		$this->apiVersion = $apiVersion;
	}

	/**
	 * @param $endpoint
	 *
	 * @return mixed
	 * @throws Exception
	 */
	public function get( $endpoint ) {
		$response   = $this->request( 'GET', $endpoint );
		$statusCode = $this->getStatusCode();
		if ( $statusCode === 200 ) {
			return $response;
		} else {
			throw new Exception( $response->message ?? "Error processing your request" );
		}
	}

	/**
	 * @param $method
	 * @param $endpoint
	 * @param null $params
	 *
	 * @return mixed
	 */
	protected function request( $method, $endpoint, $params = null ) {

		$endpoint = sprintf( '/%s/%s', $this->apiVersion, trim( $endpoint, "/" ) );

		$ch   = curl_init();
		$time = time();
		curl_setopt( $ch, CURLOPT_CUSTOMREQUEST, $method );
		curl_setopt( $ch, CURLOPT_URL, $this->restUrl . $endpoint );
		curl_setopt( $ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC );

		if ( $this->apiKey !== null && $this->apiSecret !== null ) {
			$signature = $this->createSignature( $method, $endpoint, $time );
			curl_setopt( $ch, CURLOPT_USERPWD, $this->apiKey . ':' . $signature );
		}

		curl_setopt( $ch, CURLOPT_HTTPHEADER, [
			'Date: ' . gmdate( 'Ymd\THis\Z', $time ),
		] );

		if ( $params !== null ) {
			curl_setopt( $ch, CURLOPT_POSTFIELDS, json_encode( $params ) );
		}
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
		$response = json_decode( curl_exec( $ch ) );
		$this->setStatusCode( curl_getinfo( $ch, CURLINFO_HTTP_CODE ) );
		curl_close( $ch );

		return $response;
	}

	/**
	 * @param $method
	 * @param $endpoint
	 * @param $time
	 *
	 * @return string
	 */
	private function createSignature( $method, $endpoint, $time ): string {

		$canonicalRequest = sprintf( '%s %s %s', $method, $endpoint, $time );

		return hash_hmac( 'sha1', $canonicalRequest, $this->apiSecret );

	}

	/**
	 * @return int
	 */
	public function getStatusCode() {
		return $this->statusCode;
	}

	/**
	 * @param int $statusCode
	 */
	public function setStatusCode( $statusCode ): void {
		$this->statusCode = $statusCode;
	}

	/**
	 * @param $endpoint
	 * @param null $params
	 *
	 * @return mixed
	 * @throws Exception
	 */
	public function post( $endpoint, $params = null ) {
		$response   = $this->request( 'POST', $endpoint, $params );
		$statusCode = $this->getStatusCode();
		if ( $statusCode === 201 || $statusCode === 200 ) {
			return $response;
		} else {
			throw new Exception( $response->message ?? "Error processing your request" );
		}
	}

	/**
	 * @param $endpoint
	 *
	 * @return mixed
	 * @throws Exception
	 */
	public function delete( $endpoint ) {
		$response   = $this->request( 'DELETE', $endpoint );
		$statusCode = $this->getStatusCode();
		if ( $statusCode === 200 ) {
			return $response;
		} else {
			throw new Exception( $response->message ?? "Error processing your request" );
		}
	}

	public function put( $endpoint, $params = null ) {
		$response   = $this->request( 'PUT', $endpoint, $params );
		$statusCode = $this->getStatusCode();
		if ( $statusCode === 201 || $statusCode === 200 ) {
			return $response;
		} else {
			throw new Exception( $response->message ?? "Error processing your request" );
		}
	}
}