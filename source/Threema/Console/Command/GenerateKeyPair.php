<?php
 /**
 * @author Threema GmbH
 * @copyright Copyright (c) 2015 Threema GmbH
 */

namespace Threema\Console\Command;

use Threema\Console\Common;
use Threema\MsgApi\Tools\CryptTool;

class GenerateKeyPair extends Base {
	function __construct() {
		parent::__construct('Generate Key Pair',
			array('privateKeyFile', 'publicKeyFile'),
			'Generate a new key pair and write the private and public keys to the respective files (in hex).');
	}

	function doRun() {
		$keyPair = CryptTool::getInstance()->generateKeyPair();

		$privateKeyHex = bin2hex($keyPair->privateKey);
		$publicKeyHex = bin2hex($keyPair->publicKey);

		file_put_contents($this->getArgument(0), Common::convertPrivateKey($privateKeyHex)."\n");
		file_put_contents($this->getArgument(1), Common::convertPublicKey($publicKeyHex)."\n");

		Common::l('keypair generated');
	}
}
