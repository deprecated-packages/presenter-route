<?php declare (strict_types = 1);

namespace OdbavTo\PresenterRoute;

use Nette;
use Nette\Http\IRequest;

class RestRouteList extends Nette\Application\Routers\RouteList
{
	const ALLOWED_METHODS_KEY = 'methods';

	private $mergedRoutes;


	public function match(IRequest $httpRequest): ?Nette\Application\Request
	{
		if ($httpRequest->getMethod() === IRequest::OPTIONS) {
			/** @var Route $route */
			foreach ($this as $route) {
				$appRequest = $route->match($httpRequest);
				if ($appRequest !== NULL) {
					$methods = $this->getMethodsForRoute($route->route());
					$appRequest->setParameters([self::ALLOWED_METHODS_KEY => $methods]);

					$appRequest->setPresenterName(OptionsPresenter::class);
					return $appRequest;
				}
			}
			return NULL;
		}

		return parent::match($httpRequest);
	}


	private function mergeRoute(string $route, array $supportedHttpMethods)
	{
		if (isset($this->mergedRoutes[$route])) {
			$this->mergedRoutes[$route] = $this->mergedRoutes[$route] + $supportedHttpMethods;
		} else {
			$this->mergedRoutes[$route] = $supportedHttpMethods;
		}
	}

	private function getMethodsForRoute(string $route): array
	{
		return isset($this->mergedRoutes[$route]) ? $this->mergedRoutes[$route] : [];
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

		$this->mergeRoute($route->route(), $route->supportedHttpMethods());

		parent::offsetSet($index, $route);
	}
}