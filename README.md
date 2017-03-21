# OdbavTo\PresenterRoute
Routes for Nette single action presenters with HTTP methods support.

## TODO
- Parameters in URL


## Routes
```php
use OdbavTo\PresenterRoute\Route;
use OdbavTo\PresenterRoute\RestRoute;
use Nette\Application\Routers\RouteList;

$router = new RouteList();

$router[] = new Route('/', HomepagePresenter::class);
$router[] = new Route('/', HomepagePresenter::class);
```

## REST routes
Support for `GET`, `POST`, `PUT`, `DELETE`, `PATCH`, `HEAD`, `OPTIONS` HTTP methods.
```php
use OdbavTo\PresenterRoute\RestRoute;
use Nette\Application\Routers\RouteList;
use Nette\Http\IRequest;

$router = new RouteList();

$router[] = RestRoute::get('/', HomepagePresenter::class);
// or
$router[] = new Route('/', HomepagePresenter::class, [IRequest::GET]);

$router[] = new RestRoute::post('/', HomepagePresenter::class);
// or
$router[] = new Route('/', HomepagePresenter::class, [IRequest::POST]);

$router[] = new RestRoute::put('/', HomepagePresenter::class);
// or
$router[] = new Route('/', HomepagePresenter::class, [IRequest::PIT]);

$router[] = new RestRoute::delete('/', HomepagePresenter::class);
// or
$router[] = new Route('/', HomepagePresenter::class, [IRequest::DELETE]);

$router[] = new RestRoute::patch('/', HomepagePresenter::class);
// or
$router[] = new Route('/', HomepagePresenter::class, [IRequest::PATCH]);

$router[] = new RestRoute::head('/', HomepagePresenter::class);
// or
$router[] = new Route('/', HomepagePresenter::class, [IRequest::HEAD]);

$router[] = new RestRoute::options('/', HomepagePresenter::class);
// or
$router[] = new Route('/', HomepagePresenter::class, [IRequest::OPTIONS]);
```

For multiple HTTP methods use `Route` and 3rd parameter:
```php
$router[] = new Route('/', HomepagePresenter::class, [IRequest::GET, IRequest::POST, IRequest::DELETE]);
```
