# OdbavTo\PresenterRoute
Routes for Nette single action presenters with HTTP methods support.

## TODO
- Parameters in URL


## Routes
```php
use OdbavTo\PresenterRoute\Route;
use Nette\Application\Routers\RouteList;

$router = new RouteList();

$router[] = new Route('/', HomepagePresenter::class);
```

## REST routes
Support for `GET`, `POST`, `PUT`, `DELETE`, `PATCH`, `HEAD`, `OPTIONS` HTTP methods.
```php
$router[] = RestRoute::get('/', HomepagePresenter::class);
// or
$router[] = new Route('/', HomepagePresenter::class, [IRequest::GET]);
```

```php
$router[] = new RestRoute::post('/', HomepagePresenter::class);
// or
$router[] = new Route('/', HomepagePresenter::class, [IRequest::POST]);
```

```php
$router[] = new RestRoute::put('/', HomepagePresenter::class);
// or
$router[] = new Route('/', HomepagePresenter::class, [IRequest::PUT]);
```

```php
$router[] = new RestRoute::delete('/', HomepagePresenter::class);
// or
$router[] = new Route('/', HomepagePresenter::class, [IRequest::DELETE]);
```

```php
$router[] = new RestRoute::patch('/', HomepagePresenter::class);
// or
$router[] = new Route('/', HomepagePresenter::class, [IRequest::PATCH]);
```

```php
$router[] = new RestRoute::head('/', HomepagePresenter::class);
// or
$router[] = new Route('/', HomepagePresenter::class, [IRequest::HEAD]);
```

```php
$router[] = new RestRoute::options('/', HomepagePresenter::class);
// or
$router[] = new Route('/', HomepagePresenter::class, [IRequest::OPTIONS]);
```

For multiple HTTP methods use `Route` and 3rd parameter:
```php
$router[] = new Route('/', HomepagePresenter::class, [IRequest::GET, IRequest::POST, IRequest::DELETE]);
```
