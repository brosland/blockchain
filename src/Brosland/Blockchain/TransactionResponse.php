<?php

namespace Brosland\Blockchain;

class TransactionResponse extends \Nette\Object
{
	/**
	 * @var string
	 */
	private $message;
	/**
	 * @var string
	 */
	private $hash;


	/**
	 * 
	 * @param string $message
	 * @param string $hash
	 */
	public function __construct($message, $hash)
	{
		$this->message = $message;
		$this->hash = $hash;
	}

	/**
	 * @return string
	 */
	public function getMessage()
	{
		return $this->message;
	}

	/**
	 * @return string
	 */
	public function getHash()
	{
		return $this->hash;
	}
}