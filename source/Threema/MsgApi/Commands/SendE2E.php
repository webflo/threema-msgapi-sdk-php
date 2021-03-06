<?php
 /**
 * @author Threema GmbH
 * @copyright Copyright (c) 2015 Threema GmbH
 */

namespace Threema\MsgApi\Commands;
use Threema\MsgApi\Commands\Results\SendE2EResult;
use Threema\MsgApi\Receiver;

class SendE2E implements CommandInterface {
	/**
	 * @var string
	 */
	private $nonce;
	/**
	 * @var string
	 */
	private $box;
	/**
	 * @var \Threema\MsgApi\Receiver
	 */
	private $receiver;

	/**
	 * @param \Threema\MsgApi\Receiver $receiver
	 * @param string $nonce
	 * @param string $box
	 */
	function __construct(Receiver $receiver, $nonce, $box) {
		$this->nonce = $nonce;
		$this->box = $box;
		$this->receiver = $receiver;
	}

	public function getNonce() {
		return $this->nonce;
	}

	public function getBox() {
		return $this->box;
	}

	/**
	 * @return array
	 */
	function getParams() {
		$p = $this->receiver->getParams();
		$p['nonce'] = bin2hex($this->getNonce());
		$p['box'] = bin2hex($this->getBox());
		return $p;
	}

	function getPath() {
		return 'send_e2e';
	}

	/**
	 * @param int $httpCode
	 * @param object $res
	 * @return SendE2EResult
	 */
	function parseResult($httpCode, $res){
		return new SendE2EResult($httpCode, $res);
	}
}
