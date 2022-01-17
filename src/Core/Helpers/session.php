<?php
use Core\Libs\Session;

/* --- SESSION HELPERS ---- */
/**
 * get Session data from $_SESSION
 * Ako $name е масив или имаме стойност в value
 * то ще слагаме сеиия ($nama=>$value),
 *  иначе вземам сесия с име $name;
 */
if (!function_exists('sessionData')) {

    function sessionData($name = null, $data = null)
    {
        $session = app(Session::class);

        if ($name == null && $data == null){
            return $session->get_all();
        }

        if (is_array($name)) {
            return $session->store($name);

        } elseif ($name !== null && $data !== null) {
            return $session->store($name, $data);

        } else {
            return $session->getData($name);
        }

    }
}
/**
 * Get flash Session msg
 */
if (!function_exists('flash')) {

    function flash($name)
    {
        $session = app(Session::class);

        return $session->getFlash($name);
    }
}

/**
 *
 */
if (!function_exists('session_push')) {

    function session_push($name, $value)
    {
        $session = app(Session::class);

        $session->push($name, $value);
    }
}

/**
 *  Session pull
 * function returns and removes a key / value pai
 */
if (!function_exists('session_pull')) {

    function session_pull($name, $default = null)
    {
        $session = app(Session::class);

        return $session->pull($name, $default);
    }
}

/**
 *  Delete value from session
 */
if (!function_exists('session_delete')) {

    function session_delete($name, $default = null)
    {
        $session = app(Session::class);

        return $session->delete($name, $default);
    }
}
