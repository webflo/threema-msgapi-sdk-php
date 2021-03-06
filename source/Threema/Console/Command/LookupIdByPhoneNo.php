<?php
 /**
 * @author Threema GmbH
 * @copyright Copyright (c) 2015 Threema GmbH
 */

namespace Threema\Console\Command;

use Threema\Console\Common;
use Threema\MsgApi\Connection;
use Threema\MsgApi\ConnectionSettings;
use Threema\MsgApi\Tools\CryptTool;

class LookupIdByPhoneNo extends Base {
	function __construct() {
		parent::__construct('ID-Lookup By Phone Number',
			array('phoneNo', 'from', 'secret'),
			'Lookup the ID linked to the given phone number (will be hashed locally).');
	}

	function doRun() {
		$phoneNo = $this->getArgument(0);
		$from = $this->getArgument(1);
		$secret = $this->getArgument(2);

		Common::required($phoneNo, $from, $secret);
		//hash first

		//define connection settings
		$settings = new ConnectionSettings($from, $secret);

		//create a connection
		$connector = new Connection($settings);

		$result = $connector->keyLookupByPhoneNumber($phoneNo);;
		Common::required($result);
		if($result->isSuccess()) {
			Common::l($result->getId());
		}
		else {
			Common::e($result->getErrorMessage());
		}

	}
}
