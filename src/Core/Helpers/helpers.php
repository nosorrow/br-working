<?php

use Core\Libs\Config;
use Core\Libs\Uri;
use Core\Libs\Url;
use Core\Bootstrap\DiContainer;
use Core\Libs\Validator;
use Core\Libs\Csrf;
use Core\Bootstrap\Router;
use Core\Libs\View;
use Core\Libs\Request;
use Core\Libs\Support\HigherOrderTapProxy;

defined('APPLICATION_DIR') OR exit('No direct Accesss here !');

/**
 * @param  string  $version
 *
 * @return  bool
 */
function is_php($version = '5.0.0')
{
    static $phpVer;
    $version = (string) $version;

    if ( ! isset($phpVer[ $version ]))
    {
        $phpVer[ $version ] = (version_compare(PHP_VERSION, $version) < 0) ? false : true;
    }

    return $phpVer[ $version ];
}


/**
 *  DI Container
 */
if (!function_exists('app')) {
    // Прави инстанция на DI container;
    function app($name)
    {
        $container = new DiContainer();

        return $container->get($name);
    }

}

if (!function_exists('isClosure')) {
    /**
     * @param $suspected_closure
     * @return bool
     */
    function isClosure($suspected_closure) {
        if (is_callable($suspected_closure)){

            $reflection = new \ReflectionFunction($suspected_closure);
            return (bool) $reflection->isClosure();

        }

        return false;
    }
}

if (! function_exists('tap')) {
    /**
     * Call the given Closure with the given value then return the value.
     *
     * @param  mixed  $value
     * @param  callable|null  $callback
     * @return mixed
     */
    function tap($value, $callback = null)
    {
        if (is_null($callback)) {
            return new HigherOrderTapProxy($value);
        }

        $callback($value);

        return $value;
    }
}

/*--- URL ----*/

if (!function_exists('site_url')) {
    /**
     * Връща пълния URL
     * @param null $uri
     * @return mixed
     */
    function site_url($uri = null)
    {
        return app(Url::class)->getSiteUrl($uri);
    }
}

if (!function_exists('route')) {
    /**
     * @param $routename
     * @param array $params
     * @param null $request_method
     * @return mixed
     */
    function route($routename, array $params = [], $request_method = null)
    {
        $container = app(Router::class);

        return $container->route($routename, $params, $request_method)->route;
    }
}

if (!function_exists('redirect')) {

    /**
     * redirect()->to('home')->with('msg', 'Login Page')
     * redirect()->route('home')
     * redirect()->away('http://abv.bg')
     * redirect( 'ErrorPage/show/404' )
     * redirect( route(name, [agr, arg1]) )
     */
    function redirect($uri = null)
    {
        //$container = app(Uri::class);
        $container = app(\Core\Libs\Redirector::class);
        if ($uri !== null) {
            $container->to($uri);

        } else {
            return $container;
        }

    }
}

// ---     Request Helpers ---- //

if (!function_exists('request_post')) {

    /**
     * @param $name
     * @param null $normalize
     * @return mixed
     */
    function request_post($name, $normalize = null)
    {
        $container = app(Request::class);

        return $container->post($name, $normalize);
    }

}

if (!function_exists('request_get')) {

    /**
     * @param $name
     * @param null $normalize
     * @return mixed
     */
    function request_get($name, $normalize = null)
    {
        $container = app(Request::class);

        return $container->get($name, $normalize);
    }

}

if (!function_exists('set_cookie')) {

    /**
     * @param $name
     * @param $value
     * @return mixed
     */
    function set_cookie($name, $value)
    {
        $container = app(Request::class);

        $container->set_cookie($name, $value);
    }

}

if (!function_exists('get_cookie')) {

    /**
     * @param $name
     * @return mixed
     */
    function get_cookie($name)
    {
        $container = app(Request::class);

        return $container->cookie($name);
    }

}

/*  -- Validation Helpers --- */
if (!function_exists('oldValue')) {

    /**
     * При неуспешна валидация връща стойността на полето.
     * @param $field
     * @return mixed|null
     */
    function oldValue($field, $html_decode = true)
    {
        $Obj = Validator::getInstance();

        if ($Obj->hasErrors() === true) {
            return ($html_decode) ? htmlspecialchars_decode((string)$Obj->input->input($field)) : $Obj->input->input($field);

        }

		return '';
	}
}
/*
 * Check for errors
 */
if (!function_exists('has_error')) {
    /**
     * @param null $field
     * @return bool
     */
    function has_error($field = null)
    {
        $validator = app(Validator::class);

        return $validator->hasErrors($field);
    }
}

if (!function_exists('validation_error')) {

    /**
     * Показва съобщеие за грешки при валидация на форма
     * @param $field
     * @return string
     */
    function validation_error($field)
    {
        $Obj = Validator::getInstance();

        if ($Obj->hasErrors($field) === true) {

            return $Obj->errors($field, '', '', '<span style="color:#c9302c">%s</span>');

        } else {

            return '';
        }
    }
}

//---------------  Form Helpers ------------------------

if (!function_exists('csrf_field')) {

    function csrf_field()
    {
        $container = app(Csrf::class);

        $container->csrf_field();
    }
}

//-- --- Render View ---------------------------------
/**
 * setLayout('dashboard')->render('result', $data);
 */
if (!function_exists('setLayout')) {

    function setLayout($layout)
    {
        $container = app(View::class);

        return $container->setLayout($layout);
    }
}
/**
 * Render View
 */
if (!function_exists('view')) {

    function view($name, $data = [])
    {
        $container = app(View::class);

        $container->render($name, $data);

    }
}
/**
 *
 * recursive delete dir
 *
 */
if (!function_exists('rrmdir')) {

    function rrmdir($dir)
    {
        if (!realpath($dir)) {
            return false;
        }

        $files = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::CHILD_FIRST
        );

        foreach ($files as $fileinfo) {
            $todo = ($fileinfo->isDir() ? 'rmdir' : 'unlink');
            $todo($fileinfo->getRealPath());
        }
        if (rmdir($dir)) {
            return true;
        } else {
            return false;
        }
    }
}

/**
 * Auth functions
 */
if (!function_exists("passwordHash")) {
    /**
     * @param $str
     * @return bool|string
     */
    function passwordHash($str)
    {
        $opt = ["cost" => 10];
        return password_hash($str, PASSWORD_BCRYPT, $opt);
    }
}
