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
		
		$route = $this->normalizePath($this->route);
		$route = str_replace('/', '\/', $route);
		
		// use named subpatterns to match params
		$routeRegex = preg_replace('/<[\w_-]+>/', '(?$0[\w_-]+)', 
			$route);
		$routeRegex = '@^' . $routeRegex . '$@';

		$result = preg_match($routeRegex, $path, $matches);
		if (!$result) {
			return NULL;
		}
		
		$params = $httpRequest->getQuery();
		if (is_array($matches) && count($matches) > 1) {
			$params += array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
		}
		
		return new Request(
			$this->presenterClassName,
			$httpRequest->getMethod(),
			$params,
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
	 * @throws RouteException
	 */
	function constructUrl(Request $appRequest, Nette\Http\Url $refUrl)
	{
		$baseUrl = $refUrl->getHostUrl();
		
		$path = preg_replace_callback('/<([\w_-]+)>/', function ($matches) use ($appRequest)
		{
			if (!isset($matches[1])) {
				throw new RouteException('There is something very wrong with matches: ' . var_export($matches, false));
			}
			$match = $matches[1];
			$value = $appRequest->getParameter($match);
			if ($value) {
				return $value;
			} else {
				throw new RouteException('Parameter ' . $match . ' is not defined in Request.');
			}
		}, $this->route);
		
		if ($path === null) {
			throw new RouteException('There was an error on constructing url with: ' . $this->route);
		}
		
		return $baseUrl . '/' . $path;
		
	}


	private function isHttpMethodSupported(string $httpMethod)
	{
		if (is_array($this->supportedHttpMethods)) {
			return in_array($httpMethod, $this->supportedHttpMethods, TRUE);
		}

		return TRUE;
	}
}