<?php

namespace Brosland\Blockchain;

class TransactionInput extends \Nette\Object
{
	/**
	 * @var array
	 */
	private $args;


	/**
	 * Returns new instance of transactionInput created from responce of https://blockchain.info/rawblock/$hash?format=json
	 * @param array $args
	 * @return Block
	 */
	public static function createFromArray($args)
	{
		if (isset($args['prev_out']))
		{
			$args['prev_out'] = TransactionOutput::createFromArray($args['prev_out']);
		}

		return new TransactionInput($args);
	}

	/**
	 * @param array $args
	 */
	private function __construct(array $args)
	{
		$this->args = $args;
	}

	/**
	 * @return string
	 */
	public function getSequence()
	{
		return $this->args['sequence'];
	}

	/**
	 * @return transactionOutput
	 */
	public function getPreviousOutput()
	{
		return isset($this->args['prev_out']) ? $this->args['prev_out'] : NULL;
	}

	/**
	 * @return string
	 */
	public function getScript()
	{
		return $this->args['script'];
	}
}