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
	private $route;

	/**
	 * @var string
	 */
	private $presenterClassName;

	/**
	 * @var array|null
	 */
	private $supportedHttpMethods;


	public function __construct(string $route, string $presenterClassName , array $supportedHttpMethods = null)
	{
		$this->route = $route;
		$this->presenterClassName = $presenterClassName;
		$this->supportedHttpMethods = $supportedHttpMethods;
	}
	
	
	private function normalizePath(string $path): string
	{
		return trim($path, '/');
	}


	/**
	 * Maps HTTP request to a Request object.
	 *
	 * @param \Nette\Http\IRequest $httpRequest
	 *
	 * @return Request|NULL
	 */
	public function match(Nette\Http\IRequest $httpRequest)
	{
		$path = $this->normalizePath($httpRequest->getUrl()->getPath());

		if (!$this->isHttpMethodSupported($httpRequest->getMethod())) {
			return NULL;
		}

		if ($path !== $this->route) {
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
	 * @param Request $appRequest
	 * @param \Nette\Http\Url $refUrl
	 *
	 * @return NULL|string
	 * @throws \Exception
	 */
	function constructUrl(Request $appRequest, Nette\Http\Url $refUrl)
	{
		$baseUrl = $refUrl->getHostUrl();
		
		$path = preg_replace_callback('/<([\w]+)>/', function ($matches) use ($appRequest) {
			foreach ($matches as $match) {
				if ($match[0] === '<') {
					continue;
				}
				if ($value = $appRequest->getParameter($match)) {
					return $value;
				} else {
					throw new RouteException('Parameter ' . $match . ' is not defined in Request.');
				}
			}
		}, $this->route);
		
		if ($path === null) {
			throw new RouteException();
		}
		
		return $baseUrl . '/' . $path;
		
	}


	private function isHttpMethodSupported($httpMethod)
	{
		if (is_array($this->supportedHttpMethods)) {
			return in_array($httpMethod, $this->supportedHttpMethods, TRUE);
		}

		return TRUE;
	}
}