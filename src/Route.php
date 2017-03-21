<?php declare (strict_types = 1);

namespace OdbavTo\PresenterRoute;


use Nette;
use Nette\Application\IRouter;
use Nette\Application\Request;

class Route implements IRouter
{
	/**
	 * @var string
	 */
	private $url;

	/**
	 * @var string
	 */
	private $presenterClassName;

	/**
	 * @var array|null
	 */
	private $supportedHttpMethods;


	public function __construct(string $url, string $presenterClassName , array $supportedHttpMethods = null)
	{
		$this->url = $url;
		$this->presenterClassName = $presenterClassName;
		$this->supportedHttpMethods = $supportedHttpMethods;
	}


	/**
	 * Maps HTTP request to a Request object.
	 *
	 * @return Request|NULL
	 */
	function match(Nette\Http\IRequest $httpRequest)
	{
		// TODO: Implement match() method.
	}


	/**
	 * Constructs absolute URL from Request object.
	 *
	 * @return string|NULL
	 */
	function constructUrl(Request $appRequest, Nette\Http\Url $refUrl)
	{
		// TODO: Implement constructUrl() method.
	}
}