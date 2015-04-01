<?php

namespace Brosland\Blockchain;

use Nette\Http\IRequest;

abstract class HttpCallback extends \Nette\Object
{
	/**
	 * @var IRequest
	 */
	protected $request;
	/**
	 * @var string $transactionHash
	 */
	protected $transactionHash = NULL;
	/**
	 * @var int
	 */
	protected $value = NULL;
	/**
	 * @var string
	 */
	protected $inputAddress = NULL;
	/**
	 * @var int
	 */
	protected $confirmations = NULL;
	/**
	 * @var bool
	 */
	protected $test = NULL;


	/**
	 * @param IRequest $request
	 */
	public function __construct(IRequest $request)
	{
		$this->request = $request;
	}

	public function __invoke()
	{
		$this->transactionHash = $this->request->getQuery('transaction_hash');
		$this->value = $this->request->getQuery('value');
		$this->inputAddress = $this->request->getQuery('input_address');
		$this->confirmations = $this->request->getQuery('confirmations');
		$this->test = $this->request->getQuery('test', FALSE);

		$this->onCallback();
	}

	public abstract function onCallback();
}