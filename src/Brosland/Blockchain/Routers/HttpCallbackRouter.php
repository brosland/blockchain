<?php

namespace Brosland\Blockchain\Routers;

use Nette\Application\Request,
	Nette\Application\Routers\Route,
	Nette\Http\IRequest,
	Nette\Http\Url;

class HttpCallbackRouter extends \Nette\Object implements \Nette\Application\IRouter
{
	/**
	 * @var array
	 */
	public $onReceived = [];
	/**
	 * @var Route
	 */
	private $route;


	/**
	 * @param string $mask example 'blockchain-callback'
	 */
	public function __construct($mask)
	{
		$this->route = new Route($mask, function (IRequest $request)
		{
			$notification = new \Brosland\Blockchain\HttpCallback($request);

			$this->onReceived($notification);

			if ($notification->isAllowedToSendOkResponse())
			{
				return new \Nette\Application\Responses\TextResponse('*ok*');
			}
		});
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
		return $this->route->constructUrl($appRequest, $refUrl);
	}
}