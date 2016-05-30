<?php

namespace Brosland\Blockchain;

use GuzzleHttp\Client,
	Nette\Utils\Json;

class Wallet extends \Nette\Object
{

	/**
	 * @var string
	 */
	private $id, $password, $password2;
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
	 * @var Client
	 */
	private $client;


	/**
	 * @param string $baseUrl
	 * @param string $id
	 * @param string $password
	 * @param string $password2
	 */
	public function __construct($baseUrl, $id, $password, $password2 = NULL)
	{
		$this->id = $id;
		$this->password = $password;
		$this->password2 = $password2;

		$this->client = new Client([
			'base_url' => $baseUrl . '/merchant/' . $this->id . '/',
			'Content-Type' => 'application/x-www-form-urlencoded',
			'CAINFO' => __DIR__ . '/certificates/cacert.pem'
		]);
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
			$response = $this->sendRequest('balance');
			$this->balance = $response['balance'];
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
				$this->addresses[$address['address']] = Address::createFromArray($address);
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
				'address' => $address, 'confirmations' => $this->minConfirmations
			]);

			$this->addresses[$address] = Address::createFromArray($response);
		}

		return $this->addresses[$address];
	}

	/**
	 * @param string $label = NULL
	 * @return Address
	 */
	public function addAddress($label = NULL)
	{
		$response = $this->sendRequest('new_address', ['label' => $label]);
		$response['balance'] = $response['total_received'] = 0;

		return Address::createFromArray($response);
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
	 * @param TransactionRequest $request
	 * @return TransactionResponse
	 * @throws \Nette\InvalidArgumentException
	 */
	public function transfer(TransactionRequest $request)
	{
		if (count($request->getRecipients()) == 0)
		{
			throw new \Nette\InvalidArgumentException('Recipient not found.');
		}

		$parameters = array_filter([
			'recipients' => Json::encode($request->getRecipients()),
			'from' => $request->getFrom(),
			'note' => $request->getNote(),
			'fee' => $request->getFee() > 0 ? $request->getFee() : NULL
		]);

		return TransactionResponse::createFromArray($this->sendRequest('sendmany', $parameters));
	}

	/**
	 * @param string $endpoint
	 * @param array $parameters
	 * @return array
	 * @throws BlockchainException
	 */
	private function sendRequest($endpoint, array $parameters = [])
	{
		$query = array_merge($parameters, [
			'password' => $this->password, 'second_password' => $this->password2
		]);

		try
		{
			$response = $this->client->get($endpoint, ['query' => $query]);
			$responceBody = Json::decode($response->getBody(), Json::FORCE_ARRAY);

			if (isset($responceBody['error']))
			{
				throw new BlockchainException($responceBody['error']);
			}

			return $responceBody;
		}
		catch (\GuzzleHttp\Exception\RequestException $ex)
		{
			throw new BlockchainException($ex->getMessage());
		}
	}
}