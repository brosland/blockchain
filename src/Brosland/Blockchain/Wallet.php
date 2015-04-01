<?php

namespace Brosland\Blockchain;

use Kdyby\Curl\Request,
	Nette\Utils\Json;

class Wallet extends \Nette\Object
{
	const BASE_URL = 'https://blockchain.info/merchant';


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
	private $addresses = array ();
	/**
	 * @var int
	 */
	private $balance = NULL;
	/**
	 * @var int
	 */
	private $minConfirmations = 0;
	/**
	 * @var bool
	 */
	private $debugMode = FALSE;


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
	 * @param bool $debugMode
	 */
	public function setDebugMode($debugMode)
	{
		$this->debugMode = $debugMode;

		return $this;
	}

	/**
	 * @return int
	 */
	public function getBalance($preferSource = FALSE)
	{
		if ($this->balance === NULL || $preferSource)
		{
			$this->balance = $this->sendRequest('balance')->balance; // not required second password
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
			$response = $this->sendRequest('list', array (
				'confirmations' => $this->minConfirmations
			)); // not required second password

			$this->addresses = array ();

			foreach ($response->addresses as $data)
			{
				$address = new Address($data->address, $data->balance, $data->total_received);
				$address->setLabel($data->label);

				$this->addresses[$data->address] = $address;
			}
		}

		return $this->addresses;
	}

	/**
	 * @param string $addressId
	 * @param bool $preferSource
	 * @return Address
	 */
	public function getAddress($addressId, $preferSource = FALSE)
	{
		if (!isset($this->addresses[$addressId]) || $preferSource)
		{
			$response = $this->sendRequest('address_balance', array (
				'address' => $addressId,
				'confirmations' => $this->minConfirmations
			)); // not required second password

			$address = new Address($response->address, $response->balance, $response->total_received);
			$this->addresses[$addressId] = $address;
		}

		return $this->addresses[$addressId];
	}

	/**
	 * @param string $label = NULL
	 * @return Address
	 */
	public function addAddress($label = NULL)
	{
		$response = $this->sendRequest('new_address', array ('label' => $label));

		$address = new Address($response->address);
		$address->setLabel($label);

		$this->addresses[$response->address] = $address;

		return $address;
	}

	/**
	 * @param string $addressId
	 * @param bool $archived
	 */
	public function setAddressArchived($addressId, $archived = TRUE)
	{
		$part = ($archived ? '' : 'un') . 'archive_address';

		$this->sendRequest($part, array ('address' => $addressId));

		if (isset($this->addresses[$addressId]))
		{
			$this->addresses[$addressId]->setArchived($archived);
		}
	}

	/**
	 * @param int $term Addresses which have not received any transactions in
	 * at least this many days will be consolidated.
	 * @return array Returns a list of archived addresses.
	 */
	public function consolidateAddresses($term = 60)
	{
		$response = $this->sendRequest('auto_consolidate', array ('days' => $term));

		foreach ($response->consolidated as $addressId)
		{
			if (isset($this->addresses[$addressId]))
			{
				$this->addresses[$addressId]->setArchived(TRUE);
			}
		}

		return $response->consolidated;
	}

	/**
	 * @param Transaction $transaction
	 * @return TransactionResponse
	 * @throws \Nette\InvalidArgumentException
	 */
	public function transfer(Transaction $transaction)
	{
		if (count($transaction->getRecipients()) == 0)
		{
			throw new \Nette\InvalidArgumentException('Please add at least one recipient.');
		}

		$parameters = array (
			'recipients' => Json::encode($transaction->getRecipients()), // TODO test
			'from' => $transaction->getFrom(),
			'note' => $transaction->getNote(),
			'fee' => $transaction->getFee() > 0 ? $transaction->getFee() : NULL
		);

		$response = $this->sendRequest('sendmany', $parameters);

		return new TransactionResponse($response->message, $response->tx_hash);
	}

	/**
	 * @param string $part
	 * @param array $parameters
	 * @return Json
	 */
	private function sendRequest($part = '', $parameters = array ())
	{
		$url = self::BASE_URL . '/' . $this->id . '/' . $part;
		$get = array_merge($parameters, array (
			'password' => $this->password,
			'second_password' => $this->password2
		));

		$request = new Request($url);
		$request->headers['Content-Type'] = 'application/x-www-form-urlencoded';
		$request->options['CONNECTTIMEOUT'] = 30;
		$request->options['TIMEOUT'] = 60;
		$request->options['CAINFO'] = __DIR__ . '/certificates/cacert.pem';

		$response = $request->get($get);
		$jsonResponse = Json::decode($response->getResponse());

		if (isset($jsonResponse->error))
		{
			throw new BlockchainException($jsonResponse->error);
		}

		if ($this->debugMode)
		{
			dump($response);
		}

		return $jsonResponse;
	}
}