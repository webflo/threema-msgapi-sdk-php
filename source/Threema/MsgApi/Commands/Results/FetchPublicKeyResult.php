<?php
 /**
 * @author Threema GmbH
 * @copyright Copyright (c) 2015 Threema GmbH
 */

namespace Threema\MsgApi\Commands\Results;

class FetchPublicKeyResult extends Result {

	/**
	 * @var string
	 */
	private $publicKey;

	/**
	 * @param string $response
	 */
	protected function processResponse($response) {
		$this->publicKey = (string)$response;
	}

	/**
	 * @return string
	 */
	public function getPublicKey() {
		return $this->publicKey;
	}

	/**
	 * @param int $httpCode
	 * @return string
	 */
	protected function getErrorMessageByErrorCode($httpCode)
	{
		switch($httpCode) {
			case 401:
				return 'API identity or secret incorrect';
			case 404:
				return 'No matching ID found';
			case 500:
				return 'A temporary internal server error has occurred';
			default:
				return 'Unknown error';
		}
	}


}
