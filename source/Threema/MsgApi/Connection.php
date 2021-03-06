<?php
 /**
 * @author Threema GmbH
 * @copyright Copyright (c) 2015 Threema GmbH
 */

namespace Threema\MsgApi;
use Threema\Core\Exception;
use Threema\Core\Url;
use Threema\MsgApi\Commands\CommandInterface;
use Threema\MsgApi\Commands\FetchPublicKey;
use Threema\MsgApi\Commands\LookupEmail;
use Threema\MsgApi\Commands\LookupPhone;
use Threema\MsgApi\Commands\Results\FetchPublicKeyResult;
use Threema\MsgApi\Commands\Results\LookupIdResult;
use Threema\MsgApi\Commands\Results\LookupPhoneResult;
use Threema\MsgApi\Commands\Results\Result;
use Threema\MsgApi\Commands\Results\SendSimpleResult;
use Threema\MsgApi\Commands\Results\SendE2EResult;
use Threema\MsgApi\Commands\SendSimple;
use Threema\MsgApi\Commands\SendE2E;

/**
 * Class Connection
 * @package Threema\MsgApi
 */
class Connection {
	/**
	 * @var string
	 */
	private $host = 'https://msgapi.threema.ch';

	/**
	 * @var \Threema\MsgApi\ConnectionSettings
	 */
	private $setting;

	/**
	 * @param \Threema\MsgApi\ConnectionSettings $setting
	 */
	function __construct(ConnectionSettings $setting) {
		$this->setting = $setting;
	}

	/**
	 * @param Receiver $receiver
	 * @param $text
	 * @return SendSimpleResult
	 */
	public function sendSimple(Receiver $receiver, $text) {
		$command = new SendSimple($receiver, $text);
		return $this->post($command);
	}

	/**
	 * @param Receiver $receiver
	 * @param $nonce
	 * @param $box
	 * @return SendE2EResult
	 */
	public function sendE2E(Receiver $receiver, $nonce, $box) {
		$command = new SendE2E($receiver, $nonce, $box);
		return $this->post($command);
	}

	/**
	 * @param $phoneNumber
	 * @return LookupIdResult
	 */
	public function keyLookupByPhoneNumber($phoneNumber) {
		$command = new LookupPhone($phoneNumber);
		return $this->get($command);
	}
	/**
	 * @param string $email
	 * @return LookupIdResult
	 */
	public function keyLookupByEmail($email) {
		$command = new LookupEmail($email);
		return $this->get($command);
	}

	/**
	 * @param $threemaId
	 * @return FetchPublicKeyResult
	 */
	public function fetchPublicKey($threemaId) {
		$command = new FetchPublicKey($threemaId);
		return $this->get($command);
	}
	private function createDefaultOptions() {
		return array(
			CURLOPT_RETURNTRANSFER => true
		);
	}

	/**
	 * @param array $params
	 * @return array
	 */
	private function processRequestParams(array $params) {
		if(null === $params) {
			$params = array();
		}

		$params['from'] = $this->setting->getThreemaId();
		$params['secret'] = $this->setting->getSecret();

		return $params;
	}

	/**
	 * @param CommandInterface $command
	 * @return Result
	 */
	protected function get(CommandInterface $command) {
		$params = $this->processRequestParams($command->getParams());
		return $this->call($command->getPath(),
			$this->createDefaultOptions(),
			$params,
			function($httpCode, $response) use($command) {
				return $command->parseResult($httpCode, $response);
			});
	}

	/**
	 * @param CommandInterface $command
	 * @return Result
	 */
	protected function post(CommandInterface $command) {
		$options = $this->createDefaultOptions();
		$params = $this->processRequestParams($command->getParams());

		$options[CURLOPT_POST] = true;
		$options[CURLOPT_POSTFIELDS] = http_build_query($params);
		$options[CURLOPT_HTTPHEADER] = array(
			'Content-Type: application/x-www-form-urlencoded');

		return $this->call($command->getPath(), $options, null, function($httpCode, $response) use($command) {
			return $command->parseResult($httpCode, $response);
		});

	}

	private function call($path, array $curlOptions, array $parameters = null, \Closure $result = null) {
		$fullPath = new Url('', $this->host);
		$fullPath->addPath($path);

		if(null !== $parameters && count($parameters)) {
			foreach($parameters as $key => $value) {
				$fullPath->setValue($key, $value);
			}
		}

		$session = curl_init($fullPath->getFullPath());
		curl_setopt_array($session, $curlOptions);

		$response = curl_exec($session);
		if(false === $response) {
			throw new Exception($path.' '.curl_error($session));
		}

		$httpCode = curl_getinfo($session, CURLINFO_HTTP_CODE);
		if(null === $result && $httpCode != 200) {
			throw new Exception($httpCode);
		}

		if(null !== $result) {
			return $result->__invoke($httpCode, $response);
		}
		else {
			return $response;
		}
	}
}
