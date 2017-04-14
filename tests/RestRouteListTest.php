<?php

namespace OdbavTo\PresenterRoute\Tests;


use Nette\Http\IRequest;
use Nette\Http\UrlScript;
use OdbavTo\PresenterRoute\OptionsPresenter;
use OdbavTo\PresenterRoute\RestRoute;
use PHPUnit\Framework\TestCase;
use OdbavTo\PresenterRoute\RestRouteList;
use Nette\Http\Request as HttpRequest;
use Nette\Application\Request as AppRequest;

class RestRouteListTest extends TestCase
{
	/**
	 * @test
	 * @dataProvider matchProvider
	 */
	public function matchOptions(array $routes, string $url, array $methods)
	{
		$router = new RestRouteList();

		foreach ($routes as $route) {
			$router[] = $route;
		}

		$httpRequest = new HttpRequest(new UrlScript($url), null, null, null, null, null, IRequest::OPTIONS);

		$result = $router->match($httpRequest);

		$params = [RestRouteList::ALLOWED_METHODS_KEY => $methods];
		$expected = new AppRequest(OptionsPresenter::class, IRequest::OPTIONS, $params);

		$this->assertEquals($expected, $result);
	}


	public function matchProvider()
	{
		return [
			[[RestRoute::get('/me', 'me')], '/me', [IRequest::GET]],
			[[RestRoute::get('/me', 'me')], '/meh', []],
			[[RestRoute::get('/me', 'me'), RestRoute::post('/me', 'me')], '/me', [IRequest::GET, IRequest::POST]],
			[[RestRoute::get('/me', 'me'), RestRoute::post('/me/<id>', 'me')], '/me/he', [IRequest::POST]],
			[[RestRoute::get('/me/he', 'me'), RestRoute::post('/me/<id>', 'me')], '/me/he', [IRequest::GET, IRequest::POST]],
			[[RestRoute::get('/me/he', 'me'), RestRoute::get('/me/<id>', 'me')], '/me/he', [IRequest::GET]],
		];
	}


}