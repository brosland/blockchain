<?php

namespace Brosland\Blockchain;

use Nette\Http\IRequest;

class HttpCallback extends \Nette\Object
{
	/**
	 * @var IRequest
	 */
	private $request;
	/**
	 * @var bool
	 */
	private $sendOkResponse = TRUE;


	/**
	 * @param IRequest $request
	 */
	public function __construct(IRequest $request)
	{
		$this->request = $request;
	}

	/**
	 * @return IRequest
	 */
	public function getRequest()
	{
		return $this->request;
	}

	/**
	 * @return string
	 */
	public function getTransactionHash()
	{
		return $this->request->getQuery('transaction_hash');
	}

	/**
	 * @return string
	 */
	public function getValue()
	{
		return $this->request->getQuery('value');
	}

	/**
	 * @return string
	 */
	public function getInputAddress()
	{
		return $this->request->getQuery('input_address');
	}

	/**
	 * @return int
	 */
	public function getConfirmations()
	{
		return (int) $this->request->getQuery('confirmations');
	}

	/**
	 * @return bool
	 */
	public function isTest()
	{
		return (bool) $this->request->getQuery('test', FALSE);
	}

	/**
	 * @return bool
	 */
	public function isAllowedToSendOkResponse()
	{
		return $this->sendOkResponse;
	}

	/**
	 * @return self
	 */
	public function denyToSendOkResponse()
	{
		$this->sendOkResponse = FALSE;

		return $this;
	}
}