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

class LookupPublicKeyById extends Base {
	function __construct() {
		parent::__construct('Fetch Public Key',
			array('id', 'from', 'secret'),
			'Lookup the public key for the given ID.');
	}

	function doRun() {
		$id = $this->getArgument(0);
		$from = $this->getArgument(1);
		$secret = $this->getArgument(2);

		Common::required($id, $from, $secret);
		//hash first

		//define connection settings
		$settings = new ConnectionSettings($from, $secret);

		//create a connection
		$connector = new Connection($settings);

		$result = $connector->fetchPublicKey($id);
		if($result->isSuccess()) {
			Common::l(Common::convertPublicKey($result->getPublicKey()));
		}
		else {
			Common::e($result->getErrorMessage());
		}
	}
}
