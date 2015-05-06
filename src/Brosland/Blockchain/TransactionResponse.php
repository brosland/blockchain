<?php

namespace Brosland\Blockchain;

class TransactionResponse extends \Nette\Object
{
	/**
	 * @var array
	 */
	private $response;


	/**
	 * @param string $response
	 */
	public function __construct($response)
	{
		$this->response = $response;
	}

	/**
	 * @return string
	 */
	public function getMessage()
	{
		return $this->response['message'];
	}

	/**
	 * @return string
	 */
	public function getHash()
	{
		return $this->response['tx_hash'];
	}

	/**
	 * @return string
	 */
	public function getNotice()
	{
		return isset($this->response['notice']) ? $this->response['notice'] : NULL;
	}
}