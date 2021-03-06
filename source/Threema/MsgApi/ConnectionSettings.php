<?php
 /**
 * @author Threema GmbH
 * @copyright Copyright (c) 2015 Threema GmbH
 */

namespace Threema\MsgApi;

class ConnectionSettings {
	/**
	 * @var string
	 */
	private $threemaId;

	/**
	 * @var string
	 */
	private $secret;

	/**
	 * @param string $threemaId valid threema id (8chars)
	 * @param string $secret
	 */
	function __construct($threemaId, $secret) {
		$this->threemaId = $threemaId;
		$this->secret = $secret;
	}

	/**
	 * @return string
	 */
	public function getThreemaId() {
		return $this->threemaId;
	}

	/**
	 * @return string
	 */
	public function getSecret() {
		return $this->secret;
	}
}
