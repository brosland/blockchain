<?php

namespace Brosland\Blockchain\Routers;

use Nette\Http\Url,
	Nette\Application\Routers\Route,
	Nette\Http\IRequest,
	Nette\Application\Request;

class CallbackRouter extends \Nette\Object implements \Nette\Application\IRouter
{
	/**
	 * @var Route
	 */
	private $route;
	/**
	 * @var array
	 */
	public $onCallback = array ();


	/**
	 * @param string $mask example 'blockchain-callback'
	 */
	public function __construct($mask)
	{
		$this->route = new Route($mask, function ()
		{
			$this->onCallback();

			return new \Nette\Application\Responses\TextResponse('*ok*');
		});
	}

	/**
	 * @param callable $callback
	 */
	public function addCallback($callback)
	{
		$this->onCallback[] = $callback;
	}

	/**
	 * Maps HTTP request to a PresenterRequest object.
	 *
	 * @param IRequest $httpRequest
	 * @return Request|NULL
	 * @throws \Nette\InvalidStateException
	 */
	public function match(IRequest $httpRequest)
	{
		return $this->route->match($httpRequest);
	}

	/**
	 * Constructs absolute URL from Request object.
	 *
	 * @param Request $appRequest
	 * @param Url $refUrl referential URI
	 * @return string|NULL
	 */
	public function constructUrl(Request $appRequest, Url $refUrl)
	{
		$url = $this->route->constructUrl($appRequest, $refUrl);

		if ($url !== NULL)
		{
			if (is_string($url))
			{
				$url = new Url($url);
			}

			$url->setQuery('')->canonicalize();
		}

		return $url;
	}
}