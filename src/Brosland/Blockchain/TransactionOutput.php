<?php

namespace Brosland\Blockchain;

class TransactionOutput extends \Nette\Object
{
	/**
	 * @var array
	 */
	private $args;


	/**
	 * Returns new instance of TransactionOutput created from responce of https://blockchain.info/rawblock/$hash?format=json
	 * @param array $args
	 * @return Block
	 */
	public static function createFromArray($args)
	{
		return new TransactionOutput($args);
	}

	/**
	 * @param array $args
	 */
	private function __construct(array $args)
	{
		$this->args = $args;
	}

	/**
	 * @return bool
	 */
	public function isSpent()
	{
		return $this->args['spent'];
	}

	/**
	 * @return int
	 */
	public function getTransactionIndex()
	{
		return $this->args['tx_index'];
	}
	
	/**
	 * @return int
	 */
	public function getType()
	{
		return $this->args['type'];
	}

	/**
	 * @return string
	 */
	public function getAddress()
	{
		return $this->args['addr'];
	}
	
	/**
	 * @return string
	 */
	public function getValue()
	{
		return $this->args['value'];
	}

	/**
	 * @return string
	 */
	public function getScript()
	{
		return $this->args['script'];
	}
}