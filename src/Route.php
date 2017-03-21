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
	 * @param \Nette\Http\IRequest $httpRequest
	 *
	 * @return \Nette\Application\Request|NULL
	 */
	public function match(Nette\Http\IRequest $httpRequest)
	{
		if (!$this->isHttpMethodSupported($httpRequest->getMethod())) {
			return NULL;
		}

		return new Request(
			$this->presenterClassName,
			$httpRequest->getMethod(),
			$httpRequest->getQuery(),
			$httpRequest->getPost(),
			$httpRequest->getFiles(),
			[Request::SECURED => $httpRequest->isSecured()]
		);
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


	private function isHttpMethodSupported($httpMethod)
	{
		if (is_array($this->supportedHttpMethods)) {
			return in_array($httpMethod, $this->supportedHttpMethods, TRUE);
		}

		return TRUE;
	}
}