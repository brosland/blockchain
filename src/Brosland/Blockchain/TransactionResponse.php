<?php

namespace Brosland\Blockchain;

class TransactionResponse extends \Nette\Object
{

	/**
	 * @var array
	 */
	private static $REQUIRED = ['message', 'tx_hash'];
	/**
	 * @var array
	 */
	private $data;


	private function __construct()
	{
		
	}

	/**
	 * @param array $data
	 * @return TransactionResponse
	 */
	public static function createFromArray(array $data)
	{
		Utils::checkRequiredFields(self::$REQUIRED, $data);

		$response = new TransactionResponse();
		$response->data = $data;

		return $response;
	}

	/**
	 * @return string
	 */
	public function getMessage()
	{
		return $this->data['message'];
	}

	/**
	 * @return string
	 */
	public function getHash()
	{
		return $this->data['tx_hash'];
	}

	/**
	 * @return string
	 */
	public function getNotice()
	{
		return isset($this->data['notice']) ? $this->data['notice'] : NULL;
	}
}