<?php
 /**
 * @author Threema GmbH
 * @copyright Copyright (c) 2015 Threema GmbH
 */

namespace Threema\MsgApi\Messages;

/**
 * Abstract base class of messages that can be sent with end-to-end encryption via Threema.
 */
abstract class ThreemaMessage {

	/**
	 * @var int
	 */
	private $typeCode;

	/**
	 * @param int $typeCode
	 */
	function __construct($typeCode) {
		$this->typeCode = $typeCode;
	}

	/**
	 * Get the message type code of this message.
	 *
	 * @return int message type code
	 */
	final public function getTypeCode() {
		return $this->typeCode;
	}
}
