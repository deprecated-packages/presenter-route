<?php declare (strict_types = 1);

namespace OdbavTo\PresenterRoute;

use Nette;
use Nette\Application\Request;
use Nette\Http\IRequest;

class RestRouteList extends Nette\Application\Routers\RouteList
{
	const ALLOWED_METHODS_KEY = 'allowed_http_methods';

	/** @var array  */
	private $supportedHttpMethods = [];

	public function match(IRequest $httpRequest): ?Request
	{
		$appRequest = parent::match($httpRequest);
		if ($appRequest) {
			return $appRequest;
		}

		if ($httpRequest->getMethod() === IRequest::OPTIONS) {
			/** @var Route $route */
			foreach ($this as $route) {
				$route->allowOnceAllHttpMethods();
				$appRequest = $route->match($httpRequest);
				if ($appRequest !== NULL) {
					$this->addSupportedHttpMethods($route->supportedHttpMethods());
				}
			}
			return $this->createOptionsRequest();
		}

		return NULL;
	}


	/**
	 * @param mixed $index
	 * @param Route $route
	 * @throws RouteException
	 */
	public function offsetSet($index, $route)
	{
		if (!$route instanceof Route) {
			throw new RouteException('Argument must be ' . Route::class . ' descendant.');
		}

		parent::offsetSet($index, $route);
	}


	private function addSupportedHttpMethods(array $methods)
	{
		$this->supportedHttpMethods = array_unique(array_merge($this->supportedHttpMethods, $methods));
	}


	private function createOptionsRequest()
	{
		$params = [self::ALLOWED_METHODS_KEY => $this->supportedHttpMethods];
		return new Request(OptionsPresenter::class, IRequest::OPTIONS, $params);
	}
}