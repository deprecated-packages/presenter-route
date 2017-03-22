<?php

namespace OdbavTo\Tests\PresenterRoute;

use PHPUnit\Framework\TestCase;
use OdbavTo\PresenterRoute\Route;
use Nette\Http\IRequest;
use Nette\Http\Request;

class RouteTest extends TestCase
{
	public function match($route, $url)
	{
		$httpRequest = new Request($url);
		$presenter = 'presenter';
		$return = (new Route($route, $presenter, [IRequest::GET] ))->match($httpRequest);
		
		$expected = new Request($presenter);
		
		$this->assertEquals($expected, $return);
	}
}
