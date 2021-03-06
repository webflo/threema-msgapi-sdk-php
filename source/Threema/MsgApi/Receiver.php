<?php
 /**
 * @author Threema GmbH
 * @copyright Copyright (c) 2015 Threema GmbH
 */

namespace Threema\MsgApi;

class Receiver {
	const typeId = 'to';
	const typePhone = 'phone';
	const typeEmail = 'email';

	private $type = self::typeId;
	private $value;

	public function __construct($value, $type = self::typeId) {
		$this->value = $value;
		$this->type = $type;
	}

	/**
	 * @param string $threemaId
	 * @return $this
	 */
	public function setToThreemaId($threemaId) {
		return $this->setValue($threemaId,
			self::typeId);
	}

	/**
	 * @param string $phoneNo
	 * @return $this
	 */
	public function setToPhoneNo($phoneNo) {
		return $this->setValue($phoneNo,
			self::typePhone);
	}

	/**
	 * @param string $emailAddress
	 * @return $this
	 */
	public function setToEmail($emailAddress) {
		return $this->setValue($emailAddress,
			self::typeEmail);
	}

	/**
	 * @param string $value
	 * @param string $type
	 * @return $this
	 */
	private function setValue($value, $type) {
		$this->value = $value;
		$this->type = $type;
		return $this;
	}

	/**
	 * @return array
	 * @throws \InvalidArgumentException
	 */
	public function getParams() {
		switch($this->type)
		{
			case self::typeId:
				$to = $this->type;
				$this->value = strtoupper(trim($this->value));
				break;

			case self::typeEmail:
			case self::typePhone:
				$to = $this->type;
				break;
			default:
				throw new \InvalidArgumentException();
		}

		return array(
			$to => $this->value
		);
	}
}
