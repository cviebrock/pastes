<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Simply tell Laravel the HTTP verbs and URIs it should respond to. It is a
| breeze to setup your applications using Laravel's RESTful routing, and it
| is perfectly suited for building both large applications and simple APIs.
| Enjoy the fresh air and simplicity of the framework.
|
| Let's respond to a simple GET request to http://example.com/hello:
|
|		Route::get('hello', function()
|		{
|			return 'Hello World!';
|		});
|
| You can even respond to more than one URI:
|
|		Route::post('hello, world', function()
|		{
|			return 'Hello World!';
|		});
|
| It's easy to allow URI wildcards using (:num) or (:any):
|
|		Route::put('hello/(:any)', function($name)
|		{
|			return "Welcome, $name.";
|		});
|
*/
/*
|--------------------------------------------------------------------------
| Display the "new paste" view...
|--------------------------------------------------------------------------
*/

Route::get('/', array('as' => 'new', 'do' => function ()
{
	return View::make('new')->with('fork', '');
}));

/*
|--------------------------------------------------------------------------
| Create a new paste...
|--------------------------------------------------------------------------
*/

Route::post('/', function ()
{
	$errors = Paste::validate(Input::get());

	if (count($errors) > 0)
	{
		return Redirect::to_route('new')->with('errors', $errors);
	}

	$paste = new Paste(array('paste' => Input::get('paste')));

	$paste->save();

	return Redirect::to_route('paste', array(Math::to_base($paste->id)));
});

/*
|--------------------------------------------------------------------------
| View a raw paste...
|--------------------------------------------------------------------------
*/

Route::get('(:any)/raw', array('as' => 'raw', 'do' => function ($id)
{
	if ( ! is_null($paste = Paste::find($id = Math::to_base_10($id))))
	{
		return Response::make($paste->paste)->header('Content-Type', 'text/plain');
	}

	return Response::error('404');
}));

/*
|--------------------------------------------------------------------------
| Fork an existing paste...
|--------------------------------------------------------------------------
*/

Route::get('(:any)/fork', array('as' => 'fork', 'do' => function ($id)
{
	if ( ! is_null($paste = Paste::find($id = Math::to_base_10($id))))
	{
		return View::make('new')->with('fork', $paste->paste);
	}

	return Response::error('404');
}));

/*
|--------------------------------------------------------------------------
| View an existing paste...
|--------------------------------------------------------------------------
*/

Route::get('(:any)', array('as' => 'paste', 'do' => function ($id)
{
	$paste = Paste::find(Math::to_base_10($id));

	if (is_null($paste))
	{
		return Response::error('404');
	}

	$paste->increment('views');

	return View::make('view')->with('paste', htmlentities($paste->paste))
						    ->with('id', $id)
						    ->with('views', $paste->views . ' '. Str::plural('time', $paste->views) )
						    ->with('created',Time::ago($paste->created_at) );
}));

/*
|--------------------------------------------------------------------------
| Application 404 & 500 Error Handlers
|--------------------------------------------------------------------------
|
| To centralize and simplify 404 handling, Laravel uses an awesome event
| system to retrieve the response. Feel free to modify this function to
| your tastes and the needs of your application.
|
| Similarly, we use an event to handle the display of 500 level errors
| within the application. These errors are fired when there is an
| uncaught exception thrown in the application.
|
*/

Event::listen('404', function()
{
	return Response::error('404');
});

Event::listen('500', function()
{
	return Response::error('500');
});

/*
|--------------------------------------------------------------------------
| Route Filters
|--------------------------------------------------------------------------
|
| Filters provide a convenient method for attaching functionality to your
| routes. The built-in "before" and "after" filters are called before and
| after every request to your application, and you may even create other
| filters that can be attached to individual routes.
|
| Let's walk through an example...
|
| First, define a filter:
|
|		Route::filter('filter', function()
|		{
|			return 'Filtered!';
|		});
|
| Next, attach the filter to a route:
|
|		Router::register('GET /', array('before' => 'filter', function()
|		{
|			return 'Hello World!';
|		}));
|
*/

Route::filter('before', function()
{
	// Do stuff before every request to your application...
});

Route::filter('after', function($response)
{
	// Do stuff after every request to your application...
});

Route::filter('csrf', function()
{
	if (Request::forged()) return Response::error('500');
});

Route::filter('auth', function()
{
	if (Auth::guest()) return Redirect::to('login');
});
