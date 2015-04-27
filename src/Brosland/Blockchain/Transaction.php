<?php

namespace Brosland\Blockchain;

use DateTime;

class Transaction extends \Nette\Object
{
	const MIN_LOCK_TIME = 500000000;


	/**
	 * @var array
	 */
	private $args;
	/**
	 * @var array
	 */
	private $inputs = array ();
	/**
	 * @var array
	 */
	private $outputs = array ();


	/**
	 * Returns new instance of Transaction created from responce of https://blockchain.info/address/$address?format=json
	 * @param array $args
	 * @return Transaction
	 */
	public static function createFromArray($args)
	{
		$args['time'] = DateTime::createFromFormat('U', $args['time']);
		$args['lock_time'] >= self::MIN_LOCK_TIME ?
				DateTime::createFromFormat('U', $args['lock_time']) : NULL;

		$transaction = new Transaction($args);

		foreach ($transaction->args['inputs'] as $input)
		{
			$transaction->inputs[] = TransactionInput::createFromArray($input);
		}

		unset($transaction->args['inputs']);

		foreach ($transaction->args['out'] as $output)
		{
			$transaction->outputs[] = TransactionOutput::createFromArray($output);
		}

		unset($transaction->args['out']);

		return $transaction;
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
	public function getHash()
	{
		return $this->args['hash'];
	}

	/**
	 * @return string
	 */
	public function getIndex()
	{
		return $this->args['index'];
	}

	/**
	 * The block datetime at which this transaction is locked
	 * 
	 * @return DateTime
	 */
	public function getLockTime()
	{
		return $this->args['lock_time'];
	}

	/**
	 * @return int
	 */
	public function getSize()
	{
		return $this->args['size'];
	}

	/**
	 * @return string
	 */
	public function getRelayedBy()
	{
		return $this->args['relayed_by'];
	}

	/**
	 * @return int
	 */
	public function getBlockHeight()
	{
		return $this->args['block_height'];
	}

	/**
	 * @return int
	 */
	public function getVersion()
	{
		return $this->args['ver'];
	}

	/**
	 * @return TransactionInput[]
	 */
	public function getInputs()
	{
		return $this->inputs;
	}

	/**
	 * @return TransactionOutput[]
	 */
	public function getOutputs()
	{
		return $this->outputs;
	}
}