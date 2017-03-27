<?php declare (strict_types = 1);

namespace OdbavTo\PresenterRoute;

class PathMatcher
{
	/**
	 * @var PathMatcher
	 */
	private static $instance;
	
	public static function getInstance(): PathMatcher
	{
		if (!self::$instance) {
			self::$instance = new PathMatcher();
		}
		return self::$instance;
	}
	
	private function __construct()
	{
	}


	private function normalizePath(string $path): string
	{
		return trim($path, '/');
	}
	
	/**
	 * @return array matched parameters
	 */
	public function match(string $route, string $path): ?array
	{
		$path = $this->normalizePath($path);
		
		$route = $this->normalizePath($route);
		$route = str_replace('/', '\/', $route);
		
		// use named subpatterns to match params
		$routeRegex = preg_replace('/<[\w_-]+>/', '(?$0[\w_-]+)', 
			$route);
		$routeRegex = '@^' . $routeRegex . '$@';

		$result = preg_match($routeRegex, $path, $matches);
		if (!$result) {
			return null;
		}
		
		if (is_array($matches) && count($matches) > 1) {
			return array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
		} else {
			return [];
		}
	}
	
	public function createUrl(string $route, array $parameters): string
	{
		$path = preg_replace_callback('/<([\w_-]+)>/', function ($matches) use ($parameters)
		{
			if (!isset($matches[1])) {
				throw new RouteException('There is something very wrong with matches: ' . var_export($matches, false));
			}
			$match = $matches[1];
			
			if (isset($parameters[$match])) {
				return $parameters[$match];
			} else {
				throw new RouteException('Parameter ' . $match . ' is not defined in Request.');
			}
		}, $route);
		
		return $path;
	}
}
