<?php

namespace Brosland\Blockchain;

use Kdyby\Curl\CurlSender,
	Kdyby\Curl\Request,
	Nette\Utils\Json;

class Wallet extends \Nette\Object
{
	/**
	 * @var string
	 */
	private $id;
	/**
	 * @var string
	 */
	private $password;
	/**
	 * @var string
	 */
	private $password2;
	/**
	 * @var Address[]
	 */
	private $addresses = [];
	/**
	 * @var string
	 */
	private $balance = NULL;
	/**
	 * @var int
	 */
	private $minConfirmations = 0;
	/**
	 * @var string
	 */
	private $baseUrl;
	/**
	 * @var CurlSender
	 */
	private $sender;


	/**
	 * @param string $id
	 * @param string $password
	 * @param string $password2
	 */
	public function __construct($id, $password, $password2 = NULL)
	{
		$this->id = $id;
		$this->password = $password;
		$this->password2 = $password2;
		$this->baseUrl = Blockchain::URL . '/merchant';

		$this->sender = new CurlSender();
		$this->sender->headers['Content-Type'] = 'application/x-www-form-urlencoded';
		$this->sender->options['CAINFO'] = __DIR__ . '/certificates/cacert.pem';
	}

	/**
	 * @return string
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * @param int $minConfirmations
	 * @return self
	 */
	public function setMinConfirmations($minConfirmations)
	{
		$this->minConfirmations = $minConfirmations;

		return $this;
	}

	/**
	 * @param bool $preferSource
	 * @return string
	 */
	public function getBalance($preferSource = FALSE)
	{
		if ($this->balance === NULL || $preferSource)
		{
			$this->balance = $this->sendRequest('balance')['balance'];
		}

		return $this->balance;
	}

	/**
	 * @param bool $preferSource
	 * @return array
	 */
	public function getAddresses($preferSource = FALSE)
	{
		if (empty($this->addresses) || $preferSource)
		{
			$response = $this->sendRequest('list', ['confirmations' => $this->minConfirmations]);
			$this->addresses = [];

			foreach ($response['addresses'] as $address)
			{
				$this->addresses[$address['address']] = new Address($address);
			}
		}

		return $this->addresses;
	}

	/**
	 * @param string $address
	 * @param bool $preferSource
	 * @return Address
	 */
	public function getAddress($address, $preferSource = FALSE)
	{
		if (!isset($this->addresses[$address]) || $preferSource)
		{
			$response = $this->sendRequest('address_balance', [
				'address' => $address,
				'confirmations' => $this->minConfirmations
			]);

			$this->addresses[$address] = new Address($response);
		}

		return $this->addresses[$address];
	}

	/**
	 * @param string $label = NULL
	 * @return Address
	 */
	public function addAddress($label = NULL)
	{
		return $this->sendRequest('new_address', ['label' => $label])['address'];
	}

	/**
	 * @param string $address
	 * @param bool $archived
	 */
	public function setAddressArchived($address, $archived = TRUE)
	{
		$part = ($archived ? '' : 'un') . 'archive_address';

		$this->sendRequest($part, ['address' => $address]);
	}

	/**
	 * @param int $term Addresses which have not received any transactions in
	 * at least this many days will be consolidated.
	 * @return array Returns a list of archived addresses.
	 */
	public function consolidateAddresses($term = 60)
	{
		$response = $this->sendRequest('auto_consolidate', ['days' => $term]);

		return $response['consolidated'];
	}

	/**
	 * @param TransactionRequest $transaction
	 * @return TransactionResponse
	 * @throws \Nette\InvalidArgumentException
	 */
	public function transfer(TransactionRequest $transaction)
	{
		if (count($transaction->getRecipients()) == 0)
		{
			throw new \Nette\InvalidArgumentException('Recipient not found.');
		}

		$parameters = [
			'recipients' => Json::encode($transaction->getRecipients()),
			'from' => $transaction->getFrom(),
			'note' => $transaction->getNote(),
			'fee' => $transaction->getFee() > 0 ? $transaction->getFee() : NULL
		];

		return new TransactionResponse($this->sendRequest('sendmany', $parameters));
	}

	/**
	 * @param string $endpoint
	 * @param array $parameters
	 * @return array
	 */
	private function sendRequest($endpoint, array $parameters = [])
	{
		$url = $this->baseUrl . '/' . $this->id . '/' . $endpoint;
		$query = array_merge($parameters, [
			'password' => $this->password,
			'second_password' => $this->password2
		]);

		try
		{
			$request = new Request($url);
			$request->setSender($this->sender);

			$response = Json::decode($request->get($query)->getResponse(), Json::FORCE_ARRAY);

			if (isset($response['error']))
			{
				throw new BlockchainException($response['error']);
			}

			return $response;
		}
		catch (\Kdyby\Curl\CurlException $ex)
		{
			throw new BlockchainException($ex->getResponse()->getResponse());
		}
	}
}